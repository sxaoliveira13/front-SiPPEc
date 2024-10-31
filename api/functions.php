<?php

/**
 * Sanitize strings or arrays
 * @param $string
 * @param string $type
 * @return array|string|null
 */
function sanitize($string, string $type = "default")
{
    if (is_array($string)) {
        $temp = array();
        foreach ($string as $key => $value) {
            $temp[$key] = sanitize($value, $type);
        }
        return $temp;
    }
    if (empty($string)) {
        return "";
    }
    switch ($type) {
        case "default":
            return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        case "int":
            return (int) $string;
        case "email":
            return filter_var($string, FILTER_SANITIZE_EMAIL);
        case "url":
            return filter_var($string, FILTER_SANITIZE_URL);
        default:
            return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    return "";
}

/**
 * Redirect to the location
 * @param string $location
 */
function forceRedirect($location)
{
    global $CFG;
    $url = $CFG['system_url'] . $location;
    if (!headers_sent()) {
        header("location: {$url}");
    } else {
        echo "<script>window.location.href = '{$url}';</script>";
        echo "<script>window.location = '{$url}';</script>";
        echo "<script>window.location.replace('{$url}');</script>";
        echo "<META http-equiv='refresh' content='1;URL={$url}'>";
    }
    die;
}

/**
 * Generate secure passwords
 * @param string $pass
 * @return string
 */
function hashPass($pass)
{
    global $CFG;
    $pass = hash("sha512", $CFG['salt0'] . $pass . $CFG['salt1']);
    $pass = hash("sha512", $CFG['salt1'] . $pass . $CFG['salt2']);
    return $pass;
}

/**
 * Checks if the password recovery token exists and is valid
 * @param string $token
 * @return boolean
 */
function checkRecoverPasswordToken($token)
{
    global $CFG;

    try {
        $sql = "SELECT id, validated, expiresAt FROM passwordRecover WHERE token = :token";
        $stmt = $CFG['link']->prepare($sql);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();

        $recover = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$recover) {
            return ['success' => false, 'message' => 'Link de recuperação inválido.'];
        }

        $currentDateTime = new DateTime();
        $expiresAt = new DateTime($recover['expiresAt']);

        if ($currentDateTime > $expiresAt) {
            return ['success' => false, 'message' => 'O Link de recuperação expirou.'];
        }

        return ['success' => true, 'message' => 'Token válido.', 'id' => $recover['id'], 'validated' => $recover['validated']];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erro ao verificar link de recuperação.'];
    }
}

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Send an email to destination
 * @param string $destination
 * @param string $subject
 * @param string $body
 */
function sendMail($destination, $subject, $body, $altBody = '')
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'victor.costa.osses@gmail.com';
        $mail->Password = 'wwkw afyy xqtc pfaz';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('victor.costa.osses@gmail.com');
        $mail->addAddress($destination);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = $altBody;

        $mail->send();
    } catch (Exception $e) {
        error("Falha ao tentar enviar email de recuperação.");
    }
}

function newUserToken($userId)
{
    global $CFG;

    try {
        $key = randGen(69, "alphanum");
        $validity = date("Y-m-d H:i:s", (time() + $CFG['sessionValidity']));
        $hashPass = hashPass($key);

        $sql = "INSERT INTO actUserToken (id, userId, token, validity) 
                            VALUES (NULL, :userId, :token, :validity)";

        $stmt = $CFG['link']->prepare($sql);

        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':token', $hashPass, PDO::PARAM_STR);
        $stmt->bindParam(':validity', $validity, PDO::PARAM_STR);

        $stmt->execute();
        $tokenId = $CFG['link']->lastInsertId();
    } catch (PDOException $e) {
        error($e->getMessage());
    } catch (Exception $e) {
        error($e->getMessage());
    }

    if (!empty($tokenId)) {
        setcookie(
            'userToken',
            $tokenId . "_" . $key,
            [
                'expires' => time() + $CFG['sessionValidity'],
                'path' => '/',
                'domain' => '',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]
        );
    }
}


/**
 * Generate random string by length and type
 * @param int $length
 * @param string $type
 */
function randGen($length, $type = "alphanum")
{
    $length = (int)$length;
    switch ($type) {
        case ("alphanum"): {
                $charset = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890";
                break;
            }
        case ("alpha"): {
                $charset = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
                break;
            }
        case ("numbers"): {
                $charset = "1234567890";
                break;
            }
        case ("hex"): {
                $charset = "1234567890abcdef";
                break;
            }
        case ("hex1"): {
                $charset = "abcdef";
                break;
            }
        default: {
                $charset = "01";
                break;
            }
    }
    return substr(str_shuffle(str_repeat($charset, $length)), 0, $length);
}

/**
 * Return a error message and a code to redirect to another page
 * @param string $msg
 * @return int $code
 */
function error($msg, $code = 999)
{
    die(json_encode(array("success" => false, "msg" => $msg, "code" => $code)));
}

/**
 * Remove a specific cookie by key
 * @param string $key
 * @param string $path
 * @param string $domain
 * @param bool $secure
 * @return bool
 */
function unsetcookie($key, $path = '', $domain = '', $secure = true)
{
    if (array_key_exists($key, $_COOKIE)) {
        if (false === setcookie($key, '', time() - 3600, $path, $domain, $secure)) {
            return false;
        }

        unset($_COOKIE[$key]);
    }

    return true;
}

/**
 * Check the validity of a user token in the database
 * @param string $tokenCookie 
 * @return array|void 
 */
function checkToken($tokenCookie)
{
    global $CFG;
    if (empty($tokenCookie)) {
        return;
    }
    $token = @explode('_', $tokenCookie);
    $tokenId = 0;
    if (count($token) != 2) {
        return;
    } else {
        $tokenCookie = $token[1];
        $tokenId = (int)$token[0];
        if (empty($tokenId)) {
            return;
        }
    }
    try {
        $sql = "select u.id, u.name, u.email, u.phone, u.type, uT.id as tokenId, uT.userId, uT.token, uT.validity from actUserToken uT join actUser u on u.id = uT.userId where uT.id = :tokenId and uT.token = :tokenCookie";

        $stmt = $CFG['link']->prepare($sql);

        $tokenCookie = hashPass($tokenCookie);

        $stmt->bindParam(':tokenId', $tokenId, PDO::PARAM_INT);
        $stmt->bindParam(':tokenCookie', $tokenCookie, PDO::PARAM_STR);
        $stmt->execute();

        $rs = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null;

        if (empty($rs['userId'])) {
            return;
        }
        if (strtotime($rs['validity']) <= time()) {
            deleteUserToken((int)$rs['tokenId']);
            unsetcookie('userToken');
            return;
        }

        $sql = "UPDATE actUser SET lastAccess = NOW() WHERE id = :userId";
        $stmt = $CFG['link']->prepare($sql);
        $stmt->bindParam(':userId', $rs['userId'], PDO::PARAM_INT);
        $stmt->execute();
    } catch (Exception $e) {
        return;
    }

    return array('userId' => $rs['userId'], 'userName' => $rs['name'], 'userType' => $rs['type']);
}

/**
 * Deletes a user token from the database
 * @param int $tokenId
 * @return void
 */
function deleteUserToken($tokenId)
{
    global $CFG;
    try {
        $sql = "DELETE FROM actUserToken WHERE id = :tokenId";
        $stmt = $CFG['link']->prepare($sql);
        $stmt->bindParam(':tokenId', $tokenId, PDO::PARAM_INT);
        $success = $stmt->execute();

        if (!$success) {
            throw new Exception("Falha ao excluir o token de usuário.");
        }
    } catch (Exception $e) {
        return;
    }
}
