<?php require('./controller/list.php');


?>

<script src="../src/js/trainingforInscription.js"></script>


<?php foreach ($treinamentos as $i => $treinamento) {

  $tokenTraining = encrypt_id($treinamento['id'], $encryptionKey, $signatureKey);
  $colapsoId = 'colapso_' . $i;
?>





  <div class="card d-flex flex-row mt-3 rounded-1" style='margin-bottom: -6px;'>
    <div class=" card-header text-center text-white rounded-0 rounded-start-1" style="background-color: #58af9b;">
      <h5 class="mt-3">NR <br><?php echo $treinamento['nr']; ?></h5>
    </div>
    <div class="card-body">
      <div class="d-flex flex-row justify-content-between">
        <p class="card-text nomenclaura" style="font-size: 18px; "><?php echo $treinamento['nomenclatura']; ?></p>

        <a href="#" id="btn-info" class="btn btn" data-bs-toggle="collapse" data-bs-target="#<?php echo $colapsoId; ?>" aria-expanded="false" aria-controls="collapseExample">
          <i class="icon bi bi-chevron-right fs-3" style="color:#58af9b;"></i>

        </a>
      </div>
    </div>
  </div>

  <div class="collapse" id="<?php echo $colapsoId; ?>">

    <div class="card d-flex flex-row border-top-0 ">
      <div class=" card-header text-center text-white rounded-0 rounded-start-1 border-top-0" style="background-color: #58af9b; width: 60px; max-width: 60px; min-width: 60px;">

      </div>
      <div class="card-body">


        <h6 class="mt-2">Objetivo:</h6><span id="objetivo"><?php echo $treinamento['objetivo']; ?></span>

        <h6 class="mt-2">Pré-requisitos:</h6><span id="preRequisitos"><?php echo $treinamento['pre_requisitos']; ?></span>

        <h6 class="mt-2">Carga Horária</h6><span id="cargaHoraria"><?php echo sprintf('%02d:00:00', $treinamento['carga_horaria']); ?></span>

        <h6 class="mt-2">Horas-prática</h6><span id="horasPratica"> <?php echo sprintf('%02d:00:00', $treinamento['horas_pratica']); ?></span>

        <h6 class="mt-2">Horas-teórica</h6><span id="horas-teorica"><?php echo sprintf('%02d:00:00', $treinamento['horas_teorica']); ?></span>


        <div class="col-md-12 d-flex justify-content-end">
          <button type="button" class="btn btn-login modalInscricaoBtn" data-nomenclatura="<?php echo htmlspecialchars($treinamento['nomenclatura']); ?>" data-token="<?php echo $tokenTraining; ?> ">
            Solicitar Inscrição</button>
        </div>
      </div>

    </div>
  </div>





<?php }
include('../errorAndSuccessModal.php');
?>

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