<?php

declare(strict_types=1);

// Carga de estilos base de la vista principal.
renderHeader('KMLLogistics | Inicio', [
    'Pages/Assets/Css/Pages/Category/Category.css',
]);
renderMenu('home', $data['current_user']);

// Configuracion de slides del carrusel.
$slides = [
    [
        'image' => 'Pages/Images/Carousel/slide-1.jpg',
        'title' => 'KMLLogistics',
    ],
    [
        'image' => 'Pages/Images/Carousel/slide-2.jpg',
        'title' => 'KMLLogistics',
    ],
    [
        'image' => 'Pages/Images/Carousel/slide-3.jpg',
        'title' => 'KMLLogistics',
    ],
    [
        'image' => 'Pages/Images/Carousel/slide-4.jpg',
        'title' => 'KMLLogistics',
    ],
    [
        'image' => 'Pages/Images/Carousel/slide-5.jpg',
        'title' => 'KMLLogistics',
    ],
];
?>

<main>
    <?php if (($_GET['status'] ?? '') === 'login_ok'): ?>
        <section class="container pt-4">
            <div class="alert alert-success border-0 shadow-sm">
                Sesion iniciada correctamente. Bienvenido a KMLLogistics.
            </div>
        </section>
    <?php endif; ?>

    <!-- Seccion hero con carrusel principal -->
    <section id="inicio" class="hero-section">
        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <?php foreach ($slides as $index => $slide): ?>
                    <button
                        type="button"
                        data-bs-target="#heroCarousel"
                        data-bs-slide-to="<?= $index; ?>"
                        class="<?= $index === 0 ? 'active' : ''; ?>"
                        aria-current="<?= $index === 0 ? 'true' : 'false'; ?>"
                        aria-label="Slide <?= $index + 1; ?>"
                    ></button>
                <?php endforeach; ?>
            </div>

            <div class="carousel-inner">
                <?php foreach ($slides as $index => $slide): ?>
                    <?php $imagePath = dirname(__DIR__, 3) . '/' . $slide['image']; ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : ''; ?>">
                        <?php if (is_file($imagePath)): ?>
                            <img src="<?= htmlspecialchars($slide['image'], ENT_QUOTES, 'UTF-8'); ?>" class="d-block w-100 hero-image" alt="<?= htmlspecialchars($slide['title'], ENT_QUOTES, 'UTF-8'); ?>">
                        <?php else: ?>
                            <div class="hero-placeholder d-flex align-items-center justify-content-center">
                                <div class="text-center px-4">
                                    <i class="fas fa-image fa-3x mb-3"></i>
                                    <h2 class="fw-bold"><?= htmlspecialchars($slide['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                                    <span class="badge text-bg-warning text-dark"><?= htmlspecialchars($slide['image'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="carousel-caption text-start">
                            <span class="hero-chip">KMLLogistics</span>
                            <h1><?= htmlspecialchars($slide['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
                            <a href="#categorias" class="btn btn-warning btn-lg mt-2">Ir a categorias</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
    </section>

    <!-- Seccion Category con tabla cargada por AJAX -->
    <section id="categorias" class="container pb-5">
        <div class="category-wrapper">
            <div class="mb-4">
                <span class="section-badge">Categoria</span>
                <h2 class="fw-bold mt-3 mb-2">Listado de categorias</h2>
                <p class="text-muted mb-0">Tabla adicional presentada en la seccion de categoria.</p>
            </div>

            <div class="category-table-shell shadow-sm rounded-4">
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
        </div>
    </section>
</main>

<?php
renderFooter([
    'Pages/Assets/JS/Pages/Category/Category.js',
]);
