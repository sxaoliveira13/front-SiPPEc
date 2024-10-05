<?php

require_once("../config.php");
require_once("../functions.php");

header('Content-Type: application/json; charset=utf-8');
$USERDATA = checkToken($_COOKIE['userToken']);

if (empty($USERDATA['userId'])) {
    http_response_code(401);
    error("Autenticação inválida ", 1);
}
if ($USERDATA['userType'] != 1 && $USERDATA['userType'] != 2) {
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

if ((int)$USERDATA['userId'] !== $userId) {
    http_response_code(401);
    error("Autenticação inválida", 1);
}

try {
    $sql = "SELECT id as catalogId, CategoriaId as categoryId, Status as status, Titulo as title, Conteudo as content, 
    Ambiente as ambient, Abordagem as approach, CaminhoDeAcesso as link, createdAt, PublicoAlvoID as publicId, 
    FerramentaId as toolId, HabilidadeId as abilityId FROM catalogo
    WHERE userId = :userId AND Status != 8
    ORDER BY createdAt DESC";

    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $out['data'] = $results;
} catch (PDOException $e) {
    error($e->getMessage());
} catch (Exception $e) {
    error($e->getMessage());
}

echo json_encode($out);
