<?php
require('../../api/private/connect.php');
include('../../api/private/cript.php');

$connection = new Database();
session_start();
$id = $_SESSION['login']['id'];
$permissaoId = $_SESSION['login']['permissao'];

$innerWhereClause = '';
$parameters = [];

if ($permissaoId != 1 && $permissaoId != 4) {
    $innerWhereClause = 'INNER JOIN `usuario` ON empresa_cliente.usuario_id = usuario.id WHERE usuario.id = ?';
    $parameters = [$id];
} else {
    $innerWhereClause = 'WHERE 1=1';
}

$sql = "SELECT empresa_cliente.nome_fantasia, empresa_cliente.id 
        FROM `empresa_cliente` 
        $innerWhereClause";

$stmt = $connection->connection()->prepare($sql);


foreach ($parameters as $index => $param) {
    $stmt->bindParam($index + 1, $param, PDO::PARAM_INT);
}

$stmt->execute();

$empresasData = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<div class="modal fade" id="modaldynamicModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #58af9b; color:white;">
                <h1 class="modal-title fs-5" id="staticBackdropLabel"><i class="me-2 fas fa-users"></i>Cadastrar <span id="cargooudepartamento"></span></h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="company_insert" method="POST">
                    <div class="row">
                        <div class="msg d-none alert"></div>

                        <?php
                        if ($_POST['modal_type'] === 'Cargo') { ?>
                            <div class="col-md-7 mt-3">
                                <label class="form-label" for="cargo">Cargo</label>
                                <input id="cargoInput" type="text" name="cargo" class="form-control" placeholder="Digite o cargo que deseja adicionar">
                            </div>

                            <div class="col-md-5 mt-3">
                                <label class="form-label" for="cargo">Empresa</label>
                                <select id="empresa-select-cargo" class="form-select" name="cargo" aria-label="Default select example">
                                    <option value="" selected>Selecione uma empresa...</option>
                                    <?php foreach ($empresasData as $empresa) {
                                        $token = encrypt_id($empresa['id'], $encryptionKey, $signatureKey); ?>
                                        <option value="<?php echo $token; ?>"><?php echo $empresa['nome_fantasia']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        <?php } elseif ($_POST['modal_type'] === 'Departamento') { ?>
                            <div class="col-md-7 mt-3">
                                <label class="form-label" for="departamento">Departamento</label>
                                <input id="departamentoInput" type="text" name="departamento" class="form-control" placeholder="Digite o departamento que deseja adicionar">
                            </div>

                            <div class="col-md-5 mt-3">
                                <label class="form-label" for="departamento">Empresa</label>
                                <select id="empresa-select-departamento" class="form-select" name="departamento" aria-label="Default select example">
                                    <option value="" selected>Selecione uma empresa...</option>
                                    <?php foreach ($empresasData as $empresa) {
                                        $token = encrypt_id($empresa['id'], $encryptionKey, $signatureKey); ?>
                                        <option value="<?php echo $token; ?>"><?php echo $empresa['nome_fantasia']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        <?php } ?>


                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-end mt-5">
                <button type="submit" class="btn btn-login" id="confirmarCargoOuDepartamentoBtn">Confirmar</button>
            </div>
        </div>
    </div>
</div>