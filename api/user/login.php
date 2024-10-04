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
    $sql = "select id, email from actUser where email = :userEmail and password = :userPassword";
    $stmt = $CFG['link']->prepare($sql);

    $stmt->bindParam(':userEmail', $userEmail, PDO::PARAM_STR);
    $stmt->bindParam(':userPassword', $userPassword, PDO::PARAM_STR);
    $stmt->execute();

    $rs = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($rs['id'])) {
        error('Usuário e/ou senha incorretos!');
    }

    $userId = (int)$rs['id'];
    $out['data']['userEmail'] = $rs['email'];
} catch (PDOException $e) {
    error('Falha ao tentar logar!');
} catch (Exception $e) {
    error('Falha ao tentar logar!');
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
