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
$cycle = (int)$data['cycle'];
$catalogMessage = sanitize($data['catalogMessage']);
$newStatus = $currentStatus;
$awaitingApprove = 0;
$active = 1;


try {
    $CFG['link']->beginTransaction();

    $sql = "SELECT Ciclo as cycle, Status as status FROM catalogo WHERE id = :catalogId AND Status != 8";
    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':catalogId', $catalogId, PDO::PARAM_INT);
    $stmt->execute();
    $catalog = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($catalog)) {
        error('Catálogo não encontrado. Atualize a página.');
    }

    $currentCycle = (int)$catalog['cycle'];
    $catalogStatus = (int)$catalog['status'];

    if ($cycle !== $currentCycle) {
        if ($catalogStatus === 4) {
            $sql = "SELECT 
                        c.id AS catalogId, 
                        COALESCE(ca.CategoriaId, c.CategoriaId) AS categoryId, 
                        COALESCE(ca.Titulo, c.Titulo) AS title, 
                        c.Status AS status, 
                        c.Ativo AS active, 
                        c.Ciclo AS cycle,
                        COALESCE(ca.Conteudo, c.Conteudo) AS content, 
                        COALESCE(ca.Ambiente, c.Ambiente) AS ambient, 
                        COALESCE(ca.Abordagem, c.Abordagem) AS approach, 
                        COALESCE(ca.CaminhoDeAcesso, c.CaminhoDeAcesso) AS link, 
                        c.createdAt, 
                        c.AguardandoRevisao, 
                        COALESCE(aAtualizado.name, a.name) AS abilityName, 
                        COALESCE(tAtualizado.name, t.name) AS toolName, 
                        COALESCE(pAtualizado.name, p.name) AS publicName, 
                        u.id AS userId, 
                        u.name AS userName, 
                        u.email AS userEmail, 
                        u.phone AS userPhone, 
                        u.createTime AS userCreatedAt 
                    FROM catalogo c 
                    LEFT JOIN catalogoAtualizado ca ON ca.CatalogoId = c.id 
                    LEFT JOIN ability aAtualizado ON ca.HabilidadeId = aAtualizado.id
                    LEFT JOIN tool tAtualizado ON ca.FerramentaId = tAtualizado.id
                    LEFT JOIN public pAtualizado ON ca.PublicoAlvoId = pAtualizado.id
                    INNER JOIN ability a ON c.HabilidadeId = a.id 
                    INNER JOIN tool t ON c.FerramentaId = t.id 
                    INNER JOIN public p ON c.PublicoAlvoId = p.id 
                    INNER JOIN actUser u ON c.userId = u.id 
                    WHERE c.id = :catalogId AND c.Status = 4
                    ORDER BY c.createdAt DESC";

            $stmt = $CFG['link']->prepare($sql);
            $stmt->bindParam(':catalogId', $catalogId, PDO::PARAM_INT);
            $stmt->execute();
            $catalogUpdated = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($catalogUpdated) {
                $out['catalogData'] = $catalogUpdated;
                echo json_encode($out);
                exit;
            }
        } else {
            error('O catálago foi alterado pelo usuário. Atualize a página.');
        }
    }

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
                    WHERE catalogo.id = :catalogId AND catalogo.Status != 8";

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
                WHERE id = :catalogId AND Status != 8";
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

        if ($newStatus == 8 || $newStatus == 3) { //catalogo deletetado ou cadastro recusado
            $active = 0;
        }

        $sql = "UPDATE catalogo 
                SET Status = :newStatus,
                    AguardandoRevisao = :awaitingApprove,
                    Ativo = :active,
                    lastUpdate = NOW(),
                    Ciclo = Ciclo + 1
                WHERE id = :catalogId AND Status != '8'";
        $stmt = $CFG['link']->prepare($sql);
        $stmt->bindParam(':catalogId', $catalogId, PDO::PARAM_INT);
        $stmt->bindParam(':awaitingApprove', $awaitingApprove, PDO::PARAM_INT);
        $stmt->bindParam(':active', $active, PDO::PARAM_INT);
        $stmt->bindParam(':newStatus', $newStatus, PDO::PARAM_INT);
        $stmt->execute();
    }

    $currentCycle += 1;
    $sql = "INSERT INTO messages (catalogId, message, ciclo) VALUES (:catalogId, :message, :ciclo)";
    $stmt = $CFG['link']->prepare($sql);
    $stmt->bindParam(':catalogId', $catalogId, PDO::PARAM_INT);
    $stmt->bindParam(':message', $catalogMessage, PDO::PARAM_STR);
    $stmt->bindParam(':ciclo', $currentCycle, PDO::PARAM_INT);
    $stmt->execute();

    $CFG['link']->commit();
} catch (PDOException $e) {
    $CFG['link']->rollBack();
    error("Falha ao tentar aprovar/rejeitar catálogo");
} catch (Exception $e) {
    $CFG['link']->rollBack();
    error("Falha ao tentar aprovar/rejeitar catálogo");
}

echo json_encode($out);
