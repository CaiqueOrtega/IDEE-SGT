<?php
require('../../../api/private/connect.php');
include('../../../api/private/cript.php');

header('Content-Type: application/json');

$connection = new Database();



if (!isset($encryptionKey) || !isset($signatureKey)) {
    echo "Erro: Chaves de criptografia ausentes.";
    exit();
}

if (!isset($_POST['tokensFuncionarios']) || !isset($_POST['tokenEmpresa']) || !isset($_POST['tokenTreinamento']) || !isset($_POST['dataEnvio'])) {
    echo json_encode(['msg' => 'Dados ausentes', 'status' => 400]);
    exit();
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

$funcionariosIDsJSON = json_encode($funcionariosIDs);

$tokenCompany = $_POST['tokenEmpresa'];
$tokenTraining = $_POST['tokenTreinamento'];
$dataEnvio = $_POST['dataEnvio'];

try {
    $empresaId = decrypt_id($tokenCompany, $encryptionKey, $signatureKey, 'Empresa');
    $treinamentoId = decrypt_id($tokenTraining, $encryptionKey, $signatureKey, 'Treinamento');
} catch (Exception $e) {
    echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
    exit;
}


if(getEmpresaCadastrada($connection, $empresaId, $treinamentoId)){
    echo json_encode(['msg' => 'Já existe uma ficha de inscrição para o mesmo treinamento e empresa.', 'status' => 400]);
    exit;
}


if (!empty($funcionariosIDs)) {
    $funcionariosCadastrados = getFuncionariosCadastrados($connection, $funcionariosIDs, $treinamentoId);

    if (!empty($funcionariosCadastrados)) {
        $nomesCadastrados = array_column($funcionariosCadastrados, 'nome');
         echo json_encode(['msg' => 'Os seguintes funcionários já estão registrados para o mesmo treinamento: ' . implode(', ', $nomesCadastrados), 'status' => 400]);
        exit();
    }
}



if (!empty($funcionariosIDs)) {
    $stmt = $connection->connection()->prepare("INSERT INTO `ficha_inscricao` (funcionarios, empresa_id, treinamento_id, data_realizacao) 
                                                VALUES (:funcionariosIDsJson, :tokenCompany, :tokenTraining, :dataEnvio)");

    $stmt->bindParam(':funcionariosIDsJson', $funcionariosIDsJSON, PDO::PARAM_STR);
    $stmt->bindParam(':tokenCompany', $empresaId, PDO::PARAM_STR);
    $stmt->bindParam(':tokenTraining', $treinamentoId, PDO::PARAM_STR);
    $stmt->bindParam(':dataEnvio', $dataEnvio, PDO::PARAM_STR);

    $stmt->execute();
    echo json_encode(['msg' => 'Funcionários inscritos no treinamento', 'status' => 200]);

} else {
    echo json_encode(['msg' => 'Nenhum ID de funcionário válido para inserir', 'status' => 400]);

}



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


function getEmpresaCadastrada($connection, $empresaId, $treinamentoId)
{
    $stmtCheck = $connection->connection()->prepare("
    SELECT 1
    FROM ficha_inscricao
    WHERE treinamento_id = :treinamentoId
      AND empresa_id = :empresaId
");

$stmtCheck->execute([
    ':treinamentoId' => $treinamentoId,
    ':empresaId' => $empresaId
]);

return $stmtCheck->fetchColumn();

}
