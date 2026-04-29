<?php
declare(strict_types=1);

// =========================================================
// INCLUDE: FOOTER
// Pie de pagina y carga global de jQuery y Bootstrap JS.
// =========================================================



// Render del pie de pagina y carga de scripts.
// Tecnologia asociada: jQuery para interacciones y Bootstrap JS para modals/componentes.
function renderFooter(array $scripts = []): void
{
    ?>
        <footer class="app-footer bg-dark text-white py-4">
            <div class="container text-center">
                <small>KMLLogistics &copy; <?= date('Y'); ?>.</small>
            </div>
        </footer>
        </div>
        <script src="Pages/Assets/JS/Framework/JQuery/jquery.js"></script>
        <script src="Pages/Assets/Css/Framework/BootStrap/js/bootstrap.bundle.min.js"></script>
        <?php foreach ($scripts as $script): ?>
            <script src="<?= htmlspecialchars($script, ENT_QUOTES, 'UTF-8'); ?>" data-page-script="true"></script>
        <?php endforeach; ?>
        <script src="Pages/Assets/JS/Framework/AppNavigation.js"></script>
    </body>
    </html>
    <?php
}

