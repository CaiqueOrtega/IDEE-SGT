<?php


require('../../api/private/connect.php');
include('../../api/private/cript.php');

$connection = new Database();
session_start();
$id = $_SESSION['login']['id'];
$permissaoId = $_SESSION['login']['permissao'];

function obterCargos($connection, $id, $empresaId, $whereClause)
{
    $sql = "SELECT empresa_cliente_cargo.id, empresa_cliente_cargo.nome
            FROM `empresa_cliente_cargo`
            INNER JOIN `empresa_cliente`
            ON empresa_cliente_cargo.empresa_id = empresa_cliente.id
            INNER JOIN `usuario` 
            ON empresa_cliente.usuario_id = usuario.id 
            $whereClause AND empresa_cliente.id = :empresaId";

    $stmt = $connection->connection()->prepare($sql);
    
    if (strpos($whereClause, ':id') !== false) {
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
     
    }
    $stmt->bindParam(':empresaId', $empresaId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obterDepartamentos($connection, $id, $empresaId, $whereClause)
{
    $sql = "SELECT empresa_cliente_departamento.id, empresa_cliente_departamento.nome
            FROM `empresa_cliente_departamento`
            INNER JOIN `empresa_cliente`
            ON empresa_cliente_departamento.empresa_id = empresa_cliente.id
            INNER JOIN `usuario` 
            ON empresa_cliente.usuario_id = usuario.id 
            $whereClause AND empresa_cliente.id = :empresaId";

    $stmt = $connection->connection()->prepare($sql);
        
    if (strpos($whereClause, ':id') !== false) {
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    }
    $stmt->bindParam(':empresaId', $empresaId, PDO::PARAM_INT);
   
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $token = $_POST['token'];

        try {
            $empresaId = decrypt_id($token, $encryptionKey, $signatureKey, 'Empresa');
        } catch (Exception $e) {
            echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
            exit;
        }
        
        if ($permissaoId != 1 && $permissaoId != 4) {
               
            $whereClause = 'WHERE usuario.id = :id';
        }else{
            $whereClause ='WHERE 1=1';
        }
    
        $cargosData = obterCargos($connection, $id, $empresaId, $whereClause);
        $departamentosData = obterDepartamentos($connection, $id, $empresaId,  $whereClause);
    }
} catch (PDOException $e) {

    echo "Erro de banco de dados: " . $e->getMessage();
}
