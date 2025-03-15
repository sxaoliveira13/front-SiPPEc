<?php

require_once("api/config.php");
require_once("api/functions.php");

$currentPage = 'awaitingApproval';
$pageName = 'Aguardando Aprovação';

require(dirname(__FILE__) . '/includes/authRedirect.php');
require(dirname(__FILE__) . '/includes/head.php');
?>
<script src="<?php echo $CFG['system_url'] ?>js/awaitingApproval.js"></script>

</head>

<body>
    <?php require(dirname(__FILE__) . '/includes/header.php'); ?>
    <main class="main u-container u-flex-auto">
        <section class="main__section d-flex flex-column" style="max-width: 1200px">
            <span id="loader" class="loader"></span>
            <div id="registerStatusBox" class="card bg-transparent shadow-none border-0 d-none">
                <div class="card-header text-center border-bottom-0 p-5">
                    <h2 id="registerStatusLabel" class="fw-bolder mb-3">Aguardando Aprovação</h2>
                    <p id="registerStatusMessage" class="fs-3 mb-0">Seu cadastro foi concluído e aguarda revisão do administrador do sistema. Você receberá um email de confirmação quando seu cadastro for aprovado.</p>
                </div>
            </div>
            <button id="btnLogout" onclick="logout();" class="w-auto button-primary px-5 mt-5 u-min-w-300">Voltar para o login</button>
            <script>
                buildAwaitingInfos();
            </script>
        </section>
    </main>

    <?php require(dirname(__FILE__) . '/includes/footer.php'); ?>
</body>

</html>