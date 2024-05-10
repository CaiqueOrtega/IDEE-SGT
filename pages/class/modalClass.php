<div class="modal fade" id="modalClassInfo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #58af9b; color:white;">
                <h1 class="modal-title fs-5" id="staticBackdropLabel"><i class="me-2 fas fa-users"></i>Informações da Turma</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row card mt-4 py-2  mx-1 rounded-1" style='margin-bottom: -10px;'>

                    <div class="col-md-12 d-flex flex-row justify-content-between align-items-center">
                        <h5>Turma</h5>
                        <a href="#" class="btn btn-info-class" data-bs-toggle="collapse" data-bs-target="#collapseExample1" aria-expanded="false" aria-controls="collapseExample">
                            <i class="icon bi bi-chevron-right fs-3" style="color:#58af9b;"></i>
                        </a>

                    </div>

                </div>


                <div class="collapse " id="collapseExample1">
                    <div class="card card-body border-top-0 rounded-0 mx-1">
                        <p id="turma">
                            <span class="fw-semibold">Turma:</span> <?php echo $turma['nome_turma']; ?>
                        </p>
                        <p id="treinamento">
                            <span class="fw-semibold">Treinamento:</span> <?php echo $turma['nomenclatura']; ?>
                        </p>
                        <p id="coordenador">
                            <span class="fw-semibold">Coordenador:</span> <?php echo $turma['nome_usuario']; ?>
                        </p>
                        <p id="objetivo">
                            <span class="fw-semibold">Objetivo:</span> <?php echo $turma['objetivo']; ?>
                        </p>
                        <p id="cargaHoraria">
                            <span class="fw-semibold">Carga Horária:</span> <?php echo $turma['carga_horaria']; ?>
                        </p>
                        <p id="horasPratica">
                            <span class="fw-semibold">Horas Prática:</span> <?php echo $turma['horas_pratica']; ?>
                        </p>
                        <p id="horasTeorica">
                            <span class="fw-semibold">Horas Teórica:</span> <?php echo $turma['horas_teorica']; ?>
                        </p>
                    </div>
                </div>



                <div class="row card mt-4 py-2  mx-1 rounded-1" style='margin-bottom: -10px;'>

                    <div class="col-md-12 d-flex flex-row justify-content-between align-items-center">
                        <h5>Empresa</h5>
                        <a href="#" id="btn-info-company" class="btn btn-info-class " data-bs-toggle="collapse" data-bs-target="#collapseExample2" aria-expanded="false" aria-controls="collapseExample">
                            <i class="icon bi bi-chevron-right fs-3" style="color:#58af9b;"></i>
                        </a>
                    </div>

                </div>

                <div class="collapse" id="collapseExample2">
                    <div class="card card-body border-top-0 rounded-0 mx-1">

                        <p id="empresa">
                            <span class="fw-semibold">Empresa:</span> <?php echo $turma['nome_fantasia']; ?>
                        </p>
                        <p id="cnpj">
                            <span class="fw-semibold">CNPJ:</span> <?php echo $turma['cnpj']; ?>
                        </p>



                    </div>
                </div>


                <div class="row card mt-4 py-2  mx-1 rounded-1" style='margin-bottom: -10px;'>

                    <div class="col-md-12 d-flex flex-row justify-content-between align-items-center">
                        <h5>Alunos</h5>
                        <a href="#" class="btn btn-info-class" data-bs-toggle="collapse" data-bs-target="#collapseExample3" aria-expanded="false" aria-controls="collapseExample">
                            <i class="icon bi bi-chevron-right fs-3" style="color:#58af9b;"></i>
                        </a>

                    </div>

                </div>

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
                                        <th scope="col">Registro</th>
                                        <th scope="col">Nota</th>
                                        <th scope="col">Frequencia</th>
                                    </tr>


                                </thead>

                                <tbody class="mt-1">
                                    <?php
                                    foreach ($alunosData as $aluno) {
                                        $tokenAluno = encrypt_id($aluno['id'], $encryptionKey, $signatureKey);


                                    ?>

                                        <tr class="data-row" data-tokenemployee="<?php echo $tokenAluno; ?>">
                                            <th class="editable-cell" data-field="nome_funcionario"><?php echo $aluno['nome_funcionario']; ?></th>
                                            <th class="editable-cell" data-field="cpf"><?php echo $aluno['cpf']; ?></th>
                                            <td class="editable-cell" data-field="genero"><?php echo $aluno['genero']; ?></td>
                                            <td class="editable-cell" data-field="registro"><?php echo $aluno['numero_registro_empresa']; ?></td>
                                            <td class="editable-cell" data-field="registro"><?php echo $aluno['nota']; ?></td>
                                            <td class="editable-cell" data-field="registro"><?php echo $aluno['frequencia']; ?></td>
                                            <td class="text-center">



                                                <a href="#" class="ms-2 text-danger text-center" data-bs-toggle="modal" data-bs-target="#modalDeleteClass">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </a>

                                            </td>

                                            <td class="text-end">

                                                <a href="#" class="text-primary d-flex modalClassInfo1 float-end" data-bs-toggle="modal" data-token="<?php echo $token; ?>" data-bs-target="#modalClassInfo">
                                                    <i class="bi bi-eye"></i> <i class="bi bi-three-dots-vertical"></i>
                                                </a>

                                                <script>
                                                    // Defina uma variável JavaScript com os dados PHP
                                                    var turmasData = <?php echo json_encode($turmasData); ?>;
                                                </script>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>


                        </div>





                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-login">Confirmar</button>

                </div>

            </div>



            <script>
                var buttons = document.querySelectorAll('.btn');

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