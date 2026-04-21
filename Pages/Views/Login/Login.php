<?php

declare(strict_types=1);

// Carga de estilos de la vista login.
renderHeader('KMLLogistics | Login', [
    'Pages/Assets/Css/Pages/Login/Login.css',
]);
renderMenu('login');
?>

<!-- Formulario de inicio de sesion -->
<main class="auth-page">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="card border-0 shadow-lg auth-card">
                    <div class="card-body p-4 p-lg-5">
                        <div class="text-center mb-4">
                            <div class="icon-circle mx-auto mb-3">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h1 class="h3 fw-bold">Iniciar sesion</h1>
                            <p class="text-muted mb-0">Ingresa con tu cuenta registrada.</p>
                        </div>

                        <?php if ($data['message'] !== ''): ?>
                            <div class="alert alert-<?= htmlspecialchars($data['message_type'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?= htmlspecialchars($data['message'], ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="index.php?page=login" class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Correo</label>
                                <input type="email" class="form-control" name="correo" placeholder="correo@ejemplo.com" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control password-input" name="password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-12 d-grid">
                                <button type="submit" class="btn btn-warning btn-lg">Ingresar</button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <span class="text-muted">No tienes cuenta?</span>
                            <a href="index.php?page=register" class="fw-semibold link-dark">Registrate aqui</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
renderFooter([
    'Pages/Assets/JS/Pages/Login/Login.js',
]);
