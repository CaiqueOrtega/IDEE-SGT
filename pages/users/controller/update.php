<?php
require('../../../api/private/connect.php');
include('../../../api/validade/validate.php');
include('../../../api/private/cript.php');

header('Content-Type: application/json');

$connection = new Database();

if (!isset($_POST['tokenUsuario'], $_POST['tokenPermissao'])) {
    echo json_encode(['msg' => 'Parâmetros ausentes na requisição', 'status' => 400]);
} else {
    $valid = isValid(['tokenUsuario', 'tokenPermissao']);

    if ($valid) {
        echo json_encode(['msg' => 'Nenhuma opção selecionada', 'status' => 400]);
    } else {
        $tokenUsuario = $_POST['tokenUsuario'];
        $tokenPermissao = $_POST['tokenPermissao'];

        try {
            $usuarioId = decrypt_id($tokenUsuario, $encryptionKey, $signatureKey, 'Usuário');
            $permissaoId = decrypt_id($tokenPermissao, $encryptionKey, $signatureKey, 'Permissão');
        } catch (Exception $e) {
            echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
            exit;
        }

        if ($mensagemErro = colaboradoremTreinamento($connection->connection(), $usuarioId)) {
            echo json_encode(['msg' => $mensagemErro, 'status' => 400]);
        } else {
            try {
                $pdo = $connection->connection();

                $sql = "UPDATE `login` SET permissao_id = :permissao WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':permissao', $permissaoId);
                $stmt->bindParam(':id', $usuarioId, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    echo json_encode(['msg' => 'Permissões atualizadas com sucesso.', 'status' => 200]);
                } else {
                    throw new Exception('Erro ao atualizar dados.');
                }
            } catch (Exception $e) {
                error_log('Erro na atualização: ' . $e->getMessage());
                echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
            }
        }
    }
}

function colaboradoremTreinamento($pdo, $usuarioId){

    $sql = "SELECT treinamento.nomenclatura FROM `treinamento` 
    WHERE treinamento.colaborador_id = :usuarioId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuarioId', $usuarioId, PDO::PARAM_INT);
    $stmt->execute();

    $treinamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($treinamentos)) {
        $mensagemErro = "O colaborador está cadastrado nos seguintes treinamentos:";

        foreach ($treinamentos as $index => $treinamento) {
            $numeroTreinamento = $index + 1;
            $mensagemErro .= "$numeroTreinamento. " . $treinamento['nomenclatura'];
        }

        return $mensagemErro;
    }

    return false;
}
