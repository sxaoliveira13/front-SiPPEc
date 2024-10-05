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

$out = array('success' => true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    error('Método de requisição inválido');
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);
$data = sanitize($data);

$ambiente = $data['ambiente'] ?? null;
$abordagem = $data['abordagem'] ?? null;

try {
    $sql = "UPDATE catalogo 
            SET Titulo = :titulo, 
                PublicoAlvoId = :publico, 
                Conteudo = :conteudo, 
                FerramentaId = :ferramenta, 
                HabilidadeId = :habilidade, 
                Ambiente = :ambiente, 
                Abordagem = :abordagem, 
                CaminhoDeAcesso = :link 
            WHERE id = :catalogId";

    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':titulo', $data['titulo'], PDO::PARAM_STR);
    $stmt->bindParam(':publico', $data['publico'], PDO::PARAM_INT);
    $stmt->bindParam(':conteudo', $data['conteudo'], PDO::PARAM_STR);
    $stmt->bindParam(':ferramenta', $data['ferramenta'], PDO::PARAM_INT);
    $stmt->bindParam(':habilidade', $data['habilidade'], PDO::PARAM_INT);
    $stmt->bindParam(':ambiente', $data['ambiente'], PDO::PARAM_STR);
    $stmt->bindParam(':abordagem', $data['abordagem'], PDO::PARAM_STR);
    $stmt->bindParam(':link', $data['link'], PDO::PARAM_STR);
    $stmt->bindParam(':catalogId', $data['catalogId'], PDO::PARAM_INT);

    $stmt->execute();
} catch (PDOException $e) {
    error($e->getMessage());
} catch (Exception $e) {
    error($e->getMessage());
}

echo json_encode($out);
