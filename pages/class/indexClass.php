<button id="buscarTurmas" class="btn btn-primary">Buscar Turmas</button>


<script>
$(document).ready(function() {
    $('#buscarTurmas').click(function() {
        $.ajax({
            url: 'class/controller/listClass.php', // Substitua 'class/controller/listClass.php' pelo caminho correto do seu arquivo PHP
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.status === 200) {
                    // Atualize sua tabela com os dados retornados
                    // Por exemplo, você pode recarregar a página para atualizar a tabela
                    location.reload();
                } else {
                    // Se houver um erro, mostre uma mensagem de erro
                    alert(response.msg);
                }
            },
            error: function() {
                // Se houver um erro na solicitação AJAX, mostre uma mensagem de erro
                alert('Erro na solicitação. Por favor, tente novamente mais tarde.');
            }
        });
    });
});
</script>
