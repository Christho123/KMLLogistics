$(function () {
    // Scroll suave para enlaces internos.
    $('a[href^="#"]').on('click', function (event) {
        var target = $($(this).attr('href'));

        if (target.length) {
            event.preventDefault();
            $('html, body').animate({
                scrollTop: target.offset().top - 70
            }, 500);
        }
    });

    var $categoryTableBody = $('#categoryTableBody');
    var categoriesUrl = 'Api/Category/List.php';
    var isLoadingCategories = false;

    // Escape basico para pintar HTML seguro.
    function escapeHtml(value) {
        return $('<div>').text(value === null ? '' : value).html();
    }

    // Formato simple de fecha para la tabla.
    function formatDate(dateTime) {
        if (!dateTime) {
            return 'Sin fecha';
        }

        return dateTime.replace(' ', ' | ');
    }

    // Render dinamico del cuerpo de la tabla.
    function renderRows(categories) {
        if (!categories.length) {
            $categoryTableBody.html(
                '<tr><td colspan="5" class="text-center py-4 text-muted">No hay categorias registradas.</td></tr>'
            );
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

    // Consulta AJAX periodica de categorias.
    function loadCategories() {
        if (isLoadingCategories) {
            return;
        }

        isLoadingCategories = true;

        $.ajax({
            url: categoriesUrl,
            method: 'GET',
            dataType: 'json',
            cache: false,
            success: function (response) {
                if (response.success) {
                    renderRows(response.categories || []);
                } else {
                    $categoryTableBody.html(
                        '<tr><td colspan="5" class="text-center py-4 text-danger">No se pudo cargar la tabla.</td></tr>'
                    );
                }
            },
            error: function () {
                $categoryTableBody.html(
                    '<tr><td colspan="5" class="text-center py-4 text-danger">Error de conexion al cargar categorias.</td></tr>'
                );
            },
            complete: function () {
                isLoadingCategories = false;
            }
        });
    }

    // Carga inicial y refresco automatico cada 3 segundos.
    loadCategories();
    setInterval(function () {
        loadCategories();
    }, 3000);
});
