<?php
declare(strict_types=1);

// =========================================================
// MODAL: RESTORE INACTIVE TIPO DOCUMENTO
// Restauracion de tipos de documento inactivos.
// =========================================================


?>
<div class="modal fade category-modal" id="restoreInactiveTipoDocumentoModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1"><!-- Icono de Font Awesome para restaurar --><i class="fas fa-undo me-2 text-success"></i>Confirmar restauracion</h5>
                    <p class="text-muted mb-0">Verifica la informacion antes de devolver este tipo de documento al listado activo.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="restoreInactiveTipoDocumentoForm">
                <div class="modal-body">
                    <div id="restoreInactiveTipoDocumentoFeedback" class="alert d-none" role="alert"></div>
                    <input type="hidden" id="restore_id_tipo_documento" name="id_tipo_documento">

                    <div class="confirm-exit-copy">
                        <p class="mb-2">Estas a punto de restaurar el tipo de documento <strong id="restoreInactiveTipoDocumentoName">-</strong>.</p>
                        <p class="mb-0">Si confirmas, volvera a mostrarse en el listado principal de tipos de documento activos.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <!-- Icono de Font Awesome para confirmar la restauracion -->
                        <i class="fas fa-undo me-2"></i>Restaurar tipo de documento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

