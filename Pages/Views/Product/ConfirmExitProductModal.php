<?php
declare(strict_types=1);

// =========================================================
// MODAL: CONFIRM EXIT PRODUCT
// Confirmacion para salir de formularios de producto con cambios pendientes.
// =========================================================



?>
<div class="modal fade product-modal" id="confirmExitProductModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1"><i class="fas fa-circle-question me-2 text-primary"></i><span id="confirmExitProductTitle">Confirmar salida</span></h5>
                    <p class="text-muted mb-0">Antes de cerrar, elige como deseas continuar.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="confirm-exit-copy"><p class="mb-0" id="confirmExitProductCopy">Tienes cambios pendientes.</p></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Seguir editando</button>
                <button type="button" class="btn btn-outline-danger" id="confirmExitDiscardButton">Salir sin guardar</button>
                <button type="button" class="btn btn-primary" id="confirmExitSaveButton">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>


