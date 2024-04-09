<?php
require('../../api/private/connect.php');
include('../../api/private/cript.php');
include('../../api/validade/validate.php');

$connection = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = isset($_POST['token']) ? $_POST['token'] : null;
    $tokenTreinamento = isset($_POST['tokenTraining']) ? $_POST['tokenTraining'] : null;
    $page = isset($_POST['pages']) ? $_POST['pages'] : null;

    $valid = isValid(['token', 'tokenTraining', 'pages']);
    if ($valid) {
        echo  $valid;
        exit;
    }



    try {
        $treinamentoId = decrypt_id($tokenTreinamento, $encryptionKey, $signatureKey, 'Treinamento');
        $empresaId = decrypt_id($token, $encryptionKey, $signatureKey, 'Empresa');
    } catch (Exception $e) {
        echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
        exit;
    }

    $inscricaoExiste = empresaPossuiInscricao($connection, $empresaId, $treinamentoId);

    if ($page !== "training" && $page !== "inscription") {
        echo json_encode(['msg' => 'Dados alterados', 'status' => 400]);
        exit;
    } elseif ($page === "training" && $inscricaoExiste) {
        echo '<h6 class="mt-4">Esta empresa já possui uma inscrição pendente para este treinamento. 
        Para visualizar ou modificar os dados, acesse a seção de 
        <a href="#" data-bs-dismiss="modal" aria-label="Close" 
        onclick="loadContent(\'/projeto/pages/inscription/indexInscription.php\')" 
        class="font-weight-bold">inscrições pendentes</a>.</h6>';
        exit;
    }


    $funcionariosData = getFuncionariosNaoCadastrados($connection, $empresaId, $treinamentoId);

    if (empty($funcionariosData)) {
        header('Content-Type: application/json');
        echo json_encode(['msg' => 'Todos os funcionários dessa empresa já estão cadastrados para este treinamento.', 'status' => 400]);
        exit;
    }
}

function empresaPossuiInscricao($connection, $empresaId, $treinamentoId)
{
    $sql = "SELECT COUNT(*) FROM `ficha_inscricao` WHERE empresa_id = :empresaId AND treinamento_id = :treinamentoId";
    $stmt = $connection->connection()->prepare($sql);
    $stmt->bindParam(':empresaId', $empresaId, PDO::PARAM_INT);
    $stmt->bindParam(':treinamentoId', $treinamentoId, PDO::PARAM_INT);
    $stmt->execute();

    $count = $stmt->fetchColumn();

    return $count > 0;
}

function getFuncionariosNaoCadastrados($connection, $empresaId, $treinamentoId)
{
    $sql = "SELECT ecfuncionario.*, eccargo.nome AS nome_cargo, ecdepartamento.nome AS nome_departamento
            FROM `empresa_cliente_funcionario` AS ecfuncionario
            INNER JOIN `empresa_cliente` AS ec ON ecfuncionario.empresa_id = ec.id
            INNER JOIN `empresa_cliente_cargo` AS eccargo ON ecfuncionario.cargo_id = eccargo.id
            INNER JOIN `empresa_cliente_departamento` AS ecdepartamento ON ecfuncionario.departamento_id = ecdepartamento.id
            WHERE ecfuncionario.empresa_id = :empresaId
              AND NOT EXISTS (
                  SELECT 1 FROM ficha_inscricao 
                  WHERE JSON_SEARCH(funcionarios, 'one', ecfuncionario.id, NULL, '$[*].id') IS NOT NULL 
                    AND treinamento_id = :treinamentoId
              )";

    $stmt = $connection->connection()->prepare($sql);
    $stmt->bindParam(':empresaId', $empresaId, PDO::PARAM_INT);
    $stmt->bindParam(':treinamentoId', $treinamentoId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
