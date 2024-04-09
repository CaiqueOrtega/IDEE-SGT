<?php
require('../../api/private/connect.php');
include('../../api/private/cript.php');

$connection = new Database();

session_start();
$id = $_SESSION['login']['id'];
$idPermissao = $_SESSION['login']['permissao'];

try {
    $inscricoesData = getInscricoesData($connection, $id, $idPermissao);
    $numLinhas = count($inscricoesData);
} catch (PDOException $e) {
    echo json_encode(['msg' => 'Erro de banco de dados: ' . $e->getMessage(), 'status' => 500]);
} catch (Exception $e) {
    echo json_encode(['msg' => 'Erro: ' . $e->getMessage(), 'status' => 500]);
}

function getInscricoesData($connection, $userId, $idPermissao)
{
    
    $where = ($idPermissao == 1 || $idPermissao == 4 ) ? "WHERE 1=1" : "AND empresa_cliente.usuario_id = :id";

   
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



/*foreach ($inscricoesData as $inscricao) {
    $funcionariosIDs = json_decode($inscricao['funcionarios'], true);
    $treinamentoId = $inscricao['treinamento_id'];

    
    $validFuncionariosIDs = array();

    foreach ($funcionariosIDs as $funcionarioID) {
        $stmt = $connection->connection()->prepare("SELECT COUNT(*) FROM empresa_cliente_funcionario 
                                                   WHERE id = :funcionarioID 
                                                   AND EXISTS (SELECT 1 FROM ficha_inscricao 
                                                               WHERE JSON_SEARCH(funcionarios, 'one', :funcionarioID, NULL, '$[*].id') IS NOT NULL 
                                                               AND treinamento_id = :treinamentoId)");

        $stmt->execute([
            ':funcionarioID' => $funcionarioID['id'],
            ':treinamentoId' => $treinamentoId
        ]);

        if ($stmt->fetchColumn() > 0) {
            $validFuncionariosIDs[] = $funcionarioID;
        }
    }

    $funcionariosValues = array_values($validFuncionariosIDs);
    $updateStmt = $connection->connection()->prepare("UPDATE ficha_inscricao SET funcionarios = :funcionarios WHERE id = :inscricaoId");
    $updateStmt->execute([
        ':funcionarios' => json_encode($funcionariosValues),
        ':inscricaoId' => $inscricao['id_inscricao']
    ]);

    $isEmpty = empty($validFuncionariosIDs);

    if ($isEmpty) {
        $deleteStmt = $connection->connection()->prepare("DELETE FROM ficha_inscricao WHERE id = :inscricaoId");
        $deleteStmt->execute([':inscricaoId' => $inscricao['id_inscricao']]);
    }
}

$inscricoesData = $connection->connection()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
*/
?>