<?php

require_once("../config.php");
require_once("../functions.php");

//Tipo de resposta do servidor e codificação dos caracteres
header('Content-Type: application/json; charset=utf-8');

//Saída de dados
$out = array('success'=> true, 'data' => array());

//Se não for um POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    error('Método de requisição inválido', 1);
}

//Obtendo dados enviados via POST
$json = file_get_contents('php://input');
$data = json_decode($json, true);
$out['data'] = sanitize($data['key1']);

//Resposta do servidor
echo json_encode($out);
