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
            $empresaId = decrypt_id($token, $encryptionKey, $signatureKey, 'Cargo');
        } catch (Exception $e) {
            handleError($e->getMessage(), 400);
        }

        $cargoNome = cleanAndValidate('Cargo', $_POST['dadoInput']);

        try {
            if (isPositionAlreadyExists($connection->connection(), $cargoNome, $empresaId)) {
                handleError('Cargo já cadastrado', 400);
            }

            
            if ($permissaoId != 1 && $permissaoId != 4) {

                $stmtCheckUser = $connection->connection()->prepare("SELECT COUNT(*) as userCount FROM empresa_cliente WHERE usuario_id = :userId AND id = :empresaId");
                $stmtCheckUser->bindParam(':userId', $id, PDO::PARAM_INT);
                $stmtCheckUser->bindParam(':empresaId', $empresaId, PDO::PARAM_INT);
                $stmtCheckUser->execute();
                $userCount = $stmtCheckUser->fetch(PDO::FETCH_ASSOC)['userCount'];

                if ($userCount == 0) {
                    handleError('Usuário não tem permissão para cadastrar cargo para esta empresa', 403);
                
                }
            }

       
            $stmtInsertCargo = $connection->connection()->prepare("INSERT INTO empresa_cliente_cargo (nome, empresa_id) VALUES (:cargoNome, :empresaId)");
            $stmtInsertCargo->bindParam(':cargoNome', $cargoNome, PDO::PARAM_STR);
            $stmtInsertCargo->bindParam(':empresaId', $empresaId, PDO::PARAM_INT);
            $stmtInsertCargo->execute();

            echo json_encode(['msg' => 'Cargo cadastrado com sucesso', 'status' => 200]);
            exit;

        } catch (PDOException $e) {
            handleError('Database error: ' . $e->getMessage());
        }
    }
}

function isPositionAlreadyExists($pdo, $cargoNome, $empresaId)
{
    $stmt = $pdo->prepare("SELECT nome FROM `empresa_cliente_cargo` WHERE nome = :nome AND empresa_id = :id");
    $stmt->bindParam(':nome', $cargoNome, PDO::PARAM_STR);
    $stmt->bindParam(':id', $empresaId);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}
