<?php
require($_SERVER['DOCUMENT_ROOT'] . '/IDEE-SGT/api/private/connect.php');


session_start();
$id = $_SESSION['login']['id'];
$idPermissao = $_SESSION['login']['permissao'];

try {
    if ($idPermissao == 1 || $idPermissao == 4) {
        $whereTurma = " WHERE 1=1";
        $whereAluno = " WHERE 1=1";
    } else {
        $whereTurma = " WHERE turma.colaborador_id_fk = :id";
        $whereAluno = " WHERE aluno.turma_aluno_fk = :turmaId AND turma.colaborador_id_fk = :id";
    }

    $connection = new Database();
    $turmasData = getCordenadorId($id, $connection, $whereTurma);

    // Iterar sobre as turmas para obter os dados dos alunos de cada turma
    foreach ($turmasData as $turma) {
        $turmaId = $turma['id'];
        echo "Processando turma ID: $turmaId <br>";

        $alunosData = getAlunosData($id, $connection, $whereAluno, $turmaId);
        echo "Número de alunos encontrados na turma $turmaId: " . count($alunosData) . "<br>";

        // Aqui você pode processar os dados dos alunos conforme necessário
        foreach ($alunosData as $aluno) {
            // Processar os dados dos alunos...
            echo "ID do Aluno: " . $aluno['id'] . ", Nome do Aluno: " . $aluno['nome'] . "<br>";
        }
    }
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
        error_log('Erro ao buscar dados da turma: ' . $e->getMessage());
        throw new Exception('Erro interno ao buscar dados da turma');
    }
}

function getAlunosData($userId, $connection, $whereClause,$turmaId)
{
    try {
        $pdo = $connection->connection();

        $sql = "SELECT 
        aluno.*, 
        empresa_cliente_funcionario.id AS id_funcionario_fk,
        empresa_cliente_funcionario.* 
        FROM  aluno
        INNER JOIN turma ON aluno.turma_aluno_fk = turma.id 
        INNER JOIN empresa_cliente_funcionario ON aluno.id_funcionario_fk = empresa_cliente_funcionario.id
        $whereClause";

        $stmt = $pdo->prepare($sql);

        if (strpos($whereClause, ':id') !== false) {
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        }

        if (strpos($whereClause, ':turmaId') !== false) {
            // Se estiver usando o parâmetro :turmaId, você precisa passá-lo também
            $stmt->bindParam(':turmaId', $turmaId, PDO::PARAM_INT); // Certifique-se de definir $turmaId
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Erro ao buscar dados do(s) aluno(s): ' . $e->getMessage());
        throw new Exception('Erro interno ao buscar dados do(s) aluno(s)');
    }
}
