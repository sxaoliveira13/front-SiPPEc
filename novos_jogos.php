<?php 
   if(!isset($_GET['t']) || $_GET['t'] !== hash('sha256', 'sippectoken')) {
        header("Location: https://liag.ft.unicamp.br/act/");
        die();
    } 

    $pageName = 'Jogos';
    require(dirname(__FILE__).'/includes/head.php');
?>
<body>
    <div class="container">
        <div class="content">
            <div class="side-column">
                <img src="<?php echo $CFG['system_url'].'assets/img/logo completo sem fundo.png'?>" class="logo"> 
                <a href="<?php echo $CFG['system_url'].'novos_artigos.php?t='.$CFG['access_token']?>" class="menu-item btn btn-small">Registrar Artigos</a>
                <a href="<?php echo $CFG['system_url'].'novos_jogos.php?t='.$CFG['access_token']?>" class="menu-item btn btn-small" id="atual">Registrar Jogos</a>
                <a href="<?php echo $CFG['system_url'].'novos_metodos.php?t='.$CFG['access_token']?>" class="menu-item btn btn-small">Registrar Métodos</a>
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
