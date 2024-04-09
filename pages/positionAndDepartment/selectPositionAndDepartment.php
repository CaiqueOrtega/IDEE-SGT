<?php require_once('./controller/list.php');
?>

<div class="col-md-6 mt-3" >
    <label class="form-label" for="cargo">Cargo</label>
    <select class="form-select" name="cargo" id="cargo" aria-label="Default select example">
        <option value="" selected>Selecione um cargo...</option>
        <?php foreach ($cargosData as $cargo) { 
              $tokenCargo = encrypt_id($cargo['id'], $encryptionKey, $signatureKey); ?>
            
            <option value="<?php echo $tokenCargo; ?>"><?php echo $cargo['nome']; ?></option>
        <?php } ?>
    </select>
</div>


<div class="col-md-6 mt-3">
    <label class="form-label" for="departamento">Departamento</label>
    <select class="form-select" name="departamento" id="departamento" aria-label="Default select example">
        <option value="" selected>Selecione um departamento...</option>
        <?php foreach ($departamentosData as $departamento) {
             $tokenDepartamento = encrypt_id($departamento['id'], $encryptionKey, $signatureKey); ?>
           
            <option value="<?php echo $tokenDepartamento; ?>"><?php echo $departamento['nome']; ?></option>
        <?php } ?>
    </select>
</div>

