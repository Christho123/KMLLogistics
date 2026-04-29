<?php
declare(strict_types=1);

// =========================================================
// MODELO: AUDIT
// Entidad simple para registros de auditoria.
// =========================================================



class Audit
{
    public function __construct(
        public int $idUsuario,
        public string $modulo,
        public string $accion,
        public string $descripcion,
        public ?string $datos
    ) {
    }
}

