<?php
header('Content-Type: application/json');

require('../../../api/private/connect.php');
include('../../../api/validade/validate.php');
include('../../../api/private/cript.php');

$connection = new Database();

if (!isset($_POST['tokensFuncionarios']) ||  !isset($_POST['token']) ) {
    echo json_encode(['msg' => 'Dados ausentes.', 'status' => 400]);
    exit();
}

$tokenInscricao = $_POST['token'];

try {
    $inscricaoId = decrypt_id($tokenInscricao, $encryptionKey, $signatureKey, 'Treinamento');
} catch (Exception $e) {
    echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
    exit;
}

$tokensFuncionarios = $_POST['tokensFuncionarios'];

if ($tokensFuncionarios == null) {
    echo json_encode(['msg' => 'Dados ausentes, nenhum funcionário selecionado', 'status' => 400]);
    exit();
}



foreach ($tokensFuncionarios as $tokenFuncionario) {
    try {
        $decryptedToken = decrypt_id($tokenFuncionario, $encryptionKey, $signatureKey, 'Funcionario');
    } catch (Exception $e) {
        echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
        exit;
    }

    if ($decryptedToken !== null) {
        $funcionariosIDs[] = array('id' => $decryptedToken);
    }
}


$stmt = $connection->connection()->prepare("SELECT funcionarios FROM `ficha_inscricao` WHERE id = :inscricaoId");
$stmt->bindParam(':inscricaoId', $inscricaoId, PDO::PARAM_INT);
$stmt->execute();
$dadosAntigos = json_decode($stmt->fetchColumn(), true);


$dadosComNovos = array_merge($dadosAntigos, $funcionariosIDs);

$funcionariosIDsJSON = json_encode($dadosComNovos);


$stmt = $connection->connection()->prepare("UPDATE `ficha_inscricao` SET funcionarios = :funcionariosIDsJson WHERE id = :inscricaoId");
$stmt->bindParam(':funcionariosIDsJson', $funcionariosIDsJSON, PDO::PARAM_STR);
$stmt->bindParam(':inscricaoId', $inscricaoId, PDO::PARAM_INT);
$stmt->execute();

echo json_encode(['msg' => 'Funcionários inscritos no treinamento', 'status' => 200]);







function getFuncionariosCadastrados($connection, $funcionariosIDs, $treinamentoId)
{
    $funcionariosCadastrados = array();

    foreach ($funcionariosIDs as $funcionarioID) {
        $stmt = $connection->connection()->prepare("SELECT nome_funcionario FROM empresa_cliente_funcionario 
                                                   WHERE id = :funcionarioID 
                                                   AND EXISTS (SELECT 1 FROM ficha_inscricao 
                                                               WHERE JSON_SEARCH(funcionarios, 'one', :funcionarioID, NULL, '$[*].id') IS NOT NULL 
                                                               AND treinamento_id = :treinamentoId)");

        $stmt->bindParam(':funcionarioID', $funcionarioID['id'], PDO::PARAM_STR);
        $stmt->bindParam(':treinamentoId', $treinamentoId, PDO::PARAM_STR);
        $stmt->execute();

        $nome = $stmt->fetchColumn();

        if ($nome !== false) {
            $funcionariosCadastrados[] = array('id' => $funcionarioID['id'], 'nome' => $nome);
        }
    }

    return $funcionariosCadastrados;
}
