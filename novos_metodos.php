<?php 
   if(!isset($_GET['t']) || $_GET['t'] !== hash('sha256', 'sippectoken')) {
        header("Location: https://liag.ft.unicamp.br/act/");
        die();
    } 

    $pageName = 'Métodos';
    require(dirname(__FILE__).'/includes/head.php');
?>
<body>
    <div class="container">
        <div class="content">
            <div class="side-column">
                <img src="<?php echo $CFG['system_url'].'assets/img/logo completo sem fundo.png'?>" class="logo"> 
                <a href="<?php echo $CFG['system_url'].'novos_artigos.php?t='.$CFG['access_token']?>" class="menu-item btn btn-small">Registrar Artigos</a>
                <a href="<?php echo $CFG['system_url'].'novos_jogos.php?t='.$CFG['access_token']?>" class="menu-item btn btn-small">Registrar Jogos</a>
                <a href="<?php echo $CFG['system_url'].'novos_metodos.php?t='.$CFG['access_token']?>" class="menu-item btn btn-small" id="atual">Registrar Métodos</a>
                <div class="submit-container">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                    <button class="btn btn-primary">Sair</button>
                </div>
            </div>
            
            <div class="content-column">
                <h1 class="title">Cadastrar novo Método</h1>
                <div class="form-container">
                <form id="content-form" class="two-column-form">
                    <div class="form-column">
                        <div class="input-field">
                            <label for="titulo">Título</label>
                            <input type="text" id="titulo" name="titulo" required placeholder="Digite o título do conteúdo">
                        </div>
                        <div class="input-field">
                            <label for="email">Email para contato:</label>
                            <input type="email" id="email" name="email" required placeholder="exemplo@gmail.com">
                        </div>
                        <div class="input-field">
                            <label for="link">Link de acesso ao conteúdo:</label>
                            <input type="url" id="link" name="link" required  placeholder="Cole o link de acesso ao conteúdo">
                        </div>
                        <div class="input-field">
                            <label for="autor">Nome do autor do conteúdo:</label>
                            <input type="text" id="autor" name="autor" required placeholder="Digite o nome do autor">
                        </div>
                    </div>

                    <div class="dropdown-column">
                        <div class="input-field">
                            <label for="publico">Públicos</label>
                            <div class="multiselect">
                                <div class="selectBox" onclick="showCheckboxes_publico()">
                                    <select id="publico" class="closed">
                                        <option disabled selected>Selecionar</option>
                                    </select>
                                    <div class="overSelect"></div>
                                </div>
                                <div id="checkboxes-publico" class="closed">
                                    <label for="pub1">
                                        <input type="checkbox" id="pub1" />Fundamental II</label>
                                    <label for="pub2">
                                        <input type="checkbox" id="pub2" />Ensino Médio</label>
                                    <label for="pub3">
                                        <input type="checkbox" id="pub3" />Formação para professores</label>
                                    <label for="pub4">
                                        <input type="checkbox" id="pub4" />Ensino Técnico</label>
                                    <label for="pub5">
                                        <input type="checkbox" id="pub5" />Ensino Superior</label>
                                    <label for="pub6">
                                        <input type="checkbox" id="pub5" />Ensino Infantil</label>
                                    <label for="pub7">
                                        <input type="checkbox" id="pub7" />Ensino Superior</label>
                                </div>
                            </div>
                        </div>

                        <div class="input-field">
                            <label for="conteudo">Conteúdo</label>
                            <div class="multiselect">
                                <div class="selectBox" onclick="showCheckboxes_conteudo()">
                                    <select id="conteudo" class="closed" >
                                        <option disabled selected>Selecionar</option>
                                    </select>
                                    <div class="overSelect"></div>
                                </div>
                                <div id="checkboxes-conteudo" class="closed">
                                  <label for="one">
                                    <input type="checkbox" id="one" />Lista de instruções</label>
                                  <label for="two">
                                    <input type="checkbox" id="two" />Fluxograma</label>
                                  <label for="three">
                                    <input type="checkbox" id="three" />Números binários</label>
                                  <label for="four">
                                      <input type="checkbox" id="four" />Jogo Tabuleiro</label>
                                  <label for="five">
                                      <input type="checkbox" id="five"/>Código Morse</label>
                                  <label for="six">
                                      <input type="checkbox" id="six" />Programação e GitHub</label>
                                  <label for="seven">
                                      <input type="checkbox" id="seven" />Algoritmos</label>
                                  <label for="eight">
                                      <input type="checkbox" id="eight" />Scratch</label>
                                  <label for="nine">
                                      <input type="checkbox" id="five" />Arduíno</label>
                                  <label for="ten">
                                      <input type="checkbox" id="ten" />Genius</label>
                                  <label for="eleven">
                                      <input type="checkbox" id="eleven" />MontáMática</label>
                                  <label for="twelve">
                                      <input type="checkbox" id="twelve" />CSS</label>
                                  <label for="thirteen">
                                      <input type="checkbox" id="thirteen" />HTML</label>
                                  <label for="fourteen">
                                      <input type="checkbox" id="fourteen" />Desafio</label>
                                  <label for="fiveteen">
                                      <input type="checkbox" id="fiveteen" />Minecraft</label>
                                  <label for="sixteen">
                                      <input type="checkbox" id="sixteen" />PBL (Problem Based Learning)</label>
                                  <label for="nineteen">
                                      <input type="checkbox" id="nineteen" />Outros</label>                                </div>
                            </div>
                        </div>

                        <div class="input-field">
                            <label for="ferramenta">Ferramenta</label>
                            <div class="multiselect">
                                <div class="selectBox" onclick="showCheckboxes_ferramenta()">
                                    <select id="ferramenta"  class="closed">
                                        <option disabled selected>Selecionar</option>
                                    </select>
                                    <div class="overSelect"></div>
                                </div>
                                <div id="checkboxes-ferramenta"  class="closed">
                                  <label for="ferramenta1">
                                    <input type="checkbox" id="ferramenta1" />Papel e lápisa</label>
                                  <label for="ferramenta2">
                                    <input type="checkbox" id="ferramenta2" />Scratch</label>
                                  <label for="ferramenta3">
                                    <input type="checkbox" id="ferramenta3" />App Inventor</label>
                                  <label for="ferramenta4">
                                      <input type="checkbox" id="ferramenta4" />HTML/CSS</label>
                                  <label for="ferramenta5">
                                      <input type="checkbox" id="ferramenta5" />Python</label>                                </div>
                            </div>
                        </div>

                        <div class="input-field">
                            <label for="habilidade">Habilidade</label>
                            <div class="multiselect">
                                <div class="selectBox" onclick="showCheckboxes_habilidade()">
                                    <select id="habilidade"  class="closed">
                                        <option disabled selected>Selecionar</option>
                                    </select>
                                    <div class="overSelect" ></div>
                                </div>
                                <div id="checkboxes-habilidade"  class="closed">
                                  <label for="habilidade1">
                                    <input type="checkbox" id="habilidade1" />Resolução de problemas</label>
                                  <label for="habilidade2">
                                    <input type="checkbox" id="habilidade2" />Raciocínio Lógico</label>
                                  <label for="habilidade3">
                                    <input type="checkbox" id="habilidade3" />Criatividade</label>
                                  <label for="habilidade4">
                                      <input type="checkbox" id="habilidade4" />Colaboração</label>
                                  <label for="habilidade5">
                                      <input type="checkbox" id="habilidade5" />Programação</label>                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>
