<?php

require_once("../config.php");
require_once("../functions.php");

//Tipo de resposta do servidor e codificação dos caracteres
header('Content-Type: application/json; charset=utf-8');

//Saída de dados
$out = array('success' => true);

//Se não for um POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    error('Método de requisição inválido');
}

//Obtendo dados enviados via POST
$json = file_get_contents('php://input');
$data = json_decode($json, true);
$data = sanitize($data);

//Tratando campos opcionais
$ambiente = $data['ambiente'] ?? null;
$abordagem = $data['abordagem'] ?? null;

try {
    $sql = "INSERT INTO catalogo (CategoriaId, Titulo, PublicoAlvoId, Conteudo, FerramentaId, HabilidadeId, Ambiente, Abordagem, CaminhoDeAcesso) VALUES (:categoria, :titulo, :publico, :conteudo, :ferramenta, :habilidade, :ambiente, :abordagem, :link)";

    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':categoria', $data['categoria'], PDO::PARAM_INT);
    $stmt->bindParam(':titulo', $data['titulo'], PDO::PARAM_STR);
    $stmt->bindParam(':publico', $data['publico'], PDO::PARAM_INT);
    $stmt->bindParam(':conteudo', $data['conteudo'], PDO::PARAM_STR);
    $stmt->bindParam(':ferramenta', $data['ferramenta'], PDO::PARAM_INT);
    $stmt->bindParam(':habilidade', $data['habilidade'], PDO::PARAM_INT);
    $stmt->bindParam(':ambiente', $ambiente, PDO::PARAM_STR);
    $stmt->bindParam(':abordagem', $abordagem, PDO::PARAM_STR);
    $stmt->bindParam(':link', $data['link'], PDO::PARAM_STR);

    $stmt->execute();
} catch (PDOException $e) {
    error('Falha ao tentar cadastrar!');
} catch (Exception $e) {
    error('Erro desconhecido!');
}

//Resposta do servidor
echo json_encode($out);
