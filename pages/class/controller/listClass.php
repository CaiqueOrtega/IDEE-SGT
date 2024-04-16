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
    $turmaId = decrypt_id($token, $encryptionKey, $signatureKey, 'Turma');
} catch (Exception $e) {
    handleException($e);
}

$turma = getTurmaData($connection, $turmaId);

$alunosData = [];
$alunosIds = extractAlunosIds($turma['alunos']);

if (!empty($alunosIds)) {
    $alunosIdsString = implode(',', $alunosIds);
    $alunosData = getAlunosData($connection, $alunosIdsString);
}

function handleException(Exception $e)
{
    echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
    exit;
}

function getTurmaData($connection, $turmaId)
{
    $sql = "SELECT * FROM `turma` WHERE id = :id";
    $stmt = $connection->connection()->prepare($sql);
    $stmt->bindParam(':id', $turmaId, PDO::PARAM_INT);
    $stmt->execute();

    $turmaData = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($turmaData === false) {
        echo json_encode(['msg' => 'Turma não encontrada', 'status' => 404]);
        exit;
    }

    return $turmaData;
}

function extractAlunosIds($alunosJson)
{
    $alunosIds = [];
    $alunosArray = json_decode($alunosJson, true);

    // Verificar se $alunosArray é realmente um array
    if (is_array($alunosArray)) {
        foreach ($alunosArray as $aluno) {
            // Verificar se a chave 'id' existe no array do aluno
            if (isset($aluno['id'])) {
                $alunosIds[] = $aluno['id'];
            }
        }
    }

    return $alunosIds;
}

function getAlunosData($connection, $alunosIdsString)
{
    if (empty($alunosIdsString)) {
        return [];
    }

    $sql = "SELECT * FROM `aluno` WHERE id IN ($alunosIdsString)";
    $stmt = $connection->connection()->query($sql);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


