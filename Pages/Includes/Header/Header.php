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
        <?php foreach ($styles as $style): ?>
            <link rel="stylesheet" href="<?= htmlspecialchars($style, ENT_QUOTES, 'UTF-8'); ?>">
        <?php endforeach; ?>
    </head>
    <body>
    <?php
}
