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
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="hardDeleteInactiveCategoryForm">
                <div class="modal-body">
                    <input type="hidden" id="hard_delete_id_categoria" name="id_categoria">

                    <div class="delete-copy">
                        <p class="mb-0">Estas seguro que quieres eliminar: <strong id="hardDeleteInactiveName">-</strong>?</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <!-- Icono de Font Awesome para confirmar la eliminacion definitiva -->
                        <i class="fas fa-trash me-2"></i>Confirmar eliminacion
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
