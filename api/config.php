<?php

//Reporta todos os erros
error_reporting(E_ALL);

//Exibe erros do PHP no console e na página html
ini_set('display_errors', '1');

//Medidas de segurança
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);

$CFG = array();

$CFG['systemUrl'] = 'https://liag.ft.unicamp.br/act-sistema/sippec/';

//Salts para fortalecer as senhas
$CFG['salt0'] = '9tkvNCFzmS4lCjtK0HvV9Y';
$CFG['salt1'] = 'z1w3jfk50kK0HKRkBGhubT';
$CFG['salt2'] = 'StZiBxIeMRa5e0CqVyDcDh';