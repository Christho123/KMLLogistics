# Modulo Providers

## Objetivo

El modulo `Providers` administra proveedores comerciales. Cada proveedor se relaciona con un tipo de documento y puede estar activo, inactivo o eliminado logicamente.

## Estructura del modulo

```text
KMLLogistics/
|-- Api/
|   `-- Providers/
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
    |   `-- Providers/
    |       `-- ProviderController.php
    |-- Models/
    |   `-- Providers/
    |       |-- Provider.php
    |       `-- ProviderCRUD.php
    |-- Views/
    |   `-- Providers/
    |       |-- Provider.php
    |       |-- ConfirmExitProviderModal.php
    |       |-- CreateProviderModal.php
    |       |-- DeleteProviderModal.php
    |       |-- DetailProviderModal.php
    |       |-- EditProviderModal.php
    |       |-- HardDeleteInactiveProviderModal.php
    |       |-- InactiveProviderModal.php
    |       |-- InfoProviderModal.php
    |       `-- RestoreInactiveProviderModal.php
    `-- Assets/
        |-- Css/
        |   `-- Pages/
        |       `-- Providers/
        |           `-- Providers.css
        `-- JS/
            `-- Pages/
                `-- Providers/
                    `-- Providers.js
```

## Arquitectura MVC

```text
Vista Provider.php
    -> JavaScript Providers.js
        -> Api/Providers/*.php
            -> ProviderController.php
                -> Provider.php / ProviderCRUD.php
                    -> Stored Procedures MySQL
```

## Tecnologias utilizadas

- **PHP:** endpoints, controlador y modelos.
- **POO y clases:** `Provider`, `ProviderCRUD`, `ProviderController`.
- **MVC:** divide vista, API, controlador y acceso a datos.
- **PDO:** ejecuta SP con parametros.
- **MySQL:** tabla `proveedores`, relacion con `tipo_documentos` y SP.
- **Bootstrap:** modals, formularios, tabla y layout responsive.
- **Font Awesome:** iconos de acciones.
- **jQuery:** DOM, eventos, `addClass()` y `removeClass()`.
- **AJAX:** CRUD asincrono.

## Stored Procedures usados

Los SP se llaman desde `C:\xampp\htdocs\KMLLogistics\Pages\Models\Providers\ProviderCRUD.php`.

```text
sp_proveedor_listar_activas
sp_proveedor_contar_activas
sp_proveedor_listar_inactivas
sp_proveedor_obtener_activa_por_id
sp_proveedor_obtener_por_id
sp_proveedor_crear
sp_proveedor_actualizar
sp_proveedor_eliminar_logico
sp_proveedor_restaurar
sp_proveedor_eliminar_definitivo
sp_proveedor_existe_documento
```

## AJAX y jQuery

Archivo principal: `C:\xampp\htdocs\KMLLogistics\Pages\Assets\JS\Pages\Providers\Providers.js`.

Endpoints consumidos:

```text
Api/Providers/List.php
Api/Providers/ListInactive.php
Api/Providers/Get.php
Api/Providers/Create.php
Api/Providers/Update.php
Api/Providers/Delete.php
Api/Providers/Restore.php
Api/Providers/HardDelete.php
```

El JS usa `$.ajax()` para listar y modificar proveedores sin recargar. Tambien usa `removeClass()` y `addClass()` para estados de validacion, mensajes, tamanos de modal y feedback visual.

## Interfaz

- Tabla de proveedores activos.
- Busqueda por datos comerciales.
- Formularios con tipo de documento.
- Modals de crear, editar, detalle, eliminar, inactivos, restaurar y hard delete.
- Validacion de documento duplicado.

## Auditoria

El modulo audita operaciones de creacion, actualizacion, eliminacion, restauracion y eliminacion definitiva.
