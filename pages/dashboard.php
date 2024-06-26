<?php
require_once('../api/private/auth.php');

$permissao = $_SESSION['login']['permissao'];
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina Inicial</title>
    <link rel="stylesheet" href="../src/css/lib/bootstrap.min.css">
    <link rel="stylesheet" href="../src/css/dashboardStyle.css">
    <link rel="stylesheet" href="../src/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="../src/img/logo1.png">
   


    <script src="../src/js/lib/jquery.min.js"></script>
    <script src="../src/js/lib/jquery.easing.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.5/jquery.inputmask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="../src/js/lib/popper.min.js"></script>
    <script src="../src/js/lib/bootstrap.min.js"></script>
    <script src="../src/js/lib//jquery.mark.min.js"></script>


</head>

<body>

    <header>
        <nav id="sidebarCollapse" class="collapse d-lg-block sidebar bg-white">
            <div class="position-sticky mt-3">
                <div id="logo-sidebar" class="row px-2">
                    <a class="d-flex aling-items-center justify-content-center" href="#">
                        <img src="../src/img/logo2.png" alt="Logo" width="100">
                    </a>
                </div>
                <hr id="logo-sidebar" class=" ms-3 me-3">

                <div class="list-group list-group-flush mx-2 mt-4">
                <a href="#" id="default-page" class="list-group-item list-group-item-action py-3" onclick="loadContent('/IDEE-SGT/pages/home/indexHome.php')" data-url="/IDEE-SGT/pages/home/indexHome.php">
    <i class="bi-house fa-fw me-3"></i>Pagina Inicial
</a>



                    <?php if ($permissao == 1 || $permissao == 4) { ?>
                        <a href="#" class="list-group-item list-group-item-action py-3" onclick="loadContent('/IDEE-SGT/pages/users/indexUsers.php')" data-url="/IDEE-SGT/pages/users/indexUsers.php">
                            <i class="bi bi-person me-3"></i>Usuários
                        </a>
                    <?php } ?>

                    <a href="#submenuTreinamentos" class="list-group-item list-group-item-action py-3" data-bs-toggle="collapse">
                        <i class="bi bi-stickies fa-fw me-3"></i>Treinamentos
                    </a>
                    <div id="submenuTreinamentos" class="collapse list-group list-group-flush  mb-3 ">
                    <?php if ($permissao == 1 || $permissao == 4) { ?>
                    <a href="#" class="list-group-item list-group-item-action py-2" onclick="loadContent('/IDEE-SGT/pages/training/indexTraining.php')" data-url="/IDEE-SGT/pages/training/indexTraining.php">
                            Controle de Treinamentos
                        </a>
                        <?php } ?>

                        <a href="#" class="list-group-item list-group-item-action py-2" onclick="loadContent('/IDEE-SGT/pages/training/listTraining.php')" data-url="/IDEE-SGT/pages/training/listTraining.php">
                            Lista de Treinamentos
                        </a>
                        <a href="#" class="list-group-item list-group-item-action py-2" onclick="loadContent('/IDEE-SGT/pages/inscription/indexInscription.php')" data-url="/IDEE-SGT/pages/inscription/indexInscription.php">
                            Inscrições Pendentes
                        </a>

                    </div>

                    <a href="#" class="botao-menu-lateral-turmas list-group-item list-group-item-action py-3" onclick="loadContent('/IDEE-SGT/pages/class/indexClass.php')" data-url="/IDEE-SGT/pages/class/indexClass.php">
                        <i class="bi bi-book fa-fw me-3"></i>Turmas
                    </a>

                    <a href="#" class="list-group-item list-group-item-action py-3" onclick="loadContent('/IDEE-SGT/pages/company/indexCompany.php')" data-url="/IDEE-SGT/pages/company/indexCompany.php">
                        <i class="bi bi-buildings fa-fw me-3"></i>Empresas
                    </a>

                    <a href="#" class="list-group-item list-group-item-action py-3" onclick="loadContent('/IDEE-SGT/pages/employee/indexEmployee.php')" data-url="/IDEE-SGT/pages/employee/indexEmployee.php">

                        <i class="bi bi-people me-3"></i>Funcionários
                    </a>


                    <a href="#" class="list-group-item list-group-item-action py-3 disabled" onclick="loadContent('/IDEE-SGT')" data-url="/IDEE-SGT">
                        <i class="bi bi-list-nested me-3"></i>Outros
                    </a>



                </div>
            </div>
        </nav>


        <nav id="main-navbar" class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
            <div class="container-fluid p-2 ">

                <button class="navbar-toggler me-3" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapse" aria-controls="sidebarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon "></span>
                </button>


                <form class="d-none d-md-flex formSearch input-group w-auto my-auto">
                    <span class="input-group-text text-white" style="background-color: #59af9b;"><i class="bi bi-search"></i></span>
                    <input autocomplete="on" type="search" class="form-control" id="searchInput" placeholder='Digite o que deseja pesquisar...' style="min-width: 300px" />
                </form>


                <?php include('./notificationInscription.php'); ?>
                <div class="flex-shrink-0 dropdown ms-auto me-2">
                    <a href="#" class="d-block  text-decoration-none dropdown-toggle-no-caret" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false" style="color: #79c5b3;">
                        <span class="position-relative">
                            <i class="bi bi-bell-fill "></i>
                            <?php if ($permissao == 1 || $permissao == 4 && $numLinhas != 0) { ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge-notification bg-danger" style="font-size: 0.5em;">
                                    <?php echo $numLinhas; ?>
                                </span>
                            <?php } ?>
                        </span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser2">
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="collapse" data-bs-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">Notificações</a>
                        </li>

                        <?php if ($permissao == 1 || $permissao == 4 && $numLinhas != 0) { ?>
                            <li>
                                <a class="text-decoration-none text-dark" href="#" onclick="loadContent('/IDEE-SGT/pages/inscription/indexInscription.php')" data-url="/IDEE-SGT/pages/inscription/indexInscription.php">
                                    <div class="collapse collapse-horizontal" id="collapseWidthExample">
                                        <div id="notificacao" class="card d-flex flex-row card-body m-2 border-end-0 border-start-0" style="width: 300px;">

                                            <span>Nova inscrição pendente!</span> <span>
                                                <span class="position-absolute  start-100 translate-middle badge rounded-pill badge-notification bg-danger" style="font-size: 10px;">
                                                    <?php echo $numLinhas; ?>
                                                </span>

                                            </span>
                                            <hr>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        <?php } ?>

                        <li>
                            <a class="dropdown-item" href="#">Chat e Suporte</a>
                        </li>
                    </ul>
                </div>


                <style>
                    #notificacao:hover {
                        background-color: rgb(252, 252, 252) !important;

                    }
                </style>

                <script>
                    // Adiciona um manipulador de eventos de clique para impedir o fechamento do dropdown
                    document.querySelectorAll('.dropdown-menu .dropdown-item').forEach(function(item) {
                        item.addEventListener('click', function(e) {
                            e.stopPropagation(); // Impede a propagação do evento de clique
                        });
                    });
                </script>

                <div class="vr mx-3"></div>

                <div class="flex-shrink-0 dropdown d-flex me-4">
                    <a href="#" class="d-block text-decoration-none dropdown-toggle-no-caret text-dark" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-fill fs-5" style="color: #79c5b3;"></i>
                            <span class="d-none d-md-block ms-2" id="dashboard-name"><?php echo $_SESSION['login']['nome'] ?></span>
                        </div>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser2">
                        <li><a class="dropdown-item" href="#">Minha Conta</a></li>
                        <li><a class="dropdown-item" href="#">Configuraçoes</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#staticBackdrop" href="#">Sair</a></li>
                    </ul>

                </div>
            </div>

        </nav>

    </header>

    <main style="margin-top: 58px">
        <div id="main-content" class="container pt-4">

        </div>
    </main>




    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Fazer Logout</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Deseja realmente Sair?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button id="logoutButton" type="button" class="btn btn-login ">Confirmar</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://kit.fontawesome.com/2ca71d1b50.js" crossorigin="anonymous"></script>
    <script src="../src/js/script.js"></script>


</body>

</html>