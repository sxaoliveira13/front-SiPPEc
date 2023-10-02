<?php

require_once("../config.php");
require_once("../functions.php");

header('Content-Type: application/json; charset=utf-8');
$out = array('success' => true);

try {
    deleteUserToken((int)(@explode('_', $_COOKIE['userToken'])[0]));
    unsetcookie('userToken');
} catch(Exception $e) {
    $out['success'] = false;
    $out['msg'] = $e->getMessage();
}

echo json_encode($out);