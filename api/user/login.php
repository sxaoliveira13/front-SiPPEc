<?php

require_once("../config.php");
require_once("../functions.php");

header('Content-Type: application/json; charset=utf-8');
$out = array('success' => true, 'data' => []);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    error('Método de requisição inválido');
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$userEmail = sanitize($data['userEmail']);
$userPassword = hashPass($data['userPassword']);
$userId = 0;

try {
    $CFG['link']->beginTransaction();

    $sql = "SELECT id, email, loginAttempts, isLocked, unlockDate FROM actUser WHERE email = :userEmail";
    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':userEmail', $userEmail, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($user)) {
        error('Usuário e/ou senha incorretos!');
    }

    $userId = (int)$user['id'];

    if ($user['isLocked']) {
        $unlockDate = new DateTime($user['unlockDate']);
        $now = new DateTime();

        if ($now < $unlockDate) {
            $remainingTime = $unlockDate->diff($now);
            $waitMessage = ($remainingTime->h > 0 ? $remainingTime->h . ' horas e ' : '') . $remainingTime->i . ' minutos';
            error('Essa conta está bloqueada. Você deve aguardar mais ' . $waitMessage . ' para tentar novamente.');
        } else {
            $sql = "UPDATE actUser SET isLocked = 0, loginAttempts = 0, unlockDate = NULL WHERE id = :userId";
            $stmt = $CFG['link']->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
        }
    }

    $sql = "SELECT id, email FROM actUser WHERE email = :userEmail AND password = :userPassword";
    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':userEmail', $userEmail, PDO::PARAM_STR);
    $stmt->bindParam(':userPassword', $userPassword, PDO::PARAM_STR);
    $stmt->execute();

    $rs = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($rs['id'])) {
        $attempts = ((int)$user['loginAttempts']) + 1;

        if ($attempts >= 3) {
            $lockDuration = 120;
            $hours = floor($lockDuration / 60);
            $minutes = $lockDuration % 60;

            $unlockDate = (new DateTime())->add(new DateInterval('PT' . $lockDuration . 'M'));
            $unlockDate = $unlockDate->format('Y-m-d H:i:s');

            $sql = "UPDATE actUser SET isLocked = 1, unlockDate = :unlockDate, loginAttempts = :attempts WHERE id = :userId";
            $stmt = $CFG['link']->prepare($sql);
            $stmt->bindParam(':unlockDate', $unlockDate, PDO::PARAM_STR);
            $stmt->bindParam(':attempts', $attempts, PDO::PARAM_INT);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            $CFG['link']->commit();

            $waitMessage = '';
            if ($hours > 0) {
                $waitMessage .= $hours . ' hora' . ($hours > 1 ? 's' : '');
            }
            if ($minutes > 0) {
                if ($waitMessage) {
                    $waitMessage .= ' e ';
                }
                $waitMessage .= $minutes . ' minuto' . ($minutes > 1 ? 's' : '');
            }

            error('Essa conta foi bloqueada devido a múltiplas tentativas de login. Tente novamente em ' . $waitMessage . '.');
        } else {
            $sql = "UPDATE actUser SET loginAttempts = :attempts WHERE id = :userId";
            $stmt = $CFG['link']->prepare($sql);
            $stmt->bindParam(':attempts', $attempts, PDO::PARAM_INT);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            $CFG['link']->commit();

            error('Usuário e/ou senha incorretos! Tentativas restantes: ' . (3 - $attempts));
        }
    } else {
        $sql = "UPDATE actUser SET loginAttempts = 0, isLocked = 0, unlockDate = NULL, lastAccess = NOW() WHERE id = :userId";
        $stmt = $CFG['link']->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $out['data']['userEmail'] = $rs['email'];
    }

    $CFG['link']->commit();
} catch (PDOException $e) {
    $CFG['link']->rollBack();
    error($e->getMessage());
} catch (Exception $e) {
    $CFG['link']->rollBack();
    error($e->getMessage());
}

try {
    $key = randGen(69, "alphanum");
    $validity = date("Y-m-d H:i:s", (time() + $CFG['sessionValidity']));
    $hashPass = hashPass($key);

    $sql = "INSERT INTO actUserToken (id, userId, token, validity) 
                        VALUES (NULL, :userId, :token, :validity)";

    $stmt = $CFG['link']->prepare($sql);

    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':token', $hashPass, PDO::PARAM_STR);
    $stmt->bindParam(':validity', $validity, PDO::PARAM_STR);

    $rs = $stmt->execute();
    $tokenId = $CFG['link']->lastInsertId();
} catch (PDOException $e) {
    error($e->getMessage());
} catch (Exception $e) {
    error($e->getMessage());
}

if (!empty($tokenId)) {
    setcookie(
        'userToken',
        $tokenId . "_" . $key,
        [
            'expires' => time() + $CFG['sessionValidity'],
            'path' => '/',
            'domain' => '',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]
    );
}

echo json_encode($out);
