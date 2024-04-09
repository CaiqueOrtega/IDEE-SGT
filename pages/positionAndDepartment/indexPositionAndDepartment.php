<div class="container mt-5">
    <div class="modal fade" id="modalCompanyInfo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #58af9b;">
                    <h5 class="modal-title text-white" id="exampleModalLabel">Cargos e departamentos</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="container card border rounded-2 ">
                        <div class="row py-3 bg-light shadow border rounded-2 ">
                            <div class="col d-flex justify-content-between">
                                <h5 id="companyName"></h5>

                                <div class="d-flex">
                                    <button class="btn btn-outline-primary d-flex" id="editarCargoOuDepartamentoBtn"><i class="bi bi-pen-fill"> </i><span class="d-none d-md-block">Editar</span></button>
                                </div>


                            </div>

                        </div>
                    </div>
                    <?php require('./tablePositionAndDepartment.php'); ?>


                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal" id="modalSecundario" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirmar exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Você realmente deseja excluir o <span id="orName"> </span> <span id="positionOrDepartment"></span>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-danger" id="confirmDeleteCargoOrDepartamentoBtn">Confirmar</button>
            </div>
        </div>

    </div>
</div>

<div class="modal" id="modalUpdatePositionAndDepartment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirmar Edição em <span id="positionOrdepartmentName"></span></h5>
                <button type="button" class="btn-close  close-update-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Você realmente deseja editar de <span id="campoAtual"></span> para <span id=campoNovo></span>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-update-btn" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-danger" id="confirmUpdateCargoOrDepartamentoBtn">Confirmar</button>
            </div>
        </div>
        
    </div>
</div>

<?php include('../errorAndSuccessModal.php');?>

<script src="../src/js/positionAndDepartment.js"></script>