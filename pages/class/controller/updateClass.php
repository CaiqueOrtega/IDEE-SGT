<?php
require('../../../api/private/connect.php');
include('../../../api/private/cript.php');

header('Content-Type: application/json');

$connection = new Database();

echo 'Inicio do Script<br>';

if (!isset($_POST['field'], $_POST['value'], $_POST['token'])) {
    echo json_encode(['msg' => 'Parâmetros ausentes na requisição', 'status' => 400]);
    exit;
} else {
    $valid = isValid(['field', 'value', 'token']);

    echo 'Validação inicial: ' . ($valid ? 'falhou' : 'passou') . '<br>';

    if ($valid) {
        echo 'Valid: ' . $valid . '<br>';
        exit;
    } else {
        $token = $_POST['token'];

        try {
            $turmaId = decrypt_id($token, $encryptionKey, $signatureKey, 'turma');
            echo 'ID da Turma Descriptografado: ' . $turmaId . '<br>';
        } catch (Exception $e) {
            echo 'Erro na Descriptografia do ID: ' . $e->getMessage() . '<br>';
            echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
            exit;
        }

        $allowedFields = ['nome_usuario'];
        $campo = $_POST['field'];
        $valor = $_POST['value'];

        if (!in_array($campo, $allowedFields)) {
            echo 'Campo não permitido: ' . $campo . '<br>';
            echo json_encode(['msg' => 'Campo não permitido para atualização', 'status' => 400]);
            exit;
        } else {
            try {
                $pdo = $connection->connection();
                echo 'Conexão com o banco de dados estabelecida<br>';

                if ($campo == 'nome_usuario') {
                    $valor = updateProfessorInTurma($turmaId, $valor, $connection);
                    echo 'Valor após updateProfessorInTurma: ' . $valor . '<br>';
                } else {
                    $valor = cleanAndValidateCharsNumbers($campo, $valor);
                    echo 'Valor após cleanAndValidateCharsNumbers: ' . $valor . '<br>';
                }

                $sql = "UPDATE `turma` SET $campo = :valor WHERE `turma`.`id` = :turmaId";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':valor', $valor);
                $stmt->bindParam(':turmaId', $turmaId, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    echo 'Dados atualizados com sucesso<br>';
                    echo json_encode(['msg' => 'Dados da turma atualizados com sucesso.', 'status' => 200]);
                } else {
                    throw new Exception('Erro ao atualizar dados.');
                }
            } catch (Exception $e) {
                error_log('Erro na atualização: ' . $e->getMessage());
                echo 'Erro na atualização: ' . $e->getMessage() . '<br>';
                echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
            }
        }
    }
}

function updateProfessorInTurma($turmaId, $userId, $connection) {
    try {
        $pdo = $connection->connection();
        echo 'Conexão com o banco de dados estabelecida em updateProfessorInTurma<br>';

        // Verificar se a turma existe
        $sql = "SELECT * FROM `turma` WHERE id = :turmaId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':turmaId', $turmaId, PDO::PARAM_INT);
        $stmt->execute();
        $turma = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$turma) {
            echo 'Turma não encontrada<br>';
            throw new Exception('Turma não encontrada.');
        }

        // Verificar se o novo professor existe e está ativo
        $sql = "SELECT * FROM `login` WHERE id = :newProfessorId AND ativo = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':newProfessorId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $professor = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$professor) {
            echo 'Professor não encontrado ou inativo<br>';
            throw new Exception('Professor não encontrado ou inativo.');
        }

        // Atualizar a turma com o novo professor
        $sql = "UPDATE `turma` SET colaborador_id_fk = :newProfessorId WHERE id = :turmaId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':newProfessorId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':turmaId', $turmaId, PDO::PARAM_INT);
        $stmt->execute();

        echo 'Professor atualizado com sucesso<br>';
        return 'Professor atualizado com sucesso.';
    } catch (PDOException $e) {
        error_log('Erro ao atualizar professor da turma: ' . $e->getMessage());
        echo 'Erro ao atualizar professor da turma: ' . $e->getMessage() . '<br>';
        throw new Exception('Erro interno ao atualizar professor da turma');
    }
}
?>
