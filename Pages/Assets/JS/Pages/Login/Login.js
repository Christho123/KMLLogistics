$(function () {
    // Alterna la visibilidad de la password.
    $('.toggle-password').on('click', function () {
        var $input = $(this).siblings('.password-input');
        var isPassword = $input.attr('type') === 'password';

        $input.attr('type', isPassword ? 'text' : 'password');
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });
});
