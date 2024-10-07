<?php

require_once("../config.php");
require_once("../functions.php");

header('Content-Type: application/json; charset=utf-8');

$out = array('data' => array(), 'success' => true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    error('Método de requisição inválido');
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);
$data = sanitize($data);

$catalogType = $data['filters']['catalogType'] ?? null;
$catalogTitle = $data['filters']['catalogTitle'] ?? null;
$catalogContent = $data['filters']['catalogContent'] ?? null;
$catalogTool = $data['filters']['catalogTool'] ?? null;
$catalogPublic = $data['filters']['catalogPublic'] ?? null;
$catalogAbility = $data['filters']['catalogAbility'] ?? null;
$catalogEnvironment = $data['filters']['catalogEnvironment'] ?? null;
$catalogApproach = $data['filters']['catalogApproach'] ?? null;
$catalogLink = $data['filters']['catalogLink'] ?? null;

if (!in_array($catalogType, ['1', '2', '3'])) {
    http_response_code(400);
    error("Tipo de catálogo inválido");
}

try {
    $sql = "SELECT 
        c.CategoriaId AS categoryId, 
        c.Titulo AS title, 
        c.Status AS status, 
        c.Ativo AS active, 
        c.Conteudo AS content, 
        c.Ambiente AS ambient, 
        c.Abordagem AS approach, 
        c.CaminhoDeAcesso AS link, 
        c.createdAt, 
        a.name AS abilityName, 
        t.name AS toolName, 
        p.name AS publicName 
    FROM catalogo c 
    INNER JOIN ability a ON c.HabilidadeId = a.id 
    INNER JOIN tool t ON c.FerramentaId = t.id 
    INNER JOIN public p ON c.PublicoAlvoId = p.id 
    WHERE c.CategoriaId = :categoryId AND c.Ativo = 1 AND c.Status != 8";

    if ($catalogTitle) {
        $sql .= " AND c.Titulo LIKE :catalogTitle";
    }
    if ($catalogContent) {
        $sql .= " AND c.Conteudo = :catalogContent";
    }
    if ($catalogTool) {
        $sql .= " AND c.FerramentaId = :catalogTool";
    }
    if ($catalogPublic) {
        $sql .= " AND c.PublicoAlvoId = :catalogPublic";
    }
    if ($catalogAbility) {
        $sql .= " AND c.HabilidadeId = :catalogAbility";
    }
    if ($catalogEnvironment) {
        $sql .= " AND c.Ambiente = :catalogEnvironment";
    }
    if ($catalogApproach) {
        $sql .= " AND c.Abordagem = :catalogApproach";
    }
    if ($catalogLink) {
        $sql .= " AND c.CaminhoDeAcesso LIKE :catalogLink";
    }

    $sql .= " ORDER BY c.createdAt DESC";

    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':categoryId', $catalogType, PDO::PARAM_INT);

    if ($catalogTitle) {
        $catalogTitle = "%$catalogTitle%";
        $stmt->bindParam(':catalogTitle', $catalogTitle, PDO::PARAM_STR);
    }
    if ($catalogContent) {
        $stmt->bindParam(':catalogContent', $catalogContent, PDO::PARAM_STR);
    }
    if ($catalogTool) {
        $stmt->bindParam(':catalogTool', $catalogTool, PDO::PARAM_INT);
    }
    if ($catalogPublic) {
        $stmt->bindParam(':catalogPublic', $catalogPublic, PDO::PARAM_INT);
    }
    if ($catalogAbility) {
        $stmt->bindParam(':catalogAbility', $catalogAbility, PDO::PARAM_INT);
    }
    if ($catalogEnvironment) {
        $stmt->bindParam(':catalogEnvironment', $catalogEnvironment, PDO::PARAM_STR);
    }
    if ($catalogApproach) {
        $stmt->bindParam(':catalogApproach', $catalogApproach, PDO::PARAM_STR);
    }
    if ($catalogLink) {
        $catalogLink = "%$catalogLink%";
        $stmt->bindParam(':catalogLink', $catalogLink, PDO::PARAM_STR);
    }

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $out['data'] = $results;
} catch (PDOException $e) {
    error($e->getMessage());
} catch (Exception $e) {
    error($e->getMessage());
}

echo json_encode($out);
