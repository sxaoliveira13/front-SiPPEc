<?php

require_once("../config.php");
require_once("../functions.php");

header('Content-Type: application/json; charset=utf-8');
$USERDATA = checkToken($_COOKIE['userToken']);

if (empty($USERDATA['userId'])) {
    error("Autenticação inválida ", 1);
}
if ($USERDATA['userType'] != 1 && $USERDATA['userType'] != 2) {
    error("Você não tem permissão para isso! ", 2);
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    error('Método de requisição inválido');
}

$out = array('success' => true);

$json = file_get_contents('php://input');
$data = json_decode($json, true);
$data = sanitize($data);
$catalogId = (int)$data['catalogId'];

if ((int)$USERDATA['userId'] !== (int)$data['userId']) {
    http_response_code(401);
    error("Autenticação inválida", 1);
}

$ambiente = $data['ambiente'] ?? null;
$abordagem = $data['abordagem'] ?? null;

$status = 5;

try {
    $CFG['link']->beginTransaction();

    $sql = "SELECT id, Status as status FROM catalogo WHERE id = :catalogId AND userId = :userId";
    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':catalogId', $catalogId, PDO::PARAM_INT);
    $stmt->bindParam(':userId', $USERDATA['userId'], PDO::PARAM_INT);
    $stmt->execute();

    $catalog = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($catalog)) {
        error("Você não tem permissão para alterar este catálogo!");
    }

    if ($catalog['status'] == '1' || $USERDATA['userType'] == 2) {
        if ($USERDATA['userType'] == 1) {
            $status = 1;
        }

        $sql = "UPDATE catalogo 
                SET Titulo = :titulo, 
                    PublicoAlvoId = :publico, 
                    Conteudo = :conteudo, 
                    FerramentaId = :ferramenta, 
                    HabilidadeId = :habilidade, 
                    Ambiente = :ambiente, 
                    Abordagem = :abordagem, 
                    CaminhoDeAcesso = :link,
                    Status = :status,
                    Ciclo = Ciclo + 1
                WHERE id = :catalogId";

        $stmt = $CFG['link']->prepare($sql);
        $stmt->bindParam(':titulo', $data['titulo'], PDO::PARAM_STR);
        $stmt->bindParam(':publico', $data['publico'], PDO::PARAM_INT);
        $stmt->bindParam(':conteudo', $data['conteudo'], PDO::PARAM_STR);
        $stmt->bindParam(':ferramenta', $data['ferramenta'], PDO::PARAM_INT);
        $stmt->bindParam(':habilidade', $data['habilidade'], PDO::PARAM_INT);
        $stmt->bindParam(':ambiente', $data['ambiente'], PDO::PARAM_STR);
        $stmt->bindParam(':abordagem', $data['abordagem'], PDO::PARAM_STR);
        $stmt->bindParam(':link', $data['link'], PDO::PARAM_STR);
        $stmt->bindParam(':catalogId', $catalogId, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        $sql = "UPDATE catalogo 
                SET Status = 4, 
                    AguardandoRevisao = 1,
                    Ciclo = Ciclo + 1 
                WHERE id = :catalogId";
        $stmt = $CFG['link']->prepare($sql);
        $stmt->bindParam(':catalogId', $catalogId, PDO::PARAM_INT);
        $stmt->execute();

        $sql = "SELECT id FROM catalogoAtualizado WHERE CatalogoId = :catalogId";
        $stmt = $CFG['link']->prepare($sql);
        $stmt->bindParam(':catalogId', $catalogId, PDO::PARAM_INT);
        $stmt->execute();
        $catalogAtualizado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($catalogAtualizado) {
            $sql = "UPDATE catalogoAtualizado 
                    SET CategoriaId = :categoria, 
                        Titulo = :titulo, 
                        PublicoAlvoId = :publico, 
                        Conteudo = :conteudo, 
                        FerramentaId = :ferramenta, 
                        HabilidadeId = :habilidade, 
                        Ambiente = :ambiente, 
                        Abordagem = :abordagem, 
                        CaminhoDeAcesso = :link
                    WHERE CatalogoId = :catalogId";
        } else {
            $sql = "INSERT INTO catalogoAtualizado (CatalogoId, CategoriaId, Titulo, PublicoAlvoId, Conteudo, FerramentaId, HabilidadeId, Ambiente, Abordagem, CaminhoDeAcesso) 
                    VALUES (:catalogId, :categoria, :titulo, :publico, :conteudo, :ferramenta, :habilidade, :ambiente, :abordagem, :link)";
        }

        $stmt = $CFG['link']->prepare($sql);
        $stmt->bindParam(':titulo', $data['titulo'], PDO::PARAM_STR);
        $stmt->bindParam(':publico', $data['publico'], PDO::PARAM_INT);
        $stmt->bindParam(':categoria', $data['categoryId'], PDO::PARAM_INT);
        $stmt->bindParam(':conteudo', $data['conteudo'], PDO::PARAM_STR);
        $stmt->bindParam(':ferramenta', $data['ferramenta'], PDO::PARAM_INT);
        $stmt->bindParam(':habilidade', $data['habilidade'], PDO::PARAM_INT);
        $stmt->bindParam(':ambiente', $data['ambiente'], PDO::PARAM_STR);
        $stmt->bindParam(':abordagem', $data['abordagem'], PDO::PARAM_STR);
        $stmt->bindParam(':link', $data['link'], PDO::PARAM_STR);
        $stmt->bindParam(':catalogId', $catalogId, PDO::PARAM_INT);
        $stmt->execute();
    }
    $CFG['link']->commit();
} catch (PDOException $e) {
    $CFG['link']->rollBack();
    error("Falha ao tentar atualizar catálogo");
} catch (Exception $e) {
    $CFG['link']->rollBack();
    error("Falha ao tentar atualizar catálogo");
}


echo json_encode($out);
