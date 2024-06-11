<?php
header('Content-Type: application/json');

require('../../../api/private/connect.php');
include('../../../api/private/cript.php');

$connection = new Database();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$id = isset($_SESSION['login']['id']) ? $_SESSION['login']['id'] : null;

if (isset($_POST['field'], $_POST['value'], $_POST['token'], $_POST['tokenTurma']) && $id !== null) {
    $valid = isValid(['field', 'value', 'token']);

    if ($valid) {
        echo $valid;
        exit();
    }

    
    $token = $_POST['token'];
    $tokenTurma = $_POST['tokenTurma'];

    try{
    $turmaId = decrypt_id($tokenTurma, $encryptionKey, $signatureKey, 'Turma');
    }catch(Exception $e){
        echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
        exit;
    }
    $campo = $_POST['field'];
    $valor = $_POST['value'];

    $allowedFields = ['colaborador_id_fk'];

    if (!in_array($campo, $allowedFields)) {
        echo json_encode(['msg' => 'Campo não permitido para atualização', 'status' => 400]);
    } else {
        try {
            $pdo = $connection->connection();

            switch ($campo) {

                default:
                try{
                    $valor = decrypt_id($valor, $encryptionKey, $signatureKey, 'Departamento, Cargo ou Empresa');
                }catch(Exception $e){
                    echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
                    exit;
                }
                    break;
            }

            $sql = "UPDATE `empresa_cliente_funcionario` SET $campo = :valor WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':valor', $valor);
            $stmt->bindParam(':id', $funcionarioId, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(['msg' => 'Atualização bem-sucedida!', 'status' => 200]);

        } catch (PDOException $e) {
            echo json_encode(['msg' => 'Erro na atualização: ' . $e->getMessage(), 'status' => 500]);
        } catch (Exception $e) {
            echo json_encode(['msg' => $e->getMessage(), 'status' => 500]);
        }
    }
} else {
    echo json_encode(['msg' => 'Parâmetros ausentes na requisição.', 'status' => 500]);
}

function isCpfAlreadyExists($pdo, $cpf)
{
    $stmt = $pdo->prepare("SELECT empresa_cliente_funcionario.cpf FROM `empresa_cliente_funcionario` WHERE empresa_cliente_funcionario.cpf = :cpf");
    $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}

function isNumberRegisterAlreadyExists($pdo, $registro, $empresaId)
{
    $stmt = $pdo->prepare("SELECT empresa_cliente_funcionario.numero_registro_empresa FROM `empresa_cliente_funcionario` 
        WHERE empresa_cliente_funcionario.numero_registro_empresa = :registro AND empresa_cliente_funcionario.empresa_id = :empresaId");
    $stmt->bindParam(':registro', $registro, PDO::PARAM_INT);
    $stmt->bindParam(':empresaId', $empresaId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}
?>
