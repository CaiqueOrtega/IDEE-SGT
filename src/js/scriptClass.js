$(document).ready(function () {
    // Abrir modal de deleção e passar o token
    $(document).on('click', '.openModalDeleteClass', function () {
        var token = $(this).closest('tr').data('token');
        console.log('Token from table row:', token);
        
        $('#modalDeleteClass').data('token', token);
        console.log('Token set in modal:', $('#modalDeleteClass').data('token'));

        $('#modalDeleteClass').modal('show');
    });

    // Confirmar deleção e enviar o token para o servidor
    $('#confirmDeleteBtn').on('click', function () {
        var token = $('#modalDeleteClass').data('token');
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
                            $('#tableClass').replaceWith(newTableHTML);
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
