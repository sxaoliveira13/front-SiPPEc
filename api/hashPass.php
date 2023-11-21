<?php

require_once("./config.php");
require_once("./functions.php");

if(!isset($_COOKIE['userToken'])) {
    header("Location: login.php");
    exit;
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

echo hashPass($_GET['pass']);