<?php
declare(strict_types=1);

// =========================================================
// CONFIGURACION: MAIL
// Datos SMTP usados por PHPMailer para enviar codigos.
// =========================================================



function getMailConfig(): array
{
    return [
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'username' => 'kmllogistics3@gmail.com',
        'password' => 'rsum oiqo awgf gecy',
        'encryption' => 'tls',
        'from_email' => 'kmllogistics3@gmail.com',
        'from_name' => 'KMLLogistics',
        'debug' => false,
        'timeout' => 8,
        'notify_admin_actions' => true,
    ];
}

