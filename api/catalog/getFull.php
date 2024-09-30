<?php

require_once("../config.php");
require_once("../functions.php");

header('Content-Type: application/json; charset=utf-8');
$USERDATA = checkToken($_COOKIE['userToken']);

if (empty($USERDATA['userId'])) {error("Autenticação inválida ", 1);}
if ($USERDATA['userType'] != 1 && $USERDATA['userType'] != 2) {error("Você não tem permissão para isso! ", 2);}

$out = array('data' => array(), 'success' => true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    error('Método de requisição inválido');
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);
$data = sanitize($data);

try {
    $sql = "SELECT c.id as catalogId, c.CategoriaId as categoryId, c.Titulo as title, c.Conteudo as content, 
    c.Ambiente as ambient, c.Abordagem as approach, c.CaminhoDeAcesso as link, c.createdAt, 
    a.name as abilityName, t.name as toolName, p.name as publicName FROM catalogo c 
    INNER JOIN ability a ON c.HabilidadeId = a.id 
    INNER JOIN tool t ON c.FerramentaId = t.id 
    INNER JOIN public p ON c.PublicoAlvoId = p.id 
    WHERE c.userId = :userId AND c.Status = 1 
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
