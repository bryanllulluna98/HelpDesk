# 🤖 Guía de Contexto y Memoria para Agentes de IA (`AGENT.md`)

Este archivo contiene el contexto del proyecto, directrices arquitectónicas, estándares de código, bitácora de progreso y reglas de seguridad que **todo asistente de IA** debe respetar rigurosamente al trabajar en este repositorio.

---

## 🎯 Objetivo del Proyecto y Filosofía de Desarrollo

- **Proyecto:** `PERSONAL_HelpDesk` (Sistema de Gestión de Tickets y Mesa de Ayuda).
- **Propietario:** Bryan Steven.
- **Propósito Educativo:** Este proyecto es la plataforma práctica para el crecimiento profesional de Bryan hacia un nivel **Semi-Senior / Senior** en ingeniería de software (Backend y Full-Stack en PHP/MySQL), siguiendo y superando los objetivos pedagógicos de su curso de formación.
- **Regla de Oro para la IA:** No generes soluciones rápidas, parches temporales o código desordenado estilo tutorial antiguo. Integra todos los temas del curso (incluyendo Stored Procedures, librerías JS, Composer y APIs) elevándolos a estándares profesionales de la industria (seguridad, PSR-12, tipado en PHP 8, arquitectura limpia).

---

## 🎓 Alineación Curricular con el Curso del Estudiante

El desarrollo de este sistema debe cubrir paso a paso los módulos del curso que Bryan está siguiendo:
1. **Base de Datos y Procedimientos Almacenados (Stored Procedures):**
   - Uso de SPs en MySQL (`sp_l_categoria`, `sp_i_ticket`, `sp_l_ticket_usu`, `sp_l_ticket`, etc.) invocados desde PDO mediante consultas preparadas (`CALL sp_...(?, ?)`).
2. **Autenticación y CRUD Completo de Usuarios:**
   - Registro, login, roles (`admin`, `tecnico`, `usuario`), estados y gestión de perfiles.
3. **Módulo de Tickets y Summernote JS:**
   - Creación, seguimiento, estados (*Abierto*, *En Proceso*, *En Espera*, *Cerrado*).
   - Inclusión de **archivos adjuntos** (`ticket_archivos`) en los tickets.
4. **DataTables Interactivo:**
   - Bandeja de tickets con búsqueda en tiempo real, filtros por estado y paginación AJAX.
5. **Notificaciones por Correo Electrónico con Composer y PHPMailer:**
   - Envío de alertas automatizadas a clientes y técnicos tras creación, asignación o cambio de estado.
6. **Recuperación de Contraseña con OpenSSL:**
   - Generación y validación de tokens de recuperación encriptados/desencriptados con OpenSSL vía enlace por email.
7. **Visualización y Reportes con JavaScript:**
   - Gráficos interactivos de rendimiento y métricas.
   - Calendario de incidentes mediante **FullCalendar JS**.
8. **Encuesta de Satisfacción de Atención:**
   - Calificación de servicio (1 a 5 estrellas) al cerrar el ticket (`encuestas_satisfaccion`).
9. **Integraciones Avanzadas (APIs y Terceros):**
   - Envío de notificaciones por **WhatsApp API** al crear, asignar y cerrar tickets.
   - Integración con **ChatGPT (OpenAI API)** para sugerir o generar respuestas inteligentes a incidencias.
10. **Despliegue y Puesta en Marcha:**
    - Preparación para hosting de producción (configuraciones `.env`, seguridad y subida).

---

## 🧠 Memoria de Progreso y Estado Actual (Noviembre 2026)

### ✅ Fase 1: Autenticación, Seguridad y Arquitectura Base (COMPLETADA Y VERIFICADA)
1. **Separación MVC Estricta:**
   - [`index.php`](file:///c:/laragon/www/PERSONAL_HelpDesk/index.php): Vista limpia que envía el formulario a `controller/usuario.php?op=login` y muestra alertas dinámicas (`?m=1` y `?m=2`). Si ya hay sesión activa, redirige automáticamente al home.
   - [`controller/usuario.php`](file:///c:/laragon/www/PERSONAL_HelpDesk/controller/usuario.php): Controlador que procesa el login (`op=login`) y logout (`op=logout`), valida campos requeridos, regenera IDs de sesión para evitar *Session Fixation* y orquesta las redirecciones.
   - [`model/Usuarios.php`](file:///c:/laragon/www/PERSONAL_HelpDesk/model/Usuarios.php): Modelo desacoplado del protocolo HTTP. Inicializa PDO en el constructor y utiliza consultas preparadas.
2. **Seguridad Criptográfica:**
   - Autenticación mediante `password_verify()` y generación de contraseñas con `password_hash($pass, PASSWORD_DEFAULT)`.
   - Detección inteligente en `autenticar()` para evitar confundir hashes con cadenas de texto plano.
3. **Protección de Rutas Privadas:**
   - Middleware [`view/includes/seguridad.php`](file:///c:/laragon/www/PERSONAL_HelpDesk/view/includes/seguridad.php) implementado. Cualquier vista que lo incluya exige sesión activa o expulsa al login.
   - [`view/home/home.php`](file:///c:/laragon/www/PERSONAL_HelpDesk/view/home/home.php) protegido y maquetado con perfil dinámico (`nombre`, `apellido`, `rol`) y botón funcional de cierre de sesión.
4. **Bug Crítico de Plantilla Resuelto:**
   - En [`public/js/app.js`](file:///c:/laragon/www/PERSONAL_HelpDesk/public/js/app.js) línea 183 se reemplazó la llamada inválida `$('body').click('click')` por `$('body').trigger('click')`, resolviendo el error `Uncaught TypeError: ... apply is not a function`.
5. **Base de Datos Inicial:**
   - [`docs/database.sql`](file:///c:/laragon/www/PERSONAL_HelpDesk/docs/database.sql) con hashes bcrypt reales y verificados para los 3 perfiles demo.

### ✅ Fase 2: Creación y Registro de Tickets (COMPLETADA Y FUNCIONAL)
1. **Modelos de Negocio:**
   - [`model/Categorias.php`](file:///c:/laragon/www/PERSONAL_HelpDesk/model/Categorias.php): Lista categorías activas (`listarActivas`) para poblar selectores.
   - [`model/Tickets.php`](file:///c:/laragon/www/PERSONAL_HelpDesk/model/Tickets.php): Métodos para creación de tickets (`insertar`), detalle (`obtenerPorId`), listados (`listarPorUsuario`, `listarTodos`) y conteo para dashboard (`contarPorEstado`).
2. **Controlador:**
   - [`controller/ticket.php`](file:///c:/laragon/www/PERSONAL_HelpDesk/controller/ticket.php): Valida sesión obligatoria, procesa inserciones (`op=insertar`) con validación de campos obligatorios y soporte dual (POST estándar y AJAX).
3. **Modularización de Vistas (DRY):**
   - Vistas maestras reutilizables en [`view/includes/`](file:///c:/laragon/www/PERSONAL_HelpDesk/view/includes/): `head.php`, `header.php`, `nav.php`, `scripts.php` y `seguridad.php`.
4. **Vista de Nuevo Ticket:**
   - [`view/NuevoTicket/N_ticket.php`](file:///c:/laragon/www/PERSONAL_HelpDesk/view/NuevoTicket/N_ticket.php): Formulario completo con solicitante, categorías dinámicas, selector de prioridad, título y editor enriquecido Summernote.
   - [`view/NuevoTicket/N_ticket.js`](file:///c:/laragon/www/PERSONAL_HelpDesk/view/NuevoTicket/N_ticket.js): Inicialización de Summernote y validación de campos antes de envío con alertas visuales (SweetAlert).
5. **Dashboard Conectado a Datos Reales:**
   - [`view/home/home.php`](file:///c:/laragon/www/PERSONAL_HelpDesk/view/home/home.php): Tarjetas estadísticas que muestran el conteo real de tickets abiertos, en proceso y cerrados desde MySQL.

---

## 🏗️ Arquitectura y Patrones de Diseño

El proyecto sigue una arquitectura **MVC (Modelo-Vista-Controlador)**:

1. **Modelos (`model/`):**
   - Responsabilidad única: interactuar con los datos y aplicar reglas de negocio.
   - **PROHIBIDO:** Acceder directamente a variables superglobales (`$_POST`, `$_GET`, `$_SESSION`) dentro del Modelo.
   - **PROHIBIDO:** Realizar redirecciones (`header("Location: ...")`) o emitir HTML/impresiones (`echo`, `die()`) desde los modelos.
   - Toda interacción con la base de datos debe usar **consultas preparadas con PDO**.
   - Los modelos deben inicializar su conexión en el constructor: `$this->conexion = parent::conexion();`.

2. **Controladores (`controller/`):**
   - Reciben las peticiones del cliente (HTTP POST/GET o JSON API).
   - Validan y sanitizan los datos de entrada.
   - Llaman a los métodos correspondientes del Modelo.
   - Gestionan sesiones y respuestas (redirección o renderizado de vistas).

3. **Vistas (`view/` e `index.php`):**
   - Responsabilidad única: renderizar la interfaz de usuario.
   - **PROHIBIDO:** Contener sentencias SQL o lógica de negocio.
   - Toda salida dinámica debe estar escapada contra ataques XSS mediante `htmlspecialchars($var, ENT_QUOTES, 'UTF-8')`.

---

## 🔒 Reglas Críticas de Seguridad (Security by Design)

1. **Gestión de Contraseñas:**
   - Jamás almacenar contraseñas en texto plano.
   - Usar siempre `password_hash($password, PASSWORD_DEFAULT)` al crear o actualizar.
   - Usar siempre `password_verify($password, $hash)` al autenticar.
2. **Prevención de Inyecciones SQL:**
   - Cero concatenación directa de variables en cadenas SQL.
   - Siempre usar parámetros enlazados con PDO (`prepare` con `:placeholder` o `?`).
3. **Seguridad de Sesiones:**
   - No almacenar datos sensibles en la sesión (como contraseñas).
   - Usar `session_regenerate_id(true)` tras un inicio de sesión exitoso.
   - Validar la existencia de sesión activa mediante `view/includes/seguridad.php`.
4. **Protección CSRF:**
   - Implementar tokens CSRF en todos los formularios que modifiquen estado.

---

## 💻 Estándares de Código PHP (PHP 8.x + PSR)

- **Estilo:** Seguir el estándar **PSR-12**.
- **Tipado Estricto:** Declarar tipos de datos en argumentos y retornos de funciones (`declare(strict_types=1);`, `int`, `string`, `?array`, `void`).
- **Nombres de Clases y Métodos:**
  - Clases en PascalCase (ej. `Usuarios`, `Tickets`, `Categorias`).
  - Métodos y funciones en camelCase (ej. `obtenerPorId()`, `cambiarEstado()`).
  - Variables en camelCase o snake_case consistente.
- **Manejo de Errores:**
  - Capturar excepciones específicas (`PDOException`, `InvalidArgumentException`) y registrarlas con `error_log()`.

---

## 🗄️ Entorno Local y Base de Datos

- **Servidor Local:** Laragon en Windows (`C:\laragon\www\PERSONAL_HelpDesk`).
- **Base de Datos:** MySQL / MariaDB (`helpdesk_tecnostar`).
- **Charset:** `utf8mb4`.
- **Scripts:** Cualquier cambio en la estructura de base de datos debe reflejarse en [`docs/database.sql`](file:///c:/laragon/www/PERSONAL_HelpDesk/docs/database.sql).

---

## 🎯 Próximo Paso Inmediato para la IA

### Fase 3: Bandeja de Consulta y Seguimiento de Tickets
1. **Controlador de Listado (`controller/ticket.php`):**
   - Acción `?op=listar_usuario`: Retornar JSON formateado para DataTables con los tickets del usuario logueado.
   - Acción `?op=listar_todos`: Retornar JSON con todos los tickets para administradores y soporte técnico.
2. **Vista de Consulta (`view/ConsultarTicket/` o `view/MisTickets/`):**
   - Tabla interactiva con DataTables (búsqueda en tiempo real, ordenamiento por fecha, badges de estado con colores).
   - Acciones por ticket: Ver detalle / Historial de comentarios.
3. **Módulo de Detalle de Ticket (`view/DetalleTicket/`):**
   - Visualización de datos del ticket, estado actual, y formulario para agregar respuestas/comentarios (`ticket_detalles`).
