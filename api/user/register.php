<?php

require_once("../config.php");
require_once("../functions.php");

header('Content-Type: application/json; charset=utf-8');
$out = array('success' => true, 'data' => []);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    error('Método de requisição inválido');
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);
$data = sanitize($data);
$data['userPassword'] = hashPass($data['userPassword']);
$lastInsertId;

try {
    $sql = "SELECT id FROM actUser WHERE email = :userEmail";
    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':userEmail', $data['userEmail'], PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        error('Este email já está cadastrado!');
    }

    $sql = "INSERT INTO actUser (name, email, phone, type, password) VALUES (:userName, :userEmail, :userPhone, 0, :userPassword)";
    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':userName', $data['userName'], PDO::PARAM_STR);
    $stmt->bindParam(':userEmail', $data['userEmail'], PDO::PARAM_STR);
    $stmt->bindParam(':userPhone', $data['userPhone'], PDO::PARAM_STR);
    $stmt->bindParam(':userPassword', $data['userPassword'], PDO::PARAM_STR);
    $stmt->execute();

    $lastInsertId = $CFG['link']->lastInsertId();

    $out['data']['message'] = 'Usuário cadastrado com sucesso!';
} catch (PDOException $e) {
    error('Falha ao tentar cadastrar o usuário!');
} catch (Exception $e) {
    error('Falha ao tentar cadastrar o usuário!');
}

try {
    $key = randGen(69, "alphanum");
    $validity = date("Y-m-d H:i:s", (time() + $CFG['sessionValidity']));
    $hashPass = hashPass($key);

    $sql = "INSERT INTO actUserToken (id, userId, token, validity) 
                        VALUES (NULL, :userId, :token, :validity)";

    $stmt = $CFG['link']->prepare($sql);

    $stmt->bindParam(':userId', $lastInsertId, PDO::PARAM_INT);
    $stmt->bindParam(':token', $hashPass, PDO::PARAM_STR);
    $stmt->bindParam(':validity', $validity, PDO::PARAM_STR);

    $rs = $stmt->execute();
    $tokenId = $CFG['link']->lastInsertId();
} catch (PDOException $e) {
    error($e->getMessage());
} catch (Exception $e) {
    error($e->getMessage());
}

if (!empty($tokenId)) {
    setcookie('userToken', $tokenId . "_" . $key, time() + $CFG['sessionValidity'], "/", "", true, true);
}


// require_once __DIR__ . '/../../vendor/autoload.php';

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// $mail = new PHPMailer(true);

// try {
//     $mail->isSMTP();                                      // Usar SMTP
//     $mail->Host = 'smtp.gmail.com';                       // Servidor SMTP
//     $mail->SMTPAuth = true;                               // Habilitar autenticação SMTP
//     $mail->Username = 'victor.costa.osses@gmail.com';               // Seu email
//     $mail->Password = '@VictorCosta1';                         // Senha de app que eu tenho que criar certinho ainda
//     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // Habilitar TLS
//     $mail->Port = 587;                                    // Porta do servidor SMTP

//     $mail->setFrom('victor.costa.osses@gmail.com', 'Victor Hugo Costa Osses');
//     $mail->addAddress('nemteenteressa2018@gmail.com', 'Osses');  // Adicionar destinatário

//     // Conteúdo do email
//     $mail->isHTML(true);                                  // Definir email como HTML
//     $mail->Subject = 'Assunto do email';
//     $mail->Body    = 'Este é o conteúdo do email em <b>HTML</b>';
//     $mail->AltBody = 'Este é o conteúdo do email em texto plano';  // Alternativa em texto plano

//     $mail->send();
// } catch (Exception $e) {
//     $out['msg'] = $e->getMessage();
//     error($e->getMessage());
// }

echo json_encode($out);
