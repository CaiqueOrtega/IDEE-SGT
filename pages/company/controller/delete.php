<?php
require('../../../api/private/connect.php');
include('../../../api/private/cript.php');

$connection = new Database();
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id = $_SESSION['login']['id'];
$permissaoId = $_SESSION['login']['permissao'];

$token = $_POST['token'];
try {
    $empresaId = decrypt_id($token, $encryptionKey, $signatureKey, 'Empresa');
} catch (Exception $e) {
    echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
    exit;
}

try {
    $pdo = $connection->connection();

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $pdo->beginTransaction();

    
        if (!hasDeletePermissions($connection, $empresaId, $id, $permissaoId)) {
            throw new Exception('Permissões insuficientes para excluir dados', 403);
        }
    

    if (
        deleteFuncionario($connection, $empresaId) &&
        deleteCargo($connection, $empresaId) &&
        deleteDepartamento($connection, $empresaId) &&
        deleteEmpresa($connection, $empresaId, $id)
    ) {

        $pdo->commit();
        echo json_encode(['msg' => 'Todos os dados excluídos com sucesso', 'status' => 200]);
    } else {
        throw new Exception('Erro ao excluir dados', 500);
    }
} catch (Exception $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['msg' => $e->getMessage(), 'status' => $e->getCode()]);
}





function hasDeletePermissions($connection, $empresaId, $userId,  $permissaoId)
{
    if ($permissaoId == 1 || $permissaoId == 4) {
        return true;
    }

    $sql = "SELECT 1 FROM `usuario`
            WHERE usuario.id = :userId
            AND EXISTS (
                SELECT 1 FROM `empresa_cliente`
                WHERE empresa_cliente.id = :empresaId
                AND empresa_cliente.usuario_id = usuario.id
            )";
    $stmt = $connection->connection()->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':empresaId', $empresaId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchColumn() > 0;
}



function deleteCargo($connection, $empresaId)
{
    $sql = "DELETE FROM `empresa_cliente_cargo` WHERE empresa_id = :empresaId";
    $stmt = $connection->connection()->prepare($sql);
    $stmt->bindParam(':empresaId', $empresaId, PDO::PARAM_INT);

    if (hasCargo($connection, $empresaId)) {
        return $stmt->execute();
    }

    return true;
}



function deleteDepartamento($connection, $empresaId)
{
    $sql = "DELETE FROM `empresa_cliente_departamento` WHERE empresa_id = :empresaId";
    $stmt = $connection->connection()->prepare($sql);
    $stmt->bindParam(':empresaId', $empresaId, PDO::PARAM_INT);

    if (hasDepartamento($connection, $empresaId)) {
        return $stmt->execute();
    }

    return true;
}

function deleteFuncionario($connection, $empresaId)
{
    $sql = "DELETE FROM `empresa_cliente_funcionario` WHERE empresa_id = :empresaId";
    $stmt = $connection->connection()->prepare($sql);
    $stmt->bindParam(':empresaId', $empresaId, PDO::PARAM_INT);

    if (hasFuncionario($connection, $empresaId)) {
        return $stmt->execute();
    }

    return true;
}


function deleteEmpresa($connection, $empresaId)
{
    $sql = "DELETE FROM `empresa_cliente` WHERE `id` = :empresaId ";
    $stmt = $connection->connection()->prepare($sql);
    $stmt->bindParam(':empresaId', $empresaId, PDO::PARAM_INT);


    return $stmt->execute();
}


function hasCargo($connection, $empresaId)
{
    $sql = "SELECT COUNT(*) FROM `empresa_cliente_cargo` WHERE empresa_id = :empresaId";
    $stmt = $connection->connection()->prepare($sql);
    $stmt->bindParam(':empresaId', $empresaId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchColumn() > 0;
}


function hasDepartamento($connection, $empresaId)
{
    $sql = "SELECT COUNT(*) FROM `empresa_cliente_departamento` WHERE empresa_id = :empresaId";
    $stmt = $connection->connection()->prepare($sql);
    $stmt->bindParam(':empresaId', $empresaId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchColumn() > 0;
}


function hasFuncionario($connection, $empresaId)
{
    $sql = "SELECT COUNT(*) FROM `empresa_cliente_funcionario` WHERE empresa_id = :empresaId";
    $stmt = $connection->connection()->prepare($sql);
    $stmt->bindParam(':empresaId', $empresaId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchColumn() > 0;
}
