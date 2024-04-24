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
            foreach ($turmasData as $turma) {
                $token = encrypt_id($turma['id'], $encryptionKey, $signatureKey); ?>

                <tr class="data-row" data-token="<?php echo $token; ?>">

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

                        <a href="#" class="text-primary d-flex" id data-bs-toggle="modal" data-token="<?php echo $token; ?>" data-bs-target="#modalClassInfo">
                            <i class="bi bi-eye"></i> <i class="bi bi-three-dots-vertical"></i>
                        </a>

                        <script>
                            $(document).ready(function() {
                                
                                $('a[data-bs-toggle="modal"]').click(function(event) {
                                   
                                    event.preventDefault();
                                    
                                    var token = $(this).data('token');

                                    $.ajax({
                                        url: 'class/controller/listClass.php', 
                                        type: 'POST',
                                        data: {
                                            token: token
                                        },
                                        success: function(response) {
                                          
                                            console.log('Resposta do servidor:', response);
                                        },
                                        error: function(xhr, status, error) {
                                           
                                            console.error('Erro de solicitação:', status, error);
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