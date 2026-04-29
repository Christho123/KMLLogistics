<?php
declare(strict_types=1);

// =========================================================
// VISTA: CATEGORY
// Pantalla principal del modulo de categorias.
// =========================================================



// Carga de estilos de la vista de categorias.
renderHeader('KMLLogistics | Categorias', [
    'Pages/Assets/Css/Pages/Category/Category.css',
]);
renderMenu('category', $data['current_user']);
?>

<main>
    <section id="categorias" class="container py-5">
        <div class="category-wrapper">
            <div class="mb-4">
                <span class="section-badge">Categoria</span>
                <h2 class="fw-bold mt-3 mb-2">Listado de categorias</h2>
                <p class="text-muted mb-0">Consulta en tiempo real de las categorias registradas en el sistema.</p>
            </div>

            <div class="category-toolbar d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
                <div class="category-search-block">
                    <label for="categorySearchInput" class="form-label fw-semibold mb-2">Buscar por ID o nombre</label>
                    <div class="input-group category-search-group">
                        <span class="input-group-text bg-white">
                            <!-- Icono de Font Awesome para representar la busqueda -->
                            <i class="fas fa-search"></i>
                        </span>
                        <input
                            type="text"
                            id="categorySearchInput"
                            class="form-control"
                            placeholder="Ejemplo: 1 o Laptops"
                            autocomplete="off"
                        >
                        <button type="button" class="btn btn-warning" id="filterSearchButton">
                            <!-- Icono de Font Awesome para la accion de filtrar -->
                            <i class="fas fa-filter me-1"></i>Filtrar
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="clearSearchButton">
                            <!-- Icono de Font Awesome para limpiar el filtro -->
                            <i class="fas fa-eraser me-1"></i>Limpiar
                        </button>
                    </div>
                </div>

                <div class="category-actions-toolbar">
                    <button type="button" class="btn btn-outline-secondary category-secondary-button" id="openInactiveModalButton">
                        <!-- Icono de Font Awesome para abrir el listado de inactivos -->
                        <i class="fas fa-archive me-2"></i>Ver inactivos
                    </button>
                    <button type="button" class="btn btn-warning category-create-button" id="openCreateModalButton">
                        <!-- Icono de Font Awesome para crear un nuevo registro -->
                        <i class="fas fa-plus me-2"></i>Crear
                    </button>
                </div>
            </div>

            <div class="category-table-shell table-size-10 shadow-sm rounded-4">
                <table class="table table-striped table-bordered align-middle mb-0 category-table">
                    <thead class="table-warning">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Estado</th>
                            <th>Creado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="categoryTableBody">
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                Cargando categorias...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="category-pagination d-flex flex-column gap-3 mt-3">
                <div class="category-bottom-toolbar d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <label for="pageSizeSelect" class="form-label mb-0 fw-semibold">Registros</label>
                            <select id="pageSizeSelect" class="form-select">
                                <option value="10" selected>10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                        <div class="category-summary text-muted" id="categorySummary">
                            Mostrando 0 de 0 categorias
                        </div>
                    </div>

                    <div class="category-page-status text-muted" id="categoryPageStatus">
                        Pagina 1 de 1
                    </div>
                </div>

                <div class="d-flex justify-content-end align-items-center gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-secondary" id="prevPageButton">Anterior</button>
                    <button type="button" class="btn btn-warning" id="nextPageButton">Siguiente</button>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require __DIR__ . '/CreateCategoryModal.php'; ?>
<?php require __DIR__ . '/EditCategoryModal.php'; ?>
<?php require __DIR__ . '/DetailCategoryModal.php'; ?>
<?php require __DIR__ . '/DeleteCategoryModal.php'; ?>
<?php require __DIR__ . '/ConfirmExitCategoryModal.php'; ?>
<?php require __DIR__ . '/InfoCategoryModal.php'; ?>
<?php require __DIR__ . '/InactiveCategoriesModal.php'; ?>
<?php require __DIR__ . '/HardDeleteInactiveCategoryModal.php'; ?>
<?php require __DIR__ . '/RestoreInactiveCategoryModal.php'; ?>

<?php
renderFooter([
    'Pages/Assets/JS/Pages/Category/Category.js',
]);

