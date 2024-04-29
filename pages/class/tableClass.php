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
                <th scope="col">Coordenador</th>
                <th scope="col">Carga Horaria</th>
                <th class="text-center">Excluir</th>
                <th class="text-end">Mais</th>
            </tr>


        </thead>

        <tbody class="mt-1">
            <?php
            foreach ($turmasData as $index => $turma) {
                $token = encrypt_id($turma['id'], $encryptionKey, $signatureKey);

            ?>

                <tr class="data-row" id="tableClassInfo" data-index="<?php echo $index; ?>">

                    <th class="editable-cell" data-field="turma"><?php echo $turma['nome_turma']; ?></th>
                    <td class="editable-cell" data-field="treinamento_id"><?php echo $turma['nomenclatura']; ?></td>
                    <td class="editable-cell" data-field="empresa_aluno"><?php echo $turma['nome_fantasia']; ?></td>
                    <td class="editable-cell" data-field="colaborador_id_fk"><?php echo $turma['nome_usuario']; ?></td>
                    <td class="editable-cell" data-field="treinamento_id"><?php echo $turma['carga_horaria']; ?></td>


                    <td class="text-center">

                        <a href="#" class="ms-2 text-danger text-center" data-bs-toggle="modal" data-bs-target="#modalDeleteClass">
                            <i class="bi bi-trash3-fill"></i>
                        </a>

                    </td>

                    <td class="text-end">

                        <a href="#" class="text-primary d-flex modalClassInfo1" data-bs-toggle="modal" data-token="<?php echo $token; ?>" data-bs-target="#modalClassInfo">
                            <i class="bi bi-eye"></i> <i class="bi bi-three-dots-vertical"></i>
                        </a>

                        <script>
                            $(document).ready(function() {
                                $(".modalClassInfo1").click(function(e) {
                                    e.preventDefault();
                                    var index = $(this).closest('tr').data('index'); // Captura o valor de data-index
                                    // Faz a requisição AJAX
                                    $.ajax({
                                        url: './class/tableClass.php', // Substitua 'seu_arquivo.php' pelo caminho do seu arquivo PHP
                                        method: 'POST',
                                        data: {
                                            index: index
                                        }, // Passa o valor de data-index como parâmetro
                                        success: function(response) {
                                            // Lida com a resposta do servidor
                                            console.log(response);
                                        },
                                        error: function(xhr, status, error) {
                                            // Lida com erros na requisição
                                            console.error(error);
                                        }
                                    });
                                });
                            });
                        </script>

                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>


<?php
// seu_arquivo.php

if(isset($_POST['index'])) {
    $index = $_POST['index'];
    // Faça o que precisa ser feito com o índice recebido
    // Por exemplo, você pode usá-lo para buscar dados no banco de dados ou realizar outras operações.
    echo "Índice recebido: " . $index;
} else {
    echo "Índice não recebido.";
}
?>



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
                        <a href="#" id="btn-info-class" class="btn btn" data-bs-toggle="collapse" data-bs-target="#collapseExample1" aria-expanded="false" aria-controls="collapseExample">
                            <i class="bi bi-chevron-right fs-3" style="color:#58af9b;"></i>
                        </a>
                    </div>

                </div>

           
                <div class="collapse " id="collapseExample1">
                    <div class="card card-body border-top-0 rounded-0 mx-1">
                        <p id="turma">
                            <span class="fw-semibold">Turma:</span> <?php echo $turmasData[$index]['nome_turma']; ?>
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
                        <a href="#" id="btn-info-company" class="btn btn" data-bs-toggle="collapse" data-bs-target="#collapseExample2" aria-expanded="false" aria-controls="collapseExample">
                            <i class="bi bi-chevron-right fs-3" style="color:#58af9b;"></i>
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

            </div>
            

            <div class="modal-footer">

                <button type="button" class="btn btn-login">Confirmar</button>
            </div>
        </div>
    </div>
</div>