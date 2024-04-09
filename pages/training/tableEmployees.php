<?php require('./controller/listEmployee.php');?>

<div class="table-responsive">

    <table id="tabelaEmployees" class="table table-hover table-striped" style="--bs-table-bg: transparent !important;">
        <thead>
            <tr>
                <th></th>
                <th scope="col">Nome</th>
                <th class="text-center" scope="col">Gênero</th>
                <th scope="col">Cargo</th>
                <th scope="col">Departamento</th>
                <th class="text-center" scope="col">Registro</th>

            </tr>


        </thead>

        <tbody class="mt-1">
            <?php
                foreach ($funcionariosData as $funcionario) {

                    $tokenEmployee = encrypt_id($funcionario['id'], $encryptionKey, $signatureKey);

            ?>

                    <tr class="data-row">
                        <th> <input class="form-check-input" type="checkbox" data-token="<?php echo $tokenEmployee; ?>" id="flexCheckDefault"></th>
                        <th class="editable-cell" data-field="nome_funcionario"><?php echo $funcionario['nome_funcionario']; ?></th>
                        <td class="editable-cell text-center" data-field="genero"><?php echo $funcionario['genero']; ?></td>
                        <td class="editable-cell" data-field="cargo"><?php echo $funcionario['nome_cargo']; ?></td>
                        <td class="editable-cell" data-field="departamento"><?php echo $funcionario['nome_departamento']; ?></td>
                        <td class="editable-cell text-center" data-field="registro"><?php echo $funcionario['numero_registro_empresa']; ?></td>


                    </tr>
            <?php } ?>
            
        </tbody>
    </table>
</div>