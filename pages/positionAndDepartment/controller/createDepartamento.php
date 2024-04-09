<?php
require('../../../api/private/connect.php');
include('../../../api/validade/validate.php');
include('../../../api/private/cript.php');

header('Content-Type: application/json');
$connection = new Database();

session_start();
$id = $_SESSION['login']['id'];
$permissaoId = $_SESSION['login']['permissao'];

function handleError($message, $statusCode = 500) {
    echo json_encode(['msg' => $message, 'status' => $statusCode]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $valid = isValid(['dadoInput', 'token']);

    if ($valid) {
        echo json_encode(['msg' => 'Preencha todos os campos', 'status' => 400]);
    } else {
        $token = $_POST['token'];

        try {
            $empresaId = decrypt_id($token, $encryptionKey, $signatureKey, 'Departamento');
        } catch (Exception $e) {
            handleError($e->getMessage(), 400);
        }

        $departamentoNome = cleanAndValidate('Departamento', $_POST['dadoInput']);

        try {
           
            if (isDepartmentAlreadyExists($connection->connection(), $departamentoNome, $empresaId)) {
                handleError('Departamento já cadastrado', 400);
            }

            
            if ($permissaoId != 1 && $permissaoId != 4) {
               
                $stmtCheckUser = $connection->connection()->prepare("SELECT COUNT(*) as userCount FROM empresa_cliente WHERE usuario_id = :userId AND id = :empresaId");
                $stmtCheckUser->bindParam(':userId', $id, PDO::PARAM_INT);
                $stmtCheckUser->bindParam(':empresaId', $empresaId, PDO::PARAM_INT);
                $stmtCheckUser->execute();
                $userCount = $stmtCheckUser->fetch(PDO::FETCH_ASSOC)['userCount'];

                if ($userCount == 0) {
                    handleError('Usuário não tem permissão para cadastrar departamento para esta empresa', 403);
                }
            }

            
            $stmtInsertDepartamento = $connection->connection()->prepare("INSERT INTO empresa_cliente_departamento (nome, empresa_id) VALUES (:departamentoNome, :empresaId)");
            $stmtInsertDepartamento->bindParam(':departamentoNome', $departamentoNome, PDO::PARAM_STR);
            $stmtInsertDepartamento->bindParam(':empresaId', $empresaId, PDO::PARAM_INT);
            $stmtInsertDepartamento->execute();

            echo json_encode(['msg' => 'Departamento cadastrado com sucesso', 'status' => 200]);
            exit;

        } catch (PDOException $e) {
            handleError('Database error: ' . $e->getMessage());
        }
    }
}

function isDepartmentAlreadyExists($pdo, $departamentoNome, $empresaId)
{
    $stmt = $pdo->prepare("SELECT nome FROM `empresa_cliente_departamento` WHERE nome = :nome AND empresa_id = :id");
    $stmt->bindParam(':nome', $departamentoNome, PDO::PARAM_STR);
    $stmt->bindParam(':id', $empresaId);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}
