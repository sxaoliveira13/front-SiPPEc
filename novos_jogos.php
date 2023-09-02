<?php 
    //sippectoken = 624879f5e6346c9cda0e80421d95a37c8626a2ee0a215e261be42e355390100d
    if(!isset($_GET['t'])) {
        die('Acesso negado');
    }
    if($_GET['t'] !== hash('sha256', 'sippectoken')) {
        die('Acesso negado');
    } 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de conteúdos - Jogos</title>
    <link rel="stylesheet" href="style.css">
    <script src="multiselect.js"></script>
</head>
<body>
    <div class="container">
        <div class="content">
            <div class="side-column">
                <img src="imagens/logo completo sem fundo.png" class="logo"> 
                <a href="novos_artigos.html" class="menu-item btn" >Registrar Artigos</a>
                <a href="novos_jogos.html" class="menu-item btn" id="atual">Registrar Jogos</a>
                <a href="novos_metodos.html" class="menu-item btn">Registrar Métodos</a>
                <div class="submit-container">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                    <button class="btn btn-primary">Sair</button>
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
                            <label for="email">Email para contato:</label>
                            <input type="email" id="email" name="email" required placeholder="exemplo@gmail.com">
                        </div>
                        <div class="input-field">
                            <label for="link">Link de acesso ao conteúdo:</label>
                            <input type="url" id="link" name="link" required placeholder="Cole o link de acesso ao conteúdo">
                        </div>
                        <div class="input-field">
                            <label for="autor">Nome do autor do conteúdo:</label>
                            <input type="text" id="autor" name="autor" required placeholder="Digite o nome do autor">
                        </div>
                    </div>

                    <div class="dropdown-column">
                        <div class="input-field">
                            <label for="publico">Públicos</label>
                            <select id="publico">
                                <option disabled selected>Selecionar</option>
                                <option value="Fundamental II">Fundamental II</option>
                                <option value="Ensino Médio">Ensino Médio</option>
                                <option value="Formação para professores">Formação para professores</option>
                                <option value="Ensino Técnico">Ensino Técnico</option>
                                <option value="Ensino Superior">Ensino Superior</option>
                            </select>
                        </div>

                        <div class="input-field">
                            <label for="conteudo">Conteúdo</label>
                            <select id="conteudo">
                                <option disabled selected>Selecionar</option>
                                <option value="Variada">Variada</option>
                                <option value="Religioso">Ensino Religioso</option>
                                <option value="Linguagens">Linguagens e suas tecnologias</option>
                                <option value="Ciencias-humanas">Ciências Humanas e Sociais Aplicadas</option>
                                <option value="Mat">Matemática e suas Tecnologias</option>
                                <option value="Ciencias-natureza">Ciências da Natureza e suas Tecnologias</option>
                            </select>
                        </div>

                        <div class="input-field">
                            <label for="ferramenta">Ferramenta</label>
                            <select id="ferramenta">
                                <option disabled selected>Selecionar</option>
                                <option value="GNU">GNU</option>
                                <option value="BSD">Estilo BSD	</option>
                                <option value="MIT">MIT</option>
                                <option value="Apache">Apache</option>
                                <option value="WTFPL">WTFPL</option>
                                <option value="Geogebra">Geogebra</option>
                                <option value="Público">D. Público</option>
                                <option value="NSA">Não se aplica</option>
                                <option value="Beer License">Beer License</option>
                                <option value="MirOS">MirOS</option>
                                <option value="ISC">ISC</option>
                                <option value="EPL">EPL</option>
                            </select>
                        </div>

                        <div class="input-field">
                            <label for="habilidade">Habilidade</label>
                            <select id="habilidade">
                                <option disabled selected>Selecionar</option>
                                <option value="Resolução de problemas">Resolução de problemas</option>
                                <option value="Lógico">Pensamento Lógico</option>
                                <option value="Criatividade">Criatividade</option>
                                <option value="Abstração">Abstração</option>
                            </select>
                        </div>

                        <div class="input-fields-container">
                            <div class="input-field">
                                <label for="ambiente">Ambiente</label>
                                <select id="ambiente" class="two-selects">
                                    <option disabled selected>Selecionar</option>
                                    <option value="Extracurricular">Extracurricular</option>
                                    <option value="Intracurricular">Intracurricular</option>
                                </select>
                            </div>
                        
                            <div class="input-field">
                                <label for="abordagem">Abordagem</label>
                                <select id="abordagem" class="two-selects">
                                    <option disabled selected>Selecionar</option>
                                    <option value="Instrucionista">Instrucionista</option>
                                    <option value="Intracurricular">Construcionista</option>
                                </select>
                            </div>
                        </div>

                </form>
            </div>
        </div>
    </div>
    
</body>
</html>
