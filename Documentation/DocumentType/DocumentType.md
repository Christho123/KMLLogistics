# Modulo DocumentType

## Objetivo

El modulo `DocumentType` administra los tipos de documento usados por usuarios y proveedores, por ejemplo DNI, RUC o pasaporte.

## Estructura del modulo

```text
KMLLogistics/
|-- Api/
|   `-- TipoDocumento/
|       |-- Create.php
|       |-- Delete.php
|       |-- Get.php
|       |-- HardDelete.php
|       |-- List.php
|       |-- ListInactive.php
|       |-- Restore.php
|       `-- Update.php
|-- BD/
|   `-- Empresa/
|       `-- KMLLogistics.sql
`-- Pages/
    |-- Controller/
    |   `-- TipoDocumento/
    |       `-- TipoDocumentoController.php
    |-- Models/
    |   `-- TipoDocumento/
    |       |-- TipoDocumento.php
    |       `-- TipoDocumentoCRUD.php
    |-- Views/
    |   `-- TipoDocumento/
    |       |-- TipoDocumento.php
    |       |-- ConfirmExitTipoDocumentoModal.php
    |       |-- CreateTipoDocumentoModal.php
    |       |-- DeleteTipoDocumentoModal.php
    |       |-- DetailTipoDocumentoModal.php
    |       |-- EditTipoDocumentoModal.php
    |       |-- HardDeleteInactiveTipoDocumentoModal.php
    |       |-- InactiveTipoDocumentoModal.php
    |       |-- InfoTipoDocumentoModal.php
    |       `-- RestoreInactiveTipoDocumentoModal.php
    `-- Assets/
        |-- Css/
        |   `-- Pages/
        |       `-- TipoDocumento/
        |           `-- TipoDocumento.css
        `-- JS/
            `-- Pages/
                `-- TipoDocumento/
                    `-- TipoDocumento.js
```

## Arquitectura MVC

```text
Vista TipoDocumento.php
    -> JavaScript TipoDocumento.js
        -> Api/TipoDocumento/*.php
            -> TipoDocumentoController.php
                -> TipoDocumento.php / TipoDocumentoCRUD.php
                    -> Stored Procedures MySQL
```

## Tecnologias utilizadas

- **PHP:** vistas, API, controlador y modelos.
- **POO y clases:** `TipoDocumento`, `TipoDocumentoCRUD`, `TipoDocumentoController`.
- **MVC:** organizacion por capas.
- **PDO:** llamadas seguras a SP.
- **MySQL:** tabla `tipo_documentos` y procedimientos almacenados.
- **Bootstrap:** modals, formularios y layout.
- **Font Awesome:** iconos.
- **jQuery:** eventos, DOM, `addClass()` y `removeClass()`.
- **AJAX:** comunicacion asincrona con endpoints.

## Stored Procedures usados

Los SP se llaman desde `C:\xampp\htdocs\KMLLogistics\Pages\Models\TipoDocumento\TipoDocumentoCRUD.php`.

```text
sp_tipo_documento_listar_activos
sp_tipo_documento_contar_activos
sp_tipo_documento_listar_inactivos
sp_tipo_documento_obtener_activo_por_id
sp_tipo_documento_obtener_por_id
sp_tipo_documento_crear
sp_tipo_documento_actualizar
sp_tipo_documento_eliminar_logico
sp_tipo_documento_restaurar
sp_tipo_documento_eliminar_definitivo
sp_tipo_documento_existe_nombre
```

Tambien existen SP usados por registro/perfil para cargar selects:

```text
sp_tipo_documento_listar_activos_para_select
sp_tipo_documento_obtener_activo_para_select_por_id
```

Estos se llaman desde `C:\xampp\htdocs\KMLLogistics\Pages\Models\Users\UserCRUD.php`.

## AJAX y jQuery

Archivo principal: `C:\xampp\htdocs\KMLLogistics\Pages\Assets\JS\Pages\TipoDocumento\TipoDocumento.js`.

Endpoints consumidos:

```text
Api/TipoDocumento/List.php
Api/TipoDocumento/ListInactive.php
Api/TipoDocumento/Get.php
Api/TipoDocumento/Create.php
Api/TipoDocumento/Update.php
Api/TipoDocumento/Delete.php
Api/TipoDocumento/Restore.php
Api/TipoDocumento/HardDelete.php
```

El JS usa `$.ajax()`, `addClass()` y `removeClass()` para peticiones CRUD, mensajes de respuesta, errores de formulario, modals y estados visuales.

## Interfaz

- Listado paginado.
- Busqueda por ID o nombre.
- Modals de crear, editar, detalle, eliminar, inactivos, restaurar y hard delete.
- Bloqueo de eliminacion definitiva cuando existen usuarios o proveedores relacionados.

## Auditoria

Las acciones principales se registran con `AuditLogger`.
