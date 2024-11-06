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

$userEmail = sanitize($data['userRecoverEmail']);

try {
    // Verificar se o email está cadastrado
    $sql = "SELECT email FROM actUser WHERE email = :userEmail";
    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':userEmail', $userEmail, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($user)) {
        error('Falha ao tentar enviar email de recuperação.');
    }

    $recoverCode = random_int(100000, 999999);
    $codeIsUnique = false;

    while (!$codeIsUnique) {
        $sql = "SELECT recoveryCode FROM passwordRecover WHERE recoveryCode = :recoverCode";
        $stmt = $CFG['link']->prepare($sql);
        $stmt->bindParam(':recoverCode', $recoverCode, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            $codeIsUnique = true;
        } else {
            $recoverCode = random_int(100000, 999999);
        }
    }

    $token = hashPass($recoverCode);
    $expiresAt = (new DateTime())->add(new DateInterval('PT1H'))->format('Y-m-d H:i:s');

    $sql = "INSERT INTO passwordRecover (userEmail, recoveryCode, token, expiresAt) VALUES (:userEmail, :recoverCode, :token, :expiresAt)";
    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':userEmail', $userEmail, PDO::PARAM_STR);
    $stmt->bindParam(':recoverCode', $recoverCode, PDO::PARAM_STR);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->bindParam(':expiresAt', $expiresAt, PDO::PARAM_STR);
    $stmt->execute();

    sendMail(
        $userEmail,
        "SiPPeC - " . $recoverCode . ": Código de Recuperação de Senha",
        'Seu código de recuperação de senha é: <b>' . $recoverCode . "</b>",
        "Código de Recuperação de Senha: " . $recoverCode
    );

    $out['data']['token'] = $token;
} catch (PDOException $e) {
    error("Falha ao tentar enviar email de recuperação.");
} catch (Exception $e) {
    error("Falha ao tentar enviar email de recuperação.");
}

echo json_encode($out);
