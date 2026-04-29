<?php declare(strict_types=1); ?>
<div class="modal fade product-modal" id="inactiveProductsModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1"><i class="fas fa-archive me-2 text-secondary"></i>Productos inactivos</h5>
                    <p class="text-muted mb-0">Consulta productos desactivados y decide si deseas restaurarlos o eliminarlos definitivamente.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="product-toolbar product-toolbar-modal d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
                    <div class="product-search-block product-search-block-modal">
                        <label for="inactiveProductSearchInput" class="form-label fw-semibold mb-2">Buscar por ID o nombre</label>
                        <div class="input-group product-search-group">
                            <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                            <input type="text" id="inactiveProductSearchInput" class="form-control" placeholder="Ejemplo: 5 o Mouse" autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary" id="clearInactiveSearchButton"><i class="fas fa-eraser me-1"></i>Limpiar</button>
                        </div>
                    </div>
                </div>
                <div class="product-table-shell inactive-product-table-shell shadow-sm rounded-4">
                    <table class="table table-striped table-bordered align-middle mb-0 product-table inactive-product-table">
                        <thead class="table-warning">
                            <tr>
                                <th>ID</th>
                                <th>Foto</th>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Estado</th>
                                <th>Eliminado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="inactiveProductTableBody">
                            <tr><td colspan="8" class="text-center py-4 text-muted">Cargando productos inactivos...</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="product-pagination d-flex flex-column gap-3 mt-3">
                    <div class="product-bottom-toolbar d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                        <p id="inactiveProductSummary" class="text-muted mb-0">Mostrando 0 productos inactivos</p>
                        <div class="product-page-status text-muted" id="inactiveProductStatus">Sin resultados</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button></div>
        </div>
    </div>
</div>
