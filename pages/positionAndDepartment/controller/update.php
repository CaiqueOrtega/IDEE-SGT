<?php
require_once('../../../api/private/connect.php');
include('../../../api/private/cript.php');
include('../../../api/validade/validate.php');

header('Content-Type: application/json');
$connection = new Database();
$pdo = $connection->connection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fieldsToValidate = ['field', 'value', 'token', 'type'];
    $isValid = isValid($fieldsToValidate);

    if ($isValid) {
        echo $isValid;
        exit();
    } else {
        $token = $_POST['token'];
        $tokenCompany = $_POST['tokenCompany'];
        try {
            $id = decrypt_id($token, $encryptionKey, $signatureKey, 'Cargo ou Departamento');
            $empresaId = decrypt_id($tokenCompany, $encryptionKey, $signatureKey, 'Empresa');
        } catch (Exception $e) {
            echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
            exit;
        }
        
        $campo = $_POST['field'];

        if ($campo !== 'nome') {
            echo json_encode(['error' => 'Campo inválido']);
            exit();
        }

        $valor = cleanAndValidate($campo, $_POST['value']);
        $type = $_POST['type'];


        switch ($type) {
            case 'cargo':
                updateData('empresa_cliente_cargo', $id, $campo, $valor, $pdo, $empresaId);
                break;
            case 'departamento':
                updateData('empresa_cliente_departamento', $id, $campo, $valor, $pdo, $empresaId);
                break;
            default:
                echo json_encode(['error' => 'Tipo inválido']);
        }
    }
}

function updateData($table, $id, $campo, $valor, $pdo, $empresaId)
{
    $stmt = $pdo->prepare("SELECT nome FROM `$table` WHERE nome = :nome AND empresa_id = :empresaId");
    $stmt->bindParam(':nome', $valor, PDO::PARAM_STR);
    $stmt->bindParam(':empresaId', $empresaId);
    $stmt->execute();

    if ($stmt->fetch(PDO::FETCH_ASSOC) !== false) {
        echo json_encode(['msg' => 'Nome ' . $valor .' já cadastrado', 'status' => 400]);
    } else {
        try {
            $sql = "UPDATE `$table` SET $campo = :value WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':value', $valor);

            if ($stmt->execute()) {
                echo json_encode(['msg' => ucfirst($table) . ' atualizado com sucesso', 'status' => 200]);
            } else {
                echo json_encode(['msg' => "Erro ao atualizar $table", 'status' => 400]);
            }
        } catch (PDOException $e) {
            echo json_encode(['msg' => 'Erro no banco de dados: ' . $e->getMessage(), 'status' => 500]);
        }
    }
}
