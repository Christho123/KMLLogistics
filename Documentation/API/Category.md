# API Category

## Base

```text
Api/Category/
```

Todas las respuestas son JSON. En errores se devuelve normalmente:

```json
{
  "success": false,
  "message": "Descripcion del problema"
}
```

## 1. Listar categorias activas

Ruta:

```text
GET Api/Category/List.php
```

Parametros query:

```json
{
  "page": 1,
  "page_size": 10,
  "search": "Lap"
}
```

Respuesta exitosa:

```json
{
  "success": true,
  "categories": [
    {
      "id_categoria": 1,
      "nombre_categoria": "Laptops",
      "descripcion": "Equipos portatiles para uso empresarial y operativo.",
      "estado": 1,
      "created_at": "2026-04-17 09:00:00",
      "updated_at": "2026-04-17 09:00:00"
    }
  ],
  "pagination": {
    "page": 1,
    "page_size": 10,
    "total": 1,
    "total_pages": 1
  },
  "search": "Lap"
}
```

Uso:

- Carga la tabla principal.
- Permite paginacion.
- Permite busqueda por nombre o ID segun flujo del frontend.

## 2. Listar categorias inactivas

Ruta:

```text
GET Api/Category/ListInactive.php
```

Parametros query:

```json
{
  "search": "Seg"
}
```

Respuesta exitosa:

```json
{
  "success": true,
  "categories": [
    {
      "id_categoria": 4,
      "nombre_categoria": "Seguridad",
      "descripcion": "Dispositivos para proteccion y monitoreo.",
      "estado": 0,
      "created_at": "2026-04-20 12:00:00",
      "updated_at": "2026-04-21 10:00:00",
      "deleted_at": "2026-04-21 10:00:00"
    }
  ],
  "total": 1,
  "search": "Seg"
}
```

Uso:

- Carga el modal de categorias inactivas.
- Sirve para restaurar o eliminar definitivamente.

## 3. Obtener categoria activa

Ruta:

```text
GET Api/Category/Get.php
```

Parametros query:

```json
{
  "id_categoria": 1
}
```

Respuesta exitosa:

```json
{
  "success": true,
  "category": {
    "id_categoria": 1,
    "nombre_categoria": "Laptops",
    "descripcion": "Equipos portatiles para uso empresarial y operativo.",
    "estado": 1,
    "created_at": "2026-04-17 09:00:00",
    "updated_at": "2026-04-17 09:00:00"
  }
}
```

Respuesta si no existe:

```json
{
  "success": false,
  "message": "La categoria solicitada no existe o ya no esta disponible."
}
```

Uso:

- Abre detalle.
- Carga datos para editar.
- Carga datos para confirmar eliminacion.

## 4. Crear categoria

Ruta:

```text
POST Api/Category/Create.php
```

Body:

```json
{
  "nombre_categoria": "Redes",
  "descripcion": "Equipos de conectividad",
  "estado": 1
}
```

Respuesta exitosa:

```json
{
  "success": true,
  "message": "La categoria fue creada correctamente.",
  "id_categoria": 12
}
```

Respuesta de validacion:

```json
{
  "success": false,
  "message": "Debes completar correctamente el nombre, la descripcion y el estado de la categoria."
}
```

Uso:

- Registra una categoria nueva.
- Registra auditoria.
- Notifica al email admin mediante PHPMailer.

## 5. Actualizar categoria

Ruta:

```text
POST Api/Category/Update.php
```

Body:

```json
{
  "id_categoria": 12,
  "nombre_categoria": "Redes Core",
  "descripcion": "Categoria actualizada",
  "estado": 1
}
```

Respuesta exitosa:

```json
{
  "success": true,
  "message": "La categoria fue actualizada correctamente."
}
```

Respuesta de error:

```json
{
  "success": false,
  "message": "No se pudo actualizar la categoria."
}
```

Uso:

- Actualiza datos principales.
- Si se manda `estado = 0`, el registro pasa al flujo de inactivos.
- Registra auditoria y notificacion admin.

## 6. Eliminar logicamente categoria

Ruta:

```text
POST Api/Category/Delete.php
```

Body:

```json
{
  "id_categoria": 12
}
```

Respuesta exitosa:

```json
{
  "success": true,
  "message": "La categoria fue eliminada del listado activo correctamente."
}
```

Respuesta de error:

```json
{
  "success": false,
  "message": "La categoria ya no se encuentra disponible para eliminar."
}
```

Uso:

- No borra fisicamente.
- Cambia estado y fecha `deleted_at`.
- Registra auditoria y notificacion admin.

## 7. Restaurar categoria

Ruta:

```text
POST Api/Category/Restore.php
```

Body:

```json
{
  "id_categoria": 12
}
```

Respuesta exitosa:

```json
{
  "success": true,
  "message": "La categoria fue restaurada correctamente."
}
```

Respuesta de error:

```json
{
  "success": false,
  "message": "No se pudo restaurar la categoria."
}
```

Uso:

- Devuelve la categoria al listado activo.
- Registra auditoria y notificacion admin.

## 8. Eliminar definitivamente categoria

Ruta:

```text
POST Api/Category/HardDelete.php
```

Body:

```json
{
  "id_categoria": 12
}
```

Respuesta exitosa:

```json
{
  "success": true,
  "message": "La categoria fue eliminada definitivamente de la base de datos.",
  "deleted_products": 3
}
```

Respuesta de error:

```json
{
  "success": false,
  "message": "No se pudo completar la eliminacion definitiva de la categoria."
}
```

Uso:

- Borra fisicamente una categoria inactiva.
- Puede eliminar productos asociados segun el procedimiento almacenado.
- Registra auditoria y notificacion admin.

## Flujo tecnico

```text
Category.js -> Api/Category/*.php -> CategoryController -> CategoryCRUD -> Stored Procedures
```

## Rutas completas relacionadas

```text
C:\xampp\htdocs\KMLLogistics\Api\Category\
C:\xampp\htdocs\KMLLogistics\Pages\Assets\JS\Pages\Category\Category.js
C:\xampp\htdocs\KMLLogistics\Pages\Controller\Category\CategoryController.php
C:\xampp\htdocs\KMLLogistics\Pages\Models\Category\CategoryCRUD.php
C:\xampp\htdocs\KMLLogistics\BD\Empresa\KMLLogistics.sql
```

## Tecnologias usadas por esta API

- **PHP:** cada endpoint recibe `GET` o `POST`, llama al controlador y devuelve JSON.
- **MVC:** la API funciona como puente entre `Category.js` y `CategoryController`.
- **POO:** el controlador usa clases `Category` y `CategoryCRUD`.
- **PDO:** `CategoryCRUD` ejecuta los SP con llamadas `CALL`.
- **MySQL:** los datos se resuelven con procedimientos almacenados.
- **jQuery AJAX:** `Category.js` consume estos endpoints sin recargar la pagina.
- **Bootstrap y Font Awesome:** la respuesta JSON se refleja en tablas, botones, modals e iconos.

## jQuery, addClass y removeClass

La API no manipula clases directamente; eso ocurre en:

```text
C:\xampp\htdocs\KMLLogistics\Pages\Assets\JS\Pages\Category\Category.js
```

Ese archivo usa `$.ajax()` para llamar a esta API y usa `addClass()` / `removeClass()` para feedback visual, estados de carga, errores y modals.
