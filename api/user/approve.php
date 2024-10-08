<?php

require_once("../config.php");
require_once("../functions.php");

header('Content-Type: application/json; charset=utf-8');
$USERDATA = checkToken($_COOKIE['userToken']);

if (empty($USERDATA['userId'])) {
    http_response_code(401);
    error("Autenticação inválida ", 1);
}
if ($USERDATA['userType'] != 2) {
    http_response_code(403);
    error("Você não tem permissão para isso!");
}

$out = array('data' => array(), 'success' => true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    error('Método de requisição inválido');
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);
$userId = sanitize($data['userId'], 'int');
$isApproved = sanitize($data['isApproved'], 'int');

try {
    $sql = "UPDATE actuser SET type = :userType WHERE id = :userId";

    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':userType', $isApproved, PDO::PARAM_INT);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

    $stmt->execute();
} catch (PDOException $e) {
    error('Falha ao tentar aprovar/recusar usuário!');
} catch (Exception $e) {
    error('Falha ao tentar aprovar/recusar usuário!');
}

echo json_encode($out);
