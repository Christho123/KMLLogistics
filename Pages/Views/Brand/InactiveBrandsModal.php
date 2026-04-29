<?php
// =========================================================
// MODAL: INACTIVE BRANDS
// Listado de marcas inactivas con Bootstrap Modal.
// =========================================================

declare(strict_types=1);
?>
<div class="modal fade brand-modal" id="inactiveBrandsModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1"><i class="fas fa-archive me-2 text-secondary"></i>Marcas inactivas</h5>
                    <p class="text-muted mb-0">Gestione las marcas desactivadas del sistema.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="brand-toolbar brand-toolbar-modal d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
                    <div class="brand-search-block brand-search-block-modal">
                        <label for="inactiveBrandSearchInput" class="form-label fw-semibold mb-2">Buscar por ID o nombre</label>
                        <div class="input-group brand-search-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-search"></i>
                            </span>
                            <input
                                type="text"
                                id="inactiveBrandSearchInput"
                                class="form-control"
                                placeholder="Ejemplo: Apple"
                                autocomplete="off"
                            >
                            <button type="button" class="btn btn-outline-secondary" id="clearInactiveBrandSearchButton">
                                <i class="fas fa-eraser me-1"></i>Limpiar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="brand-table-shell inactive-brand-table-shell shadow-sm rounded-4">
                    <table class="table table-striped table-bordered align-middle mb-0 brand-table inactive-brand-table">
                        <thead class="table-warning">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Proveedor</th>
                                <th>Estado</th>
                                <th>Eliminado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="inactiveBrandTableBody">
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Cargando marcas inactivas...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="brand-pagination d-flex flex-column gap-3 mt-3">
                    <div class="brand-bottom-toolbar d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                        <div class="brand-summary text-muted" id="inactiveBrandSummary">Mostrando 0 marcas inactivas</div>
                        <div class="brand-page-status text-muted" id="inactiveBrandStatus">Sin resultados</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>