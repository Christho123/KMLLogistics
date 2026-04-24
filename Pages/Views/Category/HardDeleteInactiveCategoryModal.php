<?php
// =========================================================
// MODAL: HARD DELETE INACTIVE CATEGORY
// Eliminacion definitiva de categorias inactivas.
// =========================================================

declare(strict_types=1);
?>
<div class="modal fade category-modal" id="hardDeleteInactiveCategoryModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1"><!-- Icono de Font Awesome para advertencia critica --><i class="fas fa-exclamation-triangle me-2 text-danger"></i>Eliminar definitivamente</h5>
                    <p class="text-muted mb-0">Esta accion eliminara la categoria inactiva y sus productos relacionados de toda la base de datos.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="hardDeleteInactiveCategoryForm">
                <div class="modal-body">
                    <div id="hardDeleteInactiveFeedback" class="alert d-none" role="alert"></div>
                    <input type="hidden" id="hard_delete_id_categoria" name="id_categoria">

                    <div class="delete-copy">
                        <p class="mb-2">Vas a eliminar definitivamente la categoria <strong id="hardDeleteInactiveName">-</strong>.</p>
                        <p class="mb-0">Esta operacion tambien borrara los productos vinculados a esa categoria y no se podra deshacer.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <!-- Icono de Font Awesome para confirmar la eliminacion definitiva -->
                        <i class="fas fa-trash me-2"></i>Eliminar definitivo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
