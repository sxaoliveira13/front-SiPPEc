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
    $sql = "SELECT 
            c.id AS catalogId, 
            COALESCE(ca.CategoriaId, c.CategoriaId) AS categoryId, 
            COALESCE(ca.Titulo, c.Titulo) AS title, 
            c.Status AS status, 
            c.Ativo AS active, 
            c.Ciclo AS cycle,
            COALESCE(ca.Conteudo, c.Conteudo) AS content, 
            COALESCE(ca.Ambiente, c.Ambiente) AS ambient, 
            COALESCE(ca.Abordagem, c.Abordagem) AS approach, 
            COALESCE(ca.CaminhoDeAcesso, c.CaminhoDeAcesso) AS link, 
            c.createdAt, 
            c.AguardandoRevisao, 
            COALESCE(aAtualizado.name, a.name) AS abilityName, 
            COALESCE(tAtualizado.name, t.name) AS toolName, 
            COALESCE(pAtualizado.name, p.name) AS publicName, 
            u.id AS userId, 
            u.name AS userName, 
            u.email AS userEmail, 
            u.phone AS userPhone, 
            u.createTime AS userCreatedAt 
        FROM catalogo c 
        LEFT JOIN catalogoAtualizado ca ON ca.CatalogoId = c.id 
        LEFT JOIN ability aAtualizado ON ca.HabilidadeId = aAtualizado.id
        LEFT JOIN tool tAtualizado ON ca.FerramentaId = tAtualizado.id
        LEFT JOIN public pAtualizado ON ca.PublicoAlvoId = pAtualizado.id
        INNER JOIN ability a ON c.HabilidadeId = a.id 
        INNER JOIN tool t ON c.FerramentaId = t.id 
        INNER JOIN public p ON c.PublicoAlvoId = p.id 
        INNER JOIN actUser u ON c.userId = u.id 
        WHERE c.AguardandoRevisao = 1
        ORDER BY c.createdAt DESC";

    $stmt = $CFG['link']->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $out['data'] = $results;
} catch (PDOException $e) {
    error("Falha ao tentar obter novas solicitações de catálogos");
} catch (Exception $e) {
    error("Falha ao tentar obter novas solicitações de catálogos");
}

echo json_encode($out);
