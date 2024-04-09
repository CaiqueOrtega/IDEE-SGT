<?php
require('../../api/private/connect.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$connection = new Database();

$userId = $_SESSION['login']['id'];
$idPermissao = $_SESSION['login']['permissao'];

function getFuncionariosData($connection, $userId, $whereClause)
{
    $sql = "SELECT empresa_cliente_funcionario.*, 
                    empresa_cliente.razao_social, 
                    empresa_cliente.id AS empresa_id, 
                    empresa_cliente_cargo.nome AS cargo_nome, 
                    empresa_cliente_departamento.nome AS departamento_nome,
                    login.nome AS nome_usuario 
            FROM `empresa_cliente_funcionario` 
            INNER JOIN `empresa_cliente` ON empresa_cliente_funcionario.empresa_id = empresa_cliente.id 
            LEFT JOIN `empresa_cliente_cargo` ON empresa_cliente_funcionario.cargo_id = empresa_cliente_cargo.id 
            LEFT JOIN `empresa_cliente_departamento` ON empresa_cliente_funcionario.departamento_id = empresa_cliente_departamento.id 
            INNER JOIN usuario ON empresa_cliente.usuario_id = usuario.id
            INNER JOIN login ON usuario.id = login.id
            $whereClause";

    $stmt = $connection->connection()->prepare($sql);


    if (strpos($whereClause, ':id') !== false) {
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    }

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

try {
    $sql = "SELECT empresa_cliente.nome_fantasia, empresa_cliente.id 
    FROM `empresa_cliente` INNER JOIN `usuario` ON usuario.id = empresa_cliente.usuario_id
    WHERE usuario.id = :id";

    $stmt = $connection->connection()->prepare($sql);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $empresasData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($idPermissao != 1 && $idPermissao != 4) {
        $where = "WHERE usuario.id = :id";
    } else {
        $where = "";
    }

    $funcionariosData = getFuncionariosData($connection, $userId, $where);
} catch (PDOException $e) {
    echo json_encode(['msg' => 'Erro de banco de dados: ' . $e->getMessage(), 'status' => 500]);
} catch (Exception $e) {
    echo json_encode(['msg' => 'Erro: ' . $e->getMessage(), 'status' => 500]);
}
