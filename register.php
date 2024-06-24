<?php

require_once("api/config.php");
require_once("api/functions.php");

$pageName = 'Cadastro';

// if (isset($_COOKIE['userToken'])) {
//     $USERDATA = checkToken($_COOKIE['userToken'] ?? []);
//     if (!empty($USERDATA)) {
//         header("Location: catalog.php");
//         exit;
//     }
// }

require(dirname(__FILE__) . '/includes/head.php');
?>

<script src="<?php echo $CFG['system_url'] ?>js/register.js"></script>
</head>

<body>
    <?php require(dirname(__FILE__) . '/includes/header.php'); ?>
    <main class="main u-container u-flex-auto">
        <section class="main__section">
            <div class="card auth-card">
                <div class="auth-card__header">
                    <div class="auth-card__header-img">
                        <img src="assets/img/act.png" class="img-fluid" alt="LIAG">
                    </div>
                    <h2 class="auth-card__header-title fw-bolder mt-4 mb-3">
                        Cadastro
                    </h2>
                </div>
                <div class="auth-card__body">
                    <form id="registerForm" class="form">
                        <div class="form-group">
                            <label class="form-group__label" for="userName">Nome Completo <span class="text-danger">*</span></label>
                            <input class="form-group__input form-group__input--line" type="text" placeholder="Informe o seu nome completo" id="userName" name="userName" required maxlength="128" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label class="form-group__label" for="userEmail">Email <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <input class="form-group__input form-group__input--line" required maxlength="128" type="email" placeholder="Informe o email de acesso" id="userEmail" name="userEmail" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-group__label" for="userPhone">Telefone <span class="text-danger">*</span></label>
                            <input class="form-group__input form-group__input--line phoneMask" type="text" placeholder="Informe o seu telefone de contatto" id="userPhone" name="userPhone" required maxlength="20" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                        </div>
                        <div class="form-group">
                            <label class="form-group__label" for="userPassword">Senha <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <input class="form-group__input form-group__input--line" type="password" placeholder="Informe a senha de acesso" id="userPassword" name="userPassword" required autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                                <svg class="form-group__input-icon form-group__input-icon--right" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1.54199 1L19.542 19M8.38627 7.91364C7.86363 8.4536 7.54199 9.1892 7.54199 10C7.54199 11.6569 8.88517 13 10.542 13C11.3645 13 12.1097 12.669 12.6516 12.133M5.04199 4.64715C3.14269 5.90034 1.69602 7.78394 1 10C2.27425 14.0571 6.06456 17 10.5422 17C12.5311 17 14.3844 16.4194 15.9418 15.4184M9.54197 3.04939C9.87097 3.01673 10.2047 3 10.5422 3C15.0199 3 18.8102 5.94291 20.0844 10C19.8037 10.894 19.4007 11.7338 18.8952 12.5" stroke="#A5A5A5" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-group__label" for="userConfirmePassword">Confirme a senha <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <input class="form-group__input form-group__input--line" type="password" placeholder="Confirme a senha de acesso" id="userConfirmePassword" name="userConfirmePassword" required autocomplete="off">
                                <svg class="form-group__input-icon form-group__input-icon--right" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1.54199 1L19.542 19M8.38627 7.91364C7.86363 8.4536 7.54199 9.1892 7.54199 10C7.54199 11.6569 8.88517 13 10.542 13C11.3645 13 12.1097 12.669 12.6516 12.133M5.04199 4.64715C3.14269 5.90034 1.69602 7.78394 1 10C2.27425 14.0571 6.06456 17 10.5422 17C12.5311 17 14.3844 16.4194 15.9418 15.4184M9.54197 3.04939C9.87097 3.01673 10.2047 3 10.5422 3C15.0199 3 18.8102 5.94291 20.0844 10C19.8037 10.894 19.4007 11.7338 18.8952 12.5" stroke="#A5A5A5" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                    </form>
                    <button href="#" onclick="registerUser(this);" class="button-primary mt-5">Criar Conta</button>
                    <a href="login.php" class="d-block text-center text-link mt-4">Já possuo uma conta</a>
                </div>
            </div>
        </section>
    </main>
    <?php require(dirname(__FILE__) . '/includes/footer.php'); ?>
</body>

</html>