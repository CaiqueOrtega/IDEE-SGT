<?php
require('../../../api/private/connect.php');
include('../../../api/validade/validate.php');
include('../../../api/private/cript.php');


header('Content-Type: application/json');

$connection = new Database();

if (!isset($_POST['field'], $_POST['value'], $_POST['token'])) {
    echo json_encode(['msg' => 'Parâmetros ausentes na requisição',  'status' => 400]);
} else {
    $valid = isValid(['field', 'value', 'token']);

    if ($valid) {
        echo $valid;
    } else {
        $token = $_POST['token'];

        try{
        $empresaId = decrypt_id($token, $encryptionKey, $signatureKey, 'Empresa');
        }catch(Exception $e){
            echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
            exit;
        }
        $allowedFields = ['razao_social', 'nome_fantasia', 'email', 'cnpj', 'telefone'];
        $campo = $_POST['field'];
        $valor = $_POST['value'];

        if (!in_array($campo, $allowedFields)) {
            echo json_encode(['msg' => 'Campo não permitido para atualização',  'status' => 400]);
        } else {
            try {
                $pdo = $connection->connection();

                switch ($campo) {
                    case 'email':
                        if (!isEmailFormatValid($valor)) {
                            throw new Exception('Email inválido');
                        }
                        break;

                    case 'telefone':
                        $valor = cleanAndValidatePhoneNumber('telefone', $valor);
                        break;

                    case 'cnpj':
                        $valor = cleanAndValidateCnpj($valor);
                        if (isCnpjAlreadyExists($pdo, $valor, $empresaId)) {
                            throw new Exception('CNPJ já cadastrado');
                        }
                        break;
                    default:
                        $valor = cleanAndValidateCharsNumbers($campo, $valor);
                        break;
                }

                
                $sql = "UPDATE `empresa_cliente` SET $campo = :valor WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':valor', $valor);
                $stmt->bindParam(':id', $empresaId, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    echo json_encode(['msg' => 'Dados da empresa atualizados com sucesso.', 'status' => 200]);
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

function isCnpjAlreadyExists($pdo, $cnpj, $empresaId)
{
    $stmt = $pdo->prepare("SELECT empresa_cliente.cnpj FROM `empresa_cliente` WHERE empresa_cliente.cnpj = :cnpj AND empresa_cliente.id != :id");
    $stmt->bindParam(':cnpj', $cnpj, PDO::PARAM_STR);
    $stmt->bindParam(':id', $empresaId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}
?>
