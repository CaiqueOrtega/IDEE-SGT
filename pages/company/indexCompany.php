<div id="alert" class="d-none alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Selecione</strong> qualquer campo na tabela que deseja editar!.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>


<div class="container card border  ">
  <div class="row py-3 bg-light shadow border rounded-2">
    <div class="col d-flex justify-content-between">
      <h3>Empresas</h3>

      <div class="d-flex">
        <button class="btn btn-outline-success me-2" id="dropdownCadastros" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person-fill-add me-1"></i> Registrar</button>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownCadastros">
          <li><a class="dropdown-item open-modal" data-modal-type="Cargo" href="#">Cargo</a></li>
          <li><a class="dropdown-item open-modal" data-modal-type="Departamento" href="#">Departamento</a></li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#staticBackdropRegister" href="#">Empresa</a></li>
        </ul>


        <button class="btn btn-outline-primary d-flex" id="editarBtn"><i class="bi bi-pen-fill"> </i><span class="d-none d-md-block"> Editar</span></button>
        <button class="btn btn-outline-danger fs-4 d-flex ms-2" id="relatorioBtnCompany"><i class="fa-solid fa-file-pdf text-center"></i></button>
      </div>



    </div>
  </div>
</div>

<script>
  $(document).ready(function() {

    $("#relatorioBtnCompany").click(function() {

      if ($('#tableCompanys tbody tr').length === 0) {

        $("#errorMsg").text('A tabela não contém dados. Não é possível gerar o relatório.');
        var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));

        errorModal.show();
      } else {

        window.open('relatorio/indexCompany.php', '_blank');
      }
    });
  });
</script>

<script>
  $(document).ready(function() {
    $('.open-modal').on('click', function() {
      var modalType = $(this).data('modal-type');

      $.ajax({
        url: 'positionAndDepartment/modalInsertPositionAndDepartment.php',
        type: 'POST',
        data: {
          modal_type: modalType
        },
        success: function(response) {
          $('#dynamicModalContent').html(response);
          $('#modaldynamicModal').modal('show');
          $('#cargooudepartamento').text(modalType);
        },
        error: function(error) {
          console.log(error);
        }
      });
    });
  });
</script>


<div class="mt-3">
  <?php require('./tableCompany.php') ?>
</div>




<?php require('./modalCompany.php');

include('../errorAndSuccessModal.php'); ?>
<script src="../src/js/scriptCompany.js"></script>