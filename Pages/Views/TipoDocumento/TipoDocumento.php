<?php
// =========================================================
// VISTA: TIPO DOCUMENTO
// Pantalla principal del modulo de tipos de documento.
// =========================================================

declare(strict_types=1);

renderHeader('KMLLogistics | Tipos de documento', [
    'Pages/Assets/Css/Pages/TipoDocumento/TipoDocumento.css',
]);
renderMenu('tipodocumento', $data['current_user']);
?>

<main>
    <section id="tipos-documento" class="container py-5">
        <div class="category-wrapper">
            <div class="mb-4">
                <span class="section-badge">Tipo documento</span>
                <h2 class="fw-bold mt-3 mb-2">Listado de tipos de documento</h2>
                <p class="text-muted mb-0">Consulta y administra los tipos de documento registrados en el sistema.</p>
            </div>

            <div class="category-toolbar d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
                <div class="category-search-block">
                    <label for="documentTypeSearchInput" class="form-label fw-semibold mb-2">Buscar por ID o nombre</label>
                    <div class="input-group category-search-group">
                        <span class="input-group-text bg-white">
                            <!-- Icono de Font Awesome para representar la busqueda -->
                            <i class="fas fa-search"></i>
                        </span>
                        <input
                            type="text"
                            id="documentTypeSearchInput"
                            class="form-control"
                            placeholder="Ejemplo: 1 o DNI"
                            autocomplete="off"
                        >
                        <button type="button" class="btn btn-warning" id="filterDocumentTypeSearchButton">
                            <!-- Icono de Font Awesome para la accion de filtrar -->
                            <i class="fas fa-filter me-1"></i>Filtrar
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="clearDocumentTypeSearchButton">
                            <!-- Icono de Font Awesome para limpiar el filtro -->
                            <i class="fas fa-eraser me-1"></i>Limpiar
                        </button>
                    </div>
                </div>

                <div class="category-actions-toolbar">
                    <button type="button" class="btn btn-outline-secondary category-secondary-button" id="openInactiveDocumentTypeModalButton">
                        <!-- Icono de Font Awesome para abrir el listado de inactivos -->
                        <i class="fas fa-archive me-2"></i>Ver inactivos
                    </button>
                    <button type="button" class="btn btn-warning category-create-button" id="openCreateDocumentTypeModalButton">
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
                    <tbody id="documentTypeTableBody">
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Cargando tipos de documento...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="category-pagination d-flex flex-column gap-3 mt-3">
                <div class="category-bottom-toolbar d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <label for="documentTypePageSizeSelect" class="form-label mb-0 fw-semibold">Registros</label>
                            <select id="documentTypePageSizeSelect" class="form-select">
                                <option value="10" selected>10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                        <div class="category-summary text-muted" id="documentTypeSummary">
                            Mostrando 0 de 0 tipos de documento
                        </div>
                    </div>

                    <div class="category-page-status text-muted" id="documentTypePageStatus">
                        Pagina 1 de 1
                    </div>
                </div>

                <div class="d-flex justify-content-end align-items-center gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-secondary" id="prevDocumentTypePageButton">Anterior</button>
                    <button type="button" class="btn btn-warning" id="nextDocumentTypePageButton">Siguiente</button>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require __DIR__ . '/CreateTipoDocumentoModal.php'; ?>
<?php require __DIR__ . '/EditTipoDocumentoModal.php'; ?>
<?php require __DIR__ . '/DetailTipoDocumentoModal.php'; ?>
<?php require __DIR__ . '/DeleteTipoDocumentoModal.php'; ?>
<?php require __DIR__ . '/ConfirmExitTipoDocumentoModal.php'; ?>
<?php require __DIR__ . '/InfoTipoDocumentoModal.php'; ?>
<?php require __DIR__ . '/InactiveTipoDocumentoModal.php'; ?>
<?php require __DIR__ . '/HardDeleteInactiveTipoDocumentoModal.php'; ?>
<?php require __DIR__ . '/RestoreInactiveTipoDocumentoModal.php'; ?>

<?php
renderFooter([
    'Pages/Assets/JS/Pages/TipoDocumento/TipoDocumento.js',
]);
