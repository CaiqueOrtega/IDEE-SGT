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


// Atualização da nota do aluno
if (isset($_POST['notas'])) {
    try {
        $notas = isset($_POST["notas"]) ? $_POST["notas"] : [];

        foreach ($notas as $nota) {
            $aluno_id = $nota["aluno_id"];
            $nota_pratica = $nota["nota_pratica"];
            $nota_teorica = $nota["nota_teorica"];

            // Verificar se as notas estão no formato correto
            $nota_pratica = validateNotaNumbers('nota_pratica', $nota_pratica);
            $nota_teorica = validateNotaNumbers('nota_teorica', $nota_teorica);

            // Converter valores para float para cálculos
            $nota_pratica = str_replace(',', '.', $nota_pratica);
            $nota_teorica = str_replace(',', '.', $nota_teorica);

            $nota_media = ($nota_pratica * 0.3) + ($nota_teorica * 0.7);

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

    // Verifica se o valor está vazio
    if (empty($value)) {
        echo json_encode(['msg' => "Campo '$fieldName' não pode ser vazio.", 'status' => 400]);
        exit;
    }

    // Verifica se o valor está no formato correto
    if (!preg_match('/^\d{1,2}(\,\d)?$/', $value)) {
        echo json_encode(['msg' => "Campo '$fieldName' deve estar no formato X,X, XX,X, ou um número inteiro de 0 a 10.", 'status' => 400]);
        exit;
    }

    // Converte o valor para float para validação do intervalo
    $valueNumeric = floatval(str_replace(',', '.', $value));

    // Verifica se o valor está dentro do intervalo permitido
    if ($valueNumeric < 0 || $valueNumeric > 10) {
        echo json_encode(['msg' => "Campo '$fieldName' deve ser um número entre 0 e 10.", 'status' => 400]);
        exit;
    }

    // Se o valor for um número inteiro de 0 a 10, adiciona ",0"
    if (preg_match('/^\d{1,2}$/', $value)) {
        $value .= ',0';
    }

    // Filtra apenas os números e a vírgula
    return preg_replace('/[^0-9,]/', '', $value);
}



//---------------------------------------------------------------------------------------------------------------------------

if (isset($_POST['alunos'])) {
    try {
        $alunos = $_POST['alunos'];

        $pdo = $connection->connection();

        foreach ($alunos as $aluno) {
            $aluno_id = $aluno['aluno_id'];
            $turma_id = $aluno['turma_id'];
            $presencas = $aluno['presencas'];

            $totalFaltas = 0;
            $totalDias = count($presencas);
            foreach ($presencas as $presenca) {
                $dia = $presenca["dia"];
                $presenca = $presenca["presenca"];

                $sqlDelete = ("DELETE FROM `frequencia_aluno` WHERE `aluno_id_fk` = :aluno_id_fk AND `turma_id_fk` = :turma_id_fk AND `dia` = :dia");
                $stmtDelete = $pdo->prepare($sqlDelete);
                $stmtDelete->bindValue(":aluno_id_fk", $aluno_id, PDO::PARAM_INT);
                $stmtDelete->bindValue(":turma_id_fk", $turma_id, PDO::PARAM_INT);
                $stmtDelete->bindValue(":dia", $dia, PDO::PARAM_INT);
                $stmtDelete->execute();

                if ($presenca == "N") {
                    $totalFaltas++;
                    $sqlInsert = ("INSERT INTO `frequencia_aluno` (`aluno_id_fk`, `turma_id_fk`, `dia`) VALUES (:aluno_id_fk, :turma_id_fk, :dia)");
                    $stmtInsert = $pdo->prepare($sqlInsert);
                    $stmtInsert->bindValue(":aluno_id_fk", $aluno_id, PDO::PARAM_INT);
                    $stmtInsert->bindValue(":turma_id_fk", $turma_id, PDO::PARAM_INT);
                    $stmtInsert->bindValue(":dia", $dia, PDO::PARAM_INT);
                    $stmtInsert->execute();
                }
            }

            if ($totalFaltas > 0) {
                $valor = 100;
                $percentual = ($totalFaltas * 100) / $totalDias;
                $valor_final = $valor - ($percentual * $valor);

                $total = 100;
                $pctm = $percentual;

                $frequencia = $total - ($total * $pctm * 0.01);
            } else {
                $frequencia = 100;
            }

            $sql = "UPDATE `aluno` SET `frequencia` = :frequencia WHERE `id` = :id AND `turma_aluno_fk` = :turma_aluno_fk";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $aluno_id, PDO::PARAM_INT);
            $stmt->bindParam(':turma_aluno_fk', $turma_id, PDO::PARAM_INT);
            $stmt->bindParam(':frequencia', $frequencia, PDO::PARAM_STR);
            $stmt->execute();
        }

        echo json_encode(['msg' => 'Dados das notas atualizados com sucesso.', 'status' => 200]);
    } catch (Exception $e) {
        error_log('Erro na atualização: ' . $e->getMessage());
        echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
    }
}





// Supondo que $connection é um objeto de conexão existente.



// Verificar se os dados foram recebidos corretamente via POST
if (isset($_POST['turmaId']) && isset($_POST['turmaDataConclusao'])) {
    try {
       $turmaId = $_POST['turmaId'];
        $data_conclusao = $_POST['turmaDataConclusao'];

    $pdo = $connection->connection();

    
    
    // Verificar se os valores recebidos não estão vazios
    if (empty($turmaId) || empty($data_conclusao)) {
        echo json_encode(['status' => 'error', 'message' => 'Dados inválidos.']);
        exit;
    }

    // Verificar se a data de conclusão é '0000-00-00'
    if ($data_conclusao == '0000-00-00') {
        // Atualizar a data de conclusão com a data atual
        $nova_data_conclusao = date('Y-m-d');
        
        // Ajustar o nome da coluna conforme necessário. Aqui assumimos que a coluna correta é 'id'.
        $sql = "UPDATE turma SET data_conclusao = :nova_data_conclusao WHERE id = :turma_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nova_data_conclusao', $nova_data_conclusao);
        $stmt->bindParam(':turma_id', $turmaId);

        // Executar a consulta e verificar o resultado
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Data de conclusão atualizada com sucesso.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar a data de conclusão.']);
        }
    } else {
        echo json_encode(['status' => 'no_change', 'message' => 'Data de conclusão já está definida. Nenhuma atualização necessária.']);
    }
}catch (Exception $e) {
    error_log('Erro na atualização: ' . $e->getMessage());
    echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
}

}