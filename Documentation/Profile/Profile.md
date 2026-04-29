# Modulo Profile

## Objetivo

El modulo `Profile` permite al usuario actualizar datos personales, foto, correo, rol, verificar email y cambiar password mediante codigo.

## Estructura del modulo

```text
KMLLogistics/
|-- Api/
|   `-- Profile/
|       |-- ChangePassword.php
|       |-- ConfirmEmail.php
|       |-- DeletePhoto.php
|       |-- SendEmailCode.php
|       |-- SendPasswordCode.php
|       |-- Update.php
|       `-- UploadPhoto.php
|-- BD/
|   `-- Empresa/
|       `-- KMLLogistics.sql
`-- Pages/
    |-- Config/
    |   `-- Mail.php
    |-- Controller/
    |   `-- Profile/
    |       `-- ProfileController.php
    |-- Models/
    |   `-- Users/
    |       |-- User.php
    |       `-- UserCRUD.php
    |-- Services/
    |   `-- MailerService.php
    |-- Views/
    |   `-- Profile/
    |       `-- Profile.php
    `-- Assets/
        |-- Css/
        |   `-- Pages/
        |       `-- Profile/
        |           `-- Profile.css
        `-- JS/
            `-- Pages/
                `-- Profile/
                    `-- Profile.js
```

## Arquitectura MVC

```text
Vista Profile.php
    -> JavaScript Profile.js
        -> Api/Profile/*.php
            -> ProfileController.php
                -> UserCRUD.php
                    -> Stored Procedures MySQL
```

## Tecnologias utilizadas

- **PHP:** endpoints, controlador y manejo de sesion.
- **POO y clases:** `ProfileController`, `User`, `UserCRUD`, `MailerService`.
- **MVC:** vista, API, controlador y modelo de usuarios.
- **PDO:** llamadas a SP de usuario y codigos.
- **MySQL:** tablas `usuarios`, `usuario_codigos`, `tipo_documentos`.
- **Bootstrap:** formularios, botones y componentes visuales.
- **Font Awesome:** iconos de perfil, foto, password y email.
- **jQuery:** eventos, DOM, `addClass()` y `removeClass()`.
- **AJAX:** actualizacion del perfil sin recargar.
- **PHPMailer:** envio de codigos para email y password.
- **Manejo de archivos:** subida y eliminacion de foto de perfil.

## Stored Procedures usados

Los SP se llaman desde `C:\xampp\htdocs\KMLLogistics\Pages\Models\Users\UserCRUD.php`.

```text
sp_usuario_obtener_perfil
sp_usuario_actualizar_perfil
sp_usuario_actualizar_foto
sp_usuario_codigo_crear
sp_usuario_codigo_obtener_vigente
sp_usuario_codigo_marcar_usado
sp_usuario_cambiar_password
sp_usuario_verificar_email
sp_tipo_documento_listar_activos_para_select
sp_tipo_documento_obtener_activo_para_select_por_id
```

## AJAX y jQuery

Archivo principal: `C:\xampp\htdocs\KMLLogistics\Pages\Assets\JS\Pages\Profile\Profile.js`.

Endpoints consumidos:

```text
Api/Profile/Update.php
Api/Profile/UploadPhoto.php
Api/Profile/DeletePhoto.php
Api/Profile/SendEmailCode.php
Api/Profile/ConfirmEmail.php
Api/Profile/SendPasswordCode.php
Api/Profile/ChangePassword.php
```

`Profile.js` usa AJAX para guardar cambios, subir fotos y validar codigos. Tambien usa `addClass()` y `removeClass()` para mostrar estados de carga, feedback visual, errores y cambios en el menu superior.

## Interfaz

- Formulario de datos personales.
- Cambio de rol.
- Subida y eliminacion de foto.
- Verificacion de email con codigo.
- Cambio de password con codigo.
- Actualizacion del menu sin recargar la pagina.

## Auditoria

Las acciones del perfil se registran con `AuditLogger`, incluyendo cambios de datos, foto, verificacion de email y password.
