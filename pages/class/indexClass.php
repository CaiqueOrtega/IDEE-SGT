<?php 
session_start();
$idPermissao = $_SESSION['login']['permissao'];
$id = $_SESSION['login']['id'];
?>

<div id="alert" class="d-none alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Selecione</strong> qualquer campo na tabela que deseja editar!.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<div class="container card border  ">
  <div class="row py-3 bg-light shadow border rounded-2">
    <div class="col d-flex justify-content-between">
      <h3>Turmas</h3>
      <div class="d-flex">

        <?php if ($idPermissao == 1 || $idPermissao == 4) { ?>
          <button class="btn btn-outline-primary d-flex" id="editarBtn"><i class="bi bi-pen-fill"> </i><span class="d-none d-md-block">Editar</span></button>
        <?php } ?>

        <button class="btn btn-outline-danger fs-4 d-flex ms-2" id="relatorioBtnClass"><i class="fa-solid fa-file-pdf text-center"></i></button>
      </div>
    </div>
  </div>
</div>

<div class="mt-3">
  <?php require('./tableClass.php'); ?>
</div>



<div class="modal fade" id="modalDeleteClass" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Confirmar exclusão</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <p>Você realmente deseja excluir a turma <span id="nome_turma"></span>?</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-danger" id="confirmDeleteBtn">Confirmar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalUpdateClass" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Confirmar Edição em <span id="funcionarioNome"></span></h5>
        <button type="button" class="btn-close btn-close-update" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <p>Você realmente deseja editar de <span class="fw-bold" id="campoNome"></span> para <span class="fw-bold" id=campoValue></span>?</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-close-update" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-danger" id="confirmarUpdateBtn">Confirmar</button>
      </div>
    </div>
  </div>
</div>

<script src="../src/js/scriptClass.js"></script>
<?php include('../errorAndSuccessModal.php');
?>