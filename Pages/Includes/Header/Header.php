<?php
// =========================================================
// INCLUDE: HEADER
// Encabezado HTML y carga global de Bootstrap y Font Awesome.
// =========================================================

declare(strict_types=1);

// Render del encabezado HTML y carga de estilos.
// Tecnologia asociada: Bootstrap para estilos base y Font Awesome para iconografia.
function renderHeader(string $title, array $styles = []): void
{
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
        <link rel="stylesheet" href="Pages/Assets/Css/Framework/BootStrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="Pages/Assets/Css/Framework/Fontawesome/css/all.min.css">
        <style>
            html,
            body {
                min-height: 100%;
            }

            body {
                display: flex;
                flex-direction: column;
            }

            #app-shell {
                min-height: 100vh;
                display: flex;
                flex: 1 0 auto;
                flex-direction: column;
            }

            #app-shell > main {
                flex: 1 0 auto;
            }

            .app-footer {
                margin-top: auto;
            }
        </style>
        <?php foreach ($styles as $style): ?>
            <link rel="stylesheet" href="<?= htmlspecialchars($style, ENT_QUOTES, 'UTF-8'); ?>" data-page-style="true">
        <?php endforeach; ?>
    </head>
    <body>
        <div id="app-shell">
    <?php
}
