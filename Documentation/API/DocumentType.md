# API DocumentType

## Base

```text
Api/TipoDocumento/
```

Todas las respuestas son JSON. En errores se devuelve normalmente:

```json
{
  "success": false,
  "message": "Descripcion del problema"
}
```

## 1. Listar tipos de documento activos

Ruta:

```text
GET Api/TipoDocumento/List.php
```

Parametros query:

```json
{
  "page": 1,
  "page_size": 10,
  "search": "DNI"
}
```

Respuesta exitosa:

```json
{
  "success": true,
  "document_types": [
    {
      "id_tipo_documento": 1,
      "nombre_tipo_documento": "DNI",
      "descripcion": "Documento Nacional de Identidad para personas naturales.",
      "estado": 1,
      "created_at": "2026-04-24 08:00:00",
      "updated_at": "2026-04-24 08:00:00"
    }
  ],
  "pagination": {
    "page": 1,
    "page_size": 10,
    "total": 1,
    "total_pages": 1
  },
  "search": "DNI"
}
```

Uso:

- Carga la tabla principal.
- Permite paginacion.
- Permite busqueda.

## 2. Listar tipos de documento inactivos

Ruta:

```text
GET Api/TipoDocumento/ListInactive.php
```

Parametros query:

```json
{
  "search": "RUC"
}
```

Respuesta exitosa:

```json
{
  "success": true,
  "document_types": [
    {
      "id_tipo_documento": 2,
      "nombre_tipo_documento": "RUC",
      "descripcion": "Registro Unico de Contribuyentes para empresas y negocios.",
      "estado": 0,
      "created_at": "2026-04-24 08:10:00",
      "updated_at": "2026-04-24 09:00:00",
      "deleted_at": "2026-04-24 09:00:00"
    }
  ],
  "total": 1,
  "search": "RUC"
}
```

Uso:

- Carga el modal de inactivos.
- Permite restaurar o eliminar definitivamente.

## 3. Obtener tipo de documento activo

Ruta:

```text
GET Api/TipoDocumento/Get.php
```

Parametros query:

```json
{
  "id_tipo_documento": 1
}
```

Respuesta exitosa:

```json
{
  "success": true,
  "document_type": {
    "id_tipo_documento": 1,
    "nombre_tipo_documento": "DNI",
    "descripcion": "Documento Nacional de Identidad para personas naturales.",
    "estado": 1,
    "created_at": "2026-04-24 08:00:00",
    "updated_at": "2026-04-24 08:00:00"
  }
}
```

Respuesta si no existe:

```json
{
  "success": false,
  "message": "El tipo de documento solicitado no existe o ya no esta disponible."
}
```

Uso:

- Ver detalle.
- Cargar formulario de edicion.
- Preparar eliminacion logica.

## 4. Crear tipo de documento

Ruta:

```text
POST Api/TipoDocumento/Create.php
```

Body:

```json
{
  "nombre_tipo_documento": "Licencia",
  "descripcion": "Documento de autorizacion interna.",
  "estado": 1
}
```

Respuesta exitosa:

```json
{
  "success": true,
  "message": "El tipo de documento fue creado correctamente.",
  "id_tipo_documento": 5
}
```

Respuesta por duplicado:

```json
{
  "success": false,
  "message": "Ya existe un tipo de documento activo con este nombre."
}
```

Respuesta de validacion:

```json
{
  "success": false,
  "message": "Debes completar correctamente el nombre y el estado del tipo de documento."
}
```

Uso:

- Registra un nuevo tipo de documento.
- Registra auditoria y notificacion admin.

## 5. Actualizar tipo de documento

Ruta:

```text
POST Api/TipoDocumento/Update.php
```

Body:

```json
{
  "id_tipo_documento": 1,
  "nombre_tipo_documento": "DNI Actualizado",
  "descripcion": "Documento nacional actualizado.",
  "estado": 1
}
```

Respuesta exitosa:

```json
{
  "success": true,
  "message": "El tipo de documento fue actualizado correctamente."
}
```

Respuesta por duplicado:

```json
{
  "success": false,
  "message": "Ya existe otro tipo de documento activo con este nombre."
}
```

Uso:

- Actualiza nombre, descripcion y estado.
- Registra auditoria y notificacion admin.

## 6. Eliminar logicamente tipo de documento

Ruta:

```text
POST Api/TipoDocumento/Delete.php
```

Body:

```json
{
  "id_tipo_documento": 1
}
```

Respuesta exitosa:

```json
{
  "success": true,
  "message": "El tipo de documento fue eliminado del listado activo correctamente."
}
```

Respuesta de error:

```json
{
  "success": false,
  "message": "El tipo de documento ya no se encuentra disponible para eliminar."
}
```

Uso:

- Realiza eliminacion logica.
- Registra auditoria y notificacion admin.

## 7. Restaurar tipo de documento

Ruta:

```text
POST Api/TipoDocumento/Restore.php
```

Body:

```json
{
  "id_tipo_documento": 2
}
```

Respuesta exitosa:

```json
{
  "success": true,
  "message": "El tipo de documento fue restaurado correctamente."
}
```

Respuesta por duplicado:

```json
{
  "success": false,
  "message": "No se puede restaurar este tipo de documento porque ya existe uno activo con este nombre."
}
```

Uso:

- Devuelve el tipo de documento al listado activo.
- Registra auditoria y notificacion admin.

## 8. Eliminar definitivamente tipo de documento

Ruta:

```text
POST Api/TipoDocumento/HardDelete.php
```

Body:

```json
{
  "id_tipo_documento": 2
}
```

Respuesta exitosa:

```json
{
  "success": true,
  "message": "El tipo de documento fue eliminado definitivamente de la base de datos."
}
```

Respuesta por dependencias:

```json
{
  "success": false,
  "message": "No se puede eliminar definitivamente este tipo de documento porque tiene 1 proveedor(es) y 2 usuario(s) asociados."
}
```

Respuesta general de error:

```json
{
  "success": false,
  "message": "No se pudo completar la eliminacion definitiva del tipo de documento."
}
```

Uso:

- Borra fisicamente solo si no tiene dependencias.
- Dependencias controladas: `proveedores` y `usuarios`.
- Registra auditoria y notificacion admin.

## Flujo tecnico

```text
TipoDocumento.js -> Api/TipoDocumento/*.php -> TipoDocumentoController -> TipoDocumentoCRUD -> Stored Procedures
```

## Rutas completas relacionadas

```text
C:\xampp\htdocs\KMLLogistics\Api\TipoDocumento\
C:\xampp\htdocs\KMLLogistics\Pages\Assets\JS\Pages\TipoDocumento\TipoDocumento.js
C:\xampp\htdocs\KMLLogistics\Pages\Controller\TipoDocumento\TipoDocumentoController.php
C:\xampp\htdocs\KMLLogistics\Pages\Models\TipoDocumento\TipoDocumentoCRUD.php
C:\xampp\htdocs\KMLLogistics\BD\Empresa\KMLLogistics.sql
```

## Tecnologias usadas por esta API

- **PHP:** endpoints para listar, obtener, crear, actualizar, eliminar, restaurar y hard delete.
- **MVC:** la API conecta `TipoDocumento.js` con `TipoDocumentoController`.
- **POO:** se usan clases `TipoDocumento`, `TipoDocumentoCRUD` y `TipoDocumentoController`.
- **PDO:** `TipoDocumentoCRUD` ejecuta los SP mediante `CALL`.
- **MySQL:** persistencia y reglas de dependencias en stored procedures.
- **jQuery AJAX:** el frontend consume estos endpoints sin recargar.
- **Bootstrap y Font Awesome:** la informacion JSON se muestra en tablas, modals, botones e iconos.

## jQuery, addClass y removeClass

La manipulacion visual esta en:

```text
C:\xampp\htdocs\KMLLogistics\Pages\Assets\JS\Pages\TipoDocumento\TipoDocumento.js
```

Ese archivo usa `$.ajax()` para consumir esta API y usa `addClass()` / `removeClass()` para estados visuales, mensajes, validaciones y control de modals.
