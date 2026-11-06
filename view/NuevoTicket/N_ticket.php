<?php
declare(strict_types=1);

require_once(__DIR__ . "/../includes/seguridad.php");
require_once(__DIR__ . "/../../model/Categorias.php");

$tituloPagina = "HelpDesk - Nuevo Ticket";
$paginaActiva = "nuevo_ticket";

$categoriaModel = new Categorias();
$listaCategorias = $categoriaModel->listarActivas();

$nombreUsuario = htmlspecialchars($_SESSION["nombre"] . " " . $_SESSION["apellido"], ENT_QUOTES, "UTF-8");
$correoUsuario = htmlspecialchars($_SESSION["correo"] ?? "", ENT_QUOTES, "UTF-8");
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
                            <h3>Nuevo Ticket de Soporte</h3>
                            <ol class="breadcrumb breadcrumb-simple">
                                <li><a href="../home/home.php">HelpDesk</a></li>
                                <li><a href="#">Tickets</a></li>
                                <li class="active">Nuevo Ticket</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </header>

            <div class="box-typical box-typical-padding">
                <p class="text-muted m-b-md">
                    Complete el siguiente formulario para generar una nueva solicitud de asistencia técnica.
                </p>

                <!-- Alertas de Feedback -->
                <?php if (isset($_GET["m"])): ?>
                    <?php if ($_GET["m"] === "ok"): ?>
                        <div class="alert alert-success alert-fill alert-close alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <i class="font-icon font-icon-check-circle"></i>
                            <strong>¡Ticket Registrado con Éxito!</strong> Se ha generado el ticket número 
                            <strong>#<?php echo (int)($_GET["id"] ?? 0); ?></strong>. El equipo de soporte lo atenderá a la brevedad.
                        </div>
                    <?php elseif ($_GET["m"] === "2"): ?>
                        <div class="alert alert-warning alert-fill alert-close alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <i class="font-icon font-icon-warning"></i>
                            <strong>Atención:</strong> Por favor complete todos los campos obligatorios para registrar su ticket.
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <form method="post" id="ticket_form" action="../../controller/ticket.php?op=insertar">
                    <div class="row">
                        <!-- Solicitante (Sólo Lectura) -->
                        <div class="col-lg-6">
                            <fieldset class="form-group">
                                <label class="form-label semibold" for="solicitante">Solicitante</label>
                                <input type="text" class="form-control" id="solicitante" 
                                       value="<?php echo $nombreUsuario; ?> (<?php echo $correoUsuario; ?>)" readonly>
                            </fieldset>
                        </div>

                        <!-- Categoría -->
                        <div class="col-lg-6">
                            <fieldset class="form-group">
                                <label class="form-label semibold" for="id_categoria">
                                    Categoría <span class="text-danger">*</span>
                                </label>
                                <select id="id_categoria" name="id_categoria" class="form-control" required>
                                    <option value="">-- Seleccione una categoría --</option>
                                    <?php foreach ($listaCategorias as $cat): ?>
                                        <option value="<?php echo (int) $cat["id_categoria"]; ?>">
                                            <?php echo htmlspecialchars($cat["nombre"], ENT_QUOTES, "UTF-8"); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Prioridad -->
                        <div class="col-lg-4">
                            <fieldset class="form-group">
                                <label class="form-label semibold" for="prioridad">
                                    Prioridad <span class="text-danger">*</span>
                                </label>
                                <select id="prioridad" name="prioridad" class="form-control" required>
                                    <option value="baja">Baja</option>
                                    <option value="media" selected>Media</option>
                                    <option value="alta">Alta</option>
                                    <option value="urgente">Urgente</option>
                                </select>
                            </fieldset>
                        </div>

                        <!-- Título del Ticket -->
                        <div class="col-lg-8">
                            <fieldset class="form-group">
                                <label class="form-label semibold" for="titulo">
                                    Título / Asunto <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="titulo" name="titulo" 
                                       placeholder="Resumen claro y conciso del problema" required>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Descripción Enriquecida con Summernote -->
                        <div class="col-lg-12">
                            <fieldset class="form-group">
                                <label class="form-label semibold" for="descripcion">
                                    Descripción Detallada <span class="text-danger">*</span>
                                </label>
                                <textarea id="descripcion" name="descripcion" class="summernote" required></textarea>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <button type="submit" name="action" value="add" class="btn btn-rounded btn-inline btn-primary">
                                <i class="fa fa-save"></i> Guardar Ticket
                            </button>
                            <a href="../home/home.php" class="btn btn-rounded btn-inline btn-secondary">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require_once(__DIR__ . "/../includes/scripts.php"); ?>
    <script type="text/javascript" src="N_ticket.js"></script>
</body>
</html>

