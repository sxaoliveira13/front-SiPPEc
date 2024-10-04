<?php

if (!isset($_COOKIE['userToken'])) {
    header("Location: login.php");
    exit;
}

$USERDATA = checkToken($_COOKIE['userToken'] ?? []);

if (empty($USERDATA)) {
    header("Location: login.php");
    exit;
}

switch ($USERDATA['userType']) {
    case "0":
    case "3":
        if ($currentPage !== 'awaitingApproval') {
            header("Location: awaitingApproval.php");
            exit;
        }
        break;
    case "1":
    case "2":
        if ($currentPage === 'awaitingApproval') {
            header("Location: catalog.php");
            exit;
        }
        break;
}
