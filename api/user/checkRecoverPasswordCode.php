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

$code = (int)$data['code'];
$token = sanitize($data['token']);

try {
    $sql = "SELECT id FROM passwordRecover WHERE recoveryCode = :code";
    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':code', $code, PDO::PARAM_STR);
    $stmt->execute();

    $rs = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($rs)) {
        error('Código de recuperação inválido.');
    }

    $sql = "UPDATE passwordRecover SET validated = 1 WHERE recoveryCode = :code";
    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':code', $code, PDO::PARAM_STR);
    $stmt->execute();
} catch (PDOException $e) {
    error("Não foi possível verificar o código de verificação.");
} catch (Exception $e) {
    error("Não foi possível verificar o código de verificação.");
}

echo json_encode($out);
