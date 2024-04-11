$(document).ready(function () {

    // Adiciona manipulador de eventos para limpar a pesquisa quando o usuário clica fora do campo
    $('#searchInput').on('input blur', function () {
        searchTable(); // Chama a função searchTable quando o usuário digita no campo
    });

    function removeAccents(str) {
        return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    }

    function searchTable() {
        var searchTerm = removeAccents($('#searchInput').val().toLowerCase());

        // Remove as marcações anteriores
        $('.data-row').unmark();

        if (searchTerm === '') {
            // Se o campo de pesquisa estiver vazio, exibe todas as linhas
            $('.data-row').show();
            $('.no-results-message').hide();
        } else {
            // Oculta todas as linhas e exibe apenas aquelas que correspondem à pesquisa
            $('.data-row').hide().filter(function() {
                // Use uma função de filtro personalizada para verificar a correspondência (ignorando acentos)
                return removeAccents($(this).text().toLowerCase()).includes(searchTerm);
            }).show();

            // Marca os termos de pesquisa nas linhas visíveis
            $('.data-row:visible').mark(searchTerm, {
                element: 'span',
                className: 'fuzzy-match',
            });

        }
    }

});



document.addEventListener("DOMContentLoaded", function () {
    const logoutButton = document.getElementById('logoutButton');

    if (logoutButton) {
        logoutButton.addEventListener('click', function (e) {
            e.preventDefault();
            window.location.href = "/IDEE-SGT/pages/login/loginController.php?logout";
        });
    }

});


//_______________________________________________________________________
document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('login');

    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    try {
                        const jsonData = JSON.parse(data);
                        if (jsonData.status === 200) {
                            localStorage.setItem('@token', 'true');
                            window.location.href = jsonData.redirect;
                        } else {
                            const msg = document.querySelector('.msg');
                            msg.textContent = jsonData.msg;
                            msg.classList.add('alert', 'alert-danger');
                        }
                    } catch (error) {
                        console.error(error);
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        });
    }
});


//_______________________________________________________________________


function loadContent(page) {
    localStorage.setItem('currentURL', page);

    $.ajax({
        url: page,
        type: 'POST',
        success: function (data) {
            $(document).off();
            $('#main-content').empty();
            $('#main-content').html(data);

            // Remover a classe 'active' de todos os itens
            $('.list-group-item').removeClass('active');

            // Encontrar o item correspondente à currentURL e adicionar a classe 'active'
            $('.list-group-item[data-url="' + page + '"]').addClass('active');
        },
        error: function () {
            console.error('Erro.');
        }
    });
}

$(document).ready(function () {
    $(document).on('click', '.list-group-item', function () {
        $('.list-group-item').removeClass('active');
        $(this).addClass('active');
    });

    var currentURL = localStorage.getItem('currentURL');

    if (currentURL) {
        loadContent(currentURL);
    }
});


  
  


