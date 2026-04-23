<?php

declare(strict_types=1);
?>
<div class="modal fade category-modal" id="createCategoryModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1"><i class="fas fa-folder-plus me-2 text-warning"></i>Crear categoria</h5>
                    <p class="text-muted mb-0">Completa la informacion para registrar una nueva categoria en el sistema.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="createCategoryForm">
                <div class="modal-body">
                    <div id="createCategoryFeedback" class="alert d-none" role="alert"></div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="create_nombre_categoria" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="create_nombre_categoria" name="nombre_categoria" maxlength="100" required>
                        </div>
                        <div class="col-12">
                            <label for="create_descripcion" class="form-label">Descripcion</label>
                            <textarea class="form-control" id="create_descripcion" name="descripcion" rows="4" maxlength="255" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="create_estado" class="form-label">Estado</label>
                            <select class="form-select" id="create_estado" name="estado" required>
                                <?php foreach (($data['category_status_options'] ?? []) as $option): ?>
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
                        <i class="fas fa-save me-2"></i>Guardar categoria
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
