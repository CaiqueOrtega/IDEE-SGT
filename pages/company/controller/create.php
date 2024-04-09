<?php
require('../../../api/private/connect.php');
include('../../../api/validade/validate.php');

$connection = new Database();
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id = $_SESSION['login']['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $valid = isValid(['razao_social', 'nome_fantasia', 'email', 'cnpj', 'telefone']);

    if ($valid) {
        echo $valid;
    } else {
        $razao_social = cleanAndValidateCharsNumbers('Razao social', $_POST['razao_social']);
        $nome_fantasia = cleanAndValidateCharsNumbers('Nome fantasia', $_POST['nome_fantasia']);
        $cnpj =  cleanAndValidateCnpj($_POST['cnpj']);
        $telefone = cleanAndValidatePhoneNumber('Telefone', $_POST['telefone']);
        $email = $_POST['email'];


      
        if (!isEmailFormatValid($email)) {
            echo json_encode(['msg' => 'Formato do email inválido', 'status' => 400]);
        } elseif (isCnpjAlreadyExists($connection->connection(), $cnpj)) {
            echo json_encode(['msg' => 'CNPJ já cadastrado', 'status' => 400]);
        } else {
            try {
                $pdo = $connection->connection();
                $pdo->beginTransaction();

                $stmt = $pdo->prepare("INSERT INTO `empresa_cliente` (`razao_social`, `nome_fantasia`, `email`, `cnpj`, `telefone`, `usuario_id`) 
                    VALUES (:razao_social, :nome_fantasia, :email, :cnpj, :telefone, :id)");

                $stmt->bindParam(':razao_social', $razao_social, PDO::PARAM_STR);
                $stmt->bindParam(':nome_fantasia', $nome_fantasia, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':cnpj', $cnpj, PDO::PARAM_STR);
                $stmt->bindParam(':telefone', $telefone, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);

                $stmt->execute();

                $pdo->commit();

                echo json_encode(['msg' => 'Empresa cadastrada com sucesso', 'status' => 200]);
            } catch (PDOException $e) {
                $pdo->rollBack();
                error_log('Erro ao cadastrar empresa: ' . $e->getMessage());
                echo json_encode(['msg' => 'Erro interno ao cadastrar empresa: ' . $e->getMessage(), 'status' => 500]);
            }
        }
    }
}

function isCnpjAlreadyExists($pdo, $cnpj)
{
    $stmt = $pdo->prepare("SELECT empresa_cliente.cnpj FROM `empresa_cliente` WHERE empresa_cliente.cnpj = :cnpj");
    $stmt->bindParam(':cnpj', $cnpj, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}
?>
