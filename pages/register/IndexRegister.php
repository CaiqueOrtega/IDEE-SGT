<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="../../src/css/lib/bootstrap.min.css">
    <link rel="stylesheet" href="../../src/css/style.css">

    <title>Criar Conta</title>

   
    

</head>

<body>
<div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
        <div class="card shadow">
            <div class="card-header d-flex align-items-center shadow-lg justify-content-between text-white " style="background-color: #58af9b;">
                <h5><i class="fa-solid fa-clipboard-user" style="color: #ffffff;"></i> Preencha com suas informações pessoais</h5>
                <img src="../../src/img/logo.png" alt="logo" width="100">
            </div>

            <div class="card-body bg-white container-fluid">
                <div class="container py-5">
                    <form id="userRegister" method="POST" action="/IDEE-SGT/pages/register/controller/create.php">
                    <div class="msg"></div>
                <div class="row ">
                    
                    <div class="col-md-12 mb-3">
                        <label for="nome">Nome completo</label>
                        <input type="text" name="nome" class="form-control" placeholder="Nome Completo">
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="data_nascimento">Data de nascimento</label>

                        <input type="date" class="form-control" id="dateInput" max="2005-12-31" name="data_nascimento">
                    </div>

                    <div class="form-group col-md-6 mb-3">
                        <label for="telefone">Telefone</label>
                        <input id="telefoneInput" type="text" name="telefone" class="form-control" placeholder="(__) _-____-____">
                    </div>

                    <div class="form-group col-md-6 mb-3">
                        <label for="cpf">CPF</label>
                        <input id="cpfInput" type="text" name="cpf" class="form-control" placeholder="___.___.___-__">

                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="selectGender">Gênero</label>
                        <select id="inputGender" class="form-control">
                            <option selected>selecione o genêro...</option>
                            <option value="F">Feminino</option>
                            <option value="M">Masculino</option>
                        </select>
                    </div>

                    <div class="form-group col-md-6 mb-3">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="exemplo@email.com">
                    </div>

                    <div class="form-group col-md-6 mb-4">
                        <label for="inputPassoword">Senha</label>
                        <input type="password" name="senha" class="form-control" placeholder="•••••••••••••">
                    </div>
                </div>


                <br>

                <div class="pt-1 mb-4 d-flex justify-content-end">
                    <input type="submit" value="Confirmar" class="btn-login btn px-4 ">


                </div>
                    </form>
                </div>
            </div>
        </div>
    </div>





    <script src="https://kit.fontawesome.com/2ca71d1b50.js" crossorigin="anonymous"></script>
    <script src="../../src/js/lib/jquery.min.js"></script>
    <script src="../../src/js/lib/jquery.easing.min.js"></script>
    <script src="../../src/js/lib/bootstrap.min.js"></script>
    <script src="../../src/js/lib/popper.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.5/jquery.inputmask.min.js"></script>


    <?php include('../errorAndSuccessModal.php'); ?>



    <script>
        $(document).ready(function() {
            $('#cpfInput').inputmask('999.999.999-99', {
                showMask: false
            });


            $('#telefoneInput').inputmask('(99) 9-9999-9999', {
                showMask: false
            });

            $('#userRegister').submit(function(e) {
                e.preventDefault();
                console.log('Submit do formulário acionado');

                const formData = new FormData(this);
                
                
                formData.append('genero', $('#inputGender').val());

                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        console.log('Resposta do servidor:', data);
                        const msg = $('.msg');
                        try {
                            const jsonData = data;
                            if (jsonData.status === 200) {
                                $('#userRegister')[0].reset();

                                msg.remove();
                                $("#successMsg").text(jsonData.msg);
                                var successModal = new bootstrap.Modal(document.getElementById('statusSuccessModal'));
                                $('#modalMoreUpdateInscriptionTraining').css('z-index', '1040');


                                var loginLink = $('<a>').text('Entrar na conta')
                                    .addClass('btn btn-sm mt-3 btn-login')
                                    .attr('href', '/IDEE-SGT/pages/login/login.php');

                               
                                $('.btn-login').replaceWith(loginLink);
                                successModal.show();

                            } else {

                                msg.text(jsonData.msg);
                                msg.addClass('alert alert-danger');
                            }
                        } catch (error) {
                            console.error('Erro ao analisar a resposta do servidor:', error);
                        }
                    },
                    error: function(error) {
                        console.error('Erro na solicitação AJAX:', error);
                    }
                });
            });
        });
    </script>



</body>

</html>