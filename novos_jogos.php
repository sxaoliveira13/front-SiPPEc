<?php

require_once("api/config.php");
require_once("api/functions.php");

$pageName = 'Jogos';

if(!isset($_COOKIE['userToken'])) {
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
<link rel="stylesheet" href="<?php echo $CFG['system_url'] ?>assets/css/forms.css">
<script>const userData = <?php echo json_encode($USERDATA); ?></script>

<body>
    <div class="container">
        <div class="content">
            <div class="side-column">
                <img src="<?php echo $CFG['system_url'] . 'assets/img/logo completo sem fundo.png' ?>" class="logo">
                <a href="<?php echo $CFG['system_url'] . 'novos_artigos.php' ?>" class="menu-item btn btn-small">Registrar Artigos</a>
                <a href="<?php echo $CFG['system_url'] . 'novos_jogos.php' ?>" class="menu-item btn btn-small" id="atual">Registrar Jogos</a>
                <a href="<?php echo $CFG['system_url'] . 'novos_metodos.php' ?>" class="menu-item btn btn-small">Registrar Métodos</a>
                <div class="submit-container">
                    <button id="btnSendGame" type="button" class="btn btn-primary">Enviar</button>
                    <button id="btnLogout" class="btn btn-primary">Sair</button>
                </div>
            </div>

            <div class="content-column">
                <h1 class="title">Cadastrar novo Jogo Educativos</h1>
                <div class="form-container">
                    <form id="content-form" class="two-column-form">
                        <div class="form-column">
                            <div class="input-field">
                                <label for="titulo">Título</label>
                                <input type="text" id="titulo" name="titulo" required placeholder="Digite o título do conteúdo">
                            </div>
                            <div class="input-field">
                                <label for="link">Link de acesso</label>
                                <input type="url" id="link" name="link" required placeholder="Link para acesso ao conteúdo">
                            </div>
                            <div class="input-field">
                                <label for="publico">Públicos</label>
                                <select id="publico" name="publico">
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
                            <div class="input-field">
                                    <label for="ambiente">Ambiente</label>
                                    <select id="ambiente" class="two-selects" name="ambiente">
                                        <option disabled selected>Selecionar</option>
                                        <option value="Extracurricular">Extracurricular</option>
                                        <option value="Intracurricular">Intracurricular</option>
                                    </select>
                                </div>
                        </div>

                        <div class="form-column">
                           

                            <div class="input-field">
                                <label for="ferramenta">Ferramenta</label>
                                <select id="ferramenta" name="ferramenta">
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

                            <div class="input-field">
                                <label for="habilidade">Habilidade</label>
                                <select id="habilidade" name="habilidade">
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
                            <div class="input-field">
                                <label for="conteudo">Conteúdo</label>
                                <select id="conteudo" name="conteudo">
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
                          
                                

                                <div class="input-field">
                                    <label for="abordagem">Abordagem</label>
                                    <select id="abordagem" class="two-selects" name="abordagem">
                                        <option disabled selected>Selecionar</option>
                                        <option value="Instrucionista">Instrucionista</option>
                                        <option value="Intracurricular">Construcionista</option>
                                        <option value="Construtivista">Construtivista</option>
                                    </select>
                                </div>
                            


                    </form>
                </div>
            </div>
        </div>

</body>

</html>