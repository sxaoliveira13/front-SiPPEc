<?php

require_once("api/config.php");
require_once("api/functions.php");

$currentPage = 'recoverPassword';
$pageName = 'Recuperar Senha';

if (isset($_COOKIE['userToken'])) {
    $USERDATA = checkToken($_COOKIE['userToken'] ?? []);
    if (!empty($USERDATA)) {
        header("Location: catalog.php");
        exit;
    }
}

if (!isset($_GET['t'])) {
    header("Location: login.php");
    exit;
}

$token = sanitize($_GET['t']);
$response = checkRecoverPasswordToken($token);

if (!$response['success']) {
    header("Location: login.php");
    exit;
}

require(dirname(__FILE__) . '/includes/head.php');
?>

<script>
    const token = '<?php echo $token; ?>';
</script>
<script src="<?php echo $CFG['system_url'] ?>js/recoverPassword.js"></script>
</head>

<body>
    <?php require(dirname(__FILE__) . '/includes/header.php'); ?>
    <main class="main u-container u-flex-auto">
        <div id="recoverPasswordBox" class="card auth-card">
            <div class="auth-card__header">
                <div class="auth-card__header-img">
                    <img src="assets/img/act.png" class="img-fluid" alt="LIAG">
                </div>
                <h2 class="auth-card__header-title fw-bolder mt-4 mb-3">
                    Recuperar Senha
                </h2>
            </div>
            <div class="auth-card__body">
                <form id="recoverPasswordForm" class="form" onsubmit="return false">
                    <div class="form-group">
                        <label class="form-group__label" for="code">Código de Recuperação <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <input class="form-group__input form-group__input--line" type="text" placeholder="Informe o código de 6 digitos" id="code" name="code" maxlength="6" minlength="6" required rautocomplete="off">
                        </div>
                    </div>
                </form>
                <button type="button" id="btnRecoverMyPassword" onclick="recoverPassword(this);" class="button-primary mt-5">Verificar código</button>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('recoverPasswordForm').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                if (!document.getElementById('btnRecoverMyPassword').disabled) {
                    recoverPassword(document.getElementById('btnRecoverMyPassword'));
                }
            }
        });
    </script>

    <?php require(dirname(__FILE__) . '/includes/footer.php'); ?>
</body>

</html>