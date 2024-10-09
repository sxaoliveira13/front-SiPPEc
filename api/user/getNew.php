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

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    error('Método de requisição inválido');
}

try {
    $sql = "SELECT * FROM actuser WHERE type = '0' ORDER BY createTime";
    $stmt = $CFG['link']->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $out['data'] = $results;
} catch (PDOException $e) {
    error("Falha ao tentar recuperar solicitações de cadastro de usuários.");
} catch (Exception $e) {
    error("Falha ao tentar recuperar solicitações de cadastro de usuários.");
}

echo json_encode($out);
