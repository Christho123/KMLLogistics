<?php
// =========================================================
// MODAL: DELETE PROVIDER
// Eliminacion logica de proveedores con Bootstrap Modal.
// =========================================================

declare(strict_types=1);
?>
<div class="modal fade provider-modal" id="deleteProviderModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1">
                        <i class="fas fa-triangle-exclamation me-2 text-danger"></i>
                        Confirmar eliminacion
                    </h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="deleteProviderForm">

                <div class="modal-body">

                    <input type="hidden" id="delete_id" name="id_proveedor">

                    <div class="delete-copy text-center">
                     <p class="mb-0">
                         Estas seguro que quieres eliminar:
                       <strong id="deleteProviderName">-</strong>?
                     </p>
                  </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>
                        Confirmar eliminacion
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>