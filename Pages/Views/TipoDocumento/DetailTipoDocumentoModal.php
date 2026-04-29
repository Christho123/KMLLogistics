<?php
declare(strict_types=1);

// =========================================================
// MODAL: DETAIL TIPO DOCUMENTO
// Consulta de detalle de tipos de documento con Bootstrap Modal.
// =========================================================


?>
<div class="modal fade category-modal" id="detailTipoDocumentoModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1"><!-- Icono de Font Awesome para mostrar informacion --><i class="fas fa-circle-info me-2 text-primary"></i>Detalle de tipo de documento</h5>
                    <p class="text-muted mb-0">Consulta la informacion completa del tipo de documento seleccionado.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="detail-grid">
                    <div class="detail-card">
                        <span class="detail-label">ID</span>
                        <p class="detail-value" id="detailTipoDocumentoId">-</p>
                    </div>
                    <div class="detail-card">
                        <span class="detail-label">Estado</span>
                        <p class="detail-value" id="detailTipoDocumentoStatus">-</p>
                    </div>
                    <div class="detail-card">
                        <span class="detail-label">Nombre</span>
                        <p class="detail-value" id="detailTipoDocumentoName">-</p>
                    </div>
                    <div class="detail-card">
                        <span class="detail-label">Creado</span>
                        <p class="detail-value" id="detailTipoDocumentoCreated">-</p>
                    </div>
                    <div class="detail-card">
                        <span class="detail-label">Descripcion</span>
                        <p class="detail-value" id="detailTipoDocumentoDescription">-</p>
                    </div>
                    <div class="detail-card">
                        <span class="detail-label">Actualizado</span>
                        <p class="detail-value" id="detailTipoDocumentoUpdated">-</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    <!-- Icono de Font Awesome para confirmar y cerrar -->
                    <i class="fas fa-check me-2"></i>Cerrar detalle
                </button>
            </div>
        </div>
    </div>
</div>

