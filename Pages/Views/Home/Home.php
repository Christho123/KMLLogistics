<?php
declare(strict_types=1);

// =========================================================
// VISTA: HOME
// Pantalla principal con carrusel e informacion corporativa.
// =========================================================

renderHeader('KMLLogistics | Inicio', [
    'Pages/Assets/Css/Pages/Home/Home.css',
]);
renderMenu('home', $data['current_user']);

$slides = [
    [
        'image' => 'Pages/Images/Carousel/slide-1.jpg',
        'title' => 'KML Logistics International',
        'subtitle' => 'Soluciones integrales para importar, exportar y mover carga con seguridad.',
    ],
    [
        'image' => 'Pages/Images/Carousel/slide-2.jpg',
        'title' => 'Transporte internacional',
        'subtitle' => 'Coordinamos operaciones maritimas, aereas y terrestres para tu negocio.',
    ],
    [
        'image' => 'Pages/Images/Carousel/slide-3.jpg',
        'title' => 'Gestion aduanera',
        'subtitle' => 'Acompanamiento especializado para procesos de comercio exterior.',
    ],
    [
        'image' => 'Pages/Images/Carousel/slide-4.jpg',
        'title' => 'Carga protegida',
        'subtitle' => 'Seguimiento responsable desde el origen hasta el destino final.',
    ],
    [
        'image' => 'Pages/Images/Carousel/slide-5.jpg',
        'title' => 'Aliados estrategicos',
        'subtitle' => 'Red de contactos y proveedores para operaciones confiables.',
    ],
];

$stats = [
    ['value' => 3, 'suffix' => '', 'label' => 'modos de transporte', 'icon' => 'fa-route'],
    ['value' => 24, 'suffix' => '/7', 'label' => 'seguimiento operativo', 'icon' => 'fa-headset'],
    ['value' => 100, 'suffix' => '%', 'label' => 'enfoque en seguridad', 'icon' => 'fa-shield-halved'],
];

$services = [
    [
        'icon' => 'fa-ship',
        'title' => 'Transporte maritimo',
        'text' => 'Gestion de carga internacional para operaciones de importacion y exportacion.',
    ],
    [
        'icon' => 'fa-plane-departure',
        'title' => 'Carga aerea',
        'text' => 'Alternativas rapidas para mercancias sensibles al tiempo y alto valor.',
    ],
    [
        'icon' => 'fa-truck-fast',
        'title' => 'Transporte terrestre',
        'text' => 'Coordinacion local y regional para completar la cadena logistica.',
    ],
    [
        'icon' => 'fa-file-signature',
        'title' => 'Asesoria aduanera',
        'text' => 'Orientacion en documentacion, tramites y procesos de comercio exterior.',
    ],
];

$process = [
    ['title' => 'Diagnostico', 'text' => 'Revisamos el origen, destino, tipo de carga y tiempos requeridos.'],
    ['title' => 'Plan logistico', 'text' => 'Definimos ruta, modalidad, documentos y proveedores necesarios.'],
    ['title' => 'Ejecucion', 'text' => 'Coordinamos transporte, seguimiento y gestion aduanera.'],
    ['title' => 'Entrega segura', 'text' => 'Cerramos la operacion con control de llegada y comunicacion clara.'],
];

$values = [
    ['icon' => 'fa-award', 'title' => 'Excelencia', 'text' => 'Brindamos servicios logisticos de alta calidad, superando expectativas en cada operacion.'],
    ['icon' => 'fa-handshake', 'title' => 'Cliente primero', 'text' => 'Ofrecemos soluciones adaptadas a las necesidades reales de cada empresa o emprendedor.'],
    ['icon' => 'fa-gauge-high', 'title' => 'Eficiencia', 'text' => 'Optimizamos procesos para lograr rapidez, precision y reduccion de costos.'],
    ['icon' => 'fa-shield-halved', 'title' => 'Seguridad', 'text' => 'Cuidamos cada envio como propio desde el origen hasta el destino.'],
    ['icon' => 'fa-comments', 'title' => 'Transparencia', 'text' => 'Mantenemos comunicacion clara y constante durante todo el proceso logistico.'],
    ['icon' => 'fa-lightbulb', 'title' => 'Innovacion', 'text' => 'Mejoramos con tecnologia, nuevas estrategias y aprendizaje continuo.'],
    ['icon' => 'fa-user-tie', 'title' => 'Profesionalismo', 'text' => 'Actuamos con etica, respeto y alto nivel tecnico en cada servicio.'],
    ['icon' => 'fa-leaf', 'title' => 'Sostenibilidad', 'text' => 'Promovemos practicas responsables que contribuyen al cuidado del medio ambiente.'],
];
?>

<main class="home-page">
    <?php if (($_GET['status'] ?? '') === 'login_ok'): ?>
    <?php endif; ?>

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
                            <span class="hero-chip">KML Logistic S.A.C.</span>
                            <h1><?= htmlspecialchars($slide['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
                            <p><?= htmlspecialchars($slide['subtitle'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <div class="hero-actions">
                                <a href="#servicios" class="btn btn-warning btn-lg">Ver servicios</a>
                                <a href="#valores" class="btn btn-outline-light btn-lg">Conocer valores</a>
                            </div>
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

    <section class="home-stats">
        <div class="container">
            <div class="stats-panel js-reveal">
                <?php foreach ($stats as $stat): ?>
                    <article class="stat-card">
                        <i class="fas <?= htmlspecialchars($stat['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                        <strong>
                            <span class="js-count" data-count="<?= (int) $stat['value']; ?>">0</span><?= htmlspecialchars($stat['suffix'], ENT_QUOTES, 'UTF-8'); ?>
                        </strong>
                        <span><?= htmlspecialchars($stat['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="company-overview section-space">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-5 js-reveal">
                    <span class="section-chip">Quienes somos</span>
                    <h2 class="section-title mt-3">Logistica internacional con control de punta a punta</h2>
                    <p class="section-lead">
                        KML Logistic S.A.C. es una empresa peruana dedicada a brindar servicios
                        de logistica internacional, transporte de mercancias e importacion y
                        exportacion de productos.
                    </p>
                </div>
                <div class="col-lg-7">
                    <div class="overview-grid">
                        <article class="feature-card js-reveal">
                            <i class="fas fa-globe-americas"></i>
                            <h3>Comercio exterior</h3>
                            <p>Apoyo para empresas que importan hacia el Peru o exportan hacia mercados internacionales.</p>
                        </article>
                        <article class="feature-card js-reveal">
                            <i class="fas fa-boxes-stacked"></i>
                            <h3>Gestion de carga</h3>
                            <p>Soluciones integrales para transportar, almacenar y coordinar mercancias con eficiencia.</p>
                        </article>
                        <article class="feature-card feature-card-wide js-reveal">
                            <i class="fas fa-users-gear"></i>
                            <h3>Equipo especializado</h3>
                            <p>Personal con experiencia en logistica, comercio exterior y gestion de transporte para operaciones seguras.</p>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="servicios" class="services-section section-space">
        <div class="container">
            <div class="section-heading js-reveal">
                <span class="section-chip">Servicios</span>
                <h2 class="section-title mt-3">Soluciones para mover tu mercancia</h2>
                <p>Transporte, documentacion y seguimiento pensados para operaciones de comercio internacional.</p>
            </div>
            <div class="row g-3 mt-2">
                <?php foreach ($services as $service): ?>
                    <div class="col-md-6 col-xl-3">
                        <article class="service-card js-reveal">
                            <div class="service-icon">
                                <i class="fas <?= htmlspecialchars($service['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                            </div>
                            <h3><?= htmlspecialchars($service['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p><?= htmlspecialchars($service['text'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <button class="service-more" type="button" aria-label="Resaltar servicio">
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="mission-section section-space">
        <div class="container">
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-6">
                    <article class="mission-card mission-card-main js-reveal">
                        <span class="section-chip">Mision</span>
                        <h2>Servicio logistico eficiente y confiable</h2>
                        <p>
                            Brindar servicios logisticos eficientes y confiables que permitan a
                            nuestros clientes transportar e importar mercancias de manera segura,
                            optimizando los procesos de comercio internacional mediante soluciones
                            innovadoras y un servicio de calidad.
                        </p>
                    </article>
                </div>
                <div class="col-lg-6">
                    <article class="mission-card vision-card js-reveal">
                        <span class="section-chip">Vision</span>
                        <h2>Liderazgo en logistica internacional</h2>
                        <p>
                            Ser una empresa lider en el sector de logistica internacional en el
                            Peru, reconocida por su eficiencia, innovacion y compromiso en la
                            gestion de transporte y comercio exterior.
                        </p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="process-section section-space">
        <div class="container">
            <div class="section-heading js-reveal">
                <span class="section-chip">Proceso</span>
                <h2 class="section-title mt-3">De la planificacion a la entrega</h2>
            </div>
            <div class="process-track">
                <?php foreach ($process as $index => $step): ?>
                    <article class="process-step js-reveal">
                        <span><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT); ?></span>
                        <h3><?= htmlspecialchars($step['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p><?= htmlspecialchars($step['text'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="valores" class="values-section section-space">
        <div class="container">
            <div class="section-heading js-reveal">
                <span class="section-chip">Valores</span>
                <h2 class="section-title mt-3">Principios que guian cada operacion</h2>
                <p>Selecciona un valor para destacarlo y ver como se aplica en la gestion logistica.</p>
            </div>
            <div class="value-dashboard js-reveal">
                <div class="value-tabs" role="tablist" aria-label="Valores de KML Logistic">
                    <?php foreach ($values as $index => $value): ?>
                        <button class="value-tab <?= $index === 0 ? 'active' : ''; ?>" type="button" data-value-index="<?= $index; ?>">
                            <i class="fas <?= htmlspecialchars($value['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                            <span><?= htmlspecialchars($value['title'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
                <article class="value-detail">
                    <i id="valueDetailIcon" class="fas <?= htmlspecialchars($values[0]['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                    <span>Valor destacado</span>
                    <h3 id="valueDetailTitle"><?= htmlspecialchars($values[0]['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p id="valueDetailText"><?= htmlspecialchars($values[0]['text'], ENT_QUOTES, 'UTF-8'); ?></p>
                </article>
            </div>
        </div>
    </section>
</main>

<script id="homeValuesData" type="application/json">
<?= json_encode($values, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>

<?php
renderFooter([
    'Pages/Assets/JS/Pages/Home/Home.js',
]);
