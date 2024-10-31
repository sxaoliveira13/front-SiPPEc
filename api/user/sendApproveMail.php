<?php

require_once("../config.php");
require_once("../functions.php");

header('Content-Type: application/json; charset=utf-8');
$USERDATA = checkToken($_COOKIE['userToken']);

if (empty($USERDATA['userId'])) {
    http_response_code(401);
    error("Autenticação inválida ", 1);
}
if ($USERDATA['userType'] != 2) {
    http_response_code(403);
    error("Você não tem permissão para isso!");
}

$out = array('data' => array(), 'success' => true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    error('Método de requisição inválido');
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);
$userId = sanitize($data['userId'], 'int');

try {
    $sql = "SELECT email, name, type FROM actUser WHERE id = :userId";
    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $rs = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($rs)) {
        error('Falha ao tentar enviar email de confirmação/rejeição de cadastro!');
    }

    $approved = 'Aprovado';
    $emailBody = 'Seu cadastro na plataforma foi aprovado! Agora você pode contribuir com a disseminação do Pensamento Computacional acessando <a href="https://liag.ft.unicamp.br/act-sistema/sippec/catalog.php">https://liag.ft.unicamp.br/act-sistema/sippec/login.php</a>.<br><br>Agradecemos pela sua colaboração.';

    if ($rs['type'] === '3') {
        $approved = 'Negado';
        $emailBody = 'Lamentamos informar que sua solicitação de cadastro foi negada pelo administrador do sistema. Para mais informações, entre em contato com o professor <b>Marcos Augusto Francisco Borges</b> pelo email <b>maborges@unicamp.br</b>';
    }

    sendMail(
        $rs['email'],
        "SiPPeC - Cadastro " . $approved,
        $emailBody,
        $emailBody
    );
} catch (PDOException $e) {
    error('Falha ao tentar enviar email de confirmação/rejeição de cadastro!');
} catch (Exception $e) {
    error('Falha ao tentar enviar email de confirmação/rejeição de cadastro!');
}

echo json_encode($out);
