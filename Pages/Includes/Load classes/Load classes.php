<?php
// =========================================================
// INCLUDE: LOAD CLASSES
// Carga manual de clases base para el flujo MVC.
// =========================================================

declare(strict_types=1);

// Ruta base de la carpeta Pages.
$pagesRoot = dirname(__DIR__, 2);

// Carga manual de configuracion, modelos, controladores e includes.
// Tecnologia asociada: estructura MVC + POO.
require_once $pagesRoot . '/Config/Database.php';

require_once $pagesRoot . '/Models/Category/Category.php';
require_once $pagesRoot . '/Models/Category/CategoryCRUD.php';

require_once $pagesRoot . '/Models/Providers/Provider.php';
require_once $pagesRoot . '/Models/Providers/ProviderCRUD.php';
require_once $pagesRoot . '/Controller/Providers/ProviderController.php';

require_once $pagesRoot . '/Models/TipoDocumento/TipoDocumento.php';
require_once $pagesRoot . '/Models/TipoDocumento/TipoDocumentoCRUD.php';
require_once $pagesRoot . '/Controller/TipoDocumento/TipoDocumentoController.php';

require_once $pagesRoot . '/Models/Users/User.php';
require_once $pagesRoot . '/Models/Users/UserCRUD.php';

require_once $pagesRoot . '/Controller/Category/CategoryController.php';

require_once $pagesRoot . '/Models/Brand/Brand.php';
require_once $pagesRoot . '/Models/Brand/BrandCRUD.php';
require_once $pagesRoot . '/Controller/Brand/BrandController.php';

require_once $pagesRoot . '/Models/Product/Product.php';
require_once $pagesRoot . '/Models/Product/ProductCRUD.php';
require_once $pagesRoot . '/Controller/Product/ProductController.php';

require_once $pagesRoot . '/Controller/Login/LoginController.php';
require_once $pagesRoot . '/Controller/Register/RegisterController.php';

require_once $pagesRoot . '/Includes/Header/Header.php';
require_once $pagesRoot . '/Includes/Menu/Menu.php';
require_once $pagesRoot . '/Includes/Footer/Footer.php';
