<?php
require('../../../api/private/connect.php');
include('../../../api/private/cript.php');

header('Content-Type: application/json');

$connection = new Database();

session_start();
$id = $_SESSION['login']['id'];


if (isset($_POST['token'])) {
    $token = $_POST['token'];
    try{
    $treinamentoId = decrypt_id($token, $encryptionKey, $signatureKey, 'treinamento');
    }catch(Exception $e){
        echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
        exit;
    }
    
    try {
        $sql = "DELETE FROM `treinamento` WHERE `treinamento`.`id` = :treinamentoId";

        $stmt = $connection->connection()->prepare($sql);
        $stmt->bindParam(':treinamentoId', $treinamentoId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['msg' => 'Treinamento excluído com sucesso', 'status' => 200]);
        } else {
            echo json_encode(['msg' => 'Erro ao excluir o Treinamento', 'status' => 400]);
        }
    } catch (PDOException $e) {
        echo json_encode(['msg' => 'Erro no banco de dados: ' . $e->getMessage(), 'status' => 500]);
    }
} else {
    echo json_encode(['msg' => 'Token não fornecido', 'status' => 400]);
}

exit();
?>
