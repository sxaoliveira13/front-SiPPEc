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
        <section class="main__section">
            <div class="card bg-transparent shadow-none border-0">
                <div class="card-header text-center border-bottom-0 p-5">
                    <h2 class="fw-bolder mb-3">Aguardando Aprovação</h2>
                    <p class="fs-3 mb-0">Seu cadastro foi concluído e aguarda revisão do administrador do sistema. Você receberá um email de confirmação quando seu cadastro for aprovado.</p>
                </div>
            </div>
        </section>
    </main>

    <?php require(dirname(__FILE__) . '/includes/footer.php'); ?>
</body>

</html>