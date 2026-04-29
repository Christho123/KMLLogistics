<?php
declare(strict_types=1);

// =========================================================
// VISTA: PROFILE
// Configuracion del perfil del usuario.
// =========================================================



$profile = $data['profile'] ?? [];
$isEmailVerified = (int) ($profile['email_verificado'] ?? 0) === 1;

renderHeader('KMLLogistics | Perfil', [
    'Pages/Assets/Css/Pages/Profile/Profile.css',
]);
renderMenu('profile', $data['current_user']);
?>

<main>
    <section class="container py-5">
        <div class="profile-wrapper">
            <div class="profile-head mb-4">
                <div>
                    <span class="section-badge">Perfil</span>
                    <h2 class="fw-bold mt-3 mb-2">Configuracion del usuario</h2>
                    <p class="text-muted mb-0">Actualiza tus datos, foto, email y password desde un solo lugar.</p>
                </div>
                <div class="profile-avatar-shell">
                    <?php if (!empty($profile['foto'])): ?>
                        <img id="profileAvatar" src="<?= htmlspecialchars((string) $profile['foto'], ENT_QUOTES, 'UTF-8'); ?>" alt="Foto de perfil">
                    <?php else: ?>
                        <span id="profileAvatarFallback"><i class="fas fa-user"></i></span>
                        <img id="profileAvatar" class="d-none" src="" alt="Foto de perfil">
                    <?php endif; ?>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="profile-panel">
                        <h5 class="fw-bold mb-3"><i class="fas fa-address-card text-warning me-2"></i>Datos personales</h5>
                        <div id="profileFeedback" class="alert d-none"></div>
                        <form id="profileForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="profile_nombres">Nombres</label>
                                    <input type="text" class="form-control" id="profile_nombres" name="nombres" value="<?= htmlspecialchars((string) ($profile['nombres'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="profile_apellidos">Apellidos</label>
                                    <input type="text" class="form-control" id="profile_apellidos" name="apellidos" value="<?= htmlspecialchars((string) ($profile['apellidos'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="profile_correo">Email</label>
                                    <input type="email" class="form-control" id="profile_correo" name="correo" value="<?= htmlspecialchars((string) ($profile['correo'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="profile_rol">Rol</label>
                                    <select class="form-select" id="profile_rol" name="rol">
                                        <?php foreach ($data['roles'] as $role): ?>
                                            <option value="<?= htmlspecialchars($role, ENT_QUOTES, 'UTF-8'); ?>" <?= (string) ($profile['rol'] ?? '') === $role ? 'selected' : ''; ?>>
                                                <?= htmlspecialchars($role, ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="profile_id_tipo_documento">Tipo documento</label>
                                    <select class="form-select" id="profile_id_tipo_documento" name="id_tipo_documento">
                                        <?php foreach ($data['document_types'] as $type): ?>
                                            <option value="<?= (int) $type['id_tipo_documento']; ?>" <?= (int) ($profile['id_tipo_documento'] ?? 0) === (int) $type['id_tipo_documento'] ? 'selected' : ''; ?>>
                                                <?= htmlspecialchars((string) $type['nombre_tipo_documento'], ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="profile_numero_documento">Numero documento</label>
                                    <input type="text" class="form-control" id="profile_numero_documento" name="numero_documento" value="<?= htmlspecialchars((string) ($profile['numero_documento'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-warning fw-semibold">
                                    <i class="fas fa-save me-2"></i>Guardar perfil
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="profile-panel mb-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-image text-warning me-2"></i>Foto de perfil</h5>
                        <div id="photoFeedback" class="alert d-none"></div>
                        <form id="photoForm" enctype="multipart/form-data">
                            <input type="file" class="form-control mb-3" id="profile_foto" name="foto" accept="image/png,image/jpeg,image/webp">
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="submit" class="btn btn-warning fw-semibold">
                                    <i class="fas fa-upload me-2"></i>Subir foto
                                </button>
                                <button type="button" class="btn btn-outline-danger" id="deletePhotoButton">
                                    <i class="fas fa-trash me-2"></i>Eliminar foto
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="profile-panel mb-4 <?= $isEmailVerified ? 'd-none' : ''; ?>" id="emailVerificationPanel">
                        <h5 class="fw-bold mb-3"><i class="fas fa-envelope-circle-check text-warning me-2"></i>Verificar email</h5>
                        <div id="emailFeedback" class="alert d-none"></div>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="email_code" placeholder="Codigo de 6 digitos" maxlength="6">
                            <button type="button" class="btn btn-outline-secondary" id="sendEmailCodeButton">Enviar codigo</button>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="profile-countdown text-muted" id="emailCountdown">Sin codigo activo</span>
                            <button type="button" class="btn btn-warning fw-semibold" id="confirmEmailButton">Confirmar</button>
                        </div>
                    </div>

                    <div class="profile-panel">
                        <h5 class="fw-bold mb-3"><i class="fas fa-key text-warning me-2"></i>Cambiar password</h5>
                        <div id="passwordFeedback" class="alert d-none"></div>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="password_code" placeholder="Codigo de 6 digitos" maxlength="6">
                            <button type="button" class="btn btn-outline-secondary" id="sendPasswordCodeButton">Enviar codigo</button>
                        </div>
                        <div class="row g-3">
                            <div class="col-12">
                                <input type="password" class="form-control" id="new_password" placeholder="Nueva password">
                            </div>
                            <div class="col-12">
                                <input type="password" class="form-control" id="confirm_password" placeholder="Confirmar password">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="profile-countdown text-muted" id="passwordCountdown">Sin codigo activo</span>
                            <button type="button" class="btn btn-warning fw-semibold" id="changePasswordButton">Cambiar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
renderFooter([
    'Pages/Assets/JS/Pages/Profile/Profile.js',
]);

