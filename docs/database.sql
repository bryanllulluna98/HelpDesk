-- ====================================================================
-- SISTEMA DE GESTIÓN DE TICKETS Y MESA DE AYUDA (HELPDESK TECNOSTAR)
-- Base de Datos Inicial, Estructura de Tablas y Stored Procedures
-- Motor: MySQL 5.7+ / MariaDB 10.4+
-- ====================================================================

-- 1. Creación de la base de datos si no existe
CREATE DATABASE IF NOT EXISTS `helpdesk_tecnostar` 
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `helpdesk_tecnostar`;

-- --------------------------------------------------------------------
-- 2. Estructura de la tabla `usuarios`
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id_usuario` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `apellido` VARCHAR(100) NOT NULL,
  `correo` VARCHAR(150) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `rol` ENUM('admin', 'tecnico', 'usuario') NOT NULL DEFAULT 'usuario',
  `telefono` VARCHAR(20) DEFAULT NULL COMMENT 'Para notificaciones por WhatsApp',
  `token_recuperacion` VARCHAR(255) DEFAULT NULL COMMENT 'Para recuperación con OpenSSL',
  `estado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1: Activo, 0: Inactivo',
  `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `uk_usuario_correo` (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 3. Inserción de usuarios iniciales de prueba (Hashes verificados)
-- --------------------------------------------------------------------
INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `correo`, `password`, `rol`, `telefono`, `estado`, `fecha_creacion`) VALUES
-- Administrador: pass = admin123
(1, 'Bryan', 'Steven', 'admin@tecnostar.com', '$2y$12$tkvfELwM271s8Q7j6HicE.SYWZGMw6u9K48uJRnpadaG5lgn38vuK', 'admin', '593999999991', 1, NOW()),
-- Técnico de soporte: pass = soporte123
(2, 'Carlos', 'Mendoza', 'soporte@tecnostar.com', '$2y$12$xkCIXViMmrQbyX0DjoTl1er9R43F0CiuiIGSAY5FOrck/rxRC0u5m', 'tecnico', '593999999992', 1, NOW()),
-- Usuario final: pass = usuario123
(3, 'Ana', 'Gómez', 'usuario@tecnostar.com', '$2y$12$js56L7w2UTc9KgQthNCXduvZQ2UYqM3tjWXaBMuexACbQFBiSb9Ti', 'usuario', '593999999993', 1, NOW());

-- --------------------------------------------------------------------
-- 4. Estructura de la tabla `categorias`
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `categorias`;
CREATE TABLE `categorias` (
  `id_categoria` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `descripcion` VARCHAR(255) DEFAULT NULL,
  `estado` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categorias` (`id_categoria`, `nombre`, `descripcion`, `estado`) VALUES
(1, 'Hardware / Equipos', 'Problemas con computadoras, impresoras o periféricos', 1),
(2, 'Software / Programas', 'Instalación de programas, errores de software, licencias', 1),
(3, 'Redes e Internet', 'Fallas de conexión wifi, cables de red, acceso VPN', 1),
(4, 'Accesos y Contraseñas', 'Restablecimiento de claves, permisos en sistemas', 1);

-- --------------------------------------------------------------------
-- 5. Estructura de la tabla `tickets`
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `tickets`;
CREATE TABLE `tickets` (
  `id_ticket` INT(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` INT(11) NOT NULL COMMENT 'Usuario solicitante',
  `id_categoria` INT(11) NOT NULL,
  `id_tecnico` INT(11) DEFAULT NULL COMMENT 'Técnico asignado',
  `titulo` VARCHAR(200) NOT NULL,
  `descripcion` MEDIUMTEXT NOT NULL,
  `prioridad` ENUM('baja', 'media', 'alta', 'urgente') NOT NULL DEFAULT 'media',
  `estado` ENUM('abierto', 'en_proceso', 'espera', 'cerrado') NOT NULL DEFAULT 'abierto',
  `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_asignacion` DATETIME DEFAULT NULL,
  `fecha_cierre` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id_ticket`),
  KEY `fk_ticket_usuario` (`id_usuario`),
  KEY `fk_ticket_categoria` (`id_categoria`),
  KEY `fk_ticket_tecnico` (`id_tecnico`),
  CONSTRAINT `fk_ticket_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  CONSTRAINT `fk_ticket_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`),
  CONSTRAINT `fk_ticket_tecnico` FOREIGN KEY (`id_tecnico`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 6. Estructura de la tabla `ticket_detalles` (Comentarios / Hilo)
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `ticket_detalles`;
CREATE TABLE `ticket_detalles` (
  `id_detalle` INT(11) NOT NULL AUTO_INCREMENT,
  `id_ticket` INT(11) NOT NULL,
  `id_usuario` INT(11) NOT NULL COMMENT 'Quien emite la respuesta',
  `mensaje` MEDIUMTEXT NOT NULL,
  `es_ia` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 si es respuesta generada por ChatGPT',
  `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_detalle`),
  KEY `fk_detalle_ticket` (`id_ticket`),
  KEY `fk_detalle_usuario` (`id_usuario`),
  CONSTRAINT `fk_detalle_ticket` FOREIGN KEY (`id_ticket`) REFERENCES `tickets` (`id_ticket`) ON DELETE CASCADE,
  CONSTRAINT `fk_detalle_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 7. Estructura de la tabla `ticket_archivos` (Adjuntos en Tickets)
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `ticket_archivos`;
CREATE TABLE `ticket_archivos` (
  `id_archivo` INT(11) NOT NULL AUTO_INCREMENT,
  `id_ticket` INT(11) NOT NULL,
  `nombre_original` VARCHAR(255) NOT NULL,
  `nombre_servidor` VARCHAR(255) NOT NULL,
  `extension` VARCHAR(10) NOT NULL,
  `tamano` INT(11) NOT NULL COMMENT 'Tamaño en bytes',
  `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_archivo`),
  KEY `fk_archivo_ticket` (`id_ticket`),
  CONSTRAINT `fk_archivo_ticket` FOREIGN KEY (`id_ticket`) REFERENCES `tickets` (`id_ticket`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 8. Estructura de la tabla `encuestas_satisfaccion`
-- --------------------------------------------------------------------
DROP TABLE IF EXISTS `encuestas_satisfaccion`;
CREATE TABLE `encuestas_satisfaccion` (
  `id_encuesta` INT(11) NOT NULL AUTO_INCREMENT,
  `id_ticket` INT(11) NOT NULL,
  `id_usuario` INT(11) NOT NULL,
  `puntuacion` TINYINT(1) NOT NULL COMMENT 'Calificación del 1 al 5',
  `comentarios` TEXT DEFAULT NULL,
  `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_encuesta`),
  UNIQUE KEY `uk_encuesta_ticket` (`id_ticket`),
  CONSTRAINT `fk_encuesta_ticket` FOREIGN KEY (`id_ticket`) REFERENCES `tickets` (`id_ticket`) ON DELETE CASCADE,
  CONSTRAINT `fk_encuesta_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ====================================================================
-- 9. STORED PROCEDURES (Procedimientos Almacenados en MySQL)
-- ====================================================================

DELIMITER //

-- SP: Listar Categorías Activas
DROP PROCEDURE IF EXISTS `sp_l_categoria` //
CREATE PROCEDURE `sp_l_categoria`()
BEGIN
    SELECT id_categoria, nombre, descripcion, estado 
    FROM categorias 
    WHERE estado = 1 
    ORDER BY nombre ASC;
END //

-- SP: Insertar Ticket
DROP PROCEDURE IF EXISTS `sp_i_ticket` //
CREATE PROCEDURE `sp_i_ticket`(
    IN p_id_usuario INT,
    IN p_id_categoria INT,
    IN p_titulo VARCHAR(200),
    IN p_descripcion MEDIUMTEXT,
    IN p_prioridad ENUM('baja', 'media', 'alta', 'urgente')
)
BEGIN
    INSERT INTO tickets (id_usuario, id_categoria, titulo, descripcion, prioridad, estado, fecha_creacion)
    VALUES (p_id_usuario, p_id_categoria, p_titulo, p_descripcion, p_prioridad, 'abierto', NOW());
    
    SELECT LAST_INSERT_ID() AS id_ticket;
END //

-- SP: Listar Tickets de un Usuario (Para DataTables)
DROP PROCEDURE IF EXISTS `sp_l_ticket_usu` //
CREATE PROCEDURE `sp_l_ticket_usu`(
    IN p_id_usuario INT
)
BEGIN
    SELECT 
        t.id_ticket,
        t.id_usuario,
        t.titulo,
        c.nombre AS categoria,
        t.prioridad,
        t.estado,
        DATE_FORMAT(t.fecha_creacion, '%d/%m/%Y %H:%i') AS fecha_creacion,
        t.fecha_cierre
    FROM tickets t
    INNER JOIN categorias c ON t.id_categoria = c.id_categoria
    WHERE t.id_usuario = p_id_usuario
    ORDER BY t.id_ticket DESC;
END //

-- SP: Listar Todos los Tickets (Para Administrador y Técnicos en DataTables)
DROP PROCEDURE IF EXISTS `sp_l_ticket` //
CREATE PROCEDURE `sp_l_ticket`()
BEGIN
    SELECT 
        t.id_ticket,
        CONCAT(u.nombre, ' ', u.apellido) AS solicitante,
        c.nombre AS categoria,
        t.titulo,
        t.prioridad,
        t.estado,
        DATE_FORMAT(t.fecha_creacion, '%d/%m/%Y %H:%i') AS fecha_creacion,
        IFNULL(CONCAT(tec.nombre, ' ', tec.apellido), 'Sin Asignar') AS tecnico
    FROM tickets t
    INNER JOIN usuarios u ON t.id_usuario = u.id_usuario
    INNER JOIN categorias c ON t.id_categoria = c.id_categoria
    LEFT JOIN usuarios tec ON t.id_tecnico = tec.id_usuario
    ORDER BY t.id_ticket DESC;
END //

-- SP: Obtener Detalle de Ticket por ID
DROP PROCEDURE IF EXISTS `sp_l_ticket_id` //
CREATE PROCEDURE `sp_l_ticket_id`(
    IN p_id_ticket INT
)
BEGIN
    SELECT 
        t.id_ticket,
        t.id_usuario,
        CONCAT(u.nombre, ' ', u.apellido) AS solicitante,
        u.correo AS correo_solicitante,
        u.telefono AS telefono_solicitante,
        t.id_categoria,
        c.nombre AS categoria,
        t.id_tecnico,
        IFNULL(CONCAT(tec.nombre, ' ', tec.apellido), 'Sin Asignar') AS tecnico,
        t.titulo,
        t.descripcion,
        t.prioridad,
        t.estado,
        DATE_FORMAT(t.fecha_creacion, '%d/%m/%Y %H:%i') AS fecha_creacion,
        DATE_FORMAT(t.fecha_cierre, '%d/%m/%Y %H:%i') AS fecha_cierre
    FROM tickets t
    INNER JOIN usuarios u ON t.id_usuario = u.id_usuario
    INNER JOIN categorias c ON t.id_categoria = c.id_categoria
    LEFT JOIN usuarios tec ON t.id_tecnico = tec.id_usuario
    WHERE t.id_ticket = p_id_ticket
    LIMIT 1;
END //

DELIMITER ;
