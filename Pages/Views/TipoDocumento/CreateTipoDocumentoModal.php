<?php
// =========================================================
// MODAL: CREATE TIPO DOCUMENTO
// Registro de nuevos tipos de documento con Bootstrap Modal.
// =========================================================

declare(strict_types=1);
?>
<div class="modal fade category-modal" id="createTipoDocumentoModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1"><!-- Icono de Font Awesome para la accion de crear --><i class="fas fa-folder-plus me-2 text-warning"></i>Crear tipo de documento</h5>
                    <p class="text-muted mb-0">Completa la informacion para registrar un nuevo tipo de documento en el sistema.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="createTipoDocumentoForm">
                <div class="modal-body">
                    <div id="createTipoDocumentoFeedback" class="alert d-none" role="alert"></div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="create_nombre_tipo_documento" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="create_nombre_tipo_documento" name="nombre_tipo_documento" maxlength="50" required>
                        </div>
                        <div class="col-12">
                            <label for="create_tipo_documento_descripcion" class="form-label">Descripcion</label>
                            <textarea class="form-control" id="create_tipo_documento_descripcion" name="descripcion" rows="4" maxlength="150"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="create_tipo_documento_estado" class="form-label">Estado</label>
                            <select class="form-select" id="create_tipo_documento_estado" name="estado" required>
                                <?php foreach (($data['tipo_documento_status_options'] ?? []) as $option): ?>
                                    <option value="<?= (int) $option['value']; ?>" <?= (int) $option['value'] === 1 ? 'selected' : ''; ?>>
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
                        <!-- Icono de Font Awesome para guardar el tipo de documento -->
                        <i class="fas fa-save me-2"></i>Guardar tipo de documento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
