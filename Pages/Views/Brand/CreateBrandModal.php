<?php
declare(strict_types=1);

// =========================================================
// MODAL: CREATE BRAND
// Formulario Bootstrap para registrar una marca.
// =========================================================



?>
<div class="modal fade" id="createBrandModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1"><i class="fas fa-tag me-2 text-warning"></i>Crear Marca</h5>
                    <p class="text-muted mb-0">Registra una nueva marca para el catÃ¡logo.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createBrandForm">
                <div class="modal-body">
                    <div id="createBrandFeedback" class="alert d-none" role="alert"></div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="create_nombre_marca" class="form-label">Nombre de la Marca</label>
                            <input type="text" class="form-control" id="create_nombre_marca" name="nombre_marca" maxlength="100" required>
                        </div>
                        <div class="col-md-12">
                            <label for="create_id_proveedor" class="form-label">Proveedor Asociado</label>
                            <select class="form-select" id="create_id_proveedor" name="id_proveedor" required>
                                <option value="" selected disabled>Seleccione un proveedor...</option>
                                <?php foreach (($data['suppliers'] ?? []) as $sup): ?>
                                    <option value="<?= (int) $sup['id_proveedor']; ?>">
                                        <?= htmlspecialchars($sup['razon_social'] ?? $sup['nombre_proveedor'] ?? 'Proveedor', ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="create_estado_marca" class="form-label">Estado</label>
                            <select class="form-select" id="create_estado" name="estado" required>
                                <?php foreach (($data['brand_status_options'] ?? []) as $option): ?>
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
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save me-2"></i>Guardar Marca</button>
                </div>
            </form>
        </div>
    </div>
</div>

