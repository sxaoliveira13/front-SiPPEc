<?php

require_once("../config.php");
require_once("../functions.php");

header('Content-Type: application/json; charset=utf-8');
$USERDATA = checkToken($_COOKIE['userToken']);

if (empty($USERDATA['userId'])) {
    error("Autenticação inválida ", 1);
}
if ($USERDATA['userType'] != 2) {
    error("Você não tem permissão para isso! ", 2);
}

$out = array('success' => true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    error('Método de requisição inválido');
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$catalogId = (int)$data['catalogId'];
$currentStatus = (int)$data['currentStatus'];
$isApproved = (int)$data['isApproved'];
$catalogMessage = sanitize($data['catalogMessage']);
$newStatus = $currentStatus;
$awaitingApprove = 0;
$active = 1;

try {
    $CFG['link']->beginTransaction();
    if ($currentStatus === 4) {
        if ($isApproved === 1) {
            $sql = "UPDATE catalogo 
                    JOIN catalogoAtualizado ON catalogo.id = catalogoAtualizado.CatalogoId
                    SET catalogo.Status = 5, 
                        catalogo.AguardandoRevisao = :awaitingApprove, 
                        catalogo.Ativo = :ativo,
                        catalogo.Titulo = catalogoAtualizado.Titulo,
                        catalogo.Conteudo = catalogoAtualizado.Conteudo,
                        catalogo.Ambiente = catalogoAtualizado.Ambiente,
                        catalogo.Abordagem = catalogoAtualizado.Abordagem,
                        catalogo.CaminhoDeAcesso = catalogoAtualizado.CaminhoDeAcesso,
                        catalogo.lastUpdate = NOW(),
                        catalogo.Ciclo = catalogo.Ciclo + 1
                    WHERE catalogo.id = :catalogId";

            $stmt = $CFG['link']->prepare($sql);
            $stmt->bindParam(':catalogId', $catalogId, PDO::PARAM_INT);
            $stmt->bindParam(':awaitingApprove', $awaitingApprove, PDO::PARAM_INT);
            $stmt->bindParam(':ativo', $active, PDO::PARAM_INT);
            $stmt->execute();
        } elseif ($isApproved === 0) {
            $sql = "UPDATE catalogo 
            SET Status = 6,
                AguardandoRevisao = :awaitingApprove,
                lastUpdate = NOW(),
                Ciclo = Ciclo + 1
                WHERE id = :catalogId";
            $stmt = $CFG['link']->prepare($sql);
            $stmt->bindParam(':catalogId', $catalogId, PDO::PARAM_INT);
            $stmt->bindParam(':awaitingApprove', $awaitingApprove, PDO::PARAM_INT);
            $stmt->execute();
        }

        $sql = "DELETE FROM catalogoAtualizado WHERE CatalogoId = :catalogId";
        $stmt = $CFG['link']->prepare($sql);
        $stmt->bindParam(':catalogId', $catalogId, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        if ($isApproved === 1) {
            $newStatus += 1; // Aprovação
        } else {
            $newStatus += 2; // Rejeição
        }

        if ($newStatus == 8) {
            $active = 0;
        }

        $sql = "UPDATE catalogo 
                SET Status = :newStatus,
                    AguardandoRevisao = :awaitingApprove,
                    Ativo = :active,
                    lastUpdate = NOW(),
                    Ciclo = Ciclo + 1
                WHERE id = :catalogId";
        $stmt = $CFG['link']->prepare($sql);
        $stmt->bindParam(':catalogId', $catalogId, PDO::PARAM_INT);
        $stmt->bindParam(':awaitingApprove', $awaitingApprove, PDO::PARAM_INT);
        $stmt->bindParam(':active', $active, PDO::PARAM_INT);
        $stmt->bindParam(':newStatus', $newStatus, PDO::PARAM_INT);
        $stmt->execute();
    }

    $sql = "SELECT Ciclo FROM catalogo WHERE id = :catalogId";
    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':catalogId', $catalogId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $updatedCiclo = $result['Ciclo'];

    $sql = "INSERT INTO messages (catalogId, message, ciclo) VALUES (:catalogId, :message, :ciclo)";
    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':catalogId', $catalogId, PDO::PARAM_INT);
    $stmt->bindParam(':message', $catalogMessage, PDO::PARAM_STR);
    $stmt->bindParam(':ciclo', $updatedCiclo, PDO::PARAM_INT);
    $stmt->execute();

    $CFG['link']->commit();
} catch (PDOException $e) {
    $CFG['link']->rollBack();
    error($e->getMessage());
} catch (Exception $e) {
    $CFG['link']->rollBack();
    error($e->getMessage());
}

echo json_encode($out);
