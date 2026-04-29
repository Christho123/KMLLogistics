<?php
declare(strict_types=1);

// =========================================================
// VISTA: REGISTER
// Formulario de registro de usuarios del sistema.
// =========================================================



// Carga de estilos de la vista registro.
renderHeader('KMLLogistics | Registro', [
    'Pages/Assets/Css/Pages/Register/Register.css',
]);
renderMenu('register');
?>

<!-- Formulario de registro -->
<main class="auth-page register-page">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card border-0 shadow-lg auth-card">
                    <div class="card-body p-4 p-lg-5">
                        <div class="text-center mb-4">
                            <div class="icon-circle mx-auto mb-3">
                                <!-- Icono de Font Awesome para representar alta de usuarios -->
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h1 class="h3 fw-bold">Registro de usuarios</h1>
                            <p class="text-muted mb-0">Esta cuenta sera utilizada para el login.</p>
                        </div>

                        <?php if ($data['message'] !== ''): ?>
                            <div class="alert alert-<?= htmlspecialchars($data['message_type'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?= htmlspecialchars($data['message'], ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="index.php?page=register" class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombres</label>
                                <input type="text" class="form-control" name="nombres" value="<?= htmlspecialchars($data['form_data']['nombres'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Apellidos</label>
                                <input type="text" class="form-control" name="apellidos" value="<?= htmlspecialchars($data['form_data']['apellidos'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Correo</label>
                                <input type="email" class="form-control" name="correo" value="<?= htmlspecialchars($data['form_data']['correo'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tipo de documento</label>
                                <select class="form-select" name="id_tipo_documento" required>
                                    <option value="">Selecciona un tipo de documento</option>
                                    <?php foreach (($data['document_types'] ?? []) as $documentType): ?>
                                        <option
                                            value="<?= (int) $documentType['id_tipo_documento']; ?>"
                                            <?= (int) ($data['form_data']['id_tipo_documento'] ?? 0) === (int) $documentType['id_tipo_documento'] ? 'selected' : ''; ?>
                                        >
                                            <?= htmlspecialchars($documentType['nombre_tipo_documento'], ENT_QUOTES, 'UTF-8'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Numero de documento</label>
                                <input type="text" class="form-control" name="numero_documento" value="<?= htmlspecialchars($data['form_data']['numero_documento'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control password-input" name="password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <!-- Icono de Font Awesome que cambia visualmente desde Register.js -->
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirmar password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control password-input" name="confirm_password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <!-- Icono de Font Awesome que cambia visualmente desde Register.js -->
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-12 d-grid">
                                <button type="submit" class="btn btn-warning btn-lg">Crear cuenta</button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <span class="text-muted">Ya tienes cuenta?</span>
                            <a href="index.php?page=login" class="fw-semibold link-dark">Inicia sesion</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
renderFooter([
    'Pages/Assets/JS/Pages/Register/Register.js',
]);

