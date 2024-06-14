//Deletar Turma
//-------------------------------------------------------------------------------------------------------------------------------------------------
$(document).ready(function () {
    // Abrir modal de deleção e passar o token
    $(document).on('click', '.openModalDeleteClass', function () {
        var token = $(this).closest('tr').data('token');

        $('#modalDeleteClass').data('token', token);

        $('#modalDeleteClass').modal('show');
    });

    // Confirmar deleção e enviar o token para o servidor
    $('#confirmDeleteBtn').on('click', function () {
        var token = $('#modalDeleteClass').data('token');

        $('#modalDeleteClass').modal('hide');

        $.ajax({
            type: 'POST',
            url: 'class/controller/deleteClass.php',
            data: { token: token },
            success: function (response) {

                if (response.status === 400) {
                    var errorMessage = "Erro na solicitação: " + response.msg;
                    console.log(errorMessage);
                    $('#modalPermissao').css('z-index', 1040);

                    $("#errorMsg").text(response.msg);
                    var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));
                    errorModal.show();
                } else {
                    $.ajax({
                        type: 'GET',
                        url: 'class/tableClass.php',
                        success: function (newTableHTML) {
                            $('#tableClass').replaceWith(newTableHTML);
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

//Atualizar Turma
//-------------------------------------------------------------------------------------------------------------------------------------------------
$(document).ready(function () {
    var modoEdicao = false;
    var btnTexto = $('#editarBtn span');
    var iconElement = $('#editarBtn i');
    var selectedCell;
    var currentValue;

    function updateCellAndSendData() {
        var cell = selectedCell;
        var tokenTurma = cell.closest('tr').data('token');
        var selectElement = cell.find('select');

        var originalValue = cell.data('original-value');
        var newTextValue, newValue;

        if (selectElement.length > 0) {
            var selectedOptionText = selectElement.find('option:selected').text();
            var selectedOptionValue = selectElement.find('option:selected').val();

            if (originalValue !== selectedOptionText) {
                newTextValue = selectedOptionText;
                newValue = selectedOptionValue;
            } else {
                cell.text(originalValue);
                selectedCell = null;
                return;
            }
        } else {
            newTextValue = inputField.val().trim();
            newTextValue = (originalValue !== newTextValue) ? newTextValue : originalValue;
            newValue = newTextValue;
        }

        console.log('Valor Original:', originalValue);
        console.log('Novo Valor (Texto):', newTextValue);
        console.log('Novo Valor (Banco):', newValue);

        if (originalValue !== newTextValue) {
            cell.text(newTextValue);

            $('#modalUpdateClass').modal('show');
            $('#campoNome').text(originalValue);
            $('#campoValue').text(newTextValue);

            $('#confirmarUpdateBtn').off('click').on('click', function () {
                updateTable(newValue, tokenTurma);
            });
        } else {
            cell.text(originalValue);
        }

        $('.btn-close-update').on('click', function () {
            cell.text(originalValue);
        });

        selectedCell = null;
    }

    $('#editarBtn').on('click', function () {
        if ($('#tableClass tbody tr').length === 0) {
            $("#errorMsg").text('A tabela não contém dados. Não é possível editar.');
            var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));
            errorModal.show();
        } else {
            modoEdicao = !modoEdicao;

            if (modoEdicao) {
                iconElement.removeClass().addClass('bi bi-box-arrow-right');
                btnTexto.text('Sair da Edição ');
                $('#alert').removeClass('d-none');
                habilitarEdicao();
            } else {
                iconElement.removeClass().addClass('bi bi-pen-fill');
                btnTexto.text('Editar ');
                $('.editable-cell, .editable-cell-colaborador').unbind('click');
                selectedCell = null;
            }
        }
    });

    function habilitarEdicao() {
        $('.editable-cell, .editable-cell-colaborador').on('click', function () {
            var cell = $(this);
            var tr = cell.closest('tr');

            if (!cell.data('original-value')) {
                cell.data('original-value', cell.text().trim());
            }

            var originalValue = cell.data('original-value');
            var selectElement = cell.find('select');

            if (cell.hasClass('editable-cell-colaborador')) {
                if (selectElement.length === 0) {
                    var colaborador_id = tr.data('colaborador')
                    var colaborador_nome = cell.text()
                    var selectId = cell.hasClass('editable-cell-colaborador');

                    $.ajax({
                        type: 'POST',
                        url: 'class/selectClass.php',
                        data: {
                            colaborador_id: colaborador_id,
                            colaborador_nome: colaborador_nome
                        },
                        success: function (response) {
                            var tempDiv = $('<div>').html(response);
                            var specificSelect = tempDiv.find('#colaborador_selected');
                            selectElement = specificSelect;
                            cell.html(selectElement);
                            selectElement.focus();
                            selectElement.on('change', function () {
                                currentValue = $('#' + selectId + ' option:selected').text();
                                selectedCell = cell;
                                updateCellAndSendData();
                            });

                            selectElement.on('blur', function (e) {
                                if (!$(e.relatedTarget).closest('.editable-cell, .editable-cell-colaborador').length) {
                                    cell.html(originalValue);
                                }
                            });

                            selectElement.on('keydown', function (e) {
                                if (e.key === 'Enter') {
                                    cell.html(originalValue);
                                }
                            });

                        },
                        error: function (error) {
                            console.error('Erro na solicitação AJAX:', error);
                        }
                    });
                } else {
                    selectElement.focus();
                }
            }
        });
    }
    function updateTable(newValue, tokenTurma) {
        $.ajax({
            method: 'POST',
            url: 'class/controller/updateClass.php',
            data: {
                tokenTurma: tokenTurma,
                colaborador_id: newValue
            },
            success: function (response) {
                console.log(response);
                if (response.status !== 200) {
                    var errorMessage = "Erro na solicitação: " + response.msg;
                    $('#modalUpdateClass').modal('hide');
                    console.log(errorMessage);
                    $("#errorMsg").text(response.msg);
                    var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));
                    errorModal.show();
                } else {
                    $.ajax({
                        type: 'GET',
                        url: 'class/tableClass.php',
                        success: function (newTableHTML) {
                            $('#tableClass').replaceWith(newTableHTML);
                            habilitarEdicao();
                            $('#modalUpdateClass').modal('hide');
                        },
                        error: function (error) {
                            console.error('Erro ao obter dados da tabela:', error);
                        }
                    });
                }
            },
            error: function (error) {
                console.error(error);
            }
        });
    }

});
//-------------------------------------------------------------------------------------------------------------------------------------------------




//Atualizar Status Aluno
//-------------------------------------------------------------------------------------------------------------------------------------------------

$(document).ready(function () {
    // Abrir modal e passar o token
    $(document).on('click', '.openModalStatus', function () {
        var alunoId = $(this).data('alunoid');
        var turmaId = $(this).data('turmaid');
        var token = $(this).closest('tr').data('token');
        var novoStatus = $(this).data('novostatus'); // Defina o novo status aqui
        $('#confirmarUpdateStatusBtn').data('token', token);
        $('#confirmarUpdateStatusBtn').data('alunoid', alunoId);
        $('#confirmarUpdateStatusBtn').data('turmaid', turmaId);
        $('#confirmarUpdateStatusBtn').data('novostatus', novoStatus); // Defina o novo status
        $('#modalStatus-' + alunoId + turmaId).modal('show');
    });

    // Evento clique no botão "Confirmar" para atualizar o status
    $(document).on('click', '.confirmarUpdateStatusBtn', function () {
        var alunoId = $(this).data('alunoid');
        var turmaId = $(this).data('turmaid');
        var token = $(this).data('token');
        var novoStatus = $(this).data('novostatus'); // Obtenha o novo status aqui
        console.log('Token ao confirmar:', token);
        console.log('Novo status:', novoStatus);

        if (!token || novoStatus === "") {
            console.error('Token ou status inválido');
            return;
        }

        $.ajax({
            type: 'POST',
            url: 'class/controller/updateClass.php',
            data: {
                tokenAluno: token,
                novoStatus: novoStatus
            },
            success: function (response) {
                if (response.status === 400) {
                    var errorMessage = "Erro na solicitação: " + response.msg;
                    console.log(errorMessage);
                    $('#modalPermissao').css('z-index', 1040);
                    $("#errorMsg").text(response.msg);
                    var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));
                    errorModal.show();
                } else {
                    $.ajax({
                        type: 'GET',
                        url: 'class/tableClass.php',
                        success: function (newTableHTML) {
                            $('#tableClass').replaceWith(newTableHTML);
                        },
                        error: function (error) {
                            console.error('Erro ao obter dados da tabela:', error);
                        }
                    });
                }
                console.log('Update successful');
                $('#modalStatus-' + alunoId + turmaId).modal('hide');
            },
            error: function (error) {
                console.error('Error updating: ', error);
            }
        });

        $(this).removeData('token'); // Limpar o token após a solicitação AJAX
    });

    $(document).on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('token'); // Limpar o token após fechar o modal
    });
});
//-------------------------------------------------------------------------------------------------------------------------------------------------

$(document).ready(function () {
    var modoEdicao = false;
    var btnTexto = $('#editarBtn span');
    var iconElement = $('#editarBtn i');
    var selectedCell;
    var currentValue;

    function updateCellAndSendData() {
        var cell = selectedCell;
        var tokenTurma = cell.closest('tr').data('token');
        var selectElement = cell.find('select');


        var originalValue = cell.data('original-value');
        var newTextValue, newValue;

        if (selectElement.length > 0) {
            var selectedOptionText = selectElement.find('option:selected').text();
            var selectedOptionValue = selectElement.find('option:selected').val();

            if (originalValue !== selectedOptionText) {
                newTextValue = selectedOptionText;
                newValue = selectedOptionValue;
            } else {
                cell.text(originalValue);
                selectedCell = null;
                return;
            }
        } else {
            newTextValue = inputField.val().trim();
            newTextValue = (originalValue !== newTextValue) ? newTextValue : originalValue;
            newValue = newTextValue;
        }

        console.log('Valor Original:', originalValue);
        console.log('Novo Valor (Texto):', newTextValue);
        console.log('Novo Valor (Banco):', newValue);

        if (originalValue !== newTextValue) {
            cell.text(newTextValue);

            $('#modalUpdateClass').modal('show');
            $('#campoNome').text(originalValue);
            $('#campoValue').text(newTextValue);

            $('#confirmarUpdateBtn').off('click').on('click', function () {
                updateTable(newValue, tokenTurma);
            });
        } else {
            cell.text(originalValue);
        }

        $('.btn-close-update').on('click', function () {
            cell.text(originalValue);
        });

        selectedCell = null;
    }

    $('#editarBtn').on('click', function () {
        if ($('#tableClass tbody tr').length === 0) {
            $("#errorMsg").text('A tabela não contém dados. Não é possível editar.');
            var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));
            errorModal.show();
        } else {
            modoEdicao = !modoEdicao;

            if (modoEdicao) {
                iconElement.removeClass().addClass('bi bi-box-arrow-right');
                btnTexto.text('Sair da Edição ');
                $('#alert').removeClass('d-none');
                habilitarEdicao();
            } else {
                iconElement.removeClass().addClass('bi bi-pen-fill');
                btnTexto.text('Editar ');
                $('.editable-cell, .editable-cell-colaborador').unbind('click');
                selectedCell = null;
            }
        }
    });

    function habilitarEdicao() {
        $('.editable-cell, .editable-cell-colaborador').on('click', function () {
            var cell = $(this);
            var tr = cell.closest('tr');

            if (!cell.data('original-value')) {
                cell.data('original-value', cell.text().trim());
            }

            var originalValue = cell.data('original-value');
            var selectElement = cell.find('select');

            if (cell.hasClass('editable-cell-colaborador')) {
                if (selectElement.length === 0) {
                    var colaborador_id = tr.data('colaborador')
                    var colaborador_nome = cell.text()
                    var selectId = cell.hasClass('editable-cell-colaborador');

                    $.ajax({
                        type: 'POST',
                        url: 'class/selectClass.php',
                        data: {
                            colaborador_id: colaborador_id,
                            colaborador_nome: colaborador_nome
                        },
                        success: function (response) {
                            var tempDiv = $('<div>').html(response);
                            var specificSelect = tempDiv.find('#colaborador_selected');
                            selectElement = specificSelect;
                            cell.html(selectElement);
                            selectElement.focus();
                            selectElement.on('change', function () {
                                currentValue = $('#' + selectId + ' option:selected').text();
                                selectedCell = cell;
                                updateCellAndSendData();
                            });

                            selectElement.on('blur', function (e) {
                                if (!$(e.relatedTarget).closest('.editable-cell, .editable-cell-colaborador').length) {
                                    cell.html(originalValue);
                                }
                            });

                            selectElement.on('keydown', function (e) {
                                if (e.key === 'Enter') {
                                    cell.html(originalValue);
                                }
                            });

                        },
                        error: function (error) {
                            console.error('Erro na solicitação AJAX:', error);
                        }
                    });
                } else {
                    selectElement.focus();
                }
            }
        });
    }
    function updateTable(newValue, tokenTurma) {
        $.ajax({
            method: 'POST',
            url: 'class/controller/updateClass.php',
            data: {
                tokenTurma: tokenTurma,
                colaborador_id: newValue
            },
            success: function (response) {
                console.log(response);
                if (response.status !== 200) {
                    var errorMessage = "Erro na solicitação: " + response.msg;
                    $('#modalUpdateClass').modal('hide');
                    console.log(errorMessage);
                    $("#errorMsg").text(response.msg);
                    var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));
                    errorModal.show();
                } else {
                    $.ajax({
                        type: 'GET',
                        url: 'class/tableClass.php',
                        success: function (newTableHTML) {
                            $('#tableClass').replaceWith(newTableHTML);
                            habilitarEdicao();
                            $('#modalUpdateClass').modal('hide');
                        },
                        error: function (error) {
                            console.error('Erro ao obter dados da tabela:', error);
                        }
                    });
                }
            },
            error: function (error) {
                console.error(error);
            }
        });
    }

});
//-------------------------------------------------------------------------------------------------------------------------------------------------

  $(document).ready(function() {

$("#relatorioBtnClass").click(function() {

  if ($('#tableClass tbody tr').length === 0) {

    $("#errorMsg").text('A tabela não contém dados. Não é possível gerar o relatório.');
    var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));

    errorModal.show();
  } else {

    window.open('relatorio/indexClass.php', '_blank');
  }
});
});



