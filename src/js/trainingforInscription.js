

(function ($) {
$(document).ready(function () {
    $('.modalInscricaoBtn').click(function () {
        var nomenclatura = $(this).data('nomenclatura');
        var tokenTraining = $(this).data('token');

        // Antes de fazer a nova consulta, remove o modal existente se houver
        $('#modalMoreInscricaoTraining').remove();

        $.ajax({
            type: 'POST',
            url: 'training/modalTraining.php',
            data: {
                token: tokenTraining
            },
            success: function (response) {

                if (response.status && response.status == 400) {

                    console.log('response.msg)')
                    $("#errorMsg").text(response.msg);
                    var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));
                    errorModal.show();
                } else {



                    $('body').append(response);

                    $('#modalMoreInscricaoTraining').modal('show');
                    $('#treinamentoNome').text(nomenclatura.substring(0, 65));
                }

            }
        });


    });
});

})(jQuery);




(function ($) {


$(document).ready(function () {
    $(document).on('click', '#enviarInscricao', function () {

        var tokensFuncionarios = [];
        $("input[type=checkbox]:checked").each(function () {
            var tokenFuncionario = $(this).data('token');
            tokensFuncionarios.push(tokenFuncionario);
        });

        var tokenEmpresa = $("#employeeCompanySelect").val();
        var tokenTreinamento = $(this).data('token');
        

        console.log("funcionarios" + tokensFuncionarios);
        console.log("Empresa:" + tokenEmpresa);
        console.log('Treinamento' + tokenTreinamento);
       
        var dataAtual = new Date();
        var dataFormatada = dataAtual.toISOString();

        console.log(tokensFuncionarios);

        $.ajax({
            type: "POST",
            url: "inscription/controller/create.php",
            data: {
                tokenEmpresa: tokenEmpresa,
                tokensFuncionarios: tokensFuncionarios,
                tokenTreinamento: tokenTreinamento,
                dataEnvio: dataFormatada
            },

            success: function (response) {
                var errorMessage = "Erro na solicitação: " + response.msg;
                var page = 'training';

                if (response.status !== 200) {

                    console.log(errorMessage);
                    $("#errorMsg").text(response.msg);
                    var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));
                    $('#modalMoreInscricaoTraining').css('z-index', '1040');
                    errorModal.show();
                } else {
                    $.ajax({
                        type: 'POST',
                        url: 'training/tableEmployees.php',
                        data: {
                            token: tokenEmpresa,
                            tokenTraining: tokenTreinamento,
                            pages: page
                        },
                        success: function (data) {

                            $('#table-employee').html(data);
                        }
                    });

                    console.log(errorMessage);
                    $("#successMsg").text(response.msg);
                    var errorModal = new bootstrap.Modal(document.getElementById('statusSuccessModal'));
                    $('#modalMoreInscricaoTraining').css('z-index', '1040');
                    errorModal.show();
                }

            },
            error: function (xhr, status, error) {
                var errorMessage = "Erro na solicitação: " + error;
                console.log(errorMessage);

            }
        });
    });

    $(document).on('hidden.bs.modal', '#statusErrorsModal, #statusSuccessModal', function () {
        $('#modalMoreInscricaoTraining').css('z-index', '');
    });
});
})(jQuery);






(function ($) {

$(document).ready(function () {
    $(document).on('change', '#employeeCompanySelect', function () {
        // Obtém o valor selecionado
        var selectedToken = $(this).val();
        var tokenTreinamento = $('#enviarInscricao').data('token');
        var pages = 'training';

        console.log("TOKEN TREINAMENTO: " + tokenTreinamento);

        if (selectedToken !== "") {
            // Envia o token para o arquivo PHP usando AJAX
            $.ajax({
                type: 'POST',
                url: 'training/tableEmployees.php', // Atualize com o caminho correto
                data: {
                    token: selectedToken,
                    tokenTraining: tokenTreinamento,
                    pages: pages
                },
                success: function (response) {

                    if (response.status == 400) {


                        $("#errorMsg").text(response.msg);
                        var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));
                        $('#modalMoreInscricaoTraining').css('z-index', '1040');
                        errorModal.show();

                    }
                    $('#table-employee').html(response);
                }
            });
        } else {
            $('#table-employee').empty();
        }
    });
});
})(jQuery);
