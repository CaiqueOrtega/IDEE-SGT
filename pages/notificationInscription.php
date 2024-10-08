<?php
require('../api/private/connect.php');
include('../api/private/cript.php');

$connection = new Database();


$id = $_SESSION['login']['id'];

$permissaoId = $_SESSION['login']['permissao'];

try {
    $inscricoesData = getInscricoesData($connection, $id, $permissaoId);
    $numLinhas = count($inscricoesData);
} catch (PDOException $e) {
    echo json_encode(['msg' => 'Erro de banco de dados: ' . $e->getMessage(), 'status' => 500]);
} catch (Exception $e) {
    echo json_encode(['msg' => 'Erro: ' . $e->getMessage(), 'status' => 500]);
}

function getInscricoesData($connection, $userId, $permissaoId)
{
    
    $where = ($permissaoId == 1 || $permissaoId == 4 ) ? "WHERE 1=1" : "AND empresa_cliente.usuario_id = :id";

   
    $sql = "SELECT empresa_cliente.*, treinamento.*, ficha_inscricao.id AS id_inscricao, ficha_inscricao.*
            FROM `ficha_inscricao` 
            INNER JOIN `empresa_cliente` ON empresa_cliente.id = ficha_inscricao.empresa_id
            INNER JOIN `treinamento` ON treinamento.id = ficha_inscricao.treinamento_id
            $where";

    
    $stmt = $connection->connection()->prepare($sql);

    
    if (strpos($where, ':id') !== false) {
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    }

    $stmt->execute();

  
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>