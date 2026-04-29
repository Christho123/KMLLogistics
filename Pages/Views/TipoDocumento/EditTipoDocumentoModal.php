<?php
declare(strict_types=1);

// =========================================================
// MODAL: EDIT TIPO DOCUMENTO
// Edicion de tipos de documento con Bootstrap Modal.
// =========================================================


?>
<div class="modal fade category-modal" id="editTipoDocumentoModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1"><!-- Icono de Font Awesome para la accion de editar --><i class="fas fa-edit me-2 text-warning"></i>Editar tipo de documento</h5>
                    <p class="text-muted mb-0">Actualiza los datos del tipo de documento seleccionado.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="editTipoDocumentoForm">
                <div class="modal-body">
                    <div id="editTipoDocumentoFeedback" class="alert d-none" role="alert"></div>
                    <input type="hidden" id="edit_id_tipo_documento" name="id_tipo_documento">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="edit_id_tipo_documento_readonly" class="form-label">ID</label>
                            <input type="text" class="form-control" id="edit_id_tipo_documento_readonly" value="Autogenerado" disabled>
                        </div>
                        <div class="col-md-8">
                            <label for="edit_nombre_tipo_documento" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="edit_nombre_tipo_documento" name="nombre_tipo_documento" maxlength="50" required>
                        </div>
                        <div class="col-12">
                            <label for="edit_tipo_documento_descripcion" class="form-label">Descripcion</label>
                            <textarea class="form-control" id="edit_tipo_documento_descripcion" name="descripcion" rows="4" maxlength="150"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_tipo_documento_estado" class="form-label">Estado</label>
                            <select class="form-select" id="edit_tipo_documento_estado" name="estado" required>
                                <?php foreach (($data['tipo_documento_status_options'] ?? []) as $option): ?>
                                    <option value="<?= (int) $option['value']; ?>">
                                        <?= htmlspecialchars($option['label'], ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">
                        <!-- Icono de Font Awesome para guardar cambios -->
                        <i class="fas fa-save me-2"></i>Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

