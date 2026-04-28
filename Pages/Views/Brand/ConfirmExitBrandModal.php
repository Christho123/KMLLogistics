<?php
// =========================================================
// MODAL: CONFIRM EXIT BRAND
// Confirmacion antes de cerrar formularios con cambios.
// =========================================================

declare(strict_types=1);
?>
<div class="modal fade brand-modal" id="confirmExitBrandModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1"><i class="fas fa-circle-question me-2 text-primary"></i><span id="confirmExitBrandTitle">Confirmar salida</span></h5>
                    <p class="text-muted mb-0">Elige como deseas continuar con la informacion de la marca.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="confirm-exit-copy">
                    <p class="mb-0" id="confirmExitBrandCopy">Tienes cambios pendientes en este formulario de marcas.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Seguir editando</button>
                <button type="button" class="btn btn-outline-danger" id="confirmBrandExitDiscardButton">Salir sin guardar</button>
                <button type="button" class="btn btn-primary" id="confirmBrandExitSaveButton">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>