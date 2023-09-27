<?php

require_once("./config.php");
require_once("./functions.php");

header('Content-Type: application/json; charset=utf-8');

$json = file_get_contents('php://input');
$data = json_decode($json, true);

echo hashPass($_GET['pass']);