<?php

require_once("api/config.php");
require_once("api/functions.php");

$pageName = 'Cadastro de Catálogos';

if (!isset($_COOKIE['userToken'])) {
    header("Location: login.php");
    exit;
}

$USERDATA = checkToken($_COOKIE['userToken'] ?? []);

if (empty($USERDATA)) {
    header("Location: login.php");
    exit;
}

require(dirname(__FILE__) . '/includes/head.php');
?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="<?php echo $CFG['system_url'] ?>js/catalog.js"></script>


</head>

<body>
    <header class="header u-container">
        <nav class="header__nav d-flex justify-content-between align-items-center">
            <div class="d-flex flex-column">
                <h2 class="fs-3 text-muted fw-bolder mb-1">Bem vindo,</h2>
                <h1 id="userName" class="fs-1 fw-bolder mb-0"></h1>
            </div>
            <ul class="header__nav-list d-lg-flex d-none flex-row list-unstyled my-0">
                <li class="mx-0">
                    <a href="#" onclick="logout();" class="u-hover-svg u-hover-svg--primary"><svg class="u-hover-svg u-hover-svg--primary" width="3.2rem" height="3.2rem" viewBox="0 0 32 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.75 28.3846H5.5C3.56695 28.3846 2 26.908 2 25.0866L2 5.2981C2 3.47658 3.56695 2.00003 5.5 2.00003H10.75M23 21.7885L30 15.1923M30 15.1923L23 8.59618M30 15.1923L9 15.1923" stroke="#2E2E2E" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                </li>
                <li class="mx-0">
                    <a href="#" class="u-hover-svg u-hover-svg--primary pe-0">
                        <svg width="3.5rem" height="3.5rem" xmlns="http://www.w3.org/2000/svg" width="28" height="33" fill="none">
                            <path fill="#2E2E2E" d="M14.0458 6.43867c.7649-.02967 1.3638-.75286 1.3374-1.61529-.0262-.86244-.6677-1.53755-1.4327-1.50788l.0953 3.12317ZM5.60554 15.2333l-1.3853.048a1.7563 1.7563 0 0 0 .00235.055l1.38295-.103Zm-2.3657 7.1188.90478 1.1835a1.35712 1.35712 0 0 0 .07201-.075l-.97679-1.1085Zm-.81079.7562.81414 1.2646a1.45554 1.45554 0 0 0 .06706-.0585l-.8812-1.2061Zm7.96555 5.0167c.7655 0 1.386-.6996 1.386-1.5625S11.1601 25 10.3946 25v3.125Zm3.6512-24.8095c-.765-.02967-1.4064.64544-1.4329 1.50788-.0262.86243.5725 1.58562 1.3376 1.61529l.0953-3.12317Zm8.3449 11.9178 1.383.1027c.0011-.0183.0018-.0364.0024-.0547l-1.3854-.048Zm2.3657 7.1167-.9768 1.1085c.0233.0259.0473.0511.0721.075l.9047-1.1835Zm.8109.7563-.8813 1.206c.0214.0198.0432.039.0656.0573l.8157-1.2633ZM17.6017 25c-.7654 0-1.386.6996-1.386 1.5625s.6206 1.5625 1.386 1.5625V25ZM12.6122 4.87708c0 .86294.6205 1.5625 1.386 1.5625.7654 0 1.3859-.69956 1.3859-1.5625h-2.7719Zm2.7719-3.31458C15.3841.69956 14.7636 0 13.9982 0c-.7655 0-1.386.69956-1.386 1.5625h2.7719ZM10.3946 25c-.76543 0-1.38595.6996-1.38595 1.5625s.62052 1.5625 1.38595 1.5625V25Zm7.2071 3.125c.7654 0 1.3859-.6996 1.3859-1.5625S18.3671 25 17.6017 25v3.125Zm-5.8211-1.5625c0-.8629-.6205-1.5625-1.386-1.5625-.76543 0-1.38595.6996-1.38595 1.5625h2.77195Zm7.207 0c0-.8629-.6205-1.5625-1.3859-1.5625-.7654 0-1.386.6996-1.386 1.5625h2.7719Zm-5.0371-23.247c-5.58424.21654-9.90341 5.60535-9.73026 11.9658l2.77062-.0959c-.12834-4.7143 3.06184-8.5919 7.05494-8.74673l-.0953-3.12317ZM4.22259 15.3363c.12978 2.2154-.59207 4.3758-1.95954 5.9072l1.95358 2.2171c1.94474-2.1781 2.95372-5.226 2.77187-8.3302l-2.76591.2059Zm-1.88753 5.8322c-.28955.2813-.53293.4978-.78723.7338l1.76242 2.4121c.20335-.189.51491-.4684.83437-.7788l-1.80956-2.3671Zm-.72014.6753c-.40519.3316-.77337.8054-1.04783 1.3056-.27106.4937-.5304 1.1585-.56363 1.8939-.03635.8044.20943 1.699.93957 2.3284.65322.5633 1.51398.7533 2.42473.7533V25c-.30183 0-.50148-.0333-.62397-.0685-.12144-.0348-.14305-.0646-.11688-.0421.03389.0294.08414.0914.11727.1808.03009.0815.0275.1348.02763.1323.00026-.006.00394-.0498.03032-.1319.02578-.0802.0664-.1754.12214-.2768.05542-.1011.11845-.195.18089-.2734.06422-.0808.11413-.1281.13803-.1475l-1.62827-2.5291Zm1.75284 6.2812h7.02684V25H3.36776v3.125ZM13.9505 6.43867c3.9931.15483 7.1832 4.03243 7.0549 8.74673l2.7707.0959c.1731-6.36045-4.1461-11.74926-9.7303-11.9658l-.0953 3.12317Zm7.0573 8.69193c-.1812 3.1036.8277 6.1504 2.7718 8.3279l1.9536-2.217c-1.3671-1.5311-2.0889-3.6907-1.9595-5.9055l-2.7659-.2054Zm2.8439 8.4029c.3195.3105.631.5898.8343.7788l1.7624-2.4121c-.2543-.236-.4977-.4525-.7872-.7337l-1.8095 2.367Zm.8999.8361c.0248.0202.0754.0683.1401.1498.063.0791.1266.1735.1826.2752.0561.1021.0972.1977.1232.2783.0266.0825.0305.1265.0307.1323.0002.0025-.0026-.0514.0277-.1335.0333-.0904.0841-.1529.1183-.1825.0262-.0227.0046.007-.1174.042-.123.0355-.3235.0688-.6264.0688v3.125c.9116 0 1.7735-.1898 2.4271-.7531.7316-.6304.9766-1.5267.9388-2.3317-.0346-.7358-.2953-1.4002-.5668-1.8935-.275-.4996-.6431-.9725-1.0467-1.3038l-1.6312 2.5267ZM24.6304 25h-7.0287v3.125h7.0287V25ZM15.3841 4.87708V1.5625h-2.7719v3.31458h2.7719ZM10.3946 28.125h7.2071V25h-7.2071v3.125Zm-1.38595-1.5625c0 3.1267 2.20095 5.7292 4.98955 5.7292v-3.125c-1.192 0-2.2176-1.1284-2.2176-2.6042H9.00865Zm4.98955 5.7292c2.7883 0 4.9894-2.6025 4.9894-5.7292h-2.7719c0 1.4758-1.0258 2.6042-2.2175 2.6042v3.125Z" />
                        </svg>
                    </a>
                </li>
            </ul>
        </nav>
    </header>
    <main class="main main__grid--1 u-container u-flex-auto">
        <section class="main__section--box h-100">
            <div class="main__section-header border-bottom">
                <div class="d-flex justify-content-between align-items-center u-box-padding--horizontal u-box-padding--vertical">
                    <h2 class="mb-0">Seus Catálogos</h2>
                    <span id="catalogsQuantity" class="badge badge--primary badge--rounded">0</span>
                </div>
            </div>
            <div class="form-group position-relative u-box-padding--horizontal u-box-padding--vertical">
                <svg class="form-group__input-icon form-group__input-icon--left" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path d="M19 3H5C3.58579 3 2.87868 3 2.43934 3.4122C2 3.8244 2 4.48782 2 5.81466V6.50448C2 7.54232 2 8.06124 2.2596 8.49142C2.5192 8.9216 2.99347 9.18858 3.94202 9.72255L6.85504 11.3624C7.49146 11.7206 7.80967 11.8998 8.03751 12.0976C8.51199 12.5095 8.80408 12.9935 8.93644 13.5872C9 13.8722 9 14.2058 9 14.8729L9 17.5424C9 18.452 9 18.9067 9.25192 19.2613C9.50385 19.6158 9.95128 19.7907 10.8462 20.1406C12.7248 20.875 13.6641 21.2422 14.3321 20.8244C15 20.4066 15 19.4519 15 17.5424V14.8729C15 14.2058 15 13.8722 15.0636 13.5872C15.1959 12.9935 15.488 12.5095 15.9625 12.0976C16.1903 11.8998 16.5085 11.7206 17.145 11.3624L20.058 9.72255C21.0065 9.18858 21.4808 8.9216 21.7404 8.49142C22 8.06124 22 7.54232 22 6.50448V5.81466C22 4.48782 22 3.8244 21.5607 3.4122C21.1213 3 20.4142 3 19 3Z" stroke="#C5C5C5" stroke-width="2.5"></path>
                    </g>
                </svg>
                <input class="form-group__input form-group__input--filter" type="text" placeholder="Pesquisar" id="filterCatalog" name="filterCatalog" autocomplete="off">
            </div>
            <ul id="catalogsList" class="main__section-catalogs u-box-padding--horizontal position-relative h-100 my-0" style="max-height: 66vh;overflow: auto;">
                <div id="catalogListLoader" class="position-absolute top-50 start-50 translate-middle">
                    <span class="loader"></span>
                </div>
            </ul>
        </section>
        <section class="main__grid--2 h-100">
            <section class="main__section--box overflow-hidden px-0">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#articleTab" type="button" role="tab" aria-selected="true">Registrar Artigos
                            <div class="tab-line"></div>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#educationGamesTab" type="button" role="tab" aria-selected="false">Registrar Jogos
                            <div class="tab-line"></div>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#methodsTab" type="button" role="tab" aria-selected="false">Registrar Métodos
                            <div class="tab-line"></div>
                        </button>
                    </li>
                </ul>
            </section>
            <section class="main__section--box">
                <div class="tab-content d-flex flex-column" id="catalogTabs">
                    <div class="tab-pane fade show flex-auto active" id="articleTab" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between u-box-padding--vertical u-box-padding--horizontal-big border-bottom">
                            <h4 class="fs-2 fw-bolder mb-0">Cadastro de Artigo</h4>
                            <button id="btnNewArticle" class="w-auto button-primary px-5">Cadastrar Artigo</button>
                        </div>
                        <form id="articleForm" class="form u-box-padding--vertical u-box-padding--horizontal-big" style="max-height: 58.5vh;overflow: auto;">
                            <div style="min-height: 180px"></div>
                            <div class="form-group">
                                <label class="form-group__label" for="articleName">Título <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input class="form-group__input form-group__input--line" type="text" placeholder="Informe o título do artigo educacional" id="articleName" name="titulo" required maxlength="200" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label" for="articleContent">Conteúdo <span class="text-danger">*</span></label>
                                <select class="form-group__input form-group__input" id="articleContent" name="conteudo" required>
                                    <option value="-1" disabled selected>Selecionar</option>
                                    <option value="Computação Física; ComFAPOO; Arduino; C++; Avaliações">Computação Física; ComFAPOO; Arduino; C++; Avaliações</option>
                                    <option value="Computação “Desplugada” (CD); Jogos Digitais (JD); Linguagem de Programação (LP); Linguagem de Programação Visual (LPV); Robótica Pedagógica (RP)">Computação “Desplugada” (CD); Jogos Digitais (JD); Linguagem de Programação (LP); Linguagem de Programação Visual (LPV); Robótica Pedagógica (RP)</option>
                                    <option value="Scratch; Code.Org; Era uma vez">Scratch; Code.Org; Era uma vez</option>
                                    <option value="Computação Física; Arduino">Computação Física; Arduino</option>
                                    <option value="Arduino; Makey Makey; Lego Mindstorms; LilyPad Arduino">Arduino; Makey Makey; Lego Mindstorms; LilyPad Arduino</option>
                                    <option value="Computação Física; ComFAPOO">Computação Física; ComFAPOO</option>
                                    <option value="Apresentação Gradativa; Jogos Digitais; Novas Linguagens; Mapas Conceituais; Metodologias Ativas; PBL;Recursos Multimídias; Robótica Pedagógicas; Scratch">Apresentação Gradativa; Jogos Digitais; Novas Linguagens; Mapas Conceituais; Metodologias Ativas; PBL;Recursos Multimídias; Robótica Pedagógicas; Scratch</option>
                                    <option value="Arduino; Robotica; Plataformas Diversas, Atividades Desplugadas; Programação em Blocos">Arduino; Robotica; Plataformas Diversas, Atividades Desplugadas; Programação em Blocos</option>
                                    <option value="Thinkertank; Unity; Linguagem C#">Thinkertank; Unity; Linguagem C#</option>
                                    <option value="Gestão de projetos; PMBOK; Braindraw; Avaliação Heurística">Gestão de projetos; PMBOK; Braindraw; Avaliação Heurística</option>
                                    <option value="Arduino; Gogo Board; Scratch; Robomid; Robocode">Arduino; Gogo Board; Scratch; Robomid; Robocode</option>
                                    <option value="HTML5; Javascript">HTML5; Javascript</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label" for="articleTool">Ferramenta <span class="text-danger">*</span></label>
                                <select class="form-group__input form-group__input" type="text" id="articleTool" name="ferramneta" required>
                                    <option value="-1" disabled selected>Selecionar</option>
                                    <option value="1">GNU</option>
                                    <option value="2">Estilo BSD</option>
                                    <option value="3">Papel e lápis</option>
                                    <option value="4">Scratch</option>
                                    <option value="5">App Inventor</option>
                                    <option value="6">Computação Física</option>
                                    <option value="7">HTML</option>
                                    <option value="8">HTML / CSS</option>
                                    <option value="9">Python</option>
                                    <option value="10">Robótica Educacional</option>
                                    <option value="11">Programação</option>
                                    <option value="12">MIT</option>
                                    <option value="13">Apache</option>
                                    <option value="14">WTFPL</option>
                                    <option value="15">Geogebra</option>
                                    <option value="16">D. Público</option>
                                    <option value="17">Não se aplica</option>
                                    <option value="18">Beer License</option>
                                    <option value="19">MirOS</option>
                                    <option value="20">ISC</option>
                                    <option value="21">EPL</option>
                                    <option value="22">XSkat</option>
                                    <option value="23">Computação Física; ComFAPOO; Arduino; C++; Avaliações</option>
                                    <option value="24">Computação “Desplugada” (CD); Jogos Digitais (JD); Linguagem de Programação (LP); Linguagem de Programação Visual (LPV); Robótica Pedagógica (RP)</option>
                                    <option value="25">Scratch; Code.Org; Era uma vez</option>
                                    <option value="26">Computação Física; Arduino</option>
                                    <option value="27">Arduino; Makey Makey; Lego Mindstorms; LilyPad Arduino</option>
                                    <option value="28">Computação Física; ComFAPOO</option>
                                    <option value="29">Apresentação Gradativa; Jogos Digitais; Novas Linguagens; Mapas Conceituais; Metodologias Ativas; PBL;Recursos Multimídias; Robótica Pedagógicas; Scratch</option>
                                    <option value="30">Arduino; Robotica; Plataformas Diversas, Atividades Desplugadas; Programação em Blocos</option>
                                    <option value="31">Thinkertank; Unity; Linguagem C#</option>
                                    <option value="32">Gestão de projetos; PMBOK; Braindraw; Avaliação Heurística</option>
                                    <option value="33">Arduino; Gogo Board; Scratch; Robomid; Robocode</option>
                                    <option value="34">HTML5; Javascript</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label" for="articlePublic">Público Alvo <span class="text-danger">*</span></label>
                                <select class="form-group__input form-group__input" type="text" id="articlePublic" name="publico" required>
                                    <option value="-1" disabled selected>Selecionar</option>
                                    <option value="1">Ensino Infantil</option>
                                    <option value="2">Ensino Fundamental I</option>
                                    <option value="3">Ensino Fundamental II</option>
                                    <option value="4">Ensino Médio</option>
                                    <option value="5">Formação para professores</option>
                                    <option value="6">Educação especial</option>
                                    <option value="7">Ensino Técnico</option>
                                    <option value="8">Ensino Superior</option>
                                    <option value="9">Fundamental II / Formação para professores / Ensino Médio / Ensino Técnico / Ensino Superior</option>
                                    <option value="10">Fundamental II / Ensino Médio / Ensino Técnico / Ensino Superior</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label" for="articleAbility">Habilidade Desenvolvida <span class="text-danger">*</span></label>
                                <select class="form-group__input form-group__input" type="text" id="articleAbility" name="habilidade" required>
                                    <option value="-1" disabled selected>Selecionar</option>
                                    <option value="1">Pensamento Lógico</option>
                                    <option value="2">Criatividade</option>
                                    <option value="3">Resolução de problemas</option>
                                    <option value="4">Programação</option>
                                    <option value="5">Não se aplica</option>
                                    <option value="6">Raciocínio lógico</option>
                                    <option value="7">Colaboração</option>
                                    <option value="8">Conhecimento computacional</option>
                                    <option value="9">Lógica</option>
                                    <option value="10">Sustentabilidade</option>
                                    <option value="11">Abstração</option>
                                    <option value="12">Resolução de problemas / Raciocínio Lógico / Criatividade / Colaboração / Programação / Lógica / Conhecimento computacional</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label" for="articleLink">Link de Acesso <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input class="form-group__input form-group__input--line" type="text" placeholder="Informe o link de acesso do artigo educacional" id="articleLink" name="link" required maxlength="200" autocomplete="off">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="educationGamesTab" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between u-box-padding--vertical u-box-padding--horizontal-big border-bottom">
                            <h4 class="fs-2 fw-bolder mb-0">Cadastro de Jogo Educacional</h4>
                            <button id="btnNewGame" class="w-auto button-primary px-5">Cadastrar Jogo</button>
                        </div>
                        <form id="gameForm" class="form u-box-padding--vertical u-box-padding--horizontal-big" style="max-height: 58.5vh;overflow: auto;">
                            <div style="min-height: 440px"></div>
                            <div class="form-group">
                                <label class="form-group__label" for="gameName">Título <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input class="form-group__input form-group__input--line" type="text" placeholder="Informe o título do jogo educacional" id="gameName" name="titulo" required maxlength="200" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label" for="gameContent">Conteúdo <span class="text-danger">*</span></label>
                                <select class="form-group__input form-group__input" id="gameContent" name="conteudo" required>
                                    <option value="-1" disabled selected>Selecionar</option>
                                    <option value="Computação Física; ComFAPOO; Arduino; C++; Avaliações">Computação Física; ComFAPOO; Arduino; C++; Avaliações</option>
                                    <option value="Computação “Desplugada” (CD); Jogos Digitais (JD); Linguagem de Programação (LP); Linguagem de Programação Visual (LPV); Robótica Pedagógica (RP)">Computação “Desplugada” (CD); Jogos Digitais (JD); Linguagem de Programação (LP); Linguagem de Programação Visual (LPV); Robótica Pedagógica (RP)</option>
                                    <option value="Scratch; Code.Org; Era uma vez">Scratch; Code.Org; Era uma vez</option>
                                    <option value="Computação Física; Arduino">Computação Física; Arduino</option>
                                    <option value="Arduino; Makey Makey; Lego Mindstorms; LilyPad Arduino">Arduino; Makey Makey; Lego Mindstorms; LilyPad Arduino</option>
                                    <option value="Computação Física; ComFAPOO">Computação Física; ComFAPOO</option>
                                    <option value="Apresentação Gradativa; Jogos Digitais; Novas Linguagens; Mapas Conceituais; Metodologias Ativas; PBL;Recursos Multimídias; Robótica Pedagógicas; Scratch">Apresentação Gradativa; Jogos Digitais; Novas Linguagens; Mapas Conceituais; Metodologias Ativas; PBL;Recursos Multimídias; Robótica Pedagógicas; Scratch</option>
                                    <option value="Arduino; Robotica; Plataformas Diversas, Atividades Desplugadas; Programação em Blocos">Arduino; Robotica; Plataformas Diversas, Atividades Desplugadas; Programação em Blocos</option>
                                    <option value="Thinkertank; Unity; Linguagem C#">Thinkertank; Unity; Linguagem C#</option>
                                    <option value="Gestão de projetos; PMBOK; Braindraw; Avaliação Heurística">Gestão de projetos; PMBOK; Braindraw; Avaliação Heurística</option>
                                    <option value="Arduino; Gogo Board; Scratch; Robomid; Robocode">Arduino; Gogo Board; Scratch; Robomid; Robocode</option>
                                    <option value="HTML5; Javascript">HTML5; Javascript</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label" for="gameTool">Ferramenta <span class="text-danger">*</span></label>
                                <select class="form-group__input form-group__input" type="text" id="gameTool" name="ferramenta" required>
                                    <option value="-1" disabled selected>Selecionar</option>
                                    <option value="1">GNU</option>
                                    <option value="2">Estilo BSD</option>
                                    <option value="3">Papel e lápis</option>
                                    <option value="4">Scratch</option>
                                    <option value="5">App Inventor</option>
                                    <option value="6">Computação Física</option>
                                    <option value="7">HTML</option>
                                    <option value="8">HTML / CSS</option>
                                    <option value="9">Python</option>
                                    <option value="10">Robótica Educacional</option>
                                    <option value="11">Programação</option>
                                    <option value="12">MIT</option>
                                    <option value="13">Apache</option>
                                    <option value="14">WTFPL</option>
                                    <option value="15">Geogebra</option>
                                    <option value="16">D. Público</option>
                                    <option value="17">Não se aplica</option>
                                    <option value="18">Beer License</option>
                                    <option value="19">MirOS</option>
                                    <option value="20">ISC</option>
                                    <option value="21">EPL</option>
                                    <option value="22">XSkat</option>
                                    <option value="23">Computação Física; ComFAPOO; Arduino; C++; Avaliações</option>
                                    <option value="24">Computação “Desplugada” (CD); Jogos Digitais (JD); Linguagem de Programação (LP); Linguagem de Programação Visual (LPV); Robótica Pedagógica (RP)</option>
                                    <option value="25">Scratch; Code.Org; Era uma vez</option>
                                    <option value="26">Computação Física; Arduino</option>
                                    <option value="27">Arduino; Makey Makey; Lego Mindstorms; LilyPad Arduino</option>
                                    <option value="28">Computação Física; ComFAPOO</option>
                                    <option value="29">Apresentação Gradativa; Jogos Digitais; Novas Linguagens; Mapas Conceituais; Metodologias Ativas; PBL;Recursos Multimídias; Robótica Pedagógicas; Scratch</option>
                                    <option value="30">Arduino; Robotica; Plataformas Diversas, Atividades Desplugadas; Programação em Blocos</option>
                                    <option value="31">Thinkertank; Unity; Linguagem C#</option>
                                    <option value="32">Gestão de projetos; PMBOK; Braindraw; Avaliação Heurística</option>
                                    <option value="33">Arduino; Gogo Board; Scratch; Robomid; Robocode</option>
                                    <option value="34">HTML5; Javascript</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label" for="gamePublic">Público Alvo <span class="text-danger">*</span></label>
                                <select class="form-group__input form-group__input" type="text" id="gamePublic" name="publico" required>
                                    <option value="-1" disabled selected>Selecionar</option>
                                    <option value="1">Ensino Infantil</option>
                                    <option value="2">Ensino Fundamental I</option>
                                    <option value="3">Ensino Fundamental II</option>
                                    <option value="4">Ensino Médio</option>
                                    <option value="5">Formação para professores</option>
                                    <option value="6">Educação especial</option>
                                    <option value="7">Ensino Técnico</option>
                                    <option value="8">Ensino Superior</option>
                                    <option value="9">Fundamental II / Formação para professores / Ensino Médio / Ensino Técnico / Ensino Superior</option>
                                    <option value="10">Fundamental II / Ensino Médio / Ensino Técnico / Ensino Superior</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label" for="gameAbility">Habilidade Desenvolvida <span class="text-danger">*</span></label>
                                <select class="form-group__input form-group__input" type="text" id="gameAbility" name="habilidade" required>
                                    <option value="-1" disabled selected>Selecionar</option>
                                    <option value="1">Pensamento Lógico</option>
                                    <option value="2">Criatividade</option>
                                    <option value="3">Resolução de problemas</option>
                                    <option value="4">Programação</option>
                                    <option value="5">Não se aplica</option>
                                    <option value="6">Raciocínio lógico</option>
                                    <option value="7">Colaboração</option>
                                    <option value="8">Conhecimento computacional</option>
                                    <option value="9">Lógica</option>
                                    <option value="10">Sustentabilidade</option>
                                    <option value="11">Abstração</option>
                                    <option value="12">Resolução de problemas / Raciocínio Lógico / Criatividade / Colaboração / Programação / Lógica / Conhecimento computacional</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label" for="gameAmbient">Ambiente do Jogo <span class="text-danger">*</span></label>
                                <select class="form-group__input form-group__input" type="text" id="gameAmbient" name="ambiente" required>
                                    <option disabled selected>Selecionar</option>
                                    <option value="Extracurricular">Extracurricular</option>
                                    <option value="Intracurricular">Intracurricular</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label" for="gameApproach">Abordagem <span class="text-danger">*</span></label>
                                <select class="form-group__input form-group__input" type="text" id="gameApproach" name="abordagem" required>
                                    <option disabled selected>Selecionar</option>
                                    <option value="Instrucionista">Instrucionista</option>
                                    <option value="Intracurricular">Construcionista</option>
                                    <option value="Construtivista">Construtivista</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label" for="gameLink">Link de Acesso <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input class="form-group__input form-group__input--line" type="text" placeholder="Informe o link de acesso do jogo educacional" id="gameLink" name="link" required maxlength="200" autocomplete="off">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="methodsTab" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between u-box-padding--vertical u-box-padding--horizontal-big border-bottom">
                            <h4 class="fs-2 fw-bolder mb-0">Cadastro de Método Educacional</h4>
                            <button id="btnNewMethod" class="w-auto button-primary px-5">Cadastrar Método</button>
                        </div>
                        <form id="methodForm" class="form u-box-padding--vertical u-box-padding--horizontal-big" style="max-height: 58.5vh;overflow: auto;">
                            <div style="min-height: 180px"></div>
                            <div class="form-group">
                                <label class="form-group__label" for="methodName">Título <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input class="form-group__input form-group__input--line" type="text" placeholder="Informe o título do método educacional" id="methodName" name="titulo" required maxlength="200" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label" for="methodContent">Conteúdo <span class="text-danger">*</span></label>
                                <select class="form-group__input form-group__input" id="methodContent" name="conteudo" required>
                                    <option value="-1" disabled selected>Selecionar</option>
                                    <option value="Computação Física; ComFAPOO; Arduino; C++; Avaliações">Computação Física; ComFAPOO; Arduino; C++; Avaliações</option>
                                    <option value="Computação “Desplugada” (CD); Jogos Digitais (JD); Linguagem de Programação (LP); Linguagem de Programação Visual (LPV); Robótica Pedagógica (RP)">Computação “Desplugada” (CD); Jogos Digitais (JD); Linguagem de Programação (LP); Linguagem de Programação Visual (LPV); Robótica Pedagógica (RP)</option>
                                    <option value="Scratch; Code.Org; Era uma vez">Scratch; Code.Org; Era uma vez</option>
                                    <option value="Computação Física; Arduino">Computação Física; Arduino</option>
                                    <option value="Arduino; Makey Makey; Lego Mindstorms; LilyPad Arduino">Arduino; Makey Makey; Lego Mindstorms; LilyPad Arduino</option>
                                    <option value="Computação Física; ComFAPOO">Computação Física; ComFAPOO</option>
                                    <option value="Apresentação Gradativa; Jogos Digitais; Novas Linguagens; Mapas Conceituais; Metodologias Ativas; PBL;Recursos Multimídias; Robótica Pedagógicas; Scratch">Apresentação Gradativa; Jogos Digitais; Novas Linguagens; Mapas Conceituais; Metodologias Ativas; PBL;Recursos Multimídias; Robótica Pedagógicas; Scratch</option>
                                    <option value="Arduino; Robotica; Plataformas Diversas, Atividades Desplugadas; Programação em Blocos">Arduino; Robotica; Plataformas Diversas, Atividades Desplugadas; Programação em Blocos</option>
                                    <option value="Thinkertank; Unity; Linguagem C#">Thinkertank; Unity; Linguagem C#</option>
                                    <option value="Gestão de projetos; PMBOK; Braindraw; Avaliação Heurística">Gestão de projetos; PMBOK; Braindraw; Avaliação Heurística</option>
                                    <option value="Arduino; Gogo Board; Scratch; Robomid; Robocode">Arduino; Gogo Board; Scratch; Robomid; Robocode</option>
                                    <option value="HTML5; Javascript">HTML5; Javascript</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label" for="methodTool">Ferramenta <span class="text-danger">*</span></label>
                                <select class="form-group__input form-group__input" type="text" id="methodTool" name="ferramenta" required>
                                    <option value="-1" disabled selected>Selecionar</option>
                                    <option value="1">GNU</option>
                                    <option value="2">Estilo BSD</option>
                                    <option value="3">Papel e lápis</option>
                                    <option value="4">Scratch</option>
                                    <option value="5">App Inventor</option>
                                    <option value="6">Computação Física</option>
                                    <option value="7">HTML</option>
                                    <option value="8">HTML / CSS</option>
                                    <option value="9">Python</option>
                                    <option value="10">Robótica Educacional</option>
                                    <option value="11">Programação</option>
                                    <option value="12">MIT</option>
                                    <option value="13">Apache</option>
                                    <option value="14">WTFPL</option>
                                    <option value="15">Geogebra</option>
                                    <option value="16">D. Público</option>
                                    <option value="17">Não se aplica</option>
                                    <option value="18">Beer License</option>
                                    <option value="19">MirOS</option>
                                    <option value="20">ISC</option>
                                    <option value="21">EPL</option>
                                    <option value="22">XSkat</option>
                                    <option value="23">Computação Física; ComFAPOO; Arduino; C++; Avaliações</option>
                                    <option value="24">Computação “Desplugada” (CD); Jogos Digitais (JD); Linguagem de Programação (LP); Linguagem de Programação Visual (LPV); Robótica Pedagógica (RP)</option>
                                    <option value="25">Scratch; Code.Org; Era uma vez</option>
                                    <option value="26">Computação Física; Arduino</option>
                                    <option value="27">Arduino; Makey Makey; Lego Mindstorms; LilyPad Arduino</option>
                                    <option value="28">Computação Física; ComFAPOO</option>
                                    <option value="29">Apresentação Gradativa; Jogos Digitais; Novas Linguagens; Mapas Conceituais; Metodologias Ativas; PBL;Recursos Multimídias; Robótica Pedagógicas; Scratch</option>
                                    <option value="30">Arduino; Robotica; Plataformas Diversas, Atividades Desplugadas; Programação em Blocos</option>
                                    <option value="31">Thinkertank; Unity; Linguagem C#</option>
                                    <option value="32">Gestão de projetos; PMBOK; Braindraw; Avaliação Heurística</option>
                                    <option value="33">Arduino; Gogo Board; Scratch; Robomid; Robocode</option>
                                    <option value="34">HTML5; Javascript</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label" for="methodPublic">Público Alvo <span class="text-danger">*</span></label>
                                <select class="form-group__input form-group__input" type="text" id="methodPublic" name="publico" required>
                                    <option value="-1" disabled selected>Selecionar</option>
                                    <option value="1">Ensino Infantil</option>
                                    <option value="2">Ensino Fundamental I</option>
                                    <option value="3">Ensino Fundamental II</option>
                                    <option value="4">Ensino Médio</option>
                                    <option value="5">Formação para professores</option>
                                    <option value="6">Educação especial</option>
                                    <option value="7">Ensino Técnico</option>
                                    <option value="8">Ensino Superior</option>
                                    <option value="9">Fundamental II / Formação para professores / Ensino Médio / Ensino Técnico / Ensino Superior</option>
                                    <option value="10">Fundamental II / Ensino Médio / Ensino Técnico / Ensino Superior</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label" for="methodAbility">Habilidade Desenvolvida <span class="text-danger">*</span></label>
                                <select class="form-group__input form-group__input" type="text" id="methodAbility" name="habilidade" required>
                                    <option value="-1" disabled selected>Selecionar</option>
                                    <option value="1">Pensamento Lógico</option>
                                    <option value="2">Criatividade</option>
                                    <option value="3">Resolução de problemas</option>
                                    <option value="4">Programação</option>
                                    <option value="5">Não se aplica</option>
                                    <option value="6">Raciocínio lógico</option>
                                    <option value="7">Colaboração</option>
                                    <option value="8">Conhecimento computacional</option>
                                    <option value="9">Lógica</option>
                                    <option value="10">Sustentabilidade</option>
                                    <option value="11">Abstração</option>
                                    <option value="12">Resolução de problemas / Raciocínio Lógico / Criatividade / Colaboração / Programação / Lógica / Conhecimento computacional</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label" for="methodLink">Link de Acesso <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input class="form-group__input form-group__input--line" type="text" placeholder="Informe o link de acesso do método educacional" id="methodLink" name="link" required maxlength="200" autocomplete="off">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </section>
    </main>

    <div class="modal fade" id="editCatalogModal" aria-hidden="true" aria-labelledby="editCatalogModalLabel" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header u-box-padding--vertical u-box-padding--horizontal-big border-bottom">
                    <h4 class="fs-1 fw-bolder mb-0">Informações do Catálogo</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body border-0 u-box-padding--vertical pb-0 u-box-padding--horizontal-big">
                <form id="editCatalogForm" class="form">
                            <input class="form-group__input form-group__input--line" type="hidden" id="catalogId" name="catalogId">
                            <input class="form-group__input form-group__input--line" type="hidden" id="categoryId" name="categoryId">
                            <div class="form-group">
                                <label class="form-group__label" for="catalogTitle">Título <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input class="form-group__input form-group__input--line" type="text" placeholder="Informe o título" id="catalogTitle" name="titulo" required="" maxlength="200" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label" for="catalogContent">Conteúdo <span class="text-danger">*</span></label>
                                <select class="form-group__input form-group__input" id="catalogContent" name="conteudo" required="">
                                    <option value="Computação Física; ComFAPOO; Arduino; C++; Avaliações">Computação Física; ComFAPOO; Arduino; C++; Avaliações</option>
                                    <option value="Computação “Desplugada” (CD); Jogos Digitais (JD); Linguagem de Programação (LP); Linguagem de Programação Visual (LPV); Robótica Pedagógica (RP)">Computação “Desplugada” (CD); Jogos Digitais (JD); Linguagem de Programação (LP); Linguagem de Programação Visual (LPV); Robótica Pedagógica (RP)</option>
                                    <option value="Scratch; Code.Org; Era uma vez">Scratch; Code.Org; Era uma vez</option>
                                    <option value="Computação Física; Arduino">Computação Física; Arduino</option>
                                    <option value="Arduino; Makey Makey; Lego Mindstorms; LilyPad Arduino">Arduino; Makey Makey; Lego Mindstorms; LilyPad Arduino</option>
                                    <option value="Computação Física; ComFAPOO">Computação Física; ComFAPOO</option>
                                    <option value="Apresentação Gradativa; Jogos Digitais; Novas Linguagens; Mapas Conceituais; Metodologias Ativas; PBL;Recursos Multimídias; Robótica Pedagógicas; Scratch">Apresentação Gradativa; Jogos Digitais; Novas Linguagens; Mapas Conceituais; Metodologias Ativas; PBL;Recursos Multimídias; Robótica Pedagógicas; Scratch</option>
                                    <option value="Arduino; Robotica; Plataformas Diversas, Atividades Desplugadas; Programação em Blocos">Arduino; Robotica; Plataformas Diversas, Atividades Desplugadas; Programação em Blocos</option>
                                    <option value="Thinkertank; Unity; Linguagem C#">Thinkertank; Unity; Linguagem C#</option>
                                    <option value="Gestão de projetos; PMBOK; Braindraw; Avaliação Heurística">Gestão de projetos; PMBOK; Braindraw; Avaliação Heurística</option>
                                    <option value="Arduino; Gogo Board; Scratch; Robomid; Robocode">Arduino; Gogo Board; Scratch; Robomid; Robocode</option>
                                    <option value="HTML5; Javascript">HTML5; Javascript</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label" for="catalogTool">Ferramenta <span class="text-danger">*</span></label>
                                <select class="form-group__input form-group__input" type="text" id="catalogTool" name="ferramenta" required="">
                                    <option value="1">GNU</option>
                                    <option value="2">Estilo BSD</option>
                                    <option value="3">Papel e lápis</option>
                                    <option value="4">Scratch</option>
                                    <option value="5">App Inventor</option>
                                    <option value="6">Computação Física</option>
                                    <option value="7">HTML</option>
                                    <option value="8">HTML / CSS</option>
                                    <option value="9">Python</option>
                                    <option value="10">Robótica Educacional</option>
                                    <option value="11">Programação</option>
                                    <option value="12">MIT</option>
                                    <option value="13">Apache</option>
                                    <option value="14">WTFPL</option>
                                    <option value="15">Geogebra</option>
                                    <option value="16">D. Público</option>
                                    <option value="17">Não se aplica</option>
                                    <option value="18">Beer License</option>
                                    <option value="19">MirOS</option>
                                    <option value="20">ISC</option>
                                    <option value="21">EPL</option>
                                    <option value="22">XSkat</option>
                                    <option value="23">Computação Física; ComFAPOO; Arduino; C++; Avaliações</option>
                                    <option value="24">Computação “Desplugada” (CD); Jogos Digitais (JD); Linguagem de Programação (LP); Linguagem de Programação Visual (LPV); Robótica Pedagógica (RP)</option>
                                    <option value="25">Scratch; Code.Org; Era uma vez</option>
                                    <option value="26">Computação Física; Arduino</option>
                                    <option value="27">Arduino; Makey Makey; Lego Mindstorms; LilyPad Arduino</option>
                                    <option value="28">Computação Física; ComFAPOO</option>
                                    <option value="29">Apresentação Gradativa; Jogos Digitais; Novas Linguagens; Mapas Conceituais; Metodologias Ativas; PBL;Recursos Multimídias; Robótica Pedagógicas; Scratch</option>
                                    <option value="30">Arduino; Robotica; Plataformas Diversas, Atividades Desplugadas; Programação em Blocos</option>
                                    <option value="31">Thinkertank; Unity; Linguagem C#</option>
                                    <option value="32">Gestão de projetos; PMBOK; Braindraw; Avaliação Heurística</option>
                                    <option value="33">Arduino; Gogo Board; Scratch; Robomid; Robocode</option>
                                    <option value="34">HTML5; Javascript</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label" for="catalogPublic">Público Alvo <span class="text-danger">*</span></label>
                                <select class="form-group__input form-group__input" type="text" id="catalogPublic" name="publico" required="">
                                    <option value="1">Ensino Infantil</option>
                                    <option value="2">Ensino Fundamental I</option>
                                    <option value="3">Ensino Fundamental II</option>
                                    <option value="4">Ensino Médio</option>
                                    <option value="5">Formação para professores</option>
                                    <option value="6">Educação especial</option>
                                    <option value="7">Ensino Técnico</option>
                                    <option value="8">Ensino Superior</option>
                                    <option value="9">Fundamental II / Formação para professores / Ensino Médio / Ensino Técnico / Ensino Superior</option>
                                    <option value="10">Fundamental II / Ensino Médio / Ensino Técnico / Ensino Superior</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label" for="catalogAbility">Habilidade Desenvolvida <span class="text-danger">*</span></label>
                                <select class="form-group__input form-group__input" type="text" id="catalogAbility" name="habilidade" required="">
                                    <option value="1">Pensamento Lógico</option>
                                    <option value="2">Criatividade</option>
                                    <option value="3">Resolução de problemas</option>
                                    <option value="4">Programação</option>
                                    <option value="5">Não se aplica</option>
                                    <option value="6">Raciocínio lógico</option>
                                    <option value="7">Colaboração</option>
                                    <option value="8">Conhecimento computacional</option>
                                    <option value="9">Lógica</option>
                                    <option value="10">Sustentabilidade</option>
                                    <option value="11">Abstração</option>
                                    <option value="12">Resolução de problemas / Raciocínio Lógico / Criatividade / Colaboração / Programação / Lógica / Conhecimento computacional</option>
                                </select>
                            </div>
                            <div id="catalogAmbientBox" class="form-group">
                                <label class="form-group__label" for="catalogAmbient">Ambiente do Jogo <span class="text-danger">*</span></label>
                                <select class="form-group__input form-group__input" type="text" id="catalogAmbient" name="ambiente" required="">
                                    <option value="Extracurricular">Extracurricular</option>
                                    <option value="Intracurricular">Intracurricular</option>
                                </select>
                            </div>
                            <div id="catalogApproachBox" class="form-group">
                                <label class="form-group__label" for="catalogApproach">Abordagem <span class="text-danger">*</span></label>
                                <select class="form-group__input form-group__input" type="text" id="catalogApproach" name="abordagem" required="">
                                    <option value="Instrucionista">Instrucionista</option>
                                    <option value="Intracurricular">Construcionista</option>
                                    <option value="Construtivista">Construtivista</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label" for="catalogLink">Link de Acesso <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input class="form-group__input form-group__input--line" type="text" placeholder="Informe o link de acesso" id="catalogLink" name="link" required="" maxlength="200" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="position-relative">
                                    <input class="form-group__input form-group__input--delete u-fw-500" type="text" placeholder='Digite "excluir" sem as aspas para remover o catálogo' id="catalogDeleteInput" name="catalogDeleteInput" maxlength="7" autocomplete="off">
                                    <button id="btnDeleteCatalog" type="button" disabled="" class="form-group__button form-group__button--delete">
                                    <svg class="w-50 h-50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M20.5001 6H3.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round"></path> <path d="M18.8332 8.5L18.3732 15.3991C18.1962 18.054 18.1077 19.3815 17.2427 20.1907C16.3777 21 15.0473 21 12.3865 21H11.6132C8.95235 21 7.62195 21 6.75694 20.1907C5.89194 19.3815 5.80344 18.054 5.62644 15.3991L5.1665 8.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round"></path> <path d="M9.5 11L10 16" stroke="#fff" stroke-width="1.5" stroke-linecap="round"></path> <path d="M14.5 11L14 16" stroke="#fff" stroke-width="1.5" stroke-linecap="round"></path> <path d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6" stroke="#fff" stroke-width="2"></path> </g></svg>
                                    </button>
                                </div>
                            </div>
                        </form>
                </div>
                <div class="modal-footer d-flex justify-content-end align-items-center border-0 u-box-padding--vertical u-box-padding--horizontal-big">
                    <button id="btnUpdateCatalog" class="fs-4 w-auto button-dark px-5" data-bs-toggle="modal" data-bs-dismiss="modal">Cancelar</button>
                        <button id="btnUpdateCatalog" onclick="updateCatalog(this);" class="fs-4 w-auto button-primary px-5">Salvar Alterações</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>