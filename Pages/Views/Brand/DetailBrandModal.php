<?php
// =========================================================
// MODAL: DETAIL BRAND
// Detalle visual de la marca seleccionada.
// =========================================================
?>
<div class="modal fade brand-modal" id="detailBrandModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Detalle de Marca</h5>
                
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body pt-0">
                

                <div class="detail-grid">
                    <div class="detail-card">
                        <span class="detail-label"><i class="fas fa-hashtag me-2"></i>ID Marca</span>
                        <p class="detail-value" id="detailBrandId">-</p>
                    </div>
                    <div class="detail-card">
                        <span class="detail-label"><i class="fas fa-tag me-2"></i>Nombre</span>
                        <p class="detail-value" id="detailBrandName">-</p>
                    </div>
                    <div class="detail-card">
                        <span class="detail-label"><i class="fas fa-truck-moving me-2"></i>Proveedor</span>
                        <p class="detail-value" id="detailSupplierName">-</p>
                    </div>
                    <div class="detail-card">
                        <span class="detail-label"><i class="fas fa-info-circle me-2"></i>Estado</span>
                        <div id="detailBrandStatus">-</div>
                    </div>
                    <div class="detail-card">
                        <span class="detail-label"><i class="fas fa-calendar-plus me-2"></i>Fecha Registro</span>
                        <p class="detail-value" id="detailBrandCreated">-</p>
                    </div>
                    <div class="detail-card">
                        <span class="detail-label"><i class="fas fa-calendar-check me-2"></i>Ultima Modificacion</span>
                        <p class="detail-value" id="detailBrandUpdated">-</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Cerrar detalle</button>
            </div>
        </div>
    </div>
</div>


