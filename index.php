<?php
// =========================================================
// ARCHIVO PRINCIPAL: INDEX
// Enrutador principal del proyecto con estructura MVC.
// =========================================================

declare(strict_types=1);

// Inicio de sesion global para login y logout.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Carga de clases, controladores e includes principales.
require_once __DIR__ . '/Pages/Includes/Load classes/Load classes.php';

// Ruta principal del sistema.
$page = $_GET['page'] ?? 'home';

// Cierre de sesion.
if ($page === 'logout') {
    session_unset();
    session_destroy();
    header('Location: index.php?page=login&status=logout');
    exit;
}

// Enrutamiento centralizado de vistas bajo el patron MVC.
switch ($page) {
    case 'home':
        $data = [
            'current_user' => $_SESSION['user'] ?? null,
        ];
        require __DIR__ . '/Pages/Views/Home/Home.php';
        break;

    case 'login':
        $controller = new LoginController();
        $data = $controller->handleRequest();
        require __DIR__ . '/Pages/Views/Login/Login.php';
        break;

    case 'register':
        $controller = new RegisterController();
        $data = $controller->handleRequest();
        require __DIR__ . '/Pages/Views/Register/Register.php';
        break;

    case 'category':
        $controller = new CategoryController();
        $data = $controller->handleRequest();
        require __DIR__ . '/Pages/Views/Category/Category.php';
        break;

    case 'tipodocumento':
        $controller = new TipoDocumentoController();
        $data = $controller->handleRequest();
        require __DIR__ . '/Pages/Views/TipoDocumento/TipoDocumento.php';
        break;
        
    case 'brand':
        $controller = new BrandController();
        $data = $controller->handleRequest();
        require __DIR__ . '/Pages/Views/Brand/Brand.php';
        break;

    default:
        header('Location: index.php?page=home');
        exit;
}
