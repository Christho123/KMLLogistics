<?php
declare(strict_types=1);

// =========================================================
// MODAL: DETAIL PRODUCT
// Detalle visual del producto seleccionado.
// =========================================================



?>
<div class="modal fade product-modal" id="detailProductModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1"><i class="fas fa-circle-info me-2 text-primary"></i>Detalle de producto</h5>
                    <p class="text-muted mb-0">Consulta la informacion completa del producto seleccionado.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="detail-grid">
                    <div class="detail-card product-photo-detail-card">
                        <span class="detail-label">Foto</span>
                        <div id="detailProductPhoto">-</div>
                    </div>
                    <div class="detail-card"><span class="detail-label">ID</span><p class="detail-value" id="detailProductId">-</p></div>
                    <div class="detail-card"><span class="detail-label">Producto</span><p class="detail-value" id="detailProductName">-</p></div>
                    <div class="detail-card"><span class="detail-label">Estado</span><p class="detail-value" id="detailProductStatus">-</p></div>
                    <div class="detail-card"><span class="detail-label">Costo</span><p class="detail-value" id="detailProductCost">-</p></div>
                    <div class="detail-card"><span class="detail-label">Ganancia</span><p class="detail-value" id="detailProductProfit">-</p></div>
                    <div class="detail-card"><span class="detail-label">Precio</span><p class="detail-value" id="detailProductPrice">-</p></div>
                    <div class="detail-card"><span class="detail-label">Stock</span><p class="detail-value" id="detailProductStock">-</p></div>
                    <div class="detail-card"><span class="detail-label">Categoria</span><p class="detail-value" id="detailProductCategory">-</p></div>
                    <div class="detail-card"><span class="detail-label">Marca</span><p class="detail-value" id="detailProductBrand">-</p></div>
                    <div class="detail-card"><span class="detail-label">Creado</span><p class="detail-value" id="detailProductCreated">-</p></div>
                    <div class="detail-card"><span class="detail-label">Actualizado</span><p class="detail-value" id="detailProductUpdated">-</p></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="fas fa-check me-2"></i>Cerrar detalle</button>
            </div>
        </div>
    </div>
</div>


