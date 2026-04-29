# Providers

## Estructura del modulo

```text
Api/Providers/
Pages/Controller/Providers/ProviderController.php
Pages/Models/Providers/Provider.php
Pages/Models/Providers/ProviderCRUD.php
Pages/Views/Providers/
Pages/Assets/JS/Pages/Providers/Providers.js
Pages/Assets/Css/Pages/Providers/Providers.css
BD/Empresa/SP/Provider/SP.sql
```

## Como esta hecho

El modulo de proveedores usa MVC con PHP POO. Trabaja con tipos de documento para validar informacion comercial y usa endpoints AJAX para operaciones del frontend.

## Tecnologias

- PHP POO.
- PDO.
- MySQL con stored procedures.
- Bootstrap.
- jQuery AJAX.
- Font Awesome.

## Flujo

```text
Vista + JS -> Api/Providers/*.php -> ProviderController -> ProviderCRUD -> SP MySQL
```

## Modals

Usa modals para crear, editar, detalle, eliminar, listar inactivos, restaurar, hard delete, informacion y confirmar salida.

El frontend usa `removeClass()` y `addClass()` para manejar estados visuales, clases de feedback y comportamiento del modal.

## Puntos clave

- CRUD de proveedores.
- Asociacion con tipo de documento.
- Validacion de dependencias.
- Activos e inactivos.
- Auditoria de acciones.
