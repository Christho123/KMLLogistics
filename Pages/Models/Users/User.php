<?php
// =========================================================
// MODELO: USER
// Entidad de dominio para usuarios del sistema.
// =========================================================

declare(strict_types=1);

// Entidad base para usuarios del sistema.
class User
{
    public string $nombres;
    public string $apellidos;
    public string $correo;
    public int $idTipoDocumento;
    public string $numeroDocumento;
    public string $password;

    // Constructor de usuario.
    public function __construct(
        string $nombres = '',
        string $apellidos = '',
        string $correo = '',
        int $idTipoDocumento = 0,
        string $numeroDocumento = '',
        string $password = ''
    ) {
        $this->nombres = trim($nombres);
        $this->apellidos = trim($apellidos);
        $this->correo = trim($correo);
        $this->idTipoDocumento = $idTipoDocumento;
        $this->numeroDocumento = trim($numeroDocumento);
        $this->password = $password;
    }
}
