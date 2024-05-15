$(document).ready(function() {
    $(".modalClassInfo1").click(function(e) {
        e.preventDefault();
        var index = $(this).closest('tr').data('index'); // Captura o valor de data-index


        // Use os dados da variável JavaScript definida no arquivo PHP
        var turmaSelecionada = turmasData[index];

        // Preenche os dados do modal com os dados da turma selecionada
        $("#turma").html("<span class='fw-semibold'>Turma:</span> " + turmaSelecionada.nome_turma);
        $("#treinamento").html("<span class='fw-semibold'>Treinamento:</span> " + turmaSelecionada.nomenclatura);
        $("#coordenador").html("<span class='fw-semibold'>Coordenador:</span> " + turmaSelecionada.nome_usuario);
        $("#objetivo").html("<span class='fw-semibold'>Objetivo:</span> " + turmaSelecionada.objetivo);
        $("#cargaHoraria").html("<span class='fw-semibold'>Carga Horária:</span> " + turmaSelecionada.carga_horaria);
        $("#horasPratica").html("<span class='fw-semibold'>Horas Prática:</span> " + turmaSelecionada.horas_pratica);
        $("#horasTeorica").html("<span class='fw-semibold'>Horas Teórica:</span> " + turmaSelecionada.horas_teorica);

        $("#empresa").html("<span class='fw-semibold'>Empresa:</span> " + turmaSelecionada.nome_fantasia);
        $("#cnpj").html("<span class='fw-semibold'>CNPJ:</span> " + turmaSelecionada.cnpj);

        // Abre o modal
        $('#modalClassInfo').modal('show');

        // Exibe mensagem de sucesso
        $("#mensagemSucesso").html("Dados carregados com sucesso!");
    });
});


   