$(function () {
    var $categoryTableBody = $('#categoryTableBody');
    var $categorySummary = $('#categorySummary');
    var $categoryPageStatus = $('#categoryPageStatus');
    var $pageSizeSelect = $('#pageSizeSelect');
    var $prevPageButton = $('#prevPageButton');
    var $nextPageButton = $('#nextPageButton');
    var $categorySearchInput = $('#categorySearchInput');
    var $filterSearchButton = $('#filterSearchButton');
    var $clearSearchButton = $('#clearSearchButton');
    var $openCreateModalButton = $('#openCreateModalButton');
    var $openInactiveModalButton = $('#openInactiveModalButton');
    var $categoryTableShell = $('.category-table-shell');
    var $inactiveCategoryTableShell = $('.inactive-category-table-shell');
    var $inactiveCategoryTableBody = $('#inactiveCategoryTableBody');
    var $inactiveCategorySummary = $('#inactiveCategorySummary');
    var $inactiveCategoryStatus = $('#inactiveCategoryStatus');
    var $inactiveCategorySearchInput = $('#inactiveCategorySearchInput');
    var $clearInactiveSearchButton = $('#clearInactiveSearchButton');
    var $createCategoryForm = $('#createCategoryForm');
    var $editCategoryForm = $('#editCategoryForm');
    var $deleteCategoryForm = $('#deleteCategoryForm');
    var $hardDeleteInactiveCategoryForm = $('#hardDeleteInactiveCategoryForm');
    var $restoreInactiveCategoryForm = $('#restoreInactiveCategoryForm');
    var $createFeedback = $('#createCategoryFeedback');
    var $editFeedback = $('#editCategoryFeedback');
    var $deleteFeedback = $('#deleteCategoryFeedback');
    var $hardDeleteInactiveFeedback = $('#hardDeleteInactiveFeedback');
    var $restoreInactiveFeedback = $('#restoreInactiveFeedback');
    var $createModalElement = $('#createCategoryModal');
    var $editModalElement = $('#editCategoryModal');
    var $detailModalElement = $('#detailCategoryModal');
    var $deleteModalElement = $('#deleteCategoryModal');
    var $confirmExitModalElement = $('#confirmExitCategoryModal');
    var $infoModalElement = $('#infoCategoryModal');
    var $inactiveCategoriesModalElement = $('#inactiveCategoriesModal');
    var $hardDeleteInactiveCategoryModalElement = $('#hardDeleteInactiveCategoryModal');
    var $restoreInactiveCategoryModalElement = $('#restoreInactiveCategoryModal');
    var $confirmExitTitle = $('#confirmExitCategoryTitle');
    var $confirmExitCopy = $('#confirmExitCategoryCopy');
    var $confirmExitSaveButton = $('#confirmExitSaveButton');
    var $infoModalTitle = $('#infoCategoryModalTitle');
    var $infoModalMessage = $('#infoCategoryModalMessage');
    var createModal = null;
    var editModal = null;
    var detailModal = null;
    var deleteModal = null;
    var confirmExitModal = null;
    var infoModal = null;
    var inactiveCategoriesModal = null;
    var hardDeleteInactiveCategoryModal = null;
    var restoreInactiveCategoryModal = null;
    var listUrl = 'Api/Category/List.php';
    var listInactiveUrl = 'Api/Category/ListInactive.php';
    var getUrl = 'Api/Category/Get.php';
    var createUrl = 'Api/Category/Create.php';
    var updateUrl = 'Api/Category/Update.php';
    var deleteUrl = 'Api/Category/Delete.php';
    var restoreUrl = 'Api/Category/Restore.php';
    var hardDeleteUrl = 'Api/Category/HardDelete.php';
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

    function isNumericSearch(value) {
        return /^[0-9]+$/.test(toTrimmedString(value));
    }

    function formatDate(dateTime) {
        if (!dateTime) {
            return 'Sin fecha';
        }

        return dateTime.replace(' ', ' | ');
    }

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

    function normalizeCategory(category) {
        if (!category) {
            return null;
        }

        return {
            id_categoria: Number(category.id_categoria) || 0,
            nombre_categoria: category.nombre_categoria || '',
            descripcion: category.descripcion || '',
            estado: Number(category.estado) || 0,
            created_at: category.created_at || '',
            updated_at: category.updated_at || ''
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

        if (inactiveCategoriesModal === null) {
            inactiveCategoriesModal = getModalInstance($inactiveCategoriesModalElement);
        }

        if (hardDeleteInactiveCategoryModal === null) {
            hardDeleteInactiveCategoryModal = getModalInstance($hardDeleteInactiveCategoryModalElement);
        }

        if (restoreInactiveCategoryModal === null) {
            restoreInactiveCategoryModal = getModalInstance($restoreInactiveCategoryModalElement);
        }
    }

    function updateTableMode(pageSize) {
        var tableModes = 'table-size-10 table-size-20 table-size-50';
        $categoryTableShell.removeClass(tableModes).addClass('table-size-' + pageSize);
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
        $confirmExitTitle.text(modalKey === 'create' ? 'Salir de crear categoria' : 'Salir de editar categoria');
        $confirmExitCopy.html(
            modalKey === 'create'
                ? 'Has ingresado informacion nueva en el formulario de <strong>crear categoria</strong>. Puedes volver, salir sin guardar o guardar antes de cerrar.'
                : 'Has realizado cambios en el formulario de <strong>editar categoria</strong>. Puedes volver, salir sin guardar o guardar antes de cerrar.'
        );

        if ($confirmExitSaveButton.length) {
            $confirmExitSaveButton.text(modalKey === 'create' ? 'Guardar categoria' : 'Guardar cambios');
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

    function validateCategoryPayload(payload) {
        var nombre = toTrimmedString(payload.nombre_categoria);
        var descripcion = toTrimmedString(payload.descripcion);
        var estado = toTrimmedString(payload.estado);

        if (nombre === '') {
            return 'Debes ingresar el nombre de la categoria.';
        }

        if (nombre.length < 3) {
            return 'El nombre de la categoria debe tener al menos 3 caracteres.';
        }

        if (descripcion === '') {
            return 'Debes ingresar una descripcion para la categoria.';
        }

        if (descripcion.length < 3) {
            return 'La descripcion debe tener al menos 3 caracteres.';
        }

        if (estado !== '0' && estado !== '1') {
            return 'Debes seleccionar un estado valido para la categoria.';
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

        $categoryTableShell
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

        $inactiveCategoryTableShell
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
            $categorySummary.text(hasSearch ? 'No se encontraron categorias con el filtro actual' : 'Mostrando 0 de 0 categorias');
        } else {
            $categorySummary.text('Mostrando ' + startRecord + ' - ' + endRecord + ' de ' + total + ' categorias');
        }

        $categoryPageStatus.text('Pagina ' + currentPage + ' de ' + totalPages);
        $pageSizeSelect.val(String(pageSize));
        updateTableMode(pageSize);
    }

    function renderMessageRow(message, textClass) {
        $categoryTableBody.html(
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

    function buildActionButtons(category) {
        return '' +
            '<div class="category-actions">' +
                '<button type="button" class="btn btn-outline-primary category-action-button js-view-category" title="Ver detalle" data-id="' + escapeHtml(category.id_categoria) + '">' +
                    '<i class="fas fa-eye"></i>' +
                '</button>' +
                '<button type="button" class="btn btn-outline-warning category-action-button js-edit-category" title="Editar" data-id="' + escapeHtml(category.id_categoria) + '">' +
                    '<i class="fas fa-edit"></i>' +
                '</button>' +
                '<button type="button" class="btn btn-outline-danger category-action-button js-delete-category" title="Eliminar" data-id="' + escapeHtml(category.id_categoria) + '" data-name="' + escapeHtml(category.nombre_categoria) + '">' +
                    '<i class="fas fa-trash"></i>' +
                '</button>' +
            '</div>';
    }

    function buildInactiveActionButtons(category) {
        return '' +
            '<div class="inactive-category-actions">' +
                '<button type="button" class="btn btn-outline-success category-action-button js-restore-category" title="Restaurar" data-id="' + escapeHtml(category.id_categoria) + '">' +
                    '<i class="fas fa-undo"></i>' +
                '</button>' +
                '<button type="button" class="btn btn-outline-danger category-action-button js-hard-delete-category" title="Eliminar definitivo" data-id="' + escapeHtml(category.id_categoria) + '" data-name="' + escapeHtml(category.nombre_categoria) + '">' +
                    '<i class="fas fa-trash"></i>' +
                '</button>' +
            '</div>';
    }

    function renderRows(categories) {
        if (!categories.length) {
            renderMessageRow('No hay categorias registradas.', 'text-muted');
            return;
        }

        var rows = $.map(categories, function (category) {
            return '' +
                '<tr>' +
                    '<td>' + escapeHtml(category.id_categoria) + '</td>' +
                    '<td>' + escapeHtml(category.nombre_categoria) + '</td>' +
                    '<td>' + escapeHtml(category.descripcion) + '</td>' +
                    '<td>' + buildStatusBadge(category.estado) + '</td>' +
                    '<td>' + escapeHtml(formatDate(category.created_at)) + '</td>' +
                    '<td>' + buildActionButtons(category) + '</td>' +
                '</tr>';
        }).join('');

        $categoryTableBody.html(rows);
        updateTableHeight(categories.length);
    }

    function renderInactiveMessageRow(message, textClass) {
        $inactiveCategoryTableBody.html(
            '<tr><td colspan="6" class="text-center py-4 ' + textClass + '">' + escapeHtml(message) + '</td></tr>'
        );
        updateInactiveTableHeight(0);
    }

    function renderInactiveRows(categories) {
        if (!categories.length) {
            renderInactiveMessageRow('No hay categorias inactivas registradas.', 'text-muted');
            return;
        }

        var rows = $.map(categories, function (category) {
            return '' +
                '<tr>' +
                    '<td>' + escapeHtml(category.id_categoria) + '</td>' +
                    '<td>' + escapeHtml(category.nombre_categoria) + '</td>' +
                    '<td>' + escapeHtml(category.descripcion) + '</td>' +
                    '<td>' + buildStatusBadge(category.estado) + '</td>' +
                    '<td>' + escapeHtml(formatDate(category.deleted_at)) + '</td>' +
                    '<td>' + buildInactiveActionButtons(category) + '</td>' +
                '</tr>';
        }).join('');

        $inactiveCategoryTableBody.html(rows);
        updateInactiveTableHeight(categories.length);
    }

    function updateInactiveSummary() {
        var total = Number(inactiveState.total) || 0;
        var hasSearch = toTrimmedString(inactiveState.search) !== '';

        if (total === 0) {
            $inactiveCategorySummary.text(hasSearch ? 'No se encontraron categorias inactivas con el filtro actual' : 'Mostrando 0 categorias inactivas');
            $inactiveCategoryStatus.text('Sin resultados');
            return;
        }

        $inactiveCategorySummary.text('Mostrando ' + total + ' categorias inactivas');
        $inactiveCategoryStatus.text(hasSearch ? 'Filtro aplicado' : 'Listado completo');
    }

    function bindDragScroll() {
        var interactiveSelector = 'button, a, input, textarea, select, label, .category-actions, .category-action-button';
        var dragThreshold = 8;

        $categoryTableShell.on('pointerdown', function (event) {
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

        $categoryTableShell.on('pointermove', function (event) {
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

        $categoryTableShell.on('pointerup pointercancel lostpointercapture', function (event) {
            if (dragState.pointerId !== null && typeof event.pointerId !== 'undefined' && dragState.pointerId !== event.pointerId) {
                return;
            }

            dragState.active = false;
            dragState.dragging = false;
            dragState.pointerId = null;
            $(this).removeClass('is-dragging');
        });
    }

    function loadCategoryDetails(categoryId, onSuccess) {
        $.ajax({
            url: getUrl,
            method: 'GET',
            dataType: 'json',
            cache: false,
            data: {
                id_categoria: Number(categoryId) || 0
            }
        })
            .done(function (response) {
                if (!response.success || !response.category) {
                    showInfoModal('Categoria no disponible', response.message || 'No se pudo obtener el detalle de la categoria seleccionada.');
                    return;
                }

                onSuccess(normalizeCategory(response.category));
            })
            .fail(function (xhr) {
                showInfoModal('No se pudo consultar la categoria', extractResponseMessage(xhr, 'Ocurrio un problema al consultar la categoria. Intenta nuevamente.'));
            });
    }

    function runMainSearch() {
        var searchValue = toTrimmedString($categorySearchInput.val());

        if (searchValue === '') {
            showInfoModal('Campo de busqueda vacio', 'Debes ingresar un ID o un nombre antes de hacer clic en Filtrar.');
            $categorySearchInput.trigger('focus');
            return;
        }

        pagination.search = searchValue;

        if (isNumericSearch(searchValue)) {
            ensureModals();
            loadCategoryDetails(Number(searchValue), function (category) {
                fillDetailModal(category);

                if (detailModal) {
                    detailModal.show();
                }
            });
            return;
        }

        loadCategories(1, pagination.page_size);
    }

    function fillDetailModal(category) {
        $('#detailCategoryId').text(category.id_categoria);
        $('#detailCategoryName').text(category.nombre_categoria);
        $('#detailCategoryDescription').text(category.descripcion || 'Sin descripcion');
        $('#detailCategoryStatus').html(buildStatusBadge(category.estado));
        $('#detailCategoryCreated').text(formatDate(category.created_at));
        $('#detailCategoryUpdated').text(formatDate(category.updated_at));
    }

    function fillEditModal(category) {
        $('#edit_id_categoria').val(category.id_categoria);
        $('#edit_id_categoria_readonly').val(category.id_categoria);
        $('#edit_nombre_categoria').val(category.nombre_categoria);
        $('#edit_descripcion').val(category.descripcion);
        $('#edit_estado').val(String(category.estado));
    }

    function prepareDeleteModal(category) {
        $('#delete_id_categoria').val(category.id_categoria);
        $('#deleteCategoryName').text(category.nombre_categoria);
        $('#deleteCategoryDescription').text(category.descripcion || 'Sin descripcion registrada.');
    }

    function prepareHardDeleteInactiveModal(category) {
        $('#hard_delete_id_categoria').val(category.id_categoria);
        $('#hardDeleteInactiveName').text(category.nombre_categoria);
    }

    function prepareRestoreInactiveModal(category) {
        $('#restore_id_categoria').val(category.id_categoria);
        $('#restoreInactiveName').text(category.nombre_categoria);
    }

    function loadCategories(page, pageSize) {
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
                renderRows(response.categories || []);
                updatePaginationInfo();
            })
            .fail(function (xhr, textStatus) {
                if (textStatus === 'abort') {
                    return;
                }

                renderMessageRow(extractResponseMessage(xhr, 'Ocurrio un problema al cargar las categorias.'), 'text-danger');
            })
            .always(function () {
                listRequest = null;
                updateControlsState(false);
            });
    }

    function loadInactiveCategories() {
        if (inactiveListRequest) {
            inactiveListRequest.abort();
            inactiveListRequest = null;
        }

        renderInactiveMessageRow('Cargando categorias inactivas...', 'text-muted');

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
                renderInactiveRows(response.categories || []);
                updateInactiveSummary();
            })
            .fail(function (xhr, textStatus) {
                if (textStatus === 'abort') {
                    return;
                }

                renderInactiveMessageRow(extractResponseMessage(xhr, 'Ocurrio un problema al cargar las categorias inactivas.'), 'text-danger');
            })
            .always(function () {
                inactiveListRequest = null;
            });
    }

    $pageSizeSelect.on('change', function () {
        loadCategories(1, Number($(this).val()) || 10);
    });

    $categorySearchInput.on('input', function () {
        var currentValue = toTrimmedString($(this).val());
        pagination.search = currentValue;

        if (searchTimer) {
            clearTimeout(searchTimer);
        }

        if (currentValue !== '' && isNumericSearch(currentValue)) {
            return;
        }

        searchTimer = window.setTimeout(function () {
            loadCategories(1, pagination.page_size);
        }, 350);
    });

    $categorySearchInput.on('keydown', function (event) {
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
        $categorySearchInput.val('');
        loadCategories(1, pagination.page_size);
    });

    $openInactiveModalButton.on('click', function () {
        ensureModals();
        setModalDialogSize($inactiveCategoriesModalElement, 'modal-xl');
        inactiveState.search = '';
        $inactiveCategorySearchInput.val('');
        updateInactiveSummary();
        loadInactiveCategories();

        if (inactiveCategoriesModal) {
            inactiveCategoriesModal.show();
        }
    });

    $inactiveCategorySearchInput.on('input', function () {
        inactiveState.search = toTrimmedString($(this).val());

        if (inactiveSearchTimer) {
            clearTimeout(inactiveSearchTimer);
        }

        inactiveSearchTimer = window.setTimeout(function () {
            loadInactiveCategories();
        }, 350);
    });

    $clearInactiveSearchButton.on('click', function () {
        inactiveState.search = '';
        $inactiveCategorySearchInput.val('');
        loadInactiveCategories();
    });

    $prevPageButton.on('click', function (event) {
        event.preventDefault();

        if (listRequest || pagination.page <= 1) {
            return;
        }

        loadCategories(pagination.page - 1, pagination.page_size);
    });

    $nextPageButton.on('click', function (event) {
        event.preventDefault();

        if (listRequest || pagination.page >= pagination.total_pages) {
            return;
        }

        loadCategories(pagination.page + 1, pagination.page_size);
    });

    $openCreateModalButton.on('click', function () {
        ensureModals();
        setModalDialogSize($createModalElement, 'modal-lg');

        if ($createCategoryForm.length) {
            $createCategoryForm[0].reset();
            setFormInitialState('create', $createCategoryForm);
        }
        showFeedback($createFeedback, '', 'info');

        if (createModal) {
            createModal.show();
        }
    });

    $categoryTableBody.on('click', '.js-view-category', function () {
        var categoryId = $(this).data('id');
        ensureModals();
        setModalDialogSize($detailModalElement, 'modal-lg');

        loadCategoryDetails(categoryId, function (category) {
            fillDetailModal(category);

            if (detailModal) {
                detailModal.show();
            }
        });
    });

    $categoryTableBody.on('click', '.js-edit-category', function () {
        var categoryId = $(this).data('id');
        ensureModals();
        setModalDialogSize($editModalElement, 'modal-lg');

        loadCategoryDetails(categoryId, function (category) {
            fillEditModal(category);
            setFormInitialState('edit', $editCategoryForm);
            showFeedback($editFeedback, '', 'info');

            if (editModal) {
                editModal.show();
            }
        });
    });

    $categoryTableBody.on('click', '.js-delete-category', function () {
        var categoryId = $(this).data('id');
        ensureModals();
        setModalDialogSize($deleteModalElement, 'modal-sm');

        loadCategoryDetails(categoryId, function (category) {
            prepareDeleteModal(category);
            showFeedback($deleteFeedback, '', 'info');

            if (deleteModal) {
                deleteModal.show();
            }
        });
    });

    $inactiveCategoryTableBody.on('click', '.js-restore-category', function () {
        var category = {
            id_categoria: Number($(this).data('id')) || 0,
            nombre_categoria: $(this).closest('tr').find('td').eq(1).text() || ''
        };

        ensureModals();
        setModalDialogSize($restoreInactiveCategoryModalElement, 'modal-sm');
        prepareRestoreInactiveModal(category);
        showFeedback($restoreInactiveFeedback, '', 'info');

        if (restoreInactiveCategoryModal) {
            restoreInactiveCategoryModal.show();
        }
    });

    $inactiveCategoryTableBody.on('click', '.js-hard-delete-category', function () {
        var category = {
            id_categoria: Number($(this).data('id')) || 0,
            nombre_categoria: $(this).data('name') || ''
        };

        ensureModals();
        setModalDialogSize($hardDeleteInactiveCategoryModalElement, 'modal-sm');
        prepareHardDeleteInactiveModal(category);
        showFeedback($hardDeleteInactiveFeedback, '', 'info');

        if (hardDeleteInactiveCategoryModal) {
            hardDeleteInactiveCategoryModal.show();
        }
    });

    $createCategoryForm.on('submit', function (event) {
        var $submitButton = $createCategoryForm.find('button[type="submit"]');
        var formData = {
            nombre_categoria: $('#create_nombre_categoria').val(),
            descripcion: $('#create_descripcion').val(),
            estado: $('#create_estado').val()
        };
        var validationMessage = validateCategoryPayload(formData);

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
            data: $createCategoryForm.serialize()
        })
            .done(function (response) {
                if (!response.success) {
                    showFeedback($createFeedback, response.message || 'No se pudo registrar la categoria.', 'danger');
                    return;
                }

                showFeedback($createFeedback, response.message || 'Categoria registrada correctamente.', 'success');
                $createCategoryForm[0].reset();
                setFormInitialState('create', $createCategoryForm);
                formState.create.allowClose = true;
                loadCategories(1, pagination.page_size);

                window.setTimeout(function () {
                    if (createModal) {
                        createModal.hide();
                    }
                }, 650);
            })
            .fail(function (xhr) {
                showFeedback($createFeedback, extractResponseMessage(xhr, 'No se pudo registrar la categoria en este momento.'), 'danger');
            })
            .always(function () {
                setButtonLoading($submitButton, false, 'Guardar categoria');
            });
    });

    $editCategoryForm.on('submit', function (event) {
        var $submitButton = $editCategoryForm.find('button[type="submit"]');
        var formData = {
            nombre_categoria: $('#edit_nombre_categoria').val(),
            descripcion: $('#edit_descripcion').val(),
            estado: $('#edit_estado').val()
        };
        var validationMessage = validateCategoryPayload(formData);

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
            data: $editCategoryForm.serialize()
        })
            .done(function (response) {
                if (!response.success) {
                    showFeedback($editFeedback, response.message || 'No se pudo actualizar la categoria.', 'danger');
                    return;
                }

                showFeedback($editFeedback, response.message || 'Categoria actualizada correctamente.', 'success');
                setFormInitialState('edit', $editCategoryForm);
                formState.edit.allowClose = true;
                loadCategories(pagination.page, pagination.page_size);

                window.setTimeout(function () {
                    if (editModal) {
                        editModal.hide();
                    }
                }, 650);
            })
            .fail(function (xhr) {
                showFeedback($editFeedback, extractResponseMessage(xhr, 'No se pudo actualizar la categoria en este momento.'), 'danger');
            })
            .always(function () {
                setButtonLoading($submitButton, false, 'Guardar cambios');
            });
    });

    $deleteCategoryForm.on('submit', function (event) {
        var $submitButton = $deleteCategoryForm.find('button[type="submit"]');

        event.preventDefault();
        showFeedback($deleteFeedback, '', 'info');
        setButtonLoading($submitButton, true, 'Eliminando...');

        $.ajax({
            url: deleteUrl,
            method: 'POST',
            dataType: 'json',
            data: $deleteCategoryForm.serialize()
        })
            .done(function (response) {
                if (!response.success) {
                    showFeedback($deleteFeedback, response.message || 'No se pudo eliminar la categoria.', 'danger');
                    return;
                }

                showFeedback($deleteFeedback, response.message || 'Categoria eliminada correctamente.', 'success');
                loadCategories(pagination.page, pagination.page_size);

                window.setTimeout(function () {
                    if (deleteModal) {
                        deleteModal.hide();
                    }
                }, 650);
            })
            .fail(function (xhr) {
                showFeedback($deleteFeedback, extractResponseMessage(xhr, 'No se pudo eliminar la categoria en este momento.'), 'danger');
            })
            .always(function () {
                setButtonLoading($submitButton, false, 'Confirmar eliminacion');
            });
    });

    $hardDeleteInactiveCategoryForm.on('submit', function (event) {
        var $submitButton = $hardDeleteInactiveCategoryForm.find('button[type="submit"]');

        event.preventDefault();
        showFeedback($hardDeleteInactiveFeedback, '', 'info');
        setButtonLoading($submitButton, true, 'Eliminando...');

        $.ajax({
            url: hardDeleteUrl,
            method: 'POST',
            dataType: 'json',
            data: $hardDeleteInactiveCategoryForm.serialize()
        })
            .done(function (response) {
                if (!response.success) {
                    showFeedback($hardDeleteInactiveFeedback, response.message || 'No se pudo eliminar definitivamente.', 'danger');
                    return;
                }

                showFeedback(
                    $hardDeleteInactiveFeedback,
                    response.deleted_products > 0
                        ? 'Categoria eliminada definitivamente. Tambien se eliminaron ' + response.deleted_products + ' producto(s) asociados.'
                        : (response.message || 'Categoria eliminada definitivamente.'),
                    'success'
                );
                loadInactiveCategories();
                loadCategories(pagination.page, pagination.page_size);

                window.setTimeout(function () {
                    if (hardDeleteInactiveCategoryModal) {
                        hardDeleteInactiveCategoryModal.hide();
                    }
                }, 900);
            })
            .fail(function (xhr) {
                showFeedback($hardDeleteInactiveFeedback, extractResponseMessage(xhr, 'No se pudo eliminar definitivamente la categoria en este momento.'), 'danger');
            })
            .always(function () {
                setButtonLoading($submitButton, false, 'Eliminar definitivo');
            });
    });

    $restoreInactiveCategoryForm.on('submit', function (event) {
        var $submitButton = $restoreInactiveCategoryForm.find('button[type="submit"]');

        event.preventDefault();
        showFeedback($restoreInactiveFeedback, '', 'info');
        setButtonLoading($submitButton, true, 'Restaurando...');

        $.ajax({
            url: restoreUrl,
            method: 'POST',
            dataType: 'json',
            data: $restoreInactiveCategoryForm.serialize()
        })
            .done(function (response) {
                if (!response.success) {
                    showFeedback($restoreInactiveFeedback, response.message || 'No se pudo restaurar la categoria.', 'danger');
                    return;
                }

                showFeedback($restoreInactiveFeedback, response.message || 'Categoria restaurada correctamente.', 'success');
                loadInactiveCategories();
                loadCategories(1, pagination.page_size);

                window.setTimeout(function () {
                    if (restoreInactiveCategoryModal) {
                        restoreInactiveCategoryModal.hide();
                    }
                }, 750);
            })
            .fail(function (xhr) {
                showFeedback($restoreInactiveFeedback, extractResponseMessage(xhr, 'No se pudo restaurar la categoria en este momento.'), 'danger');
            })
            .always(function () {
                setButtonLoading($submitButton, false, 'Restaurar categoria');
            });
    });

    $createModalElement.on('hide.bs.modal', function (event) {
        if (formState.create.allowClose || !hasUnsavedChanges('create', $createCategoryForm)) {
            return;
        }

        event.preventDefault();
        openExitPrompt('create');
    });

    $editModalElement.on('hide.bs.modal', function (event) {
        if (formState.edit.allowClose || !hasUnsavedChanges('edit', $editCategoryForm)) {
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

    $('#confirmExitDiscardButton').on('click', function () {
        var modalKey = exitPromptState.activeModalKey;

        if (!modalKey) {
            return;
        }

        if (confirmExitModal) {
            confirmExitModal.hide();
        }

        if (modalKey === 'create') {
            requestModalClose('create', createModal, $createCategoryForm);
            return;
        }

        requestModalClose('edit', editModal, $editCategoryForm);
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
            $createCategoryForm.trigger('submit');
            return;
        }

        formState.edit.pendingSave = true;
        $editCategoryForm.trigger('submit');
    });

    updatePaginationInfo();
    bindDragScroll();
    loadCategories(1, pagination.page_size);
});
