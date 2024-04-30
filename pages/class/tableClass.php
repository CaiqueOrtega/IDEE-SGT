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
                                    var token = $(this).data('token'); // Captura o token da turma

                                    var turmaData = JSON.stringify(<?php echo json_encode($turmasData); ?>);

                                    // Faz a requisição AJAX
                                    $.ajax({
                                        url: './class/modalClass.php', // Caminho do arquivo PHP
                                        method: 'POST',
                                        data: {
                                            index: index,
                                            turma: token, // Passa o token da turma
                                            turmasData: turmaData // Passa tanto o índice quanto a variável $turmasData
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