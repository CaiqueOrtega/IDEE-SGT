
<div id="alert" class="d-none alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Selecione</strong> qualquer campo na tabela que deseja editar!.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>


<div class="container card border  ">
    <div class="row py-3 bg-light shadow border rounded-2">
        <div class="col d-flex justify-content-between">
            <h3>Treinamentos</h3>

            <div class="d-flex">
                <button class="btn btn-outline-success me-2" data-bs-toggle="modal" data-bs-target="#ModalCreateTraining"><i class="bi bi-person-fill-add me-1"></i> Registrar</button>

            </div>

        </div>
    </div>
</div>



<?php require('./tableTraining.php');?>
<?php include('../errorAndSuccessModal.php');?>
<?php include('./modalCreateTraining.php'); ?>


<div class="modal fade" id="modalDeleteTraining" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Confirmar exclusão</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <p>Você realmente deseja excluir o treinamento <span id="nomenclatura"></span>?</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-danger" id="confirmDeleteBtn">Confirmar</button>
      </div>
    </div>
  </div>
</div>

<script src="../src/js/training.js"></script>

