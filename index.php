<?php
    
    require_once("config/conexion.php");
    if(isset($_POST["enviar"]) && $_POST["enviar"]=="si"){
        require_once("model/Usuarios.php");
        $usuario = new Usuarios();
        $usuario->login();
    } 
?>

<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>GESTOR DE CONTRASENAS-TECNOSTAR</title>

	<link href="img/favicon.144x144.png" rel="apple-touch-icon" type="image/png" sizes="144x144">
	<link href="img/favicon.114x114.png" rel="apple-touch-icon" type="image/png" sizes="114x114">
	<link href="img/favicon.72x72.png" rel="apple-touch-icon" type="image/png" sizes="72x72">
	<link href="img/favicon.57x57.png" rel="apple-touch-icon" type="image/png">
	<link href="img/favicon.png" rel="icon" type="image/png">
	<link href="img/favicon.ico" rel="shortcut icon">

    <link rel="stylesheet" href="public/css/separate/pages/login.min.css">
    <link rel="stylesheet" href="public/css/lib/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="public/css/lib/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/main.css">
</head>
<body>
       <div class="page-center"> 
         <div class="page-center-in">
              <div class="container-fluid">
                <form class="sign-box" action="" method="post" id="form_login" >
                    <div class="sign-avatar">
                        <img src="public/img/avatar-sign.png" alt="">
                    </div>
                    <header class="sign-title">INICIAR SESION</header>
                    <div class="form-group">
                        <input type="text" id="correo" name="correo" class="form-control" placeholder="E-Mail or Phone"/>
                    </div>
                    <div class="form-group">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password"/>
                    </div>
                    <div class="form-group">
                        <div class="float-right reset">
                            <a href="reset-password.html">Cambiar Contraseña</a>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-rounded">Ingresar</button>
                    <input type="hidden" name="enviar" value="si">
                    <p class="sign-note">¿No tienes una cuenta? <a href="sign-up.html">Registrarme</a></p>
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
        // Inicialización
        $('.page-center').matchHeight({
            target: $('html')
        });

        $(window).resize(function(){
            setTimeout(function(){
                // FORMA CORRECTA DE REMOVER: pasar el string 'remove'
                $('.page-center').matchHeight('remove'); 
                
                // Re-aplicar
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
