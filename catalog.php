<?php

require_once("api/config.php");
require_once("api/functions.php");

$currentPage = 'catalog';
$pageName = 'Cadastro de Catálogos';

require(dirname(__FILE__) . '/includes/authRedirect.php');
require(dirname(__FILE__) . '/includes/head.php');
?>
<link rel="stylesheet" href="<?php echo $CFG['system_url'] ?>assets/css/datatable.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="<?php echo $CFG['system_url'] ?>assets/js/datatable.min.js"></script>
<script src="<?php echo $CFG['system_url'] ?>js/catalog.js"></script>

</head>

<body>
    <?php require(dirname(__FILE__) . '/includes/authenticatedHeader.php'); ?>
    <main class="main main__grid--1 u-container u-flex-auto">
        <section class="main__section--box h-100">
            <div class="main__section-header border-bottom">
                <div class="d-flex justify-content-between align-items-center u-box-padding--horizontal u-box-padding--vertical">
                    <h2 class="mb-0">Seus Catálogos</h2>
                    <span id="userCatalogsQuantity" class="badge badge--primary badge--rounded badge--rounded-big">0</span>
                </div>
            </div>
            <div class="form-group position-relative u-box-padding--horizontal u-box-padding--vertical">
                <label for="filterCatalog" class="d-flex align-items-center">
                    <svg class="form-group__input-icon form-group__input-icon--left" style="cursor: default !important;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path d="M19 3H5C3.58579 3 2.87868 3 2.43934 3.4122C2 3.8244 2 4.48782 2 5.81466V6.50448C2 7.54232 2 8.06124 2.2596 8.49142C2.5192 8.9216 2.99347 9.18858 3.94202 9.72255L6.85504 11.3624C7.49146 11.7206 7.80967 11.8998 8.03751 12.0976C8.51199 12.5095 8.80408 12.9935 8.93644 13.5872C9 13.8722 9 14.2058 9 14.8729L9 17.5424C9 18.452 9 18.9067 9.25192 19.2613C9.50385 19.6158 9.95128 19.7907 10.8462 20.1406C12.7248 20.875 13.6641 21.2422 14.3321 20.8244C15 20.4066 15 19.4519 15 17.5424V14.8729C15 14.2058 15 13.8722 15.0636 13.5872C15.1959 12.9935 15.488 12.5095 15.9625 12.0976C16.1903 11.8998 16.5085 11.7206 17.145 11.3624L20.058 9.72255C21.0065 9.18858 21.4808 8.9216 21.7404 8.49142C22 8.06124 22 7.54232 22 6.50448V5.81466C22 4.48782 22 3.8244 21.5607 3.4122C21.1213 3 20.4142 3 19 3Z" stroke="#C5C5C5" stroke-width="2.5"></path>
                        </g>
                    </svg>
                </label>
                <input class="form-group__input form-group__input--filter" onkeyup="search('catalogsList', this.value)" type="text" placeholder="Pesquisar" id="filterCatalog" name="filterCatalog" autocomplete="off">
            </div>
            <ul id="catalogsList" class="main__section-catalogs u-box-padding--horizontal u-overflow-auto position-relative my-0">
                <div id="catalogListLoader" class="position-absolute top-50 start-50 translate-middle">
                    <span class="loader"></span>
                </div>
                <h3 id="noResultsText" style="line-height: 1.7" class="u-text-muted--2 d-flex align-items-center justify-content-center text-center h-100 mt-3 mb-0">
                    Nenhum resultado encontrado para sua pesquisa
                </h3>
            </ul>
        </section>
        <section class="main__grid--2 h-100">
            <section class="main__section--box overflow-hidden px-0">
                <ul class="nav nav-tabs nav-tabs--1 d-flex flex-nowrap u-box-padding--horizontal" id="myTab" role="tablist">
                    <li class="nav-item d-none" id="manageSolicitations" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#manageSolicitationsTab" type="button" role="tab" aria-selected="false">
                            <div class="d-flex justify-content-center align-items-center">Gerenciamento
                                <span id="totalSolicitationsQuantity" class="badge badge--danger badge--rounded badge--rounded-medium ms-3" style="opacity: 0;">0</span>
                            </div>
                            <div class="tab-line"></div>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#articleTab" type="button" role="tab" aria-selected="true"><span class="d-md-inline d-none">Registrar</span> Artigos
                            <div class="tab-line"></div>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#educationGamesTab" type="button" role="tab" aria-selected="false"><span class="d-md-inline d-none">Registrar</span> Jogos
                            <div class="tab-line"></div>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#methodsTab" type="button" role="tab" aria-selected="false"><span class="d-md-inline d-none">Registrar</span> Métodos
                            <div class="tab-line"></div>
                        </button>
                    </li>
                </ul>
            </section>
            <section class="main__section--box u-overflow-auto">
                <div class="tab-content d-flex flex-column">
                    <div class="tab-pane fade flex-auto" id="manageSolicitationsTab" role="tabpanel">
                        <div class="d-flex flex-wrap align-items-center justify-content-sm-start justify-content-center position-sticky bg-white top-0 u-box-padding--vertical u-box-padding--horizontal-big border-bottom" style="z-index: 2; min-height: 10.7rem">
                            <h4 class="fs-2 text-center fw-bolder mb-sm-0 mb-0" style="line-height: 1.7">Gerenciamento de Cátalogos e Usuários</h4>
                        </div>
                        <div class="u-box-padding--vertical u-box-padding--horizontal-big">
                            <ul class="nav nav-tabs nav-tabs--2 w-100 mb-3">
                                <li class="nav-item">
                                    <button class="nav-link d-flex align-items-center active" data-bs-toggle="tab" data-bs-target="#newCatalogs" type="button" role="tab" aria-selected="true">
                                        Catálogos
                                        <span id="newCatalogsQuantity" class="badge badge--danger badge--rounded badge--rounded-small ms-3" style="display: none !important;">0</span>
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link d-flex align-items-center" data-bs-toggle="tab" data-bs-target="#newUsers" type="button" role="tab" aria-selected="false">Usuários
                                        <span id="newUsersQuantity" class="badge badge--danger badge--rounded badge--rounded-small ms-3" style="display: none !important;">0</span>
                                    </button>
                                </li>
                                <!-- <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#registredUsers" type="button" role="tab" aria-selected="false">Usuários Cadastrados</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#searchCatalogs   " type="button" role="tab" aria-selected="false">Pesquisar Catálogos</button>
                                </li> -->
                            </ul>
                        </div>
                        <div class="tab-content d-flex flex-column u-box-padding--horizontal-big">
                            <div class="tab-pane fade show active flex-auto" id="newCatalogs" role="tabpanel">
                                <table class="table datatableNewCatalogs">
                                    <thead>
                                        <tr>
                                            <th>Título</th>
                                            <th>Catálogo</th>
                                            <th>Status</th>
                                            <th>Usuário</th>
                                            <th>Criado em</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade flex-auto" id="newUsers" role="tabpanel">
                                <table class="table datatableNewUserRegisters">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Telefone</th>
                                            <th>Email</th>
                                            <th>Criado em</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade flex-auto" id="registredUsers" role="tabpanel">

                            </div>
                            <div class="tab-pane fade flex-auto" id="searchCatalogs" role="tabpanel">

                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade show flex-auto active" id="articleTab" role="tabpanel">
                        <div class="d-flex flex-sm-row flex-column flex-wrap align-items-center justify-content-between position-sticky bg-white top-0 u-box-padding--vertical u-box-padding--horizontal-big border-bottom" style="z-index: 2; min-height: 10.7rem">
                            <h4 class="fs-2 fw-bolder mb-sm-0 mb-2">Cadastro de Artigo</h4>
                            <button id="btnNewArticle" class="w-auto button-primary px-5">Cadastrar Artigo</button>
                        </div>
                        <form id="articleForm" class="form u-box-padding--vertical u-box-padding--horizontal-big">

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
                                <select class="form-group__input form-group__input" type="text" id="articleTool" name="ferramenta" required>
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
                        <div class="d-flex flex-sm-row flex-column flex-wrap align-items-center justify-content-between position-sticky bg-white top-0 u-box-padding--vertical u-box-padding--horizontal-big border-bottom" style="z-index: 2; min-height: 10.7rem">
                            <h4 class="fs-2 fw-bolder mb-sm-0 mb-2">Cadastro de Jogo Educacional</h4>
                            <button id="btnNewGame" class="w-auto button-primary px-5">Cadastrar Jogo</button>
                        </div>
                        <form id="gameForm" class="form u-box-padding--vertical u-box-padding--horizontal-big">
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
                                    <option value="-1" disabled selected>Selecionar</option>
                                    <option value="Extracurricular">Extracurricular</option>
                                    <option value="Intracurricular">Intracurricular</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-group__label" for="gameApproach">Abordagem <span class="text-danger">*</span></label>
                                <select class="form-group__input form-group__input" type="text" id="gameApproach" name="abordagem" required>
                                    <option value="-1" disabled selected>Selecionar</option>
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
                        <div class="d-flex flex-sm-row flex-column flex-wrap align-items-center justify-content-between position-sticky bg-white top-0 u-box-padding--vertical u-box-padding--horizontal-big border-bottom" style="z-index: 2; min-height: 10.7rem">
                            <h4 class="fs-2 fw-bolder mb-sm-0 mb-2">Cadastro de Método Educacional</h4>
                            <button id="btnNewMethod" class="w-auto button-primary px-5">Cadastrar Método</button>
                        </div>
                        <form id="methodForm" class="form u-box-padding--vertical u-box-padding--horizontal-big">
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
            <script>
                showManageTab();
            </script>
        </section>
    </main>

    <div class="modal fade" id="editCatalogModal" aria-hidden="true" aria-labelledby="editCatalogModalLabel" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header u-box-padding--vertical u-box-padding--horizontal-big border-bottom">
                    <h4 class="fs-1 fw-bolder mb-0">Informações do Catálogo</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body border-0 u-box-padding--vertical pb-0 u-box-padding--horizontal-big">
                    <div id="catalogCurrentStatus" class="card shadow-none p-5 mb-5">
                        <div class="card-header bg-transparent border-0 p-0">
                            <h5 id="catalogCurrentStatusLabel" class="fw-bolder text-uppercase text-white fs-4 mb-0"></h5>
                        </div>
                        <div id="catalogCurrentMessage" class="card-body border-0 p-0 mt-4">

                        </div>
                    </div>
                    <form id="editCatalogForm" class="form">
                        <div class="row">
                            <input class="form-group__input form-group__input--line" type="hidden" id="categoryId" name="categoryId">
                            <input class="form-group__input form-group__input--line" type="hidden" id="catalogId" name="catalogId">
                            <div class="col-12 mb-5">
                                <div class="form-group">
                                    <label class="form-group__label" for="catalogTitle">Título <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input class="form-group__input form-group__input--line" type="text" placeholder="Informe o título" id="catalogTitle" name="titulo" required="" maxlength="200" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-12 mb-5">
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
                            </div>
                            <div class="col-xl-6 col-md-12 mb-5">
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
                            </div>
                            <div class="col-xl-6 col-md-12 mb-5">
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
                            </div>
                            <div class="col-xl-6 col-md-12 mb-5">
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
                            </div>
                            <div id="catalogAmbientBox" class="col-xl-6 col-md-12 mb-5">
                                <div class="form-group">
                                    <label class="form-group__label" for="catalogAmbient">Ambiente do Jogo <span class="text-danger">*</span></label>
                                    <select class="form-group__input form-group__input" type="text" id="catalogAmbient" name="ambiente" required="">
                                        <option value="Extracurricular">Extracurricular</option>
                                        <option value="Intracurricular">Intracurricular</option>
                                    </select>
                                </div>
                            </div>
                            <div id="catalogApproachBox" class="col-xl-6 col-md-12 mb-5">
                                <div class="form-group">
                                    <label class="form-group__label" for="catalogApproach">Abordagem <span class="text-danger">*</span></label>
                                    <select class="form-group__input form-group__input" type="text" id="catalogApproach" name="abordagem" required="">
                                        <option value="Instrucionista">Instrucionista</option>
                                        <option value="Intracurricular">Construcionista</option>
                                        <option value="Construtivista">Construtivista</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 mb-5">
                                <div class="form-group">
                                    <label class="form-group__label" for="catalogLink">Link de Acesso <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input class="form-group__input form-group__input--line" type="text" placeholder="Informe o link de acesso" id="catalogLink" name="link" required="" maxlength="200" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div id="catalogDeleteInputBox" class="col-12 mb-5">
                                <div class="form-group">
                                    <div class="position-relative">
                                        <input class="form-group__input form-group__input--delete u-fw-500" type="text" placeholder='Digite "excluir" sem as aspas para remover o catálogo' id="catalogDeleteInput" name="catalogDeleteInput" maxlength="7" autocomplete="off">
                                        <button id="btnDeleteCatalog" type="button" disabled="" class="form-group__button form-group__button--delete">
                                            <svg class="w-50 h-50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                                <g id="SVGRepo_iconCarrier">
                                                    <path d="M20.5001 6H3.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round"></path>
                                                    <path d="M18.8332 8.5L18.3732 15.3991C18.1962 18.054 18.1077 19.3815 17.2427 20.1907C16.3777 21 15.0473 21 12.3865 21H11.6132C8.95235 21 7.62195 21 6.75694 20.1907C5.89194 19.3815 5.80344 18.054 5.62644 15.3991L5.1665 8.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round"></path>
                                                    <path d="M9.5 11L10 16" stroke="#fff" stroke-width="1.5" stroke-linecap="round"></path>
                                                    <path d="M14.5 11L14 16" stroke="#fff" stroke-width="1.5" stroke-linecap="round"></path>
                                                    <path d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6" stroke="#fff" stroke-width="2"></path>
                                                </g>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-end align-items-center border-0 u-box-padding--vertical u-box-padding--horizontal-big">
                    <button class="fs-4 w-auto button-dark px-5" data-bs-dismiss="modal">Cancelar</button>
                    <button id="btnRequestRegistrationReview" onclick="requestCatalogRegistrationReview(this);" style="min-width: 200px" class="fs-4 w-auto button-primary px-5 d-none">Solicitar revisão de cadastro</button>
                    <button id="btnCatalogCancelDelete" onclick="" style="min-width: 200px" class="fs-4 w-auto button-danger text-center px-5">Cancelar solicitação de exclusão do catálago</button>
                    <button id="btnUpdateCatalog" onclick="updateCatalog(this);" style="min-width: 200px" class="fs-4 w-auto button-primary px-5">Salvar Alterações</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="catalogSolicitationModal" aria-hidden="true" aria-labelledby="catalogSolicitationModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header u-box-padding--vertical u-box-padding--horizontal-big border-bottom">
                    <h4 class="fs-2 fw-bolder mb-0">Gerenciamento de Catálogo</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body border-0 u-box-padding--vertical pb-0 u-box-padding--horizontal-big pb-0">
                    <ul class="nav nav-tabs nav-tabs--2 w-100 mb-3">
                        <li class="nav-item">
                            <button class="nav-link d-flex align-items-center active" data-bs-toggle="tab" data-bs-target="#catalogInfo" type="button" role="tab" aria-selected="true">
                                Informações do Catálogo
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link d-flex align-items-center" data-bs-toggle="tab" data-bs-target="#userInfo" type="button" role="tab" aria-selected="false">Informações do Usuário
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content d-flex flex-column u-box-padding--vertical pb-0">
                        <div class="tab-pane fade show active flex-auto" id="catalogInfo" role="tabpanel">
                            <div class="row">
                                <div class="d-flex align-items-baseline col-12 mb-5">
                                    <h3 class="fw-bolder mb-3">Status Atual:</h3>&nbsp;
                                    <p id="catalogSolicitationType" class="text-uppercase mb-0"></p>
                                </div>
                                <div class="col-6 mb-5 border-end pe-5">
                                    <h3 class="fw-bolder mb-3">Tipo</h3>
                                    <p id="catalogType" class=" mb-0"></p>
                                </div>
                                <div class="col-6 mb-5 ps-5">
                                    <h3 class="fw-bolder mb-3">Publicado</h3>
                                    <p id="catalogIsActive" class="mb-0"></p>
                                </div>
                                <div class="col-6 mb-5 border-end pe-5">
                                    <h3 class="fw-bolder mb-3">Título</h3>
                                    <p id="catalogInternalTitle" class="mb-0"></p>
                                </div>
                                <div class="col-6 mb-5 ps-5">
                                    <h3 class="fw-bolder mb-3">Público Alvo</h3>
                                    <p id="catalogPublicName" class="mb-0"></p>
                                </div>
                                <div class="col-6 mb-5 border-end pe-5">
                                    <h3 class="fw-bolder mb-3">Conteúdo</h3>
                                    <p id="catalogContentType" class="mb-0"></p>
                                </div>
                                <div class="col-6 mb-5 ps-5">
                                    <h3 class="fw-bolder mb-3">Ferramenta</h3>
                                    <p id="catalogToolName" class="mb-0"></p>
                                </div>
                                <div class="col-6 mb-5 border-end pe-5">
                                    <h3 class="fw-bolder mb-3">Habilidade</h3>
                                    <p id="catalogAbilityName" class="mb-0"></p>
                                </div>
                                <div class="col-6 mb-5 ps-5">
                                    <h3 class="fw-bolder mb-3">Link de Acesso</h3>
                                    <a href="#" target="_blank" class="text-link" id="catalogAccessLink" class="mb-0"></a>
                                </div>
                                <div class="col-6 mb-5 border-end pe-5 d-none">
                                    <h3 class="fw-bolder mb-3">Ambiente</h3>
                                    <p id="newCatalogAmbient" class="mb-0"></p>
                                </div>
                                <div class="col-6 mb-5 ps-5 d-none">
                                    <h3 class="fw-bolder mb-3">Abordagem</h3>
                                    <p id="newCatalogApproach" class="mb-0"></p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade flex-auto" id="userInfo" role="tabpanel">
                            <div class="row justify-content-center">
                                <div class="col-lg-4 col-6 mb-5 text-center border-start pe-md-5">
                                    <h3 class="fw-bolder mb-3">Nome</h3>
                                    <p id="userFullName" class="mb-0"></p>
                                </div>
                                <div class="col-lg-4 col-6  mb-5 text-center border-start px-md-5">
                                    <h3 class="fw-bolder mb-3">Email</h3>
                                    <p id="userMail" class="u-break-word mb-0"></p>
                                </div>
                                <div class="col-lg-4 col-6 mb-5 text-center border-start ps-md-5">
                                    <h3 class="fw-bolder mb-3">Telefone</h3>
                                    <p id="userPhone" class="mb-0"></p>
                                </div>
                                <div class="col-lg-4 col-6 mb-5 text-center border-start pe-md-5">
                                    <h3 class="fw-bolder mb-3">Criado em</h3>
                                    <p id="userCreateTime" class="mb-0"></p>
                                </div>
                                <div class="col-lg-4 col-6 mb-5 text-center border-start ps-md-5">
                                    <h3 class="fw-bolder mb-3">Último acesso em</h3>
                                    <p id="userLastAccess" class="mb-0"></p>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-sm-evenly justify-content-center center">
                                <div class="col-lg-3 col-6 mb-5">
                                    <div class="d-flex flex-column text-center py-5 px-2 border">
                                        <h3 id="articlesCount" class="fs-1 fw-bolder text-primary mb-3">0</h3>
                                        <span class="fs-2 fw-bolder">Artigos</span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6 mb-5">
                                    <div class="d-flex flex-column text-center py-5 px-2 border">
                                        <h3 id="gamesCount" class="fs-1 fw-bolder text-primary mb-3">0</h3>
                                        <span class="fs-2 fw-bolder">Jogos</span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6 mb-5">
                                    <div class="d-flex flex-column text-center py-5 px-2 border">
                                        <h3 id="methodsCount" class="fs-1 fw-bolder text-primary mb-3">0</h3>
                                        <span class="fs-2 fw-bolder">Métodos</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group d-flex flex-column mb-5">
                        <label for="catalogApproveMessage" class="fs-4 fw-bolder mb-2">Mensagem de Aprovação</label>
                        <textarea class="form-group__input w-100 u-fw-500 py-3" id="catalogApproveMessage" name="catalogApproveMessage" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center align-items-center border-0 u-box-padding--vertical u-box-padding--horizontal-big border-top">
                    <button class="fs-4 w-auto button-dark px-5" data-bs-dismiss="modal">Cancelar</button>
                    <button style="min-width: 200px" onclick="approveCatalog(0);" class="fs-4 w-auto button-danger px-5">Recusar Solicitação</button>
                    <button style="min-width: 200px" onclick="approveCatalog(1);" class="fs-4 w-auto button-primary px-5">Aprovar Solicitação</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>