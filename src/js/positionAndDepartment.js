$(document).ready(function () {
    var token;

    // Usando delegação de eventos para lidar com cliques nos botões
    $(document).on('click', '.delete-button-departamento, .delete-button-cargo', function () {
        var row = $(this).closest('tr');
        token = row.data('token');
        console.log(token);

        var nome = row.find('.editable-cell-cargo-departamento').text();
        var tipo = $(this).hasClass('delete-button-cargo') ? 'cargo' : 'departamento';

        $('#orName').text(nome);
        $('#positionOrDepartment').text(tipo);

        $('#modalCompanyInfo').css('z-index', '1040');
        $('#modalSecundario').modal('show');
    });

    // Usando delegação de eventos para lidar com cliques no botão de confirmação de exclusão
    $(document).on('click', '#confirmDeleteCargoOrDepartamentoBtn', function () {
        var tipo = $('#positionOrDepartment').text();

        var url = (tipo === 'cargo') ? 'positionAndDepartment/controller/deleteCargo.php' : 'positionAndDepartment/controller/deleteDepartamento.php';

        $.ajax({
            url: url,
            type: 'POST',
            data: { token: token },
            success: function (response) {
                var tokenDoModal = $('#modalPositionAndDepartment').data('token');
                console.log("ESSE È O TOKEN DO MODAL", tokenDoModal);

                $.ajax({
                    url: 'positionAndDepartment/tablePositionAndDepartment.php',
                    type: 'POST',
                    data: { token: tokenDoModal },
                    success: function (tabelasResponse) {

                        var newTbodyCargo = $(tabelasResponse).find('#tabelCargo');
                        var newTbodyDepartamento = $(tabelasResponse).find('#tabelDepartamento');

                        // Substituir o tbody antigo pelos novos elementos
                        $('#tabelCargo').replaceWith(newTbodyCargo);
                        $('#tabelDepartamento').replaceWith(newTbodyDepartamento);
                    },
                    error: function (error) {
                        console.error('Erro na segunda solicitação:', error);
                    }
                });
            },
            error: function (error) {
                console.error('Erro:', error);
            }
        });

        $('#modalSecundario').modal('hide');
    });

    // Usando delegação de eventos para lidar com o fechamento do modal
    $(document).on('hidden.bs.modal', '#modalSecundario', function () {
        $('#modalCompanyInfo').css('z-index', '');
    });
});






$(document).ready(function () {
    var modoEdicao = false;
    var btnTexto = $('#editarCargoOuDepartamentoBtn span');
    var iconElement = $('#editarCargoOuDepartamentoBtn i');

    function habilitarEdicao() {
        $('.editable-cell-cargo-departamento').on('click', function () {
            var cell = $(this);
            var tr = cell.closest('tr');
            var token = tr.data('token');
            var tokenCompany = $('#tableCargoDepartamento').data('token-company');

            var inputField = cell.find('input');
            if (inputField.length === 0) {
                var currentValue = cell.text().trim();
                cell.html('<input type="text" class="form-control" value="' + currentValue + '">');

                inputField = cell.find('input');
                inputField.focus();

                function updateCellAndSendData() {
                    var newValue = inputField.val().trim();

                    // Verifica se o valor foi realmente alterado
                    if (currentValue !== newValue) {
                        cell.text(newValue);

                        var type;
                        if (cell.hasClass('cargo')) {
                            type = 'cargo';
                        } else if (cell.hasClass('departamento')) {
                            type = 'departamento';
                        } else {
                            console.error('Tipo desconhecido');
                            return;
                        }

                        $('#campoAtual').text(currentValue);
                        $('#campoNovo').text(newValue);
                        $('#positionOrdepartmentName').text(type);


                        console.log(newValue);

                        $('#confirmUpdateCargoOrDepartamentoBtn').off('click');

                        $('#modalCompanyInfo').css('z-index', '1040');
                        $('#modalUpdatePositionAndDepartment').modal('show');


                        $('#confirmUpdateCargoOrDepartamentoBtn').on('click', function () {


                            updateTable(cell, token, newValue, type, tokenCompany);
                        });
                    } else {
                        cell.text(currentValue); // Restaura o valor original se não houve alteração
                    }

                    $('.close-update-btn').on('click', function () {
                        cell.text(currentValue);
                    });

                }

                inputField.on('blur', function () {
                    updateCellAndSendData();
                });

                inputField.on('keydown', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        updateCellAndSendData();
                    }
                });
            }

            inputField.focus();
        });
    }


    $('#editarCargoOuDepartamentoBtn').on('click', function () {
        modoEdicao = !modoEdicao;

        if (modoEdicao) {
            // Modo de edição ativado
            iconElement.removeClass().addClass('bi bi-box-arrow-right');
            btnTexto.text('Sair da Edição ');

            // Habilita a edição nas células
            habilitarEdicao();
        } else {
            // Modo de edição desativado
            iconElement.removeClass().addClass('bi bi-pen-fill');
            btnTexto.text('Editar ');

            // Remova qualquer lógica associada ao clique nas células durante o modo de edição
            $('.editable-cell-cargo-departamento').off('click');
        }
    });

    function updateTable(cell, token, newValue, type, tokenCompany) {


        $.ajax({
            method: 'POST',
            url: 'positionAndDepartment/controller/update.php',
            data: {
                field: cell.data('field'),
                token: token,
                tokenCompany: tokenCompany,
                value: newValue,
                type: type
            },
            success: function (response) {
                console.log(response);
                if (response.status !== 200) {
                    var errorMessage = "Erro na solicitação: " + response.msg;
                    $('#modalUpdatePositionAndDepartment').modal('hide');

                    console.log(errorMessage);
                    $("#errorMsg").text(response.msg);
                    var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));
                    $('#modalCompanyInfo').css('z-index', '1040');
                    errorModal.show();
                } else {
                    }

                    $('#modalUpdatePositionAndDepartment').modal('hide');
                    $.ajax({
                        url: 'positionAndDepartment/tablePositionAndDepartment.php',
                        type: 'POST',
                        data: { token: tokenCompany },
                        success: function (tabelasResponse) {
                            var newTbodyCargo = $(tabelasResponse).find('#tabelCargo');
                            var newTbodyDepartamento = $(tabelasResponse).find('#tabelDepartamento');

                            // Substituir o tbody antigo pelos novos elementos
                            $('#tabelCargo').replaceWith(newTbodyCargo);
                            $('#tabelDepartamento').replaceWith(newTbodyDepartamento);
                            habilitarEdicao();
                        },
                        error: function (error) {
                            console.error('Erro na segunda solicitação:', error);
                        }

                    });
            },
            error: function (error) {
                console.error(error);
            }
        });
    }

    $('#modalUpdatePositionAndDepartment, #statusErrorsModal').on('hidden.bs.modal', function () {
        // Restaurar o z-index do modal principal ao valor padrão
        $('#modalCompanyInfo').css('z-index', '');
    });
});




