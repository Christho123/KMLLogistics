// =========================================================
// SCRIPT: PROFILE
// AJAX para perfil, foto y codigos con contador.
// =========================================================
$(function () {
    var timers = {};

    function showFeedback($element, message, type) {
        if (!message) {
            $element.addClass('d-none').removeClass('alert-success alert-danger alert-warning alert-info').text('');
            return;
        }

        $element.removeClass('d-none alert-success alert-danger alert-warning alert-info').addClass('alert-' + type).text(message);
    }

    function escapeHtml(value) {
        return $('<div>').text(value === null || typeof value === 'undefined' ? '' : value).html();
    }

    function withCacheBuster(url) {
        if (!url) {
            return '';
        }

        var separator = String(url).indexOf('?') === -1 ? '?' : '&';
        return url + separator + 'v=' + Date.now();
    }

    function isAdminRole(role) {
        return ['admin', 'Admin', 'Administrador'].indexOf(String(role || '')) !== -1;
    }

    function syncMenu(profile) {
        var $menuButton = $('.navbar .dropdown-toggle').first();
        var $menuName = $menuButton.find('span').first();
        var $adminItems = $('.js-admin-nav-item');

        if ($menuName.length) {
            $menuName.text(profile.nombres || 'Usuario');
        }

        if (isAdminRole(profile.rol)) {
            $adminItems.removeClass('d-none');
        } else {
            $adminItems.addClass('d-none');
        }
    }

    function extractResponseMessage(xhr, fallbackMessage) {
        if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
            return xhr.responseJSON.message;
        }

        return fallbackMessage;
    }

    function setLoading($button, isLoading, label) {
        if (isLoading) {
            $button.data('original-html', $button.html()).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>' + label);
            return;
        }

        $button.prop('disabled', false).html($button.data('original-html') || label);
    }

    function startCountdown(key, seconds, $target) {
        var remaining = Number(seconds) || 300;

        if (timers[key]) {
            clearInterval(timers[key]);
        }

        function render() {
            var minutes = Math.floor(remaining / 60);
            var secondsLeft = remaining % 60;
            $target.text('Codigo activo: ' + minutes + ':' + String(secondsLeft).padStart(2, '0'));
            remaining -= 1;

            if (remaining < 0) {
                clearInterval(timers[key]);
                $target.text('Codigo vencido');
            }
        }

        render();
        timers[key] = window.setInterval(render, 1000);
    }

    function syncProfile(profile) {
        if (!profile) {
            return;
        }

        $('#profile_nombres').val(profile.nombres || '');
        $('#profile_apellidos').val(profile.apellidos || '');
        $('#profile_correo').val(profile.correo || '');
        $('#profile_rol').val(profile.rol || 'usuario');
        $('#profile_id_tipo_documento').val(String(profile.id_tipo_documento || ''));
        $('#profile_numero_documento').val(profile.numero_documento || '');

        if (profile.foto) {
            var photoUrl = withCacheBuster(profile.foto);

            $('#profileAvatar').removeClass('d-none').attr('src', photoUrl);
            $('#profileAvatarFallback').addClass('d-none');
            var $menuButton = $('.navbar .dropdown-toggle').first();
            var $menuImage = $menuButton.find('img');

            if ($menuImage.length) {
                $menuImage.attr('src', photoUrl);
            } else {
                $menuButton.find('.fa-user-circle').replaceWith('<img src="' + escapeHtml(photoUrl) + '" alt="Perfil" style="width:28px;height:28px;border-radius:50%;object-fit:cover;border:1px solid rgba(255,193,7,.45)">');
            }
        } else {
            $('#profileAvatar').addClass('d-none').attr('src', '');
            $('#profileAvatarFallback').removeClass('d-none');
            $('.navbar .dropdown-toggle img').replaceWith('<i class="fas fa-user-circle"></i>');
        }

        syncMenu(profile);

        if (Number(profile.email_verificado) === 1) {
            $('#emailVerificationPanel').addClass('d-none');
        } else {
            $('#emailVerificationPanel').removeClass('d-none');
        }
    }

    $('#profileForm').on('submit', function (event) {
        var $button = $(this).find('button[type="submit"]');
        event.preventDefault();
        showFeedback($('#profileFeedback'), '', 'info');
        setLoading($button, true, 'Guardando...');

        $.ajax({
            url: 'Api/Profile/Update.php',
            method: 'POST',
            dataType: 'json',
            data: $(this).serialize()
        })
            .done(function (response) {
                if (!response.success) {
                    showFeedback($('#profileFeedback'), response.message || 'No se pudo actualizar el perfil.', 'danger');
                    return;
                }

                syncProfile(response.profile);
                showFeedback($('#profileFeedback'), response.message || 'Perfil actualizado.', 'success');
            })
            .fail(function (xhr) {
                showFeedback($('#profileFeedback'), extractResponseMessage(xhr, 'No se pudo actualizar el perfil.'), 'danger');
            })
            .always(function () {
                setLoading($button, false, 'Guardar perfil');
            });
    });

    $('#photoForm').on('submit', function (event) {
        var $button = $(this).find('button[type="submit"]');
        event.preventDefault();
        showFeedback($('#photoFeedback'), '', 'info');
        setLoading($button, true, 'Subiendo...');

        $.ajax({
            url: 'Api/Profile/UploadPhoto.php',
            method: 'POST',
            dataType: 'json',
            data: new FormData(this),
            processData: false,
            contentType: false
        })
            .done(function (response) {
                if (!response.success) {
                    showFeedback($('#photoFeedback'), response.message || 'No se pudo subir la foto.', 'danger');
                    return;
                }

                syncProfile(response.profile);
                $('#photoForm')[0].reset();
                showFeedback($('#photoFeedback'), response.message || 'Foto actualizada.', 'success');
            })
            .fail(function (xhr) {
                showFeedback($('#photoFeedback'), extractResponseMessage(xhr, 'No se pudo subir la foto.'), 'danger');
            })
            .always(function () {
                setLoading($button, false, 'Subir foto');
            });
    });

    $('#deletePhotoButton').on('click', function () {
        var $button = $(this);
        showFeedback($('#photoFeedback'), '', 'info');
        setLoading($button, true, 'Eliminando...');

        $.ajax({
            url: 'Api/Profile/DeletePhoto.php',
            method: 'POST',
            dataType: 'json'
        })
            .done(function (response) {
                if (!response.success) {
                    showFeedback($('#photoFeedback'), response.message || 'No se pudo eliminar la foto.', 'danger');
                    return;
                }

                syncProfile(response.profile);
                showFeedback($('#photoFeedback'), response.message || 'Foto eliminada.', 'success');
            })
            .fail(function (xhr) {
                showFeedback($('#photoFeedback'), extractResponseMessage(xhr, 'No se pudo eliminar la foto.'), 'danger');
            })
            .always(function () {
                setLoading($button, false, 'Eliminar foto');
            });
    });

    $('#sendEmailCodeButton').on('click', function () {
        var $button = $(this);
        showFeedback($('#emailFeedback'), '', 'info');
        setLoading($button, true, 'Enviando...');

        $.post('Api/Profile/SendEmailCode.php', null, null, 'json')
            .done(function (response) {
                showFeedback($('#emailFeedback'), response.message || 'Codigo enviado.', response.success ? 'success' : 'danger');
                if (response.success) {
                    startCountdown('email', response.expires_in || 300, $('#emailCountdown'));
                }
            })
            .fail(function (xhr) {
                showFeedback($('#emailFeedback'), extractResponseMessage(xhr, 'No se pudo enviar el codigo.'), 'danger');
            })
            .always(function () {
                setLoading($button, false, 'Enviar codigo');
            });
    });

    $('#confirmEmailButton').on('click', function () {
        $.post('Api/Profile/ConfirmEmail.php', { code: $('#email_code').val() }, null, 'json')
            .done(function (response) {
                showFeedback($('#emailFeedback'), response.message || 'Email verificado.', response.success ? 'success' : 'danger');
                syncProfile(response.profile);
            })
            .fail(function (xhr) {
                showFeedback($('#emailFeedback'), extractResponseMessage(xhr, 'No se pudo confirmar el codigo.'), 'danger');
            });
    });

    $('#sendPasswordCodeButton').on('click', function () {
        var $button = $(this);
        showFeedback($('#passwordFeedback'), '', 'info');
        setLoading($button, true, 'Enviando...');

        $.post('Api/Profile/SendPasswordCode.php', null, null, 'json')
            .done(function (response) {
                showFeedback($('#passwordFeedback'), response.message || 'Codigo enviado.', response.success ? 'success' : 'danger');
                if (response.success) {
                    startCountdown('password', response.expires_in || 300, $('#passwordCountdown'));
                }
            })
            .fail(function (xhr) {
                showFeedback($('#passwordFeedback'), extractResponseMessage(xhr, 'No se pudo enviar el codigo.'), 'danger');
            })
            .always(function () {
                setLoading($button, false, 'Enviar codigo');
            });
    });

    $('#changePasswordButton').on('click', function () {
        $.post('Api/Profile/ChangePassword.php', {
            code: $('#password_code').val(),
            password: $('#new_password').val(),
            confirm_password: $('#confirm_password').val()
        }, null, 'json')
            .done(function (response) {
                showFeedback($('#passwordFeedback'), response.message || 'Password actualizada.', response.success ? 'success' : 'danger');
            })
            .fail(function (xhr) {
                showFeedback($('#passwordFeedback'), extractResponseMessage(xhr, 'No se pudo cambiar la password.'), 'danger');
            });
    });
});
