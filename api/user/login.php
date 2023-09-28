<?php

require_once("../config.php");
require_once("../functions.php");

header('Content-Type: application/json; charset=utf-8');
$out = array('success' => true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    error('Método de requisição inválido');
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$userLogin = sanitize($data['userLogin']);
$userPassword = hashPass($data['userPassword']);
$userId = 0;

try {
    $sql = "select userId from actUser where userLogin = :userLogin and userPassword = :userPassword";
    $stmt = $CFG['link']->prepare($sql);

    $stmt->bindParam(':userLogin', $userLogin, PDO::PARAM_STR);
    $stmt->bindParam(':userPassword', $userPassword, PDO::PARAM_STR);
    $stmt->execute();

    $rs = $stmt->fetch(PDO::FETCH_ASSOC);
   
    if (empty($rs['userId'])) {
        error('Usuário e/ou senha incorretos!');
    }

    $userId = (int)$rs['userId'];
} catch (PDOException $e) {
    error('Falha ao tentar logar!');
} catch (Exception $e) {
    error('Falha ao tentar logar!');
}

try {
    $key = randGen(69, "alphanum");
    $validity = date("Y-m-d H:i:s", (time() + $CFG['sessionValidity']));
    $hashPass = hashPass($key);

    $sql = "INSERT INTO actUserToken (tokenId, userId, tokenKey, tokenValidity) 
                        VALUES (NULL, :userId, :tokenKey, :tokenValidity)";

    $stmt = $CFG['link']->prepare($sql);

    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':tokenKey', $hashPass, PDO::PARAM_STR);
    $stmt->bindParam(':tokenValidity', $validity, PDO::PARAM_STR);
    
    $rs = $stmt->execute();
    $tokenId = $CFG['link']->lastInsertId();
} catch(PDOException $e) {
    error($e->getMessage());
} catch(Exception $e) {
    error($e->getMessage());
}

if(!empty($tokenId)) {
    setcookie('userToken', $tokenId."_".$key, time() + $CFG['sessionValidity'], "/", "", true,true);
}

echo json_encode($out);