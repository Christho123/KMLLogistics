# Category

## Estructura del modulo

```text
Api/Category/
Pages/Controller/Category/CategoryController.php
Pages/Models/Category/Category.php
Pages/Models/Category/CategoryCRUD.php
Pages/Views/Category/
Pages/Assets/JS/Pages/Category/Category.js
Pages/Assets/Css/Pages/Category/Category.css
BD/Empresa/SP/Category/SP.sql
```

## Como esta hecho

El modulo usa MVC con PHP y POO. La vista carga la tabla y los modals; el JavaScript consume endpoints AJAX; el controlador valida reglas de negocio; el modelo `CategoryCRUD` llama procedimientos almacenados con PDO.

## Tecnologias

- PHP con POO.
- PDO para conexion a MySQL.
- MySQL con stored procedures.
- Bootstrap para tabla, botones y modals.
- jQuery y AJAX para crear, listar, editar, eliminar, restaurar y eliminar definitivamente.
- Font Awesome para iconos.

## Flujo

```text
Vista + JS -> Api/Category/*.php -> CategoryController -> CategoryCRUD -> SP MySQL
```

## Modals

El modulo usa modals Bootstrap para crear, editar, detalle, eliminar, confirmar salida, informacion, inactivos, restaurar y eliminar definitivo.

En `Category.js` se usa `removeClass()` y `addClass()` para cambiar dinamicamente el tamano del modal:

```js
$dialog.removeClass('modal-sm modal-lg modal-xl');
$dialog.addClass(sizeClass);
```

Tambien se usan `removeClass()` y `addClass()` para feedback visual, modo de tabla y estado de arrastre del scroll.

## Puntos clave

- Listado paginado con selector de 10, 20 y 50 registros.
- Busqueda por ID o nombre.
- Scroll dinamico cuando hay mas de 10 registros visibles.
- Separacion entre activos e inactivos.
- Eliminacion logica y eliminacion definitiva.
- Auditoria con `AuditLogger::log()`.
