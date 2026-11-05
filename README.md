# 🎫 HelpDesk - Sistema de Gestión de Tickets y Mesa de Ayuda

Sistema web de gestión de incidencias, tickets de soporte y control de usuarios desarrollado en **PHP** y **MySQL**, diseñado bajo el patrón de arquitectura **MVC (Modelo-Vista-Controlador)**.

---

## 📌 Estado del Proyecto

- **Fase Actual:** `Fase 1 - Configuración Inicial, Arquitectura Base y Módulo de Autenticación`.
- **Servidor recomendado:** [Laragon](https://laragon.org/) (Apache + MySQL/MariaDB + PHP 8.x).

---

## 🛠️ Stack Tecnológico

| Capa | Tecnología |
| :--- | :--- |
| **Backend** | PHP 8.0+ (PDO, Programación Orientada a Objetos) |
| **Base de Datos** | MySQL 5.7+ / MariaDB 10.4+ |
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
2. Crea una nueva base de datos llamada:
   ```sql
   CREATE DATABASE helpdesk_tecnostar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
3. Importa el script SQL inicial ubicado en:
   ```
   docs/database.sql
   ```
   *(Este archivo contiene la estructura de la tabla `usuarios` y los registros de prueba iniciales).*

---

### Paso 4: Configurar la Conexión (`config/conexion.php`)

Verifica que las credenciales en `config/conexion.php` coincidan con tu entorno local:

```php
$conexion = new PDO(
    "mysql:host=localhost;dbname=helpdesk_tecnostar;charset=utf8mb4",
    "root", // Usuario por defecto de Laragon
    ""      // Contraseña por defecto (vacía en Laragon)
);
```

> **Nota:** La URL base configurada es `http://localhost/PERSONAL_HelpDesk/`. Si utilizas Virtual Hosts de Laragon, puedes adaptarla a `http://personal-helpdesk.test/`.

---

### Paso 5: Ejecutar la Aplicación

Abre tu navegador e ingresa a:
```text
http://localhost/PERSONAL_HelpDesk/
```
*(O `http://personal-helpdesk.test/` si Laragon generó el Virtual Host automático).*

---

### 🔑 Credenciales de Prueba Iniciales

Una vez importado el archivo `docs/database.sql`, puedes iniciar sesión con las siguientes cuentas:

| Rol | Correo Electrónico | Contraseña |
| :--- | :--- | :--- |
| **Administrador** | `admin@tecnostar.com` | `admin123` |
| **Técnico / Soporte**| `soporte@tecnostar.com` | `soporte123` |
| **Usuario / Cliente**| `usuario@tecnostar.com` | `usuario123` |

---

## 📂 Estructura del Directorio

```text
PERSONAL_HelpDesk/
├── AGENT.md               # Contexto, directrices y reglas para Asistentes de IA
├── README.md              # Documentación principal del proyecto
├── index.php              # Punto de entrada / Vista de Login
├── config/                # Configuraciones globales y conexión a Base de Datos
│   └── conexion.php
├── controller/            # Controladores (manejo de peticiones HTTP y lógica de flujo)
├── docs/                  # Scripts SQL, diagramas y documentación técnica
│   └── database.sql
├── model/                 # Modelos (lógica de datos, consultas a BD y entidades)
│   └── Usuarios.php
├── public/                # Archivos estáticos públicos (CSS, JS, imágenes, fuentes)
│   ├── css/
│   ├── js/
│   └── img/
└── view/                  # Vistas HTML / PHP del sistema
    ├── home/              # Panel principal una vez autenticado
    ├── NuevoTicket/       # Formulario y vista de nuevo ticket
    └── NuevoRegistro/     # Registro de nuevos usuarios
```

---

## 🗺️ Hoja de Ruta y Futuras Mejoras (Roadmap)

Este proyecto está diseñado para evolucionar desde una base funcional hacia una aplicación con estándares profesionales de la industria:

### 🎯 Fase 1: Consolidación de Autenticación y Seguridad
- [x] Maquetación de la pantalla de Login con plantilla responsiva.
- [ ] Implementación de `password_hash()` y `password_verify()` en todo el flujo de login.
- [ ] Manejo de sesiones seguras (`session_regenerate_id()`, protección de rutas con middleware/verificador de sesión).
- [ ] Protección contra ataques **CSRF** mediante tokens en formularios.
- [ ] Sanitización y validación estricta de entradas (XSS, SQL Injection).

### 🎯 Fase 2: Gestión de Usuarios y Control de Acceso (RBAC)
- [ ] CRUD completo de usuarios (crear, editar, activar/desactivar, listar).
- [ ] Roles diferenciados: **Administrador**, **Técnico**, **Usuario solicitante**.
- [ ] Menús dinámicos y permisos basados en el rol del usuario logueado.

### 🎯 Fase 3: Núcleo de Gestión de Tickets (HelpDesk Core)
- [ ] Creación de tickets con título, descripción enriquecida (Summernote / TinyMCE), categoría y prioridad (Baja, Media, Alta, Urgente).
- [ ] Bandeja de tickets con DataTables (búsqueda, paginación, filtros por estado).
- [ ] Ciclo de vida del ticket: *Abierto*, *En Proceso*, *En Espera*, *Resuelto*, *Cerrado*.
- [ ] Asignación de tickets a técnicos de soporte.

### 🎯 Fase 4: Trazabilidad, Adjuntos y Notificaciones
- [ ] Historial de cambios y seguimiento temporal de cada ticket.
- [ ] Hilo de comentarios y respuestas entre usuario y técnico.
- [ ] Carga de archivos adjuntos (capturas de pantalla, logs, PDFs).
- [ ] Envío automático de notificaciones por correo electrónico (con **PHPMailer**).

### 🎯 Fase 5: Métricas y Business Intelligence
- [ ] Dashboard con tarjetas estadísticas (Tickets abiertos, resueltos, tiempo promedio de respuesta).
- [ ] Gráficos interactivos (Chart.js / Morris.js) por categorías, técnicos y volumen mensual.
- [ ] Exportación de reportes a PDF y Excel.

### 🎯 Fase 6: Madurez de Ingeniería de Software
- [ ] Implementación de **Composer** para carga automática de clases (PSR-4 Autoloading).
- [ ] Migración de variables sensibles a un archivo `.env` (`vlucas/phpdotenv`).
- [ ] Enrutamiento centralizado mediante un Front Controller y Router amigable.
- [ ] Pruebas unitarias con **PHPUnit** o **Pest**.

---

## 🤝 Buenas Prácticas para el Desarrollo

1. **Separación de Responsabilidades:** Nunca colocar consultas SQL directamente en las Vistas ni en los Controladores. Toda interacción con la base de datos pertenece a la capa `model/`.
2. **Seguridad primero:** Siempre utiliza consultas preparadas con PDO (`prepare` y `execute`) y jamás concatenes variables en sentencias SQL.
3. **Control de Versiones:** Realiza commits atómicos y descriptivos siguiendo la convención de [Conventional Commits](https://www.conventionalcommits.org/) (`feat:`, `fix:`, `docs:`, `refactor:`).

