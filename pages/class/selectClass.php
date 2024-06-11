<?php 

require_once('./controller/listClass.php');
include('../../api/private/cript.php');



 ?>






<div class="col-md-6 mt-3" >
    <label class="form-label" for="colaborador">Colaborador</label>
    <select class="form-select" name="colaborador" id="colaborador" aria-label="Default select example">
        <option value="" selected>Selecione um colaborador......</option>
        <?php foreach ($turmasData as $turma) { 
            
            $tokenTurma = encrypt_id($turma['turma_id'], $encryptionKey, $signatureKey); ?>
            
            <option value="<?php echo $tokenTurma; ?>"><?php echo $turma['nome_usuario']; ?></option>
        <?php } ?>
    </select>
</div>

