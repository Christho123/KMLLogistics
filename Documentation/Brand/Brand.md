# Brand

## Estructura del modulo

```text
Api/Brand/
Pages/Controller/Brand/BrandController.php
Pages/Models/Brand/Brand.php
Pages/Models/Brand/BrandCRUD.php
Pages/Views/Brand/
Pages/Assets/JS/Pages/Brand/Brand.js
Pages/Assets/Css/Pages/Brand/Brand.css
BD/Empresa/SP/Brand/SP.sql
```

## Como esta hecho

El modulo de marcas usa MVC con PHP y POO. Las marcas se relacionan con proveedores. El frontend opera por AJAX y el backend persiste mediante PDO y stored procedures.

## Tecnologias

- PHP POO.
- PDO.
- MySQL con stored procedures.
- Bootstrap para UI y modals.
- jQuery AJAX para operaciones CRUD.
- Font Awesome.

## Flujo

```text
Vista + JS -> Api/Brand/*.php -> BrandController -> BrandCRUD -> SP MySQL
```

## Modals

Usa modals Bootstrap para crear, editar, detalle, eliminar, listar inactivos, restaurar y eliminar definitivamente.

En JavaScript se aplican patrones visuales con `removeClass()` y `addClass()` para feedback, botones, tamanos de modal y estados de interfaz.

## Puntos clave

- CRUD completo de marcas.
- Relacion con proveedores.
- Listado de activos e inactivos.
- Restauracion y hard delete.
- Auditoria de acciones realizadas.
