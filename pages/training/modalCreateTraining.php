<div class="modal fade" id="ModalCreateTraining" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #58af9b; color:white;">
                <h1 class="modal-title fs-5" id="staticBackdropLabel"><i class="me-2 fas fa-users"></i>Cadastrar Treinamento</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>



            <div class="modal-body">
                <form id="insert_training_form" method="POST" action="#">
                    <div class="row">
                        <div class="msg mx-auto"></div>

                        <div class="col-md-10">
                            <label class="form-label" for="nome_funcionario">Nomenclatura</label>
                            <input type="text" name="nomenclatura" class="form-control" placeholder="Nomenclatura do Treinamento">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="nr">NR</label>
                            <input type="number" name="nr" class="form-control" placeholder="Norma referente ao Treinamento">
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="form-label" for="objetivo">Objetivo</label>
                            <textarea class="form-control" name="objetivo" rows="3" placeholder="Objetivo do treinamento"></textarea>
                        </div>


                        <div class="col-md-12 mt-3">
                            <label class="form-label" for="ementa">Ementa</label>
                            <textarea class="form-control" name="ementa" rows="2" placeholder="Ementa referente ao Treinamento"></textarea>
                        </div>

                        <div class="col-md-7 mt-3">
                            <label class="form-label" for="pre_requisitos">Pré-requisitos</label>
                            <textarea class="form-control" name="pre_requisitos" rows="2" placeholder="Pré-requistos do Treinamento"></textarea>

                        </div>


                        <div class="col-md-5 mt-3">
                            <label class="form-label" for="material">Material Didático</label>
                            <textarea class="form-control" name="material" rows="2" placeholder="Material necessario para realização do Treinamento"></textarea>
                        </div>


                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="normas_referencia">Normas Referência</label>
                            <input type="text" name="normas_referencia" class="form-control" placeholder="Demais normas referentes ao Treinamento">

                        </div>



                        <div class="col-md-6 mt-5">
                            <select class="form-select" name="reciclagem" aria-label="Default select example">
                                <option value="" selected>Selecione o tempo de reciclagem...</option>
                                <option value="A">Anual</option>
                                <option value="B">Binual</option>
                                <option value="T">Trianual</option>
                            </select>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label class="form-label" for="carga_horaria">Carga Horária</label>
                            <input type="text" name="carga_horaria" class="form-control cargaHorariaInput" placeholder="__:__:__">
                        </div>

                        <div class="col-md-4 mt-3">
                            <label class="form-label" for="horas_teoricas">Horas Teóricas</label>
                            <input type="text" name="horas_teorica" class="form-control horasTeoricaInput" placeholder="__:__:__">
                        </div>

                        <div class="col-md-4 mt-3">
                            <label class="form-label" for="horas_praticas">Horas Práticas</label>
                            <input type="text" name="horas_pratica" class="form-control horasPraticaInput" placeholder="__:__:__">
                        </div>




                        <div class="col-md-12 mt-3">
                            <select id="colaboratorUserSelect" class="form-select" name="tokenColaborador" aria-label="Default select example">
                                <?php




                                $sql = ("SELECT * FROM `login` INNER JOIN `usuario` ON usuario.id = login.id WHERE login.permissao_id = 2");
                                $usuariosColaboradores = $connection->connection()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
                                ?>

                                <option value="" selected>Selecione uma colaborador...</option>
                                <?php foreach ($usuariosColaboradores as $usuarioColaborador) {
                                    $token = encrypt_id($usuarioColaborador['id'], $encryptionKey, $signatureKey); ?>
                                    <option value="<?php echo $token; ?>"><?php echo $usuarioColaborador['nome']; ?></option>
                                <?php } ?>
                            </select>

                        </div>

                    </div>



                </form>
            </div>

            <div class="modal-footer d-flex justify-content-end">
                <button type="submit" id="confirmarCreateTraining" class="btn btn-login">Confirmar</button>
            </div>
        </div>
    </div>
</div>

