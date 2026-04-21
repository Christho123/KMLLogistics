<?php

declare(strict_types=1);

// Entidad base para usuarios del sistema.
class User
{
    public string $nombres;
    public string $apellidos;
    public string $correo;
    public string $password;

    // Constructor de usuario.
    public function __construct(
        string $nombres = '',
        string $apellidos = '',
        string $correo = '',
        string $password = ''
    ) {
        $this->nombres = trim($nombres);
        $this->apellidos = trim($apellidos);
        $this->correo = trim($correo);
        $this->password = $password;
    }
}
