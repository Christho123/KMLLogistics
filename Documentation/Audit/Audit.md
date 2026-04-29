# Audit

## Estructura del modulo

```text
Api/Audit/
Pages/Controller/Audit/AuditController.php
Pages/Models/Audit/Audit.php
Pages/Models/Audit/AuditCRUD.php
Pages/Views/Audit/Audit.php
Pages/Assets/JS/Pages/Audit/Audit.js
Pages/Assets/Css/Pages/Audit/Audit.css
Pages/Services/AuditLogger.php
BD/Empresa/SP/Audit/SP.sql
```

## Como esta hecho

El modulo registra acciones del sistema mediante `AuditLogger`. La tabla `audits` guarda usuario, modulo, accion, descripcion, datos JSON, estado y fechas. No guarda `ip` ni `user_agent`.

## Tecnologias

- PHP POO.
- PDO.
- MySQL con stored procedures.
- JSON para datos adicionales.
- Bootstrap.
- jQuery AJAX.
- PHPMailer para notificaciones al email admin.

## Flujo

```text
Controlador -> AuditLogger -> AuditCRUD -> sp_audit_registrar
```

Para consulta:

```text
Vista + JS -> Api/Audit/*.php -> AuditController -> AuditCRUD -> SP MySQL
```

## Interfaz

- Listado paginado.
- Busqueda por inicial o nombre completo del usuario, o por modulo.
- Scroll dinamico cuando hay mas de 10 registros.
- Detalle en modal Bootstrap.
- Solo administradores pueden acceder.

## Notificaciones

Cada accion auditada intenta enviar un correo al email admin configurado en `Pages/Config/Mail.php`.

Si no existe usuario en sesion, el actor se reporta como `Invitado`.
