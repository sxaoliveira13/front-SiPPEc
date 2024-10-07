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
$lastInsertedId;

try {
    if (strlen($data['userName']) > 128) {
        error('O nome de cadastro é muito grande.');
    }

    if (strlen($data['userName']) < 2) {
        error('O nome de cadastro é muito curto.');
    }

    if (strlen($data['userEmail']) > 128) {
        error('O endereço de email é muito grande');
    }

    if (!filter_var($data['userEmail'], FILTER_VALIDATE_EMAIL)) {
        error('Email inválido. Por favor, forneça um email válido.');
    }

    if (strlen($data['userPhone']) > 20) {
        error('Telefone inválido. Por favor, forneça um telefone válido.');
    }

    if (!preg_match('/^[0-9\s()-]*$/', $data['userPhone'])) {
        error('Telefone inválido. O telefone deve conter apenas dígitos e caracteres de formatação.');
    }

    $passwordPattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[\W_])(?=.{6,})/';
    if (!preg_match($passwordPattern, $data['userPassword'])) {
        error('A senha deve ter pelo menos 6 caracteres e incluir pelo menos uma letra maiúscula, uma letra minúscula, um número e um caractere especial.');
    }

    $sql = "SELECT id FROM actUser WHERE email = :userEmail";
    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':userEmail', $data['userEmail'], PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        error('Este email já está cadastrado!');
    }

    $data['userPassword'] = hashPass($data['userPassword']);

    $sql = "INSERT INTO actUser (name, email, phone, type, password) VALUES (:userName, :userEmail, :userPhone, 0, :userPassword)";
    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':userName', $data['userName'], PDO::PARAM_STR);
    $stmt->bindParam(':userEmail', $data['userEmail'], PDO::PARAM_STR);
    $stmt->bindParam(':userPhone', $data['userPhone'], PDO::PARAM_STR);
    $stmt->bindParam(':userPassword', $data['userPassword'], PDO::PARAM_STR);
    $stmt->execute();

    $lastInsertedId = $CFG['link']->lastInsertId();

    $out['data']['message'] = 'Usuário cadastrado com sucesso!';

    newUserToken($lastInsertedId);
} catch (PDOException $e) {
    error('Falha ao tentar cadastrar o usuário!');
} catch (Exception $e) {
    error('Falha ao tentar cadastrar o usuário!');
}

echo json_encode($out);
