<?php
session_start();
$idPermissao = $_SESSION['login']['permissao'];
$id = $_SESSION['login']['id'];
?>
<section class="position-relative">
  <!-- <div id="alert" class="d-none alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Selecione</strong> qualquer campo na tabela que deseja editar!.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div> -->
  <div class="container card border  ">
    <div class="row py-3 bg-light shadow border rounded-2">
      <div class="col d-flex justify-content-between">
        <h3>Turmas</h3>
        <div class="d-flex">

          <?php if ($idPermissao == 1 || $idPermissao == 4) { ?>
            <button class="btn btn-outline-primary d-flex" id="editarBtn"><i class="bi bi-pen-fill"> </i><span class="d-none d-md-block">Editar</span></button>
          <?php } ?>




        </div>
      </div>
    </div>
  </div>

  <div class="mt-3">
    <?php require('./tableClass.php'); ?>
    <?php include('../errorAndSuccessModal.php'); ?>
  </div>


  <div class="input-group ms-3 position-absolute w-25" style="right: 13%; top: 6%">
    <select class="form-select" id="filtroSelect">
        <option value="" disabled selected>Selecione um filtro</option> <!-- Opção desativada e selecionada -->
        <option value="" >Sem filtro</option> <!-- Opção desativada e selecionada -->
        <?php
        $treinamentosAdicionados = array(); // Array para armazenar os IDs dos treinamentos já adicionados
        foreach ($turmasData as $turma) {
            // Verifica se o ID do treinamento já foi adicionado
            if (!in_array($turma['treinamento_id'], $treinamentosAdicionados)) {
                // Se não foi adicionado, adiciona ao <select>
                ?>
                <option value="<?php echo $turma['treinamento_id']; ?>"><?php echo $turma['nomenclatura']; ?></option>
                <?php
                // Adiciona o ID do treinamento ao array de treinamentos adicionados
                $treinamentosAdicionados[] = $turma['treinamento_id'];
            }
        }
        ?>
    </select>
    <button class="btn btn-outline-danger fs-6 ms-2" id="relatorioBtnClass" data-filtrorelatorio="<?php echo $turma['treinamento_id']; ?>"><i class="fa-solid fa-file-pdf text-center"></i></button>
</div>

</section>

<div class="modal fade" id="modalDeleteClass" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Confirmar exclusão</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <p>Você realmente deseja excluir a turma <span id="nome_turma"></span>?</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-danger" id="confirmDeleteBtn">Confirmar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalUpdateClass" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitleId">Confirmar Edição em <span id="funcionarioNome"></span></h5>
        <button type="button" class="btn-close btn-close-update" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <p>Você realmente deseja editar de <span class="fw-bold" id="campoNome"></span> para <span class="fw-bold" id=campoValue></span>?</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-close-update" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-danger" id="confirmarUpdateBtn">Confirmar</button>
      </div>
    </div>
  </div>
</div>

<script src="../src/js/scriptClass.js"></script>