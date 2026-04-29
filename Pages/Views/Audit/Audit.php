<?php
declare(strict_types=1);

// =========================================================
// VISTA: AUDIT
// Listado de auditorias solo para administradores.
// =========================================================



renderHeader('KMLLogistics | Auditoria', [
    'Pages/Assets/Css/Pages/Audit/Audit.css',
]);
renderMenu('audit', $data['current_user']);
?>

<main>
    <section class="container py-5">
        <div class="audit-wrapper">
            <div class="mb-4">
                <span class="section-badge">Auditoria</span>
                <h2 class="fw-bold mt-3 mb-2">Listado de acciones</h2>
                <p class="text-muted mb-0">Consulta los movimientos registrados por los usuarios del sistema.</p>
            </div>

            <div class="audit-toolbar d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
                <div class="audit-search-block">
                    <label for="auditSearchInput" class="form-label fw-semibold mb-2">Buscar</label>
                    <div class="input-group audit-search-group">
                        <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                        <input type="text" id="auditSearchInput" class="form-control" placeholder="Ejemplo: Admin o Perfil" autocomplete="off">
                        <button type="button" class="btn btn-warning" id="filterSearchButton"><i class="fas fa-filter me-1"></i>Filtrar</button>
                        <button type="button" class="btn btn-outline-secondary" id="clearSearchButton"><i class="fas fa-eraser me-1"></i>Limpiar</button>
                    </div>
                </div>
            </div>

            <div class="audit-table-shell table-size-10 shadow-sm rounded-4">
                <table class="table table-striped table-bordered align-middle mb-0 audit-table">
                    <thead class="table-warning">
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Modulo</th>
                            <th>Accion</th>
                            <th>Estado</th>
                            <th>Creado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="auditTableBody">
                        <tr><td colspan="7" class="text-center py-4 text-muted">Cargando auditorias...</td></tr>
                    </tbody>
                </table>
            </div>

            <div class="audit-pagination d-flex flex-column gap-3 mt-3">
                <div class="audit-bottom-toolbar d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <label for="pageSizeSelect" class="form-label mb-0 fw-semibold">Registros</label>
                            <select id="pageSizeSelect" class="form-select">
                                <option value="10" selected>10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                        <div class="audit-summary text-muted" id="auditSummary">Mostrando 0 de 0 auditorias</div>
                    </div>
                    <div class="audit-page-status text-muted" id="auditPageStatus">Pagina 1 de 1</div>
                </div>
                <div class="d-flex justify-content-end align-items-center gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-secondary" id="prevPageButton">Anterior</button>
                    <button type="button" class="btn btn-warning" id="nextPageButton">Siguiente</button>
                </div>
            </div>
        </div>
    </section>
</main>

<div class="modal fade audit-modal" id="detailAuditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold"><i class="fas fa-eye text-primary me-2"></i>Detalle de auditoria</h5>
                    <p class="text-muted mb-0 small">Informacion completa del registro seleccionado.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="auditDetailGrid" class="detail-grid"></div>
            </div>
        </div>
    </div>
</div>

<?php
renderFooter([
    'Pages/Assets/JS/Pages/Audit/Audit.js',
]);

