<?php
// =========================================================
// MODAL: HARD DELETE INACTIVE BRAND
// Eliminacion definitiva de marcas inactivas.
// =========================================================

declare(strict_types=1);
?>
<div class="modal fade brand-modal" id="hardDeleteInactiveBrandModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1"><i class="fas fa-exclamation-triangle me-2 text-danger"></i>Eliminar definitivamente</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="hardDeleteInactiveBrandForm">
                <div class="modal-body">
                    <input type="hidden" id="hard_delete_id_marca" name="id_marca">

                    <div class="delete-copy">
                        <p class="mb-0">Estas seguro que quieres eliminar permanentemente la marca: <strong id="hardDeleteInactiveBrandName">-</strong>?</p>
                        <small class="text-danger">Esta accion no se puede deshacer.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>Confirmar eliminacion
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>