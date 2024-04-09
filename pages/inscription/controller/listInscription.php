<?php
require '../../api/private/connect.php';
include '../../api/private/cript.php';

$connection = new Database();

if (!isset($_POST['token'])) {
    echo json_encode(['msg' => 'Token não definido', 'status' => 400]);
    exit;
}
$token = $_POST['token'];

try {
    $inscricaoId = decrypt_id($token, $encryptionKey, $signatureKey, 'Inscricao');
} catch (Exception $e) {
    handleException($e);
}

$inscricao = getInscricaoData($connection, $inscricaoId);

$funcionariosData = [];
$funcionariosIds = extractFuncionariosIds($inscricao['funcionarios']);

if (!empty($funcionariosIds)) {
    $funcionariosIdsString = implode(',', $funcionariosIds);
    $funcionariosData = getFuncionariosData($connection, $funcionariosIdsString);
}

function handleException(Exception $e)
{
    echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
    exit;
}

function getInscricaoData($connection, $inscricaoId)
{
    $sql = "SELECT ficha_inscricao.*, 
                   ficha_inscricao.id AS inscricao_id,
                   empresa_cliente.id AS empresa_id,
                   empresa_cliente.*, 
                   treinamento.id AS treinamento_Id, 
                   treinamento.*
            FROM `ficha_inscricao`
            INNER JOIN `empresa_cliente` ON ficha_inscricao.empresa_id = empresa_cliente.id
            INNER JOIN `treinamento` ON ficha_inscricao.treinamento_id = treinamento.id
            WHERE ficha_inscricao.id = :id";
    $stmt = $connection->connection()->prepare($sql);
    $stmt->bindParam(':id', $inscricaoId, PDO::PARAM_INT);
    $stmt->execute();

    $inscricaoData = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($inscricaoData === false) {
        echo json_encode(['msg' => 'Inscrição não encontrada', 'status' => 404]);
        exit;
    }

    return $inscricaoData;
}

function extractFuncionariosIds($funcionariosJson)
{
    $funcionariosIds = [];
    $funcionariosArray = json_decode($funcionariosJson, true);

    // Verificar se $funcionariosArray é realmente um array
    if (is_array($funcionariosArray)) {
        foreach ($funcionariosArray as $funcionario) {
            // Verificar se a chave 'id' existe no array do funcionário
            if (isset($funcionario['id'])) {
                $funcionariosIds[] = $funcionario['id'];
            }
        }
    }

    return $funcionariosIds;
}

function getFuncionariosData($connection, $funcionariosIdsString)
{
    if (empty($funcionariosIdsString)) {
        return [];
    }

    $sql = "SELECT * FROM `empresa_cliente_funcionario` WHERE id IN ($funcionariosIdsString)";
    $stmt = $connection->connection()->query($sql);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPositionDepartment($connection, $idCargo, $idDepartamento)
{
    $cargoData = fetchDataById($connection, 'empresa_cliente_cargo', $idCargo, 'nome AS nome_cargo');
    $departamentoData = fetchDataById($connection, 'empresa_cliente_departamento', $idDepartamento, 'nome AS nome_departamento');

    return [
        'nome_cargo' => $cargoData['nome_cargo'],
        'nome_departamento' => $departamentoData['nome_departamento'],
    ];
}

function fetchDataById($connection, $table, $id, $select)
{
    $sql = "SELECT $select FROM `$table` WHERE id = :id";
    $stmt = $connection->connection()->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}




?>
