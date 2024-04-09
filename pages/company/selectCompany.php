<?php 
require('../../api/private/connect.php');
include('../../api/private/cript.php');
$pdo = new Database();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id = $_SESSION['login']['id'];
$permissaoId = $_SESSION['login']['permissao'];

if($permissaoId != 1 && $permissaoId != 4){

    $innerWhereClause =  'INNER JOIN `usuario` ON empresa_cliente.usuario_id = usuario.id
    WHERE usuario.id = ?';
}else{
    $innerWhereClause = 'WHERE 1=1';
}

    $sql = "SELECT empresa_cliente.razao_social, empresa_cliente.id 
            FROM `empresa_cliente` 
            $innerWhereClause";
    $stmt = $pdo->connection()->prepare($sql);
    $stmt->execute([$id]);
    $empresasData =  $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<select class="form-select" name="empresa" id="employeeCompanySelect" aria-label="Default select example">
        <option value="" selected>Selecione a empresa...</option>
        <?php foreach ($empresasData as $empresa) : ?>
            <?php $token = encrypt_id($empresa['id'], $encryptionKey, $signatureKey); ?>
            <option value="<?= $token ?>"><?= htmlspecialchars($empresa['razao_social']) ?></option>
        <?php endforeach; ?>
    </select>
