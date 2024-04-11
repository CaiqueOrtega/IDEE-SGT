<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../src/css/lib/bootstrap.min.css">
    <link rel="stylesheet" href="../../src/css/style.css">
    <title>Entrar</title>
</head>

<body>
    <section class="vh-100 container py-5">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-xl-10 ">
                    <div class="card ">
                        <div class="row g-0">


                            <div class="col-md-6 col-lg-5 d-none d-md-flex justify-content-center align-items-center" style="background-color: #58af9b;">
                                <div class="text-white text-center mt-4  p-md-5 mx-md-4">
                                    <h4 class="mb-4">Ainda não tem uma conta?</h4>
                                    <a class="btn btn-outline-light btn-lg px-4 " href="../register/indexRegister.php">Cadastrar-se</a>
                                </div>
                            </div>


                            <div class="col-md-6 col-lg-7 d-flex align-items-center">
                                <div class="card-body p-4 p-lg-5 text-black">

                                    <form id="login" method="POST" action="/IDEE-SGT/pages/login/loginController.php">
                                        <div class="d-flex justify-content-between mb-3 pb-1">
                                            <span class="h5 fw-bold mb-0 mt-2" style="color: #58af9b;"><i class="fa fa-user" style="color: #58af9b;">
                                                </i> Entre na sua conta
                                            </span>
                                            <img class="mb-5" src="../../src/img/logo2.png" alt="logo" width="100px">
                                        </div>
                                        <div class="msg"></div>

                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="loginEmail">Endereço de Email</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-envelope" style="color: #58af9b;"></i></span>
                                                <input type="email" name="email" class="form-control form-control-lg" placeholder="example@email.com" />
                                            </div>
                                        </div>


                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="loginPassword">Entre com a Senha</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-lock" style="color: #58af9b;"></i></span>
                                                <input type="password" name="password" class="form-control form-control-lg" placeholder="•••••••••••••" />
                                            </div>
                                        </div>


                                        <div class="pt-1 mb-4 d-flex justify-content-end">
                                            <input type="submit" value="Entrar" class="btn-login btn px-4 ">


                                        </div>
                                        <a class="small text-muted" href="#!">Esqueceu sua Senha?</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <script src="../../src/js/lib/jquery.min.js"></script>
    <script src="../../src/js/lib/jquery.easing.min.js"></script>
    <script src="../../src/js/lib/bootstrap.min.js"></script>
    <script src="../../src/js/lib/popper.min.js"></script>
    <script src="https://kit.fontawesome.com/2ca71d1b50.js" crossorigin="anonymous"></script>

</body>

</html>

<script src="../../src/js/script.js"></script>