<?php
// =========================================================
// MODAL: EDIT BRAND
// Formulario Bootstrap para actualizar una marca.
// =========================================================
?>
<div class="modal fade brand-modal" id="editBrandModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1"><i class="fas fa-edit me-2 text-warning"></i>Editar marca</h5>
                    <p class="text-muted mb-0">Actualiza los datos y el proveedor de la marca.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="editBrandForm">
                <div class="modal-body">
                    <div id="editBrandFeedback" class="alert d-none" role="alert"></div>
                    <input type="hidden" id="edit_id_marca" name="id_marca">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="edit_id_marca_readonly" class="form-label">ID Marca</label>
                            <input type="text" class="form-control" id="edit_id_marca_readonly" disabled>
                        </div>
                        <div class="col-md-8">
                            <label for="edit_nombre_marca" class="form-label">Nombre de la Marca</label>
                            <input type="text" class="form-control" id="edit_nombre_marca" name="nombre_marca" maxlength="100" required>
                        </div>
                        <div class="col-md-12">
                            <label for="edit_id_proveedor" class="form-label">Proveedor Asociado</label>
                            <select class="form-select" id="edit_id_proveedor" name="id_proveedor" required>
                                <option value="" disabled>Seleccione un proveedor...</option>

                                <?php foreach (($data['suppliers'] ?? []) as $sup): ?>
                                    <option value="<?= (int) $sup['id_proveedor']; ?>">
                                        <?= htmlspecialchars($sup['razon_social'], ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="edit_estado_marca" class="form-label">Estado</label>
                            <select class="form-select" id="edit_estado" name="estado" required>
                                <?php foreach (($data['brand_status_options'] ?? []) as $option): ?>
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
                        <i class="fas fa-save me-2"></i>Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


