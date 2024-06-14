<?php
header('Content-Type: application/json');

require('../../../api/private/connect.php');
include('../../../api/private/cript.php');
include('../../../api/validade/validate.php');

$connection = new Database();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id = isset($_SESSION['login']['id']) ? $_SESSION['login']['id'] : null;

// Atualização da turma
if (isset($_POST['colaborador_id'], $_POST['tokenTurma']) && $id !== null) {
    $valid = isValid(['colaborador_id', 'tokenTurma']);

    if ($valid) {
        echo json_encode(['msg' => $valid, 'status' => 400]);
        exit();
    }

    $tokenTurma = $_POST['tokenTurma'];

    try {
        $turmaId = decrypt_id($tokenTurma, $encryptionKey, $signatureKey, 'Turma');
    } catch(Exception $e) {
        echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
        exit;
    }

    $campo = 'colaborador_id_fk';
    $colaboradorId = $_POST['colaborador_id'];

    if ($colaboradorId === false) {
        echo json_encode(['msg' => 'Colaborador Campo Inválido', 'status' => 400]);
        exit;
    }

    try {
        $pdo = $connection->connection();

        $sql = "UPDATE `turma` SET $campo = :valor WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':valor', $colaboradorId);
        $stmt->bindParam(':id', $turmaId, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['msg' => 'Atualização da turma bem-sucedida!', 'status' => 200]);

    } catch (PDOException $e) {
        echo json_encode(['msg' => 'Erro na atualização da turma: ' . $e->getMessage(), 'status' => 500]);
    } catch (Exception $e) {
        echo json_encode(['msg' => $e->getMessage(), 'status' => 500]);
    }
}







// Atualização do status do aluno
elseif (isset($_POST['tokenAluno'], $_POST['novoStatus'])) {
    $tokenAluno = $_POST['tokenAluno'];
    $novoStatus = $_POST['novoStatus'];

    try {
        $alunoId = decrypt_id($tokenAluno, $encryptionKey, $signatureKey, 'Aluno');
    } catch (Exception $e) {
        echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
        exit;
    }

    $campo = 'status';
    $status = $novoStatus;

    if ($status === false) {
        echo json_encode(['msg' => 'Status Campo Inválido', 'status' => 400]);
        exit;
    }

    try {
        $pdo = $connection->connection();

        $sql = "UPDATE `aluno` SET $campo = :valor WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':valor', $status);
        $stmt->bindParam(':id', $alunoId, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['msg' => 'Atualização do status do aluno bem-sucedida!', 'status' => 200]);

    } catch (PDOException $e) {
        echo json_encode(['msg' => 'Erro na atualização do status do aluno: ' . $e->getMessage(), 'status' => 500]);
    } catch (Exception $e) {
        echo json_encode(['msg' => $e->getMessage(), 'status' => 500]);
    }
}

// Se nenhum dos blocos anteriores for acionado, há parâmetros ausentes na requisição
else {
    echo json_encode(['msg' => 'Parâmetros ausentes na requisição.', 'status' => 400]);
}
?>
