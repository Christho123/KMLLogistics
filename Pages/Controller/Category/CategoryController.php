<?php
// =========================================================
// CONTROLADOR: CATEGORY
// Orquesta reglas de negocio y respuestas del modulo.
// =========================================================

declare(strict_types=1);

// Controlador principal del modulo Category.
// Tecnologia asociada: MVC + POO.
class CategoryController
{
    private CategoryCRUD $categoryCRUD;

    // Inicializa dependencias del modulo.
    public function __construct()
    {
        $this->categoryCRUD = new CategoryCRUD();
    }

    // Entrega los datos necesarios para la vista principal.
    public function handleRequest(): array
    {
        return [
            'current_user' => $_SESSION['user'] ?? null,
            'category_status_options' => $this->getStatusOptions(),
        ];
    }

    // Orquesta el listado principal de categorias.
    // Metodo clave consumido por la API de listado.
    public function listCategories(int $page, int $pageSize, string $search): array
    {
        $result = $this->categoryCRUD->listCategories($page, $pageSize, $search);
        $total = (int) ($result['total'] ?? 0);
        $totalPages = max(1, (int) ceil($total / $pageSize));
        $currentPage = min($page, $totalPages);

        if ($currentPage !== $page) {
            $result = $this->categoryCRUD->listCategories($currentPage, $pageSize, $search);
        }

        return [
            'success' => true,
            'categories' => $result['categories'] ?? [],
            'pagination' => [
                'page' => $currentPage,
                'page_size' => $pageSize,
                'total' => $total,
                'total_pages' => $totalPages,
            ],
            'search' => $search,
        ];
    }

    // Orquesta el listado de categorias inactivas.
    public function listInactiveCategories(string $search): array
    {
        $categories = $this->categoryCRUD->listInactiveCategories($search);

        return [
            'success' => true,
            'categories' => $categories,
            'total' => count($categories),
            'search' => $search,
        ];
    }

    // Obtiene una categoria activa por id.
    public function getCategory(int $idCategoria): array
    {
        $category = $this->categoryCRUD->findCategoryById($idCategoria);

        if ($category === null) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'La categoria solicitada no existe o ya no esta disponible.',
            ];
        }

        return [
            'success' => true,
            'category' => $category,
        ];
    }

    // Crea una nueva categoria.
    // Metodo clave con validacion de duplicados antes de insertar.
    public function createCategory(string $nombreCategoria, string $descripcion, int $estado): array
    {
        if ($this->categoryCRUD->existsByName($nombreCategoria)) {
            return [
                'success' => false,
                'status_code' => 409,
                'message' => 'Ya existe una categoria activa con este nombre.',
            ];
        }

        $category = new Category(0, $nombreCategoria, $descripcion, $estado);
        $idCategoria = $this->categoryCRUD->create($category);

        return [
            'success' => true,
            'message' => 'La categoria fue creada correctamente.',
            'id_categoria' => $idCategoria,
        ];
    }

    // Actualiza una categoria existente.
    // Metodo clave que evita nombres repetidos en categorias activas.
    public function updateCategory(int $idCategoria, string $nombreCategoria, string $descripcion, int $estado): array
    {
        $currentCategory = $this->categoryCRUD->findCategoryById($idCategoria);

        if ($currentCategory === null) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'La categoria que intentas editar ya no esta disponible.',
            ];
        }

        if ($this->categoryCRUD->existsByName($nombreCategoria, $idCategoria)) {
            return [
                'success' => false,
                'status_code' => 409,
                'message' => 'Ya existe otra categoria activa con este nombre.',
            ];
        }

        $category = new Category($idCategoria, $nombreCategoria, $descripcion, $estado);
        $updated = $this->categoryCRUD->update($category);

        return [
            'success' => true,
            'message' => $updated
                ? 'La categoria fue actualizada correctamente.'
                : 'No hubo cambios para guardar, pero la categoria sigue disponible.',
        ];
    }

    // Elimina logicamente una categoria activa.
    public function deleteCategory(int $idCategoria): array
    {
        $currentCategory = $this->categoryCRUD->findCategoryById($idCategoria);

        if ($currentCategory === null) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'La categoria ya no se encuentra disponible para eliminar.',
            ];
        }

        $deleted = $this->categoryCRUD->delete($idCategoria);

        if (!$deleted) {
            return [
                'success' => false,
                'status_code' => 409,
                'message' => 'No se pudo completar la eliminacion de la categoria.',
            ];
        }

        return [
            'success' => true,
            'message' => 'La categoria fue eliminada del listado activo correctamente.',
        ];
    }

    // Restaura una categoria inactiva.
    public function restoreCategory(int $idCategoria): array
    {
        $category = $this->categoryCRUD->findAnyCategoryById($idCategoria);

        if ($category === null || ($category['deleted_at'] === null && (int) $category['estado'] !== 0)) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'La categoria inactiva solicitada ya no esta disponible.',
            ];
        }

        if ($this->categoryCRUD->existsByName((string) ($category['nombre_categoria'] ?? ''), $idCategoria)) {
            return [
                'success' => false,
                'status_code' => 409,
                'message' => 'No se puede restaurar esta categoria porque ya existe una categoria activa con este nombre.',
            ];
        }

        $restored = $this->categoryCRUD->restore($idCategoria);

        return [
            'success' => $restored,
            'status_code' => $restored ? 200 : 409,
            'message' => $restored
                ? 'La categoria fue restaurada correctamente.'
                : 'No se pudo restaurar la categoria seleccionada.',
        ];
    }

    // Elimina definitivamente una categoria inactiva.
    public function hardDeleteCategory(int $idCategoria): array
    {
        $category = $this->categoryCRUD->findAnyCategoryById($idCategoria);

        if ($category === null || ($category['deleted_at'] === null && (int) $category['estado'] !== 0)) {
            return [
                'success' => false,
                'status_code' => 404,
                'message' => 'La categoria inactiva ya no esta disponible para eliminacion definitiva.',
            ];
        }

        $result = $this->categoryCRUD->hardDelete($idCategoria);

        if (!$result['deleted_category']) {
            return [
                'success' => false,
                'status_code' => 409,
                'message' => 'No se pudo completar la eliminacion definitiva.',
            ];
        }

        return [
            'success' => true,
            'message' => 'La categoria fue eliminada definitivamente de la base de datos.',
            'deleted_products' => (int) $result['deleted_products'],
        ];
    }

    // Opciones de estado para formularios del modulo.
    public function getStatusOptions(): array
    {
        return [
            ['value' => 1, 'label' => 'Activo'],
            ['value' => 0, 'label' => 'Inactivo'],
        ];
    }
}
