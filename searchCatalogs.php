<?php

require_once("api/config.php");
require_once("api/functions.php");

$currentPage = 'searchCatalogs';
$pageName = 'Catalogos';

if (!isset($_COOKIE['userToken'])) {
    header("Location: login.php");
    exit;
}

$USERDATA = checkToken($_COOKIE['userToken'] ?? []);

require(dirname(__FILE__) . '/includes/head.php');
?>

<link rel="stylesheet" href="<?php echo $CFG['system_url'] ?>assets/css/datatable.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="<?php echo $CFG['system_url'] ?>assets/js/datatable.min.js"></script>
<script src="<?php echo $CFG['system_url'] ?>js/searchCatalog.js"></script>
<style>
    main {
        max-width: 1600px !important;
    }

    .dt-container {
        width: 100%;
        background-color: #fff;
        padding: 1.5rem;
    }

    .datatable-scroll {
        overflow: auto;
        max-width: 95vw;
    }

    .datatable-scroll thead tr {
        background-color: #000 !important;
        color: #fff;
    }
</style>
</head>

<body>
    <?php require(dirname(__FILE__) . '/includes/header.php'); ?>
    <main class="main u-container u-flex-auto mt-4 mb-5">
        <form id="filtersForm" class="row bg-white mx-0 px-3 pt-3 mb-auto">
            <div class="col-xl-3 col-md-4 col-sm-6 col-12 my-3">
                <div class="form-group">
                    <label class="form-group__label" for="catalogType">Tipo de Catálogo </label>
                    <select class="form-group__input form-group__input" onchange="toggleFieldsVisibility(this.value);" id="catalogType" name="catalogType">
                        <option value="1">Artigo</option>
                        <option value="2">Jogo</option>
                        <option value="3">Método</option>
                    </select>
                    <script>
                        if (catalogType && typeof catalogType !== "undefined" && parseInt(catalogType) >= 1 && parseInt(catalogType) <= 3) {
                            console.log(catalogType)
                            document.getElementById('catalogType').value = catalogType;
                        }
                    </script>
                </div>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 col-12 my-3">
                <div class="form-group">
                    <label class="form-group__label" for="catalogTitle">Título</label>
                    <div class="position-relative">
                        <input class="form-group__input form-group__input--big" type="text" placeholder="Informe o título do catálogo" id="catalogTitle" name="catalogTitle" maxlength="200" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 col-12 my-3">
                <div class="form-group">
                    <label class="form-group__label" for="catalogContent">Conteúdo</label>
                    <select class="form-group__input form-group__input" id="catalogContent" name="catalogContent">
                        <option value="" selected=""></option>
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
            <div class="col-xl-3 col-md-4 col-sm-6 col-12 my-3">
                <div class="form-group">
                    <label class="form-group__label" for="catalogTool">Ferramenta</label>
                    <select class="form-group__input form-group__input" type="text" id="catalogTool" name="catalogTool">
                        <option value="" selected=""></option>
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
            <div class="col-xl-3 col-md-4 col-sm-6 col-12 my-3">
                <div class="form-group">
                    <label class="form-group__label" for="catalogPublic">Público Alvo</label>
                    <select class="form-group__input form-group__input" type="text" id="catalogPublic" name="catalogPublic">
                        <option value="" selected=""></option>
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
            <div class="col-xl-3 col-md-4 col-sm-6 col-12 my-3">
                <div class="form-group">
                    <label class="form-group__label" for="catalogAbility">Habilidade Desenvolvida</label>
                    <select class="form-group__input form-group__input" type="text" id="catalogAbility" name="catalogAbility">
                        <option value="" selected=""></option>
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
            <div id="environmentFieldBox" class="col-xl-3 col-md-4 col-sm-6 col-12 my-3 d-none">
                <div class="form-group">
                    <label class="form-group__label" for="catalogEnvironment">Ambiente</label>
                    <select class="form-group__input form-group__input" type="text" id="catalogEnvironment" name="catalogEnvironment">
                        <option value="" selected=""></option>
                        <option value="Extracurricular">Extracurricular</option>
                        <option value="Intracurricular">Intracurricular</option>
                    </select>
                </div>
            </div>
            <div id="approachFieldBox" class="col-xl-3 col-md-4 col-sm-6 col-12 my-3 d-none">
                <div class="form-group">
                    <label class="form-group__label" for="catalogApproach">Abordagem</label>
                    <select class="form-group__input form-group__input" type="text" id="catalogApproach" name="catalogApproach">
                        <option value="" selected=""></option>
                        <option value="Instrucionista">Instrucionista</option>
                        <option value="Intracurricular">Construcionista</option>
                        <option value="Construtivista">Construtivista</option>
                    </select>
                </div>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6 col-12 my-3">
                <div class="form-group">
                    <label class="form-group__label" for="catalogLink">Link de Acesso</label>
                    <div class="position-relative">
                        <input class="form-group__input form-group__input--big" type="text" placeholder="Informe o link" id="catalogLink" name="catalogLink" maxlength="200" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-12 d-flex align-items-center justify-content-end border-top mt-4 py-4">
                <button type="button" id="btnResetFilters" onclick="resetFilters();" class="button-danger fs-4 w-100 px-5 ms-auto me-3" style="max-width: 200px !important;">Reiniciar Filtros</button>
                <button type="button" id="btnFilters" onclick="buildCatalogsDatatable()" class="button-primary fs-4 w-100 px-5" style="max-width: 300px !important;">Aplicar Filtros</button>
            </div>
        </form>
        <span id="loader" class="loader my-auto"></span>
        <div id="searchDatatable1" class="w-100 mt-5 mb-auto d-none">
            <h3 class="searched-catalogs-count fw-bolder mb-4">0 catálogos encontrados</h3>
            <table id="catalogTableType1" class="table">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Público Alvo</th>
                        <th>Conteúdo</th>
                        <th>Ferramenta</th>
                        <th>Habilidade</th>
                        <th>Link</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div id="searchDatatable2" class="w-100 mt-5 mb-auto d-none">
            <h3 class="searched-catalogs-count fw-bolder mb-4">0 catálogos encontrados</h3>
            <table id="catalogTableType2" class="table">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Público Alvo</th>
                        <th>Conteúdo</th>
                        <th>Ferramenta</th>
                        <th>Habilidade</th>
                        <th>Ambiente</th>
                        <th>Abordagem</th>
                        <th>Link</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

    </main>
    <?php require(dirname(__FILE__) . '/includes/footer.php'); ?>
</body>

</html>