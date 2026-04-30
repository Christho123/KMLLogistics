<?php
declare(strict_types=1);

// =========================================================
// MODAL: CREATE PROVIDER
// Registro de nuevos proveedores con Bootstrap Modal.
// =========================================================


?>
<div class="modal fade provider-modal" id="createProviderModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1">
                        <i class="fas fa-truck me-2 text-warning"></i>Crear proveedor
                    </h5>
                    <p class="text-muted mb-0">
                        Completa la informacion para registrar un nuevo proveedor en el sistema.
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="createProviderForm">

                <div class="modal-body">
                    <div id="createProviderFeedback" class="alert d-none"></div>

                    <div class="row g-3">

                        <div class="col-12">
                            <label class="form-label">Razon Social</label>
                            <input type="text" class="form-control" name="razon_social" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Nombre Comercial</label>
                            <input type="text" class="form-control" name="nombre_comercial">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tipo Documento</label>
                            <select class="form-select" name="id_tipo_documento" required>
                                <option value="1">DNI</option>
                                <option value="2">RUC</option>
                                <option value="3">Pasaporte</option>
                                <option value="4">Carne Extranjeria</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Numero Documento</label>
                            <input type="text" class="form-control" name="numero_documento" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Telefono</label>
                            <input type="text" class="form-control" name="telefono">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Correo</label>
                            <input type="email" class="form-control" name="correo">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Direccion</label>
                            <input type="text" class="form-control" name="direccion">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Contacto</label>
                            <input type="text" class="form-control" name="contacto">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <select class="form-select" name="estado" required>
                                <option value="1" selected>Activo</option>
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
                        <i class="fas fa-save me-2"></i>Guardar proveedor
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
