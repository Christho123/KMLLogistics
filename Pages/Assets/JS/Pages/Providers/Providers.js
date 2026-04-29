$(function () {

    var $tbody = $('#providerTableBody');
    var $search = $('#providerSearchInput');
    var $btnFilter = $('#filterSearchButton');
    var $btnClear = $('#clearSearchButton');
    var $btnCreate = $('#openCreateModalButton');
    var $btnInactive = $('#openInactiveModalButton');

    var $createForm = $('#createProviderForm');
    var $editForm = $('#editProviderForm');
    var $deleteForm = $('#deleteProviderForm');

    var listUrl = 'Api/Providers/List.php';
    var getUrl = 'Api/Providers/Get.php';
    var createUrl = 'Api/Providers/Create.php';
    var updateUrl = 'Api/Providers/Update.php';
    var deleteUrl = 'Api/Providers/Delete.php';
    var listInactiveUrl = 'Api/Providers/ListInactive.php';
    var restoreUrl = 'Api/Providers/Restore.php';
    var hardDeleteUrl = 'Api/Providers/HardDelete.php';

    var createModal = new bootstrap.Modal(document.getElementById('createProviderModal'));
    var editModal = new bootstrap.Modal(document.getElementById('editProviderModal'));
    var detailModal = new bootstrap.Modal(document.getElementById('detailProviderModal'));
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteProviderModal'));
    var inactiveModal = new bootstrap.Modal(document.getElementById('inactiveProviderModal'));
    var infoModal = new bootstrap.Modal(document.getElementById('infoProviderModal'));

    function showInfoModal(title, message) {
        $('#infoProviderModalTitle').text(title || 'Aviso');
        $('#infoProviderModalMessage').text(message || 'Ocurrió un evento.');
        infoModal.show();
    }

    function loadProviders(search = '') {
        $.get(listUrl, {
            search: search,
            page: 1,
            pageSize: 10
        }, function (res) {

            if (!res.success) {
                $tbody.html('<tr><td colspan="7">Error</td></tr>');
                return;
            }

            let html = '';

            if (res.providers.length === 0) {
                html = `<tr><td colspan="7" class="text-center">Sin resultados</td></tr>`;
            } else {
                res.providers.forEach(p => {
                    html += `
                    <tr>
                        <td>${p.id_proveedor}</td>
                        <td>${p.razon_social}</td>
                        <td>${p.numero_documento}</td>
                        <td>${p.telefono}</td>
                        <td>
                        <span class="badge ${p.estado == 1 ? 'text-bg-success' : 'text-bg-secondary'}">
                        ${p.estado == 1 ? 'Activo' : 'Inactivo'}
                        </span>
                        </td>
                        <td>${p.created_at}</td>
                        <td>
    <div class="provider-actions">
    <button type="button" class="btn btn-outline-primary provider-action-button view" title="Ver detalle" data-id="${p.id_proveedor}">
        <i class="fas fa-eye"></i>
    </button>

    <button type="button" class="btn btn-outline-warning provider-action-button edit" title="Editar" data-id="${p.id_proveedor}">
        <i class="fas fa-edit"></i>
    </button>

<button type="button" class="btn btn-outline-danger provider-action-button delete"
    title="Eliminar"
    data-id="${p.id_proveedor}"
    data-name="${p.razon_social}">
    <i class="fas fa-trash"></i>
</button>
</div>
                        </td>
                    </tr>`;
                });
            }

            $tbody.html(html);

        }, 'json');
    }

    function reloadInactiveProviders() {
        $.get(listInactiveUrl, function (res) {

            if (!res.success) {
                showInfoModal('Error', res.message);
                return;
            }

            let html = '';

            if (res.providers.length === 0) {
                html = `<tr><td colspan="6" class="text-center">Sin proveedores inactivos</td></tr>`;
            } else {
                res.providers.forEach(p => {
                    html += `
                    <tr>
                        <td>${p.id_proveedor}</td>
                        <td>${p.razon_social}</td>
                        <td>${p.numero_documento}</td>
                        <td><span class="badge bg-secondary">Inactivo</span></td>
                        <td>${p.deleted_at ?? '-'}</td>
                        <td>
                            <button type="button" class="btn btn-success btn-sm restore" data-id="${p.id_proveedor}">
                                <i class="fas fa-undo"></i>
                            </button>

                            <button type="button" class="btn btn-danger btn-sm force-delete" data-id="${p.id_proveedor}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
                });
            }

            $('#inactiveProviderTableBody').html(html);
            $('#inactiveProviderCount').text(`Mostrando ${res.providers.length} proveedores inactivos`);

        }, 'json');
    }

    loadProviders();

    // ===== BUSCAR =====
    $btnFilter.click(() => loadProviders($search.val()));

    $btnClear.click(() => {
        $search.val('');
        loadProviders();
    });

    let timer;

    $search.on('keyup', function () {
        clearTimeout(timer);

        let value = $(this).val().trim();

        timer = setTimeout(() => {
            loadProviders(value);
        }, 300);
    });

    // ===== CREAR =====
    $btnCreate.click(() => {
        $createForm[0].reset();
        createModal.show();
    });

    $createForm.submit(function (e) {
        e.preventDefault();

        $.post(createUrl, $createForm.serialize(), function (res) {
            if (res.success) {
                createModal.hide();
                loadProviders($search.val());
                showInfoModal('Éxito', res.message || 'Proveedor creado correctamente');
            } else {
                showInfoModal('Error', res.message);
            }
        }, 'json');
    });

    // ===== VER =====
    $tbody.on('click', '.view', function () {
        let id = $(this).data('id');

        $.get(getUrl, { id_proveedor: id }, function (res) {

            if (!res.success) {
                showInfoModal('Error', res.message);
                return;
            }

            let p = res.provider;

            $('#detailProviderId').text(p.id_proveedor);
            $('#detailProviderStatus').html(
             `<span class="badge ${p.estado == 1 ? 'text-bg-success' : 'text-bg-secondary'}">
              ${p.estado == 1 ? 'Activo' : 'Inactivo'}
               </span>`
            );
            $('#detailProviderRazonSocial').text(p.razon_social);
            $('#detailProviderNombreComercial').text(p.nombre_comercial);
            $('#detailProviderDocumento').text(p.numero_documento);
            $('#detailProviderTelefono').text(p.telefono);
            $('#detailProviderCorreo').text(p.correo);
            $('#detailProviderDireccion').text(p.direccion);
            $('#detailProviderContacto').text(p.contacto);
            $('#detailProviderCreated').text(p.created_at);
            $('#detailProviderUpdated').text(p.updated_at);

            detailModal.show();

        }, 'json');
    });

    // ===== EDITAR =====
    $tbody.on('click', '.edit', function () {
        let id = $(this).data('id');

        $.get(getUrl, { id_proveedor: id }, function (res) {
            if (!res.success) {
                showInfoModal('Error', res.message);
                return;
            }

            let p = res.provider;

            $('#editProviderForm [name="id_proveedor"]').val(p.id_proveedor);
            $('#editProviderForm [name="razon_social"]').val(p.razon_social);
            $('#editProviderForm [name="nombre_comercial"]').val(p.nombre_comercial);
            $('#editProviderForm [name="id_tipo_documento"]').val(p.id_tipo_documento);
            $('#editProviderForm [name="numero_documento"]').val(p.numero_documento);
            $('#editProviderForm [name="telefono"]').val(p.telefono);
            $('#editProviderForm [name="correo"]').val(p.correo);
            $('#editProviderForm [name="direccion"]').val(p.direccion);
            $('#editProviderForm [name="contacto"]').val(p.contacto);
            $('#editProviderForm [name="estado"]').val(p.estado);

            editModal.show();

        }, 'json');
    });

    $editForm.submit(function (e) {
        e.preventDefault();

        $.post(updateUrl, $editForm.serialize(), function (res) {
            if (res.success) {
                editModal.hide();
                loadProviders($search.val());
                showInfoModal('Éxito', res.message || 'Proveedor actualizado correctamente');
            } else {
                showInfoModal('Error', res.message);
            }
        }, 'json');
    });

    // ===== ELIMINAR LÓGICO =====
 $tbody.on('click', '.delete', function () {

    let id = $(this).data('id');
    let name = $(this).data('name');

    $('#delete_id').val(id);
    $('#deleteProviderName').text(name || '-');

    deleteModal.show();
});

    $deleteForm.submit(function (e) {
        e.preventDefault();

        $.post(deleteUrl, $deleteForm.serialize(), function (res) {
            if (res.success) {
                deleteModal.hide();
                loadProviders($search.val());
                showInfoModal('Éxito', res.message || 'Proveedor eliminado correctamente');
            } else {
                showInfoModal('Error', res.message);
            }
        }, 'json');
    });

    // ===== ABRIR INACTIVOS =====
    $btnInactive.click(function () {
        reloadInactiveProviders();
        inactiveModal.show();
    });

  // ===== ABRIR MODAL RESTAURAR =====
$('#inactiveProviderTableBody').on('click', '.restore', function () {
    const id = $(this).data('id');
    const name = $(this).closest('tr').find('td').eq(1).text();

    $('#restore_id_proveedor').val(id);
    $('#restoreInactiveProviderName').text(name);

    const restoreModal = new bootstrap.Modal(
        document.getElementById('restoreInactiveProviderModal')
    );

    restoreModal.show();
});
// ===== CONFIRMAR RESTAURAR =====
$('#restoreInactiveProviderForm').on('submit', function (e) {
    e.preventDefault();

    $.post(restoreUrl, $(this).serialize(), function (res) {
        if (!res.success) {
            showInfoModal('Error', res.message);
            return;
        }

        bootstrap.Modal.getInstance(
            document.getElementById('restoreInactiveProviderModal')
        ).hide();

        loadProviders($search.val());
        reloadInactiveProviders();

        showInfoModal('Éxito', 'Proveedor restaurado correctamente');

    }, 'json');
});

// ===== ABRIR MODAL ELIMINAR DEFINITIVO =====
$('#inactiveProviderTableBody').on('click', '.force-delete', function () {
    const id = $(this).data('id');
    const name = $(this).closest('tr').find('td').eq(1).text();

    $('#hard_delete_id_proveedor').val(id);
    $('#hardDeleteInactiveProviderName').text(name);

    const hardDeleteModal = new bootstrap.Modal(
        document.getElementById('hardDeleteInactiveProviderModal')
    );

    hardDeleteModal.show();
});
// ===== CONFIRMAR ELIMINAR DEFINITIVO =====
$('#hardDeleteInactiveProviderForm').on('submit', function (e) {
    e.preventDefault();

    $.post(hardDeleteUrl, $(this).serialize(), function (res) {
        if (!res.success) {
            showInfoModal('Error', res.message);
            return;
        }

        bootstrap.Modal.getInstance(
            document.getElementById('hardDeleteInactiveProviderModal')
        ).hide();

        loadProviders($search.val());
        reloadInactiveProviders();

        showInfoModal('Éxito', 'Proveedor eliminado definitivamente');

    }, 'json');
});

});