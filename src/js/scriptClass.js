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
//relatorio de turmas e treinamentos
//-------------------------------------------------------------------------------------------------------------------------------------------------

$(document).ready(function () {
    // Atualizar o atributo data-filtrorelatorio do botão quando a seleção mudar
    $('#filtroSelect').change(function () {
        var selectedValue = $(this).val();

        $('#relatorioBtnClass').data('filtrorelatorio', selectedValue);
    });

    $("#relatorioBtnClass").click(function () {
        if ($('#tableClass tbody tr').length === 0) {
            $("#errorMsg").text('A tabela não contém dados. Não é possível gerar o relatório.');
            var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));
            errorModal.show();
        } else {
            // Obter o valor do atributo data-filtrorelatorio
            var filtro = $(this).data('filtrorelatorio');
            // Abrir o relatório com o filtro correspondente
            window.open('relatorio/indexClass.php?filtro=' + filtro, '_blank');
        }
    });
});

//-------------------------------------------------------------------------------------------------------------------------------------------------
//relatorio de notas e Frequencia dos Alunos
//-------------------------------------------------------------------------------------------------------------------------------------------------

$(document).ready(function () {
    // Atualizar o atributo data-filtrorelatorio do botão quando a seleção mudar
    $('#filtroSelectStudents').change(function () {
        var selectedValue = $(this).val();
        $('.relatorioBtnStudents').data('filtrorelatorio', selectedValue);
    });

    // Usar um evento de clique específico para os botões dentro do modal
    $(".relatorioBtnStudents").click(function () {
        if ($('#tableClass tbody tr').length === 0) {
            $("#errorMsg").text('A tabela não contém dados. Não é possível gerar o relatório.');
            var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));
            errorModal.show();
        } else {
            // Obter o valor do atributo data-filtrorelatorio
            var filtro = $(this).data('filtrorelatorio');
            var turmaId = $(this).data('turmarelatorioid');
            // Abrir o relatório com o filtro correspondente
            window.open('relatorio/indexStudents.php?turma_id=' + turmaId + '&filtro=' + filtro, '_blank');
        }
    });
});




//-------------------------------------------------------------------------------------------------------------------------------------------------
//certificado de conclusao
//-------------------------------------------------------------------------------------------------------------------------------------------------

$(document).ready(function () {

    $(document).on("click", ".certificadoBtnStudents", function () {
        var alunoId = $(this).data('alunocertificadoid');
        var turmaId = $(this).data('turmacertificadoid');
        var alunoMedia = $(this).data('alunomedia');
        var alunoFrequencia = $(this).data('alunofrequencia');
        var alunostaus = $(this).data('alunostatus'); // Certifique-se de que este é o atributo correto
        var turmaDataConclusao = $(this).data('turmadataconclusao');

        console.log('id do aluno:', alunoId);
        console.log('id da turma:', turmaId);
        console.log('media do aluno:', alunoMedia);
        console.log('frequencia do aluno:', alunoFrequencia);
        console.log('data de conclusao da turma:', turmaDataConclusao);
        console.log('status do aluno:', alunostaus);

        // Verificar se o status do aluno é diferente de "ativo"
        if (alunostaus !== 'ativo') {
            $("#errorMsg").text('O status do aluno não é ativo. Não é possível gerar o certificado.');
            var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));
            errorModal.show();
            return; // Impedir que o código continue
        }

        // Verificar se a média do aluno é menor que 6
        if (alunoMedia < 6) {
            $("#errorMsg").text('A média do aluno é menor que 6. Não é possível gerar o certificado.');
            var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));
            errorModal.show();
            return; // Impedir que o código continue
        }

        // Verificar se a frequência do aluno é menor que 60
        if (alunoFrequencia < 60) {
            $("#errorMsg").text('A frequência do aluno é menor que 60%. Não é possível gerar o certificado.');
            var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));
            errorModal.show();
            return; // Impedir que o código continue
        }

        // Fazer a solicitação AJAX se as verificações passarem
        $.ajax({
            url: 'class/controller/updateClass.php',
            type: 'POST',
            data: {
                turmaId: turmaId,
                turmaDataConclusao: turmaDataConclusao
            },
            success: function (response) {
                // Supondo que a resposta do back-end contenha o URL do certificado gerado
                window.open('relatorio/indexCertificado.php?turmaId='  + turmaId + '&aluno=' + alunoId, '_blank');
                location.replace(`?modalStudents=modalStudents-${turmaId}`);
            },
            error: function (xhr, status, error) {
                // Exibir uma mensagem de erro se a solicitação AJAX falhar
                $("#errorMsg").text('Ocorreu um erro ao gerar o certificado. Por favor, tente novamente.');
                var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));
                errorModal.show();
            }
        });
    });

});

//-------------------------------------------------------------------------------------------------------------------------------------------------
//Atulizar Notas dos Alunos
//-------------------------------------------------------------------------------------------------------------------------------------------------
$(document).ready(function () {
    var modoEdicao = false;
    var btnTexto = $('.editarBtnNota span');
    var iconElement = $('.editarBtnNota i');

    function habilitarEdicao() {
        $('.editable-cell').on('click', function () {
            var cell = $(this);
            var tr = cell.closest('tr');

            var inputField = cell.find('input');
            if (inputField.length === 0) {
                var currentValue = cell.text().trim();
                var inputId = '';
                var inputMask = '9[9],9'; // Define a máscara como '9[9],9' para permitir notas de 0,0 a 10,0

                if (cell.data('field') === 'nota_pratica') {
                    inputId = 'nota_praticaInput';
                    cell.html('<input id="' + inputId + '" type="text" class="form-control" placeholder="__._" value="' + currentValue + '">');
                } else if (cell.data('field') === 'nota_teorica') {
                    inputId = 'nota_teoricaInput';
                    cell.html('<input id="' + inputId + '" type="text" class="form-control" placeholder="__._" value="' + currentValue + '">');
                } else {
                    cell.html('<input type="text" class="form-control" value="' + currentValue + '">');
                }

                inputField = cell.find('input');
                inputField.inputmask(inputMask, {
                    showMaskOnHover: false,
                    showMaskOnFocus: false,
                    rightAlign: false,
                    removeMaskOnSubmit: true
                });

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
                                notas.push({ aluno_id, nota_pratica, nota_teorica });
                            }

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

                inputField.focus();
            }
        });
    }

    $('.editarBtnNota').on('click', function () {
        if ($('#tabelaNota tbody tr').length === 0) {
            $("#error-container").text('A tabela não contém dados. Não é possível editar.').removeClass('d-none');
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
            dataType: 'json',
            success: function (response) {
                if (response.status !== 200) {
                    var errorMessage = "Erro na solicitação: " + response.msg;
                    $("#error-container").text(response.msg).removeClass('d-none');
                } else {
                    $("#successMsg").text(response.msg);
                    var successModal = new bootstrap.Modal(document.getElementById('statusSuccessModal'));
                    successModal.show();
                    location.replace(`?modalStudents=modalStudents-${turmaid}`);
                }
            },
            error: function (error) {
                console.error(error);
                $("#error-container").text('Ocorreu um erro ao tentar atualizar as notas.').removeClass('d-none');
            }
        });
    }
});

//-------------------------------------------------------------------------------------------------------------------------------------------------
$(document).ready(function () {
    var modoEdicao = false;
    var btnTexto = $('.editarBtnFrequencia span');
    var iconElement = $('.editarBtnFrequencia i');
    var dias = $('#dias-container').data('dias'); // Recupera o valor de dias do atributo de dados

    function habilitarEdicao() {
        // Habilita os checkboxes para edição
        $('.form-check-input').prop('disabled', false);
    }

    function desabilitarEdicao() {
        // Desabilita os checkboxes após edição
        $('.form-check-input').prop('disabled', true);
    }

    $('.editarBtnFrequencia').on('click', function () {
        modoEdicao = !modoEdicao;

        if (modoEdicao) {
            // Modo de edição ativado
            iconElement.removeClass().addClass('bi bi-box-arrow-right');
            btnTexto.text('Sair da Edição');
            $('#alert').removeClass('d-none');

            // Habilita a edição nas células
            habilitarEdicao();
        } else {
            // Modo de edição desativado
            iconElement.removeClass().addClass('bi bi-pen-fill');
            btnTexto.text('Editar');
            $('#alert').addClass('d-none');

            // Desabilita a edição nos checkboxes
            desabilitarEdicao();
        }
    });

    // Evento para capturar a alteração nos checkboxes
    $('.form-check-input').on('change', function () {
        var checkbox = $(this);
        var isChecked = checkbox.is(':checked');

        // Aqui você pode realizar ações adicionais quando o checkbox é marcado ou desmarcado
        console.log('Checkbox ' + checkbox.attr('id') + ' está ' + (isChecked ? 'marcado' : 'desmarcado'));

        // Você pode adicionar lógica para enviar dados ou atualizar a interface conforme necessário
    });

    // Botão de confirmação
    $('.confirmStudentUpdateBtnFrequencia').on('click', function () {
        let turmaid = $(this).data("turmaid");
        let inputs = $(`.frequencia-aluno-${turmaid}`);

        let frequencias = [];
        let alunos = [];
        for (let input of inputs) {
            let checado = input.checked;

            let turma_id = input.dataset.turmaid;
            let aluno_id = input.dataset.alunoid;
            let dia = input.dataset.dia;

            frequencias.push({ aluno_id, turma_id, dia, presenca: checado ? "S" : "N" });

            if (!alunos.includes(aluno_id)) {
                alunos.push(aluno_id);
            }
        }

        let alunosPresencao = [];

        for (let aluno_id of alunos) {
            let presencas = [];
            let turma_id = null;
            for (let frequencia of frequencias) {
                let { presenca, dia } = frequencia;
                turma_id = frequencia.turma_id;
                if (frequencia.aluno_id == aluno_id) {
                    presencas.push({ presenca, dia });
                }
            }

            alunosPresencao.push({
                aluno_id,
                turma_id,
                presencas
            });
        }

        updateTableFrequencia({ alunos: alunosPresencao }, turmaid);
    });

    function updateTableFrequencia(data, turmaid) {
        console.log(data);
        $.ajax({
            method: 'POST',
            url: 'class/controller/updateClass.php',
            data,
            success: function (response) {
                if (response.status !== 200) {
                    var errorMessage = "Erro na solicitação: " + response.msg;

                    console.log(errorMessage);
                    $("#errorMsg").text(response.msg);

                    errorModal.show();
                } else {
                    location.replace(`?modalStudents=modalStudents-${turmaid}`)
                }
            },
            error: function (error) {
                console.error(error);
            }
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
                btnTexto.text('Sair da Edição');
                $('#alert').removeClass('d-none');

                // Habilita a edição nas células
                habilitarEdicao();
            } else {
                // Modo de edição desativado
                iconElement.removeClass().addClass('bi bi-pen-fill');
                btnTexto.text('Editar');
                $('#alert').addClass('d-none');

                // Remova qualquer lógica associada ao clique nas células durante o modo de edição
                $('.editable-cell').off('click');
            }
        }
    });
});

