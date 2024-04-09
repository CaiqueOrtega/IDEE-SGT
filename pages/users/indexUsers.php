<div id="alert" class="d-none alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Selecione</strong> qualquer campo na tabela que deseja editar!.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>


<div class="container card border  ">
  <div class="row py-3 bg-light shadow border rounded-2">
    <div class="col d-flex justify-content-between">
      <h3>Usuários</h3>

    



    </div>
  </div>
</div>

<div class="mt-3">
  <?php require('./tableUsers.php') ?>
</div>



<?php 
include('../errorAndSuccessModal.php');
 ?>

<script src="../src/js/users.js"></script>