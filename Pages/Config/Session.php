<?php
declare(strict_types=1);

// =========================================================
// CONFIGURACION: SESSION
// Mantiene la sesion activa hasta que el usuario cierre sesion.
// =========================================================



function startPersistentSession(): void
{
    if (session_status() !== PHP_SESSION_NONE) {
        return;
    }

    $lifetime = 315360000; // 10 anios.
    $isSecure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

    ini_set('session.gc_maxlifetime', (string) $lifetime);
    ini_set('session.cookie_lifetime', (string) $lifetime);

    session_set_cookie_params([
        'lifetime' => $lifetime,
        'path' => '/',
        'domain' => '',
        'secure' => $isSecure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();

    if (session_id() !== '') {
        session_refresh_persistent_cookie($lifetime, $isSecure);
    }
}

function session_refresh_persistent_cookie(int $lifetime, bool $isSecure): void
{
    $sessionName = session_name();
    $sessionId = session_id();

    if ($sessionName === '' || $sessionId === '') {
        return;
    }

    setcookie($sessionName, $sessionId, [
        'expires' => time() + $lifetime,
        'path' => '/',
        'domain' => '',
        'secure' => $isSecure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function destroyPersistentSession(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        startPersistentSession();
    }

    $_SESSION = [];

    $params = session_get_cookie_params();

    if (ini_get('session.use_cookies')) {
        setcookie(session_name(), '', [
            'expires' => time() - 42000,
            'path' => $params['path'] ?? '/',
            'domain' => $params['domain'] ?? '',
            'secure' => (bool) ($params['secure'] ?? false),
            'httponly' => (bool) ($params['httponly'] ?? true),
            'samesite' => $params['samesite'] ?? 'Lax',
        ]);
    }

    session_destroy();
}
