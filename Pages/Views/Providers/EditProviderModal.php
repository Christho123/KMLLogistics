<?php
declare(strict_types=1);

// =========================================================
// MODAL: EDIT PROVIDER
// Edicion de proveedores con Bootstrap Modal.
// =========================================================


?>
<div class="modal fade provider-modal" id="editProviderModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1">
                        <i class="fas fa-edit me-2 text-warning"></i>Editar proveedor
                    </h5>
                    <p class="text-muted mb-0">
                        Actualiza los datos del proveedor seleccionado.
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editProviderForm">

                <div class="modal-body">

                    <div id="editProviderFeedback" class="alert d-none"></div>

                    <input type="hidden" id="edit_id_proveedor" name="id_proveedor">

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">ID</label>
                            <input type="text" class="form-control" id="edit_id_proveedor_readonly" disabled>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">RazÃ³n Social</label>
                            <input type="text" class="form-control" id="edit_razon_social" name="razon_social" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Nombre Comercial</label>
                            <input type="text" class="form-control" id="edit_nombre_comercial" name="nombre_comercial">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tipo Documento</label>
                            <select class="form-select" id="edit_id_tipo_documento" name="id_tipo_documento" required>
                                <option value="1">DNI</option>
                                <option value="2">RUC</option>
                                <option value="3">Pasaporte</option>
                                <option value="4">CarnÃ© ExtranjerÃ­a</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">NÃºmero Documento</label>
                            <input type="text" class="form-control" id="edit_numero_documento" name="numero_documento" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">TelÃ©fono</label>
                            <input type="text" class="form-control" id="edit_telefono" name="telefono">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Correo</label>
                            <input type="email" class="form-control" id="edit_correo" name="correo">
                        </div>

                        <div class="col-12">
                            <label class="form-label">DirecciÃ³n</label>
                            <input type="text" class="form-control" id="edit_direccion" name="direccion">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Contacto</label>
                            <input type="text" class="form-control" id="edit_contacto" name="contacto">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <select class="form-select" id="edit_estado" name="estado" required>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>
                        Guardar cambios
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
