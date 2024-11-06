<?php

require_once("functions.php");

ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);

$CFG = array();
if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
    $CFG['system_url'] = 'http://localhost/sippec/';
    $CFG['host_mysql'] = 'localhost';
    $CFG['user_mysql'] = 'root';
    $CFG['pass_mysql'] = '';
    $CFG['db'] = 'sippec';
} else {
    $CFG['system_url'] = 'https://liag.ft.unicamp.br/act-sistema/sippec/';
    $CFG['host_mysql'] = $_ENV['DB_HOST'];
    $CFG['user_mysql'] = $_ENV['DB_USERNAME'];
    $CFG['pass_mysql'] = $_ENV['DB_PASSWORD'];
    $CFG['db'] = $_ENV['DB_DATABASE'];
}

$CFG['sessionValidity'] = 3600 * 24 * 15; //15 days

$CFG['salt0'] = $_ENV['SALT0'];
$CFG['salt1'] = $_ENV['SALT1'];
$CFG['salt2'] = $_ENV['SALT2'];

$CFG['db_options'] = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);

try {
    $CFG['link'] = new PDO("mysql:host={$CFG['host_mysql']};dbname={$CFG['db']};charset=UTF8", $CFG['user_mysql'], $CFG['pass_mysql'], $CFG['db_options']);
} catch (PDOException $e) {
    error('Falha ao tentar realizar a conexão com o banco de dados');
} catch (Exception $e) {
    error('Falha ao tentar realizar a conexão com o banco de dados');
}

date_default_timezone_set('America/Sao_Paulo');
