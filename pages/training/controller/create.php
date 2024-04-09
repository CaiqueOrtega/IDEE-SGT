<?php
require('../../../api/private/connect.php');
include('../../../api/validade/validate.php');
include('../../../api/private/cript.php');

$connection = new Database();
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id = $_SESSION['login']['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $valid = isValid([
        'nomenclatura', 'nr', 'objetivo', 'ementa', 'pre_requisitos',
        'material', 'normas_referencia', 'reciclagem', 'carga_horaria',
        'horas_teorica', 'horas_pratica', 'tokenColaborador'
    ]);

    if ($valid) {
        echo $valid;
    } else {
        $nomenclatura = cleanAndValidateCharsNumbers('Nomenclatura', $_POST['nomenclatura']);
        $nr = cleanAndValidateCharsNumbers('NR', $_POST['nr']);
        $objetivo = cleanAndValidateCharsNumbers('Objetivo', $_POST['objetivo']);
        $ementa = cleanAndValidateCharsNumbers('Ementa', $_POST['ementa']);
        $pre_requisitos = cleanAndValidateCharsNumbers('Pré-requisitos', $_POST['pre_requisitos']);
        $material = cleanAndValidateCharsNumbers('Material', $_POST['material']);
        $normas_referencia = cleanAndValidate('Normas Referência', $_POST['normas_referencia']);
      
        $carga_horaria =  $_POST['carga_horaria'];
        $horas_teoricas = $_POST['horas_teorica'];
        $horas_praticas = $_POST['horas_pratica'];

        switch ($_POST['reciclagem']) {
            case 'A':
                $reciclagem = 'Anual';
                break;
            case 'B':
                $reciclagem = 'Bianual';
                break;
            case 'T':
                $reciclagem = 'Trianual';
                break;
            default:
            echo json_encode(['msg' => 'Selecione o tempo valido para reciclagem', 'status' => 400]);
            exit;
                break;
        }
      
    

        try{
          $colaboradorId = decrypt_id($_POST['tokenColaborador'], $encryptionKey, $signatureKey, 'Colaborador'); 
          }catch(Exception $e){
              echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
              exit;
          }
      

        try {
            $pdo = $connection->connection();
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO `treinamento` 
                (`colaborador_id`, `nomenclatura`, `nr`, `objetivo`, `ementa`, `pre_requisitos`, `material`, `normas_referencia`, `reciclagem`, `carga_horaria`, `horas_teorica`, `horas_pratica`) 
                VALUES (:colaboradorId, :nomenclatura, :nr, :objetivo, :ementa, :pre_requisitos, :material, :normas_referencia, :reciclagem, :carga_horaria, :horas_teoricas, :horas_praticas)");

            $stmt->bindParam(':colaboradorId', $colaboradorId, PDO::PARAM_INT);
            $stmt->bindParam(':nomenclatura', $nomenclatura, PDO::PARAM_STR);
            $stmt->bindParam(':nr', $nr, PDO::PARAM_STR);
            $stmt->bindParam(':objetivo', $objetivo, PDO::PARAM_STR);
            $stmt->bindParam(':ementa', $ementa, PDO::PARAM_STR);
            $stmt->bindParam(':pre_requisitos', $pre_requisitos, PDO::PARAM_STR);
            $stmt->bindParam(':material', $material, PDO::PARAM_STR);
            $stmt->bindParam(':normas_referencia', $normas_referencia, PDO::PARAM_STR);
            $stmt->bindParam(':reciclagem', $reciclagem, PDO::PARAM_STR);
            $stmt->bindParam(':carga_horaria', $carga_horaria, PDO::PARAM_STR);
            $stmt->bindParam(':horas_teoricas', $horas_teoricas, PDO::PARAM_STR);
            $stmt->bindParam(':horas_praticas', $horas_praticas, PDO::PARAM_STR);

            $stmt->execute();
            $pdo->commit();

            echo json_encode(['msg' => 'Treinamento cadastrado com sucesso', 'status' => 200]);
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log('Erro ao cadastrar treinamento: ' . $e->getMessage());
            echo json_encode(['msg' => 'Erro interno ao cadastrar treinamento: ' . $e->getMessage(), 'status' => 500]);
        }
    }
}
?>
