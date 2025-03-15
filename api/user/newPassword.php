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
$data = sanitize($data);

$token = $data['token'];
$newPassword = $data['userPassword'];

try {
    $CFG['link']->beginTransaction();
    $passwordPattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[\W_])(?=.{6,})/';
    if (!preg_match($passwordPattern, $newPassword)) {
        error('A senha deve ter pelo menos 6 caracteres e incluir pelo menos uma letra maiúscula, uma letra minúscula, um número e um caractere especial.');
    }

    $sql = "SELECT id, email FROM actUser WHERE email = (SELECT userEmail FROM passwordRecover WHERE token = :token AND validated = 1 AND NOW() < expiresAt)";
    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        error('Link de recuperação inválido ou expirado.');
    }

    $userId = $user['id'];
    $userEmail = $user['email'];

    $hashedPassword = hashPass($newPassword);
    $sql = "UPDATE actUser SET password = :newPassword WHERE id = :userId";
    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':newPassword', $hashedPassword, PDO::PARAM_STR);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $sql = "DELETE FROM passwordRecover WHERE token = :token";
    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();

    $out['message'] = 'Senha redefinida com sucesso.';

    newUserToken($userId);
    $CFG['link']->commit();
} catch (PDOException $e) {
} catch (PDOException $e) {
    $CFG['link']->rollBack();
    error('Falha ao tentar atualizar a senha!');
} catch (Exception $e) {
} catch (PDOException $e) {
    $CFG['link']->rollBack();
    error('Falha ao tentar atualizar a senha!');
}

echo json_encode($out);
