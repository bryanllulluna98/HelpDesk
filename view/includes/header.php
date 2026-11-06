<?php
declare(strict_types=1);

$nombreUsuario = htmlspecialchars(($_SESSION["nombre"] ?? "") . " " . ($_SESSION["apellido"] ?? ""), ENT_QUOTES, "UTF-8");
$rolUsuario = htmlspecialchars($_SESSION["rol"] ?? "usuario", ENT_QUOTES, "UTF-8");
$correoUsuario = htmlspecialchars($_SESSION["correo"] ?? "", ENT_QUOTES, "UTF-8");
?>
<!-- Header Superior -->
<header class="site-header">
    <div class="container-fluid">
        <a href="../home/home.php" class="site-logo">
            <img class="hidden-md-down" src="../../public/img/logo-2.png" alt="HelpDesk Logo">
            <img class="hidden-lg-up" src="../../public/img/logo-2-mob.png" alt="HelpDesk Logo">
        </a>

        <div class="site-header-content">
            <div class="site-header-content-in">
                <div class="site-header-shown">
                    <div class="dropdown user-menu">
                        <button class="dropdown-toggle" id="dd-user-menu" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="../../public/img/avatar-sign.png" alt="Avatar">
                            <span class="lbl-name font-weight-bold ml-1"><?php echo $nombreUsuario; ?></span>
                            <span class="label label-custom label-pill ml-1"><?php echo strtoupper($rolUsuario); ?></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd-user-menu">
                            <a class="dropdown-item" href="#"><span class="font-icon glyphicon glyphicon-user"></span> Perfil (<?php echo $correoUsuario; ?>)</a>
                            <a class="dropdown-item" href="#"><span class="font-icon glyphicon glyphicon-question-sign"></span> Ayuda</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="../../controller/usuario.php?op=logout">
                                <span class="font-icon glyphicon glyphicon-log-out"></span> Cerrar Sesión
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="mobile-menu-left-overlay"></div>

