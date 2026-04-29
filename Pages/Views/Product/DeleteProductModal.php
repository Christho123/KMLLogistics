<?php
declare(strict_types=1);

// =========================================================
// MODAL: DELETE PRODUCT
// Confirmacion para eliminar logicamente un producto.
// =========================================================



?>
<div class="modal fade product-modal" id="deleteProductModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div><h5 class="modal-title mb-1"><i class="fas fa-triangle-exclamation me-2 text-danger"></i>Confirmar eliminacion</h5></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="deleteProductForm">
                <div class="modal-body">
                    <div id="deleteProductFeedback" class="alert d-none" role="alert"></div>
                    <input type="hidden" id="delete_id_producto" name="id_producto">
                    <div class="delete-copy"><p class="mb-0">Estas seguro que quieres eliminar: <strong id="deleteProductName">-</strong>?</p></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash me-2"></i>Confirmar eliminacion</button>
                </div>
            </form>
        </div>
    </div>
</div>


