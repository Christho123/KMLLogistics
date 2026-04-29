# Modulo Product

## Objetivo

El modulo `Product` administra el inventario principal: producto, costo, ganancia, precio, stock, foto, categoria, marca y estado.

## Estructura del modulo

```text
KMLLogistics/
|-- Api/
|   `-- Product/
|       |-- Create.php
|       |-- Delete.php
|       |-- Get.php
|       |-- HardDelete.php
|       |-- List.php
|       |-- ListInactive.php
|       |-- ProductImageHelper.php
|       |-- Restore.php
|       `-- Update.php
|-- BD/
|   `-- Empresa/
|       `-- KMLLogistics.sql
`-- Pages/
    |-- Controller/
    |   `-- Product/
    |       `-- ProductController.php
    |-- Models/
    |   `-- Product/
    |       |-- Product.php
    |       `-- ProductCRUD.php
    |-- Views/
    |   `-- Product/
    |       |-- Product.php
    |       |-- ConfirmExitProductModal.php
    |       |-- CreateProductModal.php
    |       |-- DeleteProductModal.php
    |       |-- DetailProductModal.php
    |       |-- EditProductModal.php
    |       |-- HardDeleteInactiveProductModal.php
    |       |-- InactiveProductsModal.php
    |       |-- InfoProductModal.php
    |       `-- RestoreInactiveProductModal.php
    `-- Assets/
        |-- Css/
        |   `-- Pages/
        |       `-- Product/
        |           `-- Product.css
        `-- JS/
            `-- Pages/
                `-- Product/
                    `-- Product.js
```

## Arquitectura MVC

```text
Vista Product.php
    -> JavaScript Product.js
        -> Api/Product/*.php
            -> ProductController.php
                -> Product.php / ProductCRUD.php
                    -> Stored Procedures MySQL
```

## Tecnologias utilizadas

- **PHP:** endpoints, controlador, helpers y render de vistas.
- **POO y clases:** `Product`, `ProductCRUD`, `ProductController`.
- **MVC:** separacion entre vista, controlador, modelo y API.
- **PDO:** llamadas a procedimientos almacenados.
- **MySQL:** tablas `productos`, `categorias`, `marcas` y SP.
- **Bootstrap:** grillas, modals, formularios y botones.
- **Font Awesome:** iconos para acciones y estados.
- **jQuery:** eventos, manipulacion del DOM, `addClass()` y `removeClass()`.
- **AJAX:** operaciones asincronas.
- **Manejo de archivos:** `ProductImageHelper.php` valida y guarda imagenes.

## Stored Procedures usados

Los SP se llaman desde `C:\xampp\htdocs\KMLLogistics\Pages\Models\Product\ProductCRUD.php`.

```text
sp_producto_listar_activas
sp_producto_contar_activas
sp_producto_listar_inactivas
sp_producto_obtener_activa_por_id
sp_producto_obtener_por_id
sp_producto_crear
sp_producto_actualizar
sp_producto_eliminar_logico
sp_producto_restaurar
sp_producto_eliminar_definitivo
sp_producto_existe_nombre
sp_producto_listar_categorias_activas
sp_producto_listar_marcas_activas
```

## AJAX y jQuery

Archivo principal: `C:\xampp\htdocs\KMLLogistics\Pages\Assets\JS\Pages\Product\Product.js`.

Endpoints consumidos:

```text
Api/Product/List.php
Api/Product/ListInactive.php
Api/Product/Get.php
Api/Product/Create.php
Api/Product/Update.php
Api/Product/Delete.php
Api/Product/Restore.php
Api/Product/HardDelete.php
```

`Product.js` usa `$.ajax()` para enviar formularios, listar datos y recuperar detalle. Tambien usa `addClass()` y `removeClass()` para feedback visual, errores, estado de carga, resaltado de modals y comportamiento de tablas.

## Interfaz

- Listado de productos activos.
- Filtro de busqueda.
- Formulario con categoria y marca.
- Calculo de precio desde costo y ganancia.
- Subida/actualizacion de foto.
- Modals de crear, editar, detalle, eliminar, inactivos, restaurar y hard delete.

## Auditoria

El modulo registra acciones importantes mediante `AuditLogger`, especialmente altas, modificaciones, eliminaciones y restauraciones.
