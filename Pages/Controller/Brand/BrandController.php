<?php
declare(strict_types=1);

// =========================================================
// CONTROLADOR: BRAND CONTROLLER
// Maneja la logica de peticiones para el modulo de Marcas.
// =========================================================



class BrandController
{
    private BrandCRUD $brandModel;

    public function __construct()
    {
        $this->brandModel = new BrandCRUD();
    }

    public function handleRequest(): array
    {
        return [
            'current_user' => $_SESSION['user'] ?? null,
            'brand_status_options' => $this->getStatusOptions(),
            'suppliers' => $this->brandModel->listSuppliers(),
        ];
    }

    public function listBrands(int $page, int $pageSize, string $search): array
    {
        $result = $this->brandModel->listBrands($page, $pageSize, $search);
        $total = (int) ($result['total'] ?? 0);
        $totalPages = max(1, (int) ceil($total / $pageSize));
        $currentPage = min($page, $totalPages);

        if ($currentPage !== $page) {
            $result = $this->brandModel->listBrands($currentPage, $pageSize, $search);
        }

        return [
            'success' => true,
            'brands' => $result['brands'] ?? [],
            'pagination' => [
                'page' => $currentPage,
                'page_size' => $pageSize,
                'total' => $total,
                'total_pages' => $totalPages,
            ],
            'search' => $search,
        ];
    }

    public function listInactiveBrands(string $search): array
    {
        $brands = $this->brandModel->listInactiveBrands($search);
        return [
            'success' => true,
            'brands' => $brands,
            'total' => count($brands),
            'search' => $search,
        ];
    }

    public function getBrand(int $idMarca): array
    {
        $brand = $this->brandModel->findBrandById($idMarca);
        if ($brand === null) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'La marca solicitada no existe o ya no esta disponible.',
            ];
        }
        return [
            'success' => true,
            'brand' => $brand,
        ];
    }

    public function createBrand(string $nombreMarca, int $idProveedor, int $estado): array
    {
        if ($this->brandModel->existsByName($nombreMarca)) {
            return [
                'success' => false,
                'status_code' => 409,
                'message' => 'Ya existe una marca activa con este nombre.',
            ];
        }
        $brand = new Brand(0, $nombreMarca, $idProveedor, $estado);
        $idMarca = $this->brandModel->create($brand);
        AuditLogger::log('Marca', 'Crear marca', 'Se creo una marca.', ['id_marca' => $idMarca, 'nombre_marca' => $nombreMarca]);
        return [
            'success' => true,
            'message' => 'La marca fue creada correctamente.',
            'id_marca' => $idMarca,
        ];
    }

    public function updateBrand(int $idMarca, string $nombreMarca, int $idProveedor, int $estado): array
    {
        $currentBrand = $this->brandModel->findBrandById($idMarca);
        if ($currentBrand === null) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'La marca que intentas editar ya no esta disponible.',
            ];
        }
        if ($this->brandModel->existsByName($nombreMarca, $idMarca)) {
            return [
                'success' => false,
                'status_code' => 409,
                'message' => 'Ya existe otra marca activa con este nombre.',
            ];
        }
        $brand = new Brand($idMarca, $nombreMarca, $idProveedor, $estado);
        $updated = $this->brandModel->update($brand);
        AuditLogger::log('Marca', 'Actualizar marca', 'Se actualizo una marca.', ['id_marca' => $idMarca, 'nombre_marca' => $nombreMarca]);
        return [
            'success' => true,
            'message' => $updated ? 'La marca fue actualizada correctamente.' : 'No hubo cambios para guardar.',
        ];
    }

    public function deleteBrand(int $idMarca): array
    {
        $deleted = $this->brandModel->delete($idMarca);
        if ($deleted) {
            AuditLogger::log('Marca', 'Eliminar marca', 'Se elimino logicamente una marca.', ['id_marca' => $idMarca]);
        }
        return [
            'success' => $deleted,
            'status_code' => $deleted ? 200 : 409,
            'message' => $deleted ? 'La marca fue eliminada correctamente.' : 'No se pudo eliminar la marca.',
        ];
    }

    public function restoreBrand(int $idMarca): array
    {
        $brand = $this->brandModel->findAnyBrandById($idMarca);
        if ($this->brandModel->existsByName($brand['nombre_marca'] ?? '', $idMarca)) {
            return [
                'success' => false,
                'status_code' => 409,
                'message' => 'No se puede restaurar porque el nombre ya existe activo.',
            ];
        }
        $restored = $this->brandModel->restore($idMarca);
        if ($restored) {
            AuditLogger::log('Marca', 'Restaurar marca', 'Se restauro una marca.', ['id_marca' => $idMarca]);
        }
        return [
            'success' => $restored,
            'message' => $restored ? 'Marca restaurada.' : 'Error al restaurar.',
        ];
    }

    public function hardDeleteBrand(int $idMarca): array
    {
        $result = $this->brandModel->hardDelete($idMarca);
        if (!empty($result['deleted_brand'])) {
            AuditLogger::log('Marca', 'Eliminar definitivo', 'Se elimino definitivamente una marca.', ['id_marca' => $idMarca]);
        }
        return [
            'success' => (bool)$result['deleted_brand'],
            'message' => $result['deleted_brand'] ? 'Marca eliminada definitivamente.' : 'Error al eliminar.',
            'deleted_products' => (int)$result['deleted_products'],
        ];
    }

    public function getStatusOptions(): array
    {
        return [
            ['value' => 1, 'label' => 'Activo'],
            ['value' => 0, 'label' => 'Inactivo'],
        ];
    }
}

