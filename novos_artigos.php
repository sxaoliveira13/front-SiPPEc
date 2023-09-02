<?php 
    //sippectoken = 624879f5e6346c9cda0e80421d95a37c8626a2ee0a215e261be42e355390100d
    if(!isset($GET['t'])) {
        die('Acesso negado');
    }
    if($GET['t'] !== hash('256', 'sippectoken')) {
        die('Acesso negado');
    } else {
        die('Acesso negado');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de conteúdos - Artigos </title>
    <link rel="stylesheet" href="style.css">
    <script src="multiselect.js"></script>
</head>
<body>
    <div class="container">
        <div class="content">
            <div class="side-column">
                <img src="imagens/logo completo sem fundo.png" class="logo"> 
                <a href="novos_artigos.html" class="menu-item btn btn-small" id="atual">Registrar Artigos</a>
                <a href="novos_jogos.html" class="menu-item btn btn-small">Registrar Jogos</a>
                <a href="novos_metodos.html" class="menu-item btn btn-small">Registrar Métodos</a>  
                <div class="submit-container">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                    <button class="btn btn-primary">Sair</button>
                </div>   
            </div>

            <div class="content-column">
                <h1 class="title">Cadastrar novo Artigo Educacional</h1>
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
                                    <input type="checkbox" id="one" />Aprendizagem Significativa</label>
                                  <label for="two">
                                    <input type="checkbox" id="two" />Programação Orientada a Objetos</label>
                                  <label for="three">
                                    <input type="checkbox" id="three" />Ensino de Programação</label>
                                  <label for="four">
                                      <input type="checkbox" id="four" />Concreteness Fading</label>
                                  <label for="five">
                                      <input type="checkbox" id="five"/>Revisão Sistemática</label>
                                  <label for="six">
                                      <input type="checkbox" id="six" />Pensamento Computacional</label>
                                  <label for="seven">
                                      <input type="checkbox" id="seven" />Oficina</label>
                                  <label for="eight">
                                      <input type="checkbox" id="eight" />Mapeamento Sistemático</label>
                                  <label for="nine">
                                      <input type="checkbox" id="five" />Teoria da Aprendizagem Significativa</label>
                                  <label for="ten">
                                      <input type="checkbox" id="ten" /> Direcionamento de grupos de PC</label>
                                  <label for="eleven">
                                      <input type="checkbox" id="eleven" />Construcionismo</label>
                                  <label for="twelve">
                                      <input type="checkbox" id="twelve" />Jogos Sérios</label>
                                  <label for="thirteen">
                                      <input type="checkbox" id="thirteen" />Jogos Educativos</label>
                                  <label for="fourteen">
                                      <input type="checkbox" id="fourteen" />Desing Participativo</label>
                                  <label for="fiveteen">
                                      <input type="checkbox" id="fiveteen" />PBL (Problem Based Learning)</label>
                                  <label for="sixteen">
                                      <input type="checkbox" id="sixteen" />PBL (Problem Based Learning)</label>
                                  <label for="seventeen">
                                      <input type="checkbox" id="seventeen" />RP (Robótica Pedagógica)</label>
                                  <label for="eightteen">
                                      <input type="checkbox" id="eightteen" />Toxicologia Ambiental</label>
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
                                    <input type="checkbox" id="ferramenta1" />Computação Física</label>
                                  <label for="ferramenta2">
                                    <input type="checkbox" id="ferramenta2" />ComFAPOO</label>
                                  <label for="ferramenta3">
                                    <input type="checkbox" id="ferramenta3" />Arduino</label>
                                  <label for="ferramenta4">
                                      <input type="checkbox" id="ferramenta4" />C++</label>
                                  <label for="ferramenta5">
                                      <input type="checkbox" id="ferramenta5" />Computação “Desplugada” (CD)</label>                                </div>
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
