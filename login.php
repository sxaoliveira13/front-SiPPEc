<?php

require_once("api/config.php");
require_once("api/functions.php");

$pageName = 'Login';

if(isset($_COOKIE['userToken'])) {
    $USERDATA = checkToken($_COOKIE['userToken'] ?? []);
    if (!empty($USERDATA)) {
        header("Location: novos_artigos.php");
        exit;
    }
}

require(dirname(__FILE__) . '/includes/head.php');
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo $CFG['system_url'] ?>assets/css/login.css">
<script src="<?php echo $CFG['system_url'] ?>assets/js/login.js"></script>

<body>
    <?php require(dirname(__FILE__) . '/includes/navbar.php'); ?>
    <div id="login">
        <form id="login-form" class="card">
            <div class="card-header">
                <h2>Login</h2>
                <h4>Apenas para administradores!</h4>
            </div>
            <div class="card-content">
                <div class="card-content-area">
                    <label for="userLogin">Usuário</label>
                    <input type="text" name="userLogin" id="userLogin" autocomplete="off">
                </div>
                <div class="card-content-area">
                    <label for="userPassword">Senha</label>
                    <input type="password" name="userPassword" id="userPassword" autocomplete="off">
                </div>
            </div>
            <div class="card-footer">
                <button id="btnSendLogin" type="button" class="submit">login</button>
            </div>
        </form>
    </div>

    <?php require(dirname(__FILE__) . '/includes/footer.php'); ?>
</body>

</html>