// =========================================================
// SCRIPT: AUDIT
// Listado y detalle de auditorias.
// =========================================================
$(function () {
    var $auditTableShell = $('.audit-table-shell');
    var pagination = { page: 1, page_size: 10, total: 0, total_pages: 1, search: '' };
    var listRequest = null;
    var searchTimer = null;
    var detailModal = bootstrap.Modal.getOrCreateInstance($('#detailAuditModal')[0]);
    var dragState = {
        active: false,
        dragging: false,
        startX: 0,
        startY: 0,
        scrollLeft: 0,
        scrollTop: 0,
        pointerId: null
    };

    function escapeHtml(value) {
        return $('<div>').text(value === null || typeof value === 'undefined' ? '' : value).html();
    }

    function formatDate(dateTime) {
        return dateTime ? String(dateTime).replace(' ', ' | ') : 'Sin fecha';
    }

    function toTrimmedString(value) {
        return String(value === null || typeof value === 'undefined' ? '' : value).trim();
    }

    function buildStatusBadge(estado) {
        return Number(estado) === 1 ? '<span class="badge text-bg-success">Activo</span>' : '<span class="badge text-bg-secondary">Inactivo</span>';
    }

    function extractResponseMessage(xhr, fallbackMessage) {
        return xhr && xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : fallbackMessage;
    }

    function renderMessage(message, textClass) {
        $('#auditTableBody').html('<tr><td colspan="7" class="text-center py-4 ' + textClass + '">' + escapeHtml(message) + '</td></tr>');
        updateTableHeight(0);
    }

    function updateTableHeight(rowCount) {
        var headerHeight = 48;
        var rowHeight = 52;
        var emptyRowHeight = 72;
        var visibleRows = Math.min(Math.max(Number(rowCount) || 0, 0), 10);
        var bodyHeight = visibleRows > 0 ? visibleRows * rowHeight : emptyRowHeight;
        var maxHeight = headerHeight + bodyHeight + 2;

        $auditTableShell
            .addClass('dynamic-height')
            .css('max-height', maxHeight + 'px');
    }

    function renderRows(audits) {
        if (!audits.length) {
            renderMessage('No hay auditorias registradas.', 'text-muted');
            return;
        }

        $('#auditTableBody').html($.map(audits, function (audit) {
            return '' +
                '<tr>' +
                    '<td>' + escapeHtml(audit.id_audit) + '</td>' +
                    '<td>' + escapeHtml(audit.usuario || 'Sistema') + '</td>' +
                    '<td>' + escapeHtml(audit.modulo) + '</td>' +
                    '<td>' + escapeHtml(audit.accion) + '</td>' +
                    '<td>' + buildStatusBadge(audit.estado) + '</td>' +
                    '<td>' + escapeHtml(formatDate(audit.created_at)) + '</td>' +
                    '<td><div class="audit-actions">' +
                        '<button type="button" class="btn btn-outline-primary audit-action-button js-view-audit" data-id="' + escapeHtml(audit.id_audit) + '" title="Ver detalle"><i class="fas fa-eye"></i></button>' +
                    '</div></td>' +
                '</tr>';
        }).join(''));
        updateTableHeight(audits.length);
    }

    function updateInfo() {
        var total = Number(pagination.total) || 0;
        var page = Number(pagination.page) || 1;
        var pages = Number(pagination.total_pages) || 1;
        var pageSize = Number(pagination.page_size) || 10;
        var start = total === 0 ? 0 : ((page - 1) * pageSize) + 1;
        var end = Math.min(page * pageSize, total);

        $('#auditSummary').text(total === 0 ? 'Mostrando 0 de 0 auditorias' : 'Mostrando ' + start + ' - ' + end + ' de ' + total + ' auditorias');
        $('#auditPageStatus').text('Pagina ' + page + ' de ' + pages);
        $('#prevPageButton').prop('disabled', page <= 1);
        $('#nextPageButton').prop('disabled', page >= pages);
    }

    function loadAudits(page, pageSize) {
        if (listRequest) {
            listRequest.abort();
        }

        pagination.page = Number(page) || 1;
        pagination.page_size = Number(pageSize) || 10;
        listRequest = $.getJSON('Api/Audit/List.php', {
            page: pagination.page,
            page_size: pagination.page_size,
            search: pagination.search
        })
            .done(function (response) {
                if (!response.success) {
                    renderMessage(response.message || 'No se pudo cargar la auditoria.', 'text-danger');
                    return;
                }

                pagination = $.extend({}, pagination, response.pagination || {});
                renderRows(response.audits || []);
                updateInfo();
            })
            .fail(function (xhr, textStatus) {
                if (textStatus !== 'abort') {
                    renderMessage(extractResponseMessage(xhr, 'No se pudo cargar la auditoria.'), 'text-danger');
                }
            });
    }

    function renderDetail(audit) {
        var fields = [
            ['ID', audit.id_audit],
            ['Usuario', audit.usuario || 'Sistema'],
            ['Modulo', audit.modulo],
            ['Accion', audit.accion],
            ['Descripcion', audit.descripcion],
            ['Datos', audit.datos || '-'],
            ['Creado', formatDate(audit.created_at)]
        ];

        $('#auditDetailGrid').html($.map(fields, function (field) {
            var wide = field[0] === 'Datos' || field[0] === 'Descripcion' ? ' detail-card-wide' : '';
            return '<div class="detail-card' + wide + '"><span class="detail-label">' + escapeHtml(field[0]) + '</span><p class="detail-value">' + escapeHtml(field[1]) + '</p></div>';
        }).join(''));
    }

    function loadAuditDetails(auditId, onSuccess) {
        $.getJSON('Api/Audit/Get.php', { id_audit: Number(auditId) || 0 })
            .done(function (response) {
                if (response.success && response.audit) {
                    onSuccess(response.audit);
                }
            });
    }

    function runMainSearch() {
        var searchValue = toTrimmedString($('#auditSearchInput').val());

        if (searchValue === '') {
            $('#auditSearchInput').trigger('focus');
            return;
        }

        pagination.search = searchValue;

        loadAudits(1, pagination.page_size);
    }

    function bindDragScroll() {
        var interactiveSelector = 'button, a, input, textarea, select, label, .audit-actions, .audit-action-button';
        var dragThreshold = 8;

        $auditTableShell.on('pointerdown', function (event) {
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

        $auditTableShell.on('pointermove', function (event) {
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

        $auditTableShell.on('pointerup pointercancel lostpointercapture', function (event) {
            if (dragState.pointerId !== null && typeof event.pointerId !== 'undefined' && dragState.pointerId !== event.pointerId) {
                return;
            }

            dragState.active = false;
            dragState.dragging = false;
            dragState.pointerId = null;
            $(this).removeClass('is-dragging');
        });
    }

    $('#auditSearchInput').on('input', function () {
        var currentValue = toTrimmedString($(this).val());
        pagination.search = currentValue;

        if (searchTimer) {
            clearTimeout(searchTimer);
        }

        searchTimer = window.setTimeout(function () {
            loadAudits(1, pagination.page_size);
        }, 350);
    });

    $('#auditSearchInput').on('keydown', function (event) {
        if (event.key !== 'Enter') {
            return;
        }

        event.preventDefault();
        runMainSearch();
    });

    $('#filterSearchButton').on('click', function () {
        runMainSearch();
    });

    $('#clearSearchButton').on('click', function () {
        pagination.search = '';
        $('#auditSearchInput').val('');
        loadAudits(1, pagination.page_size);
    });

    $('#pageSizeSelect').on('change', function () {
        loadAudits(1, Number($(this).val()) || 10);
    });

    $('#prevPageButton').on('click', function () {
        if (pagination.page > 1) {
            loadAudits(pagination.page - 1, pagination.page_size);
        }
    });

    $('#nextPageButton').on('click', function () {
        if (pagination.page < pagination.total_pages) {
            loadAudits(pagination.page + 1, pagination.page_size);
        }
    });

    $('#auditTableBody').on('click', '.js-view-audit', function () {
        loadAuditDetails($(this).data('id'), function (audit) {
            renderDetail(audit);
            detailModal.show();
        });
    });

    updateInfo();
    bindDragScroll();
    loadAudits(1, pagination.page_size);
});
