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
$data = sanitize($data);

if ((int)$USERDATA['userId'] !== (int)$data['userId']) {
    http_response_code(401);
    error("Autenticação inválida", 1);
}

try {
    $sql = "SELECT 
        c.id AS catalogId,  
        COALESCE(ca.id, NULL) AS updatedCatalogId, 
        COALESCE(ca.CategoriaId, c.CategoriaId) AS categoryId, 
        c.Status AS status,
        c.Ativo AS active,
        c.lastUpdate AS lastUpdate,
        COALESCE(ca.Titulo, c.Titulo) AS title, 
        COALESCE(ca.Conteudo, c.Conteudo) AS content, 
        COALESCE(ca.Ambiente, c.Ambiente) AS ambient, 
        COALESCE(ca.Abordagem, c.Abordagem) AS approach, 
        COALESCE(ca.CaminhoDeAcesso, c.CaminhoDeAcesso) AS link, 
        COALESCE(ca.createdAt, c.createdAt) AS createdAt, 
        COALESCE(ca.PublicoAlvoId, c.PublicoAlvoId) AS publicId, 
        COALESCE(ca.FerramentaId, c.FerramentaId) AS toolId, 
        COALESCE(ca.HabilidadeId, c.HabilidadeId) AS abilityId 
    FROM catalogo AS c
    LEFT JOIN catalogoAtualizado AS ca ON ca.CatalogoId = c.id 
    WHERE c.userId = :userId AND c.Status != 8
    ORDER BY c.createdAt DESC";

    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':userId', $data['userId'], PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $out['data'] = $results;
} catch (PDOException $e) {
    error($e->getMessage());
} catch (Exception $e) {
    error($e->getMessage());
}


echo json_encode($out);
