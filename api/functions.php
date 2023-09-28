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
        case ("default"): {
                $string = htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                return $string;
            }
        case ("url"): {
                $string = preg_replace("/[^A-Za-z0-9\&\/\\\.\?\=?!]/", '', $string);
                $string = str_replace("..", ".", $string);
                return str_replace("..", ".", $string);
            }
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
    $url = $CFG['system_url'] . sanitize($location, "url");
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
 * Return a error message and a code
 * @param string $msg
 * @return int $code
 */
function error($msg, $code = 999)
{
    die(json_encode(array("success" => false, "msg" => $msg, "code" => $code)));
}

function unsetcookie($key, $path = '', $domain = '', $secure = true)
{
    if (array_key_exists($key, $_COOKIE)) {
        if (false === setcookie($key, null, -1, $path, $domain, $secure)) {
            return false;
        }

        unset($_COOKIE[$key]);
    }

    return true;
}

function checkToken($tokenCookie)
{
    global $CFG;
    if (empty($tokenCookie)) {
        forceRedirect("login.php");
    }
    $token = @explode('_', $tokenCookie);
    $tokenId = 0;
    if (count($token) != 2) {
        $tokenCookie = "";
    } else {
        $tokenCookie = sanitize($token[1], "alphaNum");
        $tokenId = (int)$token[0];
        if (empty($tokenId)) {
            $tokenCookie = "";
        }
    }
    if (empty($tokenCookie)) {
        forceRedirect("login.php");
    } else {
        try {
            $sql = "select u.userId, u.userLogin, uT.userId, uT.tokenKey, uT.tokenValidity from actUserToken uT join actUser u on u.userId = uT.userId where uT.tokenId = :tokenId and uT.tokenKey = :tokenCookie";

            $stmt = $CFG['link']->prepare($sql);
    
            $stmt->bindParam(':tokenId', $tokenId, PDO::PARAM_INT);
            $stmt->bindParam(':tokenCookie', hashPass($tokenCookie), PDO::PARAM_STR);
            $stmt->execute();
            
            $rs = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt = null;

            if (empty($rs['userId'])) {
                forceRedirect("login.php");
            }
            if (strtotime($rs['tokenValidity']) >= time()) {
                unsetcookie('userToken');
                forceRedirect("login.php");
            }
        } catch(Exception $e) {
            error('Falha ao tentar verificar token de acesso!');
        }

        return array('userId' => $rs['userId'], 'userLogin' => $rs['userLogin']);
    }
}