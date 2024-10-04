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
    $sql = "SELECT c.id as catalogId, c.CategoriaId as categoryId, c.Titulo as title, Status as status, c.Conteudo as content, 
    c.Ambiente as ambient, c.Abordagem as approach, c.CaminhoDeAcesso as link, c.createdAt, 
    a.name as abilityName, t.name as toolName, p.name as publicName, u.id as userId, u.name as userName, u.email as userEmail, u.phone as userPhone, u.createTime as userCreatedAt FROM catalogo c 
    INNER JOIN ability a ON c.HabilidadeId = a.id 
    INNER JOIN tool t ON c.FerramentaId = t.id 
    INNER JOIN public p ON c.PublicoAlvoId = p.id 
    INNER JOIN actuser u ON c.userId = u.id 
    WHERE c.Status = 1 
    ORDER BY c.createdAt DESC";

    $stmt = $CFG['link']->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $out['data'] = $results;
} catch (PDOException $e) {
    error($e->getMessage());
} catch (Exception $e) {
    error($e->getMessage());
}

echo json_encode($out);
