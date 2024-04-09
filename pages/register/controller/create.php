<?php
header('Content-Type: application/json');
define('MIN_PASSWORD_LENGTH', 8);

require_once('../../../api/private/connect.php');
require('../../../api/private/cript.php');
include('../../../api/validade/validate.php');

$connection = new Database();

try {
    $valid = isValid(['nome', 'data_nascimento', 'telefone', 'cpf', 'genero', 'email', 'senha']);
    
    if ($valid) {
        echo $valid;
        exit;
    }

    $nome = cleanAndValidate('Nome', $_POST['nome']);
    
    $telefone = cleanAndValidatePhoneNumber('Telefone', $_POST['telefone']);
    $cpf = cleanAndValidateCpf($_POST['cpf']);
    $genero = strtoupper($_POST['genero']);
    $email = $_POST['email'];
    $password = $_POST['senha'];
    $permissaoId = 3;
    $data_nascimento= date('Y-m-d', strtotime ($_POST['data_nascimento']));

    $idade = date_diff(date_create($data_nascimento), date_create('today'))->y;
    if ($idade < 18) {
        echo json_encode(['msg' => 'Você deve ter pelo menos 18 anos para continuar.', 'status' => 400]);
        exit;
    } 


    $resultSenha = isPasswordValid($password);

    if (!$resultSenha['valid']) {
        $msg = $resultSenha['msg'] . ': ' . implode(', ', $resultSenha['errors']);
        echo json_encode(['msg' => $msg, 'status' => 400]);
    } elseif (!in_array($genero, ['F', 'M'])) {
        echo json_encode(['msg' => 'Gênero Inválido', 'status' => 400]);
    } elseif (isCpfAlreadyExists($connection->connection(), $cpf)) {
        echo json_encode(['msg' => 'CPF já possui uma conta', 'status' => 400]);
    } elseif(isEmailExists($connection->connection(), $email)){
        echo json_encode(['msg' => 'Email ja possui uma conta', 'status' => 400]);
    }
    elseif (!isEmailFormatValid($email)) {
        echo json_encode(['msg' => 'Email inválido', 'status' => 400]);
    } else {
        $pdo = $connection->connection();

        try {
            $pdo->beginTransaction();
        
            $stmtUsuario = $pdo->prepare("INSERT INTO `usuario` (`data_nascimento`, `cpf`, `telefone`, `genero`) 
            VALUES (:data_nascimento, :cpf, :telefone, :genero)");
            $stmtUsuario->bindParam(':data_nascimento', $data_nascimento, PDO::PARAM_STR);
            $stmtUsuario->bindParam(':cpf', $cpf, PDO::PARAM_STR);
            $stmtUsuario->bindParam(':telefone', $telefone, PDO::PARAM_STR);
            $stmtUsuario->bindParam(':genero', $genero, PDO::PARAM_STR_CHAR);
            $stmtUsuario->execute();
        
            $usuarioId = $pdo->lastInsertId();
            
            $hashedPassword = md5($password);
        
            $stmtLogin = $pdo->prepare("INSERT INTO `login` (`id`, `nome`, `email`, `senha`, `permissao_id`) 
            VALUES (:id, :nome, :email, :senha, :permissaoId)");
            $stmtLogin->bindParam(':id', $usuarioId, PDO::PARAM_INT);
            $stmtLogin->bindParam(':permissaoId', $permissaoId, PDO::PARAM_INT);
            $stmtLogin->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmtLogin->bindParam(':email', $email, PDO::PARAM_STR);
            $stmtLogin->bindParam(':senha', $hashedPassword, PDO::PARAM_STR);
            $stmtLogin->bindParam(':permissaoId', $permissaoId, PDO::PARAM_INT);
            $stmtLogin->execute();
        
            $pdo->commit();
            echo json_encode(['msg' => 'Cadastrado com sucesso', 'status' => 200]);
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log('Erro ao cadastrar usuário: ' . $e->getMessage());
            echo json_encode(['msg' => 'Erro interno ao se cadastrar', 'status' => 500]);
        }
    }
} catch (PDOException $e) {
    error_log('Erro ao fazer login: ' . $e->getMessage());
    echo json_encode(['msg' => 'Erro interno ao fazer login', 'status' => 500]);
}



function isCpfAlreadyExists($pdo, $cpf)
{
    $stmtUsuario = $pdo->prepare("SELECT usuario.cpf FROM `usuario` WHERE usuario.cpf = :cpf");
    $stmtUsuario->bindParam(':cpf', $cpf, PDO::PARAM_STR);
    $stmtUsuario->execute();
    return $stmtUsuario->fetch(PDO::FETCH_ASSOC) !== false;
}

function isEmailExists($pdo, $email)
{
    $stmtUsuario = $pdo->prepare("SELECT login.email FROM `login` WHERE login.email = :email");
    $stmtUsuario->bindParam(':email', $email, PDO::PARAM_STR);
    $stmtUsuario->execute();
    return $stmtUsuario->fetch(PDO::FETCH_ASSOC) !== false;
}

function isPasswordValid($password)
{
    $pattern = '/^(?=.*[0-9])(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{' . MIN_PASSWORD_LENGTH . ',}$/';

    if (preg_match($pattern, $password)) {
        return ['valid' => true, 'msg' => 'Senha válida'];
    } else {
        $errors = [];

        if (strlen($password) < MIN_PASSWORD_LENGTH) {
            $errors[] = 'A senha deve ter pelo menos ' . MIN_PASSWORD_LENGTH . ' caracteres';
        }

        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'A senha deve conter pelo menos 1 número';
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'A senha deve conter pelo menos 1 letra maiúscula';
        }

        if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
            $errors[] = 'A senha deve conter pelo menos 1 caractere especial';
        }

        return ['valid' => false, 'msg' => 'Senha inválida', 'errors' => $errors];
    }
}
