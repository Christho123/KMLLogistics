<?php
declare(strict_types=1);

// =========================================================
// MODAL: INFO TIPO DOCUMENTO
// Avisos informativos para validaciones y mensajes breves.
// =========================================================


?>
<div class="modal fade category-modal" id="infoTipoDocumentoModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1"><!-- Icono de Font Awesome para mensajes informativos --><i class="fas fa-circle-info me-2 text-warning"></i><span id="infoTipoDocumentoModalTitle">Aviso</span></h5>
                    <p class="text-muted mb-0">Revisa la informacion antes de continuar.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0" id="infoTipoDocumentoModalMessage">Mensaje informativo.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Entendido</button>
            </div>
        </div>
    </div>
</div>

