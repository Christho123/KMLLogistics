<?php
declare(strict_types=1);

// =========================================================
// VISTA: PRODUCT
// Pantalla principal del modulo de productos.
// =========================================================



renderHeader('KMLLogistics | Productos', [
    'Pages/Assets/Css/Pages/Product/Product.css',
]);
renderMenu('product', $data['current_user']);
?>

<main>
    <section id="productos" class="container py-5">
        <div class="product-wrapper">
            <div class="mb-4">
                <span class="section-badge">Producto</span>
                <h2 class="fw-bold mt-3 mb-2">Listado de productos</h2>
                <p class="text-muted mb-0">Consulta en tiempo real de los productos registrados en el sistema.</p>
            </div>

            <div class="product-toolbar d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
                <div class="product-search-block">
                    <label for="productSearchInput" class="form-label fw-semibold mb-2">Buscar por ID o nombre</label>
                    <div class="input-group product-search-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" id="productSearchInput" class="form-control" placeholder="Ejemplo: 1 o Laptop" autocomplete="off">
                        <button type="button" class="btn btn-warning" id="filterSearchButton">
                            <i class="fas fa-filter me-1"></i>Filtrar
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="clearSearchButton">
                            <i class="fas fa-eraser me-1"></i>Limpiar
                        </button>
                    </div>
                </div>

                <div class="product-actions-toolbar">
                    <button type="button" class="btn btn-outline-secondary product-secondary-button" id="openInactiveModalButton">
                        <i class="fas fa-archive me-2"></i>Ver inactivos
                    </button>
                    <button type="button" class="btn btn-warning product-create-button" id="openCreateModalButton">
                        <i class="fas fa-plus me-2"></i>Crear
                    </button>
                </div>
            </div>

            <div class="product-table-shell table-size-10 shadow-sm rounded-4">
                <table class="table table-striped table-bordered align-middle mb-0 product-table">
                    <thead class="table-warning">
                        <tr>
                            <th>ID</th>
                            <th>Foto</th>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Creado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody">
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Cargando productos...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="product-pagination d-flex flex-column gap-3 mt-3">
                <div class="product-bottom-toolbar d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <label for="pageSizeSelect" class="form-label mb-0 fw-semibold">Registros</label>
                            <select id="pageSizeSelect" class="form-select">
                                <option value="10" selected>10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                        <div class="product-summary text-muted" id="productSummary">Mostrando 0 de 0 productos</div>
                    </div>

                    <div class="product-page-status text-muted" id="productPageStatus">Pagina 1 de 1</div>
                </div>

                <div class="d-flex justify-content-end align-items-center gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-secondary" id="prevPageButton">Anterior</button>
                    <button type="button" class="btn btn-warning" id="nextPageButton">Siguiente</button>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require __DIR__ . '/CreateProductModal.php'; ?>
<?php require __DIR__ . '/EditProductModal.php'; ?>
<?php require __DIR__ . '/DetailProductModal.php'; ?>
<?php require __DIR__ . '/DeleteProductModal.php'; ?>
<?php require __DIR__ . '/ConfirmExitProductModal.php'; ?>
<?php require __DIR__ . '/InfoProductModal.php'; ?>
<?php require __DIR__ . '/InactiveProductsModal.php'; ?>
<?php require __DIR__ . '/HardDeleteInactiveProductModal.php'; ?>
<?php require __DIR__ . '/RestoreInactiveProductModal.php'; ?>

<?php
renderFooter([
    'Pages/Assets/JS/Pages/Product/Product.js',
]);

