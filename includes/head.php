<?php

$CFG = array();

if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
    $CFG['system_url'] = 'http://localhost/sippec/';
} else {
    $CFG['system_url'] = 'https://liag.ft.unicamp.br/act-sistema/sippec/';
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="theme-color" content="##ECF0F1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="robots" content="index, follow">
    <title>SiPPeC <?php if (!empty($pageName)) {
                        echo " - " . $pageName;
                    } ?></title>
    <link rel="stylesheet" href="<?php echo $CFG['system_url'] ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $CFG['system_url'] ?>main.css">
    <link rel="stylesheet" href="<?php echo $CFG['system_url'] ?>alert.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="<?php echo $CFG['system_url'] ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo $CFG['system_url'] ?>assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $CFG['system_url'] ?>main.js"></script>
    <script>const systemUrl = '<?php echo $CFG['system_url'] ?>'; </script>
    <?php if (!empty($USERDATA)) : ?>
    <script>
        const userData = <?php echo json_encode($USERDATA, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
        promiseUserLoad();
    </script>
    <?php endif; ?>
