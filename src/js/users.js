function openModal(tokenUsuario) {
    var modalSelector = '#modalPermissao';
    $(modalSelector).remove();

    $.ajax({
        type: 'POST',
        url: '/IDEE-SGT/pages/users/modalUsers.php',
        data: {
            tokenUsuario: tokenUsuario
        },

        success: function (response) {
            if (response.status == 400) {
                var errorMessage = "Erro na solicitação: " + response.msg;

                console.log(errorMessage);
                $('#modalPermissao').css('z-index', 1040);

                $("#errorMsg").text(response.msg);
                var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));

                errorModal.show();
            } else {
                $('body').append(response);
                $('#modalPermissao').modal('show');
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });


}

$('.openModalPermissao').on('click', function (e) {
    e.preventDefault();
    var tokenUsuario = $(this).closest('tr').data('token');
    openModal(tokenUsuario);

    $(document).on('hidden.bs.modal', ' #statusErrorsModal, #statusSuccessModal', function () {
        $('#modalPermissao').css('z-index', '');
    });
});





$(document).on('click', '#confirmarUpdatePermissaoBtn', function () {
    var tokenTabela = $('#confirmarUpdatePermissaoBtn').data('token');
    var tokenSelect = $('#permissao-select').val();

    $.ajax({
        type: 'POST',
        url: '/IDEE-SGT/pages/users/controller/update.php',
        data: {
            tokenUsuario: tokenTabela,
            tokenPermissao: tokenSelect
        },
        dataType: 'json',
        success: function (response) {

            if (response.status == 400) {
                var errorMessage = "Erro na solicitação: " + response.msg;


                $('#modalPermissao').css('z-index', 1040);

                $("#errorMsg").text(response.msg);
                var errorModal = new bootstrap.Modal(document.getElementById('statusErrorsModal'));

                errorModal.show();
            } else {

                $.ajax({
                    type: 'POST',
                    url: '/IDEE-SGT/pages/users/modalUsers.php',
                    data: {
                        tokenUsuario: tokenTabela
                    },
                    dataType: 'html',
                    success: function (response) {
                        $('#permissao-select').html(response);
                        var permissaoAtualNomeContent = $(response).find('#permissaoAtualNome').html();
                        $('#permissaoAtualNome').html(permissaoAtualNomeContent); 
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });

                var errorMessage = "Erro na solicitação: " + response.msg;

                $('#modalPermissao').css('z-index', 1040);

                $("#successMsg").text(response.msg);
                var successModal = new bootstrap.Modal(document.getElementById('statusSuccessModal'));

                successModal.show();
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });


    $(document).on('hidden.bs.modal', ' #statusErrorsModal, #statusSuccessModal', function () {
        $('#modalPermissao').css('z-index', '');
    });
});