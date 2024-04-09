<?php
require_once('../../../api/private/connect.php');
include('../../../api/private/cript.php');

header('Content-Type: application/json');

$connection = new Database();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $id = isset($_SESSION['login']['id']) ? $_SESSION['login']['id'] : null;
    $token = isset($_POST['token']) ? $_POST['token'] : null;

    if (!is_numeric($id) || empty($token)) {
        throw new InvalidArgumentException('Parâmetros inválidos.');
    }


    $funcionarioId = decrypt_id($token, $encryptionKey, $signatureKey, 'Funcionario');
    if (!is_numeric($funcionarioId)) {
        throw new InvalidArgumentException('ID de funcionário inválido.');
    }

    $stmtCheck = $connection->connection()->prepare("SELECT COUNT(*) FROM empresa_cliente_funcionario WHERE id = :funcionarioId");
    $stmtCheck->bindParam(':funcionarioId', $funcionarioId, PDO::PARAM_INT);
    $stmtCheck->execute();
    $funcionarioExists = $stmtCheck->fetchColumn();

    if (!$funcionarioExists) {
        echo json_encode(['msg' => 'Funcionário não encontrado', 'status' => 404]);
        exit;
    }

    $result = verifyInscription($connection, $funcionarioId);

    if ($result['msg']) {
        $mensagem = 'Não é possível excluir este funcionário, pois está atualmente inscrito nos seguintes treinamentos: ' . "\n" . $result['nomesTreinamentos'];
        echo json_encode(['msg' => $mensagem, 'status' => 400]);
        exit;
    }

    $sql = "DELETE FROM empresa_cliente_funcionario 
            WHERE id = :funcionarioId 
            AND EXISTS (
                SELECT 1 
                FROM empresa_cliente 
                INNER JOIN usuario ON empresa_cliente.usuario_id = usuario.id
                WHERE usuario.id = :userId
                  AND empresa_cliente_funcionario.empresa_id = empresa_cliente.id
            )";

    $stmt = $connection->connection()->prepare($sql);
    $stmt->bindParam(':funcionarioId', $funcionarioId, PDO::PARAM_INT);
    $stmt->bindParam(':userId', $id, PDO::PARAM_INT);

    $success = $stmt->execute();

    if ($success) {
        echo json_encode(['msg' => 'Funcionário excluído com sucesso', 'status' => 200]);
    } else {
        echo json_encode(['msg' => 'Erro ao excluir o funcionário', 'status' => 500]);
        echo json_encode(['debug' => $stmt->errorInfo(), 'status' => 500]);
    }
} catch (PDOException $pdoException) {
    echo json_encode(['msg' => 'Erro PDO: ' . $pdoException->getMessage(), 'status' => 500]);
} catch (InvalidArgumentException $e) {
    echo json_encode(['msg' => 'Parâmetros inválidos: ' . $e->getMessage(), 'status' => 400]);
}

function verifyInscription($connection, $funcionarioID)
{
    $stmt = $connection->connection()->prepare("SELECT GROUP_CONCAT(CONCAT((@index := @index + 1), '. ', t.nomenclatura) SEPARATOR '\n') as nomesTreinamentos
                                               FROM (SELECT @index := 0) AS idx
                                               CROSS JOIN ficha_inscricao fi
                                               INNER JOIN treinamento t ON fi.treinamento_id = t.id
                                               WHERE JSON_SEARCH(fi.funcionarios, 'one', :funcionarioID, NULL, '$[*].id') IS NOT NULL
                                               GROUP BY fi.treinamento_id");

    $stmt->execute([':funcionarioID' => $funcionarioID]);

    $rowCount = $stmt->rowCount();

    if ($rowCount > 0) {
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $nomesTreinamentos = [];
        foreach ($rows as $row) {
            $nomesTreinamentos[] = $row['nomesTreinamentos'];
        }

        $nomesTreinamentosString = implode("\n", $nomesTreinamentos);

        return ['msg' => true, 'nomesTreinamentos' => $nomesTreinamentosString];
    } else {
        return ['msg' => false];
    }
}
