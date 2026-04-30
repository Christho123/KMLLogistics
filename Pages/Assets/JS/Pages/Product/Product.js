// =========================================================
// SCRIPT: PRODUCT
// Logica AJAX, modals Bootstrap y eventos del modulo.
// =========================================================
$(function () {
    var $productTableBody = $('#productTableBody');
    var $productSummary = $('#productSummary');
    var $productPageStatus = $('#productPageStatus');
    var $pageSizeSelect = $('#pageSizeSelect');
    var $prevPageButton = $('#prevPageButton');
    var $nextPageButton = $('#nextPageButton');
    var $productSearchInput = $('#productSearchInput');
    var $filterSearchButton = $('#filterSearchButton');
    var $clearSearchButton = $('#clearSearchButton');
    var $productCategoryFilter = $('#productCategoryFilter');
    var $openCreateModalButton = $('#openCreateModalButton');
    var $openInactiveModalButton = $('#openInactiveModalButton');
    var $productTableShell = $('.product-table-shell').first();
    var $inactiveProductTableShell = $('.inactive-product-table-shell');
    var $inactiveProductTableBody = $('#inactiveProductTableBody');
    var $inactiveProductSummary = $('#inactiveProductSummary');
    var $inactiveProductStatus = $('#inactiveProductStatus');
    var $inactiveProductSearchInput = $('#inactiveProductSearchInput');
    var $clearInactiveSearchButton = $('#clearInactiveSearchButton');
    var $createProductForm = $('#createProductForm');
    var $editProductForm = $('#editProductForm');
    var $deleteProductForm = $('#deleteProductForm');
    var $hardDeleteInactiveProductForm = $('#hardDeleteInactiveProductForm');
    var $restoreInactiveProductForm = $('#restoreInactiveProductForm');
    var $createFeedback = $('#createProductFeedback');
    var $editFeedback = $('#editProductFeedback');
    var $deleteFeedback = $('#deleteProductFeedback');
    var $hardDeleteInactiveFeedback = $('#hardDeleteInactiveFeedback');
    var $restoreInactiveFeedback = $('#restoreInactiveFeedback');
    var $createModalElement = $('#createProductModal');
    var $editModalElement = $('#editProductModal');
    var $detailModalElement = $('#detailProductModal');
    var $deleteModalElement = $('#deleteProductModal');
    var $confirmExitModalElement = $('#confirmExitProductModal');
    var $infoModalElement = $('#infoProductModal');
    var $inactiveProductsModalElement = $('#inactiveProductsModal');
    var $hardDeleteInactiveProductModalElement = $('#hardDeleteInactiveProductModal');
    var $restoreInactiveProductModalElement = $('#restoreInactiveProductModal');
    var $confirmExitTitle = $('#confirmExitProductTitle');
    var $confirmExitCopy = $('#confirmExitProductCopy');
    var $confirmExitSaveButton = $('#confirmExitSaveButton');
    var $infoModalTitle = $('#infoProductModalTitle');
    var $infoModalMessage = $('#infoProductModalMessage');

    var createModal = null;
    var editModal = null;
    var detailModal = null;
    var deleteModal = null;
    var confirmExitModal = null;
    var infoModal = null;
    var inactiveProductsModal = null;
    var hardDeleteInactiveProductModal = null;
    var restoreInactiveProductModal = null;

    var listUrl = 'Api/Product/List.php';
    var listInactiveUrl = 'Api/Product/ListInactive.php';
    var getUrl = 'Api/Product/Get.php';
    var createUrl = 'Api/Product/Create.php';
    var updateUrl = 'Api/Product/Update.php';
    var deleteUrl = 'Api/Product/Delete.php';
    var restoreUrl = 'Api/Product/Restore.php';
    var hardDeleteUrl = 'Api/Product/HardDelete.php';

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
        search: '',
        id_categoria: 0
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

    function isNumericSearch(value) {
        return /^[0-9]+$/.test(toTrimmedString(value));
    }

    function emptyText(value) {
        var text = toTrimmedString(value);
        return text === '' ? '-' : text;
    }

    function formatDate(dateTime) {
        return dateTime ? String(dateTime).replace(' ', ' | ') : 'Sin fecha';
    }

    function formatMoney(value) {
        var number = Number(value) || 0;
        return 'S/ ' + number.toFixed(2);
    }

    function formatPercent(value) {
        return formatPercentInput((Number(value) || 0) * 100);
    }

    function parsePercentInput(value) {
        return Number(toTrimmedString(value).replace('%', '').replace(',', '.'));
    }

    function formatPercentInput(value) {
        var number = Number(value);

        if (Number.isNaN(number)) {
            return '';
        }

        return (Number.isInteger(number) ? String(number) : String(parseFloat(number.toFixed(4)))) + '%';
    }

    function calculatePriceFromPercent(costo, gananciaPercent) {
        var cost = Number(costo) || 0;
        var percent = parsePercentInput(gananciaPercent) || 0;
        var gain = percent / 100;

        if (cost <= 0 || gain < 0 || gain >= 1) {
            return 0;
        }

        return cost / (1 - gain);
    }

    function updateCalculatedPrice(prefix) {
        var price = calculatePriceFromPercent($('#' + prefix + '_costo').val(), $('#' + prefix + '_ganancia').val());
        $('#' + prefix + '_precio').val(price.toFixed(2));
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
        if (inactiveProductsModal === null) {
            inactiveProductsModal = getModalInstance($inactiveProductsModalElement);
        }
        if (hardDeleteInactiveProductModal === null) {
            hardDeleteInactiveProductModal = getModalInstance($hardDeleteInactiveProductModalElement);
        }
        if (restoreInactiveProductModal === null) {
            restoreInactiveProductModal = getModalInstance($restoreInactiveProductModalElement);
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

    function normalizeProduct(product) {
        if (!product) {
            return null;
        }

        return {
            id_producto: Number(product.id_producto) || 0,
            producto: product.producto || '',
            costo: Number(product.costo) || 0,
            ganancia: Number(product.ganancia) || 0,
            precio: Number(product.precio) || 0,
            stock: Number(product.stock) || 0,
            foto: product.foto || '',
            id_categoria: Number(product.id_categoria) || 0,
            nombre_categoria: product.nombre_categoria || product.categoria || '',
            id_marca: Number(product.id_marca) || 0,
            nombre_marca: product.nombre_marca || product.marca || '',
            estado: Number(product.estado) || 0,
            created_at: product.created_at || '',
            updated_at: product.updated_at || '',
            deleted_at: product.deleted_at || ''
        };
    }

    function validateProductPayload(payload) {
        var producto = toTrimmedString(payload.producto);
        var costo = Number(payload.costo) || 0;
        var ganancia = parsePercentInput(payload.ganancia);
        var stock = Number(payload.stock);
        var idCategoria = Number(payload.id_categoria) || 0;
        var idMarca = Number(payload.id_marca) || 0;
        var estado = toTrimmedString(payload.estado);

        if (producto === '') {
            return 'Debes ingresar el nombre del producto.';
        }

        if (producto.length < 3) {
            return 'El nombre del producto debe tener al menos 3 caracteres.';
        }

        if (costo <= 0) {
            return 'El costo debe ser mayor a 0.';
        }

        if (Number.isNaN(ganancia) || ganancia < 0 || ganancia >= 100) {
            return 'La ganancia debe ser un porcentaje entre 0 y 99.9999.';
        }

        if (Number.isNaN(stock) || stock < 0) {
            return 'El stock debe ser 0 o mayor.';
        }

        if (idCategoria <= 0) {
            return 'Debes seleccionar una categoria.';
        }

        if (idMarca <= 0) {
            return 'Debes seleccionar una marca.';
        }

        if (estado !== '0' && estado !== '1') {
            return 'Debes seleccionar un estado valido.';
        }

        return '';
    }

    function buildStatusBadge(estado) {
        var isActive = Number(estado) === 1;
        var badgeClass = isActive ? 'text-bg-success' : 'text-bg-secondary';
        var badgeText = isActive ? 'Activo' : 'Inactivo';

        return '<span class="badge ' + badgeClass + '">' + badgeText + '</span>';
    }

    function buildProductPhoto(product) {
        if (!product.foto) {
            return '<span class="product-photo-placeholder"><i class="fas fa-image"></i></span>';
        }

        return '<img class="product-photo-thumb" src="' + escapeHtml(product.foto) + '" alt="' + escapeHtml(product.producto) + '">';
    }

    function buildDetailPhoto(product) {
        if (!product.foto) {
            return '<span class="product-photo-placeholder"><i class="fas fa-image"></i></span>';
        }

        return '<img class="product-photo-detail" src="' + escapeHtml(product.foto) + '" alt="' + escapeHtml(product.producto) + '">';
    }

    function buildActionButtons(product) {
        return '' +
            '<div class="product-actions">' +
                '<button type="button" class="btn btn-outline-primary product-action-button js-view-product" title="Ver detalle" data-id="' + escapeHtml(product.id_producto) + '"><i class="fas fa-eye"></i></button>' +
                '<button type="button" class="btn btn-outline-warning product-action-button js-edit-product" title="Editar" data-id="' + escapeHtml(product.id_producto) + '"><i class="fas fa-edit"></i></button>' +
                '<button type="button" class="btn btn-outline-danger product-action-button js-delete-product" title="Eliminar" data-id="' + escapeHtml(product.id_producto) + '" data-name="' + escapeHtml(product.producto) + '"><i class="fas fa-trash"></i></button>' +
            '</div>';
    }

    function buildInactiveActionButtons(product) {
        return '' +
            '<div class="inactive-product-actions">' +
                '<button type="button" class="btn btn-outline-success product-action-button js-restore-product" title="Restaurar" data-id="' + escapeHtml(product.id_producto) + '"><i class="fas fa-undo"></i></button>' +
                '<button type="button" class="btn btn-outline-danger product-action-button js-hard-delete-product" title="Eliminar definitivo" data-id="' + escapeHtml(product.id_producto) + '" data-name="' + escapeHtml(product.producto) + '"><i class="fas fa-trash"></i></button>' +
            '</div>';
    }

    function updateTableMode(pageSize) {
        var tableModes = 'table-size-10 table-size-20 table-size-50';
        $productTableShell.removeClass(tableModes).addClass('table-size-' + pageSize);
    }

    function updateTableHeight(rowCount) {
        var headerHeight = 48;
        var rowHeight = 64;
        var emptyRowHeight = 72;
        var visibleRows = Math.min(Math.max(Number(rowCount) || 0, 0), 10);
        var bodyHeight = visibleRows > 0 ? visibleRows * rowHeight : emptyRowHeight;
        var maxHeight = headerHeight + bodyHeight + 2;

        $productTableShell.addClass('dynamic-height').css('max-height', maxHeight + 'px');
    }

    function updateInactiveTableHeight(rowCount) {
        var headerHeight = 48;
        var rowHeight = 64;
        var emptyRowHeight = 72;
        var visibleRows = Math.min(Math.max(Number(rowCount) || 0, 0), 10);
        var bodyHeight = visibleRows > 0 ? visibleRows * rowHeight : emptyRowHeight;
        var maxHeight = headerHeight + bodyHeight + 2;

        $inactiveProductTableShell.addClass('dynamic-height').css('max-height', maxHeight + 'px');
    }

    function updateControlsState(isLoading) {
        var currentPage = Number(pagination.page) || 1;
        var totalPages = Number(pagination.total_pages) || 1;

        $pageSizeSelect.prop('disabled', isLoading);
        $productCategoryFilter.prop('disabled', isLoading);
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
        var hasCategory = (Number(pagination.id_categoria) || 0) > 0;

        if (total === 0) {
            $productSummary.text(hasSearch || hasCategory ? 'No se encontraron productos con el filtro actual' : 'Mostrando 0 de 0 productos');
        } else {
            $productSummary.text('Mostrando ' + startRecord + ' - ' + endRecord + ' de ' + total + ' productos');
        }

        $productPageStatus.text('Pagina ' + currentPage + ' de ' + totalPages);
        $pageSizeSelect.val(String(pageSize));
        updateTableMode(pageSize);
    }

    function updateInactiveSummary() {
        var total = Number(inactiveState.total) || 0;
        var hasSearch = toTrimmedString(inactiveState.search) !== '';

        if (total === 0) {
            $inactiveProductSummary.text(hasSearch ? 'No se encontraron productos inactivos con el filtro actual' : 'Mostrando 0 productos inactivos');
            $inactiveProductStatus.text('Sin resultados');
            return;
        }

        $inactiveProductSummary.text('Mostrando ' + total + ' productos inactivos');
        $inactiveProductStatus.text(total + ' resultado(s)');
    }

    function renderMessageRow(message, textClass) {
        $productTableBody.html('<tr><td colspan="8" class="text-center py-4 ' + textClass + '">' + escapeHtml(message) + '</td></tr>');
        updateTableHeight(0);
    }

    function renderRows(products) {
        if (!products.length) {
            renderMessageRow('No hay productos registrados.', 'text-muted');
            return;
        }

        var rows = $.map(products, function (item) {
            var product = normalizeProduct(item);
            return '' +
                '<tr>' +
                    '<td>' + escapeHtml(product.id_producto) + '</td>' +
                    '<td>' + buildProductPhoto(product) + '</td>' +
                    '<td>' + escapeHtml(product.producto) + '</td>' +
                    '<td>' + escapeHtml(formatMoney(product.precio)) + '</td>' +
                    '<td>' + escapeHtml(product.stock) + '</td>' +
                    '<td>' + buildStatusBadge(product.estado) + '</td>' +
                    '<td>' + escapeHtml(formatDate(product.created_at)) + '</td>' +
                    '<td>' + buildActionButtons(product) + '</td>' +
                '</tr>';
        }).join('');

        $productTableBody.html(rows);
        updateTableHeight(products.length);
    }

    function renderInactiveMessageRow(message, textClass) {
        $inactiveProductTableBody.html('<tr><td colspan="8" class="text-center py-4 ' + textClass + '">' + escapeHtml(message) + '</td></tr>');
        updateInactiveTableHeight(0);
    }

    function renderInactiveRows(products) {
        if (!products.length) {
            renderInactiveMessageRow('No hay productos inactivos registrados.', 'text-muted');
            return;
        }

        var rows = $.map(products, function (item) {
            var product = normalizeProduct(item);
            return '' +
                '<tr>' +
                    '<td>' + escapeHtml(product.id_producto) + '</td>' +
                    '<td>' + buildProductPhoto(product) + '</td>' +
                    '<td>' + escapeHtml(product.producto) + '</td>' +
                    '<td>' + escapeHtml(formatMoney(product.precio)) + '</td>' +
                    '<td>' + escapeHtml(product.stock) + '</td>' +
                    '<td>' + buildStatusBadge(product.estado) + '</td>' +
                    '<td>' + escapeHtml(formatDate(product.deleted_at)) + '</td>' +
                    '<td>' + buildInactiveActionButtons(product) + '</td>' +
                '</tr>';
        }).join('');

        $inactiveProductTableBody.html(rows);
        updateInactiveTableHeight(products.length);
    }

    function getFormSnapshot($form) {
        var fields = $form.serialize();
        var fileNames = $.map($form.find('input[type="file"]'), function (input) {
            return input.files && input.files.length ? input.files[0].name : '';
        }).join('|');

        return fields + '|files:' + fileNames;
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
        $confirmExitTitle.text(modalKey === 'create' ? 'Salir de crear producto' : 'Salir de editar producto');
        $confirmExitCopy.html(
            modalKey === 'create'
                ? 'Has ingresado informacion nueva en el formulario de <strong>crear producto</strong>. Puedes volver, salir sin guardar o guardar antes de cerrar.'
                : 'Has realizado cambios en el formulario de <strong>editar producto</strong>. Puedes volver, salir sin guardar o guardar antes de cerrar.'
        );
        $confirmExitSaveButton.text(modalKey === 'create' ? 'Guardar producto' : 'Guardar cambios');

        if (confirmExitModal) {
            window.setTimeout(function () {
                confirmExitModal.show();
            }, 0);
        }
    }

    function bindDragScroll() {
        var interactiveSelector = 'button, a, input, textarea, select, label, .product-actions, .product-action-button';
        var dragThreshold = 8;

        $productTableShell.on('pointerdown', function (event) {
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

        $productTableShell.on('pointermove', function (event) {
            if (!dragState.active) {
                return;
            }

            if (!dragState.dragging) {
                if (Math.abs(event.clientX - dragState.startX) < dragThreshold && Math.abs(event.clientY - dragState.startY) < dragThreshold) {
                    return;
                }

                dragState.dragging = true;
                $(this).addClass('is-dragging');
            }

            event.preventDefault();
            this.scrollLeft = dragState.scrollLeft - (event.clientX - dragState.startX);
            this.scrollTop = dragState.scrollTop - (event.clientY - dragState.startY);
        });

        $productTableShell.on('pointerup pointercancel lostpointercapture', function (event) {
            if (dragState.pointerId !== null && typeof event.pointerId !== 'undefined' && dragState.pointerId !== event.pointerId) {
                return;
            }

            dragState.active = false;
            dragState.dragging = false;
            dragState.pointerId = null;
            $(this).removeClass('is-dragging');
        });
    }

    function loadProductDetails(productId, onSuccess) {
        $.ajax({
            url: getUrl,
            method: 'GET',
            dataType: 'json',
            cache: false,
            data: {
                id_producto: Number(productId) || 0
            }
        })
            .done(function (response) {
                if (!response.success || !response.product) {
                    showInfoModal('Producto no disponible', response.message || 'No se pudo obtener el detalle del producto seleccionado.');
                    return;
                }

                onSuccess(normalizeProduct(response.product));
            })
            .fail(function (xhr) {
                showInfoModal('No se pudo consultar el producto', extractResponseMessage(xhr, 'Ocurrio un problema al consultar el producto. Intenta nuevamente.'));
            });
    }

    function runMainSearch() {
        var searchValue = toTrimmedString($productSearchInput.val());

        if (searchValue === '') {
            showInfoModal('Campo de busqueda vacio', 'Debes ingresar un ID o un nombre antes de hacer clic en Filtrar.');
            $productSearchInput.trigger('focus');
            return;
        }

        pagination.search = searchValue;

        if (isNumericSearch(searchValue)) {
            ensureModals();
            setModalDialogSize($detailModalElement, 'modal-lg');
            loadProductDetails(Number(searchValue), function (product) {
                fillDetailModal(product);

                if (detailModal) {
                    detailModal.show();
                }
            });
            return;
        }

        loadProducts(1, pagination.page_size);
    }

    function fillDetailModal(product) {
        $('#detailProductPhoto').html(buildDetailPhoto(product));
        $('#detailProductId').text(product.id_producto);
        $('#detailProductName').text(emptyText(product.producto));
        $('#detailProductStatus').html(buildStatusBadge(product.estado));
        $('#detailProductCost').text(formatMoney(product.costo));
        $('#detailProductProfit').text(formatPercent(product.ganancia));
        $('#detailProductPrice').text(formatMoney(product.precio));
        $('#detailProductStock').text(product.stock);
        $('#detailProductCategory').text(emptyText(product.nombre_categoria));
        $('#detailProductBrand').text(emptyText(product.nombre_marca));
        $('#detailProductCreated').text(formatDate(product.created_at));
        $('#detailProductUpdated').text(formatDate(product.updated_at));
    }

    function fillEditModal(product) {
        $('#edit_id_producto').val(product.id_producto);
        $('#edit_id_producto_readonly').val(product.id_producto);
        $('#edit_producto').val(product.producto);
        $('#edit_costo').val(product.costo.toFixed(2));
        $('#edit_ganancia').val(formatPercentInput(product.ganancia * 100));
        $('#edit_precio').val(product.precio.toFixed(2));
        $('#edit_stock').val(product.stock);
        $('#edit_id_categoria').val(String(product.id_categoria));
        $('#edit_id_marca').val(String(product.id_marca));
        $('#edit_estado').val(String(product.estado));
        $('#edit_foto').val('');
    }

    function prepareDeleteModal(product) {
        $('#delete_id_producto').val(product.id_producto);
        $('#deleteProductName').text(product.producto);
    }

    function prepareHardDeleteInactiveModal(product) {
        $('#hard_delete_id_producto').val(product.id_producto);
        $('#hardDeleteInactiveName').text(product.producto);
    }

    function prepareRestoreInactiveModal(product) {
        $('#restore_id_producto').val(product.id_producto);
        $('#restoreInactiveName').text(product.producto);
    }

    function loadProducts(page, pageSize) {
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
                search: toTrimmedString(pagination.search),
                id_categoria: Number(pagination.id_categoria) || 0
            }
        })
            .done(function (response) {
                if (!response.success) {
                    renderMessageRow(response.message || 'No se pudo cargar la tabla.', 'text-danger');
                    return;
                }

                pagination = $.extend({}, pagination, response.pagination || {});
                pagination.search = response.search || toTrimmedString(pagination.search);
                pagination.id_categoria = Number(response.id_categoria) || 0;
                renderRows(response.products || []);
                updatePaginationInfo();
            })
            .fail(function (xhr, textStatus) {
                if (textStatus === 'abort') {
                    return;
                }

                renderMessageRow(extractResponseMessage(xhr, 'Ocurrio un problema al cargar los productos.'), 'text-danger');
            })
            .always(function () {
                listRequest = null;
                updateControlsState(false);
            });
    }

    function loadInactiveProducts() {
        if (inactiveListRequest) {
            inactiveListRequest.abort();
            inactiveListRequest = null;
        }

        renderInactiveMessageRow('Cargando productos inactivos...', 'text-muted');

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
                renderInactiveRows(response.products || []);
                updateInactiveSummary();
            })
            .fail(function (xhr, textStatus) {
                if (textStatus === 'abort') {
                    return;
                }

                renderInactiveMessageRow(extractResponseMessage(xhr, 'Ocurrio un problema al cargar los productos inactivos.'), 'text-danger');
            })
            .always(function () {
                inactiveListRequest = null;
            });
    }

    function submitProductForm($form, url, $feedback, successCallback, loadingLabel, finalLabel) {
        var $submitButton = $form.find('button[type="submit"]');
        var formData = new FormData($form[0]);
        var gananciaValue = parsePercentInput($form.find('[name="ganancia"]').val());
        var validationMessage = validateProductPayload({
            producto: $form.find('[name="producto"]').val(),
            costo: $form.find('[name="costo"]').val(),
            ganancia: gananciaValue,
            stock: $form.find('[name="stock"]').val(),
            id_categoria: $form.find('[name="id_categoria"]').val(),
            id_marca: $form.find('[name="id_marca"]').val(),
            estado: $form.find('[name="estado"]').val()
        });

        showFeedback($feedback, '', 'info');

        if (validationMessage !== '') {
            showFeedback($feedback, validationMessage, 'warning');
            showInfoModal('Datos incompletos', validationMessage);
            return;
        }

        formData.set('ganancia', String(gananciaValue));
        setButtonLoading($submitButton, true, loadingLabel);

        $.ajax({
            url: url,
            method: 'POST',
            dataType: 'json',
            data: formData,
            processData: false,
            contentType: false
        })
            .done(function (response) {
                if (!response.success) {
                    showFeedback($feedback, response.message || 'No se pudo guardar el producto.', 'danger');
                    return;
                }

                showFeedback($feedback, response.message || 'Producto guardado correctamente.', 'success');
                successCallback(response);
            })
            .fail(function (xhr) {
                showFeedback($feedback, extractResponseMessage(xhr, 'No se pudo guardar el producto en este momento.'), 'danger');
            })
            .always(function () {
                setButtonLoading($submitButton, false, finalLabel);
            });
    }

    $pageSizeSelect.on('change', function () {
        loadProducts(1, Number($(this).val()) || 10);
    });

    $productSearchInput.on('input', function () {
        var currentValue = toTrimmedString($(this).val());
        pagination.search = currentValue;

        if (searchTimer) {
            clearTimeout(searchTimer);
        }

        if (currentValue !== '' && isNumericSearch(currentValue)) {
            return;
        }

        searchTimer = window.setTimeout(function () {
            loadProducts(1, pagination.page_size);
        }, 350);
    });

    $productSearchInput.on('keydown', function (event) {
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
        pagination.id_categoria = 0;
        $productSearchInput.val('');
        $productCategoryFilter.val('');
        loadProducts(1, pagination.page_size);
    });

    $productCategoryFilter.on('change', function () {
        pagination.id_categoria = Number($(this).val()) || 0;
        loadProducts(1, pagination.page_size);
    });

    $openInactiveModalButton.on('click', function () {
        ensureModals();
        setModalDialogSize($inactiveProductsModalElement, 'modal-xl');
        inactiveState.search = '';
        $inactiveProductSearchInput.val('');
        updateInactiveSummary();
        loadInactiveProducts();

        if (inactiveProductsModal) {
            inactiveProductsModal.show();
        }
    });

    $inactiveProductSearchInput.on('input', function () {
        inactiveState.search = toTrimmedString($(this).val());

        if (inactiveSearchTimer) {
            clearTimeout(inactiveSearchTimer);
        }

        inactiveSearchTimer = window.setTimeout(function () {
            loadInactiveProducts();
        }, 350);
    });

    $clearInactiveSearchButton.on('click', function () {
        inactiveState.search = '';
        $inactiveProductSearchInput.val('');
        loadInactiveProducts();
    });

    $prevPageButton.on('click', function (event) {
        event.preventDefault();

        if (listRequest || pagination.page <= 1) {
            return;
        }

        loadProducts(pagination.page - 1, pagination.page_size);
    });

    $nextPageButton.on('click', function (event) {
        event.preventDefault();

        if (listRequest || pagination.page >= pagination.total_pages) {
            return;
        }

        loadProducts(pagination.page + 1, pagination.page_size);
    });

    $openCreateModalButton.on('click', function () {
        ensureModals();
        setModalDialogSize($createModalElement, 'modal-lg');

        if ($createProductForm.length) {
            $createProductForm[0].reset();
            updateCalculatedPrice('create');
            setFormInitialState('create', $createProductForm);
        }
        showFeedback($createFeedback, '', 'info');

        if (createModal) {
            createModal.show();
        }
    });

    $('#create_costo, #create_ganancia').on('input', function () {
        updateCalculatedPrice('create');
    });

    $('#edit_costo, #edit_ganancia').on('input', function () {
        updateCalculatedPrice('edit');
    });

    $('#create_ganancia, #edit_ganancia').on('blur', function () {
        var percent = parsePercentInput($(this).val());

        if (!Number.isNaN(percent)) {
            $(this).val(formatPercentInput(percent));
        }
    });

    $productTableBody.on('click', '.js-view-product', function () {
        var productId = $(this).data('id');
        ensureModals();
        setModalDialogSize($detailModalElement, 'modal-lg');

        loadProductDetails(productId, function (product) {
            fillDetailModal(product);

            if (detailModal) {
                detailModal.show();
            }
        });
    });

    $productTableBody.on('click', '.js-edit-product', function () {
        var productId = $(this).data('id');
        ensureModals();
        setModalDialogSize($editModalElement, 'modal-lg');

        loadProductDetails(productId, function (product) {
            fillEditModal(product);
            setFormInitialState('edit', $editProductForm);
            showFeedback($editFeedback, '', 'info');

            if (editModal) {
                editModal.show();
            }
        });
    });

    $productTableBody.on('click', '.js-delete-product', function () {
        var productId = $(this).data('id');
        ensureModals();
        setModalDialogSize($deleteModalElement, 'modal-sm');

        loadProductDetails(productId, function (product) {
            prepareDeleteModal(product);
            showFeedback($deleteFeedback, '', 'info');

            if (deleteModal) {
                deleteModal.show();
            }
        });
    });

    $inactiveProductTableBody.on('click', '.js-restore-product', function () {
        var product = {
            id_producto: Number($(this).data('id')) || 0,
            producto: $(this).closest('tr').find('td').eq(2).text() || ''
        };

        ensureModals();
        setModalDialogSize($restoreInactiveProductModalElement, 'modal-sm');
        prepareRestoreInactiveModal(product);
        showFeedback($restoreInactiveFeedback, '', 'info');

        if (restoreInactiveProductModal) {
            restoreInactiveProductModal.show();
        }
    });

    $inactiveProductTableBody.on('click', '.js-hard-delete-product', function () {
        var product = {
            id_producto: Number($(this).data('id')) || 0,
            producto: $(this).data('name') || ''
        };

        ensureModals();
        setModalDialogSize($hardDeleteInactiveProductModalElement, 'modal-sm');
        prepareHardDeleteInactiveModal(product);
        showFeedback($hardDeleteInactiveFeedback, '', 'info');

        if (hardDeleteInactiveProductModal) {
            hardDeleteInactiveProductModal.show();
        }
    });

    $createProductForm.on('submit', function (event) {
        event.preventDefault();

        submitProductForm(
            $createProductForm,
            createUrl,
            $createFeedback,
            function () {
                $createProductForm[0].reset();
                updateCalculatedPrice('create');
                setFormInitialState('create', $createProductForm);
                formState.create.allowClose = true;
                loadProducts(1, pagination.page_size);

                window.setTimeout(function () {
                    if (createModal) {
                        createModal.hide();
                    }
                }, 650);
            },
            'Guardando...',
            'Guardar producto'
        );
    });

    $editProductForm.on('submit', function (event) {
        event.preventDefault();

        submitProductForm(
            $editProductForm,
            updateUrl,
            $editFeedback,
            function () {
                setFormInitialState('edit', $editProductForm);
                formState.edit.allowClose = true;
                loadProducts(pagination.page, pagination.page_size);

                window.setTimeout(function () {
                    if (editModal) {
                        editModal.hide();
                    }
                }, 650);
            },
            'Actualizando...',
            'Guardar cambios'
        );
    });

    $deleteProductForm.on('submit', function (event) {
        var $submitButton = $deleteProductForm.find('button[type="submit"]');

        event.preventDefault();
        showFeedback($deleteFeedback, '', 'info');
        setButtonLoading($submitButton, true, 'Eliminando...');

        $.ajax({
            url: deleteUrl,
            method: 'POST',
            dataType: 'json',
            data: $deleteProductForm.serialize()
        })
            .done(function (response) {
                if (!response.success) {
                    showFeedback($deleteFeedback, response.message || 'No se pudo eliminar el producto.', 'danger');
                    return;
                }

                showFeedback($deleteFeedback, response.message || 'Producto eliminado correctamente.', 'success');
                loadProducts(pagination.page, pagination.page_size);

                window.setTimeout(function () {
                    if (deleteModal) {
                        deleteModal.hide();
                    }
                }, 650);
            })
            .fail(function (xhr) {
                showFeedback($deleteFeedback, extractResponseMessage(xhr, 'No se pudo eliminar el producto en este momento.'), 'danger');
            })
            .always(function () {
                setButtonLoading($submitButton, false, 'Confirmar eliminacion');
            });
    });

    $hardDeleteInactiveProductForm.on('submit', function (event) {
        var $submitButton = $hardDeleteInactiveProductForm.find('button[type="submit"]');

        event.preventDefault();
        showFeedback($hardDeleteInactiveFeedback, '', 'info');
        setButtonLoading($submitButton, true, 'Eliminando...');

        $.ajax({
            url: hardDeleteUrl,
            method: 'POST',
            dataType: 'json',
            data: $hardDeleteInactiveProductForm.serialize()
        })
            .done(function (response) {
                if (!response.success) {
                    showFeedback($hardDeleteInactiveFeedback, response.message || 'No se pudo eliminar definitivamente.', 'danger');
                    return;
                }

                showFeedback($hardDeleteInactiveFeedback, response.message || 'Producto eliminado definitivamente.', 'success');
                loadInactiveProducts();
                loadProducts(pagination.page, pagination.page_size);

                window.setTimeout(function () {
                    if (hardDeleteInactiveProductModal) {
                        hardDeleteInactiveProductModal.hide();
                    }
                }, 750);
            })
            .fail(function (xhr) {
                showFeedback($hardDeleteInactiveFeedback, extractResponseMessage(xhr, 'No se pudo eliminar definitivamente el producto en este momento.'), 'danger');
            })
            .always(function () {
                setButtonLoading($submitButton, false, 'Confirmar eliminacion');
            });
    });

    $restoreInactiveProductForm.on('submit', function (event) {
        var $submitButton = $restoreInactiveProductForm.find('button[type="submit"]');

        event.preventDefault();
        showFeedback($restoreInactiveFeedback, '', 'info');
        setButtonLoading($submitButton, true, 'Restaurando...');

        $.ajax({
            url: restoreUrl,
            method: 'POST',
            dataType: 'json',
            data: $restoreInactiveProductForm.serialize()
        })
            .done(function (response) {
                if (!response.success) {
                    showFeedback($restoreInactiveFeedback, response.message || 'No se pudo restaurar el producto.', 'danger');
                    return;
                }

                showFeedback($restoreInactiveFeedback, response.message || 'Producto restaurado correctamente.', 'success');
                loadInactiveProducts();
                loadProducts(1, pagination.page_size);

                window.setTimeout(function () {
                    if (restoreInactiveProductModal) {
                        restoreInactiveProductModal.hide();
                    }
                }, 750);
            })
            .fail(function (xhr) {
                showFeedback($restoreInactiveFeedback, extractResponseMessage(xhr, 'No se pudo restaurar el producto en este momento.'), 'danger');
            })
            .always(function () {
                setButtonLoading($submitButton, false, 'Restaurar producto');
            });
    });

    $createModalElement.on('hide.bs.modal', function (event) {
        if (formState.create.allowClose || !hasUnsavedChanges('create', $createProductForm)) {
            return;
        }

        event.preventDefault();
        openExitPrompt('create');
    });

    $editModalElement.on('hide.bs.modal', function (event) {
        if (formState.edit.allowClose || !hasUnsavedChanges('edit', $editProductForm)) {
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
            $createProductForm.trigger('submit');
            return;
        }

        formState.edit.pendingSave = true;
        $editProductForm.trigger('submit');
    });

    updatePaginationInfo();
    bindDragScroll();
    loadProducts(1, pagination.page_size);
});
