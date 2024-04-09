<?php include('./controller/list.php'); ?>


<div class="table-responsive">
    <table id="tabelaTraining" class="table table-hover table-striped" style="--bs-table-bg: transparent !important;">
        <thead>
            <tr>
                <th scope="col">NR</th>
                <th scope="col">Nomenclatura</th>
                <th scope="col">Tempo de Reciclagem</th>
                <th scope="col">Carga Horaria</th>
                <th scope="col">Horas Pratica</th>
                <th scope="col">Horas Teoricas</th>
                <th class="text-center">Excluir</th>
                <th class="text-end">Mais</th>
            </tr>


        </thead>

        <tbody class="mt-1">
            <?php
            foreach ($treinamentos as $treinamento) {

                $tokenTreinamento = encrypt_id($treinamento['id'], $encryptionKey, $signatureKey);

            ?>

                <tr class="data-row" data-token="<?php echo $tokenTreinamento ?>">
                    <th class="editable-cell" data-field="nr"><?php echo $treinamento['nr']; ?></th>
                    <td class="editable-cell" data-field="nomenclatura"><?php echo $treinamento['nomenclatura']; ?></td>
                    <td class="editable-cell" data-field="reciclagem"><?php echo $treinamento['reciclagem']; ?></td>
                    <td class="editable-cell" data-field="carga_horaria"><?php echo $treinamento['carga_horaria']; ?></td>
                    <td class="editable-cell" data-field="horas_pratica"><?php echo $treinamento['horas_pratica']; ?></td>
                    <td class="editable-cell" data-field="horas_teorica"><?php echo $treinamento['horas_teorica']; ?></td>





                    <td class="text-center">

                        <a href="#" class="ms-2 text-danger text-center openModalDeleteTraining">
                            <i class="bi bi-trash3-fill"></i>
                        </a>

                    </td>

                    <td class="text-center">

                        <a href="#" class="ms-2 text-primary d-flex text-center openUpdateTraining">
                        <i class="bi bi-pen"></i> <i class="bi bi-eye"></i> 

                        </a>

                    </td>


                </tr>
            <?php } ?>

        </tbody>
    </table>
</div>