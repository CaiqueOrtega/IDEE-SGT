<?php

require('../../../api/private/connect.php');
include('../../../api/validade/validate.php');
include('../../../api/private/cript.php');

header('Content-Type: application/json');

$connection = new Database();


if (!isset($_POST['tokenFicha'])) {
    echo json_encode(['msg' => 'Dados ausentes', 'status' => 400]);
    exit();
}


$tokenFicha = $_POST['tokenFicha'];

try {
    $idFichaInscricao = decrypt_id($tokenFicha, $encryptionKey, $signatureKey, 'FichaInscricao');
} catch (Exception $e) {
    echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
    exit;
}

try {

    $stmt = $connection->connection()->prepare("SELECT * FROM ficha_inscricao WHERE  id = :idFichaInscricao");
    $stmt->bindParam(':idFichaInscricao', $idFichaInscricao);
    $stmt->execute();

    
    if ($stmt->rowCount() > 0) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $funcionarioId = $result['funcionarios'];
        $treinamentoId = $result['treinamento_id'];
        $empresaId = $result['empresa_id'];

        $turmaId = classRegister($connection, $treinamentoId, $empresaId);

        studentRegister($connection, $funcionarioId, $turmaId );
      
        
    }
} catch (Exception $e) {
    
    echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
    exit;
}

function classRegister($connection, $treinamentoId, $empresaId) {
    try {
        $pdo = $connection->connection();
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO `turma` (`treinamento_id`, `empresa_aluno`) 
                            VALUES (:treinamento_id, :empresa_aluno)");

        $stmt->bindParam(':treinamento_id', $treinamentoId, PDO::PARAM_STR);
        $stmt->bindParam(':empresa_aluno', $empresaId, PDO::PARAM_STR);

        $stmt->execute();
        
        // Obtém o ID do último registro inserido
        $turmaId = $pdo->lastInsertId();

        $pdo->commit();

        // Retorna o ID da turma
        return $turmaId;
    } catch (PDOException $e) {
        $pdo->rollback();
        return null; // Em caso de erro, retorna null
    }
}



function studentRegister($connection, $funcionariosIds, $turmaId) {
    echo("entrou");
    try {
        $funcionariosArray = json_decode($funcionariosIds, true);
        
        $pdo = $connection->connection();
        if (is_array($funcionariosArray)) {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO `aluno` (`id_funcionario_fk`, `turma_aluno_fk`, `nota`, `frequencia`) 
                                    VALUES (:funcionarioId, :turmaId, null, 100)");

            foreach ($funcionariosArray as $funcionario) {
                $funcionarioId = $funcionario['id'];
                echo($funcionarioId);
                $stmt->bindParam(':funcionarioId', $funcionarioId, PDO::PARAM_INT);
                $stmt->bindParam(':turmaId', $turmaId, PDO::PARAM_INT);
                $stmt->execute();
                echo("test");
            }

            $pdo->commit();

            return json_encode(['msg' => 'Alunos registrados com sucesso', 'status' => 200]);
        } else {
            return json_encode(['msg' => 'Erro ao decodificar os IDs dos funcionários', 'status' => 400]);
        }
    } catch (PDOException $e) {
        if ($pdo !== null) {
            $pdo->rollback();
        }
        return json_encode(['msg' => 'Erro ao cadastrar alunos: ' . $e->getMessage(), 'status' => 400]);
    }
}
