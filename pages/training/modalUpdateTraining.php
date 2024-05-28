<?php
require('../../api/private/connect.php');
include('../../api/private/cript.php');
include '../../api/validade/validate.php';

$connection = new Database();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $valid = isValid(['token']);

    if ($valid) {
        echo $valid;
    } else {


        $token = $_POST['token'];

        try {
            $treinamentoId = decrypt_id($token, $encryptionKey, $signatureKey, 'Treinamento');
        } catch (Exception $e) {
            echo json_encode(['msg' => $e->getMessage(), 'status' => 400]);
            exit;
        }

        $pdo = $connection->connection();

        $stmt = $pdo->prepare("SELECT treinamento.*,
login.nome AS nome_colaborador
 FROM `treinamento` 
INNER JOIN `login` ON login.id = treinamento.colaborador_id
WHERE treinamento.id = :treinamentoId");
        $stmt->bindParam(':treinamentoId', $treinamentoId, PDO::PARAM_INT);
        $stmt->execute();
        $treinamento = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>


<div class="modal fade" id="ModalUpdateTraining" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #58af9b; color:white;">
                <h1 class="modal-title fs-5" id="staticBackdropLabel"><i class="me-2 fas fa-users"></i>Cadastrar Treinamento</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>



            <div class="modal-body">
                <form id="update_training_form" method="POST" action="#">
                    <div id="msg"></div>
                    <div class="row">
                        <div class="msg mx-auto"></div>

                        <div class="col-md-10">
                            <label class="form-label" for="nome_funcionario">Nomenclatura</label>
                            <input type="text" name="nomenclatura" class="form-control" value="<?php echo $treinamento['nomenclatura']; ?>" placeholder="Nomenclatura do Treinamento">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="nr">NR</label>
                            <input type="number" name="nr" class="form-control" value="<?php echo $treinamento['nr']; ?>" placeholder="Norma referente ao Treinamento">
                        </div>

                        <div class="col-md-12 mt-3">
                            <label class="form-label" for="objetivo">Objetivo</label>
                            <textarea class="form-control" name="objetivo" rows="3" placeholder="Objetivo do treinamento"><?php echo $treinamento['objetivo']; ?></textarea>
                        </div>


                        <div class="col-md-12 mt-3">
                            <label class="form-label" for="ementa">Ementa</label>
                            <textarea class="form-control" name="ementa" rows="2" placeholder="Ementa referente ao Treinamento"><?php echo $treinamento['ementa']; ?></textarea>
                        </div>

                        <div class="col-md-7 mt-3">
                            <label class="form-label" for="pre_requisitos">Pré-requisitos</label>
                            <textarea class="form-control" name="pre_requisitos" rows="2" placeholder="Pré-requistos do Treinamento"><?php echo $treinamento['pre_requisitos']; ?></textarea>

                        </div>


                        <div class="col-md-5 mt-3">
                            <label class="form-label" for="material">Material Didático</label>
                            <textarea class="form-control" name="material" rows="2" placeholder="Material necessario para realização do Treinamento"><?php echo $treinamento['material']; ?></textarea>
                        </div>


                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="normas_referencia">Normas Referência</label>
                            <input type="text" name="normas_referencia" class="form-control" value="<?php echo $treinamento['normas_referencia']; ?>" placeholder="Demais normas referentes ao Treinamento">

                        </div>

                        

                        <div class="col-md-6 mt-5">
                            <select class="form-select" name="reciclagem" aria-label="Default select example">
                                <option value="" selected><?php echo $treinamento['reciclagem']; ?></option>
                                <?php if ($treinamento['reciclagem'] !== 'Anual') : ?>
                                    <option value="A">Anual</option>
                                <?php endif; ?>
                                <?php if ($treinamento['reciclagem'] !== 'Bianual') : ?>
                                    <option value="B">Binual</option>
                                <?php endif; ?>
                                <?php if ($treinamento['reciclagem'] !== 'Trianual') : ?>
                                    <option value="T">Trianual</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mt-3">
                            <label class="form-label" for="carga_horaria">Carga Horária</label>
                            <input type="text" name="carga_horaria" value="<?php echo sprintf('%02d:00:00', $treinamento['carga_horaria']); ?>" class="form-control cargaHorariaInputDinamic" placeholder="__:__:__">
                        </div>

                        <div class="col-md-4 mt-3">
                            <label class="form-label" for="horas_teoricas">Horas Teóricas</label>
                            <input type="text" name="horas_teorica" class="form-control horasTeoricaInputDinamic" value="<?php echo sprintf('%02d:00:00', $treinamento['horas_teorica']); ?>" placeholder="__:__:__">
                        </div>

                        <div class="col-md-4 mt-3">
                            <label class="form-label" for="horas_praticas">Horas Práticas</label>
                            <input type="text" name="horas_pratica" class="form-control horasPraticaInputDinamic" value="<?php echo sprintf('%02d:00:00', $treinamento['horas_pratica']); ?>" placeholder="__:__:__">
                        </div>




                        <div class="col-md-12 mt-3">
                            <select id="colaboratorUserSelectUpdate" class="form-select" name="tokenColaborador" aria-label="Default select example">
                                <?php




                                $sql = "SELECT * FROM `login` INNER JOIN `usuario` ON usuario.id = login.id WHERE login.permissao_id = 2 AND login.id != {$treinamento['colaborador_id']}";

                                $usuariosColaboradores = $connection->connection()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
                                ?>


                                <?php $tokenColaboradorAtual = encrypt_id($treinamento['colaborador_id'], $encryptionKey, $signatureKey); ?>
                                <option value="<?php echo $tokenColaboradorAtual; ?>" selected><?php echo $treinamento['nome_colaborador']; ?></option>
                                <?php foreach ($usuariosColaboradores as $usuarioColaborador) {

                                    $tokenColaborador = encrypt_id($usuarioColaborador['id'], $encryptionKey, $signatureKey); ?>

                                    <option value="<?php echo $tokenColaborador; ?>"><?php echo $usuarioColaborador['nome']; ?></option>
                                <?php } ?>
                            </select>

                        </div>



                    </div>



                </form>
            </div>


            <div class="modal-footer d-flex justify-content-end">
                <button type="submit" id="UpdateTrainingBtn" data-tokenTreinamento="<?php echo $_POST['token'] ?>" class="btn btn-login">Confirmar</button>
            </div>
        </div>
    </div>
</div>

