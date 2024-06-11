<?php
require('./controller/listClass.php');
include '../../api/private/cript.php';
?>


<div class="table-responsive">
    <table id="tableClass" class="table table-hover table-striped" style="--bs-table-bg: transparent !important;">
        <thead>
            <tr>
                <th scope="col">Turma</th>
                <th scope="col">Treinamento</th>
                <th scope="col">Empresa</th>
                <th scope="col">Colaborador</th>
                <th scope="col">Carga Horaria</th>
                <th class="text-center">Cancelar Turma</th>
                <th class="text-end">Mais</th>
            </tr>


        </thead>

        <tbody class="mt-1">
            <?php
            foreach ($turmasData as $index => $turma) {
                $turmaId = $turma['turma_id'];
                $tokenTurma = encrypt_id($turma['turma_id'], $encryptionKey, $signatureKey);
        

                $alunosData = getAlunosData($id, $connection, $whereAluno, $turmaId);
                
            ?>

                <tr class="data-row" id="tableClassInfo" data-token="<?php echo $tokenTurma; ?>">

                    <th class="editable-cell" data-field="turma"><?php echo $turma['nome_turma']; ?></th>
                    <td class="editable-cell" data-field="treinamento_id"><?php echo $turma['nomenclatura']; ?></td>
                    <td class="editable-cell" data-field="empresa_aluno"><?php echo $turma['razao_social']; ?></td>


                    <?php if (isset($turma['nome_usuario'])) { ?>
                        <td class="editable-cell-colaborador" data-field="colaborador"><?php echo $turma['nome_usuario']; ?></td>
                    <?php } else { ?>
                        <td class="editable-cell-colaborador"></td>
                    <?php } ?>

                    <td class="editable-cell" data-field="treinamento_id"><?php echo sprintf('%02d:00:00', $turma['carga_horaria']); ?></td>



                    <td class="text-center">

                        <a href="#" class="ms-2 text-danger text-center" data-bs-toggle="modal" data-bs-target="#modalDeleteClass">
                            <i class="bi bi-x-lg"></i>
                        </a>

                    </td>

                    <td class="text-end">

                        <a href="#" class="text-primary d-flex modalClassInfo1 float-end" data-bs-toggle="modal" data-token="<?php echo $tokenTurma; ?>" data-bs-target="#modalClassInfo-<?php echo $turma['turma_id']; ?>" data-index="<?php echo $index; ?>">
                            <i class="bi bi-eye"></i> <i class="bi bi-three-dots-vertical"></i>
                        </a>



                    </td>

                    <td>

                        <?php require('./modalClass.php'); ?>
                    </td>


                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>