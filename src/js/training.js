$(document).ready(function () {

    function aplicarMascaraInputs(inputs) {
        inputs.inputmask('99:99:99');
    }

    // Aplicar a máscara aos elementos já presentes na página
    aplicarMascaraInputs($('.cargaHorariaInput, .horasTeoricaInput, .horasPraticaInput'));


    function dividirCargaHoraria(cargaHoraria) {
        var metadeCargaHoraria = divideCargaHoraria(cargaHoraria);
        $('.horasTeoricaInput').val(metadeCargaHoraria);
        $('.horasPraticaInput').val(metadeCargaHoraria);
    }

        function atualizarCamposCargaHoraria() {
            var cargaHoraria = $('.cargaHorariaInput').val();
            var horasTeoricas = $('.horasTeoricaInput').val();
            var horasPraticas = $('.horasPraticaInput').val();
    
            if (cargaHoraria !== "" && (horasTeoricas === "" || horasPraticas === "")) {
                dividirCargaHoraria(cargaHoraria);
            }

            if (horasTeoricas !== "" && horasPraticas !== "") {
                var cargaHorariaTotal = somaCargaHoraria(horasTeoricas, horasPraticas);
                $('.cargaHorariaInput').val(cargaHorariaTotal);
            }
        }
    

    function dividirCargaHorariaDinamic(cargaHorariaDinamic) {
        var metadeCargaHorariaDinamic = divideCargaHoraria(cargaHorariaDinamic);
        $('.horasTeoricaInputDinamic').val(metadeCargaHorariaDinamic);
        $('.horasPraticaInputDinamic').val(metadeCargaHorariaDinamic);
    }


    function atualizarCamposCargaHorariaDinamic() {
        var cargaHorariaDinamic = $('.cargaHorariaInputDinamic').val();
        var horasTeoricasDinamic = $('.horasTeoricaInputDinamic').val();
        var horasPraticasDinamic = $('.horasPraticaInputDinamic').val();

        if (cargaHorariaDinamic !== "" && (horasTeoricasDinamic === "" || horasPraticasDinamic === "")) {
            dividirCargaHorariaDinamic(cargaHorariaDinamic);
        }

        if (horasTeoricasDinamic !== "" && horasPraticasDinamic !== "") {
            var cargaHorariaTotalDinamic = somaCargaHoraria(horasTeoricasDinamic, horasPraticasDinamic);
            $('.cargaHorariaInputDinamic').val(cargaHorariaTotalDinamic);
        }
    }

 
    $(document).on('change', '.cargaHorariaInput, .horasTeoricaInput, .horasPraticaInput', function () {
        atualizarCamposCargaHoraria();
    });


    function aplicarMascaraModal() {
        $('body').on('focus', '.cargaHorariaInputDinamic, .horasTeoricaInputDinamic, .horasPraticaInputDinamic', function () {
            $(this).inputmask('99:99:99');
            atualizarCamposCargaHoraria();
        });
  
        $('body').on('change', '.cargaHorariaInputDinamic, .horasTeoricaInputDinamic, .horasPraticaInputDinamic', function () {
            console.log('Evento de alteração nos campos dinâmicos do modal');
            atualizarCamposCargaHorariaDinamic();
        });
    }

    // Chamar a função para aplicar a máscara aos elementos dinâmicos do modal
    aplicarMascaraModal();

    // Adicionar evento de alteração aos campos "Horas Teóricas" e "Horas Práticas"
    $('#horasTeoricasInput, #horasPraticasInput').change(function () {
        // Obter os valores dos campos
        var horasTeoricas = $('#horasTeoricasInput').val();
        var horasPraticas = $('#horasPraticasInput').val();

        // Calcular a carga horária total
        var cargaHorariaTotal = somaCargaHoraria(horasTeoricas, horasPraticas);

        // Preencher o campo "Carga Horária" com a carga horária total
        $('#cargasHorariaInput').val(cargaHorariaTotal);
    });

    // Função para dividir a carga horária pela metade
    function divideCargaHoraria(cargaHoraria) {
        var cargaHorariaArray = cargaHoraria.split(':');
        var horas = parseInt(cargaHorariaArray[0]);
        var minutos = parseInt(cargaHorariaArray[1]);
        var segundos = parseInt(cargaHorariaArray[2]);

        // Dividir cada parte pela metade
        horas = Math.floor(horas / 2);
        minutos = Math.floor(minutos / 2);
        segundos = Math.floor(segundos / 2);

        // Formatar o resultado
        return padZero(horas) + ':' + padZero(minutos) + ':' + padZero(segundos);
    }

    // Função para somar a carga horária
    function somaCargaHoraria(cargaHoraria1, cargaHoraria2) {
        var cargaHoraria1Array = cargaHoraria1.split(':');
        var cargaHoraria2Array = cargaHoraria2.split(':');

        var horas = parseInt(cargaHoraria1Array[0]) + parseInt(cargaHoraria2Array[0]);
        var minutos = parseInt(cargaHoraria1Array[1]) + parseInt(cargaHoraria2Array[1]);
        var segundos = parseInt(cargaHoraria1Array[2]) + parseInt(cargaHoraria2Array[2]);

        return padZero(horas) + ':' + padZero(minutos) + ':' + padZero(segundos);
    }

    function padZero(number) {
        return number < 10 ? '0' + number : number;
    }
});







$(document).ready(function () {
    $(document).on('click', '#confirmarCreateTraining', function (e) {
        e.preventDefault();

        var formData = $("#insert_training_form").serialize();

        $.ajax({
            type: "POST",
            url: "/projeto/pages/training/controller/create.php",
            data: formData,
            success: function (response) {
                if (response.status !== 200) {
                    var errorMessage = "Erro na solicitação: " + response.msg;

                    console.log(errorMessage);
                    $('#ModalCreateTraining').css('z-index', 1040);

                    $("#errorMsg").text(response.msg);
                    var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));

                    errorModal.show();
                } else {
                    $('#insert_training_form :input').val('');
                    console.log(errorMessage);
                    $('#ModalCreateTraining').css('z-index', 1040);

                    $("#errorMsg").text(response.msg);
                    var errorModal = new bootstrap.Modal(document.getElementById('statusSuccessModal'));

                    errorModal.show();
                    $.ajax({
                        type: 'GET',
                        url: '/projeto/pages/training/tableTraining.php',
                        success: function (newTableHTML) {
                            $('#tabelaTraining').replaceWith(newTableHTML);

                        },
                        error: function (error) {
                            console.error('Erro ao obter dados da tabela:', error);
                        }
                    });
                }
            },
            error: function (error) {
                console.error("Erro:", error);
            }
        });
    });

    $(document).on('hidden.bs.modal', '#statusErrorsModal, #statusSuccessModal', function () {
        $('#ModalCreateTraining').css('z-index', '');
    });
});




$(document).ready(function () {

    $(document).on('click', '.openModalDeleteTraining', function () {

        var token = $(this).closest('tr').data('token');

        $('#modalDeleteTraining').data('token', token);


        $('#modalDeleteTraining').modal('show');
    });


    $('#confirmDeleteBtn').on('click', function () {

        var token = $('#modalDeleteTraining').data('token');


        $('#modalDeleteTraining').modal('hide');


        $.ajax({
            type: 'POST',
            url: '/projeto/pages/training/controller/delete.php',
            data: { token: token },
            success: function (response) {
                if (response.status == 400) {
                    var errorMessage = "Erro na solicitação: " + response.msg;

                    console.log(errorMessage);
                    $('#modalPermissao').css('z-index', 1040);

                    $("#errorMsg").text(response.msg);
                    var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));

                    errorModal.show();
                } else {
                    $.ajax({
                        type: 'GET',
                        url: '/projeto/pages/training/tableTraining.php',
                        success: function (newTableHTML) {
                            $('#tabelaTraining').replaceWith(newTableHTML);

                        },
                        error: function (error) {
                            console.error('Erro ao obter dados da tabela:', error);
                        }
                    });
                }

                console.log('Delete successful');

            },
            error: function (error) {
                console.error('Error deleting: ', error);

            }
        });
    });
});





$(document).ready(function () {

    $(document).on('click', '.openUpdateTraining', function () {

        var tokenTreinamento = $(this).closest('tr').data('token');


        $('#ModalUpdateTraining').remove();


        $.ajax({
            type: 'POST',
            url: '/projeto/pages/training/modalUpdateTraining.php',
            data: { token: tokenTreinamento },
            success: function (response) {

                var tempContainer = $('<div>').html(response);


                $('body').append(tempContainer);


                $('#ModalUpdateTraining').modal('show');
            },
            error: function (error) {
                console.error('Erro na solicitação AJAX:', error);

            }
        });
    });
});



function loadUpdateScript() {

    $(document).ready(function () {
        var tokenTreinamento = $("#UpdateTrainingBtn").data("tokentreinamento");
        var originalValues = {};
        var changedFields = [];

        function getOriginalValues() {
            originalValues = {};
            $("#update_training_form :input, #update_training_form select").each(function () {
                var name = $(this).attr("name");
                originalValues[name] = $(this).val();
            });
        }

        getOriginalValues();

        $("#update_training_form :input, #update_training_form select").change(function () {
            var name = $(this).attr("name");
            if ($(this).val() !== originalValues[name] && changedFields.indexOf(name) === -1) {
                changedFields.push(name);
            }
        });

        $("#UpdateTrainingBtn").click(function (e) {
            e.preventDefault();

            var editedData = {};
            changedFields.forEach(function (name) {
                editedData[name] = $("#update_training_form [name='" + name + "']").val();
            });

            editedData['tokenTreinamento'] = tokenTreinamento;

            $.ajax({
                url: "/projeto/pages/training/controller/update.php",
                method: "POST",
                data: editedData,
                success: function (response) {
                    if (response.status === 200) {
                        $("#msg").removeClass().addClass("alert alert-success").text(response.msg);
                        getOriginalValues();

                        changedFields = [];

                        console.log(response);
                        $.ajax({
                            type: 'GET',
                            url: '/projeto/pages/training/tableTraining.php',
                            success: function (newTableHTML) {
                                $('#tabelaTraining').replaceWith(newTableHTML);

                            },
                            error: function (error) {
                                console.error('Erro ao obter dados da tabela:', error);
                            }
                        });

                    } else {
                        $("#msg").removeClass().addClass("alert alert-danger").text(response.msg);
                        console.log(response.msg);
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });
    });
}

$(document).ready(function () {
    $(document).on('shown.bs.modal', '#ModalUpdateTraining', function () {
        loadUpdateScript();
    });

});


