<?php
declare(strict_types=1);

// =========================================================
// MODAL: INACTIVE PROVIDERS
// Listado de proveedores inactivos con Bootstrap Modal.
// =========================================================


?>
<div class="modal fade provider-modal" id="inactiveProviderModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1">
                        <i class="fas fa-archive me-2 text-secondary"></i>
                        Proveedores inactivos
                    </h5>
                    <p class="text-muted mb-0">
                        Consulta proveedores desactivados y decide si deseas restaurarlos o eliminarlos definitivamente.
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="provider-toolbar provider-toolbar-modal d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">

                    <div class="provider-search-block provider-search-block-modal">
                        <label class="form-label fw-semibold mb-2">Buscar por ID o razon social</label>

                        <div class="input-group provider-search-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-search"></i>
                            </span>

                            <input
                                type="text"
                                id="inactiveProviderSearchInput"
                                class="form-control"
                                placeholder="Ejemplo: 5 o Samsung"
                                autocomplete="off"
                            >

                            <button type="button" class="btn btn-outline-secondary" id="clearInactiveProviderSearchButton">
                                <i class="fas fa-eraser me-1"></i>Limpiar
                            </button>
                        </div>
                    </div>

                </div>

                <div class="provider-table-shell inactive-provider-table-shell shadow-sm rounded-4">
                    <table class="table table-striped table-bordered align-middle mb-0 provider-table inactive-provider-table">

                        <thead class="table-warning">
                            <tr>
                                <th>ID</th>
                                <th>Razon Social</th>
                                <th>Documento</th>
                                <th>Estado</th>
                                <th>Eliminado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody id="inactiveProviderTableBody">
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Cargando proveedores inactivos...
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>

                <div class="provider-pagination d-flex flex-column gap-3 mt-3">
                    <div class="provider-bottom-toolbar d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
      <p id="inactiveProviderCount" class="text-muted">
    Mostrando 0 proveedores inactivos
</p>
                        <div class="provider-page-status text-muted" id="inactiveProviderStatus">
                            Sin resultados
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>
