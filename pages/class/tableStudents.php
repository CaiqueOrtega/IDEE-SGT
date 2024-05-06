
   <div class="table-responsive">
    <table id="tableClass" class="table table-hover table-striped" style="--bs-table-bg: transparent !important;">
            <thead>
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">Documento</th>
                    <th scope="col">Gênero</th>
                    <th scope="col">Registro</th>
                    <th scope="col">Remover</th>
                </tr>


            </thead>

            <tbody class="mt-1">
                <?php
                foreach ($alunosData as $aluno) {
                    $tokenStudent = encrypt_id($aluno['id'], $encryptionKey, $signatureKey);

                ?>

                    <tr class="data-row" data-tokenemployee="<?php echo $tokenStudent; ?>" data-tokenInscription="<?php echo $tokenInscricao; ?>">
                        <th class="editable-cell" data-field="nome_funcionario"><?php echo $aluno['nome_funcionario']; ?></th>
                        <th class="editable-cell" data-field="cpf"><?php echo $aluno['cpf']; ?></th>
                        <td class="editable-cell" data-field="genero"><?php echo $aluno['genero']; ?></td>
                        <td class="editable-cell" data-field="registro"><?php echo $aluno['numero_registro_empresa']; ?></td>
                        <td class="text-center">

                            <a href="#" class="ms-2 text-danger text-center openRemoveEmployeeModalBtn">
                                <i class="bi bi-x-lg"></i>
                            </a>

                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>


