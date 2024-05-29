<?php
require('../../../api/private/connect.php');
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
         $turmaId = decrypt_id($token, $encryptionKey, $signatureKey, 'turma');
        }catch(Exception $e){
            echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
            exit;
        }
        $allowedFields = ['nomenclatura', 'nome_usuario'];
        $campo = $_POST['field'];
        $valor = $_POST['value'];

        if (!in_array($campo, $allowedFields)) {
            echo json_encode(['msg' => 'Campo não permitido para atualização',  'status' => 400]);
        } else {
            try {
                $pdo = $connection->connection();

                switch ($campo) {
                    case 'nomenclatura':
                        $valor = cleanAndValidatePhoneNumber('telefone', $valor);
                        break;

                    case 'nome_usuario':
                        $valor = cleanAndValidateCnpj($valor);
                        if (isCnpjAlreadyExists($pdo, $valor, $turmaId)) {
                            throw new Exception('CNPJ já cadastrado');
                        }
                        break;
                    default:
                        $valor = cleanAndValidateCharsNumbers($campo, $valor);
                        break;
                }

                
                $sql = "UPDATE `turma` SET $campo = :valor WHERE `turma`.`id` = :turmaId";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':valor', $valor);
                $stmt->bindParam(':turmaId', $turmaId, PDO::PARAM_INT);

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

function treinamentoJaEmTurma(){


}