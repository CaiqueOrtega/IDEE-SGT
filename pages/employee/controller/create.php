<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST)) {
    echo json_encode(['msg' => 'Requisição Inválida', 'status' => 400]);
    exit;
}

require_once('../../../api/private/connect.php');
require('../../../api/private/cript.php');
include('../../../api/validade/validate.php');
$connection = new Database();

try {
    $valid = isValid(['empresa', 'nome_funcionario', 'email', 'telefone', 'cpf', 'genero', 'numero_registro_empresa', 'cargo', 'departamento']);
    if ($valid) {
        echo  $valid;
        exit;
    }

    try {
        $tokenEmpresa = $_POST['empresa'];
        $empresaId = decrypt_id($tokenEmpresa, $encryptionKey, $signatureKey, 'Empresa');

        $tokenCargo = $_POST['cargo'];
        $cargoId = decrypt_id($tokenCargo, $encryptionKey, $signatureKey, 'Cargo');

        $tokenDepartamento = $_POST['departamento'];
        $departamentoId = decrypt_id($tokenDepartamento, $encryptionKey, $signatureKey, 'Departamento');
    } catch (Exception $e) {
        echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
        exit;
    }

    $nome = cleanAndValidate('Nome Funcionario', $_POST['nome_funcionario']);
    $telefone = cleanAndValidatePhoneNumber('Telefone', $_POST['telefone']);
    $cpf = cleanAndValidateCpf($_POST['cpf']);
    $registro = cleanNumbersAndValidate('Número de Registro', $_POST['numero_registro_empresa']);
    $email = $_POST['email'];
    $genero = strtoupper($_POST['genero']);

    if (!in_array($genero, ['F', 'M'])) {
        echo json_encode(['msg' => 'Genero Inválido', 'status' => 400]);
    } elseif (!isEmailFormatValid($email)) {
        echo json_encode(['msg' => 'Email inválido', 'status' => 400]);
    } elseif (isCpfAlreadyExists($connection->connection(), $cpf)) {
        echo json_encode(['msg' => 'CPF já cadastrado', 'status' => 400]);
    } elseif (isNumberRegisterAlreadyExists($connection->connection(), $registro, $empresaId)) {
        echo json_encode(['msg' => 'Número de registro já cadastrado', 'status' => 400]);
    } else {
        try {
            $stmt = $connection->connection()->prepare("
                INSERT INTO `empresa_cliente_funcionario` 
                (`nome_funcionario`, `email`, `telefone`, `cpf`, `genero`, `cargo_id`, `departamento_id`, `numero_registro_empresa`, `empresa_id`) 
                VALUES 
                (:nome_funcionario, :email, :telefone, :cpf, :genero, :cargoId, :departamentoId, :numero_registro_empresa, :empresaId)
            ");

            $stmt->bindParam(':nome_funcionario', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
            $stmt->bindParam(':telefone', $telefone, PDO::PARAM_STR);
            $stmt->bindParam(':genero', $genero, PDO::PARAM_STR_CHAR);
            $stmt->bindParam(':numero_registro_empresa', $registro, PDO::PARAM_INT);
            $stmt->bindParam(':empresaId', $empresaId, PDO::PARAM_INT);
            $stmt->bindParam(':cargoId', $cargoId, PDO::PARAM_INT);
            $stmt->bindParam(':departamentoId', $departamentoId, PDO::PARAM_INT);

            $stmt->execute();

            echo json_encode(['msg' => 'Funcionário cadastrado com sucesso', 'status' => 200]);
        } catch (PDOException $e) {
            error_log('Erro ao cadastrar funcionário: ' . $e->getMessage());
            echo json_encode(['msg' => 'Erro interno ao cadastrar funcionário', 'status' => 500]);
        }
    }
} catch (PDOException $e) {
    error_log('Erro ao cadastrar funcionário: ' . $e->getMessage());
    echo json_encode(['msg' => 'Erro interno ao cadastrar funcionário', 'status' => 500]);
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
