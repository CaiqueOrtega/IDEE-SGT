<?php
require('../../api/private/connect.php');
include('../../api/private/cript.php');


session_start();

$id = $_SESSION['login']['id'];
$permissaoId = $_SESSION['login']['permissao'];

$connection = new Database();

$tokenTraining = $_POST['token'];

try {
  $treinamenoId = decrypt_id($tokenTraining, $encryptionKey, $signatureKey, 'Treinamento');
} catch (Exception $e) {
  echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
  exit;
}



if ($permissaoId != 1 && $permissaoId != 4) {
  $whereClause = 'WHERE usuario.id = :id';
} else {
  $whereClause = 'WHERE 1=1';
}


$sql = "SELECT empresa_cliente.razao_social, empresa_cliente.id,
MAX(IF(ficha_inscricao.treinamento_id = :treinamentoId, 1, 0)) AS possui_solicitacao_pendente
FROM `empresa_cliente` 
INNER JOIN `usuario` ON empresa_cliente.usuario_id = usuario.id
LEFT JOIN `ficha_inscricao` ON ficha_inscricao.empresa_id = empresa_cliente.id
                       AND ficha_inscricao.treinamento_id = :treinamentoId
                       $whereClause
GROUP BY empresa_cliente.id";


$stmt = $connection->connection()->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->bindParam(':treinamentoId', $treinamenoId, PDO::PARAM_INT);
$stmt->execute();



$empresasData = $stmt->fetchAll(PDO::FETCH_ASSOC);

$todasComSolicitacoesPendentes = array_sum(array_column($empresasData, 'possui_solicitacao_pendente')) == count($empresasData);


if ($permissaoId != 1 && $permissaoId != 4) {
  if ($todasComSolicitacoesPendentes) {
    header('Content-Type: application/json');
    echo json_encode(['msg' => 'Todas suas empresas cadastradas já possuem uma solicitação pendente para esse treinamento.', 'status' => 400]);
    exit;
  }
}


?>



<div class="modal fade" id="modalMoreInscricaoTraining" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg ">
    <div class="modal-content ">
      <div class="modal-header" style="background-color: #58af9b; color:white;">
        <h5 id="treinamentoNome"></h5>

        <h1 class="modal-title fs-5" id="staticBackdropLabel"></h1>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">


        <div class="container card border rounded-2 ">
          <div class="row py-3 bg-light shadow border rounded-2 ">
            <div class="col d-flex justify-content-between">
              <div class="col-md-5">

                <select class="form-select fs-5 " name="empresa" id="employeeCompanySelect" aria-label="Default select example" style="background-color: transparent !important;">
                  <option class="text-dark fs-6" value="" selected>Clique aqui e selecione a empresa...</option>
                  <?php
                  foreach ($empresasData as $empresa) {

                    $tokenCompany = encrypt_id($empresa['id'], $encryptionKey, $signatureKey); ?>

                    <option class="text-dark fs-6" value="<?php echo $tokenCompany ?>"><?php echo $empresa['razao_social']; ?> </option>
                  <?php } ?>
                </select>
              </div>

              <form class="d-none d-md-flex formSearch  input-group w-auto my-auto">

                <input autocomplete="on" type="search" class="form-control py-2" id="searchInput" placeholder='Digite o que deseja pesquisar...' style="min-width: 300px" />
                <span class="input-group-text text-white" style="background-color: #59af9b;"><i class="bi bi-search"></i></span>
              </form>

            </div>
          </div>
        </div>

        <div id="table-employee"></div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-login" data-token="<?php echo $tokenTraining; ?>" id="enviarInscricao">Enviar Inscrição</button>
      </div>
    </div>
  </div>
</div>