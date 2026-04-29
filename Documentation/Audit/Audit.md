# Modulo Audit

## Objetivo

El modulo `Audit` registra y consulta acciones importantes del sistema. Permite al administrador revisar eventos por usuario, modulo, accion, descripcion, datos JSON y fecha.

## Estructura del modulo

```text
KMLLogistics/
|-- Api/
|   `-- Audit/
|       |-- Get.php
|       `-- List.php
|-- BD/
|   `-- Empresa/
|       `-- KMLLogistics.sql
`-- Pages/
    |-- Controller/
    |   `-- Audit/
    |       `-- AuditController.php
    |-- Models/
    |   `-- Audit/
    |       |-- Audit.php
    |       `-- AuditCRUD.php
    |-- Services/
    |   `-- AuditLogger.php
    |-- Views/
    |   `-- Audit/
    |       `-- Audit.php
    `-- Assets/
        |-- Css/
        |   `-- Pages/
        |       `-- Audit/
        |           `-- Audit.css
        `-- JS/
            `-- Pages/
                `-- Audit/
                    `-- Audit.js
```

## Arquitectura MVC

Para registrar auditoria:

```text
Controlador del modulo
    -> AuditLogger.php
        -> AuditCRUD.php
            -> sp_audit_registrar
```

Para consultar auditoria:

```text
Vista Audit.php
    -> JavaScript Audit.js
        -> Api/Audit/*.php
            -> AuditController.php
                -> AuditCRUD.php
                    -> Stored Procedures MySQL
```

## Tecnologias utilizadas

- **PHP:** controlador, servicio de auditoria, endpoints y modelos.
- **POO y clases:** `Audit`, `AuditCRUD`, `AuditController`, `AuditLogger`.
- **MVC:** vista, JS, API, controlador y modelo.
- **PDO:** ejecucion de SP.
- **MySQL:** tabla `audits` y procedimientos almacenados.
- **JSON:** columna `datos` para informacion adicional.
- **Bootstrap:** tabla, paginacion y modal de detalle.
- **Font Awesome:** iconos de interfaz.
- **jQuery:** eventos, render de filas y manipulacion de clases.
- **AJAX:** consulta de listado y detalle.
- **PHPMailer:** notificacion opcional al correo administrador desde el flujo de auditoria.

## Stored Procedures usados

Los SP se llaman desde `C:\xampp\htdocs\KMLLogistics\Pages\Models\Audit\AuditCRUD.php`.

```text
sp_audit_registrar
sp_audit_listar_activas
sp_audit_contar_activas
sp_audit_obtener_activa_por_id
```

## AJAX y jQuery

Archivo principal: `C:\xampp\htdocs\KMLLogistics\Pages\Assets\JS\Pages\Audit\Audit.js`.

Endpoints consumidos:

```text
Api/Audit/List.php
Api/Audit/Get.php
```

`Audit.js` usa jQuery para pintar filas, mostrar estados, abrir el modal Bootstrap y alternar clases con `addClass()` y `removeClass()` cuando cambia el estado de la interfaz.

## Interfaz

- Listado paginado.
- Busqueda por usuario, modulo o accion.
- Modal de detalle.
- Visualizacion de datos JSON.
- Acceso orientado a usuarios administradores.

## Puntos clave

- Si no existe usuario en sesion, el actor puede registrarse como invitado.
- La auditoria centraliza trazabilidad de otros modulos.
- El servicio `AuditLogger` evita duplicar codigo de auditoria en cada controlador.
