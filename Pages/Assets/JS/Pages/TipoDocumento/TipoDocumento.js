// =========================================================
// SCRIPT: TIPO DOCUMENTO
// Logica AJAX, modals Bootstrap y eventos del modulo.
// =========================================================
$(function () {
    var $documentTypeTableBody = $('#documentTypeTableBody');
    var $documentTypeSummary = $('#documentTypeSummary');
    var $documentTypePageStatus = $('#documentTypePageStatus');
    var $documentTypePageSizeSelect = $('#documentTypePageSizeSelect');
    var $prevDocumentTypePageButton = $('#prevDocumentTypePageButton');
    var $nextDocumentTypePageButton = $('#nextDocumentTypePageButton');
    var $documentTypeSearchInput = $('#documentTypeSearchInput');
    var $filterDocumentTypeSearchButton = $('#filterDocumentTypeSearchButton');
    var $clearDocumentTypeSearchButton = $('#clearDocumentTypeSearchButton');
    var $openCreateDocumentTypeModalButton = $('#openCreateDocumentTypeModalButton');
    var $openInactiveDocumentTypeModalButton = $('#openInactiveDocumentTypeModalButton');
    var $documentTypeTableShell = $('.category-table-shell');
    var $inactiveDocumentTypeTableShell = $('.inactive-category-table-shell');
    var $inactiveDocumentTypeTableBody = $('#inactiveTipoDocumentoTableBody');
    var $inactiveDocumentTypeSummary = $('#inactiveTipoDocumentoSummary');
    var $inactiveDocumentTypeStatus = $('#inactiveTipoDocumentoStatus');
    var $inactiveDocumentTypeSearchInput = $('#inactiveTipoDocumentoSearchInput');
    var $clearInactiveDocumentTypeSearchButton = $('#clearInactiveTipoDocumentoSearchButton');
    var $createDocumentTypeForm = $('#createTipoDocumentoForm');
    var $editDocumentTypeForm = $('#editTipoDocumentoForm');
    var $deleteDocumentTypeForm = $('#deleteTipoDocumentoForm');
    var $hardDeleteInactiveDocumentTypeForm = $('#hardDeleteInactiveTipoDocumentoForm');
    var $restoreInactiveDocumentTypeForm = $('#restoreInactiveTipoDocumentoForm');
    var $createFeedback = $('#createTipoDocumentoFeedback');
    var $editFeedback = $('#editTipoDocumentoFeedback');
    var $deleteFeedback = $('#deleteTipoDocumentoFeedback');
    var $hardDeleteInactiveFeedback = $('#hardDeleteInactiveTipoDocumentoFeedback');
    var $restoreInactiveFeedback = $('#restoreInactiveTipoDocumentoFeedback');
    var $createModalElement = $('#createTipoDocumentoModal');
    var $editModalElement = $('#editTipoDocumentoModal');
    var $detailModalElement = $('#detailTipoDocumentoModal');
    var $deleteModalElement = $('#deleteTipoDocumentoModal');
    var $confirmExitModalElement = $('#confirmExitTipoDocumentoModal');
    var $infoModalElement = $('#infoTipoDocumentoModal');
    var $inactiveDocumentTypesModalElement = $('#inactiveTipoDocumentoModal');
    var $hardDeleteInactiveDocumentTypeModalElement = $('#hardDeleteInactiveTipoDocumentoModal');
    var $restoreInactiveDocumentTypeModalElement = $('#restoreInactiveTipoDocumentoModal');
    var $confirmExitTitle = $('#confirmExitTipoDocumentoTitle');
    var $confirmExitCopy = $('#confirmExitTipoDocumentoCopy');
    var $confirmExitSaveButton = $('#confirmExitTipoDocumentoSaveButton');
    var $infoModalTitle = $('#infoTipoDocumentoModalTitle');
    var $infoModalMessage = $('#infoTipoDocumentoModalMessage');
    var createModal = null;
    var editModal = null;
    var detailModal = null;
    var deleteModal = null;
    var confirmExitModal = null;
    var infoModal = null;
    var inactiveDocumentTypesModal = null;
    var hardDeleteInactiveDocumentTypeModal = null;
    var restoreInactiveDocumentTypeModal = null;
    var listUrl = 'Api/TipoDocumento/List.php';
    var listInactiveUrl = 'Api/TipoDocumento/ListInactive.php';
    var getUrl = 'Api/TipoDocumento/Get.php';
    var createUrl = 'Api/TipoDocumento/Create.php';
    var updateUrl = 'Api/TipoDocumento/Update.php';
    var deleteUrl = 'Api/TipoDocumento/Delete.php';
    var restoreUrl = 'Api/TipoDocumento/Restore.php';
    var hardDeleteUrl = 'Api/TipoDocumento/HardDelete.php';
    var listRequest = null;
    var inactiveListRequest = null;
    var searchTimer = null;
    var inactiveSearchTimer = null;
    var dragState = { active: false, dragging: false, startX: 0, startY: 0, scrollLeft: 0, scrollTop: 0, pointerId: null };
    var pagination = { page: 1, page_size: 10, total: 0, total_pages: 1, search: '' };
    var formState = {
        create: { initialSnapshot: '', allowClose: false, pendingSave: false },
        edit: { initialSnapshot: '', allowClose: false, pendingSave: false }
    };
    var exitPromptState = { activeModalKey: '' };
    var inactiveState = { search: '', total: 0 };

    // Utilidades base de texto y formato.
    function escapeHtml(value) { return $('<div>').text(value === null ? '' : value).html(); }
    function toTrimmedString(value) { return String(value === null || typeof value === 'undefined' ? '' : value).trim(); }
    function buildJsonPayload($form) {
        var payload = {};

        $.each($form.serializeArray(), function (_, field) {
            payload[field.name] = field.value;
        });

        return payload;
    }
    function isNumericSearch(value) { return /^[0-9]+$/.test(toTrimmedString(value)); }
    function formatDate(dateTime) { return dateTime ? dateTime.replace(' ', ' | ') : 'Sin fecha'; }

    // Gestiona el bloque visual de feedback dentro de los modals.
    function showFeedback($element, message, type) {
        if (!message) {
            $element.addClass('d-none').removeClass('alert-success alert-danger alert-warning alert-info').text('');
            return;
        }
        $element.removeClass('d-none alert-success alert-danger alert-warning alert-info').addClass('alert-' + type).text(message);
    }

    // Aplica el tamano del modal dinamicamente usando jQuery removeClass y addClass.
    function setModalDialogSize($modalElement, sizeClass) {
        var modalSizes = 'modal-sm modal-lg modal-xl';
        var $dialog = $modalElement.find('.modal-dialog');
        if (!$dialog.length) { return; }
        $dialog.removeClass(modalSizes);
        if (sizeClass) { $dialog.addClass(sizeClass); }
    }

    function setButtonLoading($button, isLoading, label) {
        if (!$button.length) { return; }
        if (isLoading) {
            $button.data('original-html', $button.html());
            $button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>' + label);
            return;
        }
        $button.prop('disabled', false).html($button.data('original-html') || label);
    }

    function normalizeDocumentType(documentType) {
        if (!documentType) { return null; }
        return {
            id_tipo_documento: Number(documentType.id_tipo_documento) || 0,
            nombre_tipo_documento: documentType.nombre_tipo_documento || '',
            descripcion: documentType.descripcion || '',
            estado: Number(documentType.estado) || 0,
            created_at: documentType.created_at || '',
            updated_at: documentType.updated_at || ''
        };
    }

    function getModalInstance($element) {
        if (!$element.length || typeof bootstrap === 'undefined' || !bootstrap.Modal) { return null; }
        return bootstrap.Modal.getOrCreateInstance($element[0]);
    }

    function ensureModals() {
        if (createModal === null) { createModal = getModalInstance($createModalElement); }
        if (editModal === null) { editModal = getModalInstance($editModalElement); }
        if (detailModal === null) { detailModal = getModalInstance($detailModalElement); }
        if (deleteModal === null) { deleteModal = getModalInstance($deleteModalElement); }
        if (confirmExitModal === null) { confirmExitModal = getModalInstance($confirmExitModalElement); }
        if (infoModal === null) { infoModal = getModalInstance($infoModalElement); }
        if (inactiveDocumentTypesModal === null) { inactiveDocumentTypesModal = getModalInstance($inactiveDocumentTypesModalElement); }
        if (hardDeleteInactiveDocumentTypeModal === null) { hardDeleteInactiveDocumentTypeModal = getModalInstance($hardDeleteInactiveDocumentTypeModalElement); }
        if (restoreInactiveDocumentTypeModal === null) { restoreInactiveDocumentTypeModal = getModalInstance($restoreInactiveDocumentTypeModalElement); }
    }

    // Define el modo visual de la tabla segun la cantidad de registros por pagina.
    function updateTableMode(pageSize) {
        var tableModes = 'table-size-10 table-size-20 table-size-50';
        $documentTypeTableShell.removeClass(tableModes).addClass('table-size-' + pageSize);
    }
    function getFormSnapshot($form) { return $form.length ? $form.serialize() : ''; }
    function setFormInitialState(modalKey, $form) {
        if (!formState[modalKey]) { return; }
        formState[modalKey].initialSnapshot = getFormSnapshot($form);
        formState[modalKey].allowClose = false;
        formState[modalKey].pendingSave = false;
    }
    function hasUnsavedChanges(modalKey, $form) {
        return formState[modalKey] ? getFormSnapshot($form) !== formState[modalKey].initialSnapshot : false;
    }
    function requestModalClose(modalKey, modalInstance) {
        if (!formState[modalKey]) { return; }
        formState[modalKey].allowClose = true;
        if (modalInstance) { modalInstance.hide(); }
        formState[modalKey].allowClose = false;
        formState[modalKey].pendingSave = false;
        exitPromptState.activeModalKey = '';
    }

    function openExitPrompt(modalKey) {
        ensureModals();
        setModalDialogSize($confirmExitModalElement, 'modal-sm');
        exitPromptState.activeModalKey = modalKey;
        $confirmExitTitle.text(modalKey === 'create' ? 'Salir de crear tipo de documento' : 'Salir de editar tipo de documento');
        $confirmExitCopy.html(
            modalKey === 'create'
                ? 'Has ingresado informacion nueva en el formulario de <strong>crear tipo de documento</strong>. Puedes volver, salir sin guardar o guardar antes de cerrar.'
                : 'Has realizado cambios en el formulario de <strong>editar tipo de documento</strong>. Puedes volver, salir sin guardar o guardar antes de cerrar.'
        );
        $confirmExitSaveButton.text(modalKey === 'create' ? 'Guardar tipo de documento' : 'Guardar cambios');
        if (confirmExitModal) { confirmExitModal.show(); }
    }

    // Muestra avisos cortos de validacion o estado en un modal reutilizable.
    function showInfoModal(title, message) {
        ensureModals();
        setModalDialogSize($infoModalElement, 'modal-sm');
        $infoModalTitle.text(title || 'Aviso');
        $infoModalMessage.text(message || 'Ocurrio un evento que requiere tu atencion.');
        if (infoModal) { infoModal.show(); }
    }

    // Extrae el mensaje JSON devuelto por la API para no mostrar errores genericos.
    function extractResponseMessage(xhr, fallbackMessage) {
        if (xhr && xhr.responseJSON && xhr.responseJSON.message) { return xhr.responseJSON.message; }
        if (xhr && xhr.responseText) {
            try {
                var parsedResponse = JSON.parse(xhr.responseText);
                if (parsedResponse && parsedResponse.message) { return parsedResponse.message; }
            } catch (error) {}
        }
        return fallbackMessage;
    }

    // Valida en cliente los campos principales antes de disparar la peticion AJAX.
    function validateDocumentTypePayload(payload) {
        var nombre = toTrimmedString(payload.nombre_tipo_documento);
        var descripcion = toTrimmedString(payload.descripcion);
        var estado = toTrimmedString(payload.estado);
        if (nombre === '') { return 'Debes ingresar el nombre del tipo de documento.'; }
        if (nombre.length < 2) { return 'El nombre del tipo de documento debe tener al menos 2 caracteres.'; }
        if (descripcion.length > 150) { return 'La descripcion no puede superar los 150 caracteres.'; }
        if (estado !== '0' && estado !== '1') { return 'Debes seleccionar un estado valido para el tipo de documento.'; }
        return '';
    }

    function updateTableHeight(rowCount) {
        var headerHeight = 48, rowHeight = 52, emptyRowHeight = 72;
        var visibleRows = Math.min(Math.max(Number(rowCount) || 0, 0), 10);
        var bodyHeight = visibleRows > 0 ? visibleRows * rowHeight : emptyRowHeight;
        $documentTypeTableShell.addClass('dynamic-height').css('max-height', (headerHeight + bodyHeight + 2) + 'px');
    }
    function updateInactiveTableHeight(rowCount) {
        var headerHeight = 48, rowHeight = 52, emptyRowHeight = 72;
        var visibleRows = Math.min(Math.max(Number(rowCount) || 0, 0), 10);
        var bodyHeight = visibleRows > 0 ? visibleRows * rowHeight : emptyRowHeight;
        $inactiveDocumentTypeTableShell.addClass('dynamic-height').css('max-height', (headerHeight + bodyHeight + 2) + 'px');
    }
    function updateControlsState(isLoading) {
        var currentPage = Number(pagination.page) || 1;
        var totalPages = Number(pagination.total_pages) || 1;
        $documentTypePageSizeSelect.prop('disabled', isLoading);
        $prevDocumentTypePageButton.prop('disabled', isLoading || currentPage <= 1);
        $nextDocumentTypePageButton.prop('disabled', isLoading || currentPage >= totalPages);
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
            $documentTypeSummary.text(hasSearch ? 'No se encontraron tipos de documento con el filtro actual' : 'Mostrando 0 de 0 tipos de documento');
        } else {
            $documentTypeSummary.text('Mostrando ' + startRecord + ' - ' + endRecord + ' de ' + total + ' tipos de documento');
        }
        $documentTypePageStatus.text('Pagina ' + currentPage + ' de ' + totalPages);
        $documentTypePageSizeSelect.val(String(pageSize));
        updateTableMode(pageSize);
    }
    function renderMessageRow(message, textClass) {
        $documentTypeTableBody.html('<tr><td colspan="6" class="text-center py-4 ' + textClass + '">' + escapeHtml(message) + '</td></tr>');
        updateTableHeight(0);
    }
    function renderInactiveMessageRow(message, textClass) {
        $inactiveDocumentTypeTableBody.html('<tr><td colspan="6" class="text-center py-4 ' + textClass + '">' + escapeHtml(message) + '</td></tr>');
        updateInactiveTableHeight(0);
    }
    function buildStatusBadge(estado) {
        return '<span class="badge ' + (Number(estado) === 1 ? 'text-bg-success' : 'text-bg-secondary') + '">' + (Number(estado) === 1 ? 'Activo' : 'Inactivo') + '</span>';
    }
    function buildActionButtons(documentType) {
        return '<div class="category-actions">'
            + '<button type="button" class="btn btn-outline-primary category-action-button js-view-document-type" title="Ver detalle" data-id="' + escapeHtml(documentType.id_tipo_documento) + '"><i class="fas fa-eye"></i></button>'
            + '<button type="button" class="btn btn-outline-warning category-action-button js-edit-document-type" title="Editar" data-id="' + escapeHtml(documentType.id_tipo_documento) + '"><i class="fas fa-edit"></i></button>'
            + '<button type="button" class="btn btn-outline-danger category-action-button js-delete-document-type" title="Eliminar" data-id="' + escapeHtml(documentType.id_tipo_documento) + '"><i class="fas fa-trash"></i></button>'
            + '</div>';
    }
    function buildInactiveActionButtons(documentType) {
        return '<div class="inactive-category-actions">'
            + '<button type="button" class="btn btn-outline-success category-action-button js-restore-document-type" title="Restaurar" data-id="' + escapeHtml(documentType.id_tipo_documento) + '" data-name="' + escapeHtml(documentType.nombre_tipo_documento) + '"><i class="fas fa-undo"></i></button>'
            + '<button type="button" class="btn btn-outline-danger category-action-button js-hard-delete-document-type" title="Eliminar definitivo" data-id="' + escapeHtml(documentType.id_tipo_documento) + '" data-name="' + escapeHtml(documentType.nombre_tipo_documento) + '"><i class="fas fa-trash"></i></button>'
            + '</div>';
    }
    function renderRows(documentTypes) {
        if (!documentTypes.length) { renderMessageRow('No hay tipos de documento registrados.', 'text-muted'); return; }
        var rows = $.map(documentTypes, function (documentType) {
            return '<tr>'
                + '<td>' + escapeHtml(documentType.id_tipo_documento) + '</td>'
                + '<td>' + escapeHtml(documentType.nombre_tipo_documento) + '</td>'
                + '<td>' + escapeHtml(documentType.descripcion || 'Sin descripcion') + '</td>'
                + '<td>' + buildStatusBadge(documentType.estado) + '</td>'
                + '<td>' + escapeHtml(formatDate(documentType.created_at)) + '</td>'
                + '<td>' + buildActionButtons(documentType) + '</td>'
                + '</tr>';
        }).join('');
        $documentTypeTableBody.html(rows);
        updateTableHeight(documentTypes.length);
    }
    function renderInactiveRows(documentTypes) {
        if (!documentTypes.length) { renderInactiveMessageRow('No hay tipos de documento inactivos registrados.', 'text-muted'); return; }
        var rows = $.map(documentTypes, function (documentType) {
            return '<tr>'
                + '<td>' + escapeHtml(documentType.id_tipo_documento) + '</td>'
                + '<td>' + escapeHtml(documentType.nombre_tipo_documento) + '</td>'
                + '<td>' + escapeHtml(documentType.descripcion || 'Sin descripcion') + '</td>'
                + '<td>' + buildStatusBadge(documentType.estado) + '</td>'
                + '<td>' + escapeHtml(formatDate(documentType.deleted_at)) + '</td>'
                + '<td>' + buildInactiveActionButtons(documentType) + '</td>'
                + '</tr>';
        }).join('');
        $inactiveDocumentTypeTableBody.html(rows);
        updateInactiveTableHeight(documentTypes.length);
    }
    function updateInactiveSummary() {
        var total = Number(inactiveState.total) || 0;
        var hasSearch = toTrimmedString(inactiveState.search) !== '';
        if (total === 0) {
            $inactiveDocumentTypeSummary.text(hasSearch ? 'No se encontraron tipos de documento inactivos con el filtro actual' : 'Mostrando 0 tipos de documento inactivos');
            $inactiveDocumentTypeStatus.text('Sin resultados');
            return;
        }
        $inactiveDocumentTypeSummary.text('Mostrando ' + total + ' tipos de documento inactivos');
        $inactiveDocumentTypeStatus.text(hasSearch ? 'Filtro aplicado' : 'Listado completo');
    }
    function bindDragScroll() {
        var interactiveSelector = 'button, a, input, textarea, select, label, .category-actions, .category-action-button';
        var dragThreshold = 8;
        $documentTypeTableShell.on('pointerdown', function (event) {
            if (event.pointerType === 'mouse' && event.button !== 0) { return; }
            if ($(event.target).closest(interactiveSelector).length) { return; }
            dragState.active = true; dragState.dragging = false; dragState.startX = event.clientX; dragState.startY = event.clientY;
            dragState.scrollLeft = this.scrollLeft; dragState.scrollTop = this.scrollTop; dragState.pointerId = event.pointerId;
            if (this.setPointerCapture) { this.setPointerCapture(event.pointerId); }
        });
        $documentTypeTableShell.on('pointermove', function (event) {
            if (!dragState.active) { return; }
            if (!dragState.dragging) {
                if (Math.abs(event.clientX - dragState.startX) < dragThreshold && Math.abs(event.clientY - dragState.startY) < dragThreshold) { return; }
                dragState.dragging = true; $(this).addClass('is-dragging');
            }
            event.preventDefault();
            this.scrollLeft = dragState.scrollLeft - (event.clientX - dragState.startX);
            this.scrollTop = dragState.scrollTop - (event.clientY - dragState.startY);
        });
        $documentTypeTableShell.on('pointerup pointercancel lostpointercapture', function (event) {
            if (dragState.pointerId !== null && typeof event.pointerId !== 'undefined' && dragState.pointerId !== event.pointerId) { return; }
            dragState.active = false; dragState.dragging = false; dragState.pointerId = null; $(this).removeClass('is-dragging');
        });
    }

    function loadDocumentTypeDetails(idTipoDocumento, onSuccess) {
        $.ajax({
            url: getUrl, method: 'GET', dataType: 'json', cache: false,
            data: { id_tipo_documento: Number(idTipoDocumento) || 0 }
        }).done(function (response) {
            if (!response.success || !response.document_type) {
                showInfoModal('Tipo de documento no disponible', response.message || 'No se pudo obtener el detalle del tipo de documento seleccionado.');
                return;
            }
            onSuccess(normalizeDocumentType(response.document_type));
        }).fail(function (xhr) {
            showInfoModal('No se pudo consultar el tipo de documento', extractResponseMessage(xhr, 'Ocurrio un problema al consultar el tipo de documento. Intenta nuevamente.'));
        });
    }

    // Define si la busqueda se resuelve como detalle por ID o como listado filtrado.
    function runMainSearch() {
        var searchValue = toTrimmedString($documentTypeSearchInput.val());
        if (searchValue === '') {
            showInfoModal('Campo de busqueda vacio', 'Debes ingresar un ID o un nombre antes de hacer clic en Filtrar.');
            $documentTypeSearchInput.trigger('focus');
            return;
        }
        pagination.search = searchValue;
        if (isNumericSearch(searchValue)) {
            ensureModals();
            loadDocumentTypeDetails(Number(searchValue), function (documentType) {
                fillDetailModal(documentType);
                if (detailModal) { detailModal.show(); }
            });
            return;
        }
        loadDocumentTypes(1, pagination.page_size);
    }

    function fillDetailModal(documentType) {
        $('#detailTipoDocumentoId').text(documentType.id_tipo_documento);
        $('#detailTipoDocumentoName').text(documentType.nombre_tipo_documento);
        $('#detailTipoDocumentoDescription').text(documentType.descripcion || 'Sin descripcion');
        $('#detailTipoDocumentoStatus').html(buildStatusBadge(documentType.estado));
        $('#detailTipoDocumentoCreated').text(formatDate(documentType.created_at));
        $('#detailTipoDocumentoUpdated').text(formatDate(documentType.updated_at));
    }
    function fillEditModal(documentType) {
        $('#edit_id_tipo_documento').val(documentType.id_tipo_documento);
        $('#edit_id_tipo_documento_readonly').val(documentType.id_tipo_documento);
        $('#edit_nombre_tipo_documento').val(documentType.nombre_tipo_documento);
        $('#edit_tipo_documento_descripcion').val(documentType.descripcion);
        $('#edit_tipo_documento_estado').val(String(documentType.estado));
    }
    function prepareDeleteModal(documentType) {
        $('#delete_id_tipo_documento').val(documentType.id_tipo_documento);
        $('#deleteTipoDocumentoName').text(documentType.nombre_tipo_documento);
    }
    function prepareHardDeleteInactiveModal(documentType) {
        $('#hard_delete_id_tipo_documento').val(documentType.id_tipo_documento);
        $('#hardDeleteInactiveTipoDocumentoName').text(documentType.nombre_tipo_documento);
    }
    function prepareRestoreInactiveModal(documentType) {
        $('#restore_id_tipo_documento').val(documentType.id_tipo_documento);
        $('#restoreInactiveTipoDocumentoName').text(documentType.nombre_tipo_documento);
    }

    function loadDocumentTypes(page, pageSize) {
        if (listRequest) { listRequest.abort(); listRequest = null; }
        pagination.page = Number(page) || 1;
        pagination.page_size = Number(pageSize) || 10;
        updateControlsState(true);
        listRequest = $.ajax({
            url: listUrl, method: 'GET', dataType: 'json', cache: false,
            data: { page: pagination.page, page_size: pagination.page_size, search: toTrimmedString(pagination.search) }
        }).done(function (response) {
            if (!response.success) { renderMessageRow(response.message || 'No se pudo cargar la tabla.', 'text-danger'); return; }
            pagination = $.extend({}, pagination, response.pagination || {});
            pagination.search = response.search || toTrimmedString(pagination.search);
            renderRows(response.document_types || []);
            updatePaginationInfo();
        }).fail(function (xhr, textStatus) {
            if (textStatus === 'abort') { return; }
            renderMessageRow(extractResponseMessage(xhr, 'Ocurrio un problema al cargar los tipos de documento.'), 'text-danger');
        }).always(function () {
            listRequest = null; updateControlsState(false);
        });
    }

    // Metodo clave del listado de tipos de documento inactivos consumido por AJAX.
    function loadInactiveDocumentTypes() {
        if (inactiveListRequest) { inactiveListRequest.abort(); inactiveListRequest = null; }
        renderInactiveMessageRow('Cargando tipos de documento inactivos...', 'text-muted');
        inactiveListRequest = $.ajax({
            url: listInactiveUrl, method: 'GET', dataType: 'json', cache: false,
            data: { search: toTrimmedString(inactiveState.search) }
        }).done(function (response) {
            if (!response.success) { renderInactiveMessageRow(response.message || 'No se pudo cargar la tabla de inactivos.', 'text-danger'); return; }
            inactiveState.total = Number(response.total) || 0;
            inactiveState.search = response.search || toTrimmedString(inactiveState.search);
            renderInactiveRows(response.document_types || []);
            updateInactiveSummary();
        }).fail(function (xhr, textStatus) {
            if (textStatus === 'abort') { return; }
            renderInactiveMessageRow(extractResponseMessage(xhr, 'Ocurrio un problema al cargar los tipos de documento inactivos.'), 'text-danger');
        }).always(function () { inactiveListRequest = null; });
    }

    $documentTypePageSizeSelect.on('change', function () { loadDocumentTypes(1, Number($(this).val()) || 10); });
    $documentTypeSearchInput.on('input', function () {
        var currentValue = toTrimmedString($(this).val());
        pagination.search = currentValue;
        if (searchTimer) { clearTimeout(searchTimer); }
        if (currentValue !== '' && isNumericSearch(currentValue)) { return; }
        searchTimer = window.setTimeout(function () { loadDocumentTypes(1, pagination.page_size); }, 350);
    });
    $documentTypeSearchInput.on('keydown', function (event) { if (event.key === 'Enter') { event.preventDefault(); runMainSearch(); } });
    $filterDocumentTypeSearchButton.on('click', function () { runMainSearch(); });
    $clearDocumentTypeSearchButton.on('click', function () {
        pagination.search = ''; $documentTypeSearchInput.val(''); loadDocumentTypes(1, pagination.page_size);
    });
    $openInactiveDocumentTypeModalButton.on('click', function () {
        ensureModals(); setModalDialogSize($inactiveDocumentTypesModalElement, 'modal-xl');
        inactiveState.search = ''; $inactiveDocumentTypeSearchInput.val(''); updateInactiveSummary(); loadInactiveDocumentTypes();
        if (inactiveDocumentTypesModal) { inactiveDocumentTypesModal.show(); }
    });
    $inactiveDocumentTypeSearchInput.on('input', function () {
        inactiveState.search = toTrimmedString($(this).val());
        if (inactiveSearchTimer) { clearTimeout(inactiveSearchTimer); }
        inactiveSearchTimer = window.setTimeout(function () { loadInactiveDocumentTypes(); }, 350);
    });
    $clearInactiveDocumentTypeSearchButton.on('click', function () {
        inactiveState.search = ''; $inactiveDocumentTypeSearchInput.val(''); loadInactiveDocumentTypes();
    });
    $prevDocumentTypePageButton.on('click', function (event) {
        event.preventDefault(); if (listRequest || pagination.page <= 1) { return; } loadDocumentTypes(pagination.page - 1, pagination.page_size);
    });
    $nextDocumentTypePageButton.on('click', function (event) {
        event.preventDefault(); if (listRequest || pagination.page >= pagination.total_pages) { return; } loadDocumentTypes(pagination.page + 1, pagination.page_size);
    });
    $openCreateDocumentTypeModalButton.on('click', function () {
        ensureModals(); setModalDialogSize($createModalElement, 'modal-lg');
        if ($createDocumentTypeForm.length) { $createDocumentTypeForm[0].reset(); setFormInitialState('create', $createDocumentTypeForm); }
        showFeedback($createFeedback, '', 'info');
        if (createModal) { createModal.show(); }
    });
    $documentTypeTableBody.on('click', '.js-view-document-type', function () {
        ensureModals(); setModalDialogSize($detailModalElement, 'modal-lg');
        loadDocumentTypeDetails($(this).data('id'), function (documentType) { fillDetailModal(documentType); if (detailModal) { detailModal.show(); } });
    });
    $documentTypeTableBody.on('click', '.js-edit-document-type', function () {
        ensureModals(); setModalDialogSize($editModalElement, 'modal-lg');
        loadDocumentTypeDetails($(this).data('id'), function (documentType) {
            fillEditModal(documentType); setFormInitialState('edit', $editDocumentTypeForm); showFeedback($editFeedback, '', 'info');
            if (editModal) { editModal.show(); }
        });
    });
    $documentTypeTableBody.on('click', '.js-delete-document-type', function () {
        ensureModals(); setModalDialogSize($deleteModalElement, 'modal-sm');
        loadDocumentTypeDetails($(this).data('id'), function (documentType) {
            prepareDeleteModal(documentType); showFeedback($deleteFeedback, '', 'info');
            if (deleteModal) { deleteModal.show(); }
        });
    });
    $inactiveDocumentTypeTableBody.on('click', '.js-restore-document-type', function () {
        var documentType = { id_tipo_documento: Number($(this).data('id')) || 0, nombre_tipo_documento: $(this).data('name') || '' };
        ensureModals(); setModalDialogSize($restoreInactiveDocumentTypeModalElement, 'modal-sm');
        prepareRestoreInactiveModal(documentType); showFeedback($restoreInactiveFeedback, '', 'info');
        if (restoreInactiveDocumentTypeModal) { restoreInactiveDocumentTypeModal.show(); }
    });
    $inactiveDocumentTypeTableBody.on('click', '.js-hard-delete-document-type', function () {
        var documentType = { id_tipo_documento: Number($(this).data('id')) || 0, nombre_tipo_documento: $(this).data('name') || '' };
        ensureModals(); setModalDialogSize($hardDeleteInactiveDocumentTypeModalElement, 'modal-sm');
        prepareHardDeleteInactiveModal(documentType); showFeedback($hardDeleteInactiveFeedback, '', 'info');
        if (hardDeleteInactiveDocumentTypeModal) { hardDeleteInactiveDocumentTypeModal.show(); }
    });

    $createDocumentTypeForm.on('submit', function (event) {
        var $submitButton = $createDocumentTypeForm.find('button[type="submit"]');
        var formData = {
            nombre_tipo_documento: $('#create_nombre_tipo_documento').val(),
            descripcion: $('#create_tipo_documento_descripcion').val(),
            estado: $('#create_tipo_documento_estado').val()
        };
        var validationMessage = validateDocumentTypePayload(formData);
        event.preventDefault(); showFeedback($createFeedback, '', 'info');
        if (validationMessage !== '') { showFeedback($createFeedback, validationMessage, 'warning'); showInfoModal('Datos incompletos', validationMessage); return; }
        setButtonLoading($submitButton, true, 'Guardando...');
        $.ajax({ url: createUrl, method: 'POST', dataType: 'json', contentType: 'application/json; charset=UTF-8', data: JSON.stringify(buildJsonPayload($createDocumentTypeForm)) })
            .done(function (response) {
                if (!response.success) { showFeedback($createFeedback, response.message || 'No se pudo registrar el tipo de documento.', 'danger'); return; }
                showFeedback($createFeedback, response.message || 'Tipo de documento registrado correctamente.', 'success');
                $createDocumentTypeForm[0].reset(); setFormInitialState('create', $createDocumentTypeForm); formState.create.allowClose = true; loadDocumentTypes(1, pagination.page_size);
                window.setTimeout(function () { if (createModal) { createModal.hide(); } }, 650);
            }).fail(function (xhr) {
                showFeedback($createFeedback, extractResponseMessage(xhr, 'No se pudo registrar el tipo de documento en este momento.'), 'danger');
            }).always(function () { setButtonLoading($submitButton, false, 'Guardar tipo de documento'); });
    });
    $editDocumentTypeForm.on('submit', function (event) {
        var $submitButton = $editDocumentTypeForm.find('button[type="submit"]');
        var formData = {
            nombre_tipo_documento: $('#edit_nombre_tipo_documento').val(),
            descripcion: $('#edit_tipo_documento_descripcion').val(),
            estado: $('#edit_tipo_documento_estado').val()
        };
        var validationMessage = validateDocumentTypePayload(formData);
        event.preventDefault(); showFeedback($editFeedback, '', 'info');
        if (validationMessage !== '') { showFeedback($editFeedback, validationMessage, 'warning'); showInfoModal('Datos incompletos', validationMessage); return; }
        setButtonLoading($submitButton, true, 'Actualizando...');
        $.ajax({ url: updateUrl, method: 'PUT', dataType: 'json', contentType: 'application/json; charset=UTF-8', data: JSON.stringify(buildJsonPayload($editDocumentTypeForm)) })
            .done(function (response) {
                if (!response.success) { showFeedback($editFeedback, response.message || 'No se pudo actualizar el tipo de documento.', 'danger'); return; }
                showFeedback($editFeedback, response.message || 'Tipo de documento actualizado correctamente.', 'success');
                setFormInitialState('edit', $editDocumentTypeForm); formState.edit.allowClose = true; loadDocumentTypes(pagination.page, pagination.page_size);
                window.setTimeout(function () { if (editModal) { editModal.hide(); } }, 650);
            }).fail(function (xhr) {
                showFeedback($editFeedback, extractResponseMessage(xhr, 'No se pudo actualizar el tipo de documento en este momento.'), 'danger');
            }).always(function () { setButtonLoading($submitButton, false, 'Guardar cambios'); });
    });
    $deleteDocumentTypeForm.on('submit', function (event) {
        var $submitButton = $deleteDocumentTypeForm.find('button[type="submit"]');
        event.preventDefault(); showFeedback($deleteFeedback, '', 'info'); setButtonLoading($submitButton, true, 'Eliminando...');
        $.ajax({ url: deleteUrl, method: 'DELETE', dataType: 'json', contentType: 'application/json; charset=UTF-8', data: JSON.stringify(buildJsonPayload($deleteDocumentTypeForm)) })
            .done(function (response) {
                if (!response.success) { showFeedback($deleteFeedback, response.message || 'No se pudo eliminar el tipo de documento.', 'danger'); return; }
                showFeedback($deleteFeedback, response.message || 'Tipo de documento eliminado correctamente.', 'success');
                loadDocumentTypes(pagination.page, pagination.page_size);
                window.setTimeout(function () { if (deleteModal) { deleteModal.hide(); } }, 650);
            }).fail(function (xhr) {
                showFeedback($deleteFeedback, extractResponseMessage(xhr, 'No se pudo eliminar el tipo de documento en este momento.'), 'danger');
            }).always(function () { setButtonLoading($submitButton, false, 'Confirmar eliminacion'); });
    });
    $hardDeleteInactiveDocumentTypeForm.on('submit', function (event) {
        var $submitButton = $hardDeleteInactiveDocumentTypeForm.find('button[type="submit"]');
        event.preventDefault(); showFeedback($hardDeleteInactiveFeedback, '', 'info'); setButtonLoading($submitButton, true, 'Eliminando...');
        $.ajax({ url: hardDeleteUrl, method: 'DELETE', dataType: 'json', contentType: 'application/json; charset=UTF-8', data: JSON.stringify(buildJsonPayload($hardDeleteInactiveDocumentTypeForm)) })
            .done(function (response) {
                if (!response.success) { showFeedback($hardDeleteInactiveFeedback, response.message || 'No se pudo eliminar definitivamente.', 'danger'); return; }
                showFeedback($hardDeleteInactiveFeedback, response.message || 'Tipo de documento eliminado definitivamente.', 'success');
                loadInactiveDocumentTypes(); loadDocumentTypes(pagination.page, pagination.page_size);
                window.setTimeout(function () { if (hardDeleteInactiveDocumentTypeModal) { hardDeleteInactiveDocumentTypeModal.hide(); } }, 900);
            }).fail(function (xhr) {
                showFeedback($hardDeleteInactiveFeedback, extractResponseMessage(xhr, 'No se pudo eliminar definitivamente el tipo de documento en este momento.'), 'danger');
            }).always(function () { setButtonLoading($submitButton, false, 'Confirmar eliminacion'); });
    });
    $restoreInactiveDocumentTypeForm.on('submit', function (event) {
        var $submitButton = $restoreInactiveDocumentTypeForm.find('button[type="submit"]');
        event.preventDefault(); showFeedback($restoreInactiveFeedback, '', 'info'); setButtonLoading($submitButton, true, 'Restaurando...');
        $.ajax({ url: restoreUrl, method: 'PUT', dataType: 'json', contentType: 'application/json; charset=UTF-8', data: JSON.stringify(buildJsonPayload($restoreInactiveDocumentTypeForm)) })
            .done(function (response) {
                if (!response.success) { showFeedback($restoreInactiveFeedback, response.message || 'No se pudo restaurar el tipo de documento.', 'danger'); return; }
                showFeedback($restoreInactiveFeedback, response.message || 'Tipo de documento restaurado correctamente.', 'success');
                loadInactiveDocumentTypes(); loadDocumentTypes(1, pagination.page_size);
                window.setTimeout(function () { if (restoreInactiveDocumentTypeModal) { restoreInactiveDocumentTypeModal.hide(); } }, 750);
            }).fail(function (xhr) {
                showFeedback($restoreInactiveFeedback, extractResponseMessage(xhr, 'No se pudo restaurar el tipo de documento en este momento.'), 'danger');
            }).always(function () { setButtonLoading($submitButton, false, 'Restaurar tipo de documento'); });
    });
    $createModalElement.on('hide.bs.modal', function (event) {
        if (formState.create.allowClose || !hasUnsavedChanges('create', $createDocumentTypeForm)) { return; }
        event.preventDefault(); openExitPrompt('create');
    });
    $editModalElement.on('hide.bs.modal', function (event) {
        if (formState.edit.allowClose || !hasUnsavedChanges('edit', $editDocumentTypeForm)) { return; }
        event.preventDefault(); openExitPrompt('edit');
    });
    $createModalElement.on('hidden.bs.modal', function () { formState.create.allowClose = false; formState.create.pendingSave = false; });
    $editModalElement.on('hidden.bs.modal', function () { formState.edit.allowClose = false; formState.edit.pendingSave = false; });
    $('#confirmExitTipoDocumentoDiscardButton').on('click', function () {
        var modalKey = exitPromptState.activeModalKey; if (!modalKey) { return; }
        if (confirmExitModal) { confirmExitModal.hide(); }
        if (modalKey === 'create') { requestModalClose('create', createModal); return; }
        requestModalClose('edit', editModal);
    });
    $confirmExitSaveButton.on('click', function () {
        var modalKey = exitPromptState.activeModalKey; if (!modalKey) { return; }
        if (confirmExitModal) { confirmExitModal.hide(); }
        if (modalKey === 'create') { formState.create.pendingSave = true; $createDocumentTypeForm.trigger('submit'); return; }
        formState.edit.pendingSave = true; $editDocumentTypeForm.trigger('submit');
    });

    updatePaginationInfo();
    bindDragScroll();
    loadDocumentTypes(1, pagination.page_size);
});
