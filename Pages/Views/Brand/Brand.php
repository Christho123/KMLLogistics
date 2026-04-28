<?php
declare(strict_types=1);

renderHeader('KMLLogistics | Marcas', [
    'Pages/Assets/Css/Pages/Category/Category.css',
    'Pages/Assets/Css/Pages/Brand/Brand.css', // Corregido: Se mantiene el CSS de Brand
]);
renderMenu('brand', $data['current_user'] ?? null);
?>

<main>
    <section id="marcas" class="container py-5">
        <div class="brand-wrapper">
            <div class="mb-4">
                <span class="section-badge">Marcas</span>
                <h2 class="fw-bold mt-3 mb-2">Listado de marcas</h2>
                <p class="text-muted mb-0">Gestión y consulta de las marcas registradas para los productos.</p>
            </div>

            <div class="brand-toolbar d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
                <div class="brand-search-block">
                    <label for="brandSearchInput" class="form-label fw-semibold mb-2">Buscar por ID o nombre</label>
                    <div class="input-group brand-search-group">
                        <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                        <input type="text" id="brandSearchInput" class="form-control" placeholder="Ejemplo: 1 o Sony" autocomplete="off">
                        <button type="button" class="btn btn-warning" id="filterBrandSearchButton">
                            <i class="fas fa-filter me-1"></i>Filtrar
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="clearBrandSearchButton">
                            <i class="fas fa-eraser me-1"></i>Limpiar
                        </button>
                    </div>
                </div>

                <div class="brand-actions-toolbar">
                    <button type="button" class="btn btn-outline-secondary" id="openInactiveBrandsModalButton">
                        <i class="fas fa-archive me-2"></i>Ver inactivos
                    </button>
                    <button type="button" class="btn btn-warning" id="openCreateBrandModalButton">
                        <i class="fas fa-plus me-2"></i>Crear
                    </button>
                </div>
            </div>

            <div class="brand-table-shell table-size-10 shadow-sm rounded-4">
                <table class="table table-striped table-bordered align-middle mb-0 brand-table"></table>
                <table class="table table-striped table-bordered align-middle mb-0">
                    <thead class="table-warning">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Proveedor</th>
                            <th>Estado</th>
                            <th>Creado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="brandTableBody">
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Cargando marcas...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="brand-pagination d-flex flex-column gap-3 mt-3">
                <div class="brand-bottom-toolbar d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <label for="brandPageSizeSelect" class="form-label mb-0 fw-semibold">Registros</label>
                            <select id="brandPageSizeSelect" class="form-select">
                                <option value="10" selected>10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                        <div class="brand-summary text-muted" id="brandSummary">Mostrando 0 de 0 marcas</div>
                    </div>
                    <div class="brand-page-status text-muted" id="brandPageStatus">Pagina 1 de 1</div>
                </div>
                <div class="d-flex justify-content-end align-items-center gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-secondary" id="prevBrandPageButton">Anterior</button>
                    <button type="button" class="btn btn-warning" id="nextBrandPageButton">Siguiente</button>
                    
                </div>
            </div>
        </div>
    </section>
</main>

<?php 
// Modals específicos de Marcas
require __DIR__ . '/CreateBrandModal.php';
require __DIR__ . '/EditBrandModal.php';
require __DIR__ . '/DetailBrandModal.php';
require __DIR__ . '/DeleteBrandModal.php';
require __DIR__ . '/InactiveBrandsModal.php';
// Reutilizamos el de confirmación de salida e info si son genéricos, 
// o creamos versiones específicas si prefieres mantener independencia total.
require __DIR__ . '/ConfirmExitBrandModal.php'; // Corregido: Se mantiene el modal de confirmación de salida
require __DIR__ . '/InfoBrandModal.php';
require __DIR__ . '/HardDeleteModal.php'; // Corregido el nombre del archivo modal
require __DIR__ . '/RestoreInactiveBrandModal.php';

renderFooter([
    'Pages/Assets/JS/Pages/Brand/Brand.js',
]);
?>