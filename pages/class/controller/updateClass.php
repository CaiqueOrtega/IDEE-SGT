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

if (isset($_POST['colaborador_id'], $_POST['tokenTurma']) && $id !== null) {
    $valid = isValid(['colaborador_id', 'tokenTurma']);

    if ($valid) {
        echo $valid;
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

        echo json_encode(['msg' => 'Atualização bem-sucedida!', 'status' => 200]);

    } catch (PDOException $e) {
        echo json_encode(['msg' => 'Erro na atualização: ' . $e->getMessage(), 'status' => 500]);
    } catch (Exception $e) {
        echo json_encode(['msg' => $e->getMessage(), 'status' => 500]);
    }
} else {
    echo json_encode(['msg' => 'Parâmetros ausentes na requisição.', 'status' => 500]);
}
?>