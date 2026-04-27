<?php
// =========================================================
// MODAL: DELETE TIPO DOCUMENTO
// Eliminacion logica de tipos de documento con Bootstrap Modal.
// =========================================================

declare(strict_types=1);
?>
<div class="modal fade category-modal" id="deleteTipoDocumentoModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1"><!-- Icono de Font Awesome para advertencia de eliminacion --><i class="fas fa-triangle-exclamation me-2 text-danger"></i>Confirmar eliminacion</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="deleteTipoDocumentoForm">
                <div class="modal-body">
                    <div id="deleteTipoDocumentoFeedback" class="alert d-none" role="alert"></div>
                    <input type="hidden" id="delete_id_tipo_documento" name="id_tipo_documento">

                    <div class="delete-copy">
                        <p class="mb-0">Estas seguro que quieres eliminar: <strong id="deleteTipoDocumentoName">-</strong>?</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <!-- Icono de Font Awesome para confirmar la eliminacion -->
                        <i class="fas fa-trash me-2"></i>Confirmar eliminacion
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
