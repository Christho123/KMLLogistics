# Documentacion del Proyecto KMLLogistics

## 1. Descripcion general

KMLLogistics es un proyecto web en PHP organizado con una estructura MVC simple, usando PDO para la conexion con MySQL, Bootstrap para la interfaz, Font Awesome para iconografia y jQuery para interacciones del frontend.

Actualmente el sistema incluye:

- Ruteo centralizado desde `index.php`
- Estructura MVC con controladores, modelos y vistas
- Conexion segura a base de datos con PDO
- Vista `Home` con carrusel principal
- Vista `Login`
- Vista `Register`
- Modulo `Category` con:
  - listado paginado
  - filtro por nombre
  - consulta por ID
  - detalle de categoria
  - creacion
  - edicion
  - eliminacion logica
  - listado de categorias inactivas
  - restauracion
  - eliminacion definitiva
- APIs JSON para el modulo de categorias
- Script SQL con tablas, datos base y procedimientos almacenados

---

## 2. Tecnologias utilizadas

- PHP
- MySQL
- PDO
- Bootstrap
- Font Awesome
- jQuery
- AJAX con jQuery
- SQL con procedimientos almacenados
- POO
- MVC

---

## 3. Estructura del proyecto

```text
KMLLogistics/
|-- index.php
|-- README.md
|-- documentacion.md
|-- Indicaciones_Profesor.md
|-- Api/
|   `-- Category/
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
|   |   `-- KMLLogistics.sql
|   `-- Script-Profesor/
`-- Pages/
    |-- Config/
    |   `-- Database.php
    |-- Controller/
    |   |-- Category/
    |   |   `-- CategoryController.php
    |   |-- Login/
    |   |   `-- LoginController.php
    |   `-- Register/
    |       `-- RegisterController.php
    |-- Models/
    |   |-- Category/
    |   |   |-- Category.php
    |   |   `-- CategoryCRUD.php
    |   `-- Users/
    |       |-- User.php
    |       `-- UserCRUD.php
    |-- Views/
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
    |   `-- Register/
    |       `-- Register.php
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
    |   |   `-- Pages/
    |   |       |-- Category/
    |   |       |   `-- Category.css
    |   |       |-- Home/
    |   |       |   `-- Home.css
    |   |       |-- Login/
    |   |       |   `-- Login.css
    |   |       `-- Register/
    |   |           `-- Register.css
    |   `-- JS/
    |       |-- Framework/
    |       `-- Pages/
    |           |-- Category/
    |           |   `-- Category.js
    |           |-- Login/
    |           |   `-- Login.js
    |           `-- Register/
    |               `-- Register.js
    `-- Images/
        `-- Carousel/
            |-- slide-1.jpg
            |-- slide-2.jpg
            |-- slide-3.jpg
            |-- slide-4.jpg
            `-- slide-5.jpg
```

---

## 4. Flujo general del sistema

Todo el sistema pasa por `index.php`.

Flujo general:

1. El usuario entra al proyecto.
2. `index.php` revisa el parametro `page`.
3. Se instancia el controlador correspondiente.
4. El controlador prepara la data para la vista.
5. La vista carga `Header`, `Menu` y `Footer`.
6. En el modulo `Category`, el frontend consulta APIs por AJAX y recibe JSON.

---

## 5. Rutas implementadas

Rutas principales:

- `index.php`
- `index.php?page=home`
- `index.php?page=category`
- `index.php?page=login`
- `index.php?page=register`
- `index.php?page=logout`

Funcion de cada ruta:

- `home`: muestra la portada principal con carrusel
- `category`: muestra el modulo de categorias
- `login`: muestra y procesa el inicio de sesion
- `register`: muestra y procesa el registro
- `logout`: destruye la sesion y redirige al login

---

## 6. Includes globales

### Header

Archivo:

- `Pages/Includes/Header/Header.php`

Funcion:

- abre la estructura HTML
- carga Bootstrap CSS
- carga Font Awesome
- carga estilos especificos por vista

### Menu

Archivo:

- `Pages/Includes/Menu/Menu.php`

Funcion:

- renderiza la barra de navegacion
- muestra acceso a `Home` y `Categoria`
- marca la pagina activa
- muestra el usuario autenticado cuando hay sesion
- muestra botones `Login` y `Registro` cuando no hay sesion

### Footer

Archivo:

- `Pages/Includes/Footer/Footer.php`

Funcion:

- renderiza el pie de pagina
- carga jQuery
- carga Bootstrap JS
- carga scripts especificos de cada vista

### Load classes

Archivo:

- `Pages/Includes/Load classes/Load classes.php`

Funcion:

- centraliza los `require_once`
- carga configuracion
- carga modelos
- carga controladores
- carga includes principales

---

## 7. Conexion a base de datos con PDO

Archivo:

- `Pages/Config/Database.php`

Configuracion actual:

- Host: `127.0.0.1`
- Puerto: `3306`
- Base de datos: `KMLLogistics`
- Usuario: `root`
- Password: `123456`

Caracteristicas:

- conexion con PDO
- charset `utf8mb4`
- manejo de errores con excepciones
- `FETCH_ASSOC`
- `ATTR_EMULATE_PREPARES = false`

---

## 8. Base de datos

Archivo SQL principal:

- `BD/Empresa/KMLLogistics.sql`

Este archivo contiene:

- creacion de la base de datos
- creacion de tablas
- inserts iniciales
- procedimientos almacenados
- ejemplos de uso
- consultas manuales de apoyo

### Tablas actuales

#### categorias

- `id_categoria`
- `nombre_categoria`
- `descripcion`
- `estado`
- `created_at`
- `updated_at`
- `deleted_at`

#### tipo_documentos

- `id_tipo_documento`
- `nombre_tipo_documento`
- `descripcion`
- `estado`
- `created_at`
- `updated_at`

#### proveedores

- `id_proveedor`
- `razon_social`
- `nombre_comercial`
- `id_tipo_documento`
- `numero_documento`
- `telefono`
- `correo`
- `direccion`
- `contacto`
- `estado`
- `created_at`
- `updated_at`
- `deleted_at`

#### marcas

- `id_marca`
- `nombre_marca`
- `id_proveedor`
- `estado`
- `created_at`
- `updated_at`
- `deleted_at`

#### productos

- `id_producto`
- `producto`
- `costo`
- `ganancia`
- `precio`
- `stock`
- `id_categoria`
- `id_marca`
- `estado`
- `created_at`
- `updated_at`
- `deleted_at`

Nota:

- `id_producto` es `AUTO_INCREMENT`
- ya no se usa un codigo tipo `PRD001`

#### usuarios

- `id_usuario`
- `nombres`
- `apellidos`
- `correo`
- `id_tipo_documento`
- `numero_documento`
- `password_hash`
- `rol`
- `estado`
- `created_at`
- `updated_at`
- `deleted_at`

---

## 9. Procedimientos almacenados actuales

Definidos en:

- `BD/Empresa/KMLLogistics.sql`

### Modulo productos

#### `sp_buscar_producto_por_id`

- busca un producto activo por `id_producto`
- devuelve:
  - `id_producto`
  - `producto`
  - `costo`
  - `ganancia`
  - `precio`
  - `stock`
  - `total`
  - `categoria`
  - `marca`

#### `sp_filtrar_por_nombre`

- busca productos activos por nombre
- devuelve:
  - `id_producto`
  - `producto`
  - `costo`
  - `ganancia`
  - `precio`
  - `stock`
  - `total`
  - `categoria`
  - `marca`

### Modulo category

#### `sp_categoria_listar_activas`

- lista categorias activas
- usa paginacion
- filtra por prefijo de nombre

#### `sp_categoria_contar_activas`

- cuenta categorias activas segun filtro

#### `sp_categoria_listar_inactivas`

- lista categorias inactivas o eliminadas logicamente

#### `sp_categoria_obtener_activa_por_id`

- obtiene una categoria activa por ID

#### `sp_categoria_obtener_por_id`

- obtiene una categoria sin importar su estado

#### `sp_categoria_crear`

- inserta una categoria nueva
- devuelve `LAST_INSERT_ID()`

#### `sp_categoria_actualizar`

- actualiza una categoria activa
- devuelve filas afectadas

#### `sp_categoria_eliminar_logico`

- marca una categoria como inactiva

#### `sp_categoria_restaurar`

- restaura una categoria eliminada logicamente

#### `sp_categoria_eliminar_definitivo`

- elimina definitivamente la categoria
- tambien elimina productos asociados

#### `sp_categoria_existe_nombre`

- valida si ya existe una categoria activa con el mismo nombre

---

## 10. Home

Archivos principales:

- `Pages/Views/Home/Home.php`
- `Pages/Assets/Css/Pages/Home/Home.css`

Funcion:

- muestra la portada principal
- renderiza un carrusel hero
- usa imagenes del directorio `Pages/Images/Carousel`
- muestra un placeholder si alguna imagen no existe
- tiene acceso rapido al modulo de categorias

---

## 11. Login

Archivos principales:

- `Pages/Controller/Login/LoginController.php`
- `Pages/Views/Login/Login.php`
- `Pages/Assets/Css/Pages/Login/Login.css`
- `Pages/Assets/JS/Pages/Login/Login.js`

Funcion:

- valida campos obligatorios
- busca usuario por correo
- verifica si el usuario esta activo
- valida password con `password_verify`
- guarda sesion al autenticar

Datos guardados en sesion:

- `id_usuario`
- `nombres`
- `apellidos`
- `correo`
- `rol`

Extra:

- el frontend permite mostrar u ocultar la password

---

## 12. Registro

Archivos principales:

- `Pages/Controller/Register/RegisterController.php`
- `Pages/Views/Register/Register.php`
- `Pages/Assets/Css/Pages/Register/Register.css`
- `Pages/Assets/JS/Pages/Register/Register.js`

Funcion:

- valida campos vacios
- valida correo
- valida longitud minima de password
- valida confirmacion de password
- evita correos duplicados
- registra tipo y numero de documento
- guarda password con `password_hash`

Extra:

- el frontend permite mostrar u ocultar la password

---

## 13. Modulo Category

Archivos principales:

- `Pages/Controller/Category/CategoryController.php`
- `Pages/Models/Category/Category.php`
- `Pages/Models/Category/CategoryCRUD.php`
- `Pages/Views/Category/Category.php`
- `Pages/Assets/Css/Pages/Category/Category.css`
- `Pages/Assets/JS/Pages/Category/Category.js`

APIs del modulo:

- `Api/Category/List.php`
- `Api/Category/ListInactive.php`
- `Api/Category/Get.php`
- `Api/Category/Create.php`
- `Api/Category/Update.php`
- `Api/Category/Delete.php`
- `Api/Category/Restore.php`
- `Api/Category/HardDelete.php`

### Funcionalidades del modulo

- listar categorias activas
- paginar resultados
- cambiar cantidad de registros por pagina
- buscar por nombre
- consultar detalle por ID
- crear categoria
- editar categoria
- eliminar logicamente
- ver categorias inactivas
- restaurar categoria
- eliminar definitivamente
- validar mensajes de error mas claros

---

## 14. Modals del modulo Category

La vista de categorias usa varios modals Bootstrap:

- `CreateCategoryModal.php`
- `EditCategoryModal.php`
- `DetailCategoryModal.php`
- `DeleteCategoryModal.php`
- `ConfirmExitCategoryModal.php`
- `InfoCategoryModal.php`
- `InactiveCategoriesModal.php`
- `HardDeleteInactiveCategoryModal.php`
- `RestoreInactiveCategoryModal.php`

Uso principal:

- crear
- editar
- ver detalle
- eliminar logicamente
- mostrar avisos
- confirmar salida con cambios
- administrar categorias inactivas

Extra:

- el tamano de varios modals se controla dinamicamente con jQuery usando `removeClass()` y `addClass()` en `Pages/Assets/JS/Pages/Category/Category.js`

---

## 15. AJAX en Category

El modulo Category trabaja con respuestas JSON por AJAX.

Principales operaciones AJAX:

- obtener detalle
- listar activas
- listar inactivas
- crear
- editar
- eliminar
- restaurar
- eliminar definitivamente

Esto permite:

- no recargar la pagina
- actualizar tabla y modals en tiempo real
- mostrar mensajes de validacion mas precisos

---

## 16. Paginacion y filtro de categorias

Caracteristicas:

- paginacion por AJAX
- selector de `10`, `20` o `50` registros
- filtro por nombre
- consulta directa por ID
- resumen de pagina y total
- botones `Anterior` y `Siguiente`

Orden del listado activo:

- `ORDER BY created_at DESC, id_categoria DESC`

---

## 17. Categorias inactivas

El sistema ya maneja borrado logico.

Flujo:

1. Una categoria activa se elimina logicamente.
2. Deja de verse en el listado principal.
3. Se puede consultar desde el modal de inactivos.
4. Desde ahi se puede:
   - restaurar
   - eliminar definitivamente

En eliminacion definitiva:

- tambien se eliminan productos asociados a la categoria

---

## 18. Validaciones y mensajes de error

Se mejoro el manejo de errores del modulo Category.

Ejemplos actuales:

- si el nombre ya existe:
  - `Ya existe una categoria activa con este nombre.`
- si se intenta restaurar una categoria cuyo nombre ya existe activa:
  - `No se puede restaurar esta categoria porque ya existe una categoria activa con este nombre.`
- si se hace clic en `Filtrar` sin escribir nada:
  - se abre un modal informativo pidiendo ingresar un valor
- si faltan datos al crear o editar:
  - se muestran mensajes claros de validacion

---

## 19. Tabla responsive de categorias

Caracteristicas:

- scroll horizontal
- scroll vertical dinamico
- ancho minimo de tabla
- arrastre con puntero
- adaptacion a mobile, tablet y desktop

---

## 20. Iconografia y librerias visuales

### Bootstrap

Se usa para:

- layout
- botones
- formularios
- tabla
- modals
- componentes visuales

### Font Awesome

Se usa para:

- menu
- login
- registro
- botones
- modals
- acciones del modulo Category

### jQuery

Se usa para:

- eventos
- AJAX
- manejo de clases CSS
- control de modals
- actualizacion dinamica de la interfaz

---

## 21. Usuario base del sistema

El SQL crea un usuario inicial:

- Correo: `admin@kmllogistics.com`
- Password: `123456`

La password almacenada en base de datos esta hasheada.

---

## 22. Como ejecutar el proyecto

### Paso 1

Colocar el proyecto en:

```text
C:\xampp\htdocs\KMLLogistics
```

### Paso 2

Encender en XAMPP:

- Apache
- MySQL

### Paso 3

Importar manualmente:

- `BD/Empresa/KMLLogistics.sql`

Esto crea:

- base de datos
- tablas
- datos iniciales
- procedimientos almacenados
- ejemplos y consultas de apoyo

### Paso 4

Abrir:

```text
http://localhost/KMLLogistics/
```

---

## 23. Problemas comunes

### Error: tabla no existe

Significa que aun no se importo correctamente el archivo SQL.

Solucion:

- importar `BD/Empresa/KMLLogistics.sql`

### Error de conexion PDO

Revisar:

- que MySQL este encendido
- que la base exista
- que el usuario sea `root`
- que la password sea `123456`
- que el puerto sea `3306`

### El modulo Category no responde bien

Revisar:

- que jQuery se este cargando
- que Bootstrap JS se este cargando
- que las APIs de `Api/Category/` respondan JSON valido
- que el SQL actual incluya los procedimientos almacenados nuevos

### No aparecen imagenes del carrusel

Revisar que existan:

- `Pages/Images/Carousel/slide-1.jpg`
- `Pages/Images/Carousel/slide-2.jpg`
- `Pages/Images/Carousel/slide-3.jpg`
- `Pages/Images/Carousel/slide-4.jpg`
- `Pages/Images/Carousel/slide-5.jpg`

---

## 24. Resumen final

El proyecto actualmente queda organizado para que:

- todo pase por `index.php`
- la estructura MVC mantenga separacion de responsabilidades
- la conexion a base de datos use PDO
- el login y registro usen buenas practicas
- el modulo Category trabaje con AJAX y APIs JSON
- exista soporte para listado activo e inactivo
- los modals usen Bootstrap
- el frontend use jQuery y Font Awesome
- la base de datos tenga procedimientos almacenados alineados con el sistema actual

Con esto, el proyecto queda mas consistente, mejor documentado y preparado para seguir creciendo.
