<?php
declare(strict_types=1);

// =========================================================
// MODELO: PRODUCT
// Entidad de dominio para el modulo de productos.
// =========================================================



class Product
{
    public int $idProducto;
    public string $producto;
    public float $costo;
    public float $ganancia;
    public float $precio;
    public int $stock;
    public ?string $foto;
    public int $idCategoria;
    public int $idMarca;
    public int $estado;

    public function __construct(
        int $idProducto = 0,
        string $producto = '',
        float $costo = 0.0,
        float $ganancia = 0.0,
        float $precio = 0.0,
        int $stock = 0,
        ?string $foto = null,
        int $idCategoria = 0,
        int $idMarca = 0,
        int $estado = 1
    ) {
        $this->idProducto = $idProducto;
        $this->producto = trim($producto);
        $this->costo = $costo;
        $this->ganancia = $ganancia;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->foto = $foto !== null && trim($foto) !== '' ? trim($foto) : null;
        $this->idCategoria = $idCategoria;
        $this->idMarca = $idMarca;
        $this->estado = $estado;
    }
}

