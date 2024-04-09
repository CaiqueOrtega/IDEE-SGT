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
    $inscricaoId = decrypt_id($token, $encryptionKey, $signatureKey, 'Inscricao');
    }catch(Exception $e){
        echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
        exit;
    }
    
    try {
        $sql = "DELETE FROM `ficha_inscricao` WHERE `ficha_inscricao`.`id` = :inscricaoId";

        $stmt = $connection->connection()->prepare($sql);
        $stmt->bindParam(':inscricaoId', $inscricaoId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['msg' => 'Inscricao excluído com sucesso', 'status' => 200]);
        } else {
            echo json_encode(['msg' => 'Erro ao excluir o funcionário', 'status' =>400]);
        }
    } catch (PDOException $e) {
        echo json_encode(['msg' => 'Erro no banco de dados: ' . $e->getMessage(), 'status' => 500]);
    }
} else {
    echo json_encode(['msg' => 'Token não fornecido', 'status' => 400]);
}

exit();
?>
