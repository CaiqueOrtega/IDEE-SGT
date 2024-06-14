

$(document).ready(function () {
    var modoEdicao = false;
    var btnTexto = $('#editarBtn span');
    var iconElement = $('#editarBtn i');
    var selectedCell;
    var currentValue;

    function habilitarEdicao() {
        $('.editable-cell, .editable-cell-cargo, .editable-cell-departamento, .editable-cell-empresa').on('click', function () {
            var cell = $(this);
            var tr = cell.closest('tr');


            // Salva o valor original da célula apenas se ainda não foi salvo
            if (!cell.data('original-value')) {
                cell.data('original-value', cell.text().trim());
            }

            var originalValue = cell.data('original-value');

            var inputField = cell.find('input');
            var selectElement = cell.find('select');

            if (cell.hasClass('editable-cell-cargo') || cell.hasClass('editable-cell-departamento')) {




                if (selectElement.length === 0) {
                    var tokenEmpresa = tr.data('token-empresa');
                    var selectId = cell.hasClass('editable-cell-cargo') ? 'cargo' : 'departamento';



                    $.ajax({
                        type: 'POST',
                        url: 'positionAndDepartment/selectPositionAndDepartment.php',
                        data: {
                            token: tokenEmpresa
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
                                if (!$(e.relatedTarget).closest('.editable-cell, .editable-cell-cargo, .editable-cell-departamento').length) {
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
            } else if (cell.hasClass('editable-cell-empresa')) {
                // Exibir o seletor de empresas



                if (selectElement.length === 0 ) {
                    $.ajax({
                        type: 'POST',
                        url: 'company/selectCompany.php',
                        success: function (response) {
                            cell.html(response);

                            $('#employeeCompanySelect').on('change', function () {
                                currentValue = $('#employeeCompanySelect option:selected').text();
                                selectedCell = cell;
                                updateCellAndSendData();
                            });

                            $('#employeeCompanySelect').on('blur', function (e) {
                                if (!$(e.relatedTarget).closest('#employeeCompanySelect').length) {
                                    cell.html(originalValue);

                                }
                            });

                            $('#employeeCompanySelect').on('keydown', function (e) {
                                if (e.key === 'Enter') {
                                    cell.html(originalValue);

                                }
                            });

                            $('#employeeCompanySelect').focus();

                        },
                        error: function (error) {
                            console.error('Erro na solicitação AJAX:', error);
                        }

                    });
                }else {
                    selectElement.focus();
                }

            } else {
                // Handle input elements
                if (inputField.length === 0) {
                    currentValue = cell.text().trim();

                    if (cell.data('field') === 'cpf') {
                        cell.html('<input id="cpfInput" type="text" class="form-control"  placeholder="___.___.___-__" value="' + currentValue + '">');
                        $('#cpfInput').inputmask('999.999.999-99', {
                            showMask: false
                        });
                    } else if (cell.data('field') === 'telefone') {
                        cell.html('<input id="telefoneInput" type="text" class="form-control" placeholder="(__) _-____-____" value="' + currentValue + '">');
                        $('#telefoneInput').mask('(00) 0-0000-0000', {
                            showMask: false
                        });
                    } else {
                        cell.html('<input type="text" class="form-control" value="' + currentValue + '">');
                    }

                    inputField = cell.find('input');

                    inputField.on('blur', function () {
                        selectedCell = cell;
                        updateCellAndSendData();
                    });

                    inputField.on('keydown', function (e) {
                        if (e.key === 'Enter') {
                            selectedCell = cell;
                            updateCellAndSendData();
                        }
                    });
                }

                inputField.focus();
            }
        });
    }





    function updateCellAndSendData() {
        var cell = selectedCell;
        var token = cell.closest('tr').data('token');
        var tokenEmpresa = cell.closest('tr').data('token-empresa');
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
            $('#modalUpdateEmployee').modal('show');

            // Atualiza os elementos no modal com o texto da opção selecionada
            $('#campoNome').text(originalValue);
            $('#campoValue').text(newTextValue);
            $('#funcionarioNome').text(dataField);

            // Define o clique do botão de confirmação
            $('#confirmarUpdateBtn').off('click').on('click', function () {
                // Chame a função para atualizar a tabela somente quando o botão de confirmação for clicado
                updateTable(cell, token, newValue, tokenEmpresa);
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
    
        if ($('#tabelaEmployees tbody tr').length === 0) {

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
            $('.editable-cell, .editable-cell-cargo, .editable-cell-departamento, .editable-cell-empresa').unbind('click');

            selectedCell = null; // Limpa a célula selecionada ao sair do modo de edição
        }
    }
    });



    function updateTable(cell, token, newValue, tokenEmpresa) {
        var dataField;

        // Verifica se a célula é uma célula de seleção
        if (cell.hasClass('editable-cell-cargo')) {
            dataField = 'cargo_id';
        } else if (cell.hasClass('editable-cell-departamento')) {
            dataField = 'departamento_id';
        } else {
            // Se não for uma célula de seleção, usa o data-field existente
            dataField = cell.data('field');
        }

        console.log('CAMPO:' + dataField);
        $.ajax({
            method: 'POST',
            url: 'employee/controller/update.php',
            data: {
                field: dataField, // Use o data-field corrigido
                token: token,
                tokenEmpresa: tokenEmpresa,
                value: newValue
            },
            success: function (response) {
                console.log(response);
                if (response.status !== 200) {
                  var errorMessage = "Erro na solicitação: " + response.msg;
                  $('#modalUpdateEmployee').modal('hide');
        
                  console.log(errorMessage);
                  $("#errorMsg").text(response.msg);
                  var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));
        
                  errorModal.show();
              } else {
                  }

                $.ajax({
                    type: 'GET',
                    url: 'employee/tableEmployee.php',
                    success: function (newTableHTML) {
                        $('#tabelaEmployees').replaceWith(newTableHTML);
                        habilitarEdicao();
                        $('#modalUpdateEmployee').modal('hide');
                    },
                    error: function (error) {
                        console.error('Erro ao obter dados da tabela:', error);
                    }
                });
            },
            error: function (error) {
                console.error(error);
            }
        });
    }

});





$(document).ready(function () {
    $('#modalDeleteEmployee').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var tr = button.closest('tr');

        var token = tr.data('token');
        var nomeFuncionario = tr.find('.editable-cell[data-field="nome_funcionario"]').text();

        $(this).find('#employeeName').text(nomeFuncionario);

        $('#confirmDeleteBtn').off('click').on('click', function () {
            $.ajax({
                type: 'POST',
                url: 'employee/controller/delete.php',
                data: {
                    token: token
                },
                success: function (response) {
                    console.log('Resposta da exclusão:', response);
                    console.log(response);
                    if (response.status !== 200) {
                      var errorMessage = "Erro na solicitação: " + response.msg;
                      

            
                      console.log(errorMessage);
                      $("#errorMsg").text(response.msg);
                      $("#errorMsg").css("white-space", "pre-line");
                      $("#errorMsg").addClass("text-start");
                      var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));
            
                      errorModal.show();
                  } else {
                      }
    
                    $.ajax({
                        type: 'GET',
                        url: 'employee/tableEmployee.php',
                        success: function (newTableHTML) {
                            console.log('Nova tabela recebida com sucesso');
                            $('#tabelaEmployees').replaceWith(newTableHTML);
                            $('#modalDeleteEmployee').modal('hide');
                        },
                        error: function (error) {
                            console.error('Erro ao obter dados da tabela:', error);
                        }
                    });
                },
                error: function (error) {
                    console.error('Erro ao excluir funcionário:', error);
                }
            });
        });
    });
});



$(document).ready(function () {
    // Adiciona um ouvinte de mudança ao elemento de seleção
    $('#employeeCompanySelect').change(function () {
        // Obtém o valor selecionado
        var selectedToken = $(this).val();
        console.log(selectedToken);
        // Verifica se foi selecionada alguma opção
        if (selectedToken !== "") {
            $.ajax({
                type: 'POST',
                url: 'positionAndDepartment/selectPositionAndDepartment.php',
                data: { token: selectedToken },
                success: function (response) {
                    // Atualiza a div com o conteúdo recebido do arquivo list.php
                    $('.positionAndDepartmentContent').html(response);
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    // Trate o erro conforme necessário
                }
            });
        } else {
            // Limpa a div se nada estiver selecionado
            $('.positionAndDepartmentContent').empty();
        }
    });
});




$(document).ready(function () {
    $('#cpfInput').inputmask('999.999.999-99', {
        showMask: false
    });


    // Aplicar o InputMask ao campo de telefone
    $('#telefoneInput').inputmask('(99) 9-9999-9999', {
        showMask: false
    });


    $("#confirmarInsertEmployeeBtn").click(function () {

        
        // Obtém os tokens dos selects
        var tokenEmpresa = $("#employeeCompanySelect").val();
        var tokenCargo = $("#cargo").val();
        var tokenDepartamento = $("#departamento").val();

        // Obtém o valor do select de gênero
        var genero = $("select[name='genero']").val();

        // Adiciona os tokens e outros dados diretamente aos dados do formulário
        var formData = new FormData($("#insert_employee_form")[0]);
        formData.append('empresa', tokenEmpresa);
        formData.append('cargo', tokenCargo);
        formData.append('departamento', tokenDepartamento);
        formData.append('genero', genero);

        // Envia a solicitação POST para o arquivo create.php com os dados do formulário
        $.ajax({
            type: "POST",
            url: "employee/controller/create.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                try {
                    const jsonData = typeof response === 'string' ? JSON.parse(response) : response;
          
                    function exibirAlerta(mensagem, sucesso = true) {
                      const msg = document.querySelector('.msg');
          
                      msg.className = 'msg alert';
          
          
                      msg.classList.add(sucesso ? 'alert-success' : 'alert-danger');
          
          
                      msg.textContent = mensagem;
                    }
          
                    if (jsonData.status === 200) {
                        $('#insert_employee_form :input').val('');
                      const msg = document.querySelector('.msg');
                      exibirAlerta(jsonData.msg, true);
                $.ajax({
                    type: 'GET',
                    url: 'employee/tableEmployee.php',
                    success: function (newTableHTML) {
                        $('#tabelaEmployees').replaceWith(newTableHTML);
                        $('#modalDeleteEmployee').modal('hide');
                    },
                    error: function (error) {
                        console.error('Erro ao obter dados da tabela:', error);
                    }
                });
            } else {
                const msg = document.querySelector('.msg');
                exibirAlerta(jsonData.msg, false);
    
              }
            } catch (error) {
              console.error(error);
            }
                
            },
            error: function (error) {
                // Lida com erros de requisição (se necessário)
                console.error(error);
            }
        });
    });
});








