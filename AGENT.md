# 🤖 Guía de Contexto y Reglas para Agentes de IA (`AGENT.md`)

Este archivo contiene el contexto del proyecto, directrices arquitectónicas, estándares de código y reglas de seguridad que **todo asistente de IA** debe respetar rigurosamente al proponer, refactorizar o generar código en este repositorio.

---

## 🎯 Objetivo del Proyecto y Filosofía de Desarrollo

- **Proyecto:** `PERSONAL_HelpDesk` (Sistema de Gestión de Tickets y Mesa de Ayuda).
- **Propietario:** Bryan Steven.
- **Propósito Educativo:** Este proyecto es la base para el crecimiento profesional de Bryan hacia un nivel **Semi-Senior / Senior** en desarrollo de software backend y full-stack.
- **Regla de Oro para la IA:** No generes soluciones rápidas, "parches" o código desordenado. Explica las decisiones arquitectónicas, promueve las mejores prácticas de la industria y guía al desarrollador en el "por qué" y no solo en el "cómo".

---

## 🏗️ Arquitectura y Patrones de Diseño

El proyecto sigue una arquitectura **MVC (Modelo-Vista-Controlador)** en PHP:

1. **Modelos (`model/`):**
   - Responsabilidad única: interactuar con los datos y aplicar reglas de negocio.
   - **PROHIBIDO:** Acceder directamente a variables superglobales (`$_POST`, `$_GET`, `$_SESSION`) dentro del Modelo.
   - **PROHIBIDO:** Realizar redirecciones (`header("Location: ...")`) o emitir HTML/impresiones (`echo`, `die()`) desde los modelos.
   - Toda interacción con la base de datos debe usar **consultas preparadas con PDO**.

2. **Controladores (`controller/`):**
   - Reciben las peticiones del cliente (HTTP POST/GET o JSON API).
   - Validan y sanitizan los datos de entrada.
   - Llaman a los métodos correspondientes del Modelo.
   - Gestionan sesiones y respuestas (redirección o renderizado de vistas).

3. **Vistas (`view/` e `index.php`):**
   - Responsabilidad única: renderizar la interfaz de usuario.
   - **PROHIBIDO:** Contener sentencias SQL o lógica de negocio compleja.
   - Toda salida dinámica debe estar escapada contra ataques XSS mediante `htmlspecialchars()`.

---

## 🔒 Reglas Críticas de Seguridad (Security by Design)

Cualquier cambio o nueva funcionalidad debe cumplir con:

1. **Gestión de Contraseñas:**
   - Jamás almacenar contraseñas en texto plano.
   - Usar siempre `password_hash($password, PASSWORD_DEFAULT)` al crear o actualizar.
   - Usar siempre `password_verify($password, $hash)` al autenticar.
2. **Prevención de Inyecciones SQL:**
   - Cero concatenación directa de variables en cadenas SQL.
   - Siempre usar parámetros enlazados con PDO (`prepare` con `:placeholder` o `?`).
3. **Seguridad de Sesiones:**
   - No almacenar datos sensibles en la sesión (como contraseñas).
   - Usar `session_regenerate_id(true)` tras un inicio de sesión exitoso para mitigar *Session Fixation*.
   - Validar la existencia de sesión activa en la cabecera de todas las vistas privadas.
4. **Protección CSRF:**
   - Implementar tokens CSRF en todos los formularios que modifiquen estado (POST/PUT/DELETE).

---

## 💻 Estándares de Código PHP (PHP 8.x + PSR)

- **Estilo:** Seguir el estándar **PSR-12**.
- **Tipado Estricto:** Declarar tipos de datos en argumentos y retornos de funciones siempre que sea posible (`declare(strict_types=1);`, `int`, `string`, `?array`, `void`).
- **Nombres de Clases y Métodos:**
  - Clases en PascalCase (ej. `UsuarioController`, `TicketModel`).
  - Métodos y funciones en camelCase (ej. `obtenerPorId()`, `cambiarEstado()`).
  - Variables en camelCase o snake_case consistente.
- **Manejo de Errores:**
  - Evitar silenciar errores con `@`.
  - Capturar excepciones específicas (`PDOException`, `InvalidArgumentException`) y registrarlas en logs en lugar de imprimirlas al usuario final.

---

## 🗄️ Base de Datos y Entorno Local

- **Servidor Local:** Laragon en Windows (`C:\laragon\www\PERSONAL_HelpDesk`).
- **Base de Datos:** MySQL / MariaDB (`helpdesk_tecnostar`).
- **Charset:** `utf8mb4`.
- **Scripts:** Cualquier cambio en la estructura de base de datos debe reflejarse en `docs/database.sql`.

---

## 🚀 Hoja de Ruta Inmediata para el Asistente

Al recibir solicitudes de nuevas características, priorizar en este orden:
1. **Refactorizar el Login:** Unificar el método de autenticación en `Usuarios.php` con `password_verify()` y mover la recepción del `$_POST` a un controlador en `controller/usuario.php`.
2. **Protección de Vistas:** Crear un archivo de verificación de sesión (ej. `view/includes/head.php` o `middleware`) para que `view/home/home.php` no sea accesible por URL directa sin sesión activa.
3. **Módulo de Tickets:** Diseñar la creación, listado y detalle de tickets.

