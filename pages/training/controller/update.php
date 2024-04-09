<?php
require('../../../api/private/connect.php');
include('../../../api/validade/validate.php');
include('../../../api/private/cript.php');

$connection = new Database();
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$validFields = [
    'nomenclatura', 'nr', 'objetivo', 'ementa', 'pre_requisitos',
    'material', 'normas_referencia', 'reciclagem', 'carga_horaria',
    'horas_teorica', 'horas_pratica', 'tokenColaborador'
];

$changedData = [];
$errorMessages = [];

foreach ($validFields as $field) {
    if (isset($_POST[$field])) {
        $cleanedValue = $_POST[$field];

        switch ($field) {
            case 'nomenclatura':
            case 'nr':
            case 'objetivo':
            case 'ementa':
            case 'pre_requisitos':
            case 'material':
            case 'normas_referencia':
                $cleanedValue = cleanAndValidateCharsNumbers(ucfirst($field), $_POST[$field]);
                break;

            case 'reciclagem':
                $reciclagem = $_POST[$field];
                switch ($reciclagem) {
                    case 'A':
                        $cleanedValue = 'Anual';
                        break;
                    case 'B':
                        $cleanedValue = 'Bianual';
                        break;
                    case 'T':
                        $cleanedValue = 'Trianual';
                        break;
                    default:
                        $cleanedValue = 'Selecione o tempo de reciclagem';
                        break;
                }
                break;

            case 'tokenColaborador':
                try {
                    $colaboradorId = decrypt_id($_POST['tokenColaborador'], $encryptionKey, $signatureKey, 'Colaborador');
                    $field = 'colaborador_id';
                    $cleanedValue = $colaboradorId;
                } catch (Exception $e) {
                    echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
                    exit;
                }
                break;
        }

        $changedData[$field] = $cleanedValue;
    }
}

try {
    $treinamentoId = decrypt_id($_POST['tokenTreinamento'], $encryptionKey, $signatureKey, 'Treinamento');
} catch (Exception $e) {
    echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
    exit;
}

if (!empty($changedData)) {
    try {
        $pdo = $connection->connection();
        $pdo->beginTransaction();

        $updateFields = [];
        $params = [];

        foreach ($changedData as $field => $value) {
            $updateFields[] = "`$field` = :$field";
            $params[":$field"] = $value;
        }

        $updateFieldsStr = implode(', ', $updateFields);

        $params[':treinamentoId'] = $treinamentoId;

        $stmt = $pdo->prepare("UPDATE `treinamento` SET $updateFieldsStr WHERE `id` = :treinamentoId");
        
        $stmt->execute($params);

        $pdo->commit();

        
        echo json_encode(['msg' => 'Treinamento atualizado com sucesso', 'status' => 200]);
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log('Erro ao atualizar treinamento: ' . $e->getMessage());
        echo json_encode(['msg' => 'Erro interno ao atualizar treinamento: ' . $e->getMessage(), 'status' => 500]);
    }
} else {
    echo json_encode(['msg' => 'Nenhum dado alterado', 'status' => 400]);
}
?>
