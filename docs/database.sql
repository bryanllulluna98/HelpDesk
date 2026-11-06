-- ====================================================================
-- SISTEMA DE GESTIÓN DE TICKETS Y MESA DE AYUDA (HELPDESK TECNOSTAR)
-- Base de Datos Inicial y Estructura de Tablas
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
  `estado` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1: Activo, 0: Inactivo',
  `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `uk_usuario_correo` (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 3. Inserción de usuarios iniciales de prueba
-- Nota de Seguridad:
-- Si el sistema usa verificación por hash (password_verify):
-- Los hashes abajo corresponden a:
--   - admin123
--   - soporte123
--   - usuario123
-- Si el sistema aún usa comparación en texto plano temporal:
-- Se deja el valor comentado para pruebas iniciales.
-- --------------------------------------------------------------------
INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `correo`, `password`, `rol`, `estado`, `fecha_creacion`) VALUES
-- Administrador: pass = admin123 (Hash bcrypt verificado)
(1, 'Bryan', 'Steven', 'admin@tecnostar.com', '$2y$12$tkvfELwM271s8Q7j6HicE.SYWZGMw6u9K48uJRnpadaG5lgn38vuK', 'admin', 1, NOW()),
-- Técnico de soporte: pass = soporte123 (Hash bcrypt verificado)
(2, 'Carlos', 'Mendoza', 'soporte@tecnostar.com', '$2y$12$xkCIXViMmrQbyX0DjoTl1er9R43F0CiuiIGSAY5FOrck/rxRC0u5m', 'tecnico', 1, NOW()),
-- Usuario final: pass = usuario123 (Hash bcrypt verificado)
(3, 'Ana', 'Gómez', 'usuario@tecnostar.com', '$2y$12$js56L7w2UTc9KgQthNCXduvZQ2UYqM3tjWXaBMuexACbQFBiSb9Ti', 'usuario', 1, NOW());

-- NOTA TEMPORAL PARA MIGRACIÓN:
-- Si estás probando la función legacy login() que aún compara texto plano directamente:
-- UPDATE `usuarios` SET `password` = 'admin123' WHERE `id_usuario` = 1;
-- UPDATE `usuarios` SET `password` = 'soporte123' WHERE `id_usuario` = 2;
-- UPDATE `usuarios` SET `password` = 'usuario123' WHERE `id_usuario` = 3;


-- ====================================================================
-- 4. TABLAS BASE PARA EL ROADMAP DEL HELPDESK (Para Fases 2 y 3)
-- ====================================================================

-- Tabla de Categorías de Tickets (Hardware, Software, Redes, Accesos, etc.)
CREATE TABLE IF NOT EXISTS `categorias` (
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

-- Tabla de Tickets
CREATE TABLE IF NOT EXISTS `tickets` (
  `id_ticket` INT(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` INT(11) NOT NULL COMMENT 'Usuario solicitante',
  `id_categoria` INT(11) NOT NULL,
  `id_tecnico` INT(11) DEFAULT NULL COMMENT 'Técnico asignado',
  `titulo` VARCHAR(200) NOT NULL,
  `descripcion` MEDIUMTEXT NOT NULL,
  `prioridad` ENUM('baja', 'media', 'alta', 'urgente') NOT NULL DEFAULT 'media',
  `estado` ENUM('abierto', 'en_proceso', 'espera', 'cerrado') NOT NULL DEFAULT 'abierto',
  `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_cierre` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id_ticket`),
  KEY `fk_ticket_usuario` (`id_usuario`),
  KEY `fk_ticket_categoria` (`id_categoria`),
  KEY `fk_ticket_tecnico` (`id_tecnico`),
  CONSTRAINT `fk_ticket_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  CONSTRAINT `fk_ticket_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`),
  CONSTRAINT `fk_ticket_tecnico` FOREIGN KEY (`id_tecnico`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Comentarios / Seguimiento de Tickets
CREATE TABLE IF NOT EXISTS `ticket_detalles` (
  `id_detalle` INT(11) NOT NULL AUTO_INCREMENT,
  `id_ticket` INT(11) NOT NULL,
  `id_usuario` INT(11) NOT NULL COMMENT 'Quien emite el mensaje',
  `mensaje` MEDIUMTEXT NOT NULL,
  `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_detalle`),
  KEY `fk_detalle_ticket` (`id_ticket`),
  KEY `fk_detalle_usuario` (`id_usuario`),
  CONSTRAINT `fk_detalle_ticket` FOREIGN KEY (`id_ticket`) REFERENCES `tickets` (`id_ticket`) ON DELETE CASCADE,
  CONSTRAINT `fk_detalle_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

