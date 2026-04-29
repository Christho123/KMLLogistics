<?php
declare(strict_types=1);

// =========================================================
// MODAL: CREATE PRODUCT
// Formulario Bootstrap para registrar productos con imagen.
// =========================================================



?>
<div class="modal fade product-modal" id="createProductModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1"><i class="fas fa-box-open me-2 text-warning"></i>Crear producto</h5>
                    <p class="text-muted mb-0">Completa la informacion para registrar un nuevo producto.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="createProductForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div id="createProductFeedback" class="alert d-none" role="alert"></div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="create_producto" class="form-label">Producto</label>
                            <input type="text" class="form-control" id="create_producto" name="producto" maxlength="150" required>
                        </div>
                        <div class="col-md-4">
                            <label for="create_costo" class="form-label">Costo</label>
                            <input type="number" class="form-control product-calc-field" id="create_costo" name="costo" min="0.01" step="0.01" required>
                        </div>
                        <div class="col-md-4">
                            <label for="create_ganancia" class="form-label">Ganancia (%)</label>
                            <input type="text" class="form-control product-calc-field" id="create_ganancia" name="ganancia" inputmode="decimal" required>
                        </div>
                        <div class="col-md-4">
                            <label for="create_precio" class="form-label">Precio</label>
                            <input type="text" class="form-control" id="create_precio" readonly value="0.00">
                        </div>
                        <div class="col-md-4">
                            <label for="create_stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="create_stock" name="stock" min="0" step="1" required>
                        </div>
                        <div class="col-md-4">
                            <label for="create_id_categoria" class="form-label">Categoria</label>
                            <select class="form-select" id="create_id_categoria" name="id_categoria" required>
                                <option value="">Selecciona</option>
                                <?php foreach (($data['categories'] ?? []) as $category): ?>
                                    <option value="<?= (int) $category['id_categoria']; ?>"><?= htmlspecialchars($category['nombre_categoria'], ENT_QUOTES, 'UTF-8'); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="create_id_marca" class="form-label">Marca</label>
                            <select class="form-select" id="create_id_marca" name="id_marca" required>
                                <option value="">Selecciona</option>
                                <?php foreach (($data['brands'] ?? []) as $brand): ?>
                                    <option value="<?= (int) $brand['id_marca']; ?>"><?= htmlspecialchars($brand['nombre_marca'], ENT_QUOTES, 'UTF-8'); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="create_foto" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="create_foto" name="foto" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label for="create_estado" class="form-label">Estado</label>
                            <select class="form-select" id="create_estado" name="estado" required>
                                <?php foreach (($data['product_status_options'] ?? []) as $option): ?>
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
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save me-2"></i>Guardar producto</button>
                </div>
            </form>
        </div>
    </div>
</div>


