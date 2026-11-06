# 🎫 HelpDesk - Sistema de Gestión de Tickets y Mesa de Ayuda

Sistema web de gestión de incidencias, tickets de soporte técnico y control de usuarios desarrollado en **PHP** y **MySQL**, diseñado bajo el patrón de arquitectura **MVC (Modelo-Vista-Controlador)** con estándares de la industria y enfoque en seguridad.

---

## 📌 Estado del Proyecto

- **Fase Actual:** `Fase 1 - Autenticación, Seguridad y Arquitectura Base (COMPLETADA Y VERIFICADA)`.
- **Próximo Objetivo:** `Fase 2 - Módulo de Creación y Gestión de Tickets`.
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
│   └── usuario.php        # Controlador de autenticación (login, logout, sesiones)
├── docs/                  # Scripts SQL y documentación técnica
│   └── database.sql       # Script de creación y datos iniciales verificados
├── model/                 # Modelos de datos desacoplados de HTTP
│   └── Usuarios.php       # Consultas preparadas, password_verify y password_hash
├── public/                # Archivos estáticos públicos (CSS, JS, imágenes, fuentes)
│   ├── css/
│   ├── js/                # Librerías (jQuery, Bootstrap, plugins) y app.js corregido
│   └── img/
└── view/                  # Vistas del sistema
    ├── includes/          # Componentes reutilizables
    │   └── seguridad.php  # Middleware de protección de rutas privadas
    ├── home/              # Panel principal protegido con perfil dinámico
    │   └── home.php
    ├── NuevoTicket/       # Formulario y vista de nuevo ticket
    └── NuevoRegistro/     # Registro de nuevos usuarios
```

---

## 🗺️ Hoja de Ruta y Futuras Mejoras (Roadmap)

### 🎯 Fase 1: Consolidación de Autenticación y Seguridad *(COMPLETADA)*
- [x] Maquetación de la pantalla de Login con plantilla responsiva.
- [x] Implementación de arquitectura MVC real (separación entre `index.php`, `controller/usuario.php` y `model/Usuarios.php`).
- [x] Implementación de `password_hash()` y `password_verify()` con hashes bcrypt verificados.
- [x] Manejo de sesiones seguras (`session_regenerate_id(true)` para mitigar fijación de sesión).
- [x] Middleware de protección de rutas privadas (`view/includes/seguridad.php`).
- [x] Panel de inicio funcional en `view/home/home.php` con perfil de usuario y cierre de sesión seguro.
- [x] Corrección del bug de eventos jQuery en la plantilla administrativa (`public/js/app.js`).
- [x] Mensajes y alertas de feedback dinámicas en la vista de login.

### 🎯 Fase 2: Creación y Gestión de Tickets *(PRÓXIMA FASE)*
- [ ] Modelo de Categorías (`model/Categorias.php`) con listado dinámico.
- [ ] Formulario de creación de ticket en `view/NuevoTicket/N_ticket.php` con editor Summernote.
- [ ] Controlador de tickets (`controller/ticket.php?op=insertar`) con validación y sanitización.
- [ ] Asignación de prioridad (Baja, Media, Alta, Urgente) y asociación al usuario en sesión.

### 🎯 Fase 3: Bandeja de Tickets y Estados
- [ ] Bandeja interactiva con DataTables (búsqueda, ordenamiento y paginación AJAX).
- [ ] Filtrado por estado (*Abierto*, *En Proceso*, *En Espera*, *Cerrado*).
- [ ] Vista detallada del ticket con historial de cambios.

### 🎯 Fase 4: Comentarios, Trazabilidad y Adjuntos
- [ ] Hilo de respuestas e interacción entre usuario solicitante y técnico.
- [ ] Carga segura de archivos adjuntos (imágenes, documentos, logs).
- [ ] Notificaciones por correo electrónico mediante **PHPMailer**.

### 🎯 Fase 5: Dashboard Estadístico y Métricas
- [ ] Tarjetas con contadores en tiempo real (Abiertos, En Proceso, Cerrados).
- [ ] Gráficos interactivos de rendimiento por categoría y técnico.
- [ ] Exportación de reportes a PDF y Excel.

### 🎯 Fase 6: Madurez de Ingeniería de Software
- [ ] Implementación de **Composer** para carga automática de clases (PSR-4 Autoloading).
- [ ] Migración de variables sensibles a un archivo `.env` (`vlucas/phpdotenv`).
- [ ] Pruebas automatizadas con **PHPUnit** o **Pest**.

---

## 🤝 Buenas Prácticas para el Desarrollo

1. **Separación de Responsabilidades:** Nunca colocar consultas SQL directamente en las Vistas ni en los Controladores. Toda interacción con la base de datos pertenece a la capa `model/`.
2. **Seguridad primero:** Siempre utiliza consultas preparadas con PDO (`prepare` y `execute`) y jamás concatenes variables en sentencias SQL.
3. **Control de Versiones:** Realiza commits atómicos y descriptivos siguiendo la convención de [Conventional Commits](https://www.conventionalcommits.org/) (`feat:`, `fix:`, `docs:`, `refactor:`).
