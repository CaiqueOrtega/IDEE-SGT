<?php
require('../../api/private/connect.php');
include('../../api/validade/validate.php');
include('../../api/private/cript.php');

$connection = new Database();
session_start();
$id = $_SESSION['login']['id'];
$permissao = $_SESSION['login']['permissao'];

if (!isset($_POST['tokenUsuario'])) {
    respondError('Parâmetros ausentes na requisição', 400);
}

$valid = isValid(['tokenUsuario']);
if ($valid) {
    respondError('Dados Inválidos', 400);
}

$tokenUsuario = $_POST['tokenUsuario'];

try {
    $usuarioId = decrypt_id($tokenUsuario, $encryptionKey, $signatureKey, 'Usuário');
} catch (Exception $e) {
    respondError($e->getMessage(), 400);
}

if ($usuarioId == $id) {
    respondError('Não é possível editar as permissões do seu próprio usuário', 400);
}

$permissaoAtual = getPermissao($connection, $usuarioId);

$permissaoAtualId = $permissaoAtual['permissao_id'];
$permissaoAtualNome = getPermissaoNome($connection, $permissaoAtualId);

if ($permissaoAtualId == 4) {
    respondError('Não é possível editar as permissões desse usuário', 400);
}

$permissoes = getOtherPermissoes($connection, $permissaoAtualId);

if ($permissao == 1) {
    $idToRemove = 1;
    foreach ($permissoes as $key => $permissao) {
        if ($permissao['id'] == $idToRemove) {
            unset($permissoes[$key]);
            break; 
        }
    }

}

?>


<div class="modal fade" id="modalPermissao" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #58af9b; color:white;">
                <h1 class="modal-title fs-5" id="staticBackdropLabel"><i class="me-2 fas fa-users"></i> Atualizar Permissões <span id="cargooudepartamento"></span></h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="company_insert" method="POST">
                    <div class="row">
                        <div class="msg d-none alert"></div>

                        <div class="col-md-12">
                            <hr>
                            <span><span class="fw-bold">Nome do Usuário: </span> <?php echo $permissaoAtual['nome']; ?></span>
                            <br>
                            <span id="permissaoAtualNome"><span class="fw-bold">Permissão Atual: </span><?php echo $permissaoAtualNome; ?></span>
                            <hr>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label" for="cargo">Empresa</label>
                            <select id="permissao-select" class="form-select" name="permissao" aria-label="Default select example">
                                <option value="" selected>Selecione a permissao...</option>
                                <?php foreach ($permissoes as $permissao) {
                                    $token = encrypt_id($permissao['id'], $encryptionKey, $signatureKey); ?>
                                    <option value="<?php echo $token; ?>"><?php echo $permissao['nome']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-end">
                <button type="submit" class="btn btn-login" id="confirmarUpdatePermissaoBtn" data-token="<?php echo $tokenUsuario; ?>">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<?php
function respondError($message, $statusCode)
{
    header('Content-Type: application/json');
    echo json_encode(['msg' => $message, 'status' => $statusCode]);
    exit;
}

function getPermissao($connection, $usuarioId)
{
    $sql = "SELECT permissao_id, nome FROM `login` WHERE id = :id";
    $stmt = $connection->connection()->prepare($sql);
    $stmt->bindParam(':id', $usuarioId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return ($result) ? $result : null;
}

function getOtherPermissoes($connection, $permissaoId)
{
    $sql = "SELECT * FROM `permissao` WHERE id != :permissao_id AND id != 4";
    $stmt = $connection->connection()->prepare($sql);
    $stmt->bindParam(':permissao_id', $permissaoId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPermissaoNome($connection, $permissaoId)
{
    $sql = "SELECT nome FROM `permissao` WHERE id = :id";
    $stmt = $connection->connection()->prepare($sql);
    $stmt->bindParam(':id', $permissaoId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchColumn();
}
?>
