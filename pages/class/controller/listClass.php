<?php
require('../../api/private/connect.php');



if (!isset($idPermissao) && !isset($id)) {
    session_start();
    $idPermissao = $_SESSION['login']['permissao'];
    $id = $_SESSION['login']['id'];
}


try {
    if ($idPermissao == 1 || $idPermissao == 4) {
        $whereTurma = " WHERE 1=1";
        $whereAluno = " WHERE aluno.turma_aluno_fk = :turmaId AND (aluno.status = 'ativo' OR aluno.status = 'inativo')";
    } else {
        $whereTurma = " WHERE turma.colaborador_id_fk = :id";
        $whereAluno = " WHERE aluno.turma_aluno_fk = :turmaId AND turma.colaborador_id_fk = :id AND aluno.status = 'ativo'";
    }

    $connection = new Database();
    $turmasData = getCordenadorId($id, $connection, $whereTurma);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}





function getCordenadorId($userId, $connection, $whereClause)
{


    try {
        $pdo = $connection->connection();

        $sql = "SELECT turma.*, 
        turma.id AS turma_id,
        treinamento.id AS treinamento_id,
        treinamento.*, 
        empresa_cliente.id AS empresa_id,
        empresa_cliente.*,
        login.id AS colaborador_id ,
        login.nome AS nome_colaborador
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
        error_log('Erro ao buscar dados da turma: ' . $e->getMessage());
        throw new Exception('Erro interno ao buscar dados da turma');
    }
}

function getAlunosData($userId, $connection, $whereClause, $turmaId = null)
{
    try {
        $pdo = $connection->connection();

        $sql = "SELECT aluno.*, 
        aluno.id AS aluno_id,
        empresa_cliente_funcionario.id AS id_funcionario_fk,
        empresa_cliente_funcionario.* 
        FROM aluno
        INNER JOIN turma ON aluno.turma_aluno_fk = turma.id 
        INNER JOIN empresa_cliente_funcionario ON aluno.id_funcionario_fk = empresa_cliente_funcionario.id
        $whereClause";

        $stmt = $pdo->prepare($sql);

        if (strpos($whereClause, ':id') !== false) {
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        }

        if ($turmaId !== null && strpos($whereClause, ':turmaId') !== false) {
            $stmt->bindParam(':turmaId', $turmaId, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Erro ao buscar dados do(s) aluno(s): ' . $e->getMessage());
        throw new Exception('Erro interno ao buscar dados do(s) aluno(s)');
    }
}



function obterColaborador($connection, $colaboradorId)
{
    $sql = "SELECT login.*, login.id AS login_id
    FROM login
    INNER JOIN permissao ON login.permissao_id = permissao.id
    WHERE permissao.id = 2 AND login.id != :colaborador_id
";

    $stmt = $connection->connection()->prepare($sql);

    $stmt->bindParam(':colaborador_id', $colaboradorId, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
