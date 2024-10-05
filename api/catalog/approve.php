<?php

require_once("../config.php");
require_once("../functions.php");

header('Content-Type: application/json; charset=utf-8');
$USERDATA = checkToken($_COOKIE['userToken']);

if (empty($USERDATA['userId'])) {
    error("Autenticação inválida ", 1);
}
if ($USERDATA['userType'] != 2) {
    error("Você não tem permissão para isso! ", 2);
}

$out = array('success' => true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    error('Método de requisição inválido');
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);
$catalogId = sanitize($data['catalogId'], 'int');
$currentStatus = sanitize($data['currentStatus'], 'int');
$isApproved = sanitize($data['isApproved'], 'int');
$newStatus = $currentStatus;
$aguardandoRevisao = 0;

if ($isApproved === 1) {
    $newStatus += 1;
} else {
    $newStatus += 2;
}

try {
    $sql = "UPDATE catalogo 
            SET Status = :newStatus,
            AguardandoRevisao = :aguardandoRevisao
            WHERE id = :catalogId";
    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':catalogId', $data['catalogId'], PDO::PARAM_INT);
    $stmt->bindParam(':aguardandoRevisao', $aguardandoRevisao, PDO::PARAM_INT);
    $stmt->bindParam(':newStatus', $newStatus, PDO::PARAM_INT);

    $stmt->execute();
} catch (PDOException $e) {
    error($e->getMessage());
} catch (Exception $e) {
    error($e->getMessage());
}

echo json_encode($out);
