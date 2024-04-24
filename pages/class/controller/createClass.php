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
    $stmt = $connection->connection()->prepare("SELECT * FROM ficha_inscricao WHERE id = :idFichaInscricao");
    $stmt->bindParam(':idFichaInscricao', $idFichaInscricao);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $funcionarioId = $result['funcionarios'];
        $treinamentoId = $result['treinamento_id'];
        $empresaId = $result['empresa_id'];

        // Chama a função classRegister para cadastrar a turma e obter o ID
        $colaboradorId = getColaboradorId($connection, $treinamentoId);
        $turmaResult = classRegister($connection, $treinamentoId, $empresaId, $colaboradorId);
        $turmaResultDecoded = json_decode($turmaResult, true);


        if ($turmaResultDecoded['status'] === 200) {
            $turmaId = $turmaResultDecoded['turma_id'];

            // Chama a função studentRegister para cadastrar os alunos na turma
            $registerResult = studentRegister($connection, $funcionarioId, $turmaId);
            $registerResultDecoded = json_decode($registerResult, true);

            if ($registerResultDecoded['status'] === 200) {
                // Se todos os alunos foram registrados, exclua a inscrição pendente 
                $stmtDelete = $connection->connection()->prepare("DELETE FROM ficha_inscricao WHERE id = :idFichaInscricao");
                $stmtDelete->bindParam(':idFichaInscricao', $idFichaInscricao);
                $stmtDelete->execute();

                echo $registerResult; // Exibe a resposta da função studentRegister
            } else {
                echo $registerResult; // Exibe a resposta da função studentRegister
            }
        } else {
            echo $turmaResult; // Exibe a resposta da função classRegister
        }
    }
} catch (Exception $e) {
    echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
    exit;
}


function classRegister($connection, $treinamentoId, $empresaId, $colaboradorId)
{
    echo ("entrou2");
    try {
        $pdo = $connection->connection();
        $pdo->beginTransaction();

        // Verifica se a empresa já possui uma turma com o mesmo treinamento
        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM `turma` WHERE `treinamento_id` = :treinamento_id AND `empresa_aluno` = :empresa_aluno");
        $stmtCheck->bindParam(':treinamento_id', $treinamentoId, PDO::PARAM_INT);
        $stmtCheck->bindParam(':empresa_aluno', $empresaId, PDO::PARAM_INT);
        $stmtCheck->execute();
        $count = $stmtCheck->fetchColumn();


        if ($count > 0) {
            // Se a empresa já tiver uma turma com o mesmo treinamento, retorna uma mensagem de erro
            return json_encode(['msg' => 'A empresa já possui uma turma com o mesmo treinamento', 'status' => 400]);
        } else {
            // Caso contrário, insere a nova turma no banco de dados
            // Recupere o último caractere do nome da última turma
            $stmtUltimaTurma = $pdo->prepare("SELECT SUBSTRING(nome_turma, -1) AS ultima_letra FROM turma ORDER BY id DESC LIMIT 1");
            $stmtUltimaTurma->execute();
            $ultimaTurma = $stmtUltimaTurma->fetch(PDO::FETCH_ASSOC);

            // Determine a próxima letra com base na última turma cadastrada
            $proximaLetra = 'A';
            if ($ultimaTurma && $ultimaTurma['ultima_letra'] >= 'A' && $ultimaTurma['ultima_letra'] < 'Z') {
                $proximaLetra = chr(ord($ultimaTurma['ultima_letra']) + 1);
            }

            // Gere o nome da turma com base na próxima letra
            $nomeTurma = "Turma " . $proximaLetra;

            // Insira a nova turma no banco de dados
            $stmtInsert = $pdo->prepare("INSERT INTO `turma` (`nome_turma`, `treinamento_id`, `empresa_aluno`, `colaborador_id_fk`) 
                                VALUES (:nome_turma, :treinamento_id, :empresa_aluno, :colaborador_id)");
            $stmtInsert->bindParam(':nome_turma', $nomeTurma, PDO::PARAM_STR);
            $stmtInsert->bindParam(':treinamento_id', $treinamentoId, PDO::PARAM_INT);
            $stmtInsert->bindParam(':empresa_aluno', $empresaId, PDO::PARAM_INT);
            $stmtInsert->bindParam(':colaborador_id', $colaboradorId, PDO::PARAM_INT);
            $stmtInsert->execute();

            $turmaId = $pdo->lastInsertId();

            $pdo->commit();

            return json_encode(['msg' => 'Turma registrada com sucesso', 'status' => 200, 'turma_id' => $turmaId]);
        }
    } catch (PDOException $e) {
        $pdo->rollback();
        return json_encode(['msg' => 'Erro ao cadastrar Turma: ' . $e->getMessage(), 'status' => 400]);
    }
}

function studentRegister($connection, $funcionariosIds, $turmaId)
{
    echo ("entrou3");

    try {
        $funcionariosArray = json_decode($funcionariosIds, true);

        $pdo = $connection->connection();
        if (is_array($funcionariosArray)) {
            $pdo->beginTransaction();

            $stmtExists = $pdo->prepare("SELECT COUNT(*) FROM `aluno` WHERE `id_funcionario_fk` = :funcionarioId AND `turma_aluno_fk` = :turmaId");
            $stmtInsert = $pdo->prepare("INSERT INTO `aluno` (`id_funcionario_fk`, `turma_aluno_fk`, `nota`, `frequencia`) 
                                    VALUES (:funcionarioId, :turmaId, null, 100)");

            foreach ($funcionariosArray as $funcionario) {
                $funcionarioId = $funcionario['id'];

                // Verifica se já existe um registro do aluno na turma
                $stmtExists->bindParam(':funcionarioId', $funcionarioId, PDO::PARAM_INT);
                $stmtExists->bindParam(':turmaId', $turmaId, PDO::PARAM_INT);
                $stmtExists->execute();
                $count = $stmtExists->fetchColumn();

                if ($count == 0) {
                    // Insere o aluno na turma apenas se não existir um registro prévio
                    $stmtInsert->bindParam(':funcionarioId', $funcionarioId, PDO::PARAM_INT);
                    $stmtInsert->bindParam(':turmaId', $turmaId, PDO::PARAM_INT);
                    $stmtInsert->execute();
                } else {
                    // Se o aluno já estiver cadastrado na turma, emitir uma mensagem de aviso
                    echo "O aluno com ID $funcionarioId já está cadastrado na turma com ID $turmaId.<br>";
                }
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

function getColaboradorId($connection, $treinamentoId)
{
    echo ("entrou1");

    $stmt = $connection->connection()->prepare("SELECT `colaborador_id` FROM treinamento WHERE `id` = :treinamento_id");
    $stmt->bindParam(':treinamento_id', $treinamentoId, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['colaborador_id'];
}
