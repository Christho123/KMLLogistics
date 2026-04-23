# API Category

Este archivo resume como usar las APIs del modulo `Category` en el proyecto `KMLLogistics`.

## Base

Todas las rutas estan bajo:

```text
Api/Category/
```

Las respuestas son JSON con esta estructura general:

```json
{
  "success": true
}
```

Si ocurre un error, normalmente se responde:

```json
{
  "success": false,
  "message": "Descripcion del problema"
}
```

## Endpoints

### 1. Listar categorias activas

Archivo:

```text
Api/Category/List.php
```

Metodo:

```text
GET
```

Parametros:

- `page`: numero de pagina
- `page_size`: cantidad por pagina
- `search`: filtro por nombre de categoria

Ejemplo:

```text
Api/Category/List.php?page=1&page_size=10&search=Lap
```

Respuesta esperada:

```json
{
  "success": true,
  "categories": [],
  "pagination": {
    "page": 1,
    "page_size": 10,
    "total": 5,
    "total_pages": 1
  },
  "search": "Lap"
}
```

Notas:

- Solo lista categorias activas.
- Usa procedimientos almacenados en BD.

### 2. Listar categorias inactivas

Archivo:

```text
Api/Category/ListInactive.php
```

Metodo:

```text
GET
```

Parametros:

- `search`: filtro por nombre

Ejemplo:

```text
Api/Category/ListInactive.php?search=Seg
```

Respuesta esperada:

```json
{
  "success": true,
  "categories": [],
  "total": 2,
  "search": "Seg"
}
```

Notas:

- Lista categorias inactivas o eliminadas logicamente.

### 3. Obtener detalle de una categoria activa

Archivo:

```text
Api/Category/Get.php
```

Metodo:

```text
GET
```

Parametros:

- `id_categoria`

Ejemplo:

```text
Api/Category/Get.php?id_categoria=5
```

Respuesta esperada:

```json
{
  "success": true,
  "category": {
    "id_categoria": 5,
    "nombre_categoria": "Almacenamiento",
    "descripcion": "Soluciones para respaldo y gestion de datos.",
    "estado": 1,
    "created_at": "2026-04-23 13:49:52",
    "updated_at": "2026-04-23 13:49:52"
  }
}
```

### 4. Crear categoria

Archivo:

```text
Api/Category/Create.php
```

Metodo:

```text
POST
```

Parametros:

- `nombre_categoria`
- `descripcion`
- `estado`

Ejemplo:

```text
nombre_categoria=Redes
descripcion=Equipos de conectividad
estado=1
```

Respuesta esperada:

```json
{
  "success": true,
  "message": "La categoria fue creada correctamente.",
  "id_categoria": 12
}
```

### 5. Actualizar categoria

Archivo:

```text
Api/Category/Update.php
```

Metodo:

```text
POST
```

Parametros:

- `id_categoria`
- `nombre_categoria`
- `descripcion`
- `estado`

Ejemplo:

```text
id_categoria=12
nombre_categoria=Redes Core
descripcion=Categoria actualizada
estado=1
```

Notas:

- Si `estado=0`, la categoria pasa al listado de inactivos.
- Si `estado=1`, permanece en el listado principal.

### 6. Eliminar logicamente una categoria

Archivo:

```text
Api/Category/Delete.php
```

Metodo:

```text
POST
```

Parametros:

- `id_categoria`

Ejemplo:

```text
id_categoria=12
```

Respuesta esperada:

```json
{
  "success": true,
  "message": "La categoria fue eliminada del listado activo correctamente."
}
```

Notas:

- No elimina fisicamente el registro.
- La categoria pasa al listado de inactivos.

### 7. Restaurar categoria inactiva

Archivo:

```text
Api/Category/Restore.php
```

Metodo:

```text
POST
```

Parametros:

- `id_categoria`

Ejemplo:

```text
id_categoria=12
```

Respuesta esperada:

```json
{
  "success": true,
  "message": "La categoria fue restaurada correctamente."
}
```

### 8. Eliminar definitivamente una categoria

Archivo:

```text
Api/Category/HardDelete.php
```

Metodo:

```text
POST
```

Parametros:

- `id_categoria`

Ejemplo:

```text
id_categoria=12
```

Respuesta esperada:

```json
{
  "success": true,
  "message": "La categoria fue eliminada definitivamente de la base de datos.",
  "deleted_products": 3
}
```

Notas:

- Esta accion elimina fisicamente la categoria.
- Tambien elimina los productos asociados a esa categoria.

## Flujo recomendado en frontend

### Tabla principal

- Usar `List.php` para cargar categorias activas.
- Usar `Get.php` para ver detalle y cargar datos en editar.
- Usar `Create.php` para registrar.
- Usar `Update.php` para editar.
- Usar `Delete.php` para enviar a inactivos.

### Modal de inactivos

- Usar `ListInactive.php` para cargar la tabla.
- Usar `Restore.php` para devolver la categoria al listado activo.
- Usar `HardDelete.php` para eliminar definitivamente.

## Relacion con MVC

El flujo actual del modulo es:

```text
View/JS -> Api/Category/*.php -> CategoryController -> CategoryCRUD -> Stored Procedures
```

Esto significa:

- La vista no consulta la BD directamente.
- Los archivos en `Api/Category` reciben la peticion HTTP.
- `CategoryController` coordina la logica.
- `CategoryCRUD` se encarga de llamar a los procedimientos almacenados.

## Archivo clave de base de datos

Los procedimientos almacenados usados por este modulo estan definidos en:

[KMLLogistics.sql](C:\xampp\htdocs\KMLLogistics\BD\Empresa\KMLLogistics.sql:1)
