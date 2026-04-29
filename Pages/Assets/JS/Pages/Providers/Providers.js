// =========================================================
// SCRIPT: PROVIDERS
// Logica AJAX, modals Bootstrap y eventos del modulo.
// =========================================================
$(function () {
    var $providerTableBody = $('#providerTableBody');
    var $providerSummary = $('#providerSummary');
    var $providerPageStatus = $('#providerPageStatus');
    var $pageSizeSelect = $('#pageSizeSelect');
    var $prevPageButton = $('#prevPageButton');
    var $nextPageButton = $('#nextPageButton');
    var $providerSearchInput = $('#providerSearchInput');
    var $filterSearchButton = $('#filterSearchButton');
    var $clearSearchButton = $('#clearSearchButton');
    var $openCreateModalButton = $('#openCreateModalButton');
    var $openInactiveModalButton = $('#openInactiveModalButton');
    var $providerTableShell = $('.provider-table-shell').first();
    var $inactiveProviderTableShell = $('.inactive-provider-table-shell');
    var $inactiveProviderTableBody = $('#inactiveProviderTableBody');
    var $inactiveProviderSummary = $('#inactiveProviderCount');
    var $inactiveProviderStatus = $('#inactiveProviderStatus');
    var $inactiveProviderSearchInput = $('#inactiveProviderSearchInput');
    var $clearInactiveProviderSearchButton = $('#clearInactiveProviderSearchButton');
    var $createProviderForm = $('#createProviderForm');
    var $editProviderForm = $('#editProviderForm');
    var $deleteProviderForm = $('#deleteProviderForm');
    var $hardDeleteInactiveProviderForm = $('#hardDeleteInactiveProviderForm');
    var $restoreInactiveProviderForm = $('#restoreInactiveProviderForm');
    var $createFeedback = $('#createProviderFeedback');
    var $editFeedback = $('#editProviderFeedback');
    var $deleteFeedback = $('#deleteProviderFeedback');
    var $hardDeleteInactiveFeedback = $('#hardDeleteInactiveProviderFeedback');
    var $restoreInactiveFeedback = $('#restoreInactiveProviderFeedback');
    var $createModalElement = $('#createProviderModal');
    var $editModalElement = $('#editProviderModal');
    var $detailModalElement = $('#detailProviderModal');
    var $deleteModalElement = $('#deleteProviderModal');
    var $confirmExitModalElement = $('#confirmExitProviderModal');
    var $infoModalElement = $('#infoProviderModal');
    var $inactiveProviderModalElement = $('#inactiveProviderModal');
    var $hardDeleteInactiveProviderModalElement = $('#hardDeleteInactiveProviderModal');
    var $restoreInactiveProviderModalElement = $('#restoreInactiveProviderModal');
    var $confirmExitTitle = $('#confirmExitProviderTitle');
    var $confirmExitCopy = $('#confirmExitProviderCopy');
    var $confirmExitSaveButton = $('#confirmExitSaveProviderButton');
    var $infoModalTitle = $('#infoProviderModalTitle');
    var $infoModalMessage = $('#infoProviderModalMessage');

    var createModal = null;
    var editModal = null;
    var detailModal = null;
    var deleteModal = null;
    var confirmExitModal = null;
    var infoModal = null;
    var inactiveProviderModal = null;
    var hardDeleteInactiveProviderModal = null;
    var restoreInactiveProviderModal = null;

    var listUrl = 'Api/Providers/List.php';
    var listInactiveUrl = 'Api/Providers/ListInactive.php';
    var getUrl = 'Api/Providers/Get.php';
    var createUrl = 'Api/Providers/Create.php';
    var updateUrl = 'Api/Providers/Update.php';
    var deleteUrl = 'Api/Providers/Delete.php';
    var restoreUrl = 'Api/Providers/Restore.php';
    var hardDeleteUrl = 'Api/Providers/HardDelete.php';

    var listRequest = null;
    var inactiveListRequest = null;
    var searchTimer = null;
    var inactiveSearchTimer = null;
    var dragState = {
        active: false,
        dragging: false,
        startX: 0,
        startY: 0,
        scrollLeft: 0,
        scrollTop: 0,
        pointerId: null
    };
    var pagination = {
        page: 1,
        page_size: 10,
        total: 0,
        total_pages: 1,
        search: ''
    };
    var formState = {
        create: {
            initialSnapshot: '',
            allowClose: false,
            pendingSave: false
        },
        edit: {
            initialSnapshot: '',
            allowClose: false,
            pendingSave: false
        }
    };
    var exitPromptState = {
        activeModalKey: ''
    };
    var inactiveState = {
        search: '',
        total: 0
    };

    function escapeHtml(value) {
        return $('<div>').text(value === null || typeof value === 'undefined' ? '' : value).html();
    }

    function toTrimmedString(value) {
        return String(value === null || typeof value === 'undefined' ? '' : value).trim();
    }

    function emptyText(value) {
        var text = toTrimmedString(value);
        return text === '' ? '-' : text;
    }

    function isNumericSearch(value) {
        return /^[0-9]+$/.test(toTrimmedString(value));
    }

    function formatDate(dateTime) {
        return dateTime ? String(dateTime).replace(' ', ' | ') : 'Sin fecha';
    }

    function showFeedback($element, message, type) {
        if (!$element.length) {
            return;
        }

        if (!message) {
            $element.addClass('d-none').removeClass('alert-success alert-danger alert-warning alert-info').text('');
            return;
        }

        $element
            .removeClass('d-none alert-success alert-danger alert-warning alert-info')
            .addClass('alert-' + type)
            .text(message);
    }

    function setModalDialogSize($modalElement, sizeClass) {
        var modalSizes = 'modal-sm modal-lg modal-xl';
        var $dialog = $modalElement.find('.modal-dialog');

        if (!$dialog.length) {
            return;
        }

        $dialog.removeClass(modalSizes);

        if (sizeClass) {
            $dialog.addClass(sizeClass);
        }
    }

    function setButtonLoading($button, isLoading, label) {
        if (!$button.length) {
            return;
        }

        if (isLoading) {
            $button.data('original-html', $button.html());
            $button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>' + label);
            return;
        }

        $button.prop('disabled', false).html($button.data('original-html') || label);
    }

    function getModalInstance($element) {
        if (!$element.length || typeof bootstrap === 'undefined' || !bootstrap.Modal) {
            return null;
        }

        return bootstrap.Modal.getOrCreateInstance($element[0]);
    }

    function ensureModals() {
        if (createModal === null) {
            createModal = getModalInstance($createModalElement);
        }
        if (editModal === null) {
            editModal = getModalInstance($editModalElement);
        }
        if (detailModal === null) {
            detailModal = getModalInstance($detailModalElement);
        }
        if (deleteModal === null) {
            deleteModal = getModalInstance($deleteModalElement);
        }
        if (confirmExitModal === null) {
            confirmExitModal = getModalInstance($confirmExitModalElement);
        }
        if (infoModal === null) {
            infoModal = getModalInstance($infoModalElement);
        }
        if (inactiveProviderModal === null) {
            inactiveProviderModal = getModalInstance($inactiveProviderModalElement);
        }
        if (hardDeleteInactiveProviderModal === null) {
            hardDeleteInactiveProviderModal = getModalInstance($hardDeleteInactiveProviderModalElement);
        }
        if (restoreInactiveProviderModal === null) {
            restoreInactiveProviderModal = getModalInstance($restoreInactiveProviderModalElement);
        }
    }

    function showInfoModal(title, message) {
        ensureModals();
        setModalDialogSize($infoModalElement, 'modal-sm');
        $infoModalTitle.text(title || 'Aviso');
        $infoModalMessage.text(message || 'Ocurrio un evento que requiere tu atencion.');

        if (infoModal) {
            infoModal.show();
        }
    }

    function extractResponseMessage(xhr, fallbackMessage) {
        if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
            return xhr.responseJSON.message;
        }

        if (xhr && xhr.responseText) {
            try {
                var parsedResponse = JSON.parse(xhr.responseText);
                if (parsedResponse && parsedResponse.message) {
                    return parsedResponse.message;
                }
            } catch (error) {
                // Respuesta no JSON.
            }
        }

        return fallbackMessage;
    }

    function normalizeProvider(provider) {
        if (!provider) {
            return null;
        }

        return {
            id_proveedor: Number(provider.id_proveedor) || 0,
            razon_social: provider.razon_social || '',
            nombre_comercial: provider.nombre_comercial || '',
            id_tipo_documento: Number(provider.id_tipo_documento) || 0,
            numero_documento: provider.numero_documento || '',
            telefono: provider.telefono || '',
            correo: provider.correo || '',
            direccion: provider.direccion || '',
            contacto: provider.contacto || '',
            estado: Number(provider.estado) || 0,
            created_at: provider.created_at || '',
            updated_at: provider.updated_at || '',
            deleted_at: provider.deleted_at || ''
        };
    }

    function validateProviderPayload(payload) {
        var razonSocial = toTrimmedString(payload.razon_social);
        var idTipoDocumento = Number(payload.id_tipo_documento) || 0;
        var numeroDocumento = toTrimmedString(payload.numero_documento);
        var estado = toTrimmedString(payload.estado);

        if (razonSocial === '') {
            return 'Debes ingresar la razon social del proveedor.';
        }

        if (razonSocial.length < 3) {
            return 'La razon social debe tener al menos 3 caracteres.';
        }

        if (idTipoDocumento <= 0) {
            return 'Debes seleccionar un tipo de documento valido.';
        }

        if (numeroDocumento === '') {
            return 'Debes ingresar el numero de documento.';
        }

        if (estado !== '0' && estado !== '1') {
            return 'Debes seleccionar un estado valido para el proveedor.';
        }

        return '';
    }

    function updateTableMode(pageSize) {
        var tableModes = 'table-size-10 table-size-20 table-size-50';
        $providerTableShell.removeClass(tableModes).addClass('table-size-' + pageSize);
    }

    function updateTableHeight(rowCount) {
        var headerHeight = 48;
        var rowHeight = 52;
        var emptyRowHeight = 72;
        var visibleRows = Math.min(Math.max(Number(rowCount) || 0, 0), 10);
        var bodyHeight = visibleRows > 0 ? visibleRows * rowHeight : emptyRowHeight;
        var maxHeight = headerHeight + bodyHeight + 2;

        $providerTableShell
            .addClass('dynamic-height')
            .css('max-height', maxHeight + 'px');
    }

    function updateInactiveTableHeight(rowCount) {
        var headerHeight = 48;
        var rowHeight = 52;
        var emptyRowHeight = 72;
        var visibleRows = Math.min(Math.max(Number(rowCount) || 0, 0), 10);
        var bodyHeight = visibleRows > 0 ? visibleRows * rowHeight : emptyRowHeight;
        var maxHeight = headerHeight + bodyHeight + 2;

        $inactiveProviderTableShell
            .addClass('dynamic-height')
            .css('max-height', maxHeight + 'px');
    }

    function updateControlsState(isLoading) {
        var currentPage = Number(pagination.page) || 1;
        var totalPages = Number(pagination.total_pages) || 1;

        $pageSizeSelect.prop('disabled', isLoading);
        $prevPageButton.prop('disabled', isLoading || currentPage <= 1);
        $nextPageButton.prop('disabled', isLoading || currentPage >= totalPages);
    }

    function updatePaginationInfo() {
        var total = Number(pagination.total) || 0;
        var currentPage = Number(pagination.page) || 1;
        var totalPages = Number(pagination.total_pages) || 1;
        var pageSize = Number(pagination.page_size) || 10;
        var startRecord = total === 0 ? 0 : ((currentPage - 1) * pageSize) + 1;
        var endRecord = Math.min(currentPage * pageSize, total);
        var hasSearch = toTrimmedString(pagination.search) !== '';

        if (total === 0) {
            $providerSummary.text(hasSearch ? 'No se encontraron proveedores con el filtro actual' : 'Mostrando 0 de 0 proveedores');
        } else {
            $providerSummary.text('Mostrando ' + startRecord + ' - ' + endRecord + ' de ' + total + ' proveedores');
        }

        $providerPageStatus.text('Pagina ' + currentPage + ' de ' + totalPages);
        $pageSizeSelect.val(String(pageSize));
        updateTableMode(pageSize);
    }

    function updateInactiveSummary() {
        var total = Number(inactiveState.total) || 0;
        var hasSearch = toTrimmedString(inactiveState.search) !== '';

        if (total === 0) {
            $inactiveProviderSummary.text(hasSearch ? 'No se encontraron proveedores inactivos con el filtro actual' : 'Mostrando 0 proveedores inactivos');
            $inactiveProviderStatus.text('Sin resultados');
            return;
        }

        $inactiveProviderSummary.text('Mostrando ' + total + ' proveedores inactivos');
        $inactiveProviderStatus.text(total + ' resultado(s)');
    }

    function buildStatusBadge(estado) {
        var isActive = Number(estado) === 1;
        var badgeClass = isActive ? 'text-bg-success' : 'text-bg-secondary';
        var badgeText = isActive ? 'Activo' : 'Inactivo';

        return '<span class="badge ' + badgeClass + '">' + badgeText + '</span>';
    }

    function buildActionButtons(provider) {
        return '' +
            '<div class="provider-actions">' +
                '<button type="button" class="btn btn-outline-primary provider-action-button js-view-provider" title="Ver detalle" data-id="' + escapeHtml(provider.id_proveedor) + '">' +
                    '<i class="fas fa-eye"></i>' +
                '</button>' +
                '<button type="button" class="btn btn-outline-warning provider-action-button js-edit-provider" title="Editar" data-id="' + escapeHtml(provider.id_proveedor) + '">' +
                    '<i class="fas fa-edit"></i>' +
                '</button>' +
                '<button type="button" class="btn btn-outline-danger provider-action-button js-delete-provider" title="Eliminar" data-id="' + escapeHtml(provider.id_proveedor) + '" data-name="' + escapeHtml(provider.razon_social) + '">' +
                    '<i class="fas fa-trash"></i>' +
                '</button>' +
            '</div>';
    }

    function buildInactiveActionButtons(provider) {
        return '' +
            '<div class="inactive-provider-actions">' +
                '<button type="button" class="btn btn-outline-success provider-action-button js-restore-provider" title="Restaurar" data-id="' + escapeHtml(provider.id_proveedor) + '">' +
                    '<i class="fas fa-undo"></i>' +
                '</button>' +
                '<button type="button" class="btn btn-outline-danger provider-action-button js-hard-delete-provider" title="Eliminar definitivo" data-id="' + escapeHtml(provider.id_proveedor) + '" data-name="' + escapeHtml(provider.razon_social) + '">' +
                    '<i class="fas fa-trash"></i>' +
                '</button>' +
            '</div>';
    }

    function renderMessageRow(message, textClass) {
        $providerTableBody.html(
            '<tr><td colspan="7" class="text-center py-4 ' + textClass + '">' + escapeHtml(message) + '</td></tr>'
        );
        updateTableHeight(0);
    }

    function renderRows(providers) {
        if (!providers.length) {
            renderMessageRow('No hay proveedores registrados.', 'text-muted');
            return;
        }

        var rows = $.map(providers, function (provider) {
            return '' +
                '<tr>' +
                    '<td>' + escapeHtml(provider.id_proveedor) + '</td>' +
                    '<td>' + escapeHtml(provider.razon_social) + '</td>' +
                    '<td>' + escapeHtml(provider.numero_documento) + '</td>' +
                    '<td>' + escapeHtml(emptyText(provider.telefono)) + '</td>' +
                    '<td>' + buildStatusBadge(provider.estado) + '</td>' +
                    '<td>' + escapeHtml(formatDate(provider.created_at)) + '</td>' +
                    '<td>' + buildActionButtons(provider) + '</td>' +
                '</tr>';
        }).join('');

        $providerTableBody.html(rows);
        updateTableHeight(providers.length);
    }

    function renderInactiveMessageRow(message, textClass) {
        $inactiveProviderTableBody.html(
            '<tr><td colspan="6" class="text-center py-4 ' + textClass + '">' + escapeHtml(message) + '</td></tr>'
        );
        updateInactiveTableHeight(0);
    }

    function renderInactiveRows(providers) {
        if (!providers.length) {
            renderInactiveMessageRow('No hay proveedores inactivos registrados.', 'text-muted');
            return;
        }

        var rows = $.map(providers, function (provider) {
            return '' +
                '<tr>' +
                    '<td>' + escapeHtml(provider.id_proveedor) + '</td>' +
                    '<td>' + escapeHtml(provider.razon_social) + '</td>' +
                    '<td>' + escapeHtml(provider.numero_documento) + '</td>' +
                    '<td>' + buildStatusBadge(provider.estado) + '</td>' +
                    '<td>' + escapeHtml(formatDate(provider.deleted_at)) + '</td>' +
                    '<td>' + buildInactiveActionButtons(provider) + '</td>' +
                '</tr>';
        }).join('');

        $inactiveProviderTableBody.html(rows);
        updateInactiveTableHeight(providers.length);
    }

    function getFormSnapshot($form) {
        return $form.length ? $form.serialize() : '';
    }

    function setFormInitialState(modalKey, $form) {
        if (!formState[modalKey]) {
            return;
        }

        formState[modalKey].initialSnapshot = getFormSnapshot($form);
        formState[modalKey].allowClose = false;
        formState[modalKey].pendingSave = false;
    }

    function hasUnsavedChanges(modalKey, $form) {
        if (!formState[modalKey]) {
            return false;
        }

        return getFormSnapshot($form) !== formState[modalKey].initialSnapshot;
    }

    function requestModalClose(modalKey, modalInstance) {
        if (!formState[modalKey]) {
            return;
        }

        formState[modalKey].allowClose = true;

        if (modalInstance) {
            modalInstance.hide();
        }

        formState[modalKey].allowClose = false;
        formState[modalKey].pendingSave = false;
        exitPromptState.activeModalKey = '';
    }

    function openExitPrompt(modalKey) {
        ensureModals();
        setModalDialogSize($confirmExitModalElement, 'modal-sm');

        exitPromptState.activeModalKey = modalKey;
        $confirmExitTitle.text(modalKey === 'create' ? 'Salir de crear proveedor' : 'Salir de editar proveedor');
        $confirmExitCopy.html(
            modalKey === 'create'
                ? 'Has ingresado informacion nueva en el formulario de <strong>crear proveedor</strong>. Puedes volver, salir sin guardar o guardar antes de cerrar.'
                : 'Has realizado cambios en el formulario de <strong>editar proveedor</strong>. Puedes volver, salir sin guardar o guardar antes de cerrar.'
        );

        if ($confirmExitSaveButton.length) {
            $confirmExitSaveButton.text(modalKey === 'create' ? 'Guardar proveedor' : 'Guardar cambios');
        }

        if (confirmExitModal) {
            window.setTimeout(function () {
                confirmExitModal.show();
            }, 0);
        }
    }

    function bindDragScroll() {
        var interactiveSelector = 'button, a, input, textarea, select, label, .provider-actions, .provider-action-button';
        var dragThreshold = 8;

        $providerTableShell.on('pointerdown', function (event) {
            if (event.pointerType === 'mouse' && event.button !== 0) {
                return;
            }

            if ($(event.target).closest(interactiveSelector).length) {
                return;
            }

            dragState.active = true;
            dragState.dragging = false;
            dragState.startX = event.clientX;
            dragState.startY = event.clientY;
            dragState.scrollLeft = this.scrollLeft;
            dragState.scrollTop = this.scrollTop;
            dragState.pointerId = event.pointerId;

            if (this.setPointerCapture) {
                this.setPointerCapture(event.pointerId);
            }
        });

        $providerTableShell.on('pointermove', function (event) {
            if (!dragState.active) {
                return;
            }

            if (!dragState.dragging) {
                if (
                    Math.abs(event.clientX - dragState.startX) < dragThreshold &&
                    Math.abs(event.clientY - dragState.startY) < dragThreshold
                ) {
                    return;
                }

                dragState.dragging = true;
                $(this).addClass('is-dragging');
            }

            event.preventDefault();
            this.scrollLeft = dragState.scrollLeft - (event.clientX - dragState.startX);
            this.scrollTop = dragState.scrollTop - (event.clientY - dragState.startY);
        });

        $providerTableShell.on('pointerup pointercancel lostpointercapture', function (event) {
            if (dragState.pointerId !== null && typeof event.pointerId !== 'undefined' && dragState.pointerId !== event.pointerId) {
                return;
            }

            dragState.active = false;
            dragState.dragging = false;
            dragState.pointerId = null;
            $(this).removeClass('is-dragging');
        });
    }

    function loadProviderDetails(providerId, onSuccess) {
        $.ajax({
            url: getUrl,
            method: 'GET',
            dataType: 'json',
            cache: false,
            data: {
                id_proveedor: Number(providerId) || 0
            }
        })
            .done(function (response) {
                if (!response.success || !response.provider) {
                    showInfoModal('Proveedor no disponible', response.message || 'No se pudo obtener el detalle del proveedor seleccionado.');
                    return;
                }

                onSuccess(normalizeProvider(response.provider));
            })
            .fail(function (xhr) {
                showInfoModal('No se pudo consultar el proveedor', extractResponseMessage(xhr, 'Ocurrio un problema al consultar el proveedor. Intenta nuevamente.'));
            });
    }

    function runMainSearch() {
        var searchValue = toTrimmedString($providerSearchInput.val());

        if (searchValue === '') {
            showInfoModal('Campo de busqueda vacio', 'Debes ingresar un ID o un nombre antes de hacer clic en Filtrar.');
            $providerSearchInput.trigger('focus');
            return;
        }

        pagination.search = searchValue;

        if (isNumericSearch(searchValue)) {
            ensureModals();
            setModalDialogSize($detailModalElement, 'modal-lg');
            loadProviderDetails(Number(searchValue), function (provider) {
                fillDetailModal(provider);

                if (detailModal) {
                    detailModal.show();
                }
            });
            return;
        }

        loadProviders(1, pagination.page_size);
    }

    function fillDetailModal(provider) {
        $('#detailProviderId').text(provider.id_proveedor);
        $('#detailProviderStatus').html(buildStatusBadge(provider.estado));
        $('#detailProviderRazonSocial').text(emptyText(provider.razon_social));
        $('#detailProviderNombreComercial').text(emptyText(provider.nombre_comercial));
        $('#detailProviderDocumento').text(emptyText(provider.numero_documento));
        $('#detailProviderTelefono').text(emptyText(provider.telefono));
        $('#detailProviderCorreo').text(emptyText(provider.correo));
        $('#detailProviderDireccion').text(emptyText(provider.direccion));
        $('#detailProviderContacto').text(emptyText(provider.contacto));
        $('#detailProviderCreated').text(formatDate(provider.created_at));
        $('#detailProviderUpdated').text(formatDate(provider.updated_at));
    }

    function fillEditModal(provider) {
        $('#edit_id_proveedor').val(provider.id_proveedor);
        $('#edit_id_proveedor_readonly').val(provider.id_proveedor);
        $('#edit_razon_social').val(provider.razon_social);
        $('#edit_nombre_comercial').val(provider.nombre_comercial);
        $('#edit_id_tipo_documento').val(String(provider.id_tipo_documento));
        $('#edit_numero_documento').val(provider.numero_documento);
        $('#edit_telefono').val(provider.telefono);
        $('#edit_correo').val(provider.correo);
        $('#edit_direccion').val(provider.direccion);
        $('#edit_contacto').val(provider.contacto);
        $('#edit_estado').val(String(provider.estado));
    }

    function prepareDeleteModal(provider) {
        $('#delete_id').val(provider.id_proveedor);
        $('#deleteProviderName').text(provider.razon_social || '-');
    }

    function prepareHardDeleteInactiveModal(provider) {
        $('#hard_delete_id_proveedor').val(provider.id_proveedor);
        $('#hardDeleteInactiveProviderName').text(provider.razon_social || '-');
    }

    function prepareRestoreInactiveModal(provider) {
        $('#restore_id_proveedor').val(provider.id_proveedor);
        $('#restoreInactiveProviderName').text(provider.razon_social || '-');
    }

    function loadProviders(page, pageSize) {
        if (listRequest) {
            listRequest.abort();
            listRequest = null;
        }

        pagination.page = Number(page) || 1;
        pagination.page_size = Number(pageSize) || 10;
        updateControlsState(true);

        listRequest = $.ajax({
            url: listUrl,
            method: 'GET',
            dataType: 'json',
            cache: false,
            data: {
                page: pagination.page,
                page_size: pagination.page_size,
                search: toTrimmedString(pagination.search)
            }
        })
            .done(function (response) {
                if (!response.success) {
                    renderMessageRow(response.message || 'No se pudo cargar la tabla.', 'text-danger');
                    return;
                }

                pagination = $.extend({}, pagination, response.pagination || {});
                pagination.search = response.search || toTrimmedString(pagination.search);
                renderRows(response.providers || []);
                updatePaginationInfo();
            })
            .fail(function (xhr, textStatus) {
                if (textStatus === 'abort') {
                    return;
                }

                renderMessageRow(extractResponseMessage(xhr, 'Ocurrio un problema al cargar los proveedores.'), 'text-danger');
            })
            .always(function () {
                listRequest = null;
                updateControlsState(false);
            });
    }

    function loadInactiveProviders() {
        if (inactiveListRequest) {
            inactiveListRequest.abort();
            inactiveListRequest = null;
        }

        renderInactiveMessageRow('Cargando proveedores inactivos...', 'text-muted');

        inactiveListRequest = $.ajax({
            url: listInactiveUrl,
            method: 'GET',
            dataType: 'json',
            cache: false,
            data: {
                search: toTrimmedString(inactiveState.search)
            }
        })
            .done(function (response) {
                if (!response.success) {
                    renderInactiveMessageRow(response.message || 'No se pudo cargar la tabla de inactivos.', 'text-danger');
                    return;
                }

                inactiveState.total = (response.providers || []).length;
                inactiveState.search = response.search || toTrimmedString(inactiveState.search);
                renderInactiveRows(response.providers || []);
                updateInactiveSummary();
            })
            .fail(function (xhr, textStatus) {
                if (textStatus === 'abort') {
                    return;
                }

                renderInactiveMessageRow(extractResponseMessage(xhr, 'Ocurrio un problema al cargar los proveedores inactivos.'), 'text-danger');
            })
            .always(function () {
                inactiveListRequest = null;
            });
    }

    $pageSizeSelect.on('change', function () {
        loadProviders(1, Number($(this).val()) || 10);
    });

    $providerSearchInput.on('input', function () {
        var currentValue = toTrimmedString($(this).val());
        pagination.search = currentValue;

        if (searchTimer) {
            clearTimeout(searchTimer);
        }

        if (currentValue !== '' && isNumericSearch(currentValue)) {
            return;
        }

        searchTimer = window.setTimeout(function () {
            loadProviders(1, pagination.page_size);
        }, 350);
    });

    $providerSearchInput.on('keydown', function (event) {
        if (event.key !== 'Enter') {
            return;
        }

        event.preventDefault();
        runMainSearch();
    });

    $filterSearchButton.on('click', function () {
        runMainSearch();
    });

    $clearSearchButton.on('click', function () {
        pagination.search = '';
        $providerSearchInput.val('');
        loadProviders(1, pagination.page_size);
    });

    $openInactiveModalButton.on('click', function () {
        ensureModals();
        setModalDialogSize($inactiveProviderModalElement, 'modal-xl');
        inactiveState.search = '';
        $inactiveProviderSearchInput.val('');
        updateInactiveSummary();
        loadInactiveProviders();

        if (inactiveProviderModal) {
            inactiveProviderModal.show();
        }
    });

    $inactiveProviderSearchInput.on('input', function () {
        inactiveState.search = toTrimmedString($(this).val());

        if (inactiveSearchTimer) {
            clearTimeout(inactiveSearchTimer);
        }

        inactiveSearchTimer = window.setTimeout(function () {
            loadInactiveProviders();
        }, 350);
    });

    $clearInactiveProviderSearchButton.on('click', function () {
        inactiveState.search = '';
        $inactiveProviderSearchInput.val('');
        loadInactiveProviders();
    });

    $prevPageButton.on('click', function (event) {
        event.preventDefault();

        if (listRequest || pagination.page <= 1) {
            return;
        }

        loadProviders(pagination.page - 1, pagination.page_size);
    });

    $nextPageButton.on('click', function (event) {
        event.preventDefault();

        if (listRequest || pagination.page >= pagination.total_pages) {
            return;
        }

        loadProviders(pagination.page + 1, pagination.page_size);
    });

    $openCreateModalButton.on('click', function () {
        ensureModals();
        setModalDialogSize($createModalElement, 'modal-lg');

        if ($createProviderForm.length) {
            $createProviderForm[0].reset();
            setFormInitialState('create', $createProviderForm);
        }
        showFeedback($createFeedback, '', 'info');

        if (createModal) {
            createModal.show();
        }
    });

    $providerTableBody.on('click', '.js-view-provider', function () {
        var providerId = $(this).data('id');
        ensureModals();
        setModalDialogSize($detailModalElement, 'modal-lg');

        loadProviderDetails(providerId, function (provider) {
            fillDetailModal(provider);

            if (detailModal) {
                detailModal.show();
            }
        });
    });

    $providerTableBody.on('click', '.js-edit-provider', function () {
        var providerId = $(this).data('id');
        ensureModals();
        setModalDialogSize($editModalElement, 'modal-lg');

        loadProviderDetails(providerId, function (provider) {
            fillEditModal(provider);
            setFormInitialState('edit', $editProviderForm);
            showFeedback($editFeedback, '', 'info');

            if (editModal) {
                editModal.show();
            }
        });
    });

    $providerTableBody.on('click', '.js-delete-provider', function () {
        var providerId = $(this).data('id');
        ensureModals();
        setModalDialogSize($deleteModalElement, 'modal-sm');

        loadProviderDetails(providerId, function (provider) {
            prepareDeleteModal(provider);
            showFeedback($deleteFeedback, '', 'info');

            if (deleteModal) {
                deleteModal.show();
            }
        });
    });

    $inactiveProviderTableBody.on('click', '.js-restore-provider', function () {
        var provider = {
            id_proveedor: Number($(this).data('id')) || 0,
            razon_social: $(this).closest('tr').find('td').eq(1).text() || ''
        };

        ensureModals();
        setModalDialogSize($restoreInactiveProviderModalElement, 'modal-sm');
        prepareRestoreInactiveModal(provider);
        showFeedback($restoreInactiveFeedback, '', 'info');

        if (restoreInactiveProviderModal) {
            restoreInactiveProviderModal.show();
        }
    });

    $inactiveProviderTableBody.on('click', '.js-hard-delete-provider', function () {
        var provider = {
            id_proveedor: Number($(this).data('id')) || 0,
            razon_social: $(this).data('name') || ''
        };

        ensureModals();
        setModalDialogSize($hardDeleteInactiveProviderModalElement, 'modal-sm');
        prepareHardDeleteInactiveModal(provider);
        showFeedback($hardDeleteInactiveFeedback, '', 'info');

        if (hardDeleteInactiveProviderModal) {
            hardDeleteInactiveProviderModal.show();
        }
    });

    $createProviderForm.on('submit', function (event) {
        var $submitButton = $createProviderForm.find('button[type="submit"]');
        var formData = {
            razon_social: $createProviderForm.find('[name="razon_social"]').val(),
            id_tipo_documento: $createProviderForm.find('[name="id_tipo_documento"]').val(),
            numero_documento: $createProviderForm.find('[name="numero_documento"]').val(),
            estado: $createProviderForm.find('[name="estado"]').val()
        };
        var validationMessage = validateProviderPayload(formData);

        event.preventDefault();
        showFeedback($createFeedback, '', 'info');

        if (validationMessage !== '') {
            showFeedback($createFeedback, validationMessage, 'warning');
            showInfoModal('Datos incompletos', validationMessage);
            return;
        }

        setButtonLoading($submitButton, true, 'Guardando...');

        $.ajax({
            url: createUrl,
            method: 'POST',
            dataType: 'json',
            data: $createProviderForm.serialize()
        })
            .done(function (response) {
                if (!response.success) {
                    showFeedback($createFeedback, response.message || 'No se pudo registrar el proveedor.', 'danger');
                    return;
                }

                showFeedback($createFeedback, response.message || 'Proveedor registrado correctamente.', 'success');
                $createProviderForm[0].reset();
                setFormInitialState('create', $createProviderForm);
                formState.create.allowClose = true;
                loadProviders(1, pagination.page_size);

                window.setTimeout(function () {
                    if (createModal) {
                        createModal.hide();
                    }
                }, 650);
            })
            .fail(function (xhr) {
                showFeedback($createFeedback, extractResponseMessage(xhr, 'No se pudo registrar el proveedor en este momento.'), 'danger');
            })
            .always(function () {
                setButtonLoading($submitButton, false, 'Guardar proveedor');
            });
    });

    $editProviderForm.on('submit', function (event) {
        var $submitButton = $editProviderForm.find('button[type="submit"]');
        var formData = {
            razon_social: $('#edit_razon_social').val(),
            id_tipo_documento: $('#edit_id_tipo_documento').val(),
            numero_documento: $('#edit_numero_documento').val(),
            estado: $('#edit_estado').val()
        };
        var validationMessage = validateProviderPayload(formData);

        event.preventDefault();
        showFeedback($editFeedback, '', 'info');

        if (validationMessage !== '') {
            showFeedback($editFeedback, validationMessage, 'warning');
            showInfoModal('Datos incompletos', validationMessage);
            return;
        }

        setButtonLoading($submitButton, true, 'Actualizando...');

        $.ajax({
            url: updateUrl,
            method: 'POST',
            dataType: 'json',
            data: $editProviderForm.serialize()
        })
            .done(function (response) {
                if (!response.success) {
                    showFeedback($editFeedback, response.message || 'No se pudo actualizar el proveedor.', 'danger');
                    return;
                }

                showFeedback($editFeedback, response.message || 'Proveedor actualizado correctamente.', 'success');
                setFormInitialState('edit', $editProviderForm);
                formState.edit.allowClose = true;
                loadProviders(pagination.page, pagination.page_size);

                window.setTimeout(function () {
                    if (editModal) {
                        editModal.hide();
                    }
                }, 650);
            })
            .fail(function (xhr) {
                showFeedback($editFeedback, extractResponseMessage(xhr, 'No se pudo actualizar el proveedor en este momento.'), 'danger');
            })
            .always(function () {
                setButtonLoading($submitButton, false, 'Guardar cambios');
            });
    });

    $deleteProviderForm.on('submit', function (event) {
        var $submitButton = $deleteProviderForm.find('button[type="submit"]');

        event.preventDefault();
        showFeedback($deleteFeedback, '', 'info');
        setButtonLoading($submitButton, true, 'Eliminando...');

        $.ajax({
            url: deleteUrl,
            method: 'POST',
            dataType: 'json',
            data: $deleteProviderForm.serialize()
        })
            .done(function (response) {
                if (!response.success) {
                    showFeedback($deleteFeedback, response.message || 'No se pudo eliminar el proveedor.', 'danger');
                    showInfoModal('Error', response.message || 'No se pudo eliminar el proveedor.');
                    return;
                }

                showFeedback($deleteFeedback, response.message || 'Proveedor eliminado correctamente.', 'success');
                loadProviders(pagination.page, pagination.page_size);

                window.setTimeout(function () {
                    if (deleteModal) {
                        deleteModal.hide();
                    }
                }, 650);
            })
            .fail(function (xhr) {
                showFeedback($deleteFeedback, extractResponseMessage(xhr, 'No se pudo eliminar el proveedor en este momento.'), 'danger');
            })
            .always(function () {
                setButtonLoading($submitButton, false, 'Confirmar eliminacion');
            });
    });

    $hardDeleteInactiveProviderForm.on('submit', function (event) {
        var $submitButton = $hardDeleteInactiveProviderForm.find('button[type="submit"]');

        event.preventDefault();
        showFeedback($hardDeleteInactiveFeedback, '', 'info');
        setButtonLoading($submitButton, true, 'Eliminando...');

        $.ajax({
            url: hardDeleteUrl,
            method: 'POST',
            dataType: 'json',
            data: $hardDeleteInactiveProviderForm.serialize()
        })
            .done(function (response) {
                if (!response.success) {
                    showFeedback($hardDeleteInactiveFeedback, response.message || 'No se pudo eliminar definitivamente.', 'danger');
                    showInfoModal('Error', response.message || 'No se pudo eliminar definitivamente.');
                    return;
                }

                showFeedback($hardDeleteInactiveFeedback, response.message || 'Proveedor eliminado definitivamente.', 'success');
                loadInactiveProviders();
                loadProviders(pagination.page, pagination.page_size);

                window.setTimeout(function () {
                    if (hardDeleteInactiveProviderModal) {
                        hardDeleteInactiveProviderModal.hide();
                    }
                }, 750);
            })
            .fail(function (xhr) {
                showFeedback($hardDeleteInactiveFeedback, extractResponseMessage(xhr, 'No se pudo eliminar definitivamente el proveedor en este momento.'), 'danger');
            })
            .always(function () {
                setButtonLoading($submitButton, false, 'Confirmar eliminacion');
            });
    });

    $restoreInactiveProviderForm.on('submit', function (event) {
        var $submitButton = $restoreInactiveProviderForm.find('button[type="submit"]');

        event.preventDefault();
        showFeedback($restoreInactiveFeedback, '', 'info');
        setButtonLoading($submitButton, true, 'Restaurando...');

        $.ajax({
            url: restoreUrl,
            method: 'POST',
            dataType: 'json',
            data: $restoreInactiveProviderForm.serialize()
        })
            .done(function (response) {
                if (!response.success) {
                    showFeedback($restoreInactiveFeedback, response.message || 'No se pudo restaurar el proveedor.', 'danger');
                    return;
                }

                showFeedback($restoreInactiveFeedback, response.message || 'Proveedor restaurado correctamente.', 'success');
                loadInactiveProviders();
                loadProviders(1, pagination.page_size);

                window.setTimeout(function () {
                    if (restoreInactiveProviderModal) {
                        restoreInactiveProviderModal.hide();
                    }
                }, 750);
            })
            .fail(function (xhr) {
                showFeedback($restoreInactiveFeedback, extractResponseMessage(xhr, 'No se pudo restaurar el proveedor en este momento.'), 'danger');
            })
            .always(function () {
                setButtonLoading($submitButton, false, 'Restaurar proveedor');
            });
    });

    $createModalElement.on('hide.bs.modal', function (event) {
        if (formState.create.allowClose || !hasUnsavedChanges('create', $createProviderForm)) {
            return;
        }

        event.preventDefault();
        openExitPrompt('create');
    });

    $editModalElement.on('hide.bs.modal', function (event) {
        if (formState.edit.allowClose || !hasUnsavedChanges('edit', $editProviderForm)) {
            return;
        }

        event.preventDefault();
        openExitPrompt('edit');
    });

    $createModalElement.on('hidden.bs.modal', function () {
        formState.create.allowClose = false;
        formState.create.pendingSave = false;
    });

    $editModalElement.on('hidden.bs.modal', function () {
        formState.edit.allowClose = false;
        formState.edit.pendingSave = false;
    });

    $('#confirmExitDiscardProviderButton').on('click', function () {
        var modalKey = exitPromptState.activeModalKey;

        if (!modalKey) {
            return;
        }

        if (confirmExitModal) {
            confirmExitModal.hide();
        }

        if (modalKey === 'create') {
            requestModalClose('create', createModal);
            return;
        }

        requestModalClose('edit', editModal);
    });

    $confirmExitSaveButton.on('click', function () {
        var modalKey = exitPromptState.activeModalKey;

        if (!modalKey) {
            return;
        }

        if (confirmExitModal) {
            confirmExitModal.hide();
        }

        if (modalKey === 'create') {
            formState.create.pendingSave = true;
            $createProviderForm.trigger('submit');
            return;
        }

        formState.edit.pendingSave = true;
        $editProviderForm.trigger('submit');
    });

    updatePaginationInfo();
    bindDragScroll();
    loadProviders(1, pagination.page_size);
});
