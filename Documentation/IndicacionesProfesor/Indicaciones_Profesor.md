# Indicaciones Profesor

## Alcance documentado
Este archivo resume las indicaciones aplicadas en los archivos propios del proyecto.

No se documentan librerias de terceros como:
- `Pages/Assets/Css/Framework/BootStrap/...`
- `Pages/Assets/Css/Framework/Fontawesome/...`
- `Pages/Assets/JS/Framework/JQuery/jquery.js`

## Estructura MVC
El proyecto sigue una estructura MVC basica:

- `index.php` ---- linea 4
  Explica que el archivo principal funciona como enrutador con estructura MVC.
- `index.php` ---- linea 28
  Se documenta el enrutamiento centralizado de vistas.
- `Pages/Controller/Category/CategoryController.php` ---- linea 10
  Se comenta el uso de MVC + POO en el controlador de categorias.
- `Pages/Controller/Login/LoginController.php` ---- linea 10
  Se comenta el uso de MVC + POO en el controlador de login.
- `Pages/Controller/Register/RegisterController.php` ---- linea 10
  Se comenta el uso de MVC + POO en el controlador de registro.
- `Pages/Models/Category/Category.php` ---- linea 11
  Modelo entidad de categoria.
- `Pages/Models/Category/CategoryCRUD.php` ---- linea 11
  Modelo de acceso a datos para categorias.
- `Pages/Models/Users/User.php` ---- linea 10
  Modelo entidad de usuario.
- `Pages/Models/Users/UserCRUD.php` ---- linea 11
  Modelo de acceso a datos para usuarios.
- `Pages/Views/Category/Category.php` ---- lineas 10-13
  Vista principal del modulo Category.
- `Pages/Views/Login/Login.php` ---- lineas 10-13
  Vista del login.
- `Pages/Views/Register/Register.php` ---- lineas 10-13
  Vista del registro.
- `Pages/Views/Home/Home.php` ---- lineas 10-13
  Vista del home.

## APIs creadas
Las APIs del modulo Category fueron comentadas como endpoints AJAX:

- `Api/Category/Create.php` ---- linea 3
- `Api/Category/Delete.php` ---- linea 3
- `Api/Category/Get.php` ---- linea 3
- `Api/Category/HardDelete.php` ---- linea 3
- `Api/Category/List.php` ---- linea 3
- `Api/Category/ListInactive.php` ---- linea 3
- `Api/Category/Restore.php` ---- linea 3
- `Api/Category/Update.php` ---- linea 3

## AJAX
Las peticiones AJAX del modulo Category se manejan en:

- `Pages/Assets/JS/Pages/Category/Category.js` ---- linea 580
  AJAX para obtener detalle de categoria.
- `Pages/Assets/JS/Pages/Category/Category.js` ---- linea 673
  AJAX para listar categorias activas.
- `Pages/Assets/JS/Pages/Category/Category.js` ---- linea 717
  AJAX para listar categorias inactivas.
- `Pages/Assets/JS/Pages/Category/Category.js` ---- linea 952
  AJAX para crear categoria.
- `Pages/Assets/JS/Pages/Category/Category.js` ---- linea 1004
  AJAX para editar categoria.
- `Pages/Assets/JS/Pages/Category/Category.js` ---- linea 1042
  AJAX para eliminar categoria.
- `Pages/Assets/JS/Pages/Category/Category.js` ---- linea 1078
  AJAX para eliminar definitivamente.
- `Pages/Assets/JS/Pages/Category/Category.js` ---- linea 1121
  AJAX para restaurar categoria.

## jQuery removeClass() y addClass()
La indicacion del profesor sobre cambiar tamano de modals con jQuery ya esta aplicada y comentada:

- `Pages/Assets/JS/Pages/Category/Category.js` ---- linea 138
  Comentario que explica el uso de `removeClass()` y `addClass()` para modals.
- `Pages/Assets/JS/Pages/Category/Category.js` ---- linea 140
  Funcion `setModalDialogSize($modalElement, sizeClass)`.
- `Pages/Assets/JS/Pages/Category/Category.js` ---- linea 148
  Uso de `removeClass('modal-sm modal-lg modal-xl')`.
- `Pages/Assets/JS/Pages/Category/Category.js` ---- linea 151
  Uso de `addClass(sizeClass)`.

Tambien se usan `removeClass()` y `addClass()` en otros escenarios visuales:

- `Pages/Assets/JS/Pages/Category/Category.js` ---- lineas 128-134
  Mostrar y ocultar feedback dentro de modals.
- `Pages/Assets/JS/Pages/Category/Category.js` ---- linea 232
  Cambiar clase visual del tamano de la tabla.
- `Pages/Assets/JS/Pages/Category/Category.js` ---- lineas 558 y 574
  Agregar y quitar clase de arrastre visual.

## Modals Bootstrap
Los modals estan construidos con Bootstrap y se comentaron individualmente:

- `Pages/Includes/Header/Header.php` ---- linea 20
  Carga de Bootstrap CSS.
- `Pages/Includes/Footer/Footer.php` ---- linea 20
  Carga de Bootstrap JS.
- `Pages/Views/Category/CreateCategoryModal.php` ---- lineas 3-4 y 9-10
- `Pages/Views/Category/EditCategoryModal.php` ---- lineas 3-4 y 9-10
- `Pages/Views/Category/DetailCategoryModal.php` ---- lineas 3-4 y 9-10
- `Pages/Views/Category/DeleteCategoryModal.php` ---- lineas 3-4 y 9-10
- `Pages/Views/Category/ConfirmExitCategoryModal.php` ---- lineas 3 y 9-10
- `Pages/Views/Category/InfoCategoryModal.php` ---- lineas 3 y 9-10
- `Pages/Views/Category/InactiveCategoriesModal.php` ---- lineas 3-4 y 9-10
- `Pages/Views/Category/HardDeleteInactiveCategoryModal.php` ---- lineas 3 y 9-10
- `Pages/Views/Category/RestoreInactiveCategoryModal.php` ---- lineas 3 y 9-10

## Font Awesome
Se agregaron comentarios donde se usan iconos Font Awesome.

### Vistas y menu
- `Pages/Includes/Menu/Menu.php` ---- linea 17
  Icono principal de la marca.
- `Pages/Includes/Menu/Menu.php` ---- linea 34
  Icono del usuario autenticado.
- `Pages/Views/Home/Home.php` ---- linea 69
  Icono de imagen cuando no existe el slide.
- `Pages/Views/Login/Login.php` ---- linea 26
  Icono de seguridad del login.
- `Pages/Views/Login/Login.php` ---- linea 49
  Icono del ojo para mostrar password.
- `Pages/Views/Register/Register.php` ---- linea 26
  Icono de alta de usuario.
- `Pages/Views/Register/Register.php` ---- lineas 75 y 85
  Iconos para mostrar password.
- `Pages/Views/Category/Category.php` ---- lineas 31, 42, 46, 54, 58
  Iconos de busqueda, filtro, limpiar, inactivos y crear.

### Modals Category
- `Pages/Views/Category/CreateCategoryModal.php` ---- lineas 14 y 48
- `Pages/Views/Category/EditCategoryModal.php` ---- lineas 14 y 53
- `Pages/Views/Category/DetailCategoryModal.php` ---- lineas 14 y 50
- `Pages/Views/Category/DeleteCategoryModal.php` ---- lineas 14 y 33
- `Pages/Views/Category/ConfirmExitCategoryModal.php` ---- linea 14
- `Pages/Views/Category/InfoCategoryModal.php` ---- linea 16
- `Pages/Views/Category/InactiveCategoriesModal.php` ---- lineas 14, 26 y 37
- `Pages/Views/Category/HardDeleteInactiveCategoryModal.php` ---- lineas 14 y 33
- `Pages/Views/Category/RestoreInactiveCategoryModal.php` ---- lineas 14 y 33

### Cambio de iconos desde JavaScript
- `Pages/Assets/JS/Pages/Login/Login.js` ---- linea 13
  Se usa `toggleClass('fa-eye fa-eye-slash')`.
- `Pages/Assets/JS/Pages/Register/Register.js` ---- linea 13
  Se usa `toggleClass('fa-eye fa-eye-slash')`.

## MySQL
La base de datos principal y su estructura SQL se documentan en:

- `BD/Empresa/KMLLogistics.sql` ---- lineas 14, 26, 37, 58, 71, 90
  Creacion de tablas principales.
- `BD/Empresa/KMLLogistics.sql` ---- lineas 166, 189, 224, 254, 273, 299, 319, 338, 357, 380, 395, 410, 439
  Creacion de procedimientos almacenados.

## PDO
PDO se usa como capa de acceso segura a la base de datos:

- `Pages/Config/Database.php` ---- lineas 9-11
  Funcion central de conexion PDO.
- `Pages/Config/Database.php` ---- lineas 27-35
  Configuracion segura de PDO.
- `Pages/Models/Category/CategoryCRUD.php` ---- lineas 4, 10, 45, 48, 50
  Uso de PDO con procedimientos almacenados.
- `Pages/Models/Users/UserCRUD.php` ---- lineas 4, 10, 25, 31, 41, 55
  Uso de PDO con `prepare()`, `bindValue()` y `query()`.

## POO
La programacion orientada a objetos se refleja en:

- `Pages/Models/Category/Category.php` ---- linea 11
- `Pages/Models/Category/CategoryCRUD.php` ---- linea 11
- `Pages/Models/Users/User.php` ---- linea 10
- `Pages/Models/Users/UserCRUD.php` ---- linea 11
- `Pages/Controller/Category/CategoryController.php` ---- linea 11
- `Pages/Controller/Login/LoginController.php` ---- linea 11
- `Pages/Controller/Register/RegisterController.php` ---- linea 11

## Comentarios agregados en archivos
Se agregaron comentarios tipo titulo y explicaciones breves en:

- `index.php`
- `Pages/Config/Database.php`
- `Pages/Includes/Header/Header.php`
- `Pages/Includes/Footer/Footer.php`
- `Pages/Includes/Load classes/Load classes.php`
- `Pages/Includes/Menu/Menu.php`
- `Pages/Models/Category/Category.php`
- `Pages/Models/Category/CategoryCRUD.php`
- `Pages/Models/Users/User.php`
- `Pages/Models/Users/UserCRUD.php`
- `Pages/Controller/Category/CategoryController.php`
- `Pages/Controller/Login/LoginController.php`
- `Pages/Controller/Register/RegisterController.php`
- `Api/Category/*.php`
- `Pages/Assets/JS/Pages/Category/Category.js`
- `Pages/Assets/JS/Pages/Login/Login.js`
- `Pages/Assets/JS/Pages/Register/Register.js`
- `Pages/Assets/Css/Pages/Category/Category.css`
- `Pages/Assets/Css/Pages/Home/Home.css`
- `Pages/Assets/Css/Pages/Login/Login.css`
- `Pages/Assets/Css/Pages/Register/Register.css`
- `Pages/Views/Home/Home.php`
- `Pages/Views/Login/Login.php`
- `Pages/Views/Register/Register.php`
- `Pages/Views/Category/*.php`

## Nota final
Las referencias de linea corresponden al estado actual del proyecto luego de agregar comentarios y documentacion.
