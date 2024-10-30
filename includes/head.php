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
    <meta name="description" content="Sistema de gerenciamento de catálogos do ACT (Aprendizado, Computação e Tecnologia), um projeto do LIAG que promove o Pensamento Computacional e a Computação Criativa na educação no Brasil.">
    <meta name="author" content="LIAG - Laboratório de Informática, Aprendizagem e Gestão">
    <meta name="keywords" content="LIAG, ACT, Tecnologia, Computação Criativa, Aprendizado, Pensamento Computacional, Unicamp, Educação, Métodos, Jogos Educativos, Artigos">
    <meta name="robots" content="index, follow">

    <!-- Open Graph para Facebook e Instagram -->
    <meta property="og:title" content="SiPPeC - Um sistema de gerenciamento de Catálogos do ACT (Aprendizado, Computação e Tecnologia)">
    <meta property="og:description" content="Explore o SiPPeC, um projeto do LIAG que incentiva o Pensamento Computacional e a Computação Criativa na educação brasileira. Aqui você encontrará ferramentas e recursos para inovar o ensino de tecnologia.">
    <meta property="og:image" content="<?php echo $CFG['system_url'] ?>assets/img/actCompleto.png">
    <meta property="og:url" content="<?php echo $CFG['system_url'] ?>">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="pt_BR">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="SiPPeC - Um sistema de gerenciamento de Catálogos do ACT (Aprendizado, Computação e Tecnologia)">
    <meta name="twitter:description" content="Explore o SiPPeC, um projeto do LIAG que incentiva o Pensamento Computacional e a Computação Criativa na educação brasileira. Aqui você encontrará ferramentas e recursos para inovar o ensino de tecnologia.">
    <meta name="twitter:image" content="<?php echo $CFG['system_url'] ?>assets/img/actCompleto.png">

    <title>SiPPeC <?php if (!empty($pageName)) {
                        echo " - " . $pageName;
                    } ?></title>
    <link rel="stylesheet" href="<?php echo $CFG['system_url'] ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $CFG['system_url'] ?>main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

    <script>
        const currentPage = '<?php echo $currentPage; ?>';
        const systemUrl = '<?php echo $CFG['system_url']; ?>';
    </script>

    <script src="<?php echo $CFG['system_url'] ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo $CFG['system_url'] ?>assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $CFG['system_url'] ?>main.js"></script>

    <?php if (!empty($USERDATA)) : ?>
        <script>
            const userData = <?php echo json_encode($USERDATA, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
            promiseUserLoad();
        </script>
    <?php endif; ?>