<?php
require('../../api/private/connect.php');

function getEmpresaData($userId, $connection, $whereClause)
{
    try {
        $pdo = $connection->connection();

        $sql = "SELECT empresa_cliente.razao_social, 
        empresa_cliente.nome_fantasia, 
        empresa_cliente.telefone, 
                empresa_cliente.cnpj, 
                empresa_cliente.email, 
                empresa_cliente.id,
                login.nome AS nome_usuario
                FROM `empresa_cliente`
                INNER JOIN `usuario` 
                ON empresa_cliente.usuario_id = usuario.id 
                INNER JOIN `login`
                ON usuario.id = login.id
                $whereClause";

        $stmt = $pdo->prepare($sql);

       
        if (strpos($whereClause, ':id') !== false) {
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        
        error_log('Erro ao buscar dados da empresa: ' . $e->getMessage());
     
        throw new Exception('Erro interno ao buscar dados da empresa');
    }
}

session_start();
$id = $_SESSION['login']['id'];
$idPermissao = $permissao = $_SESSION['login']['permissao'];

try {
    if ($idPermissao == 1 || $idPermissao == 4) {
        $where = "WHERE 1=1";
    } else {
        $where = "AND usuario.id = :id";
    }

    $connection = new Database();
    $empresasData = getEmpresaData($id, $connection, $where);

} catch (Exception $e) {
   
    echo json_encode(['error' => $e->getMessage()]);
}

