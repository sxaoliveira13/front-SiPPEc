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
 * Return a error message and a code
 * @param string $msg
 * @return int $code
 */
function error($msg,$code=999){
    die(json_encode(array("success"=>false,"msg"=>$msg,"code"=>$code)));
}