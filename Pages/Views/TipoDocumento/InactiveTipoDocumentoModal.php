<?php
declare(strict_types=1);

// =========================================================
// MODAL: INACTIVE TIPO DOCUMENTO
// Listado de tipos de documento inactivos con Bootstrap Modal.
// =========================================================


?>
<div class="modal fade category-modal" id="inactiveTipoDocumentoModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1"><!-- Icono de Font Awesome para archivo/inactivos --><i class="fas fa-archive me-2 text-secondary"></i>Tipos de documento inactivos</h5>
                    <p class="text-muted mb-0">Consulta tipos de documento desactivados y decide si deseas restaurarlos o eliminarlos definitivamente.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="category-toolbar category-toolbar-modal d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
                    <div class="category-search-block category-search-block-modal">
                        <label for="inactiveTipoDocumentoSearchInput" class="form-label fw-semibold mb-2">Buscar por ID o nombre</label>
                        <div class="input-group category-search-group">
                            <span class="input-group-text bg-white">
                                <!-- Icono de Font Awesome para la busqueda -->
                                <i class="fas fa-search"></i>
                            </span>
                            <input
                                type="text"
                                id="inactiveTipoDocumentoSearchInput"
                                class="form-control"
                                placeholder="Ejemplo: 2 o RUC"
                                autocomplete="off"
                            >
                            <button type="button" class="btn btn-outline-secondary" id="clearInactiveTipoDocumentoSearchButton">
                                <!-- Icono de Font Awesome para limpiar el filtro -->
                                <i class="fas fa-eraser me-1"></i>Limpiar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="category-table-shell inactive-category-table-shell shadow-sm rounded-4">
                    <table class="table table-striped table-bordered align-middle mb-0 category-table inactive-category-table">
                        <thead class="table-warning">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripcion</th>
                                <th>Estado</th>
                                <th>Eliminado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="inactiveTipoDocumentoTableBody">
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Cargando tipos de documento inactivos...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="category-pagination d-flex flex-column gap-3 mt-3">
                    <div class="category-bottom-toolbar d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                        <div class="category-summary text-muted" id="inactiveTipoDocumentoSummary">Mostrando 0 tipos de documento inactivos</div>
                        <div class="category-page-status text-muted" id="inactiveTipoDocumentoStatus">Sin resultados</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

