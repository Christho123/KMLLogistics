<?php
declare(strict_types=1);

// =========================================================
// MODAL: DELETE BRAND
// Confirmacion para eliminar logicamente una marca.
// =========================================================



?>
<div class="modal fade" id="deleteBrandModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-triangle-exclamation me-2 text-danger"></i>Confirmar eliminacion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="deleteBrandForm">
                <div class="modal-body">
                    <input type="hidden" id="delete_id_marca" name="id_marca">
                    <p class="mb-0">Estas seguro que quieres eliminar la marca: <strong id="deleteBrandName">-</strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash me-2"></i>Confirmar eliminacion</button>
                </div>
            </form>
        </div>
    </div>
</div>

