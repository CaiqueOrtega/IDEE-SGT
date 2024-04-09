

$('#cnpjInput').inputmask('99.999.999/9999-99', {
  showMask: false
});

$('#telefoneInput').mask('(00) 0-0000-0000', {
  showMask: false
});


$(document).ready(function () {
  $('#confirmarInsertCompanyBtn').click(function (e) {
    e.preventDefault();

    $.ajax({
      type: 'POST',
      url: 'company/controller/create.php',
      data: $('#company_insert').serialize()
    })
      .done(data => {
        try {
          const jsonData = data;

          function exibirAlerta(mensagem, sucesso = true) {
            const msg = document.querySelector('.msg');

            msg.className = 'msg alert';


            msg.classList.add(sucesso ? 'alert-success' : 'alert-danger');


            msg.textContent = mensagem;
          }

          if (jsonData.status === 200) {
            const msg = document.querySelector('.msg');
            exibirAlerta(jsonData.msg, true);

            document.getElementById('company_insert').reset();
            $.ajax({
              type: 'GET',
              url: 'company/tableCompany.php',
              success: function (newTableHTML) {


                $('#tableCompanys').replaceWith(newTableHTML);
                $('#modalDelete').modal('hide');
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
      })
      .fail(error => {
        console.error(error);
      });
  });
});




$(document).ready(function () {


  $('#consultarcnpj').click(function (e) {
    e.preventDefault();
    const cnpj = $('#cnpjInput').inputmask('unmaskedvalue');

    if (cnpj.trim() === '') {
      const msg = document.querySelector('.msg');
      msg.textContent = 'Por favor, preencha o campo CNPJ antes de consultar.';
      msg.classList.add('alert', 'alert-danger');
      return;
    }
    $.ajax({
      url: `https://www.receitaws.com.br/v1/cnpj/${cnpj}`,
      dataType: 'jsonp',
      success: function (data) {
        if (data.status === "ERROR") {
          const msg = document.querySelector('.msg');
          if (data.message && data.message === "CNPJ inválido") {
            msg.textContent = 'CNPJ inválido! Por favor, verifique o número digitado.';
          } else {
            msg.textContent = 'Erro ao consultar CNPJ.';
          }
          msg.classList.add('alert', 'alert-danger');
          return;
        }
      
   
        const telefone = data.telefone || '';  
      
        if (telefone && typeof telefone === 'string') {
          const telefoneValido = telefone.match(/\(\d{2}\) \d{4,5}-\d{4}/);
      
          if (telefoneValido) {
            $('#telefoneInput').val(telefoneValido);
          } else {
            $('#telefoneInput').val('Número de telefone não disponível');
          }
        } else {
          $('#telefoneInput').val('Número de telefone não disponível');
        }
      
        $('#razaoSocialInput').val(data.nome);
        $('#emailInput').val(data.email);
        $('#nomeFantasiaInput').val(data.fantasia);
      },
      error: function (error) {
        console.error(error);
      }
    });
  });
});


$(document).ready(function () {
  $('#modalDeleteCompany').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var tr = button.closest('tr');

    var token = tr.data('token');
    var nomeEmpresa = tr.find('.editable-cell[data-field="nome_fantasia"]').text();

    $(this).find('#companyName').text(nomeEmpresa);

    $('#confirmDeleteCompanyBtn').off('click').on('click', function () {
      $.ajax({
        type: 'POST',
        url: 'company/controller/delete.php',
        data: {
          token: token
        },
        success: function (response) {
          console.log(response);

          try {
            const jsonData = response;
            if (jsonData && jsonData.status === 200) {
              // Restante do seu código aqui
              $.ajax({
                type: 'GET',
                url: 'company/tableCompany.php',
                success: function (newTableHTML) {
                  $('#tableCompanys').html(newTableHTML);
                  $('#modalDeleteCompany').modal('hide');
                },
                error: function (error) {
                  console.error('Erro ao obter dados da tabela:', error);
                }
              });
            } else {
              console.error('Erro ao excluir empresa:', jsonData ? jsonData.msg : 'Resposta do servidor inválida');
            }
          } catch (error) {
            console.error('Erro ao analisar resposta JSON:', error);
          }
        },
        error: function (error) {
          console.error('Erro ao excluir empresa:', error);
        }
      });
    });
  });
});




$(document).ready(function () {
  var modoEdicao = false;
  var btnTexto = $('#editarBtn span');
  var iconElement = $('#editarBtn i');

  function habilitarEdicao() {
    $('.editable-cell').on('click', function () {
      var cell = $(this);
      var tr = cell.closest('tr');
      var token = tr.data('token');
      var currentCompanyName = tr.find('.editable-cell[data-field="nome_fantasia"]').text();

      var inputField = cell.find('input');
      if (inputField.length === 0) {
        var currentValue = cell.text().trim();

        if (cell.data('field') === 'cnpj') {
          cell.html('<input id="cnpjInput" type="text" class="form-control"  placeholder="__.___.___/____-__" value="' + currentValue + '">');
          $('#cnpjInput').inputmask('99.999.999/9999-99', {
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

        function updateCellAndSendData() {
          var newValue = inputField.val().trim();


          if (currentValue !== newValue) {
            cell.text(newValue);
            console.log('Novo Valor (Banco):', newValue);
            console.log('Token:', token);
            console.log(cell.data('field'));
            $('#campName').text(cell.data('field'));
            $('#campValue').text(newValue);
            $('#companyName').text(currentCompanyName);

            $('#modalUpdateCompany').modal('show');

            $('#confirmCompanyUpdateBtn').off('click').on('click', function () {


              // Chame a função para atualizar a tabela somente quando o botão de confirmação for clicado
              updateTable(cell, token, newValue);
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
            updateCellAndSendData();
          }
        });
      }

      inputField.focus();
    });
  }


  $('#editarBtn').on('click', function () {
    if ($('#tableCompanys tbody tr').length === 0) {

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

  function updateTable(cell, token, newValue) {
    $.ajax({
      method: 'POST',
      url: 'company/controller/update.php',
      data: {
        field: cell.data('field'),
        token: token,
        value: newValue
      },
      success: function (response) {
        console.log(response);
        if (response.status !== 200) {
          var errorMessage = "Erro na solicitação: " + response.msg;
          
          $('#modalUpdateCompany').modal('hide');

          console.log(errorMessage);
          $("#errorMsg").text(response.msg);
          var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));

          errorModal.show();
      } else {
          }
          
        $.ajax({
          type: 'GET',
          url: 'company/tableCompany.php',
          success: function (newTableHTML) {
            $('#tableCompanys').replaceWith(newTableHTML);
            habilitarEdicao();
            $('#modalUpdateCompany').modal('hide');
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
  $(document).off('click', '.modalCompanyInfo').on('click', '.modalCompanyInfo', function (event) {
    event.preventDefault();

    var button = $(this);
    var tr = button.closest('tr');
    var token = tr.data('token');

    // Restante do seu código AJAX permanece inalterado
    $.ajax({
      type: 'POST',
      url: 'positionAndDepartment/indexPositionAndDepartment.php', // Altere para o caminho correto do seu arquivo PHP
      data: { token: token },
      success: function (response) {
        // Remove qualquer conteúdo existente no modal
        $('#modalPositionAndDepartment').empty();

        // Adiciona o HTML da resposta ao modal
        $('#modalPositionAndDepartment').html(response);

        // Atualiza o nome da empresa
        var companyName = tr.find('.editable-cell[data-field="nome_fantasia"]').text().trim();
        $('#modalPositionAndDepartment').find('#companyName').text(companyName);
        $('#modalPositionAndDepartment').data('token', token);

        // Executa scripts dentro do modal, se houver
        $('#modalPositionAndDepartment').find('script').each(function () {
          eval($(this).text());
        });

        // Abre o modal
        $('#modalCompanyInfo').modal('show');
      },
      error: function (error) {
        console.error('Erro na solicitação AJAX:', error);
      }
    });
  });
});



$(document).ready(function () {
  function enviarSolicitacaoAjax(url, dados, inputLimpar, selectLimpar) {
    $.ajax({
      type: 'POST',
      url: url,
      data: dados,
      success: function (response) {
        try {
          const jsonData = response;

          function exibirAlerta(mensagem, sucesso = true) {
            const msg = $('#company_insert .msg');

            // Remover classes de alerta existentes
            msg.removeClass('alert-success alert-danger d-none');

            // Adicionar classe de alerta com base no sucesso
            msg.addClass(sucesso ? 'alert-success' : 'alert-danger');

            // Definir o texto da mensagem
            msg.text(mensagem);

            // Remover classe d-none para exibir a mensagem
            msg.removeClass('d-none');

            // Limpar o input
            $(inputLimpar).val('');

            // Limpar o select
            $(selectLimpar).val('');
          }

          if (jsonData.status === 200) {
            exibirAlerta(jsonData.msg, true);
          } else {
            exibirAlerta(jsonData.msg, false);
          }
        } catch (error) {
          console.error(error);
        }
      },
      error: function (error) {
        console.error('Erro na solicitação AJAX:', error);
      }
    });
  }

  $(document).on('click', '#modaldynamicModal #confirmarCargoOuDepartamentoBtn', function () {
    var tipoModal = $('#modaldynamicModal #cargooudepartamento').text();
    var input = '';
    var select = '';

    if (tipoModal === 'Cargo') {
      input = '#modaldynamicModal #cargoInput';
      select = '#modaldynamicModal #empresa-select-cargo';
    } else if (tipoModal === 'Departamento') {
      input = '#modaldynamicModal #departamentoInput';
      select = '#modaldynamicModal #empresa-select-departamento';
    }

    var dadoInput = $(input).val();
    var dadoSelect = $(select).find(':selected').attr('value');

    var dados = {
      dadoInput: dadoInput,
      token: dadoSelect
    };

    var url = tipoModal === 'Cargo'
      ? 'positionAndDepartment/controller/createCargo.php'
      : 'positionAndDepartment/controller/createDepartamento.php';

    enviarSolicitacaoAjax(url, dados, input, select);
  });

  $('#modaldynamicModal').on('hidden.bs.modal', function (e) {
    // Limpar a mensagem e redefinir as classes ao fechar o modal
    const msg = $('#modaldynamicModal #company_insert .msg');
    msg.text('');
    msg.removeClass('alert-success alert-danger').addClass('d-none');

    // Limpar o input e o select
    $('#modaldynamicModal #cargoInput').val('');
    $('#modaldynamicModal #departamentoInput').val('');
    $('#modaldynamicModal #empresa-select-cargo').val('');
    $('#modaldynamicModal #empresa-select-departamento').val('');
  });
});
