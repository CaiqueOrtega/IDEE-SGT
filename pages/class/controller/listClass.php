<?php
require('../../api/private/connect.php');


function getCordenadorId($userId, $connection, $whereClause)
{
    try {
        $pdo = $connection->connection();

        $sql = "SELECT turma.*, 
                treinamento.id AS treinamento_id,
                treinamento.*, 
                empresa_cliente.id AS empresa_id,
                empresa_cliente.*,
                login.id AS colaborador_id_fk,
                login.*
                FROM `turma`
                INNER JOIN `treinamento` ON turma.treinamento_id = treinamento.id
                INNER JOIN `empresa_cliente` ON turma.empresa_aluno = empresa_cliente.id
                INNER JOIN `login`ON turma.colaborador_id_fk = login.id
                $whereClause";

        $stmt = $pdo->prepare($sql);

       
        if (strpos($whereClause, ':id') !== false) {
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Erro ao buscar dados da empresa: ' . $e->getMessage());
        throw new Exception('Erro interno ao buscar dados da empresa');
    }
}

session_start();
$id = $_SESSION['login']['id'];
$idPermissao = $_SESSION['login']['permissao'];

try {
    if ($idPermissao == 1 || $idPermissao == 2 || $idPermissao == 4) {
        $where = "WHERE 1=1";
    } else {
        $where = "AND usuario.id = :id";
    }

    $connection = new Database();
    $turmasData = getCordenadorId($id, $connection, $where);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

