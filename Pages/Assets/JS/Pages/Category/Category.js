$(function () {
    var $categoryTableBody = $('#categoryTableBody');
    var $categorySummary = $('#categorySummary');
    var $categoryPageStatus = $('#categoryPageStatus');
    var $pageSizeSelect = $('#pageSizeSelect');
    var $prevPageButton = $('#prevPageButton');
    var $nextPageButton = $('#nextPageButton');
    var $categoryTableShell = $('.category-table-shell');
    var listUrl = 'Api/Category/List.php';
    var watchUrl = 'Api/Category/Watch.php';
    var listRequest = null;
    var watchRequest = null;
    var categoryStats = {
        total: 0,
        latest_id: 0
    };
    var pagination = {
        page: 1,
        page_size: 10,
        total: 0,
        total_pages: 1,
        latest_id: 0
    };

    function escapeHtml(value) {
        return $('<div>').text(value === null ? '' : value).html();
    }

    function formatDate(dateTime) {
        if (!dateTime) {
            return 'Sin fecha';
        }

        return dateTime.replace(' ', ' | ');
    }

    function updateTableMode(pageSize) {
        var tableModes = 'table-size-10 table-size-20 table-size-50';
        $categoryTableShell.removeClass(tableModes).addClass('table-size-' + pageSize);
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

        if (total === 0) {
            $categorySummary.text('Mostrando 0 de 0 categorias');
        } else {
            $categorySummary.text('Mostrando ' + startRecord + ' - ' + endRecord + ' de ' + total + ' categorias');
        }

        $categoryPageStatus.text('Pagina ' + currentPage + ' de ' + totalPages);
        $pageSizeSelect.val(String(pageSize));
        updateTableMode(pageSize);
    }

    function renderMessageRow(message, textClass) {
        $categoryTableBody.html(
            '<tr><td colspan="5" class="text-center py-4 ' + textClass + '">' + escapeHtml(message) + '</td></tr>'
        );
    }

    function renderRows(categories) {
        if (!categories.length) {
            renderMessageRow('No hay categorias registradas.', 'text-muted');
            return;
        }

        var rows = $.map(categories, function (category) {
            var badgeClass = Number(category.estado) === 1 ? 'text-bg-success' : 'text-bg-secondary';
            var badgeText = Number(category.estado) === 1 ? 'Activo' : 'Inactivo';

            return '' +
                '<tr>' +
                    '<td>' + escapeHtml(category.id_categoria) + '</td>' +
                    '<td>' + escapeHtml(category.nombre_categoria) + '</td>' +
                    '<td>' + escapeHtml(category.descripcion) + '</td>' +
                    '<td><span class="badge ' + badgeClass + '">' + badgeText + '</span></td>' +
                    '<td>' + escapeHtml(formatDate(category.created_at)) + '</td>' +
                '</tr>';
        }).join('');

        $categoryTableBody.html(rows);
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
                page_size: pagination.page_size
            },
            dataType: 'json',
            cache: false
        })
            .done(function (response) {
                if (!response.success) {
                    renderMessageRow('No se pudo cargar la tabla.', 'text-danger');
                    return;
                }

                pagination = $.extend({}, pagination, response.pagination || {});
                categoryStats.total = Number(pagination.total) || 0;
                categoryStats.latest_id = Number(pagination.latest_id) || 0;
                renderRows(response.categories || []);
                updatePaginationInfo();
            })
            .fail(function (xhr, textStatus) {
                if (textStatus === 'abort') {
                    return;
                }

                renderMessageRow('Error de conexion al cargar categorias.', 'text-danger');
            })
            .always(function () {
                listRequest = null;
                updateControlsState(false);
            });
    }

    function watchCategoryChanges() {
        if (watchRequest || listRequest) {
            return;
        }

        watchRequest = $.ajax({
            url: watchUrl,
            method: 'GET',
            dataType: 'json',
            cache: false
        })
            .done(function (response) {
                var nextTotal;
                var nextLatestId;
                var nextTotalPages;
                var targetPage;

                if (!response.success) {
                    return;
                }

                nextTotal = Number(response.total) || 0;
                nextLatestId = Number(response.latest_id) || 0;

                if (nextTotal === categoryStats.total && nextLatestId === categoryStats.latest_id) {
                    return;
                }

                categoryStats.total = nextTotal;
                categoryStats.latest_id = nextLatestId;
                nextTotalPages = Math.max(1, Math.ceil(nextTotal / pagination.page_size));
                targetPage = Math.min(pagination.page, nextTotalPages);
                loadCategories(targetPage, pagination.page_size);
            })
            .always(function () {
                watchRequest = null;
            });
    }

    $pageSizeSelect.on('change', function () {
        loadCategories(1, Number($(this).val()) || 10);
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

    updatePaginationInfo();
    loadCategories(1, pagination.page_size);
    setInterval(watchCategoryChanges, 3000);
});