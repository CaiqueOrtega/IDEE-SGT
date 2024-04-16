<?php require('./controller/list.php'); ?>

<div class="table-responsive">

    <table id="tabelaClass" class="table table-hover table-striped" style="--bs-table-bg: transparent !important;">
        <thead>
            <tr>
                <th scope="col">NR</th>
                <th scope="col">Turma</th>
                <th scope="col">Treinamento</th>
                <th scope="col">Empresa Solicitante</th>
                <th class="" scope="col">Cancelar</th>
                <th class="" scope="col">Mais</th>
            </tr>


        </thead>

        <?php
        if (empty($turmaData) && $idPermissao != 1 && $idPermissao != 4) { ?>

            <div id="alert" class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Solicte treinamentos</strong> para poder manipular os dados!.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

        <?php } else { ?>

            <tbody class="mt-1">
                <?php foreach ($turmaData as  $inscricao) {
                    $token = encrypt_id($inscricao['id_inscricao'], $encryptionKey, $signatureKey); ?>

                    <tr class="data-row" data-token="<?php echo $token ?>">
                        <th class="editable-cell" data-field="nome_funcionario"><?php echo $inscricao['nr']; ?></th>
                        <td class="editable-cell-nomenclatura" data-field="genero"><?php echo $inscricao['nomenclatura']; ?></td>
                        <td class="editable-cell" data-field="nome_funcionario"><?php echo $inscricao['razao_social']; ?></td>
                        <td class="editable-cell" data-field="registro"><?php echo date('d-m-Y', strtotime($inscricao['data_realizacao'])); ?></td>
                        <td>
                            <a href="#" class="ms-2 text-danger text-center" data-bs-toggle="modal" data-bs-target="#modalDeleteInscription">
                                <i class="bi bi-x-lg"></i>
                            </a>

                        </td>

                        <td class="text-end">

                            <a href="#" class="text-primary d-flex modalInscriptionInfo">
                                <i class="bi bi-eye"></i> <i class="bi bi-three-dots-vertical"></i>
                            </a>

                        </td>

                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
    </table>
</div>