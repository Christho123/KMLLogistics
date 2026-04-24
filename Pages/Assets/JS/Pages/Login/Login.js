// =========================================================
// SCRIPT: LOGIN
// Interacciones del formulario de inicio de sesion.
// =========================================================
$(function () {
    // Alterna la visibilidad de la password.
    $('.toggle-password').on('click', function () {
        var $input = $(this).siblings('.password-input');
        var isPassword = $input.attr('type') === 'password';

        $input.attr('type', isPassword ? 'text' : 'password');
        // Se alternan iconos de Font Awesome para mostrar el estado visual del campo.
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });
});
