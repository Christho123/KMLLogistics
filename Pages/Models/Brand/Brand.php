<?php
declare(strict_types=1);

// =========================================================
// MODELO: BRAND
// Entidad de dominio para el modulo de marcas.
// =========================================================



class Brand
{
    public int $idMarca;
    public string $nombreMarca;
    public int $idProveedor; // Cambiado: ahora apunta al proveedor
    public int $estado;

    public function __construct(
        int $idMarca = 0,
        string $nombreMarca = '',
        int $idProveedor = 0,
        int $estado = 1
    )
    {
        $this->idMarca = $idMarca;
        $this->nombreMarca = trim($nombreMarca);
        $this->idProveedor = $idProveedor;
        $this->estado = $estado;
    }
}
