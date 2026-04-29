# Profile

## Estructura del modulo

```text
Api/Profile/
Pages/Controller/Profile/ProfileController.php
Pages/Views/Profile/Profile.php
Pages/Assets/JS/Pages/Profile/Profile.js
Pages/Assets/Css/Pages/Profile/Profile.css
Pages/Services/MailerService.php
Pages/Config/Mail.php
```

## Como esta hecho

El perfil permite actualizar datos personales, rol, foto, verificar email y cambiar password con codigo. Usa AJAX para evitar recargar la pagina y sincroniza el menu cuando cambia el rol o la foto.

## Tecnologias

- PHP POO.
- PDO mediante `UserCRUD`.
- Bootstrap.
- jQuery AJAX.
- PHPMailer para enviar codigos.
- Manejo de archivos para foto de perfil.

## Flujo

```text
Profile.php + Profile.js -> Api/Profile/*.php -> ProfileController -> UserCRUD
```

## AJAX

El JS actualiza:

- Datos del formulario.
- Foto de perfil con cache-buster.
- Menu superior.
- Visibilidad de Auditoria segun rol.
- Bloque de verificacion de email si ya fue verificado.

## Puntos clave

- Subir y eliminar foto sin F5.
- Guardar perfil sin F5.
- Ocultar Auditoria si el usuario deja de ser admin.
- Enviar codigos de email y password con PHPMailer.
- Auditar acciones del perfil.
