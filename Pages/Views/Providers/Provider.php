<?php
declare(strict_types=1);

// =========================================================
// VISTA: PROVIDERS
// Adaptacion directa de Category.php para proveedores.
// =========================================================



// Carga de estilos
renderHeader('KMLLogistics | Proveedores', [
    'Pages/Assets/Css/Pages/Providers/Providers.css',
]);

renderMenu('providers', $data['current_user']);
?>

<main>
    <section id="proveedores" class="container py-5">
        <div class="provider-wrapper">

            <!-- HEADER -->
            <div class="mb-4">
                <span class="section-badge">Proveedor</span>
                <h2 class="fw-bold mt-3 mb-2">Listado de proveedores</h2>
                <p class="text-muted mb-0">
                    Consulta en tiempo real de los proveedores registrados en el sistema.
                </p>
            </div>

            <!-- TOOLBAR -->
            <div class="provider-toolbar d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">

                <!-- BUSCADOR -->
                <div class="provider-search-block">
                    <label for="providerSearchInput" class="form-label fw-semibold mb-2">
                        Buscar por ID o nombre
                    </label>

                    <div class="input-group provider-search-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search"></i>
                        </span>

                        <input
                            type="text"
                            id="providerSearchInput"
                            class="form-control"
                            placeholder="Ejemplo: 1 o Dell"
                            autocomplete="off"
                        >

                        <button type="button" class="btn btn-warning" id="filterSearchButton">
                            <i class="fas fa-filter me-1"></i>Filtrar
                        </button>

                        <button type="button" class="btn btn-outline-secondary" id="clearSearchButton">
                            <i class="fas fa-eraser me-1"></i>Limpiar
                        </button>
                    </div>
                </div>

                <!-- ACCIONES -->
                <div class="provider-actions-toolbar">
                    <button type="button" class="btn btn-outline-secondary" id="openInactiveModalButton">
                        <i class="fas fa-archive me-2"></i>Ver inactivos
                    </button>

                    <button type="button" class="btn btn-warning" id="openCreateModalButton">
                        <i class="fas fa-plus me-2"></i>Crear
                    </button>
                </div>
            </div>

            <!-- TABLA -->
            <div class="provider-table-shell table-size-10 shadow-sm rounded-4">
                <table class="table table-striped table-bordered align-middle mb-0 provider-table">
                    <thead class="table-warning">
                        <tr>
                            <th>ID</th>
                            <th>RazÃ³n Social</th>
                            <th>Documento</th>
                            <th>TelÃ©fono</th>
                            <th>Estado</th>
                            <th>Creado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody id="providerTableBody">
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                Cargando proveedores...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- PAGINACIÃ“N -->
            <div class="provider-pagination d-flex flex-column gap-3 mt-3">

                <div class="provider-bottom-toolbar d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

                    <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <label for="pageSizeSelect" class="form-label mb-0 fw-semibold">
                                Registros
                            </label>

                            <select id="pageSizeSelect" class="form-select">
                                <option value="10" selected>10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                        </div>

                        <div class="provider-summary text-muted" id="providerSummary">
                            Mostrando 0 de 0 proveedores
                        </div>
                    </div>

                    <div class="provider-page-status text-muted" id="providerPageStatus">
                        PÃ¡gina 1 de 1
                    </div>
                </div>

                <div class="d-flex justify-content-end align-items-center gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-secondary" id="prevPageButton">
                        Anterior
                    </button>

                    <button type="button" class="btn btn-warning" id="nextPageButton">
                        Siguiente
                    </button>
                </div>
            </div>

        </div>
    </section>
</main>

<!-- MODALES -->
<?php require __DIR__ . '/CreateProviderModal.php'; ?>
<?php require __DIR__ . '/EditProviderModal.php'; ?>
<?php require __DIR__ . '/DetailProviderModal.php'; ?>
<?php require __DIR__ . '/DeleteProviderModal.php'; ?>
<?php require __DIR__ . '/ConfirmExitProviderModal.php'; ?>
<?php require __DIR__ . '/InfoProviderModal.php'; ?>
<?php require __DIR__ . '/InactiveProviderModal.php'; ?>
<?php require __DIR__ . '/HardDeleteInactiveProviderModal.php'; ?>
<?php require __DIR__ . '/RestoreInactiveProviderModal.php'; ?>

<?php
renderFooter([
    'Pages/Assets/JS/Pages/Providers/Providers.js',
]);
