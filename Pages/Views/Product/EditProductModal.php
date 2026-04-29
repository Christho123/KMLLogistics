<?php declare(strict_types=1); ?>
<div class="modal fade product-modal" id="editProductModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1"><i class="fas fa-edit me-2 text-warning"></i>Editar producto</h5>
                    <p class="text-muted mb-0">Actualiza los datos del producto seleccionado.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="editProductForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div id="editProductFeedback" class="alert d-none" role="alert"></div>
                    <input type="hidden" id="edit_id_producto" name="id_producto">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="edit_id_producto_readonly" class="form-label">ID</label>
                            <input type="text" class="form-control" id="edit_id_producto_readonly" disabled>
                        </div>
                        <div class="col-md-9">
                            <label for="edit_producto" class="form-label">Producto</label>
                            <input type="text" class="form-control" id="edit_producto" name="producto" maxlength="150" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_costo" class="form-label">Costo</label>
                            <input type="number" class="form-control product-calc-field" id="edit_costo" name="costo" min="0.01" step="0.01" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_ganancia" class="form-label">Ganancia (%)</label>
                            <input type="number" class="form-control product-calc-field" id="edit_ganancia" name="ganancia" min="0" max="99.9999" step="0.0001" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_precio" class="form-label">Precio</label>
                            <input type="text" class="form-control" id="edit_precio" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="edit_stock" name="stock" min="0" step="1" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_id_categoria" class="form-label">Categoria</label>
                            <select class="form-select" id="edit_id_categoria" name="id_categoria" required>
                                <?php foreach (($data['categories'] ?? []) as $category): ?>
                                    <option value="<?= (int) $category['id_categoria']; ?>"><?= htmlspecialchars($category['nombre_categoria'], ENT_QUOTES, 'UTF-8'); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_id_marca" class="form-label">Marca</label>
                            <select class="form-select" id="edit_id_marca" name="id_marca" required>
                                <?php foreach (($data['brands'] ?? []) as $brand): ?>
                                    <option value="<?= (int) $brand['id_marca']; ?>"><?= htmlspecialchars($brand['nombre_marca'], ENT_QUOTES, 'UTF-8'); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_foto" class="form-label">Cambiar foto</label>
                            <input type="file" class="form-control" id="edit_foto" name="foto" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_estado" class="form-label">Estado</label>
                            <select class="form-select" id="edit_estado" name="estado" required>
                                <?php foreach (($data['product_status_options'] ?? []) as $option): ?>
                                    <option value="<?= (int) $option['value']; ?>"><?= htmlspecialchars($option['label'], ENT_QUOTES, 'UTF-8'); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save me-2"></i>Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
