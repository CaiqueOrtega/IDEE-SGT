<?php require('./controller/list.php'); ?>



<div id= "tableCargoDepartamento" data-token-company="<?php echo $token = $_POST['token']; ?>" class="table-responsive d-flex">
  <table id="tabelCargo" class="table table-hover table-striped" style="--bs-table-bg: transparent !important;">
    <thead>
      <tr>
        <th scope="col">Nome Cargo</th>
        <th></th>
      </tr>


    </thead>

    <tbody class="mt-1">
      <?php

      foreach ($cargosData as $cargo) {
        $token = encrypt_id($cargo['id'], $encryptionKey, $signatureKey); ?>

        <tr class="data-row" data-token="<?php echo $token; ?>">

          <th class="editable-cell-cargo-departamento cargo" data-field="nome"><?php echo $cargo['nome']; ?></th>

          <td class="text-center">
            <a href="#" class="ms-2 text-danger text-center delete-button-cargo" data-url="positionAndDepartment/controller/deleteCargo.php">
              <i class="bi bi-trash3-fill"></i>
            </a>

          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>




  <table id="tabelDepartamento" class="table table-hover table-striped" style="--bs-table-bg: transparent !important;">
    <thead>
      <tr>
        <th scope="col">Nome Departamento</th>
        <th></th>
      </tr>


    </thead>

    <tbody class="mt-1">
      <?php
      foreach ($departamentosData as $departamento) {
        $token = encrypt_id($departamento['id'], $encryptionKey, $signatureKey); ?>

        <tr class="data-row" data-token="<?php echo $token; ?>">

          <th class="editable-cell-cargo-departamento departamento" data-field="nome"><?php echo $departamento['nome']; ?></th>

          <td class="text-center">
            <a href="#" class="ms-2 text-danger delete-button-departamento"  data-url="positionAndDepartment/controller/deleteDepartamento.php">
              <i class="bi bi-trash3-fill"></i>
            </a>

          </td>
        </tr>
      <?php } ?>
    </tbody>

   

  </table>
</div>







