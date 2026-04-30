<?php
declare(strict_types=1);

// =========================================================
// INCLUDE: MENU
// Navegacion principal reutilizable del sistema.
// =========================================================



// Render del menu principal de navegacion.
function renderMenu(string $currentPage, ?array $currentUser = null): void
{
    $isAdmin = $currentUser && in_array((string) ($currentUser['rol'] ?? ''), ['admin', 'Admin', 'Administrador'], true);
    $userPhoto = (string) ($currentUser['foto'] ?? '');
    ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-truck-fast me-2"></i>KMLLogistics
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">

                    <li class="nav-item">
                        <a class="nav-link<?= $currentPage === 'home' ? ' active' : ''; ?>" href="index.php?page=home">
                            Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link<?= $currentPage === 'category' ? ' active' : ''; ?>" href="index.php?page=category">
                            Categoria
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link<?= $currentPage === 'tipodocumento' ? ' active' : ''; ?>" href="index.php?page=tipodocumento">
                            Tipo documento
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link<?= $currentPage === 'providers' ? ' active' : ''; ?>" href="index.php?page=providers">
                            Proveedores
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link<?= $currentPage === 'brand' ? ' active' : ''; ?>" href="index.php?page=brand">
                            Marca
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link<?= $currentPage === 'product' ? ' active' : ''; ?>" href="index.php?page=product">
                            Producto
                        </a>
                    </li>

                    <?php if ($currentUser): ?>
                        <li class="nav-item js-admin-nav-item<?= $isAdmin ? '' : ' d-none'; ?>">
                            <a class="nav-link<?= $currentPage === 'audit' ? ' active' : ''; ?>" href="index.php?page=audit">
                                Auditoria
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($currentUser): ?>
                        <li class="nav-item dropdown">
                            <button class="btn btn-dark dropdown-toggle d-flex align-items-center gap-2 text-warning" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php if ($userPhoto !== ''): ?>
                                    <img src="<?= htmlspecialchars($userPhoto, ENT_QUOTES, 'UTF-8'); ?>" alt="Perfil" style="width:28px;height:28px;border-radius:50%;object-fit:cover;border:1px solid rgba(255,193,7,.45)">
                                <?php else: ?>
                                    <i class="fas fa-user-circle"></i>
                                <?php endif; ?>
                                <span><?= htmlspecialchars($currentUser['nombres'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li>
                                    <a class="dropdown-item<?= $currentPage === 'profile' ? ' active' : ''; ?>" href="index.php?page=profile">
                                        <i class="fas fa-id-card me-2"></i>Perfil
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="index.php?page=logout">
                                        <i class="fas fa-right-from-bracket me-2"></i>Salir
                                    </a>
                                </li>
                            </ul>
                        </li>

                    <?php else: ?>

                        <li class="nav-item">
                            <a class="btn btn-outline-light btn-sm" href="index.php?page=login">
                                Login
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="btn btn-warning btn-sm" href="index.php?page=register">
                                Registro
                            </a>
                        </li>

                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>
    <?php
}

