<?php
declare(strict_types=1);

// =========================================================
// MODAL: DETAIL PROVIDER
// Consulta de detalle de proveedores con Bootstrap Modal.
// =========================================================


?>
<div class="modal fade provider-modal" id="detailProviderModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1">
                        <i class="fas fa-circle-info me-2 text-primary"></i>
                        Detalle de proveedor
                    </h5>
                    <p class="text-muted mb-0">
                        Consulta la informacion completa del proveedor seleccionado.
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="detail-grid">

                    <div class="detail-card">
                        <span class="detail-label">ID</span>
                        <p class="detail-value" id="detailProviderId">-</p>
                    </div>

                    <div class="detail-card">
                        <span class="detail-label">Estado</span>
                        <p class="detail-value" id="detailProviderStatus">-</p>
                    </div>

                    <div class="detail-card">
                        <span class="detail-label">RazÃ³n Social</span>
                        <p class="detail-value" id="detailProviderRazonSocial">-</p>
                    </div>

                    <div class="detail-card">
                        <span class="detail-label">Nombre Comercial</span>
                        <p class="detail-value" id="detailProviderNombreComercial">-</p>
                    </div>

                    <div class="detail-card">
                        <span class="detail-label">Documento</span>
                        <p class="detail-value" id="detailProviderDocumento">-</p>
                    </div>

                    <div class="detail-card">
                        <span class="detail-label">TelÃ©fono</span>
                        <p class="detail-value" id="detailProviderTelefono">-</p>
                    </div>

                    <div class="detail-card">
                        <span class="detail-label">Correo</span>
                        <p class="detail-value" id="detailProviderCorreo">-</p>
                    </div>

                    <div class="detail-card">
                        <span class="detail-label">DirecciÃ³n</span>
                        <p class="detail-value" id="detailProviderDireccion">-</p>
                    </div>

                    <div class="detail-card">
                        <span class="detail-label">Contacto</span>
                        <p class="detail-value" id="detailProviderContacto">-</p>
                    </div>

                    <div class="detail-card">
                        <span class="detail-label">Creado</span>
                        <p class="detail-value" id="detailProviderCreated">-</p>
                    </div>

                    <div class="detail-card">
                        <span class="detail-label">Actualizado</span>
                        <p class="detail-value" id="detailProviderUpdated">-</p>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    <i class="fas fa-check me-2"></i>
                    Cerrar detalle
                </button>
            </div>

        </div>
    </div>
</div>
