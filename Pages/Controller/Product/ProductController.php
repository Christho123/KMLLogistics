<?php
// =========================================================
// CONTROLADOR: PRODUCT
// Orquesta reglas de negocio y respuestas del modulo.
// =========================================================

declare(strict_types=1);

class ProductController
{
    private ProductCRUD $productCRUD;

    public function __construct()
    {
        $this->productCRUD = new ProductCRUD();
    }

    public function handleRequest(): array
    {
        return [
            'current_user' => $_SESSION['user'] ?? null,
            'product_status_options' => $this->getStatusOptions(),
            'categories' => $this->productCRUD->listCategories(),
            'brands' => $this->productCRUD->listBrands(),
        ];
    }

    public function listProducts(int $page, int $pageSize, string $search): array
    {
        $result = $this->productCRUD->listProducts($page, $pageSize, $search);
        $total = (int) ($result['total'] ?? 0);
        $totalPages = max(1, (int) ceil($total / $pageSize));
        $currentPage = min($page, $totalPages);

        if ($currentPage !== $page) {
            $result = $this->productCRUD->listProducts($currentPage, $pageSize, $search);
        }

        return [
            'success' => true,
            'products' => $result['products'] ?? [],
            'pagination' => [
                'page' => $currentPage,
                'page_size' => $pageSize,
                'total' => $total,
                'total_pages' => $totalPages,
            ],
            'search' => $search,
        ];
    }

    public function listInactiveProducts(string $search): array
    {
        $products = $this->productCRUD->listInactiveProducts($search);

        return [
            'success' => true,
            'products' => $products,
            'total' => count($products),
            'search' => $search,
        ];
    }

    public function getProduct(int $idProducto): array
    {
        $product = $this->productCRUD->findProductById($idProducto);

        if ($product === null) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'El producto solicitado no existe o ya no esta disponible.',
            ];
        }

        return [
            'success' => true,
            'product' => $product,
        ];
    }

    public function createProduct(
        string $producto,
        float $costo,
        float $gananciaPercent,
        int $stock,
        ?string $foto,
        int $idCategoria,
        int $idMarca,
        int $estado
    ): array {
        if ($this->productCRUD->existsByName($producto)) {
            return [
                'success' => false,
                'status_code' => 409,
                'message' => 'Ya existe un producto activo con este nombre.',
            ];
        }

        $ganancia = $this->percentToDecimal($gananciaPercent);
        $precio = $this->calculatePrice($costo, $ganancia);
        $product = new Product(0, $producto, $costo, $ganancia, $precio, $stock, $foto, $idCategoria, $idMarca, $estado);
        $idProducto = $this->productCRUD->create($product);

        return [
            'success' => true,
            'message' => 'El producto fue creado correctamente.',
            'id_producto' => $idProducto,
            'precio' => $precio,
        ];
    }

    public function updateProduct(
        int $idProducto,
        string $producto,
        float $costo,
        float $gananciaPercent,
        int $stock,
        ?string $foto,
        int $idCategoria,
        int $idMarca,
        int $estado
    ): array {
        $currentProduct = $this->productCRUD->findProductById($idProducto);

        if ($currentProduct === null) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'El producto que intentas editar ya no esta disponible.',
            ];
        }

        if ($this->productCRUD->existsByName($producto, $idProducto)) {
            return [
                'success' => false,
                'status_code' => 409,
                'message' => 'Ya existe otro producto activo con este nombre.',
            ];
        }

        $ganancia = $this->percentToDecimal($gananciaPercent);
        $precio = $this->calculatePrice($costo, $ganancia);
        $fotoFinal = $foto !== null ? $foto : ($currentProduct['foto'] ?? null);
        $product = new Product($idProducto, $producto, $costo, $ganancia, $precio, $stock, $fotoFinal, $idCategoria, $idMarca, $estado);
        $updated = $this->productCRUD->update($product);

        return [
            'success' => true,
            'message' => $updated
                ? 'El producto fue actualizado correctamente.'
                : 'No hubo cambios para guardar, pero el producto sigue disponible.',
            'precio' => $precio,
        ];
    }

    public function deleteProduct(int $idProducto): array
    {
        $currentProduct = $this->productCRUD->findProductById($idProducto);

        if ($currentProduct === null) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'El producto ya no se encuentra disponible para eliminar.',
            ];
        }

        $deleted = $this->productCRUD->delete($idProducto);

        return [
            'success' => $deleted,
            'status_code' => $deleted ? 200 : 409,
            'message' => $deleted
                ? 'El producto fue eliminado del listado activo correctamente.'
                : 'No se pudo completar la eliminacion del producto.',
        ];
    }

    public function restoreProduct(int $idProducto): array
    {
        $product = $this->productCRUD->findAnyProductById($idProducto);

        if ($product === null || ($product['deleted_at'] === null && (int) $product['estado'] !== 0)) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'El producto inactivo solicitado ya no esta disponible.',
            ];
        }

        if ($this->productCRUD->existsByName((string) ($product['producto'] ?? ''), $idProducto)) {
            return [
                'success' => false,
                'status_code' => 409,
                'message' => 'No se puede restaurar este producto porque ya existe un producto activo con este nombre.',
            ];
        }

        $restored = $this->productCRUD->restore($idProducto);

        return [
            'success' => $restored,
            'status_code' => $restored ? 200 : 409,
            'message' => $restored
                ? 'El producto fue restaurado correctamente.'
                : 'No se pudo restaurar el producto seleccionado.',
        ];
    }

    public function hardDeleteProduct(int $idProducto): array
    {
        $product = $this->productCRUD->findAnyProductById($idProducto);

        if ($product === null || ($product['deleted_at'] === null && (int) $product['estado'] !== 0)) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'El producto inactivo ya no esta disponible para eliminacion definitiva.',
            ];
        }

        $deleted = $this->productCRUD->hardDelete($idProducto);

        return [
            'success' => $deleted,
            'status_code' => $deleted ? 200 : 409,
            'message' => $deleted
                ? 'El producto fue eliminado definitivamente de la base de datos.'
                : 'No se pudo completar la eliminacion definitiva.',
        ];
    }

    public function getStatusOptions(): array
    {
        return [
            ['value' => 1, 'label' => 'Activo'],
            ['value' => 0, 'label' => 'Inactivo'],
        ];
    }

    private function percentToDecimal(float $gananciaPercent): float
    {
        return round($gananciaPercent / 100, 4);
    }

    private function calculatePrice(float $costo, float $ganancia): float
    {
        if ($ganancia >= 1) {
            return 0.0;
        }

        return round($costo / (1 - $ganancia), 2);
    }
}
