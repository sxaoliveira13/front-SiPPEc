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

try {
    $sqlUser = "SELECT id, name, email, phone, createTime, lastAccess FROM actuser WHERE id = :userId";
    $stmtUser = $CFG['link']->prepare($sqlUser);
    $stmtUser->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmtUser->execute();
    $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if ($userData) {
        $out['data']['user'] = $userData;


        $sqlCatalog = "SELECT CategoriaId, COUNT(CategoriaId) AS catalogCount
                       FROM catalogo
                       WHERE userId = :userId
                       GROUP BY CategoriaId";
        $stmtCatalog = $CFG['link']->prepare($sqlCatalog);
        $stmtCatalog->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmtCatalog->execute();
        $catalogData = $stmtCatalog->fetchAll(PDO::FETCH_ASSOC);


        $out['data']['catalogs'] = $catalogData;
    } else {
        $out['error'] = "Usuário não encontrado";
    }
} catch (PDOException $e) {
    error("Falha ao tentar recuperar dados do usuário.");
} catch (Exception $e) {
    error("Falha ao tentar recuperar dados do usuário.");
}


echo json_encode($out);
