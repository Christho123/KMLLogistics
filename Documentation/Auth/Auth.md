# Auth

## Estructura del modulo

```text
Pages/Controller/Login/LoginController.php
Pages/Controller/Register/RegisterController.php
Pages/Views/Login/Login.php
Pages/Views/Register/Register.php
Pages/Assets/JS/Pages/Login/Login.js
Pages/Assets/JS/Pages/Register/Register.js
Pages/Models/Users/User.php
Pages/Models/Users/UserCRUD.php
BD/Empresa/SP/User/SP.sql
```

## Como esta hecho

El modulo de autenticacion maneja login, logout y registro de usuarios. Usa POO con controladores y modelos, y guarda passwords con `password_hash()`.

## Tecnologias

- PHP POO.
- PDO.
- MySQL con stored procedures.
- Bootstrap.
- Font Awesome.
- jQuery para alternar visibilidad de password.

## Flujo de login

```text
Login.php -> LoginController -> UserCRUD -> sp_usuario_obtener_por_correo
```

## Flujo de registro

```text
Register.php -> RegisterController -> UserCRUD -> sp_usuario_registrar
```

## Puntos clave

- Passwords hasheadas.
- Sesion PHP.
- Auditoria para login correcto, logout, registro e intento fallido.
- Si no hay sesion, las acciones se notifican como `Invitado`.
