<?php
declare(strict_types=1);

// =========================================================
// MODELO: PROVIDER
// Entidad de dominio para el modulo de proveedores.
// =========================================================



// Entidad base para el modulo Providers.
class Provider
{
    public int $idProveedor;
    public string $razonSocial;
    public string $nombreComercial;
    public int $idTipoDocumento;
    public string $numeroDocumento;
    public string $telefono;
    public string $correo;
    public string $direccion;
    public string $contacto;
    public int $estado;
    public array $providers;

     public function __construct(
        int $idProveedor = 0,
        string $razonSocial = '',
        string $nombreComercial = '',
        int $idTipoDocumento = 0,
        string $numeroDocumento = '',
        string $telefono = '',
        string $correo = '',
        string $direccion = '',
        string $contacto = '',
        int $estado = 1,
        array $providers = []
    )
    {
        $this->idProveedor = $idProveedor;
        $this->razonSocial = trim($razonSocial);
        $this->nombreComercial = trim($nombreComercial);
        $this->idTipoDocumento = $idTipoDocumento;
        $this->numeroDocumento = trim($numeroDocumento);
        $this->telefono = trim($telefono);
        $this->correo = trim($correo);
        $this->direccion = trim($direccion);
        $this->contacto = trim($contacto);
        $this->estado = $estado;
        $this->providers = $providers;
    }
}
