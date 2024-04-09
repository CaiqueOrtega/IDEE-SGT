<?php
require('./controller/list.php');
include '../../api/private/cript.php';
?>


<div class="table-responsive">
    <table id="tableCompanys" class="table table-hover table-striped" style="--bs-table-bg: transparent !important;">
        <thead>
            <tr>
                <th scope="col">Razao Social</th>
                <th scope="col">Nome Fantasia</th>
                <th scope="col">Email</th>
                <th scope="col">CNPJ</th>
                <th scope="col">Telefone</th>
                <?php if ($idPermissao == 1 || $idPermissao == 4) { ?>
                    <th scope="col">Usuário</th>

                <?php } ?>
                <th class="text-center">Excluir</th>
                <th class="text-end">Mais</th>
            </tr>


        </thead>

        <tbody class="mt-1">
            <?php
            foreach ($empresasData as $empresa) {
                $token = encrypt_id($empresa['id'], $encryptionKey, $signatureKey); ?>

                <tr class="data-row" data-token="<?php echo $token; ?>">

                    <th class="editable-cell" data-field="razao_social"><?php echo $empresa['razao_social']; ?></th>
                    <td class="editable-cell" data-field="nome_fantasia"><?php echo $empresa['nome_fantasia']; ?></td>
                    <td class="editable-cell" data-field="email"><?php echo $empresa['email']; ?></td>
                    <td class="editable-cell" data-field="cnpj"><?php echo $empresa['cnpj']; ?></td>
                    <td class="editable-cell" data-field="telefone"><?php echo $empresa['telefone']; ?></td>
                    <?php if ($idPermissao == 1 || $idPermissao == 4) { ?>
                            <td class="editable-cell" data-field="registro"><?php echo $empresa['nome_usuario']; ?></td>
                        <?php } ?>
                    
                    <td class="text-center">
                        
                        <a href="#" class="ms-2 text-danger text-center" data-bs-toggle="modal" data-bs-target="#modalDeleteCompany">
                            <i class="bi bi-trash3-fill"></i>
                        </a>

                    </td>

                    <td class="text-end">
        
                        <a href="#" class="text-primary d-flex modalCompanyInfo"  >
                        <i class="bi bi-eye"></i> <i class="bi bi-three-dots-vertical"></i>
                        </a>
        
                    </td>
                </tr>
                <?php } ?>
            </tbody>
    </table>
</div>


