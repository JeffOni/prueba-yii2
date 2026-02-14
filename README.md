# Sistema de Gestión de Inventario - Yii2

Sistema de gestión de inventario desarrollado con **Yii2 Advanced Template** que implementa un CRUD completo de productos y usuarios con control de acceso basado en roles (RBAC).

## 📋 Tabla de Contenidos

- [Características](#-características)
- [Requisitos](#-requisitos)
- [Instalación](#-instalación)
- [Configuración](#-configuración)
- [Base de Datos](#-base-de-datos)
- [Roles y Permisos](#-roles-y-permisos)
- [Credenciales de Acceso](#-credenciales-de-acceso)
- [Uso](#-uso)
- [Herramientas de Prueba de API](#-herramientas-de-prueba-de-api-opcional)
- [Estructura del Proyecto](#-estructura-del-proyecto)

## ✨ Características

### Requisitos Funcionales Implementados

- **RF-01**: Usuario administrador con contraseña encriptada (bcrypt)
- **RF-02**: CRUD de usuarios (solo para administradores)
- **RF-03**: Gestión de roles: Admin, Editor, Viewer
- **RF-04**: Asignación de roles con auditoría de cambios
- **RF-05**: Autenticación con login/logout y protección CSRF
- **RF-06**: CRUD de productos con control de acceso
- **RF-07**: Listado con filtros, búsqueda por nombre/SKU y paginación (20 items/página)
- **RF-08**: UI responsive con Bootstrap 5 y mensajes flash

### Funcionalidades Adicionales

- **Soft Delete**: Los usuarios marcados como eliminados mantienen su historial
- **Audit Log**: Registro de cambios en roles y usuarios
- **Validaciones**: Formularios con validación del lado cliente y servidor
- **Pretty URLs**: URLs amigables sin `index.php`
- **Docker**: Entorno completamente dockerizado

## 🛠 Requisitos

### Requisitos Técnicos

- **Docker Desktop** >= 20.10
- **Docker Compose** >= 2.0
- **Git**
- **WSL 2** (solo para Windows)

### Versiones de Software

- PHP 8.2
- MySQL 8.0
- Yii2 ~2.0.45
- Bootstrap 5.2
- phpMyAdmin (para gestión de base de datos)

## 📦 Instalación

### 1. Clonar el Repositorio

```bash
git clone https://github.com/JeffOni/prueba-yii2.git
cd prueba-yii2
```

### 2. Levantar el Stack con Docker

```bash
docker compose up
```

Este único comando se encarga de todo automáticamente:

- Construye las imágenes con **multi-stage build** (instala dependencias Composer, inicializa Yii2)
- Levanta MySQL 8.0 con healthcheck
- Espera a que MySQL esté listo antes de continuar
- Ejecuta las migraciones RBAC (tablas de roles y permisos)
- Ejecuta las migraciones de la aplicación (user, product, audit_log)
- Ejecuta los seeders (usuario admin, roles, productos de ejemplo)
- Inicia Apache para backend y frontend

**No se requiere** copiar `.env`, ejecutar `composer install`, ni correr migraciones manualmente. Todo tiene valores por defecto para entornos de prueba/local.

### Servicios disponibles

| Servicio           | URL                    | Puerto |
| ------------------ | ---------------------- | ------ |
| Backend (Admin)    | http://localhost:21080 | 21080  |
| Frontend (Público) | http://localhost:20080 | 20080  |
| phpMyAdmin         | http://localhost:8080  | 8080   |
| MySQL              | localhost:3306         | 3306   |

### Variables de entorno (opcionales)

Todas las variables tienen **valores por defecto**. Si se necesita personalizar, crear un archivo `.env` en la raíz (ver `.env.example`):

```bash
DB_HOST=mysql                    # default: mysql
DB_NAME=inventory_system         # default: inventory_system
DB_USER=yii2user                 # default: yii2user
DB_PASSWORD=yii2pass             # default: yii2pass
MYSQL_ROOT_PASSWORD=verysecret   # default: verysecret
JWT_SECRET_KEY=your-key-here     # default: your-jwt-secret-key-here
JWT_EXPIRE_TIME=3600             # default: 3600
```

## 🗄️ Base de Datos

### Tablas Principales

#### `user`

Almacena información de usuarios del sistema.

| Campo         | Tipo         | Descripción                                |
| ------------- | ------------ | ------------------------------------------ |
| id            | INT          | ID único del usuario                       |
| username      | VARCHAR(255) | Nombre de usuario único                    |
| email         | VARCHAR(255) | Correo electrónico único                   |
| password_hash | VARCHAR(255) | Contraseña encriptada (bcrypt)             |
| auth_key      | VARCHAR(32)  | Token para "recordarme"                    |
| status        | SMALLINT     | Estado: 0=Eliminado, 9=Inactivo, 10=Activo |
| created_at    | INT          | Timestamp de creación                      |
| updated_at    | INT          | Timestamp de última actualización          |

#### `product`

Gestiona el inventario de productos.

| Campo       | Tipo          | Descripción                       |
| ----------- | ------------- | --------------------------------- |
| id          | INT           | ID único del producto             |
| name        | VARCHAR(255)  | Nombre del producto               |
| description | TEXT          | Descripción detallada             |
| sku         | VARCHAR(100)  | Código SKU único                  |
| price       | DECIMAL(10,2) | Precio del producto               |
| stock       | INT           | Cantidad en inventario            |
| status      | SMALLINT      | Estado: 0=Inactivo, 1=Activo      |
| created_at  | INT           | Timestamp de creación             |
| updated_at  | INT           | Timestamp de última actualización |

#### `audit_log`

Registra cambios en roles y usuarios para auditoría.

| Campo      | Tipo         | Descripción                          |
| ---------- | ------------ | ------------------------------------ |
| id         | INT          | ID único del registro                |
| user_id    | INT          | ID del usuario que realizó la acción |
| action     | VARCHAR(100) | Tipo de acción realizada             |
| table_name | VARCHAR(100) | Tabla afectada                       |
| record_id  | INT          | ID del registro afectado             |
| old_value  | TEXT         | Valor anterior                       |
| new_value  | TEXT         | Nuevo valor                          |
| ip_address | VARCHAR(45)  | IP del usuario                       |
| user_agent | VARCHAR(255) | Navegador del usuario                |
| created_at | INT          | Timestamp de la acción               |

## 🔐 Roles y Permisos

### Jerarquía de Roles

```
Admin
  └── Editor
        └── Viewer
```

### Permisos por Rol

| Permiso       | Admin | Editor | Viewer |
| ------------- | ----- | ------ | ------ |
| **Productos** |
| viewProduct   | ✅    | ✅     | ✅     |
| createProduct | ✅    | ✅     | ❌     |
| updateProduct | ✅    | ✅     | ❌     |
| deleteProduct | ✅    | ❌     | ❌     |
| **Usuarios**  |
| viewUser      | ✅    | ❌     | ❌     |
| createUser    | ✅    | ❌     | ❌     |
| updateUser    | ✅    | ❌     | ❌     |
| deleteUser    | ✅    | ❌     | ❌     |
| manageRoles   | ✅    | ❌     | ❌     |

### Descripción de Roles

- **Admin**: Acceso completo al sistema. Puede gestionar usuarios, productos y asignar roles.
- **Editor**: Puede crear y modificar productos, pero no eliminarlos ni gestionar usuarios.
- **Viewer**: Solo puede visualizar productos. Acceso de solo lectura.

## 🔑 Credenciales de Acceso

### Usuario Administrador Predeterminado

```
URL: http://localhost:21080
Usuario: admin
Contraseña: Admin123!
Rol: Admin
```

### Base de Datos MySQL

```
Host: mysql (desde Docker) / localhost:3306 (desde host)
Base de datos: inventory_system
Usuario: yii2user
Contraseña: yii2pass
```

### phpMyAdmin

```
URL: http://localhost:8080
Servidor: mysql
Usuario: yii2user
Contraseña: yii2pass
```

## 🚀 Uso

### 1. Iniciar Sesión

Acceder a http://localhost:21080 e iniciar sesión con las credenciales del administrador.

### 2. Gestión de Productos

- **Listar Productos**: `/product/index`
- **Ver Producto**: `/product/view?id={id}`
- **Crear Producto**: `/product/create`
- **Editar Producto**: `/product/update?id={id}`
- **Eliminar Producto**: Botón en listado (solo Admin)

#### Filtros Disponibles

- Búsqueda por nombre del producto
- Búsqueda por SKU
- Filtro por stock
- Filtro por estado (Activo/Inactivo)

### 3. Gestión de Usuarios

- **Listar Usuarios**: `/user/index`
- **Ver Usuario**: `/user/view?id={id}`
- **Crear Usuario**: `/user/create`
- **Editar Usuario**: `/user/update?id={id}`
- **Eliminar Usuario**: Botón en listado (soft delete)

#### Asignación de Roles

Al crear o editar un usuario, seleccionar el rol apropiado:

- Admin
- Editor
- Viewer

Los cambios de rol son registrados automáticamente en `audit_log`.

### 4. Auditoría

Consultar la tabla `audit_log` en phpMyAdmin para ver:

- Cambios de roles
- Creación de usuarios
- Modificaciones de usuarios
- Eliminaciones de usuarios

Cada registro incluye:

- Usuario que realizó la acción
- Fecha y hora
- Dirección IP
- Navegador utilizado
- Valores anteriores y nuevos

## 🧪 Herramientas de Prueba de API (Opcional)

El proyecto incluye herramientas auxiliares para facilitar las pruebas de la API RESTful:

### **test-api.html** - Probador Visual de API

Interfaz web interactiva para probar todos los endpoints de la API sin necesidad de Postman.

**Cómo usar:**

1. Abrir el archivo en el navegador: `file:///c:/laragon/www/prueba-yii2/test-api.html`
2. Hacer clic en "🔑 Obtener Token" (credenciales: admin/Admin123!)
3. Probar los diferentes endpoints:
   - Listar productos
   - Crear producto
   - Ver producto
   - Verificar token JWT

**Características:**

- ✅ Interfaz Bootstrap 5 responsive
- ✅ Muestra respuestas JSON formateadas
- ✅ Gestión automática de token JWT
- ✅ Indicadores visuales de éxito/error

### **test-api.sh** - Script Bash para Pruebas

Script bash que ejecuta pruebas automatizadas de la API.

**Cómo usar:**

```bash
# En WSL o Git Bash
bash test-api.sh
```

**Requisitos:**

- `curl` instalado
- `jq` instalado (opcional, para formatear JSON)
- Docker corriendo con el backend activo

**Nota:** Estas herramientas son **opcionales** y solo están incluidas para facilitar la evaluación. La documentación formal de la API está en `API_DOCUMENTATION.md`.

## 📁 Estructura del Proyecto

```
prueba-yii2/
├── backend/                    # Aplicación de administración
│   ├── controllers/            # Controladores del backend
│   │   ├── ProductController.php
│   │   ├── UserController.php
│   │   └── SiteController.php
│   ├── models/                 # Modelos específicos del backend
│   │   ├── ProductSearch.php
│   │   └── UserSearch.php
│   ├── views/                  # Vistas del backend
│   │   ├── product/
│   │   ├── user/
│   │   └── layouts/
│   ├── web/                    # Assets públicos
│   └── Dockerfile              # Multi-stage build para backend
├── common/                     # Código compartido
│   ├── config/                 # Configuraciones compartidas
│   ├── models/                 # Modelos compartidos
│   │   ├── User.php
│   │   └── Product.php
│   └── mail/                   # Plantillas de email
├── console/                    # Aplicación de consola
│   ├── migrations/             # Migraciones de base de datos
│   │   ├── m260206_223344_create_product_table.php
│   │   ├── m260206_223353_create_audit_log_table.php
│   │   ├── m260206_223403_seed_admin_user.php
│   │   ├── m260206_223415_seed_rbac_data.php
│   │   └── m260206_223424_seed_sample_products.php
│   └── controllers/            # Comandos de consola
├── frontend/                   # Aplicación pública
│   └── Dockerfile              # Multi-stage build para frontend
├── docker/
│   └── mysql/
│       └── init.sql            # Script de inicialización de MySQL
├── environments/               # Configuraciones por entorno (dev/prod)
├── docker-compose.yml          # Orquestación de servicios Docker
├── docker-entrypoint.sh        # Script de inicialización (migraciones, Apache)
├── .dockerignore               # Exclusiones del build context
├── .env.example                # Plantilla de variables de entorno
├── composer.json               # Dependencias PHP
├── composer.lock               # Versiones fijas de dependencias
└── README.md                   # Este archivo
```

## 🐳 Comandos Docker Útiles

### Ver Logs

```bash
# Logs del backend
docker-compose logs -f backend

# Logs de MySQL
docker-compose logs -f mysql
```

### Reiniciar Servicios

```bash
docker-compose restart
```

### Detener Servicios

```bash
docker-compose down
```

### Acceder al Contenedor

```bash
# Acceder al backend
docker-compose exec backend bash

# Acceder a MySQL
docker-compose exec mysql mysql -u yii2user -p
```

### Limpiar y Reconstruir

```bash
docker-compose down -v
docker-compose up -d --build
```

## 🧪 Comandos Yii2 Útiles

### Migraciones

```bash
# Ver migraciones aplicadas
docker-compose exec backend php yii migrate/history

# Revertir última migración
docker-compose exec backend php yii migrate/down

# Crear nueva migración
docker-compose exec backend php yii migrate/create nombre_migracion
```

### Cache

```bash
# Limpiar cache
docker-compose exec backend php yii cache/flush-all
```

### RBAC

```bash
# Ver asignaciones de roles
docker-compose exec backend php yii migrate --migrationPath=@yii/rbac/migrations
```

## 📝 Notas Importantes

1. **Docker Compose**: El comando `docker compose up` levanta todo el stack automáticamente (build, migraciones, seeders). No requiere pasos manuales ni archivo `.env`.
2. **Multi-stage Build**: Los Dockerfiles usan multi-stage build para optimizar las imágenes (stage builder + stage production).
3. **Variables de entorno**: Todas tienen valores por defecto (fallbacks) para entornos de prueba/local, configurables via `.env` si se necesita.
4. **Protección del Usuario Admin**: El usuario admin principal (ID: 1) no puede ser eliminado desde la interfaz.
5. **Soft Delete**: Los usuarios eliminados mantienen su registro en la base de datos con `status=0`.
6. **Auditoría**: Todos los cambios en roles y usuarios son registrados automáticamente.
7. **Validaciones**: El sistema implementa validaciones tanto del lado del servidor como del cliente.
8. **CSRF**: Todas las acciones POST/DELETE están protegidas contra CSRF.
9. **Pretty URLs**: Las URLs no incluyen `index.php` gracias a la configuración del urlManager.

## 🔧 Solución de Problemas

### Error: "Connection refused" al conectar a MySQL

Asegúrese de que el servicio MySQL esté corriendo:

```bash
docker-compose ps
```

### Error: "Target class ... does not exist"

Ejecutar composer install:

```bash
docker-compose exec backend composer install
```

### Error: "Table doesn't exist"

Ejecutar las migraciones:

```bash
docker-compose exec backend php yii migrate --interactive=0
```

## 👨‍💻 Autor

**Jefferson Pozo Bohórquez**
Prueba Técnica - Yii2 Framework
GitHub: [@JeffOni](https://github.com/JeffOni)

## 📄 Licencia

Este proyecto utiliza la licencia BSD-3-Clause proporcionada por Yii Framework.

---

**Fecha de Desarrollo**: Febrero 2026
**Framework**: Yii2 Advanced Template 2.0.45
**Versión de PHP**: 8.2
**Base de Datos**: MySQL 8.0
