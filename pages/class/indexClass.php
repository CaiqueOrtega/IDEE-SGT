
<div class="container card border  ">
    <div class="row py-3 bg-light shadow border rounded-2">
        <div class="col d-flex justify-content-between">
            <h3>Turmas</h3>


            <button class="btn btn-outline-danger fs-4 d-flex ms-2" id="relatorioBtnClass"><i class="fa-solid fa-file-pdf text-center"></i></button>
        </div>
    </div>
</div>

<?php include('./tableClass.php')?>

<div id="modalClassInfoContent"></div>







<style>
    .editable-cell-nomenclatura {
        max-width: 299px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>



<div class="modal fade" id="modalDeleteInscription" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">Confirmar exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <p>Você realmente deseja excluir a Turma ?</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-danger" id="confirmDeleteBtn">Confirmar</button>
            </div>
        </div>
    </div>
</div>




<script src="../src/js/inscription.js"></script>

<?php include('../errorAndSuccessModal.php'); ?>


