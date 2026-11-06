<?php
declare(strict_types=1);

require_once(__DIR__ . "/../includes/seguridad.php");

$nombreCompleto = htmlspecialchars($_SESSION["nombre"] . " " . $_SESSION["apellido"], ENT_QUOTES, "UTF-8");
$rol = htmlspecialchars($_SESSION["rol"] ?? "usuario", ENT_QUOTES, "UTF-8");
$correo = htmlspecialchars($_SESSION["correo"] ?? "", ENT_QUOTES, "UTF-8");
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>HelpDesk - Panel Principal</title>

	<link href="../../public/img/favicon.ico" rel="shortcut icon">
	<link rel="stylesheet" href="../../public/css/lib/font-awesome/font-awesome.min.css">
	<link rel="stylesheet" href="../../public/css/lib/bootstrap/bootstrap.min.css">
	<link rel="stylesheet" href="../../public/css/main.css">
</head>
<body class="with-side-menu">

	<!-- Header Superior -->
	<header class="site-header">
	    <div class="container-fluid">
	        <a href="home.php" class="site-logo">
	            <img class="hidden-md-down" src="../../public/img/logo-2.png" alt="HelpDesk Logo">
	            <img class="hidden-lg-up" src="../../public/img/logo-2-mob.png" alt="HelpDesk Logo">
	        </a>

	        <div class="site-header-content">
	            <div class="site-header-content-in">
	                <div class="site-header-shown">
	                    <div class="dropdown user-menu">
	                        <button class="dropdown-toggle" id="dd-user-menu" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	                            <img src="../../public/img/avatar-sign.png" alt="Avatar">
	                            <span class="lbl-name font-weight-bold ml-1"><?php echo $nombreCompleto; ?></span>
	                            <span class="label label-custom label-pill ml-1"><?php echo strtoupper($rol); ?></span>
	                        </button>
	                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd-user-menu">
	                            <a class="dropdown-item" href="#"><span class="font-icon glyphicon glyphicon-user"></span> Perfil (<?php echo $correo; ?>)</a>
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

	<!-- Menú Lateral -->
	<nav class="side-menu">
	    <ul class="side-menu-list">
	        <li class="blue-dirty active">
	            <a href="home.php">
	                <i class="font-icon glyphicon glyphicon-home"></i>
	                <span class="lbl">Inicio</span>
	            </a>
	        </li>
	        <li class="blue-dirty">
	            <a href="../NuevoTicket/N_ticket.php">
	                <i class="font-icon glyphicon glyphicon-plus-sign"></i>
	                <span class="lbl">Nuevo Ticket</span>
	            </a>
	        </li>
	        <li class="blue-dirty">
	            <a href="#">
	                <i class="font-icon glyphicon glyphicon-list-alt"></i>
	                <span class="lbl">Mis Tickets</span>
	            </a>
	        </li>
	        <?php if ($rol === 'admin'): ?>
	        <li class="gold">
	            <a href="../NuevoRegistro/N_registro.php">
	                <i class="font-icon glyphicon glyphicon-user"></i>
	                <span class="lbl">Gestión de Usuarios</span>
	            </a>
	        </li>
	        <?php endif; ?>
	        <li class="red">
	            <a href="../../controller/usuario.php?op=logout">
	                <i class="font-icon glyphicon glyphicon-log-out"></i>
	                <span class="lbl">Salir</span>
	            </a>
	        </li>
	    </ul>
	</nav>

	<!-- Contenido Principal -->
	<div class="page-content">
	    <div class="container-fluid">
	        <header class="section-header">
	            <div class="tbl">
	                <div class="tbl-row">
	                    <div class="tbl-cell">
	                        <h3>Panel de Control - HelpDesk</h3>
	                        <ol class="breadcrumb breadcrumb-simple">
	                            <li><a href="home.php">HelpDesk</a></li>
	                            <li class="active">Inicio</li>
	                        </ol>
	                    </div>
	                </div>
	            </div>
	        </header>

	        <!-- Tarjeta de Bienvenida -->
	        <div class="box-typical box-typical-padding">
	            <h4>¡Hola, <?php echo $nombreCompleto; ?>!</h4>
	            <p class="text-muted">
	                Bienvenido a la Mesa de Ayuda. Has iniciado sesión con el rol de 
	                <strong><?php echo ucfirst($rol); ?></strong>.
	            </p>
	            <a href="../NuevoTicket/N_ticket.php" class="btn btn-rounded btn-inline btn-primary">
	                <i class="fa fa-plus"></i> Crear Nuevo Ticket
	            </a>
	        </div>

	        <!-- Tarjetas de Estadísticas (Placeholder para Fase 3) -->
	        <div class="row">
	            <div class="col-sm-4">
	                <article class="statistic-box red">
	                    <div>
	                        <div class="number">0</div>
	                        <div class="caption"><div>Tickets Abiertos</div></div>
	                    </div>
	                </article>
	            </div>
	            <div class="col-sm-4">
	                <article class="statistic-box yellow">
	                    <div>
	                        <div class="number">0</div>
	                        <div class="caption"><div>Tickets En Proceso</div></div>
	                    </div>
	                </article>
	            </div>
	            <div class="col-sm-4">
	                <article class="statistic-box green">
	                    <div>
	                        <div class="number">0</div>
	                        <div class="caption"><div>Tickets Cerrados</div></div>
	                    </div>
	                </article>
	            </div>
	        </div>
	    </div>
	</div>

	<!-- Scripts requeridos por la plantilla -->
	<script src="../../public/js/lib/jquery/jquery.min.js"></script>
	<script src="../../public/js/lib/tether/tether.min.js"></script>
	<script src="../../public/js/lib/bootstrap/bootstrap.min.js"></script>
	<script src="../../public/js/plugins.js"></script>
	<script src="../../public/js/app.js"></script>
</body>
</html>
