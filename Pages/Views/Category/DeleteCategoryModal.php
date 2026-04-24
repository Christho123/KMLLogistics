<?php
// =========================================================
// MODAL: DELETE CATEGORY
// Eliminacion logica de categorias con Bootstrap Modal.
// =========================================================

declare(strict_types=1);
?>
<div class="modal fade category-modal" id="deleteCategoryModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1"><!-- Icono de Font Awesome para advertencia de eliminacion --><i class="fas fa-triangle-exclamation me-2 text-danger"></i>Confirmar eliminacion</h5>
                    <p class="text-muted mb-0">Esta accion realizara una eliminacion logica y retirara la categoria del listado activo.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="deleteCategoryForm">
                <div class="modal-body">
                    <div id="deleteCategoryFeedback" class="alert d-none" role="alert"></div>
                    <input type="hidden" id="delete_id_categoria" name="id_categoria">

                    <div class="delete-copy">
                        <p class="mb-2">Estas a punto de eliminar la categoria <strong id="deleteCategoryName">-</strong>.</p>
                        <p class="mb-0">Verifica que ya no necesites esta informacion activa. Descripcion actual: <strong id="deleteCategoryDescription">-</strong></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <!-- Icono de Font Awesome para confirmar la eliminacion -->
                        <i class="fas fa-trash me-2"></i>Confirmar eliminacion
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
