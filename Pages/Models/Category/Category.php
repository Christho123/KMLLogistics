<?php
declare(strict_types=1);

// =========================================================
// MODELO: CATEGORY
// Entidad de dominio para el modulo de categorias.
// =========================================================



// Entidad base para el modulo Category.
// Tecnologia asociada: POO mediante una clase que encapsula datos de negocio.
class Category
{
    public int $idCategoria;
    public string $nombreCategoria;
    public string $descripcion;
    public int $estado;
    public array $categories;

    // Constructor simple de apoyo.
    public function __construct(
        int $idCategoria = 0,
        string $nombreCategoria = '',
        string $descripcion = '',
        int $estado = 1,
        array $categories = []
    )
    {
        $this->idCategoria = $idCategoria;
        $this->nombreCategoria = trim($nombreCategoria);
        $this->descripcion = trim($descripcion);
        $this->estado = $estado;
        $this->categories = $categories;
    }
}

