<?php

declare(strict_types=1);

// Carga de estilos de la vista de categorias.
renderHeader('KMLLogistics | Categorias', [
    'Pages/Assets/Css/Pages/Category/Category.css',
]);
renderMenu('category', $data['current_user']);
?>

<main>
    <section id="categorias" class="container py-5">
        <div class="category-wrapper">
            <div class="mb-4">
                <span class="section-badge">Categoria</span>
                <h2 class="fw-bold mt-3 mb-2">Listado de categorias</h2>
                <p class="text-muted mb-0">Consulta en tiempo real de las categorias registradas en el sistema.</p>
            </div>

            <div class="category-toolbar d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                <div class="d-flex align-items-center gap-2">
                    <label for="pageSizeSelect" class="form-label mb-0 fw-semibold">Registros</label>
                    <select id="pageSizeSelect" class="form-select">
                        <option value="10" selected>10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>

            <div class="category-table-shell table-size-10 shadow-sm rounded-4">
                <table class="table table-striped table-bordered align-middle mb-0 category-table">
                    <thead class="table-warning">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Estado</th>
                            <th>Creado</th>
                        </tr>
                    </thead>
                    <tbody id="categoryTableBody">
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Cargando categorias...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="category-pagination d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-3">
                <div class="category-page-status text-muted" id="categoryPageStatus">
                    Pagina 1 de 1
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-secondary" id="prevPageButton">Anterior</button>
                    <button type="button" class="btn btn-warning" id="nextPageButton">Siguiente</button>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
renderFooter([
    'Pages/Assets/JS/Pages/Category/Category.js',
]);