<?php
if (!isset($_GET['t']) || $_GET['t'] !== hash('sha256', 'sippectoken')) {
    header("Location: https://liag.ft.unicamp.br/act/");
    die();
}

$pageName = 'Login';
require(dirname(__FILE__) . '/includes/head.php');
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo $CFG['system_url'] ?>assets/css/login.css">
<script src="<?php echo $CFG['system_url'] ?>assets/js/login.js"></script>

<body>
    <?php require(dirname(__FILE__) . '/includes/navbar.php'); ?>
    <div id="login">
        <form id="loginForm" class="card">
            <div class="card-header">
                <h2>Login</h2>
                <h4>Apenas para administradores!</h4>
            </div>
            <div class="card-content">
                <div class="card-content-area">
                    <label for="userLogin">Usuário</label>
                    <input type="text" id="userLogin" autocomplete="off">
                </div>
                <div class="card-content-area">
                    <label for="userPassword">Senha</label>
                    <input type="password" id="userPassword" autocomplete="off">
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