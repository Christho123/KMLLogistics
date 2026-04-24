<?php
// =========================================================
// MODAL: INACTIVE CATEGORIES
// Listado de categorias inactivas con Bootstrap Modal.
// =========================================================

declare(strict_types=1);
?>
<div class="modal fade category-modal" id="inactiveCategoriesModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1"><!-- Icono de Font Awesome para archivo/inactivos --><i class="fas fa-archive me-2 text-secondary"></i>Categorias inactivas</h5>
                    <p class="text-muted mb-0">Consulta categorias desactivadas y decide si deseas restaurarlas o eliminarlas definitivamente.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="category-toolbar category-toolbar-modal d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
                    <div class="category-search-block category-search-block-modal">
                        <label for="inactiveCategorySearchInput" class="form-label fw-semibold mb-2">Buscar por ID o nombre</label>
                        <div class="input-group category-search-group">
                            <span class="input-group-text bg-white">
                                <!-- Icono de Font Awesome para la busqueda -->
                                <i class="fas fa-search"></i>
                            </span>
                            <input
                                type="text"
                                id="inactiveCategorySearchInput"
                                class="form-control"
                                placeholder="Ejemplo: 8 o Redes"
                                autocomplete="off"
                            >
                            <button type="button" class="btn btn-outline-secondary" id="clearInactiveSearchButton">
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
                        <tbody id="inactiveCategoryTableBody">
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Cargando categorias inactivas...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="category-pagination d-flex flex-column gap-3 mt-3">
                    <div class="category-bottom-toolbar d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                        <div class="category-summary text-muted" id="inactiveCategorySummary">Mostrando 0 categorias inactivas</div>
                        <div class="category-page-status text-muted" id="inactiveCategoryStatus">Sin resultados</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
