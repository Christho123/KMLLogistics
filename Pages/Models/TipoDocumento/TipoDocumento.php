<?php
declare(strict_types=1);

// =========================================================
// MODELO: TIPO DOCUMENTO
// Entidad de dominio para el modulo de tipos de documento.
// =========================================================



// Entidad base para el modulo TipoDocumento.
// Tecnologia asociada: POO mediante una clase que encapsula datos de negocio.
class TipoDocumento
{
    public int $idTipoDocumento;
    public string $nombreTipoDocumento;
    public string $descripcion;
    public int $estado;
    public array $tiposDocumento;

    // Constructor simple de apoyo.
    public function __construct(
        int $idTipoDocumento = 0,
        string $nombreTipoDocumento = '',
        string $descripcion = '',
        int $estado = 1,
        array $tiposDocumento = []
    ) {
        $this->idTipoDocumento = $idTipoDocumento;
        $this->nombreTipoDocumento = trim($nombreTipoDocumento);
        $this->descripcion = trim($descripcion);
        $this->estado = $estado;
        $this->tiposDocumento = $tiposDocumento;
    }
}

