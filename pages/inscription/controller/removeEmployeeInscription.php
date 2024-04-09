<?php 
require('../../../api/private/connect.php');
include('../../../api/private/cript.php');

header('Content-Type: application/json');
$connection = new Database();

$tokenInscription = isset($_POST['tokenInscription']) ? $_POST['tokenInscription'] : null;
$tokenFuncionario = isset($_POST['tokenEmployee']) ? $_POST['tokenEmployee'] : null;

try {
    $inscricaoId = decrypt_id($tokenInscription, $encryptionKey, $signatureKey, 'Inscrição');
    $funcionarioId = decrypt_id($tokenFuncionario, $encryptionKey, $signatureKey, 'Funcionario');

    if (!is_numeric($inscricaoId) || !is_numeric($funcionarioId)) {
        throw new InvalidArgumentException('Parâmetros inválidos.');
    }


} catch (Exception $e) {
    echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
    exit;
}

try {
   $connection = $connection->connection();
   $connection->beginTransaction();

    
    $stmtSelect = $connection->prepare("SELECT funcionarios FROM ficha_inscricao WHERE id = :inscricaoId");
    $stmtSelect->execute([':inscricaoId' => $inscricaoId]);
    $currentFuncionarios = json_decode($stmtSelect->fetchColumn(), true);

    
    $indexToRemove = array_search(['id' => $funcionarioId], $currentFuncionarios);
    if ($indexToRemove !== false) {
        unset($currentFuncionarios[$indexToRemove]);
    }

 
    $stmtUpdate = $connection->prepare("UPDATE ficha_inscricao SET funcionarios = :funcionarios WHERE id = :inscricaoId");
    $stmtUpdate->execute([
        ':funcionarios' => json_encode(array_values($currentFuncionarios)),
        ':inscricaoId' => $inscricaoId
    ]);

    if (empty($currentFuncionarios)) {
        $stmtDelete = $connection->prepare("DELETE FROM ficha_inscricao WHERE id = :inscricaoId");
        $stmtDelete->execute([':inscricaoId' => $inscricaoId]);
    }

    $connection->commit();

    echo json_encode(['msg' => 'Funcionário removido com sucesso.', 'status' => 200]);
} catch (Exception $e) {

    $connection->rollBack();
    echo json_encode(['msg' => 'Erro ao remover o funcionário: ' . $e->getMessage(), 'status' => 500]);
}