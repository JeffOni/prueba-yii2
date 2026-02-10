# API RESTful - Documentación

Sistema de inventario con API RESTful y autenticación JWT.

## 📋 Tabla de Contenidos

- [Autenticación](#autenticación)
- [Endpoints de Productos](#endpoints-de-productos)
- [Endpoints de Usuarios](#endpoints-de-usuarios)
- [Códigos de Respuesta](#códigos-de-respuesta)
- [Ejemplos con cURL](#ejemplos-con-curl)

## 🔐 Autenticación

La API utiliza **JWT (JSON Web Tokens)** para la autenticación. Todos los endpoints (excepto `/auth/login`) requieren un token válido en el header `Authorization`.

### **POST /api/v1/auth/login**

Obtener token JWT con credenciales de usuario.

**Request:**

```http
POST /api/v1/auth/login
Content-Type: application/json

{
  "username": "admin",
  "password": "Admin123!"
}
```

**Response (200 OK):**

```json
{
  "success": true,
  "message": "Login exitoso",
  "data": {
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "user": {
      "id": 1,
      "username": "admin",
      "email": "admin@example.com",
      "status": 10
    }
  }
}
```

**Response (401 Unauthorized):**

```json
{
  "success": false,
  "message": "Credenciales inválidas"
}
```

### **POST /api/v1/auth/verify**

Verificar si un token JWT es válido.

**Request:**

```http
POST /api/v1/auth/verify
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
```

**Response (200 OK):**

```json
{
  "success": true,
  "message": "Token válido",
  "data": {
    "iat": 1707500000,
    "exp": 1707503600,
    "user_id": 1,
    "username": "admin",
    "email": "admin@example.com"
  }
}
```

---

## 📦 Endpoints de Productos

Todos los endpoints requieren autenticación JWT y permisos RBAC.

### **GET /api/v1/products**

Listar todos los productos (paginado).

**Permisos requeridos:** `viewProduct` (Admin, Editor, Viewer)

**Request:**

```http
GET /api/v1/products
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
```

**Query Parameters:**

- `page` (int): Número de página (default: 1)
- `per-page` (int): Elementos por página (default: 20)

**Response (200 OK):**

```json
{
  "items": [
    {
      "id": 1,
      "name": "Laptop Dell Inspiron 15",
      "description": "Laptop para trabajo y estudio...",
      "sku": "TECH-001",
      "price": "799.99",
      "stock": 15,
      "status": 1,
      "created_at": 1707400000,
      "updated_at": 1707400000
    },
    ...
  ],
  "_links": {
    "self": {"href": "http://localhost:21080/api/v1/products?page=1"},
    "next": {"href": "http://localhost:21080/api/v1/products?page=2"}
  },
  "_meta": {
    "totalCount": 15,
    "pageCount": 1,
    "currentPage": 1,
    "perPage": 20
  }
}
```

### **GET /api/v1/products/{id}**

Ver detalles de un producto específico.

**Permisos requeridos:** `viewProduct`

**Request:**

```http
GET /api/v1/products/1
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
```

**Response (200 OK):**

```json
{
  "id": 1,
  "name": "Laptop Dell Inspiron 15",
  "description": "Laptop para trabajo y estudio...",
  "sku": "TECH-001",
  "price": "799.99",
  "stock": 15,
  "status": 1,
  "created_at": 1707400000,
  "updated_at": 1707400000
}
```

**Response (404 Not Found):**

```json
{
  "name": "Not Found",
  "message": "Object not found: 999",
  "code": 0,
  "status": 404
}
```

### **POST /api/v1/products**

Crear un nuevo producto.

**Permisos requeridos:** `createProduct` (Admin, Editor)

**Request:**

```http
POST /api/v1/products
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
Content-Type: application/json

{
  "name": "Mouse Logitech MX Master 3",
  "description": "Mouse ergonómico inalámbrico",
  "sku": "TECH-016",
  "price": 99.99,
  "stock": 50,
  "status": 1
}
```

**Response (201 Created):**

```json
{
  "id": 16,
  "name": "Mouse Logitech MX Master 3",
  "description": "Mouse ergonómico inalámbrico",
  "sku": "TECH-016",
  "price": "99.99",
  "stock": 50,
  "status": 1,
  "created_at": 1707500000,
  "updated_at": 1707500000
}
```

**Response (422 Unprocessable Entity - Validación):**

```json
[
  {
    "field": "sku",
    "message": "Este SKU ya está registrado."
  },
  {
    "field": "price",
    "message": "El precio debe ser mayor a 0."
  }
]
```

### **PUT /api/v1/products/{id}**

Actualizar un producto existente (actualización completa).

**Permisos requeridos:** `updateProduct` (Admin, Editor)

**Request:**

```http
PUT /api/v1/products/16
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
Content-Type: application/json

{
  "name": "Mouse Logitech MX Master 3S",
  "description": "Mouse ergonómico inalámbrico - Última versión",
  "sku": "TECH-016",
  "price": 109.99,
  "stock": 45,
  "status": 1
}
```

**Response (200 OK):**

```json
{
  "id": 16,
  "name": "Mouse Logitech MX Master 3S",
  "description": "Mouse ergonómico inalámbrico - Última versión",
  "sku": "TECH-016",
  "price": "109.99",
  "stock": 45,
  "status": 1,
  "created_at": 1707500000,
  "updated_at": 1707500100
}
```

### **PATCH /api/v1/products/{id}**

Actualizar parcialmente un producto.

**Permisos requeridos:** `updateProduct` (Admin, Editor)

**Request:**

```http
PATCH /api/v1/products/16
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
Content-Type: application/json

{
  "stock": 40
}
```

**Response (200 OK):**

```json
{
  "id": 16,
  "name": "Mouse Logitech MX Master 3S",
  "description": "Mouse ergonómico inalámbrico - Última versión",
  "sku": "TECH-016",
  "price": "109.99",
  "stock": 40,
  "status": 1,
  "created_at": 1707500000,
  "updated_at": 1707500200
}
```

### **DELETE /api/v1/products/{id}**

Eliminar un producto.

**Permisos requeridos:** `deleteProduct` (Admin)

**Request:**

```http
DELETE /api/v1/products/16
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
```

**Response (204 No Content)**

Sin cuerpo de respuesta.

---

## 👥 Endpoints de Usuarios

Todos los endpoints de usuarios **solo están disponibles para administradores**.

### **GET /api/v1/users**

Listar todos los usuarios.

**Permisos requeridos:** `viewUser` (Admin)

**Request:**

```http
GET /api/v1/users
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
```

**Response (200 OK):**

```json
{
  "items": [
    {
      "id": 1,
      "username": "admin",
      "email": "admin@example.com",
      "status": 10,
      "created_at": 1707300000,
      "updated_at": 1707300000
    },
    ...
  ],
  "_meta": {
    "totalCount": 5,
    "pageCount": 1,
    "currentPage": 1,
    "perPage": 20
  }
}
```

### **GET /api/v1/users/{id}**

Ver detalles de un usuario.

**Permisos requeridos:** `viewUser` (Admin)

**Request:**

```http
GET /api/v1/users/1
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
```

**Response (200 OK):**

```json
{
  "id": 1,
  "username": "admin",
  "email": "admin@example.com",
  "status": 10,
  "created_at": 1707300000,
  "updated_at": 1707300000
}
```

### **POST /api/v1/users**

Crear un nuevo usuario.

**Permisos requeridos:** `createUser` (Admin)

**Request:**

```http
POST /api/v1/users
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
Content-Type: application/json

{
  "username": "editor1",
  "email": "editor@example.com",
  "password": "Editor123!",
  "status": 10
}
```

**Response (201 Created):**

```json
{
  "id": 6,
  "username": "editor1",
  "email": "editor@example.com",
  "status": 10,
  "created_at": 1707500000,
  "updated_at": 1707500000
}
```

### **PUT /api/v1/users/{id}**

Actualizar datos de un usuario.

**Permisos requeridos:** `updateUser` (Admin)

**Request:**

```http
PUT /api/v1/users/6
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
Content-Type: application/json

{
  "username": "editor1",
  "email": "editor-updated@example.com",
  "status": 10
}
```

**Response (200 OK):**

```json
{
  "id": 6,
  "username": "editor1",
  "email": "editor-updated@example.com",
  "status": 10,
  "created_at": 1707500000,
  "updated_at": 1707500100
}
```

### **DELETE /api/v1/users/{id}**

Eliminar un usuario.

**Permisos requeridos:** `deleteUser` (Admin)

**Request:**

```http
DELETE /api/v1/users/6
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...
```

**Response (204 No Content)**

---

## 📊 Códigos de Respuesta

| Código | Descripción                                      |
| ------ | ------------------------------------------------ |
| 200    | OK - Solicitud exitosa                           |
| 201    | Created - Recurso creado exitosamente            |
| 204    | No Content - Recurso eliminado exitosamente      |
| 400    | Bad Request - Parámetros inválidos               |
| 401    | Unauthorized - Token inválido o no proporcionado |
| 403    | Forbidden - Sin permisos para realizar la acción |
| 404    | Not Found - Recurso no encontrado                |
| 422    | Unprocessable Entity - Errores de validación     |
| 500    | Internal Server Error - Error del servidor       |

---

## 🔧 Ejemplos con cURL

### 1. Obtener token JWT

```bash
curl -X POST http://localhost:21080/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "username": "admin",
    "password": "Admin123!"
  }'
```

### 2. Listar productos

```bash
curl -X GET http://localhost:21080/api/v1/products \
  -H "Authorization: Bearer TU_TOKEN_JWT_AQUI"
```

### 3. Ver un producto

```bash
curl -X GET http://localhost:21080/api/v1/products/1 \
  -H "Authorization: Bearer TU_TOKEN_JWT_AQUI"
```

### 4. Crear un producto

```bash
curl -X POST http://localhost:21080/api/v1/products \
  -H "Authorization: Bearer TU_TOKEN_JWT_AQUI" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Producto Nuevo",
    "description": "Descripción del producto",
    "sku": "PROD-NEW-001",
    "price": 49.99,
    "stock": 100,
    "status": 1
  }'
```

### 5. Actualizar un producto

```bash
curl -X PUT http://localhost:21080/api/v1/products/1 \
  -H "Authorization: Bearer TU_TOKEN_JWT_AQUI" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Producto Actualizado",
    "description": "Nueva descripción",
    "sku": "PROD-UPD-001",
    "price": 59.99,
    "stock": 90,
    "status": 1
  }'
```

### 6. Eliminar un producto

```bash
curl -X DELETE http://localhost:21080/api/v1/products/1 \
  -H "Authorization: Bearer TU_TOKEN_JWT_AQUI"
```

---

## 🔒 Seguridad

- Todos los tokens JWT expiran después de **1 hora** por defecto
- Las contraseñas se almacenan encriptadas con **bcrypt**
- Todos los endpoints implementan **control de acceso RBAC**
- Protección **CSRF** en formularios web
- Validación de entrada en todos los endpoints

---

## 📝 Notas

- La API está disponible en: `http://localhost:21080/api/v1/`
- El token JWT debe incluirse en el header: `Authorization: Bearer {token}`
- Los permisos se verifican contra el sistema RBAC de Yii2
- La paginación por defecto es de 20 elementos por página

---

**Desarrollado con Yii2 Framework + JWT**
