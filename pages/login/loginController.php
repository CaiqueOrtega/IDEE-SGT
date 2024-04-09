<?php
session_start();
include '../../api/private/connect.php';

$connection = new Database();
$connection = $connection->connection();


header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) ? ($_POST['email']) : null;
    $password = isset($_POST['password']) ? ($_POST['password']) : null;

    if (empty($email) || empty($password)) {
        echo json_encode(['msg' => 'Preencha todos os campos', 'status' => 400]);
    } else {
        $password = md5($password);

        try {
            $stmt = $connection->prepare("SELECT * FROM login WHERE email = :email AND senha = :password");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['id'];
                $_SESSION['login'] = [
                    'id' => $id,
                    'nome' => $row['nome'],
                    'email' => $row['email'],
                    'permissao' => $row['permissao_id']
                ];

                
                $resp = ['msg' => 'Login efetuado com sucesso', 'status' => 200, 'redirect' => '/projeto/pages/dashboard.php'];
            
            } else {
                $resp = ['msg' => 'E-mail ou Senha inválida', 'status' => 403];
            }
        } catch (PDOException $e) {
            $resp = ['msg' => 'Erro na consulta SQL: ' . $e->getMessage(), 'status' => 500];
        }

        echo json_encode($resp);
        exit();
    }
}



if (isset($_GET['logout']) && isset($_SESSION['login'])) {
    session_unset();
    session_destroy();
    header("Location: /projeto/home.php");
    exit();
} 