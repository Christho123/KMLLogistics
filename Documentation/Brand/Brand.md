# Modulo Brand

## Objetivo

El modulo `Brand` administra marcas asociadas a proveedores. Permite gestionar marcas activas e inactivas y mantener la relacion con el proveedor correspondiente.

## Estructura del modulo

```text
KMLLogistics/
|-- Api/
|   `-- Brand/
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
    |   `-- Brand/
    |       `-- BrandController.php
    |-- Models/
    |   `-- Brand/
    |       |-- Brand.php
    |       `-- BrandCRUD.php
    |-- Views/
    |   `-- Brand/
    |       |-- Brand.php
    |       |-- ConfirmExitBrandModal.php
    |       |-- CreateBrandModal.php
    |       |-- DeleteBrandModal.php
    |       |-- DetailBrandModal.php
    |       |-- EditBrandModal.php
    |       |-- HardDeleteModal.php
    |       |-- InactiveBrandsModal.php
    |       |-- InfoBrandModal.php
    |       `-- RestoreInactiveBrandModal.php
    `-- Assets/
        |-- Css/
        |   `-- Pages/
        |       `-- Brand/
        |           `-- Brand.css
        `-- JS/
            `-- Pages/
                `-- Brand/
                    `-- Brand.js
```

## Arquitectura MVC

```text
Vista Brand.php
    -> JavaScript Brand.js
        -> Api/Brand/*.php
            -> BrandController.php
                -> Brand.php / BrandCRUD.php
                    -> Stored Procedures MySQL
```

## Tecnologias utilizadas

- **PHP:** endpoints, controlador, vista y modelos.
- **POO y clases:** `Brand`, `BrandCRUD`, `BrandController`.
- **MVC:** separa presentacion, logica y acceso a datos.
- **PDO:** ejecuta procedimientos almacenados con `CALL`.
- **MySQL:** tablas `marcas`, `proveedores` y SP.
- **Bootstrap:** layout, botones, formularios y modals.
- **Font Awesome:** iconografia de acciones.
- **jQuery:** eventos, DOM, validaciones visuales, `addClass()` y `removeClass()`.
- **AJAX:** CRUD sin recargar la pagina.

## Stored Procedures usados

Los SP se llaman desde `C:\xampp\htdocs\KMLLogistics\Pages\Models\Brand\BrandCRUD.php`.

```text
sp_marca_listar_activas
sp_marca_contar_activas
sp_marca_listar_inactivas
sp_marca_obtener_activa_por_id
sp_marca_obtener_por_id
sp_marca_crear
sp_marca_actualizar
sp_marca_eliminar_logico
sp_marca_restaurar
sp_marca_eliminar_definitivo
sp_marca_existe_nombre
sp_marca_listar_proveedores_activos
```

## AJAX y jQuery

Archivo principal: `C:\xampp\htdocs\KMLLogistics\Pages\Assets\JS\Pages\Brand\Brand.js`.

Endpoints consumidos:

```text
Api/Brand/List.php
Api/Brand/ListInactive.php
Api/Brand/Get.php
Api/Brand/Create.php
Api/Brand/Update.php
Api/Brand/Delete.php
Api/Brand/Restore.php
Api/Brand/HardDelete.php
```

`Brand.js` usa `addClass()` y `removeClass()` para estados de carga, feedback, control de modals y estilos de interfaz. Tambien usa `bootstrap.Modal` para abrir y cerrar modals de forma programatica.

## Interfaz

- Tabla de marcas activas.
- Filtro de busqueda.
- Selector de proveedor.
- Modals de crear, editar, detalle y eliminar.
- Modal de marcas inactivas.
- Restauracion y hard delete.

## Auditoria

Las acciones del usuario se registran mediante el servicio de auditoria para mantener historial del modulo.
