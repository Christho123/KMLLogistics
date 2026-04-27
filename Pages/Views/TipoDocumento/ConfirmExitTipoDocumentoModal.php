<?php
// =========================================================
// MODAL: CONFIRM EXIT TIPO DOCUMENTO
// Confirmacion antes de cerrar formularios con cambios.
// =========================================================

declare(strict_types=1);
?>
<div class="modal fade category-modal" id="confirmExitTipoDocumentoModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1"><!-- Icono de Font Awesome para dudas o confirmacion --><i class="fas fa-circle-question me-2 text-primary"></i><span id="confirmExitTipoDocumentoTitle">Confirmar salida</span></h5>
                    <p class="text-muted mb-0">Antes de cerrar, elige como deseas continuar con la informacion ingresada.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="confirm-exit-copy">
                    <p class="mb-0" id="confirmExitTipoDocumentoCopy">Tienes cambios pendientes en este formulario.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Seguir editando</button>
                <button type="button" class="btn btn-outline-danger" id="confirmExitTipoDocumentoDiscardButton">Salir sin guardar</button>
                <button type="button" class="btn btn-primary" id="confirmExitTipoDocumentoSaveButton">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>
