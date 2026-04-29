<?php declare(strict_types=1); ?>
<div class="modal fade product-modal" id="restoreInactiveProductModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1"><i class="fas fa-undo me-2 text-success"></i>Confirmar restauracion</h5>
                    <p class="text-muted mb-0">Verifica la informacion antes de devolver este producto al listado activo.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="restoreInactiveProductForm">
                <div class="modal-body">
                    <div id="restoreInactiveFeedback" class="alert d-none" role="alert"></div>
                    <input type="hidden" id="restore_id_producto" name="id_producto">
                    <div class="confirm-exit-copy">
                        <p class="mb-2">Estas a punto de restaurar el producto <strong id="restoreInactiveName">-</strong>.</p>
                        <p class="mb-0">Si confirmas, volvera a mostrarse en el listado principal de productos activos.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-undo me-2"></i>Restaurar producto</button>
                </div>
            </form>
        </div>
    </div>
</div>
