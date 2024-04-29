<?php
require($_SERVER['DOCUMENT_ROOT'] . '/IDEE-SGT/api/private/connect.php');


session_start();
$id = $_SESSION['login']['id'];
$idPermissao = $_SESSION['login']['permissao'];

try {
    if ($idPermissao == 1 || $idPermissao == 4) {
        $where = " WHERE 1=1";
    } else {

        $where = " WHERE turma.colaborador_id_fk = :id";
    }

    $connection = new Database();
    $turmasData = getCordenadorId($id, $connection, $where);
    $alunosData = getAlunosData($id, $connection, $where);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

function getCordenadorId($userId, $connection, $whereClause)
{


    try {
        $pdo = $connection->connection();

        $sql = "SELECT turma.*, 
        treinamento.id AS treinamento_id,
        treinamento.*, 
        empresa_cliente.id AS empresa_id,
        empresa_cliente.*,
        login.nome AS nome_usuario
        FROM `turma`
        INNER JOIN `treinamento` ON turma.treinamento_id = treinamento.id
        INNER JOIN `empresa_cliente` ON turma.empresa_aluno = empresa_cliente.id
        INNER JOIN `login`ON turma.colaborador_id_fk = login.id 
                $whereClause";

        $stmt = $pdo->prepare($sql);


        if (strpos($whereClause, ':id') != false) {
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Erro ao buscar dados da empresa: ' . $e->getMessage());
        throw new Exception('Erro interno ao buscar dados da empresa');
    }
}

function getAlunosData($userId, $connection, $whereClause)
{
    try {
        $pdo = $connection->connection();

        $sql = "SELECT aluno.*, turma.id AS turma_aluno_fk
        FROM aluno
        INNER JOIN turma ON aluno.turma_aluno_fk = turma.id;
        
                $whereClause";

        $stmt = $pdo->prepare($sql);


        if (strpos($whereClause, ':id') != false) {
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Erro ao buscar dados do(s) aluno(s): ' . $e->getMessage());
        throw new Exception('Erro interno ao buscar dados do(s) aluno(s)');
    }
}