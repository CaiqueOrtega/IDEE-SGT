<div class="modal fade" id="modalClassInfo-<?php echo $turma['turma_id']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #58af9b; color:white;">
                <h1 class="modal-title fs-5" id="staticBackdropLabel"><i class="me-2 fas fa-users"></i>Informações da Turma</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row card mt-4 py-2  mx-1 rounded-1" style='margin-bottom: -10px;'>
                    <div class="col-md-12 d-flex flex-row justify-content-between align-items-center">
                        <h5>Turma</h5>
                        <a href="#" class="btn btn-info-class" data-bs-toggle="collapse" data-bs-target="#collapseExample1" aria-expanded="false" aria-controls="collapseExample1">
                            <i class="icon bi bi-chevron-right fs-3" style="color:#58af9b;"></i>
                        </a>
                    </div>
                </div>
                <div class="collapse show" id="collapseExample1">
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
                <div class="row card mt-4 py-2 mx-1 rounded-1" style='margin-bottom: -10px;'>
                    <div class="col-md-12 d-flex flex-row justify-content-between align-items-center">
                        <h5>Empresa</h5>
                        <a href="#" id="btn-info-company" class="btn btn-info-class" data-bs-toggle="collapse" data-bs-target="#collapseExample2" aria-expanded="false" aria-controls="collapseExample2">
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
                <div class="row card mt-4 py-2 mx-1 rounded-1" style='margin-bottom: -10px;'>
                    <div class="col-md-12 d-flex flex-row justify-content-between align-items-center">
                        <h5>Gerenciar Alunos</h5>
                        <a href="#" class="btn btn-info-class" data-bs-toggle="modal" data-bs-target="#modalStudents-<?php echo $turma['turma_id']; ?>">
                            <i class="icon bi bi-people fs-3" style="color:#58af9b;"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="modal-footer mt-3">
                <button type="button" class="btn btn-login">Confirmar</button>
            </div>
        </div>
    </div>
</div>


<!-- Fullscreen Modal -->
<div class="modal fade" id="modalStudents-<?php echo $turma['turma_id']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="studentsModalLabel" aria-hidden="false">
    <div class="modal-dialog modal-dialog-scrollable modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #58af9b; color: white;">
                <h1 class="modal-title fs-5" id="studentsModalLabel"><i class="me-2 fas fa-users"></i>Alunos da Turma</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body " style="background-color: #f0f2f5 ;">
                <div class="container card border shadow">
                    <div class="row py-3 bg-light  border rounded-top align-items-center">
                        <div class="col d-flex align-items-center">
                            <h3 class="text-start mb-0">Alunos</h3>
                        </div>
                        <div class="col d-flex justify-content-end align-items-center">
                            <!-- Botão para Modal de Notas -->
                            <button class="btn btn-outline-success me-2 d-flex align-items-center py-2" id="openNotas-<?php echo $turma['turma_id']; ?>">
                                <i class="bi bi-clipboard2-check me-1"></i><span class="d-none d-md-block">Notas</span>
                            </button>
                            <!-- Botão para Modal de Frequência -->
                            <button class="btn btn-outline-primary d-flex align-items-center py-2" id="openFrequencia-<?php echo $turma['turma_id']; ?>">
                                <i class="bi bi-calendar2-date me-1"></i><span class="d-none d-md-block">Frequência</span>
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabelaStudents" class="table table-hover table-striped" style="--bs-table-bg: transparent !important;">
                                <thead>
                                    <tr>
                                        <th scope="col">Registro</th>
                                        <th scope="col">Nome</th>
                                        <th scope="col">Documento</th>
                                        <th scope="col">Gênero</th>
                                        <th scope="col">Remover Aluno</th>
                                    </tr>
                                </thead>
                                <tbody class="mt-1">
                                    <?php foreach ($alunosData as $index => $aluno) {
                                        $tokenAluno = encrypt_id($aluno['aluno_id'], $encryptionKey, $signatureKey);
                                    ?>
                                        <tr class="data-row" data-index="<?php echo $index; ?>">
                                            <th data-field="registro" class="text-right"> <?php echo $aluno['numero_registro_empresa']; ?></th>
                                            <td data-field="nome_funcionario"><?php echo $aluno['nome_funcionario']; ?></td>
                                            <th data-field="cpf"><?php echo $aluno['cpf']; ?></th>
                                            <td data-field="genero"><?php echo $aluno['genero']; ?></td>


                                            <td class="text-right">
                                                <a href="#" class="ms-2 text-danger text-end/" data-bs-toggle="modal" data-bs-target="#modalDeleteStudents">
                                                    <i class="bi bi-trash3-fill fs-6"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Notas -->
<div class="modal fade" id="modalNotas-<?php echo $turma['turma_id']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalNotasLabel-<?php echo $turma['turma_id']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #58af9b; color: white;">
                <h1 class="modal-title fs-5" id="modalNotasLabel"><i class="me-2 fas fa-users"></i>Informações da Turma - Notas</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabelaStudents" class="table table-hover table-striped" style="--bs-table-bg: transparent !important;">
                            <thead>
                                <tr>
                                    <th scope="col">Registro</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Nota Pratica</th>
                                    <th scope="col">Nota Teorica</th>
                                    <th scope="col">Media</th>
                                </tr>
                            </thead>
                            <tbody class="mt-1">
                                <?php foreach ($alunosData as $index => $aluno) {
                                    $tokenAluno = encrypt_id($aluno['aluno_id'], $encryptionKey, $signatureKey);
                                ?>
                                    <tr class="data-row" data-index="<?php echo $index; ?>">
                                        <th data-field="registro" class="text-right"> <?php echo $aluno['numero_registro_empresa']; ?></th>
                                        <td data-field="nome_funcionario"><?php echo $aluno['nome_funcionario']; ?></td>
                                        <th class="editable-cell" data-field="nota_pratica"><?php echo $aluno['nota_pratica'] ?? '0'; ?></th>
                                        <th class="editable-cell" data-field="nota_teorica"><?php echo $aluno['nota_teorica'] ?? '0'; ?></th>
                                        <th class="editable-cell" data-field="nota_media"><?php echo $aluno['nota_media'] ?? '0'; ?></th>

                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
            <div class="modal-footer mt-3">
                <button type="button" class="btn btn-login" data-bs-dismiss="modal">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Frequência -->
<div class="modal fade" id="modalFrequencia-<?php echo $turma['turma_id']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalFrequenciaLabel-<?php echo $turma['turma_id']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #58af9b; color: white;">
                <h1 class="modal-title fs-5" id="modalFrequenciaLabel"><i class="me-2 fas fa-users"></i>Informações da Turma - Frequência</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabelaStudents" class="table table-hover table-striped" style="--bs-table-bg: transparent !important;">
                            <thead>
                                <tr>
                                    <th scope="col">Registro</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Frequencia</th>
                                    <?php
                                    $cargaHorariaTotal = isset($turma['carga_horaria']) ? $turma['carga_horaria'] : 0;

                                    // Verificar se $cargaHorariaTotal é numérico
                                    if (!is_numeric($cargaHorariaTotal)) {
                                        $cargaHorariaTotal = 0;
                                    }

                                    // Número de horas consideradas por dia
                                    $horasPorDia = 4;

                                    // Calcular o número de dias
                                    $dias = ceil($cargaHorariaTotal / $horasPorDia);

                                    for ($i = 1; $i <= $dias; $i++) { ?>
                                        <th scope="col">Dia <?php echo $i; ?></th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($alunosData as $index => $aluno) { ?>
                                    <tr class="data-row" data-index="<?php echo $index; ?>">
                                        <th data-field="registro" class="text-right"><?php echo $aluno['numero_registro_empresa']; ?></th>
                                        <td data-field="nome_funcionario"><?php echo $aluno['nome_funcionario']; ?></td>
                                        <td data-field="frequencia">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="frequencia-<?php echo $aluno['aluno_id']; ?>" name="frequencia[]" value="<?php echo $aluno['aluno_id']; ?>" checked>
                                                <label class="form-check-label" for="frequencia-<?php echo $aluno['aluno_id']; ?>"></label>
                                            </div>
                                        </td>
                                        <?php for ($i = 1; $i <= $dias; $i++) { ?>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="frequencia-<?php echo $aluno['aluno_id']; ?>-dia-<?php echo $i; ?>" name="frequencia[<?php echo $aluno['aluno_id']; ?>][]" value="dia-<?php echo $i; ?>" checked>
                                                    <label class="form-check-label" for="frequencia-<?php echo $aluno['aluno_id']; ?>-dia-<?php echo $i; ?>"></label>
                                                </div>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Estilos customizados para os checkboxes -->
                <style>
                    .form-check-input:checked~.form-check-label {
                        color: green;
                    }

                    .form-check-input:not(:checked)~.form-check-label {
                        color: red;
                    }

                    .form-check-input:not(:checked)~.form-check-label::after {
                        content: "\f00d";
                        /* Unicode for FontAwesome "X" icon */
                        font-family: "Font Awesome 5 Free";
                        font-weight: 900;
                        margin-left: 5px;
                    }
                </style>


            </div>
            <div class="modal-footer mt-3">
                <button type="button" class="btn btn-login" data-bs-dismiss="modal">Confirmar</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        // Função para abrir o modal secundário sem fechar o fullscreen modal
        function openSecondaryModal(trigger, modalToOpen, parentModal) {
            $(trigger).on('click', function() {
                $(modalToOpen).modal('show');
                $(parentModal).css('z-index', '1040'); // Ajusta o z-index para manter o fullscreen modal no fundo
            });

            $(modalToOpen).on('hidden.bs.modal', function() {
                $(parentModal).css('z-index', '1055'); // Redefine o z-index quando o modal secundário é fechado
            });
        }

        // Aplicando a função para os modais de Notas e Frequência
        openSecondaryModal('#openNotas-<?php echo $turma['turma_id']; ?>', '#modalNotas-<?php echo $turma['turma_id']; ?>', '#modalStudents-<?php echo $turma['turma_id']; ?>');
        openSecondaryModal('#openFrequencia-<?php echo $turma['turma_id']; ?>', '#modalFrequencia-<?php echo $turma['turma_id']; ?>', '#modalStudents-<?php echo $turma['turma_id']; ?>');
    });
</script>

<script>
    var buttons = document.querySelectorAll('.btn-info-class');

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