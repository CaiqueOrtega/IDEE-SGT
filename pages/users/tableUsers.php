<?php
require('./controller/list.php');

?>


<div class="table-responsive">
    <table id="tableCompanys" class="table table-hover table-striped" style="--bs-table-bg: transparent !important;">
        <thead>
            <tr>
                <th scope="col">Nome</th>
                <th scope="col">Email</th>
                <th scope="col">Data de Nascimento</th>
                <th scope="col">CPF</th>
                <th scope="col">Telefone</th>
                <th class="text-center" scope="col">Permissões</th>
            </tr>


        </thead>

        <tbody class="mt-1">
            <?php
            foreach ($usuariosData as $usuario) {
                $token = encrypt_id($usuario['id'], $encryptionKey, $signatureKey); ?>

                <tr class="data-row" data-token="<?php echo $token; ?>">

                    <th class="editable-cell" data-field="nome"><?php echo $usuario['nome']; ?></th>
                    <td class="editable-cell" data-field="email"><?php echo $usuario['email']; ?></td>
                    <td class="editable-cell" data-field="data_nacsimento"><?php echo $usuario['data_nascimento']; ?></td>
                    <td class="editable-cell" data-field="cpf"><?php echo $usuario['cpf']; ?></td>
                    <td class="editable-cell" data-field="telefone"><?php echo $usuario['telefone']; ?></td>




                    <td class="text-center">

                        <a href="#" class="text-primary d-flex justify-content-center openModalPermissao">
                            <i class="bi bi-eye"></i> <i class="bi bi-three-dots-vertical"></i>
                        </a>

                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
