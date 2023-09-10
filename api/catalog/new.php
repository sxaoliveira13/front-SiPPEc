<?php

//Tipo de resposta do servidor e codificação dos caracteres
header('Content-Type: application/json; charset=utf-8');

//Saída de dados
$out = array('success'=> true, 'data' => array());

//Resposta do servidor
echo json_encode($out);
