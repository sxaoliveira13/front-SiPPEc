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
$data = sanitize($data);

if ((int)$USERDATA['userId'] !== (int)$data['userId']) {
    http_response_code(401);
    error("Autenticação inválida", 1);
}

$ambiente = $data['ambiente'] ?? null;
$abordagem = $data['abordagem'] ?? null;

$status = 1;
$awaitingResivision = 1;
$active = 0;

if ($USERDATA['userType'] == 2) {
    $status = 2;
    $awaitingResivision = 0;
    $active = 1;
}

$errorMsg;

if (strlen($data['titulo']) > 200) {
    $errorMsg = 'O título é muito grande!';
}

if (strlen($data['link']) > 200) {
    $errorMsg = 'O link de acesso é muito grande!';
}

if (!empty($error)) {
    error($errorMsg);
}

try {
    $sql = "INSERT INTO catalogo (userId, CategoriaId, Titulo, Ativo, Status, AguardandoRevisao, PublicoAlvoId, Conteudo, FerramentaId, HabilidadeId, Ambiente, Abordagem, CaminhoDeAcesso) VALUES (:userId, :categoria, :titulo, :active, :status, :aguardandoRevisao, :publico, :conteudo, :ferramenta, :habilidade, :ambiente, :abordagem, :link)";

    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':userId', $data['userId'], PDO::PARAM_INT);
    $stmt->bindParam(':categoria', $data['categoria'], PDO::PARAM_INT);
    $stmt->bindParam(':titulo', $data['titulo'], PDO::PARAM_STR);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':active', $active, PDO::PARAM_INT);
    $stmt->bindParam(':aguardandoRevisao', $awaitingResivision, PDO::PARAM_INT);
    $stmt->bindParam(':publico', $data['publico'], PDO::PARAM_INT);
    $stmt->bindParam(':conteudo', $data['conteudo'], PDO::PARAM_STR);
    $stmt->bindParam(':ferramenta', $data['ferramenta'], PDO::PARAM_INT);
    $stmt->bindParam(':habilidade', $data['habilidade'], PDO::PARAM_INT);
    $stmt->bindParam(':ambiente', $ambiente, PDO::PARAM_STR);
    $stmt->bindParam(':abordagem', $abordagem, PDO::PARAM_STR);
    $stmt->bindParam(':link', $data['link'], PDO::PARAM_STR);

    $stmt->execute();
} catch (PDOException $e) {
    error("Falha ao tentar cadastrar catálogo");
} catch (Exception $e) {
    error("Falha ao tentar cadastrar catálogo");
}

echo json_encode($out);
