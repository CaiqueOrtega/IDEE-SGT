<div class="modal-footer">
    <button class="btn btn-danger" id="btnBuscarFicha" data-token="<?php echo $tokenInscricao; ?>">Confirmar</button>
</div>


<script>
    $(document).ready(function(){
        $('#btnBuscarFicha').click(function(){
            var tokenFicha = $(this).data('token');

            $.ajax({
                url: 'class/controller/createClass.php', // Caminho do script PHP para buscar os dados da ficha de inscrição
                method: 'POST',
                dataType: 'json',
                data: { tokenFicha: tokenFicha },
                success: function(response) {
                    // Manipule a resposta da requisição aqui conforme necessário
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
