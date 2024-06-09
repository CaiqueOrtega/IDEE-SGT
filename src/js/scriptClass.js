$(document).ready(function () {
    // Abrir modal de deleção e passar o token
    $(document).on('click', '.openModalDeleteClass', function () {
        var token = $(this).closest('tr').data('token');
        console.log('Token from table row:', token);
        
        $('#confirmDeleteBtn').data('token', token);
        console.log('Token set in button:', $('#confirmDeleteBtn').data('token'));

        $('#modalDeleteClass').modal('show');
    });

    // Confirmar deleção e enviar o token para o servidor
    $('#confirmDeleteBtn').on('click', function () {
        var token = $(this).data('token');
        console.log('Token to be sent to server:', token);

        $('#modalDeleteClass').modal('hide');

        $.ajax({
            type: 'POST',
            url: '/IDEE-SGT/pages/class/controller/deleteClass.php',
            data: { token: token },
            dataType: 'json',
            success: function (response) {
                console.log('Server response:', response);

                if (response.status === 400) {
                    var errorMessage = "Erro na solicitação: " + response.msg;
                    console.log(errorMessage);

                    $('#modalPermissao').css('z-index', 1040);
                    $("#errorMsg").text(response.msg);
                    var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));
                    errorModal.show();
                } else {
                    console.log('Deletion successful, updating table...');

                    $.ajax({
                        type: 'GET',
                        url: '/IDEE-SGT/pages/class/tableClass.php',
                        success: function (newTableHTML) {
                            console.log('Table data received, updating HTML.');
                            $('#tableClass').html(newTableHTML);
                        },
                        error: function (error) {
                            console.error('Erro ao obter dados da tabela:', error);
                            var errorMessage = "Erro ao recarregar a tabela: " + error.statusText;
                            $("#errorMsg").text(errorMessage);
                            var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));
                            errorModal.show();
                        }
                    });
                }
            },
            error: function (error) {
                console.error('Error deleting:', error);
                var errorMessage = "Erro ao excluir: " + error.statusText;
                $("#errorMsg").text(errorMessage);
                var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));
                errorModal.show();
            }
        });
    });
});






$(document).ready(function () {
    var modoEdicao = false;
    var btnTexto = $('#editarBtn span');
    var iconElement = $('#editarBtn i');
    var selectedCell;
    var currentValue;

    function habilitarEdicao() {
        $('.editable-cell, .editable-cell-colaborador').on('click', function () {
            var cell = $(this);
            var tr = cell.closest('tr');

            // Salva o valor original da célula apenas se ainda não foi salvo
            if (!cell.data('original-value')) {
                cell.data('original-value', cell.text().trim());
            }

            var originalValue = cell.data('original-value');

            var inputField = cell.find('input');
            var selectElement = cell.find('select');

            if (cell.hasClass('editable-cell-colaborador')) {
                if (selectElement.length === 0) {
                    var tokenTurma = tr.data('token-turma');
                    var selectId = cell.hasClass('editable-cell-colaborador') ? 'colaborador' : '';

                    $.ajax({
                        type: 'POST',
                        url: 'class/selectClass.php',
                        data: {
                            token: tokenTurma
                        },
                        success: function (response) {
                            var tempDiv = $('<div>').html(response);
                            var specificSelect = tempDiv.find('#' + selectId);

                            selectElement = $('<select>')
                                .addClass('form-select')
                                .attr('id', specificSelect.attr('id'))
                                .html(specificSelect.html());

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
                            // Substituir o conteúdo da célula pelo novo elemento select
                            cell.html(selectElement);
                            selectElement.focus();
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

    function updateCellAndSendData() {
        var cell = selectedCell;
        var token = cell.closest('tr').data('token');
        var tokenTurma = cell.closest('tr').data('token-turma');
        var inputField = cell.find('input');
        var selectElement = cell.find('select');

        var originalValue = cell.data('original-value'); // Obtém o valor original da célula
        var dataField = cell.attr('data-field'); // Usamos attr() para obter o valor do atributo
        var newTextValue, newValue;

        if (selectElement.length > 0) {
            // Para elementos select, obtemos o texto e o valor da opção selecionada
            var selectedOptionText = selectElement.find('option:selected').text();
            var selectedOptionValue = selectElement.find('option:selected').val();

            // Verifica se houve uma alteração no texto
            if (originalValue !== selectedOptionText) {
                newTextValue = selectedOptionText;
                newValue = selectedOptionValue;
            } else {
                // Se não houver alteração no texto, restaura o valor original
                cell.text(originalValue);
                // Limpa a célula selecionada após a atualização
                selectedCell = null;
                return;
            }
        } else {
            // Para outros elementos de entrada, obtemos o valor do campo de entrada
            newTextValue = inputField.val().trim();

            // Verifica se houve uma alteração no texto
            newTextValue = (originalValue !== newTextValue) ? newTextValue : originalValue;
            newValue = newTextValue;
        }

        // Adicione console.log para depuração
        console.log('Valor Original:', originalValue);
        console.log('Novo Valor (Texto):', newTextValue);
        console.log('Novo Valor (Banco):', newValue);
        console.log('Data Field:', dataField);

        // Verifica se o valor foi realmente alterado
        if (originalValue !== newTextValue) {
            // Atualiza o texto da célula com o novo valor
            cell.text(newTextValue);

            // Mostra o modal
            $('#modalUpdateClass').modal('show');

            // Atualiza os elementos no modal com o texto da opção selecionada
            $('#campoNome').text(originalValue);
            $('#campoValue').text(newTextValue);
            $('#funcionarioNome').text(dataField);

            // Define o clique do botão de confirmação
            $('#confirmarUpdateBtn').off('click').on('click', function () {
                // Chame a função para atualizar a tabela somente quando o botão de confirmação for clicado
                updateTable(cell, token, newValue, tokenTurma);
            });
        } else {
            // Se não houver alteração, restaura o valor original
            cell.text(originalValue);
        }

        // Define o clique do botão de fechar
        $('.btn-close-update').on('click', function () {
            // Restaura o valor original se o botão de fechar for clicado
            cell.text(originalValue);
        });

        // Limpa a célula selecionada após a atualização
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
                // Modo de edição ativado
                iconElement.removeClass().addClass('bi bi-box-arrow-right');
                btnTexto.text('Sair da Edição ');
                $('#alert').removeClass('d-none');

                // Desvincula (unbind) os manipuladores de eventos existentes antes de adicionar novos
                // Habilita a edição nas células
                habilitarEdicao();
            } else {
                // Modo de edição desativado
                iconElement.removeClass().addClass('bi bi-pen-fill');
                btnTexto.text('Editar ');

                // Desvincula (unbind) todos os manipuladores de eventos nas células editáveis
                $('.editable-cell, .editable-cell-colaborador').unbind('click');

                selectedCell = null; // Limpa a célula selecionada ao sair do modo de edição
            }
        }
    });

    function updateTable(cell, token, newValue, tokenTurma) {
        var dataField;

        // Verifica se a célula é uma célula de seleção
        if (cell.hasClass('editable-cell-cargo')) {
            dataField = 'colaborador';
        } else {
            // Se não for uma célula de seleção, usa o data-field existente
            dataField = cell.data('field');
        }

        console.log('CAMPO:' + dataField);
        $.ajax({
            method: 'POST',
            url: 'class/controller/updateClass.php',
            data: {
                field: dataField, // Use o data-field corrigido
                token: token,
                tokenTurma: tokenTurma,
                value: newValue
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
                    // Assuming success: reload the table
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
