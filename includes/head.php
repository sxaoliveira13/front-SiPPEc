<?php

$CFG = array();

if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
    $CFG['system_url'] = 'http://localhost/sippec/';
} else {
    $CFG['system_url'] = 'https://liag.ft.unicamp.br/act-sistema/sippec/';
}

$CFG['access_token'] = '624879f5e6346c9cda0e80421d95a37c8626a2ee0a215e261be42e355390100d';

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="theme-color" content="##ECF0F1">
    <title>Cadastro de conteúdos <?php if(!empty($pageName)){echo " - ".$pageName; } ?></title>
    <script src="<?php echo $CFG['system_url'] ?>assets/js/main.js"></script>
</head>