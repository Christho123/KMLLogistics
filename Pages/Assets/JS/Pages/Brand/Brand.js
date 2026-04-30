// Logica AJAX adaptada para el modulo de Marcas.
// =========================================================
$(function () {
    var $brandTableBody = $('#brandTableBody');
    var $brandSummary = $('#brandSummary');
    var $brandPageStatus = $('#brandPageStatus');
    var $brandPageSizeSelect = $('#brandPageSizeSelect');
    var $prevBrandPageButton = $('#prevBrandPageButton');
    var $nextBrandPageButton = $('#nextBrandPageButton');
    var $brandSearchInput = $('#brandSearchInput');
    var $filterBrandSearchButton = $('#filterBrandSearchButton');
    var $clearBrandSearchButton = $('#clearBrandSearchButton');
    var $openCreateBrandModalButton = $('#openCreateBrandModalButton');
    var $openInactiveBrandsModalButton = $('#openInactiveBrandsModalButton');
    var $brandTableShell = $('.brand-table-shell');
    var $inactiveBrandTableShell = $('.inactive-brand-table-shell');
    var $inactiveBrandTableBody = $('#inactiveBrandTableBody');
    var $inactiveBrandSummary = $('#inactiveBrandSummary');
    var $inactiveBrandStatus = $('#inactiveBrandStatus');
    var $inactiveBrandSearchInput = $('#inactiveBrandSearchInput');
    var $clearInactiveBrandSearchButton = $('#clearInactiveBrandSearchButton');
    var $createBrandForm = $('#createBrandForm');
    var $editBrandForm = $('#editBrandForm');
    var $deleteBrandForm = $('#deleteBrandForm');
    var $hardDeleteInactiveBrandForm = $('#hardDeleteInactiveBrandForm');
    var $restoreInactiveBrandForm = $('#restoreInactiveBrandForm');
    var $createFeedback = $('#createBrandFeedback');
    var $editFeedback = $('#editBrandFeedback');
    var $deleteFeedback = $('#deleteBrandFeedback');
    var $hardDeleteInactiveFeedback = $('#hardDeleteInactiveFeedback');
    var $restoreInactiveFeedback = $('#restoreInactiveFeedback');
    var $createModalElement = $('#createBrandModal');
    var $editModalElement = $('#editBrandModal');
    var $detailModalElement = $('#detailBrandModal');
    var $deleteModalElement = $('#deleteBrandModal');
    var $confirmExitModalElement = $('#confirmExitBrandModal');
    var $infoModalElement = $('#infoBrandModal');
    var $inactiveBrandsModalElement = $('#inactiveBrandsModal');
    var $hardDeleteInactiveBrandModalElement = $('#hardDeleteInactiveBrandModal');
    var $restoreInactiveBrandModalElement = $('#restoreInactiveBrandModal');
    var $confirmExitTitle = $('#confirmExitBrandTitle');
    var $confirmExitCopy = $('#confirmExitBrandCopy');
    var $confirmExitSaveButton = $('#confirmBrandExitSaveButton');
    var $infoModalTitle = $('#infoBrandModalTitle');
    var $infoModalMessage = $('#infoBrandModalMessage');
    var createModal = null;
    var editModal = null;
    var detailModal = null;
    var deleteModal = null;
    var confirmExitModal = null;
    var infoModal = null;
    var inactiveBrandsModal = null;
    var hardDeleteInactiveBrandModal = null;
    var restoreInactiveBrandModal = null;
    var listUrl = 'Api/Brand/List.php';
    var listInactiveUrl = 'Api/Brand/ListInactive.php';
    var getUrl = 'Api/Brand/Get.php';
    var createUrl = 'Api/Brand/Create.php';
    var updateUrl = 'Api/Brand/Update.php';
    var deleteUrl = 'Api/Brand/Delete.php';
    var restoreUrl = 'Api/Brand/Restore.php';
    var hardDeleteUrl = 'Api/Brand/HardDelete.php';
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
        return $('<div>').text(value === null ? '' : value).html();
    }

    function toTrimmedString(value) {
        return String(value === null || typeof value === 'undefined' ? '' : value).trim();
    }

    function buildJsonPayload($form) {
        var payload = {};

        $.each($form.serializeArray(), function (_, field) {
            payload[field.name] = field.value;
        });

        return payload;
    }

    function isNumericSearch(value) {
        return /^[0-9]+$/.test(toTrimmedString(value));
    }

    function formatDate(dateTime) {
        if (!dateTime) {
            return 'Sin fecha';
        }

        return dateTime.replace(' ', ' | ');
    }

    // Gestiona el bloque visual de feedback dentro de los modals.
    function showFeedback($element, message, type) {
        if (!message) {
            $element.addClass('d-none').removeClass('alert-success alert-danger alert-warning alert-info').text('');
            return;
        }

        $element
            .removeClass('d-none alert-success alert-danger alert-warning alert-info')
            .addClass('alert-' + type)
            .text(message);
    }

    // Aplica el tamano del modal dinamicamente usando jQuery removeClass y addClass.
    // Indicacion del profesor: controlar modal-sm, modal-lg y modal-xl desde JS.
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

    function normalizeBrand(brand) {
        if (!brand) {
            return null;
        }

        return {
            id_marca: Number(brand.id_marca) || 0,
            nombre_marca: brand.nombre_marca || '',
            id_proveedor: Number(brand.id_proveedor) || 0,
            nombre_proveedor: brand.nombre_proveedor || '',
            estado: Number(brand.estado) || 0,
            created_at: brand.created_at || '',
            updated_at: brand.updated_at || '',
            deleted_at: brand.deleted_at || ''
        };
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

        if (inactiveBrandsModal === null) {
            inactiveBrandsModal = getModalInstance($inactiveBrandsModalElement);
        }

        if (hardDeleteInactiveBrandModal === null) {
            hardDeleteInactiveBrandModal = getModalInstance($hardDeleteInactiveBrandModalElement);
        }

        if (restoreInactiveBrandModal === null) {
            restoreInactiveBrandModal = getModalInstance($restoreInactiveBrandModalElement);
        }
    }

    function updateTableMode(pageSize) {
        var tableModes = 'table-size-10 table-size-20 table-size-50';
        $brandTableShell.removeClass(tableModes).addClass('table-size-' + pageSize);
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

    function requestModalClose(modalKey, modalInstance, $form) {
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
        $confirmExitTitle.text(modalKey === 'create' ? 'Salir de crear marca' : 'Salir de editar marca');
        $confirmExitCopy.html(
            modalKey === 'create'
                ? 'Has ingresado informacion nueva en el formulario de <strong>crear marca</strong>. Puedes volver, salir sin guardar o guardar antes de cerrar.'
                : 'Has realizado cambios en el formulario de <strong>editar marca</strong>. Puedes volver, salir sin guardar o guardar antes de cerrar.'
        );

        if ($confirmExitSaveButton.length) {
            $confirmExitSaveButton.text(modalKey === 'create' ? 'Guardar marca' : 'Guardar cambios');
        }

        if (confirmExitModal) {
            confirmExitModal.show();
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

    // Extrae el mensaje JSON devuelto por la API para no mostrar errores genericos.
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
                // La respuesta no vino como JSON valido.
            }
        }

        return fallbackMessage;
    }

    // Valida en cliente los campos principales antes de disparar la peticion AJAX.
    function validateBrandPayload(payload) {
        var nombre = toTrimmedString(payload.nombre_marca);
        var idProveedor = Number(payload.id_proveedor);
        var estado = toTrimmedString(payload.estado);

        if (nombre === '') {
            return 'Debes ingresar el nombre de la marca.';
        }

        if (nombre.length < 2) {
            return 'El nombre de la marca debe tener al menos 2 caracteres.';
        }

        if (idProveedor === 0) {
            return 'Debes seleccionar un proveedor para la marca.';
        }

        if (estado !== '0' && estado !== '1') {
            return 'Debes seleccionar un estado valido para la marca.';
        }

        return '';
    }

    function updateTableHeight(rowCount) {
        var headerHeight = 48;
        var rowHeight = 52;
        var emptyRowHeight = 72;
        var visibleRows = Math.min(Math.max(Number(rowCount) || 0, 0), 10);
        var bodyHeight = visibleRows > 0 ? visibleRows * rowHeight : emptyRowHeight;
        var maxHeight = headerHeight + bodyHeight + 2;

        $brandTableShell
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

        $inactiveBrandTableShell
            .addClass('dynamic-height')
            .css('max-height', maxHeight + 'px');
    }

    function updateControlsState(isLoading) {
        var currentPage = Number(pagination.page) || 1;
        var totalPages = Number(pagination.total_pages) || 1;

        $brandPageSizeSelect.prop('disabled', isLoading);
        $prevBrandPageButton.prop('disabled', isLoading || currentPage <= 1);
        $nextBrandPageButton.prop('disabled', isLoading || currentPage >= totalPages);
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
            $brandSummary.text(hasSearch ? 'No se encontraron marcas con el filtro actual' : 'Mostrando 0 de 0 marcas');
        } else {
            $brandSummary.text('Mostrando ' + startRecord + ' - ' + endRecord + ' de ' + total + ' marcas');
        }

        $brandPageStatus.text('Pagina ' + currentPage + ' de ' + totalPages);
        $brandPageSizeSelect.val(String(pageSize));
        updateTableMode(pageSize);
    }

    function renderMessageRow(message, textClass) {
        $brandTableBody.html(
            '<tr><td colspan="6" class="text-center py-4 ' + textClass + '">' + escapeHtml(message) + '</td></tr>'
        );
        updateTableHeight(0);
    }

    function buildStatusBadge(estado) {
        var isActive = Number(estado) === 1;
        var badgeClass = isActive ? 'text-bg-success' : 'text-bg-secondary';
        var badgeText = isActive ? 'Activo' : 'Inactivo';

        return '<span class="badge ' + badgeClass + '">' + badgeText + '</span>';
    }

    function buildActionButtons(brand) {
        return '' +
            '<div class="brand-actions">' +
                '<button type="button" class="btn btn-outline-primary brand-action-button js-view-brand" title="Ver detalle" data-id="' + escapeHtml(brand.id_marca) + '">' +
                    '<i class="fas fa-eye"></i>' +
                '</button>' +
                '<button type="button" class="btn btn-outline-warning brand-action-button js-edit-brand" title="Editar" data-id="' + escapeHtml(brand.id_marca) + '">' +
                    '<i class="fas fa-edit"></i>' +
                '</button>' +
                '<button type="button" class="btn btn-outline-danger brand-action-button js-delete-brand" title="Eliminar" data-id="' + escapeHtml(brand.id_marca) + '" data-name="' + escapeHtml(brand.nombre_marca) + '">' +
                    '<i class="fas fa-trash"></i>' +
                '</button>' +
            '</div>';
    }

    function buildInactiveActionButtons(brand) {
        return '' +
            '<div class="inactive-brand-actions">' +
                '<button type="button" class="btn btn-outline-success brand-action-button js-restore-brand" title="Restaurar" data-id="' + escapeHtml(brand.id_marca) + '">' +
                    '<i class="fas fa-undo"></i>' +
                '</button>' +
                '<button type="button" class="btn btn-outline-danger brand-action-button js-hard-delete-brand" title="Eliminar definitivo" data-id="' + escapeHtml(brand.id_marca) + '" data-name="' + escapeHtml(brand.nombre_marca) + '">' +
                    '<i class="fas fa-trash"></i>' +
                '</button>' +
            '</div>';
    }

    function renderRows(brands) {
        if (!brands.length) {
            $brandTableBody.html('<tr><td colspan="6" class="text-center py-4 text-muted">No se encontraron marcas.</td></tr>');
            return;
        }

        var rows = $.map(brands, function (brand) {
            var statusBadge = buildStatusBadge(brand.estado);

            return '<tr>' +
                '<td>' + brand.id_marca + '</td>' +
                '<td>' + escapeHtml(brand.nombre_marca) + '</td>' + // Anadido el nombre de la marca
                '<td>' + (brand.nombre_proveedor || 'No asignado') + '</td>' +
                '<td>' + statusBadge + '</td>' +
                '<td>' + (brand.created_at || '---') + '</td>' +
                '<td>' + buildActionButtons(brand) + '</td>' + // Usar buildActionButtons
                '</tr>';
        }).join('');

        $brandTableBody.html(rows);
        updateTableHeight(brands.length);
    }

    function renderInactiveMessageRow(message, textClass) {
        $inactiveBrandTableBody.html(
            '<tr><td colspan="6" class="text-center py-4 ' + textClass + '">' + escapeHtml(message) + '</td></tr>'
        );
        updateInactiveTableHeight(0);
    }

    function renderInactiveRows(brands) {
        if (!brands.length) {
            renderInactiveMessageRow('No hay marcas inactivas registradas.', 'text-muted');
            return;
        }

        var rows = $.map(brands, function (brand) {
            return '' +
                '<tr>' +
                    '<td>' + escapeHtml(brand.id_marca) + '</td>' +
                    '<td>' + escapeHtml(brand.nombre_marca) + '</td>' +
                    '<td>' + escapeHtml(brand.nombre_proveedor || 'N/A') + '</td>' +
                    '<td>' + buildStatusBadge(brand.estado) + '</td>' +
                    '<td>' + escapeHtml(formatDate(brand.deleted_at)) + '</td>' +
                    '<td>' + buildInactiveActionButtons(brand) + '</td>' +
                '</tr>';
        }).join('');

        $inactiveBrandTableBody.html(rows);
        updateInactiveTableHeight(brands.length);
    }

    function updateInactiveSummary() {
        var total = Number(inactiveState.total) || 0;
        var hasSearch = toTrimmedString(inactiveState.search) !== '';

        if (total === 0) {
            $inactiveBrandSummary.text(hasSearch ? 'No se encontraron marcas inactivas con el filtro actual' : 'Mostrando 0 marcas inactivas');
            $inactiveBrandStatus.text('Sin resultados');
            return;
        }

        $inactiveBrandSummary.text('Mostrando ' + total + ' marcas inactivas');
        $inactiveBrandStatus.text(hasSearch ? 'Filtro aplicado' : 'Listado completo');
    }

    function bindDragScroll() {
        var interactiveSelector = 'button, a, input, textarea, select, label, .brand-actions, .brand-action-button';
        var dragThreshold = 8;

        $brandTableShell.on('pointerdown', function (event) {
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

        $brandTableShell.on('pointermove', function (event) {
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

        $brandTableShell.on('pointerup pointercancel lostpointercapture', function (event) {
            if (dragState.pointerId !== null && typeof event.pointerId !== 'undefined' && dragState.pointerId !== event.pointerId) {
                return;
            }

            dragState.active = false;
            dragState.dragging = false;
            dragState.pointerId = null;
            $(this).removeClass('is-dragging');
        });
    }

    // Solicita el detalle de una marca por AJAX y ejecuta un callback al responder.
    function loadBrandDetails(brandId, onSuccess) {
        $.ajax({
            url: getUrl,
            method: 'GET',
            dataType: 'json',
            cache: false,
            data: {
                id_marca: Number(brandId) || 0
            }
        })
            .done(function (response) {
                if (!response.success || !response.brand) {
                    showInfoModal('Marca no disponible', response.message || 'No se pudo obtener el detalle de la marca seleccionada.');
                    return;
                }

                onSuccess(normalizeBrand(response.brand));
            })
            .fail(function (xhr) {
                showInfoModal('No se pudo consultar la marca', extractResponseMessage(xhr, 'Ocurrio un problema al consultar la marca. Intenta nuevamente.'));
            });
    }

    // Define si la busqueda se resuelve como detalle por ID o como listado filtrado.
    function runMainSearch() {
        var searchValue = toTrimmedString($brandSearchInput.val());

        if (searchValue === '') {
            showInfoModal('Campo de busqueda vacio', 'Debes ingresar un ID o un nombre antes de hacer clic en Filtrar.');
            $brandSearchInput.trigger('focus');
            return;
        }

        pagination.search = searchValue;

        if (isNumericSearch(searchValue)) {
            ensureModals();
            loadBrandDetails(Number(searchValue), function (brand) {
                fillDetailModal(brand);

                if (detailModal) {
                    detailModal.show();
                }
            });
            return;
        }

        loadBrands(1, pagination.page_size);
    }

    function fillDetailModal(brand) {
        $('#detailBrandId').text(brand.id_marca);
        $('#detailBrandName').text(brand.nombre_marca);
        document.getElementById("detailSupplierName").textContent = brand.nombre_proveedor ?? "";
        $('#detailBrandStatus').html(buildStatusBadge(brand.estado));
        $('#detailBrandCreated').text(formatDate(brand.created_at));
        $('#detailBrandUpdated').text(formatDate(brand.updated_at));
    }

    function fillEditModal(brand) {
        $('#edit_id_marca').val(brand.id_marca);
        $('#edit_id_marca_readonly').val(brand.id_marca);
        $('#edit_nombre_marca').val(brand.nombre_marca);
        $('#edit_id_proveedor').val(brand.id_proveedor);
        $('#edit_estado').val(String(brand.estado));
    }

    function prepareDeleteModal(brand) {
        $('#delete_id_marca').val(brand.id_marca);
        $('#deleteBrandName').text(brand.nombre_marca);
        $('#deleteBrandProveedor').text(brand.nombre_proveedor || 'Sin proveedor registrado.');
    }

    function prepareHardDeleteInactiveModal(brand) {
        $('#hard_delete_id_marca').val(brand.id_marca);
        $('#hardDeleteInactiveName').text(brand.nombre_marca);
    }

    function prepareRestoreInactiveModal(brand) {
        $('#restore_id_marca').val(brand.id_marca);
        $('#restoreInactiveName').text(brand.nombre_marca);
    }

    // Metodo clave del listado principal consumido por AJAX.
    function loadBrands(page, pageSize) {
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
            data: {
                page: pagination.page,
                page_size: pagination.page_size,
                search: toTrimmedString(pagination.search)
            },
            dataType: 'json',
            cache: false
        })
            .done(function (response) {
                if (!response.success) {
                    renderMessageRow(response.message || 'No se pudo cargar la tabla.', 'text-danger');
                    return;
                }

                pagination = $.extend({}, pagination, response.pagination || {});
                pagination.search = response.search || toTrimmedString(pagination.search);
                renderRows(response.brands || []);
                updatePaginationInfo();
            })
            .fail(function (xhr, textStatus) {
                if (textStatus === 'abort') {
                    return;
                }

                renderMessageRow(extractResponseMessage(xhr, 'Ocurrio un problema al cargar las marcas.'), 'text-danger');
            })
            .always(function () {
                listRequest = null;
                updateControlsState(false);
            });
    }

    // Metodo clave del listado de marcas inactivas consumido por AJAX.
    function loadInactiveBrands() {
        if (inactiveListRequest) {
            inactiveListRequest.abort();
            inactiveListRequest = null;
        }

        renderInactiveMessageRow('Cargando marcas inactivas...', 'text-muted');

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

                inactiveState.total = Number(response.total) || 0;
                inactiveState.search = response.search || toTrimmedString(inactiveState.search);
                renderInactiveRows(response.brands || []);
                updateInactiveSummary();
            })
            .fail(function (xhr, textStatus) {
                if (textStatus === 'abort') {
                    return;
                }

                renderInactiveMessageRow(extractResponseMessage(xhr, 'Ocurrio un problema al cargar las marcas inactivas.'), 'text-danger');
            })
            .always(function () {
                inactiveListRequest = null;
            });
    }

    $brandPageSizeSelect.on('change', function () {
        loadBrands(1, Number($(this).val()) || 10);
    });

    $brandSearchInput.on('input', function () {
        var currentValue = toTrimmedString($(this).val());
        pagination.search = currentValue;

        if (searchTimer) {
            clearTimeout(searchTimer);
        }

        if (currentValue !== '' && isNumericSearch(currentValue)) {
            return;
        }

        searchTimer = window.setTimeout(function () {
            loadBrands(1, pagination.page_size);
        }, 350);
    });

    $brandSearchInput.on('keydown', function (event) {
        if (event.key !== 'Enter') {
            return;
        }

        event.preventDefault();
        runMainSearch();
    });

    $filterBrandSearchButton.on('click', function () {
        runMainSearch();
    });

    $clearBrandSearchButton.on('click', function () {
        pagination.search = '';
        $brandSearchInput.val('');
        loadBrands(1, pagination.page_size);
    });

    $openInactiveBrandsModalButton.on('click', function () {
        ensureModals();
        setModalDialogSize($inactiveBrandsModalElement, 'modal-xl');
        inactiveState.search = '';
        $inactiveBrandSearchInput.val('');
        updateInactiveSummary();
        loadInactiveBrands();

        if (inactiveBrandsModal) {
            inactiveBrandsModal.show();
        }
    });

    $inactiveBrandSearchInput.on('input', function () {
        inactiveState.search = toTrimmedString($(this).val());

        if (inactiveSearchTimer) {
            clearTimeout(inactiveSearchTimer);
        }

        inactiveSearchTimer = window.setTimeout(function () {
            loadInactiveBrands();
        }, 350);
    });

    $clearInactiveBrandSearchButton.on('click', function () {
        inactiveState.search = '';
        $inactiveBrandSearchInput.val('');
        loadInactiveBrands();
    });

    $prevBrandPageButton.on('click', function (event) {
        event.preventDefault();

        if (listRequest || pagination.page <= 1) {
            return;
        }

        loadBrands(pagination.page - 1, pagination.page_size);
    });

    $nextBrandPageButton.on('click', function (event) {
        event.preventDefault();

        if (listRequest || pagination.page >= pagination.total_pages) {
            return;
        }

        loadBrands(pagination.page + 1, pagination.page_size);
    });

    $openCreateBrandModalButton.on('click', function () {
        ensureModals();
        setModalDialogSize($createModalElement, 'modal-lg');

        if ($createBrandForm.length) {
            $createBrandForm[0].reset();
            setFormInitialState('create', $createBrandForm);
        }
        showFeedback($createFeedback, '', 'info');

        if (createModal) {
            createModal.show();
        }
    });

    $brandTableBody.on('click', '.js-view-brand', function () {
        var brandId = $(this).data('id');
        ensureModals();
        setModalDialogSize($detailModalElement, 'modal-lg');

        loadBrandDetails(brandId, function (brand) {
            fillDetailModal(brand);

            if (detailModal) {
                detailModal.show();
            }
        });
    });

    $brandTableBody.on('click', '.js-edit-brand', function () {
        var brandId = $(this).data('id');
        ensureModals();
        setModalDialogSize($editModalElement, 'modal-lg');

        loadBrandDetails(brandId, function (brand) {
            fillEditModal(brand);
            setFormInitialState('edit', $editBrandForm);
            showFeedback($editFeedback, '', 'info');

            if (editModal) {
                editModal.show();
            }
        });
    });

    $brandTableBody.on('click', '.js-delete-brand', function () {
        var brandId = $(this).data('id');
        ensureModals();
        setModalDialogSize($deleteModalElement, 'modal-sm');

        loadBrandDetails(brandId, function (brand) {
            prepareDeleteModal(brand);
            showFeedback($deleteFeedback, '', 'info');

            if (deleteModal) {
                deleteModal.show();
            }
        });
    });

    $inactiveBrandTableBody.on('click', '.js-restore-brand', function () {
        var brand = {
            id_marca: Number($(this).data('id')) || 0,
            nombre_marca: $(this).closest('tr').find('td').eq(1).text() || ''
        };

        ensureModals();
        setModalDialogSize($restoreInactiveBrandModalElement, 'modal-sm');
        prepareRestoreInactiveModal(brand);
        showFeedback($restoreInactiveFeedback, '', 'info');

        if (restoreInactiveBrandModal) {
            restoreInactiveBrandModal.show();
        }
    });

    $inactiveBrandTableBody.on('click', '.js-hard-delete-brand', function () {
        var brand = {
            id_marca: Number($(this).data('id')) || 0,
            nombre_marca: $(this).data('name') || ''
        };

        ensureModals();
        setModalDialogSize($hardDeleteInactiveBrandModalElement, 'modal-sm');
        prepareHardDeleteInactiveModal(brand);
        showFeedback($hardDeleteInactiveFeedback, '', 'info');

        if (hardDeleteInactiveBrandModal) {
            hardDeleteInactiveBrandModal.show();
        }
    });

    $createBrandForm.on('submit', function (event) {
        var $submitButton = $createBrandForm.find('button[type="submit"]');
        var formData = {
            nombre_marca: $('#create_nombre_marca').val(),
            id_proveedor: $('#create_id_proveedor').val(),
            estado: $('#create_estado').val()
        };
        var validationMessage = validateBrandPayload(formData);

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
            contentType: 'application/json; charset=UTF-8',
            data: JSON.stringify(buildJsonPayload($createBrandForm))
        })
            .done(function (response) {
                if (!response.success) {
                    showFeedback($createFeedback, response.message || 'No se pudo registrar la marca.', 'danger');
                    return;
                }

                showFeedback($createFeedback, response.message || 'Marca registrada correctamente.', 'success');
                $createBrandForm[0].reset();
                setFormInitialState('create', $createBrandForm);
                formState.create.allowClose = true;
                loadBrands(1, pagination.page_size);

                window.setTimeout(function () {
                    if (createModal) {
                        createModal.hide();
                    }
                }, 650);
            })
            .fail(function (xhr) {
                showFeedback($createFeedback, extractResponseMessage(xhr, 'No se pudo registrar la marca en este momento.'), 'danger');
            })
            .always(function () {
                setButtonLoading($submitButton, false, 'Guardar marca');
            });
    });

    $editBrandForm.on('submit', function (event) {
        var $submitButton = $editBrandForm.find('button[type="submit"]');
        var formData = {
            nombre_marca: $('#edit_nombre_marca').val(),
            id_proveedor: $('#edit_id_proveedor').val(),
            estado: $('#edit_estado').val()
        };
        var validationMessage = validateBrandPayload(formData);

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
            method: 'PUT',
            dataType: 'json',
            contentType: 'application/json; charset=UTF-8',
            data: JSON.stringify(buildJsonPayload($editBrandForm))
        })
            .done(function (response) {
                if (!response.success) {
                    showFeedback($editFeedback, response.message || 'No se pudo actualizar la marca.', 'danger');
                    return;
                }

                showFeedback($editFeedback, response.message || 'Marca actualizada correctamente.', 'success');
                setFormInitialState('edit', $editBrandForm);
                formState.edit.allowClose = true;
                loadBrands(pagination.page, pagination.page_size);

                window.setTimeout(function () {
                    if (editModal) {
                        editModal.hide();
                    }
                }, 650);
            })
            .fail(function (xhr) {
                showFeedback($editFeedback, extractResponseMessage(xhr, 'No se pudo actualizar la marca en este momento.'), 'danger');
            })
            .always(function () {
                setButtonLoading($submitButton, false, 'Guardar cambios');
            });
    });

    $deleteBrandForm.on('submit', function (event) {
        var $submitButton = $deleteBrandForm.find('button[type="submit"]');

        event.preventDefault();
        showFeedback($deleteFeedback, '', 'info');
        setButtonLoading($submitButton, true, 'Eliminando...');

        $.ajax({
            url: deleteUrl,
            method: 'DELETE',
            dataType: 'json',
            contentType: 'application/json; charset=UTF-8',
            data: JSON.stringify(buildJsonPayload($deleteBrandForm))
        })
            .done(function (response) {
                if (!response.success) {
                    showFeedback($deleteFeedback, response.message || 'No se pudo eliminar la marca.', 'danger');
                    return;
                }

                showFeedback($deleteFeedback, response.message || 'Marca eliminada correctamente.', 'success');
                loadBrands(pagination.page, pagination.page_size);

                window.setTimeout(function () {
                    if (deleteModal) {
                        deleteModal.hide();
                    }
                }, 650);
            })
            .fail(function (xhr) {
                showFeedback($deleteFeedback, extractResponseMessage(xhr, 'No se pudo eliminar la marca en este momento.'), 'danger');
            })
            .always(function () {
                setButtonLoading($submitButton, false, 'Confirmar eliminacion');
            });
    });

    $hardDeleteInactiveBrandForm.on('submit', function (event) {
        var $submitButton = $hardDeleteInactiveBrandForm.find('button[type="submit"]');

        event.preventDefault();
        showFeedback($hardDeleteInactiveFeedback, '', 'info');
        setButtonLoading($submitButton, true, 'Eliminando...');

        $.ajax({
            url: hardDeleteUrl,
            method: 'DELETE',
            dataType: 'json',
            contentType: 'application/json; charset=UTF-8',
            data: JSON.stringify(buildJsonPayload($hardDeleteInactiveBrandForm))
        })
            .done(function (response) {
                if (!response.success) {
                    showFeedback($hardDeleteInactiveFeedback, response.message || 'No se pudo eliminar definitivamente.', 'danger');
                    return;
                }

                showFeedback(
                    $hardDeleteInactiveFeedback,
                    response.deleted_products > 0
                        ? 'Marca eliminada definitivamente. Tambien se eliminaron ' + response.deleted_products + ' producto(s) asociados.'
                        : (response.message || 'Marca eliminada definitivamente.'),
                    'success'
                );
                loadInactiveBrands();
                loadBrands(pagination.page, pagination.page_size);

                window.setTimeout(function () {
                    if (hardDeleteInactiveBrandModal) {
                        hardDeleteInactiveBrandModal.hide();
                    }
                }, 900);
            })
            .fail(function (xhr) {
                showFeedback($hardDeleteInactiveFeedback, extractResponseMessage(xhr, 'No se pudo eliminar definitivamente la marca en este momento.'), 'danger');
            })
            .always(function () {
                setButtonLoading($submitButton, false, 'Eliminar definitivo');
            });
    });

    $restoreInactiveBrandForm.on('submit', function (event) {
        var $submitButton = $restoreInactiveBrandForm.find('button[type="submit"]');

        event.preventDefault();
        showFeedback($restoreInactiveFeedback, '', 'info');
        setButtonLoading($submitButton, true, 'Restaurando...');

        $.ajax({
            url: restoreUrl,
            method: 'PUT',
            dataType: 'json',
            contentType: 'application/json; charset=UTF-8',
            data: JSON.stringify(buildJsonPayload($restoreInactiveBrandForm))
        })
            .done(function (response) {
                if (!response.success) {
                    showFeedback($restoreInactiveFeedback, response.message || 'No se pudo restaurar la marca.', 'danger');
                    return;
                }

                showFeedback($restoreInactiveFeedback, response.message || 'Marca restaurada correctamente.', 'success');
                loadInactiveBrands();
                loadBrands(1, pagination.page_size);

                window.setTimeout(function () {
                    if (restoreInactiveBrandModal) {
                        restoreInactiveBrandModal.hide();
                    }
                }, 750);
            })
            .fail(function (xhr) {
                showFeedback($restoreInactiveFeedback, extractResponseMessage(xhr, 'No se pudo restaurar la marca en este momento.'), 'danger');
            })
            .always(function () {
                setButtonLoading($submitButton, false, 'Restaurar marca');
            });
    });

    $createModalElement.on('hide.bs.modal', function (event) {
        if (formState.create.allowClose || !hasUnsavedChanges('create', $createBrandForm)) {
            return;
        }

        event.preventDefault();
        openExitPrompt('create');
    });

    $editModalElement.on('hide.bs.modal', function (event) {
        if (formState.edit.allowClose || !hasUnsavedChanges('edit', $editBrandForm)) {
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

    $('#confirmBrandExitDiscardButton').on('click', function () {
        var modalKey = exitPromptState.activeModalKey;

        if (!modalKey) {
            return;
        }

        if (confirmExitModal) {
            confirmExitModal.hide();
        }

        if (modalKey === 'create') {
            requestModalClose('create', createModal, $createBrandForm);
            return;
        }

        requestModalClose('edit', editModal, $editBrandForm);
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
            $createBrandForm.trigger('submit');
            return;
        }

        formState.edit.pendingSave = true;
        $editBrandForm.trigger('submit');
    });

    // Inicializar
    ensureModals();
    updatePaginationInfo();
    bindDragScroll();
    loadBrands(1, pagination.page_size);
});
