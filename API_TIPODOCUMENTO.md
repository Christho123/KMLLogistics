# API TipoDocumento

## Descripcion

Este archivo documenta los endpoints JSON del modulo `TipoDocumento`.

Base relativa:

- `Api/TipoDocumento/`

---

## Endpoints disponibles

### `List.php`

Metodo:

- `GET`

Parametros:

- `page`
- `page_size`
- `search`

Respuesta:

- `success`
- `document_types`
- `pagination`
- `search`

Uso:

- listado paginado de tipos de documento activos

Ejemplo de request:

```json
{
  "page": 1,
  "page_size": 10,
  "search": "DNI"
}
```

Ejemplo de response exitosa:

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
      "updated_at": "2026-04-24 08:00:00",
      "deleted_at": null
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

Ejemplo de response con error:

```json
{
  "success": false,
  "message": "Ocurrio un problema al cargar el listado de tipos de documento."
}
```

### `ListInactive.php`

Metodo:

- `GET`

Parametros:

- `search`

Respuesta:

- `success`
- `document_types`
- `total`
- `search`

Uso:

- listado de tipos de documento inactivos

Ejemplo de request:

```json
{
  "search": "2"
}
```

Ejemplo de response exitosa:

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
  "search": "2"
}
```

Ejemplo de response con error:

```json
{
  "success": false,
  "message": "Ocurrio un problema al cargar el listado de tipos de documento inactivos."
}
```

### `Get.php`

Metodo:

- `GET`

Parametros:

- `id_tipo_documento`

Respuesta:

- `success`
- `document_type`
- `message`

Uso:

- detalle de un tipo de documento activo

Ejemplo de request:

```json
{
  "id_tipo_documento": 1
}
```

Ejemplo de response exitosa:

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

Ejemplo de response con error:

```json
{
  "success": false,
  "message": "El tipo de documento solicitado no existe o ya no esta disponible."
}
```

### `Create.php`

Metodo:

- `POST`

Parametros:

- `nombre_tipo_documento`
- `descripcion`
- `estado`

Respuesta:

- `success`
- `message`
- `id_tipo_documento`

Uso:

- crear un tipo de documento nuevo

Ejemplo de request:

```json
{
  "nombre_tipo_documento": "Licencia",
  "descripcion": "Documento de autorizacion interna.",
  "estado": 1
}
```

Ejemplo de response exitosa:

```json
{
  "success": true,
  "message": "El tipo de documento fue creado correctamente.",
  "id_tipo_documento": 5
}
```

Ejemplo de response con error por duplicado:

```json
{
  "success": false,
  "message": "Ya existe un tipo de documento activo con este nombre."
}
```

Ejemplo de response con error de validacion:

```json
{
  "success": false,
  "message": "Debes completar correctamente el nombre y el estado del tipo de documento."
}
```

### `Update.php`

Metodo:

- `POST`

Parametros:

- `id_tipo_documento`
- `nombre_tipo_documento`
- `descripcion`
- `estado`

Respuesta:

- `success`
- `message`

Uso:

- actualizar un tipo de documento existente

Ejemplo de request:

```json
{
  "id_tipo_documento": 1,
  "nombre_tipo_documento": "DNI Actualizado",
  "descripcion": "Documento nacional actualizado.",
  "estado": 1
}
```

Ejemplo de response exitosa:

```json
{
  "success": true,
  "message": "El tipo de documento fue actualizado correctamente."
}
```

Ejemplo de response con error:

```json
{
  "success": false,
  "message": "Ya existe otro tipo de documento activo con este nombre."
}
```

### `Delete.php`

Metodo:

- `POST`

Parametros:

- `id_tipo_documento`

Respuesta:

- `success`
- `message`

Uso:

- eliminacion logica de tipos de documento

Ejemplo de request:

```json
{
  "id_tipo_documento": 1
}
```

Ejemplo de response exitosa:

```json
{
  "success": true,
  "message": "El tipo de documento fue eliminado del listado activo correctamente."
}
```

Ejemplo de response con error:

```json
{
  "success": false,
  "message": "El tipo de documento ya no se encuentra disponible para eliminar."
}
```

### `Restore.php`

Metodo:

- `POST`

Parametros:

- `id_tipo_documento`

Respuesta:

- `success`
- `message`

Uso:

- restaurar un tipo de documento inactivo

Ejemplo de request:

```json
{
  "id_tipo_documento": 2
}
```

Ejemplo de response exitosa:

```json
{
  "success": true,
  "message": "El tipo de documento fue restaurado correctamente."
}
```

Ejemplo de response con error:

```json
{
  "success": false,
  "message": "No se puede restaurar este tipo de documento porque ya existe uno activo con este nombre."
}
```

### `HardDelete.php`

Metodo:

- `POST`

Parametros:

- `id_tipo_documento`

Respuesta:

- `success`
- `message`

Uso:

- eliminacion definitiva de tipos de documento inactivos
- si tiene proveedores o usuarios asociados, la API devuelve error controlado

Ejemplo de request:

```json
{
  "id_tipo_documento": 2
}
```

Ejemplo de response exitosa:

```json
{
  "success": true,
  "message": "El tipo de documento fue eliminado definitivamente de la base de datos."
}
```

Ejemplo de response con error por dependencias:

```json
{
  "success": false,
  "message": "No se puede eliminar definitivamente este tipo de documento porque tiene 1 proveedor(es) y 2 usuario(s) asociados."
}
```

Ejemplo de response con error general:

```json
{
  "success": false,
  "message": "No se pudo completar la eliminacion definitiva del tipo de documento."
}
```

---

## Reglas importantes

- Los nombres activos no deben duplicarse.
- La eliminacion definitiva solo procede si el tipo de documento no tiene dependencias en:
  - `proveedores`
  - `usuarios`
- El listado principal acepta paginacion con:
  - `10`
  - `20`
  - `50`

---

## Flujo frontend asociado

Archivo JS principal:

- `Pages/Assets/JS/Pages/TipoDocumento/TipoDocumento.js`

Vista principal:

- `Pages/Views/TipoDocumento/TipoDocumento.php`

Controlador:

- `Pages/Controller/TipoDocumento/TipoDocumentoController.php`

Modelo:

- `Pages/Models/TipoDocumento/TipoDocumentoCRUD.php`
