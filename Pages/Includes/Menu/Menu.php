<?php
// =========================================================
// INCLUDE: MENU
// Navegacion principal reutilizable del sistema.
// =========================================================

declare(strict_types=1);

// Render del menu principal de navegacion.
function renderMenu(string $currentPage, ?array $currentUser = null): void
{
    ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <!-- Icono de Font Awesome para identidad visual del proyecto -->
                <i class="fas fa-truck-fast me-2"></i>KMLLogistics
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link<?= $currentPage === 'home' ? ' active' : ''; ?>" href="index.php?page=home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?= $currentPage === 'category' ? ' active' : ''; ?>" href="index.php?page=category">Categoria</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?= $currentPage === 'brand' ? ' active' : ''; ?>" href="index.php?page=brand">Marca</a>
                    </li>
                    <?php if ($currentUser): ?>
                        <li class="nav-item">
                            <span class="nav-link text-warning">
                                <!-- Icono de Font Awesome para representar al usuario autenticado -->
                                <i class="fas fa-user-circle me-1"></i>
                                <?= htmlspecialchars($currentUser['nombres'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-light btn-sm" href="index.php?page=logout">Salir</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="btn btn-outline-light btn-sm" href="index.php?page=login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-warning btn-sm" href="index.php?page=register">Registro</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <?php
}
