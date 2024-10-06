<?php

require_once("../config.php");
require_once("../functions.php");

header('Content-Type: application/json; charset=utf-8');
$USERDATA = checkToken($_COOKIE['userToken']);

if (empty($USERDATA['userId'])) {
    error("Autenticação inválida ", 1);
}
if ($USERDATA['userType'] != 1 && $USERDATA['userType'] != 2) {
    error("Você não tem permissão para isso! ", 2);
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    error('Método de requisição inválido');
}

$out = array('success' => true);

$json = file_get_contents('php://input');
$data = json_decode($json, true);
$catalogId = sanitize($data['catalogId'], 'int');
$status = 7;
$awaitingRevision = 1;
$active = 1;

try {
    $sql = "SELECT id, Status as status FROM catalogo WHERE id = :catalogId AND userId = :userId";
    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':catalogId', $catalogId, PDO::PARAM_INT);
    $stmt->bindParam(':userId', $USERDATA['userId'], PDO::PARAM_INT);
    $stmt->execute();

    $catalog = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($catalog)) {
        error("Você não tem permissão para alterar este catálogo!");
    }

    if ($catalog['status'] == '1' || $USERDATA['userType'] == 2) {
        $status = 8;
        $awaitingRevision = 0;
        $active = 0;
    }

    $sql = "UPDATE catalogo 
    SET Status = :status,
    AguardandoRevisao = :awaitingRevision,
    Ativo = :active,
    Ciclo = Ciclo + 1
    WHERE id = :catalogId";
    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':status', $status, PDO::PARAM_INT);
    $stmt->bindParam(':active', $active, PDO::PARAM_INT);
    $stmt->bindParam(':catalogId', $catalogId, PDO::PARAM_INT);
    $stmt->bindParam(':awaitingRevision', $awaitingRevision, PDO::PARAM_INT);
    $stmt->execute();
} catch (PDOException $e) {
    error($e->getMessage());
} catch (Exception $e) {
    error($e->getMessage());
}

echo json_encode($out);
