# Modulo Category

## Objetivo

El modulo `Category` administra las categorias de productos. Permite listar, buscar, crear, editar, ver detalle, eliminar logicamente, restaurar y eliminar definitivamente registros.

## Estructura del modulo

```text
KMLLogistics/
|-- Api/
|   `-- Category/
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
    |   `-- Category/
    |       `-- CategoryController.php
    |-- Models/
    |   `-- Category/
    |       |-- Category.php
    |       `-- CategoryCRUD.php
    |-- Views/
    |   `-- Category/
    |       |-- Category.php
    |       |-- ConfirmExitCategoryModal.php
    |       |-- CreateCategoryModal.php
    |       |-- DeleteCategoryModal.php
    |       |-- DetailCategoryModal.php
    |       |-- EditCategoryModal.php
    |       |-- HardDeleteInactiveCategoryModal.php
    |       |-- InactiveCategoriesModal.php
    |       |-- InfoCategoryModal.php
    |       `-- RestoreInactiveCategoryModal.php
    `-- Assets/
        |-- Css/
        |   `-- Pages/
        |       `-- Category/
        |           `-- Category.css
        `-- JS/
            `-- Pages/
                `-- Category/
                    `-- Category.js
```

## Arquitectura MVC

```text
Vista Category.php
    -> JavaScript Category.js
        -> Api/Category/*.php
            -> CategoryController.php
                -> Category.php / CategoryCRUD.php
                    -> Stored Procedures MySQL
```

- **Vista:** `C:\xampp\htdocs\KMLLogistics\Pages\Views\Category\Category.php` renderiza tabla, filtros, botones y modals.
- **Controller:** `C:\xampp\htdocs\KMLLogistics\Pages\Controller\Category\CategoryController.php` valida datos, aplica reglas de negocio y llama al modelo.
- **Modelo entidad:** `C:\xampp\htdocs\KMLLogistics\Pages\Models\Category\Category.php` representa los datos de una categoria usando POO.
- **Modelo CRUD:** `C:\xampp\htdocs\KMLLogistics\Pages\Models\Category\CategoryCRUD.php` usa PDO para llamar procedimientos almacenados.
- **Base de datos:** los SP estan dentro de `C:\xampp\htdocs\KMLLogistics\BD\Empresa\KMLLogistics.sql`.

## Tecnologias utilizadas

- **PHP:** render de vistas, endpoints API, controladores y modelos.
- **POO y clases:** `Category`, `CategoryCRUD` y `CategoryController`.
- **MVC:** separa vista, controlador, modelo y capa API.
- **PDO:** prepara y ejecuta `CALL sp_*` contra MySQL.
- **MySQL:** persistencia de datos y procedimientos almacenados.
- **Bootstrap:** tablas, botones, grid responsive y modals.
- **Font Awesome:** iconos de acciones visuales.
- **jQuery:** eventos, lectura de formularios, manipulacion de DOM y clases.
- **AJAX:** operaciones CRUD sin recargar la pagina.

## Stored Procedures usados

Los SP se llaman desde `C:\xampp\htdocs\KMLLogistics\Pages\Models\Category\CategoryCRUD.php`.

```text
sp_categoria_listar_activas
sp_categoria_contar_activas
sp_categoria_listar_inactivas
sp_categoria_obtener_activa_por_id
sp_categoria_obtener_por_id
sp_categoria_crear
sp_categoria_actualizar
sp_categoria_eliminar_logico
sp_categoria_restaurar
sp_categoria_eliminar_definitivo
sp_categoria_existe_nombre
```

## AJAX y jQuery

El archivo principal es `C:\xampp\htdocs\KMLLogistics\Pages\Assets\JS\Pages\Category\Category.js`.

Usa AJAX para consumir:

```text
Api/Category/List.php
Api/Category/ListInactive.php
Api/Category/Get.php
Api/Category/Create.php
Api/Category/Update.php
Api/Category/Delete.php
Api/Category/Restore.php
Api/Category/HardDelete.php
```

Tambien usa jQuery para actualizar estados visuales con `addClass()` y `removeClass()`, por ejemplo:

```js
$dialog.removeClass('modal-sm modal-lg modal-xl');
$dialog.addClass(sizeClass);
```

Este patron se usa para cambiar tamanos de modal, mostrar feedback, marcar estados de carga, limpiar errores y controlar interacciones de tabla.

## Interfaz

- Listado paginado.
- Busqueda por ID o nombre.
- Selector de cantidad de filas.
- Modal de creacion.
- Modal de edicion.
- Modal de detalle.
- Modal de eliminacion logica.
- Modal de registros inactivos.
- Restauracion.
- Eliminacion definitiva.
- Mensajes informativos con Bootstrap.

## Auditoria

Las acciones importantes se registran mediante `AuditLogger`, dejando trazabilidad de creacion, actualizacion, eliminacion, restauracion y hard delete.
