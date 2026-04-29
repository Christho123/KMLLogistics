# Documentacion General del Proyecto KMLLogistics

## 1. Titulo del trabajo

**Optimizacion de la gestion logistica e inventario mediante un sistema web para KMLLogistics**

Este titulo considera:

- **Problema:** gestion logistica e inventario que requiere orden, control y trazabilidad.
- **Solucion:** sistema web con arquitectura MVC, CRUD, auditoria, autenticacion y base de datos MySQL.
- **Empresa:** KMLLogistics / KML Logistic S.A.C.

---

## 2. Descripcion general

KMLLogistics es un sistema web desarrollado en PHP para apoyar la gestion interna de una empresa de logistica internacional. El sistema permite administrar categorias, tipos de documento, proveedores, marcas, productos, usuarios, perfil de usuario y auditoria de acciones.

El proyecto usa una arquitectura MVC simple:

- `index.php` funciona como router principal.
- `Pages/Controller` contiene la logica de cada modulo.
- `Pages/Models` contiene clases entidad y clases CRUD.
- `Pages/Views` contiene las pantallas y modals.
- `Api` contiene endpoints JSON consumidos por AJAX.
- `BD/Empresa/KMLLogistics.sql` contiene la base de datos completa.

---

## 3. Tecnologias utilizadas

- **PHP:** backend, vistas, controladores, endpoints, sesiones y validaciones.
- **POO:** clases para entidades, CRUD, controladores y servicios.
- **MVC:** separacion entre rutas, vistas, controladores y modelos.
- **PDO:** conexion segura con MySQL y ejecucion de procedimientos almacenados.
- **MySQL / MariaDB:** base de datos relacional.
- **Stored Procedures:** capa SQL para listar, crear, actualizar, eliminar y validar.
- **Bootstrap:** layout responsive, tablas, botones, formularios y modals.
- **Font Awesome:** iconografia en menu, botones, formularios y tarjetas.
- **jQuery:** eventos, DOM, AJAX, `addClass()` y `removeClass()`.
- **AJAX:** operaciones CRUD sin recargar la pagina.
- **PHPMailer:** envio de codigos y notificaciones por correo.
- **CSS propio por modulo:** estilos especificos en `Pages/Assets/Css/Pages`.
- **JavaScript propio por modulo:** logica de frontend en `Pages/Assets/JS/Pages`.

---

## 4. Estructura completa del proyecto

```text
KMLLogistics/
|-- index.php
|-- Api/
|   |-- Audit/
|   |   |-- Get.php
|   |   `-- List.php
|   |-- Brand/
|   |   |-- Create.php
|   |   |-- Delete.php
|   |   |-- Get.php
|   |   |-- HardDelete.php
|   |   |-- List.php
|   |   |-- ListInactive.php
|   |   |-- Restore.php
|   |   `-- Update.php
|   |-- Category/
|   |   |-- Create.php
|   |   |-- Delete.php
|   |   |-- Get.php
|   |   |-- HardDelete.php
|   |   |-- List.php
|   |   |-- ListInactive.php
|   |   |-- Restore.php
|   |   `-- Update.php
|   |-- Product/
|   |   |-- Create.php
|   |   |-- Delete.php
|   |   |-- Get.php
|   |   |-- HardDelete.php
|   |   |-- List.php
|   |   |-- ListInactive.php
|   |   |-- ProductImageHelper.php
|   |   |-- Restore.php
|   |   `-- Update.php
|   |-- Profile/
|   |   |-- ChangePassword.php
|   |   |-- ConfirmEmail.php
|   |   |-- DeletePhoto.php
|   |   |-- SendEmailCode.php
|   |   |-- SendPasswordCode.php
|   |   |-- Update.php
|   |   `-- UploadPhoto.php
|   |-- Providers/
|   |   |-- Create.php
|   |   |-- Delete.php
|   |   |-- Get.php
|   |   |-- HardDelete.php
|   |   |-- List.php
|   |   |-- ListInactive.php
|   |   |-- Restore.php
|   |   `-- Update.php
|   `-- TipoDocumento/
|       |-- Create.php
|       |-- Delete.php
|       |-- Get.php
|       |-- HardDelete.php
|       |-- List.php
|       |-- ListInactive.php
|       |-- Restore.php
|       `-- Update.php
|-- BD/
|   |-- Empresa/
|   |   |-- KMLLogistics.sql
|   |   |-- SP/
|   |   |   |-- Audit/
|   |   |   |   `-- SP.sql
|   |   |   |-- Brand/
|   |   |   |   `-- SP.sql
|   |   |   |-- Category/
|   |   |   |   `-- SP.sql
|   |   |   |-- Producto/
|   |   |   |   `-- SP.sql
|   |   |   |-- Provider/
|   |   |   |   `-- SP.sql
|   |   |   |-- TipoDocumento/
|   |   |   |   `-- SP.sql
|   |   |   `-- User/
|   |   |       `-- SP.sql
|   |   `-- Tablas/
|   |       `-- Tablas.sql
|   `-- Script-Profesor/
|       `-- Script-Profesor.sql
|-- Documentation/
|   |-- README.md
|   |-- API/
|   |   |-- Category.md
|   |   `-- DocumentType.md
|   |-- Audit/
|   |   `-- Audit.md
|   |-- Auth/
|   |   `-- Auth.md
|   |-- Brand/
|   |   `-- Brand.md
|   |-- Category/
|   |   `-- Category.md
|   |-- DocumentType/
|   |   `-- DocumentType.md
|   |-- General/
|   |   `-- Project.md
|   |-- Home/
|   |   `-- Home.md
|   |-- IndicacionesProfesor/
|   |   `-- Indicaciones_Profesor.md
|   |-- Product/
|   |   `-- Product.md
|   |-- Profile/
|   |   `-- Profile.md
|   `-- Providers/
|       `-- Providers.md
`-- Pages/
    |-- Config/
    |   |-- Database.php
    |   `-- Mail.php
    |-- Controller/
    |   |-- Audit/
    |   |   `-- AuditController.php
    |   |-- Brand/
    |   |   `-- BrandController.php
    |   |-- Category/
    |   |   `-- CategoryController.php
    |   |-- Login/
    |   |   `-- LoginController.php
    |   |-- Product/
    |   |   `-- ProductController.php
    |   |-- Profile/
    |   |   `-- ProfileController.php
    |   |-- Providers/
    |   |   `-- ProviderController.php
    |   |-- Register/
    |   |   `-- RegisterController.php
    |   `-- TipoDocumento/
    |       `-- TipoDocumentoController.php
    |-- Models/
    |   |-- Audit/
    |   |   |-- Audit.php
    |   |   `-- AuditCRUD.php
    |   |-- Brand/
    |   |   |-- Brand.php
    |   |   `-- BrandCRUD.php
    |   |-- Category/
    |   |   |-- Category.php
    |   |   `-- CategoryCRUD.php
    |   |-- Product/
    |   |   |-- Product.php
    |   |   `-- ProductCRUD.php
    |   |-- Providers/
    |   |   |-- Provider.php
    |   |   `-- ProviderCRUD.php
    |   |-- TipoDocumento/
    |   |   |-- TipoDocumento.php
    |   |   `-- TipoDocumentoCRUD.php
    |   `-- Users/
    |       |-- User.php
    |       `-- UserCRUD.php
    |-- Views/
    |   |-- Audit/
    |   |   `-- Audit.php
    |   |-- Brand/
    |   |   |-- Brand.php
    |   |   |-- ConfirmExitBrandModal.php
    |   |   |-- CreateBrandModal.php
    |   |   |-- DeleteBrandModal.php
    |   |   |-- DetailBrandModal.php
    |   |   |-- EditBrandModal.php
    |   |   |-- HardDeleteModal.php
    |   |   |-- InactiveBrandsModal.php
    |   |   |-- InfoBrandModal.php
    |   |   `-- RestoreInactiveBrandModal.php
    |   |-- Category/
    |   |   |-- Category.php
    |   |   |-- ConfirmExitCategoryModal.php
    |   |   |-- CreateCategoryModal.php
    |   |   |-- DeleteCategoryModal.php
    |   |   |-- DetailCategoryModal.php
    |   |   |-- EditCategoryModal.php
    |   |   |-- HardDeleteInactiveCategoryModal.php
    |   |   |-- InactiveCategoriesModal.php
    |   |   |-- InfoCategoryModal.php
    |   |   `-- RestoreInactiveCategoryModal.php
    |   |-- Home/
    |   |   `-- Home.php
    |   |-- Login/
    |   |   `-- Login.php
    |   |-- Product/
    |   |   |-- Product.php
    |   |   |-- ConfirmExitProductModal.php
    |   |   |-- CreateProductModal.php
    |   |   |-- DeleteProductModal.php
    |   |   |-- DetailProductModal.php
    |   |   |-- EditProductModal.php
    |   |   |-- HardDeleteInactiveProductModal.php
    |   |   |-- InactiveProductsModal.php
    |   |   |-- InfoProductModal.php
    |   |   `-- RestoreInactiveProductModal.php
    |   |-- Profile/
    |   |   `-- Profile.php
    |   |-- Providers/
    |   |   |-- Provider.php
    |   |   |-- ConfirmExitProviderModal.php
    |   |   |-- CreateProviderModal.php
    |   |   |-- DeleteProviderModal.php
    |   |   |-- DetailProviderModal.php
    |   |   |-- EditProviderModal.php
    |   |   |-- HardDeleteInactiveProviderModal.php
    |   |   |-- InactiveProviderModal.php
    |   |   |-- InfoProviderModal.php
    |   |   `-- RestoreInactiveProviderModal.php
    |   |-- Register/
    |   |   `-- Register.php
    |   `-- TipoDocumento/
    |       |-- TipoDocumento.php
    |       |-- ConfirmExitTipoDocumentoModal.php
    |       |-- CreateTipoDocumentoModal.php
    |       |-- DeleteTipoDocumentoModal.php
    |       |-- DetailTipoDocumentoModal.php
    |       |-- EditTipoDocumentoModal.php
    |       |-- HardDeleteInactiveTipoDocumentoModal.php
    |       |-- InactiveTipoDocumentoModal.php
    |       |-- InfoTipoDocumentoModal.php
    |       `-- RestoreInactiveTipoDocumentoModal.php
    |-- Includes/
    |   |-- Footer/
    |   |   `-- Footer.php
    |   |-- Header/
    |   |   `-- Header.php
    |   |-- Load classes/
    |   |   `-- Load classes.php
    |   `-- Menu/
    |       `-- Menu.php
    |-- Assets/
    |   |-- Css/
    |   |   |-- Framework/
    |   |   |   |-- BootStrap/
    |   |   |   `-- Fontawesome/
    |   |   `-- Pages/
    |   |       |-- Audit/
    |   |       |   `-- Audit.css
    |   |       |-- Brand/
    |   |       |   `-- Brand.css
    |   |       |-- Category/
    |   |       |   `-- Category.css
    |   |       |-- Home/
    |   |       |   `-- Home.css
    |   |       |-- Login/
    |   |       |   `-- Login.css
    |   |       |-- Product/
    |   |       |   `-- Product.css
    |   |       |-- Profile/
    |   |       |   `-- Profile.css
    |   |       |-- Providers/
    |   |       |   `-- Providers.css
    |   |       |-- Register/
    |   |       |   `-- Register.css
    |   |       `-- TipoDocumento/
    |   |           `-- TipoDocumento.css
    |   `-- JS/
    |       |-- Framework/
    |       |   |-- AppNavigation.js
    |       |   `-- JQuery/
    |       |       `-- jquery.js
    |       `-- Pages/
    |           |-- Audit/
    |           |   `-- Audit.js
    |           |-- Brand/
    |           |   `-- Brand.js
    |           |-- Category/
    |           |   `-- Category.js
    |           |-- Home/
    |           |   `-- Home.js
    |           |-- Login/
    |           |   `-- Login.js
    |           |-- Product/
    |           |   `-- Product.js
    |           |-- Profile/
    |           |   `-- Profile.js
    |           |-- Providers/
    |           |   `-- Providers.js
    |           |-- Register/
    |           |   `-- Register.js
    |           `-- TipoDocumento/
    |               `-- TipoDocumento.js
    |-- Images/
    |   |-- Carousel/
    |   |   |-- .gitkeep
    |   |   |-- slide-1.jpg
    |   |   |-- slide-2.jpg
    |   |   |-- slide-3.jpg
    |   |   |-- slide-4.jpg
    |   |   `-- slide-5.jpg
    |   |-- Products/
    |   `-- Users/
    |-- PHPMailer/
    |   |-- src/
    |   |-- language/
    |   `-- README.md
    `-- Services/
        |-- AuditLogger.php
        `-- MailerService.php
```

Nota: `Pages/Assets/Css/Framework/BootStrap`, `Pages/Assets/Css/Framework/Fontawesome` y `Pages/PHPMailer` contienen librerias externas con muchos archivos internos. En la estructura se muestran como carpetas para no mezclar codigo del proyecto con codigo de libreria.

---

## 5. Flujo general del sistema

```text
Usuario
    -> index.php?page=...
        -> Controller correspondiente
            -> View correspondiente
                -> CSS/JS del modulo
                    -> Api/*.php por AJAX
                        -> Controller
                            -> Model CRUD con PDO
                                -> Stored Procedures MySQL
```

`index.php` inicia sesion, carga `Load classes.php`, identifica `$_GET['page']` y decide que vista o controlador ejecutar.

---

## 6. Rutas principales del router

| Ruta | Modulo | Archivo de vista |
|---|---|---|
| `index.php?page=home` | Home | `Pages/Views/Home/Home.php` |
| `index.php?page=login` | Login | `Pages/Views/Login/Login.php` |
| `index.php?page=register` | Register | `Pages/Views/Register/Register.php` |
| `index.php?page=logout` | Logout | Se procesa en `index.php` |
| `index.php?page=category` | Category | `Pages/Views/Category/Category.php` |
| `index.php?page=tipodocumento` | TipoDocumento | `Pages/Views/TipoDocumento/TipoDocumento.php` |
| `index.php?page=providers` | Providers | `Pages/Views/Providers/Provider.php` |
| `index.php?page=brand` | Brand | `Pages/Views/Brand/Brand.php` |
| `index.php?page=product` | Product | `Pages/Views/Product/Product.php` |
| `index.php?page=profile` | Profile | `Pages/Views/Profile/Profile.php` |
| `index.php?page=audit` | Audit | `Pages/Views/Audit/Audit.php` |

---

## 7. Includes globales

### Header

Archivo: `C:\xampp\htdocs\KMLLogistics\Pages\Includes\Header\Header.php`

Responsabilidades:

- Define `<!DOCTYPE html>`, `html`, `head` y apertura del `body`.
- Carga Bootstrap CSS.
- Carga Font Awesome.
- Carga estilos especificos por vista.
- Define estructura base `#app-shell`.

### Menu

Archivo: `C:\xampp\htdocs\KMLLogistics\Pages\Includes\Menu\Menu.php`

Responsabilidades:

- Renderiza la navegacion principal.
- Marca el modulo activo.
- Muestra opciones segun sesion y rol.
- Integra accesos a Home, Category, TipoDocumento, Providers, Brand, Product, Profile y Audit.

### Footer

Archivo: `C:\xampp\htdocs\KMLLogistics\Pages\Includes\Footer\Footer.php`

Responsabilidades:

- Renderiza el pie de pagina con datos de KML Logistic S.A.C.
- Carga jQuery.
- Carga Bootstrap JS.
- Carga scripts especificos de cada vista.
- Carga `AppNavigation.js`.

### Load classes

Archivo: `C:\xampp\htdocs\KMLLogistics\Pages\Includes\Load classes\Load classes.php`

Responsabilidades:

- Centraliza `require_once`.
- Carga configuracion.
- Carga servicios.
- Carga modelos.
- Carga controladores.
- Carga includes globales.

---

## 8. Configuracion

### Base de datos

Archivo: `C:\xampp\htdocs\KMLLogistics\Pages\Config\Database.php`

Usa:

- PDO.
- MySQL.
- Charset `utf8mb4`.
- `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION`.
- `PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC`.
- `PDO::ATTR_EMULATE_PREPARES => false`.

### Correo

Archivo: `C:\xampp\htdocs\KMLLogistics\Pages\Config\Mail.php`

Usa:

- SMTP.
- PHPMailer.
- Remitente del sistema.
- Configuracion para enviar codigos y notificaciones.

Por seguridad, la documentacion no replica contrasenas ni claves de aplicacion.

---

## 9. Base de datos

Archivo principal: `C:\xampp\htdocs\KMLLogistics\BD\Empresa\KMLLogistics.sql`

Este archivo contiene:

- Creacion de base de datos `KMLLogistics`.
- Creacion de tablas.
- Datos iniciales.
- Usuario administrador inicial.
- Procedimientos almacenados.
- Ejemplos de llamadas `CALL`.

### Tablas

#### categorias

Campos: `id_categoria`, `nombre_categoria`, `descripcion`, `estado`, `created_at`, `updated_at`, `deleted_at`.

#### tipo_documentos

Campos: `id_tipo_documento`, `nombre_tipo_documento`, `descripcion`, `estado`, `created_at`, `updated_at`, `deleted_at`.

#### proveedores

Campos: `id_proveedor`, `razon_social`, `nombre_comercial`, `id_tipo_documento`, `numero_documento`, `telefono`, `correo`, `direccion`, `contacto`, `estado`, `created_at`, `updated_at`, `deleted_at`.

Relacion:

- `proveedores.id_tipo_documento` referencia `tipo_documentos.id_tipo_documento`.

#### marcas

Campos: `id_marca`, `nombre_marca`, `id_proveedor`, `estado`, `created_at`, `updated_at`, `deleted_at`.

Relacion:

- `marcas.id_proveedor` referencia `proveedores.id_proveedor`.

#### productos

Campos: `id_producto`, `producto`, `costo`, `ganancia`, `precio`, `stock`, `foto`, `id_categoria`, `id_marca`, `estado`, `created_at`, `updated_at`, `deleted_at`.

Relaciones:

- `productos.id_categoria` referencia `categorias.id_categoria`.
- `productos.id_marca` referencia `marcas.id_marca`.

#### usuarios

Campos: `id_usuario`, `nombres`, `apellidos`, `correo`, `id_tipo_documento`, `numero_documento`, `password_hash`, `rol`, `foto`, `email_verificado`, `email_verified_at`, `estado`, `created_at`, `updated_at`, `deleted_at`.

Relacion:

- `usuarios.id_tipo_documento` referencia `tipo_documentos.id_tipo_documento`.

#### usuario_codigos

Campos: `id_codigo`, `id_usuario`, `tipo`, `codigo_hash`, `destino_email`, `expires_at`, `used_at`, `created_at`.

Relacion:

- `usuario_codigos.id_usuario` referencia `usuarios.id_usuario`.

#### audits

Campos: `id_audit`, `id_usuario`, `modulo`, `accion`, `descripcion`, `datos`, `estado`, `created_at`, `updated_at`, `deleted_at`.

Relacion:

- `audits.id_usuario` referencia `usuarios.id_usuario`.

---

## 10. Stored Procedures

Todos los SP finales estan integrados en:

```text
C:\xampp\htdocs\KMLLogistics\BD\Empresa\KMLLogistics.sql
```

### TipoDocumento

```text
sp_tipo_documento_listar_activos
sp_tipo_documento_contar_activos
sp_tipo_documento_listar_inactivos
sp_tipo_documento_obtener_activo_por_id
sp_tipo_documento_obtener_por_id
sp_tipo_documento_crear
sp_tipo_documento_actualizar
sp_tipo_documento_eliminar_logico
sp_tipo_documento_restaurar
sp_tipo_documento_eliminar_definitivo
sp_tipo_documento_existe_nombre
sp_tipo_documento_listar_activos_para_select
sp_tipo_documento_obtener_activo_para_select_por_id
```

### Usuarios, autenticacion y perfil

```text
sp_usuario_obtener_por_correo
sp_usuario_obtener_perfil
sp_usuario_actualizar_perfil
sp_usuario_actualizar_foto
sp_usuario_codigo_crear
sp_usuario_codigo_obtener_vigente
sp_usuario_codigo_marcar_usado
sp_usuario_cambiar_password
sp_usuario_verificar_email
sp_usuario_registrar
```

### Category

```text
sp_categoria_listar_activas
sp_categoria_contar_activas
sp_categoria_listar_inactivas
sp_categoria_obtener_activa_por_id
sp_categoria_obtener_por_id
sp_categoria_crear
sp_categoria_actualizar
sp_categoria_eliminar_logico
sp_categoria_restaurar
sp_categoria_eliminar_definitivo
sp_categoria_existe_nombre
```

### Product

```text
sp_producto_listar_activas
sp_producto_contar_activas
sp_producto_listar_inactivas
sp_producto_obtener_activa_por_id
sp_producto_obtener_por_id
sp_producto_crear
sp_producto_actualizar
sp_producto_eliminar_logico
sp_producto_restaurar
sp_producto_eliminar_definitivo
sp_producto_existe_nombre
sp_producto_listar_categorias_activas
sp_producto_listar_marcas_activas
```

### Providers

```text
sp_proveedor_listar_activas
sp_proveedor_contar_activas
sp_proveedor_listar_inactivas
sp_proveedor_obtener_activa_por_id
sp_proveedor_obtener_por_id
sp_proveedor_crear
sp_proveedor_actualizar
sp_proveedor_eliminar_logico
sp_proveedor_restaurar
sp_proveedor_eliminar_definitivo
sp_proveedor_existe_documento
```

### Brand

```text
sp_marca_listar_activas
sp_marca_contar_activas
sp_marca_listar_inactivas
sp_marca_obtener_activa_por_id
sp_marca_obtener_por_id
sp_marca_crear
sp_marca_actualizar
sp_marca_eliminar_logico
sp_marca_restaurar
sp_marca_eliminar_definitivo
sp_marca_existe_nombre
sp_marca_listar_proveedores_activos
```

### Audit

```text
sp_audit_registrar
sp_audit_listar_activas
sp_audit_contar_activas
sp_audit_obtener_activa_por_id
```

---

## 11. Modulos del sistema

### Home

Archivos:

- `C:\xampp\htdocs\KMLLogistics\Pages\Views\Home\Home.php`
- `C:\xampp\htdocs\KMLLogistics\Pages\Assets\Css\Pages\Home\Home.css`
- `C:\xampp\htdocs\KMLLogistics\Pages\Assets\JS\Pages\Home\Home.js`

Funciones:

- Carrusel principal.
- Informacion de KML Logistic S.A.C.
- Tarjetas de servicios.
- Mision, vision y valores.
- Proceso logistico.
- Contadores visuales y valores interactivos con JavaScript.

### Auth

Archivos:

- `C:\xampp\htdocs\KMLLogistics\Pages\Controller\Login\LoginController.php`
- `C:\xampp\htdocs\KMLLogistics\Pages\Controller\Register\RegisterController.php`
- `C:\xampp\htdocs\KMLLogistics\Pages\Models\Users\User.php`
- `C:\xampp\htdocs\KMLLogistics\Pages\Models\Users\UserCRUD.php`
- `C:\xampp\htdocs\KMLLogistics\Pages\Views\Login\Login.php`
- `C:\xampp\htdocs\KMLLogistics\Pages\Views\Register\Register.php`

Funciones:

- Login.
- Registro.
- Logout.
- Manejo de sesiones.
- Passwords con `password_hash()` y `password_verify()`.
- Auditoria de accesos.

### Category

Funciones:

- CRUD completo.
- Listado activo e inactivo.
- Paginacion.
- Busqueda.
- Restauracion.
- Eliminacion definitiva.
- Auditoria.

### TipoDocumento

Funciones:

- CRUD completo.
- Listado activo e inactivo.
- Validacion de nombres duplicados.
- Bloqueo de hard delete si existen usuarios o proveedores relacionados.
- Uso en selects de usuarios y proveedores.

### Providers

Funciones:

- CRUD de proveedores.
- Relacion con tipos de documento.
- Validacion de documento duplicado.
- Activos, inactivos, restauracion y hard delete.

### Brand

Funciones:

- CRUD de marcas.
- Relacion con proveedores.
- Selector de proveedores activos.
- Activos, inactivos, restauracion y hard delete.

### Product

Funciones:

- CRUD de productos.
- Relacion con categorias y marcas.
- Costo, ganancia, precio y stock.
- Foto del producto.
- Manejo de imagenes con `ProductImageHelper.php`.
- Activos, inactivos, restauracion y hard delete.

### Profile

Funciones:

- Actualizar datos personales.
- Cambiar rol.
- Subir y eliminar foto.
- Verificar correo.
- Cambiar password con codigo.
- Envio de codigos con PHPMailer.

### Audit

Funciones:

- Registrar acciones del sistema.
- Listar auditoria.
- Ver detalle de acciones.
- Guardar datos extra en JSON.
- Notificar acciones relevantes por correo.

---

## 12. APIs JSON

Las carpetas en `Api/` devuelven JSON y son consumidas por los archivos JS de cada modulo.

### Audit

```text
Api/Audit/List.php
Api/Audit/Get.php
```

### Brand

```text
Api/Brand/List.php
Api/Brand/ListInactive.php
Api/Brand/Get.php
Api/Brand/Create.php
Api/Brand/Update.php
Api/Brand/Delete.php
Api/Brand/Restore.php
Api/Brand/HardDelete.php
```

### Category

```text
Api/Category/List.php
Api/Category/ListInactive.php
Api/Category/Get.php
Api/Category/Create.php
Api/Category/Update.php
Api/Category/Delete.php
Api/Category/Restore.php
Api/Category/HardDelete.php
```

### Product

```text
Api/Product/List.php
Api/Product/ListInactive.php
Api/Product/Get.php
Api/Product/Create.php
Api/Product/Update.php
Api/Product/Delete.php
Api/Product/Restore.php
Api/Product/HardDelete.php
Api/Product/ProductImageHelper.php
```

### Profile

```text
Api/Profile/Update.php
Api/Profile/UploadPhoto.php
Api/Profile/DeletePhoto.php
Api/Profile/SendEmailCode.php
Api/Profile/ConfirmEmail.php
Api/Profile/SendPasswordCode.php
Api/Profile/ChangePassword.php
```

### Providers

```text
Api/Providers/List.php
Api/Providers/ListInactive.php
Api/Providers/Get.php
Api/Providers/Create.php
Api/Providers/Update.php
Api/Providers/Delete.php
Api/Providers/Restore.php
Api/Providers/HardDelete.php
```

### TipoDocumento

```text
Api/TipoDocumento/List.php
Api/TipoDocumento/ListInactive.php
Api/TipoDocumento/Get.php
Api/TipoDocumento/Create.php
Api/TipoDocumento/Update.php
Api/TipoDocumento/Delete.php
Api/TipoDocumento/Restore.php
Api/TipoDocumento/HardDelete.php
```

---

## 13. Frontend por modulo

Cada modulo tiene CSS y JS propio:

| Modulo | CSS | JS |
|---|---|---|
| Audit | `Pages/Assets/Css/Pages/Audit/Audit.css` | `Pages/Assets/JS/Pages/Audit/Audit.js` |
| Brand | `Pages/Assets/Css/Pages/Brand/Brand.css` | `Pages/Assets/JS/Pages/Brand/Brand.js` |
| Category | `Pages/Assets/Css/Pages/Category/Category.css` | `Pages/Assets/JS/Pages/Category/Category.js` |
| Home | `Pages/Assets/Css/Pages/Home/Home.css` | `Pages/Assets/JS/Pages/Home/Home.js` |
| Login | `Pages/Assets/Css/Pages/Login/Login.css` | `Pages/Assets/JS/Pages/Login/Login.js` |
| Product | `Pages/Assets/Css/Pages/Product/Product.css` | `Pages/Assets/JS/Pages/Product/Product.js` |
| Profile | `Pages/Assets/Css/Pages/Profile/Profile.css` | `Pages/Assets/JS/Pages/Profile/Profile.js` |
| Providers | `Pages/Assets/Css/Pages/Providers/Providers.css` | `Pages/Assets/JS/Pages/Providers/Providers.js` |
| Register | `Pages/Assets/Css/Pages/Register/Register.css` | `Pages/Assets/JS/Pages/Register/Register.js` |
| TipoDocumento | `Pages/Assets/Css/Pages/TipoDocumento/TipoDocumento.css` | `Pages/Assets/JS/Pages/TipoDocumento/TipoDocumento.js` |

### Uso de jQuery

Se usa para:

- `$(function () {})`.
- Capturar eventos.
- Leer formularios.
- Ejecutar `$.ajax()`.
- Actualizar tablas.
- Abrir modals.
- Mostrar feedback.
- Aplicar `addClass()` y `removeClass()`.

Ejemplo de patron:

```js
$dialog.removeClass('modal-sm modal-lg modal-xl');
$dialog.addClass(sizeClass);
```

---

## 14. Bootstrap y Font Awesome

### Bootstrap

Se usa para:

- Layout responsive.
- Navbar.
- Botones.
- Formularios.
- Tablas.
- Modals.
- Badges y alerts.
- Carrusel de Home.

### Font Awesome

Se usa para:

- Iconos del menu.
- Iconos de botones CRUD.
- Iconos de formularios.
- Iconos de cards del Home.
- Iconos de acciones en modals.

---

## 15. Servicios

### AuditLogger

Archivo: `C:\xampp\htdocs\KMLLogistics\Pages\Services\AuditLogger.php`

Funciones:

- Centraliza el registro de auditoria.
- Usa `AuditCRUD`.
- Registra modulo, accion, descripcion y datos JSON.
- Puede enviar notificaciones al administrador.

### MailerService

Archivo: `C:\xampp\htdocs\KMLLogistics\Pages\Services\MailerService.php`

Funciones:

- Centraliza envio de correos.
- Usa PHPMailer.
- Envia codigos de verificacion.
- Envia codigos de cambio de password.
- Envia notificaciones del sistema.

---

## 16. Seguridad y sesiones

El proyecto maneja:

- `session_start()` desde `index.php`.
- Usuario autenticado en `$_SESSION['user']`.
- Roles de usuario.
- Passwords con hash.
- Codigos de verificacion hasheados.
- Validacion de formularios desde backend.
- Uso de PDO con consultas preparadas.
- Separacion entre usuario invitado y usuario autenticado en auditoria.

---

## 17. Usuario administrador inicial

El SQL crea un usuario inicial:

```text
Correo: admin@kmllogistics.com
Password base: 123456
Rol: admin
```

La password se guarda hasheada en la tabla `usuarios`.

---

## 18. Ejecucion del proyecto

### Paso 1

Colocar el proyecto en:

```text
C:\xampp\htdocs\KMLLogistics
```

### Paso 2

Iniciar servicios en XAMPP:

```text
Apache
MySQL
```

### Paso 3

Importar manualmente:

```text
C:\xampp\htdocs\KMLLogistics\BD\Empresa\KMLLogistics.sql
```

### Paso 4

Abrir en navegador:

```text
http://localhost/KMLLogistics/
```

---

## 19. Problemas comunes

### Error de conexion PDO

Revisar:

- MySQL encendido.
- Base `KMLLogistics` creada.
- Usuario y clave configurados en `Database.php`.
- Puerto `3306`.

### Error de procedimiento almacenado no encontrado

Revisar:

- Que `KMLLogistics.sql` se importo completo.
- Que no se omitieron delimitadores `DELIMITER $$`.
- Que el SP existe dentro del archivo SQL.

### AJAX no responde

Revisar:

- Que la ruta `Api/...` exista.
- Que el endpoint devuelva JSON valido.
- Que jQuery se este cargando desde el footer.
- Que no haya errores en consola.

### Modals no abren

Revisar:

- Que Bootstrap JS se cargue desde `Footer.php`.
- Que el modal exista en la vista.
- Que el ID usado en JS coincida con el HTML.

### Imagenes no aparecen

Revisar:

- `Pages/Images/Carousel/` para Home.
- `Pages/Images/Products/` para productos.
- `Pages/Images/Users/` para perfiles.

---

## 20. Documentacion por modulo

La documentacion especifica se encuentra en:

```text
Documentation/Home/Home.md
Documentation/Auth/Auth.md
Documentation/Category/Category.md
Documentation/DocumentType/DocumentType.md
Documentation/Providers/Providers.md
Documentation/Brand/Brand.md
Documentation/Product/Product.md
Documentation/Profile/Profile.md
Documentation/Audit/Audit.md
Documentation/API/Category.md
Documentation/API/DocumentType.md
```

---

## 21. Resumen final

KMLLogistics es un sistema web completo para gestionar informacion operativa de una empresa logistica. El proyecto integra PHP, MVC, POO, PDO, MySQL, stored procedures, Bootstrap, Font Awesome, jQuery, AJAX, PHPMailer y servicios internos.

El sistema queda dividido por modulos, cada uno con sus vistas, controladores, modelos, endpoints, estilos y scripts. La base de datos centraliza reglas mediante procedimientos almacenados, y la auditoria registra acciones importantes para mantener trazabilidad.
