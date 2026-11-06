<?php
declare(strict_types=1);

$pagina = $paginaActiva ?? "";
$rolActual = $_SESSION["rol"] ?? "usuario";
?>
<!-- Menú Lateral de Navegación -->
<nav class="side-menu">
    <ul class="side-menu-list">
        <li class="blue-dirty <?php echo ($pagina === 'home') ? 'active' : ''; ?>">
            <a href="../home/home.php">
                <i class="font-icon glyphicon glyphicon-home"></i>
                <span class="lbl">Inicio</span>
            </a>
        </li>
        <li class="blue-dirty <?php echo ($pagina === 'nuevo_ticket') ? 'active' : ''; ?>">
            <a href="../NuevoTicket/N_ticket.php">
                <i class="font-icon glyphicon glyphicon-plus-sign"></i>
                <span class="lbl">Nuevo Ticket</span>
            </a>
        </li>
        <li class="blue-dirty <?php echo ($pagina === 'mis_tickets') ? 'active' : ''; ?>">
            <a href="#">
                <i class="font-icon glyphicon glyphicon-list-alt"></i>
                <span class="lbl">Mis Tickets</span>
            </a>
        </li>
        <?php if ($rolActual === 'admin'): ?>
        <li class="gold <?php echo ($pagina === 'usuarios') ? 'active' : ''; ?>">
            <a href="../NuevoRegistro/N_registro.php">
                <i class="font-icon glyphicon glyphicon-user"></i>
                <span class="lbl">Gestión de Usuarios</span>
            </a>
        </li>
        <?php endif; ?>
        <li class="red">
            <a href="../../controller/usuario.php?op=logout">
                <i class="font-icon glyphicon glyphicon-log-out"></i>
                <span class="lbl">Cerrar Sesión</span>
            </a>
        </li>
    </ul>
</nav>

