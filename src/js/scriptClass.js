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
        $('.confirmarUpdateStatusBtn').data('token', token);
        $('.confirmarUpdateStatusBtn').data('alunoid', alunoId);
        $('.confirmarUpdateStatusBtn').data('turmaid', turmaId);
        $('.confirmarUpdateStatusBtn').data('novostatus', novoStatus); // Defina o novo status
        $('#modalStatus-' + alunoId).modal('show');
    });

    function openSecondaryModal(trigger, modalToOpen, parentModal) {
        $(trigger).on('click', function () {
            $(modalToOpen).modal('show');
            $(parentModal).css('z-index', '1040'); // Ajusta o z-index para manter o fullscreen modal no fundo
        });

        $(modalToOpen).on('hidden.bs.modal', function () {
            $(parentModal).css('z-index', '1055'); // Redefine o z-index quando o modal secundário é fechado
        });
    }

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
                    // $.ajax({
                    //     type: 'GET',
                    //     url: 'class/tableClass.php',
                    //     success: function (newTableHTML) {
                    //         $('#tableClass').replaceWith(newTableHTML);
                    //     },
                    //     error: function (error) {
                    //         console.error('Erro ao obter dados da tabela:', error);
                    //     }
                    // });

                    location.replace(`?modalStudents=modalStudents-${turmaId}`);
                }

                // $('#modalStatus-' + alunoId).modal('hide');
                // $('#modalStudents-' + turmaId).modal('show');

                // var modalToOpen = '#modalStatus-' + alunoId;
                // var parentModal = '#modalStudents-' + turmaId;

                // openSecondaryModal('#modalStudents-' + turmaId);
                // openSecondaryModal(`#openNotas-${turmaId}`, `#modalNotas-${turmaId}`, `#modalStudents-${turmaId}`);
                // openSecondaryModal(`#openFrequencia-${turmaId}`, `#modalFrequencia-${turmaId}`, `#modalStudents-${turmaId}`);


                // $(modalToOpen).modal('show');
                // $(parentModal).css('z-index', '1055'); // Ajusta o z-index para manter o fullscreen modal no fundo
            },
            error: function (error) {
                console.error('Error updating: ', error);
            }
        });

        $(this).removeData('token'); // Limpar o token após a solicitação AJAX
    });

    function verificarModalUrl() {
        const urlSearchParams = new URLSearchParams(window.location.search);
        const params = Object.fromEntries(urlSearchParams.entries());

        let { modalStudents, modalNotas } = params;

        if (modalStudents) {
            $(`#${modalStudents}`).modal('show');
        }
        if (modalNotas) {
            $(`#${modalNotas}`).modal('show');
        }
    }

    verificarModalUrl();

    $(document).on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('token'); // Limpar o token após fechar o modal
    });
});
//-------------------------------------------------------------------------------------------------------------------------------------------------

$(document).ready(function () {

    $(".botao-menu-lateral-turmas").on("click", function () {
        location.replace("?");
    });

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

$(document).ready(function () {

    $("#relatorioBtnClass").click(function () {

        if ($('#tableClass tbody tr').length === 0) {

            $("#errorMsg").text('A tabela não contém dados. Não é possível gerar o relatório.');
            var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));

            errorModal.show();
        } else {

            window.open('relatorio/indexClass.php', '_blank');
        }
    });
});

//-------------------------------------------------------------------------------------------------------------------------------------------------

$(document).ready(function () {
    var modoEdicao = false;
    var btnTexto = $('.editarBtnNota span');
    var iconElement = $('.editarBtnNota i');

    function habilitarEdicao() {
        $('.editable-cell').on('click', function () {
            var cell = $(this);
            var tr = cell.closest('tr');
            var token = tr.data('token');


            var inputField = cell.find('input');
            if (inputField.length === 0) {
                var currentValue = cell.text().trim();

                if (cell.data('field') === 'nota_pratica') {
                    cell.html('<input id="nota_praticaInput" type="text" class="form-control" placeholder=" __._ " value="' + currentValue + '">');

                } else if (cell.data('field') === 'nota_teorica') {
                    cell.html('<input id="nota_teoricaInput" type="text" class="form-control" placeholder=" __._ " value="' + currentValue + '">');

                } else {
                    cell.html('<input type="text" class="form-control" value="' + currentValue + '">');
                }
                inputField = cell.find('input');

                function updateCellAndSendData() {
                    var newValue = inputField.val().trim();


                    if (currentValue !== newValue) {
                        cell.text(newValue);

                        $('#campName').text(cell.data('field'));
                        $('#campValue').text(newValue);


                        $('.confirmCompanyUpdateBtnNota').off('click').on('click', function () {
                            let aluno_ids = $(this).data('aluno_ids');
                            let turmaid = $(this).data('turmaid');

                            let array_aluno_ids = String(aluno_ids).split(",");

                            let notas = [];
                            for (let aluno_id of array_aluno_ids) {
                                let nota_pratica = $(`.nota_pratica-${aluno_id}`).text();
                                let nota_teorica = $(`.nota_teorica-${aluno_id}`).text();
                                notas.push({ aluno_id, nota_pratica, nota_teorica })
                            }

                            // Chame a função para atualizar a tabela somente quando o botão de confirmação for clicado
                            updateTableNota(notas, turmaid);
                        });
                    } else {
                        cell.text(currentValue); // Restaura o valor original se não houve alteração
                    }


                }

                inputField.on('blur', function () {
                    updateCellAndSendData();
                });

                inputField.on('keydown', function (e) {
                    if (e.key === 'Enter') {
                        updateCellAndSendData();
                    }
                });
            }

            inputField.focus();
        });
    }


    $('.editarBtnNota').on('click', function () {
        if ($('#tabelaNota tbody tr').length === 0) {

            $("#errorMsg").text('A tabela não contém dados. Não é possível editar.');

            errorModal.show();
        } else {


            modoEdicao = !modoEdicao;

            if (modoEdicao) {
                // Modo de edição ativado
                iconElement.removeClass().addClass('bi bi-box-arrow-right');
                btnTexto.text('Sair da Edição ');
                $('#alert').removeClass('d-none');

                // Habilita a edição nas células
                habilitarEdicao();
            } else {
                // Modo de edição desativado
                iconElement.removeClass().addClass('bi bi-pen-fill');
                btnTexto.text('Editar ');

                // Remova qualquer lógica associada ao clique nas células durante o modo de edição
                $('.editable-cell').off('click');
            }
        }
    });

    function updateTableNota(notas, turmaid) {
        $.ajax({
            method: 'POST',
            url: 'class/controller/updateClass.php',
            data: { notas },
            success: function (response) {
                console.log(response);
                if (response.status !== 200) {
                    var errorMessage = "Erro na solicitação: " + response.msg;

                    console.log(errorMessage);
                    $("#errorMsg").text(response.msg);

                    errorModal.show();
                } else {
                    location.replace(`?modalNotas=modalNotas-${turmaid}`)
                }

            },
            error: function (error) {
                console.error(error);
            }
        });
    }
});