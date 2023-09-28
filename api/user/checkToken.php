<?php

require_once("../config.php");
require_once("../functions.php");

header('Content-Type: application/json; charset=utf-8');
$USERDATA = checkToken($_COOKIE['userToken']) ?? [];
$out = array('success' => true, 'data' => sanitize($USERDATA));
if(empty($USERDATA)) $out['success'] = false;

echo $out;