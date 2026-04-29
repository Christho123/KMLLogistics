# DocumentType

## Estructura del modulo

```text
Api/TipoDocumento/
Pages/Controller/TipoDocumento/TipoDocumentoController.php
Pages/Models/TipoDocumento/TipoDocumento.php
Pages/Models/TipoDocumento/TipoDocumentoCRUD.php
Pages/Views/TipoDocumento/
Pages/Assets/JS/Pages/TipoDocumento/TipoDocumento.js
Pages/Assets/Css/Pages/TipoDocumento/TipoDocumento.css
BD/Empresa/SP/TipoDocumento/SP.sql
```

## Como esta hecho

El modulo `TipoDocumento` sigue MVC con PHP orientado a objetos. La vista presenta listado y modals, el JS maneja AJAX, el controlador valida datos y el modelo CRUD ejecuta procedimientos almacenados con PDO.

## Tecnologias

- PHP con POO.
- PDO.
- MySQL y stored procedures.
- Bootstrap.
- jQuery y AJAX.
- Font Awesome.

## Flujo

```text
Vista + JS -> Api/TipoDocumento/*.php -> TipoDocumentoController -> TipoDocumentoCRUD -> SP MySQL
```

## Modals

Incluye modals para crear, editar, detalle, eliminar, confirmar salida, informacion, inactivos, restaurar y eliminar definitivo.

El JavaScript usa `removeClass()` y `addClass()` para controlar tamanos de modal y estados visuales, siguiendo el mismo patron aplicado en Category.

## Puntos clave

- Listado paginado.
- Busqueda por ID o nombre.
- Validacion de nombres duplicados.
- Eliminacion logica.
- Restauracion de registros inactivos.
- Eliminacion definitiva bloqueada si existen dependencias con usuarios o proveedores.
- Auditoria de acciones.
