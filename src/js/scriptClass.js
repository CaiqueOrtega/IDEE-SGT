// Função auto-invocada para evitar conflitos de variáveis globais
(function ($) {
    // Função executada quando o documento estiver pronto
    $(document).ready(function () {
   
       // Use event delegation for dynamically loaded content
       $(document).on('click', '.openRemoveTurmaBtn', function (event) {
   
           event.preventDefault();
   
           // Exibe o modal de remoção de funcionário
           $('#modalRemoveAluno').modal('show');
           $('#modalClassInfo').css('z-index', 1040);
   
           // Obtém os dados do funcionário selecionado
           var tr = $(this).closest('tr');
           var tokenEmployee = tr.data('tokenemployee');
           var tokenInscription = tr.data('tokeninscription');
           var nomeAluno = tr.find('.editable-cell[data-field="nome_funcionario"]').text();
   
           console.log(tokenInscription);
   
           // Exibe o nome do funcionário no modal
           $('#employeeName').text(nomeFuncionario);
   
           // Configura o botão de confirmação de remoção
           $('#confirmRemoveEmployeeBtn').off('click').on('click', function () {
               // Envia uma solicitação para remover o funcionário
               $.ajax({
                   type: 'POST',
                   url: 'inscription/controller/removeEmployeeInscription.php',
                   data: {
                       tokenEmployee: tokenEmployee,
                       tokenInscription: tokenInscription
                   },
                   success: function (response) {
                       console.log('Resposta da exclusão:', response);
                       console.log(response);
   
                       var modalRemoveEmployee = $('#modalRemoveEmployee');
                       var modalInscriptionInfo = $('#modalInscriptionInfo');
   
                       if (response.status !== 200) {
                           // Exibe mensagem de erro em caso de falha
                           var errorMessage = "Erro na solicitação: " + response.msg;
   
                           modalRemoveEmployee.modal('hide');
                           modalInscriptionInfo.css('z-index', 1040);
   
                           console.log(errorMessage);
                           $("#errorMsg").text(response.msg);
                           var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));
   
                           errorModal.show();
                       } else {
                           // Exibe mensagem de sucesso em caso de sucesso
                           var errorMessage = "Erro na solicitação: " + response.msg;
   
                           modalRemoveEmployee.modal('hide');
                           modalInscriptionInfo.css('z-index', 1040);
   
                           console.log(errorMessage);
                           $("#successMsg").text(response.msg);
                           var successModal = new bootstrap.Modal(document.getElementById('statusSuccessModal'));
   
                           successModal.show();
                       }
   
                       // Atualiza a tabela de inscrições após a exclusão do funcionário
                       $.ajax({
                           type: 'POST',
                           url: 'inscription/modalInscription.php',
                           data: {
                               token: tokenInscription
                           },
                           success: function (newTableHTML) {
                               console.log('Nova tabela recebida com sucesso');
                               var extractedTable = $(newTableHTML).find('#tabelaEmployees').html();
   
                               $('#tabelaEmployees').html(extractedTable);
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
   
       // Fecha os modais quando são ocultados
       $(document).on('hidden.bs.modal', '#modalRemoveEmployee, #statusErrorsModal, #statusSuccessModal', function () {
           $('#modalInscriptionInfo').css('z-index', '');
       });
   });
   
   })(jQuery);
   
   
   
   
   (function ($) {
    
   $(document).on('click', '#btnReturnModalInscriptionInfo', function (event) {
       var tokenInscription = $('#modalMoreUpdateInscriptionTraining').data('tokeninscription');
   
       // Atualiza a tabela de inscrições ao retornar ao modal de informações
       $.ajax({
           type: 'POST',
           url: 'inscription/modalinscription.php',
           data: {
               token: tokenInscription
           },
           success: function (newTableHTML) {
               var extractedTable = $(newTableHTML).find('#tabelaEmployees').html();
               $('#tabelaEmployees').html(extractedTable);
           },
           error: function (error) {
               console.error('Erro ao obter dados da tabela:', error);
           }
       });
   });
   
   })(jQuery);
   
   
   
   
   
   
   
   
   (function ($) {
    $(document).ready(function () {
       // Use delegação de eventos para elementos dinâmicos
       $(document).on('click', '#updateInscricao', function () {
           var tokensFuncionarios = [];
           $("input[type=checkbox]:checked").each(function () {
               var tokenFuncionario = $(this).data('token');
               tokensFuncionarios.push(tokenFuncionario);
           });
   
           var tokenTraining = $('#modalMoreUpdateInscriptionTraining').data('tokentraining');
           var tokenInscription = $('#modalMoreUpdateInscriptionTraining').data('tokeninscription');
           var tokenCompany = $('#modalMoreUpdateInscriptionTraining').data('token');
           var page = "inscription";
   
           console.log(tokensFuncionarios);
   
           $.ajax({
               type: "POST",
               url: "inscription/controller/updateEmployeeInscription.php",
               data: {
                   token: tokenInscription,
                   tokensFuncionarios: tokensFuncionarios
               },
   
               success: function (response) {
                   var errorMessage = "Erro na solicitação: " + response.msg;
   
                   if (response.status !== 200) {
                       console.log(errorMessage);
                       $("#errorMsg").text(response.msg);
                       var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));
                       $('#modalMoreUpdateInscriptionTraining').css('z-index', '1040');
                       errorModal.show();
                   } else {
                       $.ajax({
                           type: 'POST',
                           url: 'training/tableEmployees.php',
                           data: {
                               token: tokenCompany,
                               tokenTraining: tokenTraining,
                               pages: page
                           },
                           success: function (data) {
                               if (data.status == 400) {
                                   var errorMessage = data.msg;
                                   $('#table-employee').text(errorMessage).addClass('font-weight-bold fs-5 mt-4');
                                   var linkText = ' voltar';
   
                                   var link = $('<a>').attr({
                                       'href': '#',
                                       'id': 'btnReturnModalInscriptionInfo',
                                       'data-bs-toggle': 'modal',             
                                       'data-bs-target': '#modalInscriptionInfo'  
                                   }).addClass('fs-5 mt-2').text(linkText); 
   
                                   $('#table-employee').append(link);
   
                               } else {
                                   $('#table-employee #btnReturnModalInscriptionInfo').remove();
                                   $('#table-employee').removeClass('font-weight-bold  mt-4');
                                   $('#table-employee').html(data);
                               }
   
                           }
   
   
                       });
   
   
                       $("#successMsg").text(response.msg);
                       var successModal = new bootstrap.Modal(document.getElementById('statusSuccessModal'));
                       $('#modalMoreUpdateInscriptionTraining').css('z-index', '1040');
                       successModal.show();
                   }
               },
               error: function (error) {
                   var errorMessage = "Erro na solicitação: " + error;
                   console.log(errorMessage);
               }
           });
       });
   
       $(document).on('hidden.bs.modal', '#statusErrorsModal, #statusSuccessModal', function () {
           $('#modalMoreUpdateInscriptionTraining').css('z-index', '');
       });
   });
   
   
   })(jQuery);
   
   
   
   (function ($) {
   
    $(document).on('click', '#btnOpenModalUpdateInscriptionEmployees', function (event) {
       var tokenTraining = $(this).data('tokentraining');
       var token = $(this).data('token');
       var page = 'inscription';
   
       if (token !== "") {
           // Envia o token para o arquivo PHP usando AJAX
           $.ajax({
               type: 'POST',
               url: 'training/tableEmployees.php', // Atualize com o caminho correto
               data: {
                   token: token,
                   tokenTraining: tokenTraining,
                   pages: page
               },
               success: function (response) {
                   if (response.status == 400) {
                       $("#errorMsg").text(response.msg);
                       var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));
                       $('#modalInscriptionInfo').css('z-index', '1040');
                       errorModal.show();
                   } else {
                       // Se a resposta não contiver erros, preencha a tabela e abra o modal
                       $('#table-employee').html(response);
                       openModal();
                   }
               },
               error: function (xhr, status, error) {
                   // Trate erros de requisição AJAX aqui, se necessário
                   console.error("Erro na requisição AJAX:", status, error);
               }
           });
       } else {
           // Se o token estiver vazio, limpe a tabela
           $('#table-employee').empty();
           openModal();
       }
   
       function openModal() {
           $('#modalInscriptionInfo').modal('hide');
           var modalMoreUpdateInscription = new bootstrap.Modal(document.getElementById('modalMoreUpdateInscriptionTraining'));
           modalMoreUpdateInscription.show();
       }
   
       $(document).on('hidden.bs.modal', '#statusErrorsModal', function () {
           $('#modalInscriptionInfo').css('z-index', '');
       });
   });
   
   
   })(jQuery);
   
   
   
   
   (function ($) {
    $(document).ready(function () {
       $('.modalInscriptionInfo').click(function () {
           const token = $(this).closest('tr').data('token');
   
   
           // Realizar a solicitação AJAX
           $.ajax({
               url: 'inscription/modalInscription.php',
               type: 'POST', // Alterar para POST
               data: {
                   token: token
               }, // Incluir o parâmetro token no corpo da solicitação
               success: function (response) {
                   // Inserir o conteúdo retornado no modal
                   $('#modalInscriptionInfoContent').html(response);
   
                   $('#modalInscriptionInfo').modal('show');
   
               },
               error: function (error) {
                   console.log('Erro na solicitação AJAX:', error);
               }
           });
       });
   });
   })(jQuery);
   
   
   
   (function ($) {
    
   $(document).ready(function () {
       $('#modalDeleteInscription').on('show.bs.modal', function (event) {
           var button = $(event.relatedTarget);
           const token = button.closest('tr').data('token');
   
           $('#confirmDeleteBtn').off('click').on('click', function () {
               // Agora você pode usar o token aqui
               $.ajax({
                   type: 'POST',
                   url: 'inscription/controller/delete.php',
                   data: {
                       token: token
                   },
                   success: function (response) {
                       console.log(response);
                       console.log("esse" + token);
                       // Agora, passando o token como parâmetro
                       reloadTable(token);
                   },
                   error: function (error) {
                       console.error('Erro ao excluir funcionário:', error);
                   }
               });
           });
       });
   
       function reloadTable(token) {
           $.ajax({
               type: 'POST',
               url: 'inscription/tableInscription.php',
               data: {
                   token: token
               },
               success: function (newTableHTML) {
                   $('#tabelaInscriptions').replaceWith(newTableHTML);
                   $('#modalDeleteInscription').modal('hide');
               },
               error: function (error) {
                   console.error('Erro ao obter dados da tabela:', error);
               }
           });
       }
   });
   })(jQuery);
   
   
   
   
     $(document).ready(function() {
   
       $("#relatorioBtnInscription").click(function() {
   
         if ($('#tabelaInscriptions tbody tr').length === 0) {
   
           $("#errorMsg").text('A tabela não contém dados. Não é possível gerar o relatório.');
           var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));
   
           errorModal.show();
         } else {
   
           window.open('relatorio/indexInscription.php', '_blank');
         }
       });
     });
