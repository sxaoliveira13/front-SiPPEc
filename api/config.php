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

//Variáveis em ambiente local e em produção
if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
    $CFG['system_url'] = 'http://localhost/sippec/';
    $CFG['host_mysql'] = 'localhost';
    $CFG['user_mysql'] = 'root';
    $CFG['pass_mysql'] = '';
    $CFG['db'] = 'sippec';
} else {
    $CFG['system_url'] = 'https://liag.ft.unicamp.br/act-sistema/sippec/';
    $CFG['host_mysql'] = 'liag.ft.unicamp.br';
    $CFG['user_mysql'] = 'liag';
    $CFG['pass_mysql'] = 'V2vWup3Pmd725S96';
    $CFG['db'] = 'liag';
}

//Salts para fortalecer as senhas
$CFG['salt0'] = '9tkvNCFzmS4lCjtK0HvV9Y';
$CFG['salt1'] = 'z1w3jfk50kK0HKRkBGhubT';
$CFG['salt2'] = 'StZiBxIeMRa5e0CqVyDcDh';

$CFG['db_options'] = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);

$CFG['link'] = new PDO("mysql:host={$CFG['host_mysql']};dbname={$CFG['db']};charset=UTF8",  $CFG['user_mysql'], $CFG['pass_mysql'], $CFG['db_options']);