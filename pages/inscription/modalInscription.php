<?php include('./controller/listInscription.php'); ?>

<div class="modal fade" id="modalInscriptionInfo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #58af9b; color:white;">
                <h1 class="modal-title fs-5" id="staticBackdropLabel"><i class="me-2 fas fa-users"></i>Informações da Solicitação de Inscriçao</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">


                <div class="alert alertSucesso">

                </div>

                <div class="alert alertErro">

                </div>

                <div class="row card mt-4 py-2  mx-1 rounded-1" style='margin-bottom: -10px;'>

                    <div class="col-md-12 d-flex flex-row justify-content-between align-items-center">
                        <h5>Treinamento Solicitado</h5>
                        <a href="#" id="btn-info" class="btn btn-colapse" data-bs-toggle="collapse" data-bs-target="#collapseExample1" aria-expanded="false" aria-controls="collapseExample">
                            <i class="icon bi bi-chevron-right fs-3" style="color:#58af9b;"></i>
                        </a>
                    </div>

                </div>

                <div class="collapse show" id="collapseExample1">
                    <div class="card card-body border-top-0 rounded-0 mx-1">

                        <h6 class="mt-2">Nomenclatura:</h6><span id="objetivo"><?php echo $inscricao['nomenclatura']; ?></span>

                        <h6 class="mt-2">Objetivo:</h6><span id="objetivo"><?php echo $inscricao['objetivo']; ?></span>

                        <h6 class="mt-2">Pré-requisitos:</h6><span id="preRequisitos"><?php echo $inscricao['pre_requisitos']; ?></span>

                        <h6 class="mt-2">Carga Horária</h6><span id="cargaHoraria"><?php echo $inscricao['carga_horaria']; ?></span>

                        <h6 class="mt-2">Horas-prática</h6><span id="horasPratica"> <?php echo $inscricao['horas_pratica']; ?></span>

                        <h6 class="mt-2">Horas-teórica</h6><span id="horas-teorica"><?php echo $inscricao['horas_teorica']; ?></span>
                    </div>
                </div>


                <div class="row mt-4 card py-2  mx-1 rounded-1" style='margin-bottom: -10px;'>

                    <div class="col-md-12 d-flex flex-row justify-content-between align-items-center">
                        <h5>Empresa Solicitante</h5>
                        <a href="#" id="btn-info" class="btn btn-colapse" data-bs-toggle="collapse" data-bs-target="#collapseExample2" aria-expanded="false" aria-controls="collapseExample">
                            <i class="icon bi bi-chevron-right fs-3" style="color:#58af9b;"></i>
                        </a>
                    </div>

                </div>

                <div class="collapse " id="collapseExample2">
                    <div class="card card-body border-top-0 rounded-0 mx-1">
                        <p id="horas-teorica">
                            <span class="fw-semibold">Razao Social:</span> <?php echo $inscricao['razao_social']; ?>
                        </p>

                        <p id="horas-teorica">
                            <span class="fw-semibold">CNPJ:</span> <?php echo $inscricao['cnpj']; ?>
                        </p>

                        <p id="horas-teorica">
                            <span class="fw-semibold">Data Solicitação:</span> <?php echo $inscricao['data_realizacao']; ?>
                        </p>

                    </div>
                </div>


                <div class="row mt-4 card py-2 mx-1 rounded-1" style='margin-bottom: -10px;'>

                    <div class="col-md-12 d-flex flex-row justify-content-between align-items-center">
                        <h5>Funcionários Inscritos</h5>
                        <a href="#" id="btn-info" class="btn btn-colapse" data-bs-toggle="collapse" data-bs-target="#collapseExample3" aria-expanded="false" aria-controls="collapseExample">
                            <i class="icon bi bi-chevron-right fs-3" style="color:#58af9b;"></i>
                        </a>
                    </div>

                </div>

                <?php $tokenTraining = encrypt_id($inscricao['treinamento_Id'], $encryptionKey, $signatureKey);
                $tokenCompany = encrypt_id($inscricao['empresa_id'], $encryptionKey, $signatureKey);
                $tokenInscricao =  encrypt_id($inscricao['inscricao_id'], $encryptionKey, $signatureKey);
                ?>


                <div class="collapse " id="collapseExample3">
                    <div class="card card-body border-top-0 rounded-0 mx-1">

                        <button class="btn btn-outline-primary me-2" data-token="<?php echo $tokenCompany; ?>" data-tokenTraining="<?php echo $tokenTraining; ?>" id="btnOpenModalUpdateInscriptionEmployees"><i class="bi bi-person-fill-add me-1"></i> Adicionar</button>

                        <div class="table-responsive">

                            <table id="tabelaEmployees" class="table table-hover table-striped" style="--bs-table-bg: transparent !important;">
                                <thead>
                                    <tr>
                                        <th scope="col">Nome</th>
                                        <th scope="col">Documento</th>
                                        <th scope="col">Gênero</th>
                                        <th scope="col">Cargo</th>
                                        <th scope="col">Departamento</th>
                                        <th scope="col">Registro</th>
                                        <th scope="col">Remover</th>
                                    </tr>


                                </thead>

                                <tbody class="mt-1">
                                    <?php
                                    foreach ($funcionariosData as $funcionario) {
                                        $tokenEmployee = encrypt_id($funcionario['id'], $encryptionKey, $signatureKey);

                                        $positionAndDepartment = getPositionDepartment($connection, $funcionario['cargo_id'], $funcionario['departamento_id']);
                                    ?>

                                        <tr class="data-row" data-tokenemployee="<?php echo $tokenEmployee; ?>" data-tokenInscription="<?php echo $tokenInscricao; ?>">
                                            <th class="editable-cell" data-field="nome_funcionario"><?php echo $funcionario['nome_funcionario']; ?></th>
                                            <th class="editable-cell" data-field="cpf"><?php echo $funcionario['cpf']; ?></th>
                                            <td class="editable-cell" data-field="genero"><?php echo $funcionario['genero']; ?></td>
                                            <th class="editable-cell" data-field="cargo"><?php echo $positionAndDepartment['nome_cargo']; ?></th>
                                            <td class="editable-cell" data-field="departamento"><?php echo $positionAndDepartment['nome_departamento']; ?></td>
                                            <td class="editable-cell" data-field="registro"><?php echo $funcionario['numero_registro_empresa']; ?></td>
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
                </div>

                <div class="modal-footer d-flex justify-content-end mt-5">
                    <button type="submit" class="btn btn-login" id="confirmarInsertCompanyBtn">Confirmar</button>


                    <button type="submit" class="btn btn-login" id="confirmarInsertClassBtn" data-token="<?php echo $tokenInscricao; ?>">Cadastrar Turma</button>



                    <script>
                        $(document).ready(function() {
                            $('#confirmarInsertCompanyBtn').on('click', function() {
                                $('#modalInscriptionInfo').modal('hide');
                            });
                        });

                        $(document).ready(function() {
                            $('#confirmarInsertClassBtn').click(function() {
                                var tokenFicha = $('#confirmarInsertClassBtn').data("token");

                                $.ajax({
                                    url: 'class/controller/createClass.php',
                                    method: 'POST',
                                    dataType: 'json',
                                    data: {
                                        tokenFicha: tokenFicha
                                    },
                                    success: function(response) {
                                       
                                    },
                                    error: function(xhr, status, error) {
                                        console.error(xhr.responseText);
                                    }
                                });
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>



<div data-tokeninscription="<?php echo $tokenInscricao; ?>" data-tokenTraining="<?php echo $tokenTraining; ?>" data-token="<?php echo $tokenCompany; ?>" class="modal fade" id="modalMoreUpdateInscriptionTraining" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content ">
            <div class="modal-header" style="background-color: #58af9b; color:white;">
                <h4 id="treinamentoNome">Funcionários</h4>


                <button type="button" id="btnReturnModalInscriptionInfo" class="btn btn-outline-light border-0" data-bs-toggle="modal" data-bs-target="#modalInscriptionInfo"><i class="bi bi-arrow-left-square fs-4 "></i></button>
            </div>
            <div class="modal-body">


                <div class="container">
                    <div class="row py-3 bg-light shadow border rounded-2 ">
                        <div class="col d-flex justify-content-between">
                            <div class="col-md-5">

                                <h5><?php echo $inscricao['razao_social']; ?></h5>


                            </div>

                            <form class="d-none d-md-flex formSearch  input-group w-auto my-auto">

                                <input autocomplete="on" type="search" class="form-control py-2" id="searchInput" placeholder='Digite o que deseja pesquisar...' style="min-width: 300px" />
                                <span class="input-group-text text-white" style="background-color: #59af9b;"><i class="bi bi-search"></i></span>
                            </form>

                        </div>
                    </div>
                </div>

                <div id="table-employee"></div>




            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-login" id="updateInscricao" data-token="">Adicionar</button>
            </div>
        </div>
    </div>
</div>



<div class="modal" id="modalRemoveEmployee" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirmar exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Você realmente deseja excluir o funcionario(a) <span id="employeeName"></span>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-danger" id="confirmRemoveEmployeeBtn">Confirmar</button>
            </div>
        </div>

    </div>
</div>

<script>
    var buttons = document.querySelectorAll('.btn-colapse');

    // Itera sobre cada botão
    buttons.forEach(function(button) {
        // Adiciona um ouvinte de evento de clique a cada botão
        button.addEventListener('click', function() {
            var btn = this;
            var icon = btn.querySelector('.icon');

            // Desabilita o botão
            btn.disabled = true;

            // Verifica se as informações estão abertas ou fechadas
            var isExpanded = btn.getAttribute('aria-expanded') === 'true';

            // Muda a seta dependendo do estado das informações
            if (isExpanded) {
                icon.classList.remove('bi-chevron-right');
                icon.classList.add('bi-chevron-down');
            } else {
                icon.classList.remove('bi-chevron-down');
                icon.classList.add('bi-chevron-right');
            }

            // Aguarda 1 segundo para simular a animação
            setTimeout(function() {
                // Reabilita o botão após a animação
                btn.disabled = false;
            }, 1000);
        });
    });
</script>