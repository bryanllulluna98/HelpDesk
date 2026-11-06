# 🎫 HelpDesk - Sistema de Gestión de Tickets y Mesa de Ayuda

Sistema web de gestión de incidencias, tickets de soporte técnico y control de usuarios desarrollado en **PHP** y **MySQL**, diseñado bajo el patrón de arquitectura **MVC (Modelo-Vista-Controlador)** con estándares de la industria y enfoque en seguridad.

---

## 📌 Estado del Proyecto

- **Fase Actual:** `Fase 2 - Creación y Registro de Tickets (COMPLETADA Y FUNCIONAL)`.
- **Próximo Objetivo:** `Fase 3 - Bandeja de Consulta, DataTables y Detalle de Tickets`.
- **Servidor recomendado:** [Laragon](https://laragon.org/) (Apache + MySQL/MariaDB + PHP 8.x).

---

## 🛠️ Stack Tecnológico

| Capa | Tecnología |
| :--- | :--- |
| **Backend** | PHP 8.0+ (PDO, Programación Orientada a Objetos, PSR-12) |
| **Base de Datos** | MySQL 5.7+ / MariaDB 10.4+ (`utf8mb4_unicode_ci`) |
| **Servidor Web** | Apache (gestionado con Laragon) |
| **Frontend** | HTML5, CSS3, JavaScript (ES6+), jQuery |
| **Framework CSS / UI** | Bootstrap, Template Admin (lib/separate), FontAwesome |

---

## 🚀 Requisitos Previos

Antes de desplegar el proyecto localmente, asegúrate de tener instalado:

1. **Laragon** (recomendado) o XAMPP con:
   - **PHP >= 8.0** (con extensiones `pdo_mysql`, `mbstring`, `openssl` habilitadas).
   - **MySQL >= 5.7** o **MariaDB >= 10.4**.
2. **Git** para control de versiones.
3. Navegador web moderno (Chrome, Firefox, Edge).

---

## 💻 Guía de Despliegue e Instalación Local

### Paso 1: Ubicación del Proyecto en Laragon
El proyecto debe estar clonado o copiado dentro de la carpeta raíz de Laragon:
```bash
C:\laragon\www\PERSONAL_HelpDesk
```

Si lo descargas con Git:
```bash
cd C:\laragon\www
git clone https://github.com/bryanllulluna98/HelpDesk.git PERSONAL_HelpDesk
```

---

### Paso 2: Iniciar los Servicios
1. Abre **Laragon**.
2. Haz clic en **"Iniciar Todo"** (*Start All*) para activar **Apache** y **MySQL**.

---

### Paso 3: Crear la Base de Datos e Importar Tablas

1. Abre el gestor de base de datos de tu preferencia (**HeidiSQL**, **phpMyAdmin** o consola de MySQL desde Laragon).
2. Crea una nueva base de datos:
   ```sql
   CREATE DATABASE helpdesk_tecnostar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
3. Abre y ejecuta el script SQL inicial ubicado en:
   ```
   docs/database.sql
   ```
   *(Este archivo contiene las tablas `usuarios`, `categorias`, `tickets`, `ticket_detalles` y los 3 usuarios de prueba con contraseñas encriptadas verificadas).*

---

### Paso 4: Configurar la Conexión (`config/conexion.php`)

Verifica que las credenciales en `config/conexion.php` coincidan con tu entorno local:

```php
$dsn = "mysql:host=localhost;dbname=helpdesk_tecnostar;charset=utf8mb4";
$this->dbh = new PDO($dsn, "root", "", $opciones);
```

> **Nota:** La URL base configurada por defecto es `http://localhost/PERSONAL_HelpDesk/`.

---

### Paso 5: Ejecutar la Aplicación

Abre tu navegador e ingresa a:
```text
http://localhost/PERSONAL_HelpDesk/
```

---

### 🔑 Credenciales de Prueba Iniciales (Verificadas)

El script SQL incluye 3 perfiles con contraseñas encriptadas con **bcrypt**:

| Rol | Correo Electrónico | Contraseña |
| :--- | :--- | :--- |
| **Administrador** | `admin@tecnostar.com` | `admin123` |
| **Técnico / Soporte**| `soporte@tecnostar.com` | `soporte123` |
| **Usuario / Cliente**| `usuario@tecnostar.com` | `usuario123` |

---

## 📂 Estructura del Directorio

```text
PERSONAL_HelpDesk/
├── AGENT.md               # Contexto, memoria persistente y reglas para Asistentes de IA
├── README.md              # Documentación principal del proyecto
├── .gitignore             # Exclusión de archivos sensibles y temporales
├── index.php              # Vista de Login con alertas dinámicas (?m=1, ?m=2)
├── config/                # Configuraciones globales y conexión a Base de Datos
│   └── conexion.php       # Conexión persistente PDO (utf8mb4, excepciones y ruta base)
├── controller/            # Controladores del sistema (MVC)
│   ├── usuario.php        # Controlador de autenticación (login, logout, sesiones)
│   └── ticket.php         # Controlador de tickets (inserción, combos y listados)
├── docs/                  # Scripts SQL y documentación técnica
│   └── database.sql       # Script de creación de tablas y datos iniciales
├── model/                 # Modelos de datos desacoplados de HTTP
│   ├── Usuarios.php       # Consultas preparadas, password_verify y password_hash
│   ├── Categorias.php     # Consulta de categorías activas
│   └── Tickets.php        # CRUD de tickets y conteo de estados para dashboard
├── public/                # Archivos estáticos públicos (CSS, JS, imágenes, fuentes)
│   ├── css/               # Hojas de estilo (Clean UI, Summernote, SweetAlert)
│   ├── js/                # Librerías (jQuery, Bootstrap, plugins) y app.js corregido
│   └── img/
└── view/                  # Vistas del sistema
    ├── includes/          # Componentes reutilizables maestros
    │   ├── head.php       # Metas, títulos y llamadas CSS
    │   ├── header.php     # Barra superior con perfil y menú de usuario
    │   ├── nav.php        # Menú lateral dinámico con indicador de página activa
    │   ├── scripts.php    # Scripts base JS
    │   └── seguridad.php  # Middleware de protección de rutas privadas
    ├── home/              # Panel principal protegido con métricas en tiempo real
    │   └── home.php
    ├── NuevoTicket/       # Formulario y vista de creación de ticket
    │   ├── N_ticket.php   # Formulario con editor Summernote
    │   └── N_ticket.js    # Lógica frontend y validación con SweetAlert
    └── NuevoRegistro/     # Registro de nuevos usuarios
```

---

## 🗺️ Hoja de Ruta y Futuras Mejoras (Roadmap del Curso y Proyecto)

Este plan de desarrollo cubre integralmente el temario práctico del curso, elevando cada entrega a estándares profesionales de ingeniería de software:

### 🎯 Fase 1: Consolidación de Autenticación y Seguridad *(COMPLETADA)*
- [x] Maquetación de la pantalla de Login con plantilla responsiva.
- [x] Implementación de arquitectura MVC real (separación entre `index.php`, `controller/usuario.php` y `model/Usuarios.php`).
- [x] Implementación de `password_hash()` y `password_verify()` con hashes bcrypt verificados.
- [x] Manejo de sesiones seguras (`session_regenerate_id(true)` para mitigar fijación de sesión).
- [x] Middleware de protección de rutas privadas (`view/includes/seguridad.php`).
- [x] Panel de inicio funcional en `view/home/home.php` con perfil de usuario y cierre de sesión seguro.
- [x] Corrección del bug de eventos jQuery en la plantilla administrativa (`public/js/app.js`).
- [x] Mensajes y alertas de feedback dinámicas en la vista de login.

### 🎯 Fase 2: Creación y Gestión de Tickets *(COMPLETADA)*
- [x] Modelo de Categorías (`model/Categorias.php`) con listado dinámico.
- [x] Modelo de Tickets (`model/Tickets.php`) con inserción, consulta y conteo para dashboard.
- [x] Controlador de tickets (`controller/ticket.php?op=insertar`) con validación obligatoria.
- [x] Componentes de interfaz reutilizables (`view/includes/`: `head.php`, `header.php`, `nav.php`, `scripts.php`).
- [x] Formulario de creación de ticket en `view/NuevoTicket/N_ticket.php` con editor Summernote.
- [x] Validación previa al envío con SweetAlert en `view/NuevoTicket/N_ticket.js`.
- [x] Conexión de contadores del Dashboard (`view/home/home.php`) a la base de datos real.

### 🎯 Fase 3: Stored Procedures y Bandeja con DataTables *(PRÓXIMA FASE)*
- [ ] Creación e invocación de Procedimientos Almacenados en MySQL (`sp_l_ticket_usu`, `sp_l_ticket`, `sp_l_categoria`).
- [ ] Bandeja interactiva "Mis Tickets" con DataTables (búsqueda, ordenamiento y paginación AJAX).
- [ ] Badges y etiquetas visuales con colores según el estado (*Abierto*, *En Proceso*, *En Espera*, *Cerrado*).

### 🎯 Fase 4: Adjuntos en Tickets y Seguimiento de Conversación
- [ ] Hilo de respuestas / comentarios bidireccional entre usuario y técnico (`ticket_detalles`).
- [ ] Carga y descarga segura de archivos adjuntos (imágenes, PDFs, capturas) en tickets (`ticket_archivos`).

### 🎯 Fase 5: Composer, PHPMailer y Recuperación de Clave con OpenSSL
- [ ] Configuración del gestor de dependencias **Composer**.
- [ ] Alertas automáticas por correo electrónico con **PHPMailer** al crear, responder o cerrar tickets.
- [ ] Módulo de recuperación de contraseña vía email con tokens cifrados mediante **OpenSSL**.

### 🎯 Fase 6: Asignación de Tickets y CRUD Completo de Usuarios
- [ ] Módulo de administración para asignar tickets a técnicos de soporte.
- [ ] CRUD completo de usuarios (crear, listar, editar, cambiar rol y activar/inactivar).

### 🎯 Fase 7: Dashboard Gráfico y Calendario de Incidentes
- [ ] Gráficos interactivos con JavaScript para volumen de tickets y rendimiento por categoría.
- [ ] Visualización de incidentes y fechas de cierre mediante **FullCalendar JS**.

### 🎯 Fase 8: Encuesta de Satisfacción de Atención
- [ ] Formulario de evaluación de calidad de servicio (calificación de 1 a 5 estrellas y comentarios) al cerrar un ticket.
- [ ] Reporte de satisfacción del cliente.

### 🎯 Fase 9: Notificaciones por WhatsApp y Respuestas con IA (ChatGPT)
- [ ] Integración con API de WhatsApp para notificación automática al crear, asignar y cerrar tickets.
- [ ] Integración con la API de OpenAI (ChatGPT) para sugerir respuestas inteligentes automáticas en tickets.

### 🎯 Fase 10: Mockups del Sistema y Despliegue en Hosting de Producción
- [ ] Documentación de Mockups y arquitectura final.
- [ ] Preparación de variables de entorno (`.env`) y migración a hosting web de producción.

---

## 🤝 Buenas Prácticas para el Desarrollo

1. **Separación de Responsabilidades:** Nunca colocar consultas SQL directamente en las Vistas ni en los Controladores. Toda interacción con la base de datos pertenece a la capa `model/`.
2. **Seguridad primero:** Siempre utiliza consultas preparadas con PDO (`prepare` y `execute`) y jamás concatenes variables en sentencias SQL.
3. **Control de Versiones:** Realiza commits atómicos y descriptivos siguiendo la convención de [Conventional Commits](https://www.conventionalcommits.org/) (`feat:`, `fix:`, `docs:`, `refactor:`).
