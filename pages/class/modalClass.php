<div class="modal fade" id="modalClassInfo-<?php echo $turma['turma_id']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #58af9b; color:white;">
                <h1 class="modal-title fs-5" id="staticBackdropLabel"><i class="me-2 fas fa-users"></i>Informações da <?php echo $turma['nome_turma']; ?></h1>
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
                            <span class="fw-semibold">Coordenador:</span> <?php echo $turma['nome_colaborador']; ?>
                        </p>
                        <p id="objetivo">
                            <span class="fw-semibold">Objetivo:</span> <?php echo $turma['objetivo']; ?>
                        </p>
                        <p id="data_inicio">
                            <span class="fw-semibold">Data de Inicio:</span> <?php echo date('d-m-Y', strtotime($turma['data_inicio'])); ?>
                        </p>
                        <p id="cargaHoraria">
                            <span class="fw-semibold">Carga Horária:</span> <?php echo sprintf('%02d:00:00', $turma['carga_horaria']); ?>
                        </p>
                        <p id="horasPratica">
                            <span class="fw-semibold">Horas Prática:</span> <?php echo sprintf('%02d:00:00', $turma['horas_pratica']); ?>
                        </p>
                        <p id="horasTeorica">
                            <span class="fw-semibold">Horas Teórica:</span> <?php echo sprintf('%02d:00:00', $turma['horas_teorica']); ?>
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
<div class="modal fade" id="modalStudents-<?php echo $turma['turma_id']; ?>" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="studentsModalLabel" aria-hidden="false">
    <div class="modal-dialog modal-dialog-scrollable modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #58af9b; color: white;">
                <h1 class="modal-title fs-5" id="studentsModalLabel"><i class="me-2 fas fa-users"></i>Alunos da <?php echo $turma['nome_turma']; ?></h1>
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

                            <button class="btn btn-outline-danger fs-4 d-flex ms-2 relatorioBtnStudents" data-turmarelatorioid="<?php echo $turma['turma_id']; ?>">
                            <i class="fa-solid fa-file-pdf text-center"></i>
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
                                        <th scope="col">Status Aluno</th>
                                       
                                        <?php if ($idPermissao == 1 || $idPermissao == 4) { ?>
                                            <th scope="col" class="text-center">Modificar Status</th>
                                        <?php } ?>
                                        <th scope="col" class="text-center">Gerar Certificado</th>
                                    </tr>
                                </thead>
                                <tbody class="mt-1">
                                    <?php foreach ($alunosData as $index => $aluno) {
                                        $tokenAluno = encrypt_id($aluno['aluno_id'], $encryptionKey, $signatureKey); ?>
                                        <tr class="data-row" data-index="<?php echo $index; ?>" data-token="<?php echo $tokenAluno; ?>" data-colaborador="<?php echo $aluno['aluno_id'] ?>">
                                            <th data-field="registro" class="text-right"> <?php echo $aluno['numero_registro_empresa']; ?></th>
                                            <td data-field="nome_funcionario"><?php echo $aluno['nome_funcionario']; ?></td>
                                            <th data-field="cpf"><?php echo $aluno['cpf']; ?></th>
                                            <td data-field="genero"><?php echo $aluno['genero']; ?></td>
                                            <td class="editable-cell-colaborador" data-field="status"><?php echo $aluno['status']; ?></td>

                                            <?php if ($idPermissao == 1 || $idPermissao == 4) { ?>
                                                <td class="text-center">
                                                    <a href="#" class="ms-2 text-primary text-center openModalStatus" data-bs-toggle="modal" data-bs-target="#modalStatus-<?php echo $aluno['aluno_id']; ?>" data-alunoid="<?php echo $aluno['aluno_id']; ?>" data-turmaid="<?php echo $turma['turma_id']; ?>">
                                                        <i class="bi bi-pencil-fill fs-6"></i>
                                                    </a>
                                                </td>

                                                <td class="text-center">
                                                    <a href="#" class="ms-2 text-success text-center certificadoBtnStudents">
                                                    <i class="bi bi-file-earmark-check-fill"></i>
                                                    </a>
                                                    
                                                </td>


                                            <?php } ?>
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

<!-- Modais de Status -->
<?php foreach ($alunosData as $index => $aluno) {
    $tokenAluno = encrypt_id($aluno['aluno_id'], $encryptionKey, $signatureKey); ?>
    <div class="modal fade modalStatus" id="modalStatus-<?php echo $aluno['aluno_id']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalStatusLabel" aria-hidden="true" data-alunoid="<?php echo $aluno['aluno_id']; ?>" data-turmaid="<?php echo $turma['turma_id']; ?>" data-token="<?php echo $tokenAluno; ?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #58af9b; color:white;">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel"><i class="me-2 fas fa-users"></i> Atualizar Status do Aluno <span id="cargooudepartamento"></span></h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="company_insert" method="POST">
                        <div class="row">
                            <div class="msg d-none alert"></div>
                            <div class="col-md-12">
                                <hr>
                                <span><span class="fw-bold">Nome do Aluno: </span> <?php echo $aluno['nome_funcionario']; ?></span>
                                <br>
                                <span id="statusAtual"><span class="fw-bold">Status Atual: </span><?php echo $aluno['status']; ?></span>
                                <hr>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="cargo">Status</label>
                                <?php
                                $statusAtual = $aluno['status'];
                                $opcaoSelecionada = ($statusAtual == 'ativo') ? 'inativo' : 'ativo';
                                ?>
                                <select class="form-select status-select" name="permissao" aria-label="Default select example" data-alunoid="<?php echo $aluno['aluno_id']; ?>" data-turmaid="<?php echo $turma['turma_id']; ?>">
                                    <option value="<?php echo $opcaoSelecionada; ?>"><?php echo ucfirst($opcaoSelecionada); ?></option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-end">
                    <button type="button" class="btn btn-login confirmarUpdateStatusBtn" data-token="<?php echo $tokenAluno; ?>" data-novostatus="<?php echo $opcaoSelecionada; ?>">Confirmar</button>
                </div>
            </div>
        </div>
    </div>





<?php } ?>

<!-- <script>
    $(document).ready(function() {
        // Função para abrir o modal secundário sem fechar o fullscreen modal
        function openSecondaryModal(trigger) {
            $(document).on('click', trigger, function() {
                var alunoId = $(this).data('alunoid');
                var turmaId = $(this).data('turmaid');

                // Aqui você pode usar os valores de alunoId e turmaId conforme necessário
                console.log('Aluno ID:', alunoId);
                console.log('Turma ID:', turmaId);

                var modalToOpen = '#modalStatus-' + alunoId;
                var parentModal = '#modalStudents-' + turmaId;

                $(modalToOpen).modal('show');
                $(parentModal).css('z-index', '1040'); // Ajusta o z-index para manter o fullscreen modal no fundo
            });

            $(document).on('hidden.bs.modal', '.modalStatus', function() {
                var turmaId = $(this).data('turmaid');
                var parentModal = '#modalStudents-' + turmaId;
                $(parentModal).css('z-index', '1055'); // Redefine o z-index quando o modal secundário é fechado
            });
        }

        openSecondaryModal('.openModalStatus');
        
    });
    </script> -->




<!-- Modal de Notas -->
<div class="modal fade" id="modalNotas-<?php echo $turma['turma_id']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalNotasLabel-<?php echo $turma['turma_id']; ?>" data-notas="<?php echo $turma['turma_id']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #58af9b; color: white;">
                <h1 class="modal-title fs-5" id="modalNotasLabel"><i class="me-2 fas fa-users"></i>Informações da <?php echo $turma['nome_turma']; ?> - Notas</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabelaNota" class="table table-hover table-striped" style="--bs-table-bg: transparent !important;">
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
                                <?php $aluno_ids = []; ?>
                                <?php foreach ($alunosData as $index => $aluno) {
                                    $tokenAluno = encrypt_id($aluno['aluno_id'], $encryptionKey, $signatureKey);
                                ?>
                                    <tr class="data-row" data-index="<?php echo $index; ?>">
                                        <th data-field="registro" class="text-right"> <?php echo $aluno['numero_registro_empresa']; ?></th>
                                        <td data-field="nome_funcionario"><?php echo $aluno['nome_funcionario']; ?></td>
                                        <th class="nota_pratica-<?php echo $aluno['aluno_id']; ?> editable-cell" data-field="nota_pratica"><?php echo number_format($aluno['nota_pratica'], 1, ',', ''); ?></th>
                                        <th class="nota_teorica-<?php echo $aluno['aluno_id']; ?> editable-cell" data-field="nota_teorica"><?php echo number_format($aluno['nota_teorica'], 1, ',', ''); ?></th>
                                        <th data-field="nota_media"><?php echo number_format($aluno['nota_media'], 1, ',', ''); ?></th>
                                    </tr>
                                    <?php array_push($aluno_ids, $aluno['aluno_id']); ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>

            <div class="modal-footer mt-3">

                <button class="btn btn-outline-primary d-flex editarBtnNota"><i class="bi bi-pen-fill"> </i><span class="d-none d-md-block">Editar</span></button>

                <button data-turmaid="<?php echo $turma['turma_id']; ?>" data-aluno_ids="<?php echo join(',', $aluno_ids); ?>" type="button" class="btn btn-login confirmCompanyUpdateBtnNota">Confirmar</button>
            </div>
        </div>
    </div>
</div>


<?php $pdo = $connection->connection(); ?>

<!-- Modal de Frequência -->
<div class="modal fade" id="modalFrequencia-<?php echo $turma['turma_id']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalFrequenciaLabel-<?php echo $turma['turma_id']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #58af9b; color: white;">
                <h1 class="modal-title fs-5" id="modalFrequenciaLabel"><i class="me-2 fas fa-users"></i>Informações da <?php echo $turma['nome_turma']; ?> - Frequência</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabelaStudents" class="table table-hover table-striped table-bordered" style="--bs-table-bg: transparent !important;">
                            <thead>
                                <tr>
                                    <th scope="col">Registro</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Frequência</th>
                                    <?php
                                    $cargaHorariaTotal = $turma['carga_horaria'];
                                    $horasPorDia = 4;
                                    $dias = ceil($cargaHorariaTotal / $horasPorDia);

                                    for ($i = 1; $i <= $dias; $i++) { ?>
                                        <th scope="col-3">Dia <?php echo $i; ?></th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php foreach ($alunosData as $index => $aluno) { ?>
                                    <tr class="data-row align-middle" data-index="<?php echo $index; ?>">
                                        <th data-field="registro" class="text-right"><?php echo $aluno['numero_registro_empresa']; ?></th>
                                        <td data-field="nome_funcionario"><?php echo $aluno['nome_funcionario']; ?></td>
                                        <td data-field="frequencia"><?php echo $aluno['frequencia']; ?></td>
                                        <?php for ($i = 1; $i <= $dias; $i++) { ?>
                                            <?php
                                            $sqlExisteFrequencia = ("SELECT * FROM `frequencia_aluno` WHERE `aluno_id_fk` = :aluno_id_fk AND `turma_id_fk` = :turma_id_fk AND `dia` = :dia LIMIT 1");
                                            $stmtFrequenciaAluno = $pdo->prepare($sqlExisteFrequencia);
                                            $stmtFrequenciaAluno->bindValue(":aluno_id_fk", $aluno['aluno_id'], PDO::PARAM_INT);
                                            $stmtFrequenciaAluno->bindValue(":turma_id_fk", $turma['turma_id'], PDO::PARAM_INT);
                                            $stmtFrequenciaAluno->bindValue(":dia", $i, PDO::PARAM_INT);
                                            $stmtFrequenciaAluno->execute();
                                            $frequenciaAluno = $stmtFrequenciaAluno->fetch(PDO::FETCH_OBJ);
                                            ?>
                                            <td class="p-4">
                                                <div class="form-check fs-5">
                                                    <input data-turmaid="<?php echo $turma['turma_id']; ?>" data-alunoid="<?php echo $aluno['aluno_id']; ?>" data-dia="<?php echo $i; ?>" class="frequencia-aluno-<?php echo $turma['turma_id']; ?> frequencia-<?php echo $aluno['aluno_id']; ?> form-check-input" type="checkbox" id="frequencia-<?php echo $aluno['aluno_id']; ?>-dia-<?php echo $i; ?>" name="frequencia[]" value="dia-<?php echo $i; ?>" <?php echo !$frequenciaAluno ? 'checked' : ''; ?> disabled>
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
            </div>
            <div class="modal-footer mt-3">


                <button class="btn btn-outline-primary d-flex editarBtnFrequencia"><i class="bi bi-pen-fill"> </i><span class="d-none d-md-block">Editar</span></button>

                <button data-turmaid="<?php echo $turma['turma_id']; ?>" type="button" class="btn btn-login confirmStudentUpdateBtnFrequencia">Confirmar</button>


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
    });
</script>