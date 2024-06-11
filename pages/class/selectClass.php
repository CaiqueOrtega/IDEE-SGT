<?php
require_once('./controller/listClass.php');



$colaboradorId = $_POST['colaborador_id'];


$colaradores =  obterColaborador($connection, $colaboradorId)


?>

    <select class="form-select"  name="colaborador" id="colaborador_selected" aria-label="Default select example">
        <option value="" selected>Selecione um colaborador......</option>
        <option value="" disabled><?php echo $_POST['colaborador_nome']  ?></option>
        <?php foreach ($colaradores as $colaborador) { ?>


            <option value="<?php echo $colaborador['login_id']; ?>"><?php echo $colaborador['nome']; ?></option>
        <?php } ?>
    </select>
