<?php
header('Content-Type: application/json');

require('../../../api/private/connect.php');
include('../../../api/private/cript.php');
include('../../../api/validade/validate.php');

$connection = new Database();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id = isset($_SESSION['login']['id']) ? $_SESSION['login']['id'] : null;

// Atualização da turma
if (isset($_POST['colaborador_id'], $_POST['tokenTurma']) && $id !== null) {
    $valid = isValid(['colaborador_id', 'tokenTurma']);

    if ($valid) {
        echo json_encode(['msg' => $valid, 'status' => 400]);
        exit();
    }

    $tokenTurma = $_POST['tokenTurma'];

    try {
        $turmaId = decrypt_id($tokenTurma, $encryptionKey, $signatureKey, 'Turma');
    } catch (Exception $e) {
        echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
        exit;
    }

    $campo = 'colaborador_id_fk';
    $colaboradorId = $_POST['colaborador_id'];

    if ($colaboradorId === false) {
        echo json_encode(['msg' => 'Colaborador Campo Inválido', 'status' => 400]);
        exit;
    }

    try {
        $pdo = $connection->connection();

        $sql = "UPDATE `turma` SET $campo = :valor WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':valor', $colaboradorId);
        $stmt->bindParam(':id', $turmaId, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['msg' => 'Atualização da turma bem-sucedida!', 'status' => 200]);
    } catch (PDOException $e) {
        echo json_encode(['msg' => 'Erro na atualização da turma: ' . $e->getMessage(), 'status' => 500]);
    } catch (Exception $e) {
        echo json_encode(['msg' => $e->getMessage(), 'status' => 500]);
    }
}



// Atualização do status do aluno
elseif (isset($_POST['tokenAluno'], $_POST['novoStatus'])) {
    $tokenAluno = $_POST['tokenAluno'];
    $novoStatus = $_POST['novoStatus'];

    try {
        $alunoId = decrypt_id($tokenAluno, $encryptionKey, $signatureKey, 'Aluno');
    } catch (Exception $e) {
        echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
        exit;
    }

    $campo = 'status';
    $status = $novoStatus;

    if ($status === false) {
        echo json_encode(['msg' => 'Status Campo Inválido', 'status' => 400]);
        exit;
    }

    try {
        $pdo = $connection->connection();

        $sql = "UPDATE `aluno` SET $campo = :valor WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':valor', $status);
        $stmt->bindParam(':id', $alunoId, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['msg' => 'Atualização do status do aluno bem-sucedida!', 'status' => 200]);
    } catch (PDOException $e) {
        echo json_encode(['msg' => 'Erro na atualização do status do aluno: ' . $e->getMessage(), 'status' => 500]);
    } catch (Exception $e) {
        echo json_encode(['msg' => $e->getMessage(), 'status' => 500]);
    }
}

// Se nenhum dos blocos anteriores for acionado, há parâmetros ausentes na requisição




//----------------------------------------------------------------------------------------------------------


// Atualização do status do aluno
if (isset($_POST['notas'])) {
    try {
        $notas = isset($_POST["notas"]) ? $_POST["notas"] : [];

        foreach ($notas as $nota) {
            $aluno_id = $nota["aluno_id"];
            $nota_pratica = $nota["nota_pratica"];
            $nota_teorica = $nota["nota_teorica"];

            $nota_media = 10;

            $pdo = $connection->connection();

            $sql = "UPDATE `aluno` SET `nota_pratica` = :nota_pratica, `nota_teorica` = :nota_teorica, `nota_media` = :nota_media WHERE `id` = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $aluno_id, PDO::PARAM_INT);
            $stmt->bindParam(':nota_pratica', $nota_pratica, PDO::PARAM_STR);
            $stmt->bindParam(':nota_teorica', $nota_teorica, PDO::PARAM_STR);
            $stmt->bindParam(':nota_media', $nota_media, PDO::PARAM_STR);

            $stmt->execute();
        }

        echo json_encode(['msg' => 'Dados das notas atualizados com sucesso.', 'status' => 200]);
    } catch (Exception $e) {
        error_log('Erro na atualização: ' . $e->getMessage());
        echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
    }
}

function validateNotaNumbers($fieldName, $value)
{
    // Remove espaços em branco no início e fim da string
    $value = trim($value);

    // Verifica se o valor está no formato X,X ou XX,X
    if (!preg_match('/^\d{1,2},\d$/', $value)) {
        echo json_encode(['msg' => "Campo '$fieldName' deve estar no formato X,X ou XX,X.", 'status' => 400]);
        exit;
    }

    // Filtra apenas os números (opcional, depende do uso posterior)
    $filteredData = preg_replace('/[^0-9,]/', '', $value);

    return $filteredData;
}
