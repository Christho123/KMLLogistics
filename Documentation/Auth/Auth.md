# Modulo Auth

## Objetivo

El modulo `Auth` gestiona autenticacion, registro y cierre de sesion. Trabaja con usuarios, tipos de documento y passwords hasheadas.

## Estructura del modulo

```text
KMLLogistics/
|-- BD/
|   `-- Empresa/
|       `-- KMLLogistics.sql
`-- Pages/
    |-- Controller/
    |   |-- Login/
    |   |   `-- LoginController.php
    |   `-- Register/
    |       `-- RegisterController.php
    |-- Models/
    |   `-- Users/
    |       |-- User.php
    |       `-- UserCRUD.php
    |-- Views/
    |   |-- Login/
    |   |   `-- Login.php
    |   `-- Register/
    |       `-- Register.php
    `-- Assets/
        |-- Css/
        |   `-- Pages/
        |       |-- Login/
        |       |   `-- Login.css
        |       `-- Register/
        |           `-- Register.css
        `-- JS/
            `-- Pages/
                |-- Login/
                |   `-- Login.js
                `-- Register/
                    `-- Register.js
```

## Arquitectura MVC

Login:

```text
Login.php
    -> LoginController.php
        -> UserCRUD.php
            -> sp_usuario_obtener_por_correo
```

Registro:

```text
Register.php
    -> RegisterController.php
        -> User.php / UserCRUD.php
            -> sp_usuario_registrar
```

## Tecnologias utilizadas

- **PHP:** controladores, sesiones, validaciones y render de vistas.
- **POO y clases:** `User`, `UserCRUD`, `LoginController`, `RegisterController`.
- **MVC:** separa vistas, controladores y acceso a datos.
- **PDO:** llamadas a SP desde `UserCRUD`.
- **MySQL:** tablas `usuarios`, `tipo_documentos` y procedimientos almacenados.
- **Bootstrap:** formularios, botones y layout responsive.
- **Font Awesome:** iconos de usuario, email, password y acciones.
- **jQuery:** interacciones de formulario, por ejemplo mostrar/ocultar password.
- **AJAX:** no es el flujo principal de login/registro; se trabaja principalmente con submit PHP tradicional.

## Stored Procedures usados

Los SP se llaman desde `C:\xampp\htdocs\KMLLogistics\Pages\Models\Users\UserCRUD.php`.

```text
sp_usuario_obtener_por_correo
sp_usuario_registrar
sp_tipo_documento_listar_activos_para_select
sp_tipo_documento_obtener_activo_para_select_por_id
```

## JavaScript y jQuery

Archivos:

```text
C:\xampp\htdocs\KMLLogistics\Pages\Assets\JS\Pages\Login\Login.js
C:\xampp\htdocs\KMLLogistics\Pages\Assets\JS\Pages\Register\Register.js
```

Se usa jQuery para mejorar la experiencia del formulario, especialmente controles de password y eventos de interfaz. Este modulo no depende de `$.ajax()` para autenticar.

## Seguridad

- Passwords generadas con `password_hash()`.
- Verificacion con `password_verify()` en login.
- Manejo de sesion PHP.
- Validacion de correo y tipo de documento.
- Auditoria de login correcto, intento fallido, registro y logout.

## Auditoria

El modulo registra eventos de autenticacion con `AuditLogger`, incluyendo accesos exitosos e intentos fallidos.
