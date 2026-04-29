# Product

## Estructura del modulo

```text
Api/Product/
Pages/Controller/Product/ProductController.php
Pages/Models/Product/Product.php
Pages/Models/Product/ProductCRUD.php
Pages/Views/Product/
Pages/Assets/JS/Pages/Product/Product.js
Pages/Assets/Css/Pages/Product/Product.css
BD/Empresa/SP/Producto/SP.sql
```

## Como esta hecho

El modulo de productos usa MVC y POO. Permite gestionar datos del producto, categoria, marca, stock, precio y foto. El helper `ProductImageHelper.php` centraliza validaciones y guardado de imagen.

## Tecnologias

- PHP POO.
- PDO para base de datos.
- MySQL con stored procedures.
- Bootstrap para layout y modals.
- jQuery AJAX.
- Manejo de archivos para imagenes.
- Font Awesome.

## Flujo

```text
Vista + JS -> Api/Product/*.php -> ProductController -> ProductCRUD -> SP MySQL
```

## Modals

Incluye modals para crear, editar, detalle, eliminar, inactivos, restaurar, hard delete, informacion y confirmar salida.

El JS usa `removeClass()` y `addClass()` para controlar estados visuales, clases de feedback y comportamiento de modals.

## Puntos clave

- CRUD completo.
- Calculo de precio desde costo y ganancia.
- Subida y actualizacion de foto.
- Asociacion con categorias y marcas.
- Eliminacion logica, restauracion y eliminacion definitiva.
- Auditoria por accion.
