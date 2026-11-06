<?php
declare(strict_types=1);

require_once(__DIR__ . "/config/conexion.php");

// Si el usuario ya cuenta con una sesión activa, redirigir al panel principal
if (isset($_SESSION["id_usuario"])) {
    header("Location: " . Conectar::ruta() . "view/home/home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>HelpDesk - Iniciar Sesión</title>

	<link href="public/img/favicon.144x144.png" rel="apple-touch-icon" type="image/png" sizes="144x144">
	<link href="public/img/favicon.114x114.png" rel="apple-touch-icon" type="image/png" sizes="114x114">
	<link href="public/img/favicon.72x72.png" rel="apple-touch-icon" type="image/png" sizes="72x72">
	<link href="public/img/favicon.57x57.png" rel="apple-touch-icon" type="image/png">
	<link href="public/img/favicon.png" rel="icon" type="image/png">
	<link href="public/img/favicon.ico" rel="shortcut icon">

    <link rel="stylesheet" href="public/css/separate/pages/login.min.css">
    <link rel="stylesheet" href="public/css/lib/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="public/css/lib/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/main.css">
</head>
<body>
    <div class="page-center"> 
        <div class="page-center-in">
            <div class="container-fluid">
                <form class="sign-box" action="controller/usuario.php?op=login" method="post" id="form_login">
                    <div class="sign-avatar">
                        <img src="public/img/avatar-sign.png" alt="Avatar">
                    </div>
                    <header class="sign-title">INICIAR SESIÓN</header>

                    <?php if (isset($_GET["m"])): ?>
                        <?php if ($_GET["m"] === "1"): ?>
                            <div class="alert alert-danger alert-fill alert-close alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <i class="font-icon font-icon-warning"></i>
                                <strong>Error:</strong> Correo o contraseña incorrectos.
                            </div>
                        <?php elseif ($_GET["m"] === "2"): ?>
                            <div class="alert alert-warning alert-fill alert-close alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <i class="font-icon font-icon-warning"></i>
                                <strong>Atención:</strong> Por favor complete todos los campos.
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <div class="form-group">
                        <input type="email" id="correo" name="correo" class="form-control" placeholder="Correo electrónico" required />
                    </div>
                    <div class="form-group">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" required />
                    </div>
                    <div class="form-group">
                        <div class="float-right reset">
                            <a href="reset-password.html">¿Olvidaste tu contraseña?</a>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-rounded">Ingresar</button>
                    <p class="sign-note">¿No tienes una cuenta? <a href="view/NuevoRegistro/N_registro.php">Registrarme</a></p>
                </form>
            </div>
        </div>
    </div>

    <script src="public/js/lib/jquery/jquery.min.js"></script>
    <script src="public/js/lib/tether/tether.min.js"></script>
    <script src="public/js/lib/bootstrap/bootstrap.min.js"></script>
    <script src="public/js/plugins.js"></script>
    <script type="text/javascript" src="public/js/lib/match-height/jquery.matchHeight.min.js"></script>
    <script>
        $(function() {
            $('.page-center').matchHeight({
                target: $('html')
            });

            $(window).resize(function(){
                setTimeout(function(){
                    $('.page-center').matchHeight('remove'); 
                    $('.page-center').matchHeight({
                        target: $('html')
                    });
                }, 100);
            });
        });
    </script>
    <script src="public/js/app.js"></script>
</body>
</html>
