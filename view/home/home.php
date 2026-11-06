<?php
declare(strict_types=1);

require_once(__DIR__ . "/../includes/seguridad.php");
require_once(__DIR__ . "/../../model/Tickets.php");

$tituloPagina = "HelpDesk - Panel Principal";
$paginaActiva = "home";

$ticketModel = new Tickets();
$esAdmin = ($_SESSION["rol"] ?? "") === "admin";
$idUsuario = (int) $_SESSION["id_usuario"];

// Conteo dinámico de tickets
$abiertos = $ticketModel->contarPorEstado("abierto", $esAdmin ? null : $idUsuario);
$enProceso = $ticketModel->contarPorEstado("en_proceso", $esAdmin ? null : $idUsuario);
$cerrados = $ticketModel->contarPorEstado("cerrado", $esAdmin ? null : $idUsuario);

$nombreUsuario = htmlspecialchars($_SESSION["nombre"] . " " . $_SESSION["apellido"], ENT_QUOTES, "UTF-8");
$rolUsuario = htmlspecialchars($_SESSION["rol"] ?? "usuario", ENT_QUOTES, "UTF-8");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once(__DIR__ . "/../includes/head.php"); ?>
</head>
<body class="with-side-menu">

    <?php require_once(__DIR__ . "/../includes/header.php"); ?>
    <?php require_once(__DIR__ . "/../includes/nav.php"); ?>

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
                <h4>¡Bienvenido, <?php echo $nombreUsuario; ?>!</h4>
                <p class="text-muted">
                    Panel de control de la Mesa de Ayuda. Has iniciado sesión con el rol de 
                    <span class="label label-custom label-pill"><?php echo strtoupper($rolUsuario); ?></span>.
                </p>
                <a href="../NuevoTicket/N_ticket.php" class="btn btn-rounded btn-inline btn-primary">
                    <i class="fa fa-plus"></i> Crear Nuevo Ticket
                </a>
            </div>

            <!-- Tarjetas de Estadísticas Reales -->
            <div class="row">
                <div class="col-sm-4">
                    <article class="statistic-box red">
                        <div>
                            <div class="number"><?php echo $abiertos; ?></div>
                            <div class="caption"><div>Tickets Abiertos</div></div>
                        </div>
                    </article>
                </div>
                <div class="col-sm-4">
                    <article class="statistic-box yellow">
                        <div>
                            <div class="number"><?php echo $enProceso; ?></div>
                            <div class="caption"><div>Tickets En Proceso</div></div>
                        </div>
                    </article>
                </div>
                <div class="col-sm-4">
                    <article class="statistic-box green">
                        <div>
                            <div class="number"><?php echo $cerrados; ?></div>
                            <div class="caption"><div>Tickets Cerrados</div></div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>

    <?php require_once(__DIR__ . "/../includes/scripts.php"); ?>
</body>
</html>
