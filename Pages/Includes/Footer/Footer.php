<?php

declare(strict_types=1);

// Render del pie de pagina y carga de scripts.
function renderFooter(array $scripts = []): void
{
    ?>
        <footer class="bg-dark text-white py-4 mt-5">
            <div class="container text-center">
                <small>KMLLogistics &copy; <?= date('Y'); ?>.</small>
            </div>
        </footer>
        <script src="Pages/Assets/JS/Framework/JQuery/jquery.js"></script>
        <script src="Pages/Assets/Css/Framework/BootStrap/js/bootstrap.bundle.min.js"></script>
        <?php foreach ($scripts as $script): ?>
            <script src="<?= htmlspecialchars($script, ENT_QUOTES, 'UTF-8'); ?>"></script>
        <?php endforeach; ?>
    </body>
    </html>
    <?php
}
