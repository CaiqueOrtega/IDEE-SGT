<?php
require('./controller/list.php');
include '../../api/private/cript.php';
?>



<div class="table-responsive">

    <table id="tabelaEmployees" class="table table-hover table-striped" style="--bs-table-bg: transparent !important;">
        <thead>
            <tr>
                <th scope="col">Nome</th>
                <th scope="col">E-mail</th>
                <th scope="col">Telefone</th>
                <th scope="col">Documento</th>
                <th scope="col">Gênero</th>
                <th scope="col">Cargo</th>
                <th scope="col">Departamento</th>
                <th scope="col">Registro</th>
                <th scope="col">Empresa</th>
                <?php if ($idPermissao == 1 || $idPermissao == 4) { ?>
                    <th scope="col">Usuário</th>

                <?php } ?>
                <th class="text-center">Acão</th>

            </tr>

        </thead>


        <?php if (empty($empresasData) && ($idPermissao != 1 && $idPermissao != 4)) : ?>
            <div id="alert" class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Cadastre primeiramente empresas cargos e departamentos</strong> para poder manipular dados de funcionários!.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php else : ?>


            <tbody class="mt-1">



                <?php
                foreach ($funcionariosData as $funcionario) {

                    $token = encrypt_id($funcionario['id'], $encryptionKey, $signatureKey);
                    $tokenEmpresa =  encrypt_id($funcionario['empresa_id'], $encryptionKey, $signatureKey);
                ?>

                    <tr class="data-row" data-token="<?php echo $token; ?>" data-token-empresa="<?php echo $tokenEmpresa; ?>">

                        <th class="editable-cell" data-field="nome_funcionario"><?php echo $funcionario['nome_funcionario']; ?></th>
                        <td class="editable-cell" data-field="email"><?php echo $funcionario['email']; ?></td>
                        <td class="editable-cell" data-field="telefone"><?php echo $funcionario['telefone']; ?></td>
                        <td class="editable-cell" data-field="cpf"><?php echo $funcionario['cpf']; ?></td>
                        <td class="editable-cell" data-field="genero"><?php echo $funcionario['genero']; ?></td>

                        <?php if (isset($funcionario['cargo_nome'])) { ?>
                            <td class="editable-cell-cargo" data-field="cargo_id"><?php echo $funcionario['cargo_nome']; ?></td>
                        <?php } else { ?>
                            <td class="editable-cell-cargo"></td>
                        <?php } ?>

                        <?php if (isset($funcionario['departamento_nome'])) { ?>
                            <td class="editable-cell-departamento" data-field="departamento_id"><?php echo $funcionario['departamento_nome']; ?></td>
                        <?php } else { ?>
                            <td class="editable-cell-departamento"></td>
                        <?php } ?>
                        <td class="editable-cell" data-field="registro"><?php echo $funcionario['numero_registro_empresa']; ?></td>
                        <td class="editable-cell-empresa" data-field="empresa_id"><?php echo $funcionario['razao_social']; ?></td>

                        <?php if ($idPermissao == 1 || $idPermissao == 4) { ?>
                            <td class="editable-cell-usuario" data-field="usuario"><?php echo $funcionario['nome_usuario']; ?></td>
                        <?php } ?>

                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="#" class="text-danger text-center" data-bs-toggle="modal" data-bs-target="#modalDeleteEmployee">
                                    <i class="bi bi-trash3-fill"></i>
                                </a>
                            </div>
                        </td>

                    </tr>

                <?php } ?>
            </tbody>

        <?php endif; ?>
    </table>
</div>