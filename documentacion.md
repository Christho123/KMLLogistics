# Documentacion del Proyecto KMLLogistics

## 1. Descripcion general

KMLLogistics es un proyecto PHP estructurado por capas para mantener separacion de responsabilidades, escalabilidad y reutilizacion de codigo.

El sistema implementado actualmente incluye:

- Conexion a base de datos con PDO
- Ruteo centralizado desde `index.php`
- Includes globales reutilizables
- Vista `Home` independiente con carrusel tipo hero
- Vista `Category` independiente con listado de categorias
- Paginacion del listado de categorias por AJAX
- Selector de cantidad de registros por pagina
- Tabla responsive con scroll horizontal y arrastre
- Registro de usuarios
- Login con `password_hash` y `password_verify`
- Script SQL con tablas, datos base, procedimientos almacenados y consultas de apoyo

---

## 2. Tecnologias utilizadas

- PHP
- MySQL
- PDO
- Bootstrap
- Font Awesome
- jQuery
- AJAX con jQuery

---

## 3. Estructura del proyecto

```text
KMLLogistics/
|-- index.php
|-- README.md
|-- documentacion.md
|-- Api/
|   `-- Category/
|       `-- List.php
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
    |   |   `-- Category.php
    |   |-- Home/
    |   |   `-- Home.php
    |   |-- Login/
    |   |   `-- Login.php
    |   `-- Register/
    |       `-- Register.php
    |-- Includes/
    |   |-- Header/
    |   |   `-- Header.php
    |   |-- Footer/
    |   |   `-- Footer.php
    |   |-- Menu/
    |   |   `-- Menu.php
    |   `-- Load classes/
    |       `-- Load classes.php
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

Flujo principal:

1. El usuario entra al proyecto.
2. `index.php` revisa el parametro `page`.
3. Se carga el controlador correspondiente.
4. El controlador prepara datos para la vista.
5. La vista usa los includes globales:
   - Header
   - Menu
   - Footer
   - Load classes
6. Si la ruta es `category`, la vista consume datos desde `Api/Category/List.php`.

---

## 5. Rutas implementadas

Archivo principal:

- `index.php`

Rutas:

- `index.php`
- `index.php?page=home`
- `index.php?page=category`
- `index.php?page=login`
- `index.php?page=register`
- `index.php?page=logout`

Funcion de cada ruta:

- `home`: muestra la vista de inicio con carrusel hero
- `category`: muestra la vista de categorias con tabla paginada
- `login`: muestra y procesa el inicio de sesion
- `register`: muestra y procesa el registro
- `logout`: destruye la sesion y redirecciona al login

---

## 6. Includes globales

### Header

Archivo:

- `Pages/Includes/Header/Header.php`

Funcion:

- abre la estructura HTML
- carga Bootstrap
- carga Font Awesome
- carga estilos especificos por vista

### Menu

Archivo:

- `Pages/Includes/Menu/Menu.php`

Funcion:

- muestra la barra de navegacion
- muestra accesos a `Home` y `Categoria`
- marca la vista activa segun la ruta actual
- muestra botones de `Login` y `Registro`
- muestra datos del usuario cuando hay sesion

### Footer

Archivo:

- `Pages/Includes/Footer/Footer.php`

Funcion:

- muestra el pie de pagina
- carga jQuery
- carga Bootstrap JS
- carga scripts especificos por vista

### Load classes

Archivo:

- `Pages/Includes/Load classes/Load classes.php`

Funcion:

- centraliza `require_once` de configuracion
- carga modelos
- carga controladores
- carga includes

---

## 7. Conexion a base de datos con PDO

Archivo:

- `Pages/Config/Database.php`

Configuracion:

- Host: `127.0.0.1`
- Puerto: `3306`
- Base de datos: `KMLLogistics`
- Usuario: `root`
- Password: `123456`

Caracteristicas:

- usa PDO
- usa `utf8mb4`
- usa excepciones con `PDO::ATTR_ERRMODE`
- usa `FETCH_ASSOC`
- desactiva `ATTR_EMULATE_PREPARES`

---

## 8. Base de datos

Archivo SQL principal:

- `BD/Empresa/KMLLogistics.sql`

Este archivo contiene:

- creacion de la base de datos
- creacion de tablas
- inserts iniciales
- procedimientos almacenados
- consultas directas para categorias

### Tablas actuales

#### categorias

- `id_categoria`
- `nombre_categoria`
- `descripcion`
- `estado`
- `created_at`
- `updated_at`
- `deleted_at`

#### marcas

- `id_marca`
- `nombre_marca`
- `estado`
- `created_at`
- `updated_at`
- `deleted_at`

#### productos

- `id_producto`
- `codigo`
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

#### usuarios

- `id_usuario`
- `nombres`
- `apellidos`
- `correo`
- `password_hash`
- `rol`
- `estado`
- `created_at`
- `updated_at`
- `deleted_at`

---

## 9. Procedimientos almacenados

Definidos en:

- `BD/Empresa/KMLLogistics.sql`

### sp_buscar_por_codigo

Busca un producto exacto por codigo.

Devuelve:

- producto
- costo
- ganancia
- precio
- stock
- total
- categoria
- marca

### sp_filtrar_por_nombre

Busca productos por nombre parcial o completo.

Devuelve:

- producto
- costo
- ganancia
- precio
- stock
- total
- categoria
- marca

---

## 10. Consultas SQL directas para categorias

En el archivo `BD/Empresa/KMLLogistics.sql` tambien se dejaron consultas directas para:

- buscar categoria por ID
- buscar categoria por nombre
- actualizar categoria
- eliminar categoria

Estas consultas son SQL normal, no procedimientos almacenados.

---

## 11. Vista Home

Archivos principales:

- `Pages/Views/Home/Home.php`
- `Pages/Assets/Css/Pages/Home/Home.css`

Funcion:

- renderiza la portada principal del sistema
- muestra un carrusel hero con 5 slides
- muestra un boton para navegar hacia `index.php?page=category`
- usa una vista separada de la pantalla de categorias

Caracteristicas visuales:

- imagenes a pantalla amplia en el hero
- placeholder automatico si falta una imagen
- estilos propios para la pagina de inicio

---

## 12. Modulo Category

Archivos principales:

- `Pages/Controller/Category/CategoryController.php`
- `Pages/Models/Category/Category.php`
- `Pages/Models/Category/CategoryCRUD.php`
- `Pages/Views/Category/Category.php`
- `Pages/Assets/Css/Pages/Category/Category.css`
- `Pages/Assets/JS/Pages/Category/Category.js`
- `Api/Category/List.php`

Funcion:

- muestra el listado de categorias en una vista independiente
- consume datos por AJAX sin recargar la pagina
- permite paginar resultados
- permite cambiar la cantidad de registros por pagina

### Orden y filtro del listado

El listado se muestra del mas reciente al mas antiguo usando:

- `ORDER BY created_at DESC, id_categoria DESC`

Tambien filtra:

- `deleted_at IS NULL`

---

## 13. Paginacion del listado de categorias

La paginacion se procesa entre el frontend y el endpoint `Api/Category/List.php`.

Funcionamiento:

1. La vista `Category.php` muestra la estructura de la tabla, el selector de registros y los botones `Anterior` y `Siguiente`.
2. `Category.js` envia una peticion AJAX con `page` y `page_size`.
3. `Api/Category/List.php` valida esos parametros.
4. `CategoryCRUD::listCategories()` aplica `LIMIT` y `OFFSET`.
5. El endpoint devuelve JSON con:
   - `categories`
   - `pagination.page`
   - `pagination.page_size`
   - `pagination.total`
   - `pagination.total_pages`
6. jQuery vuelve a pintar la tabla y actualiza el texto de pagina actual.

Tamanos permitidos por pagina:

- `10`
- `20`
- `50`

---

## 14. AJAX en Category

La tabla de categorias no se renderiza con filas fijas desde PHP.

Funcionamiento:

1. La vista carga la estructura base.
2. `Category.js` consulta por AJAX a `Api/Category/List.php`.
3. El endpoint devuelve JSON.
4. jQuery pinta las filas en el `tbody`.
5. Los botones de paginacion permiten avanzar o retroceder sin recargar la pagina.

Esto permite:

- consultar datos de forma dinamica
- navegar entre paginas del listado
- cambiar la cantidad de registros visibles

---

## 15. Tabla responsive de categorias

La tabla fue adaptada para pantallas pequenas y para listados mas largos.

Caracteristicas:

- contenedor con `overflow-x: auto`
- scroll horizontal visible
- scroll vertical cuando la cantidad de filas lo requiere
- ancho minimo de tabla para evitar que las columnas se deformen
- soporte de arrastre con puntero sobre el contenedor
- compatible con celular, tablet y desktop

---

## 16. Login

Archivos principales:

- `Pages/Controller/Login/LoginController.php`
- `Pages/Views/Login/Login.php`
- `Pages/Assets/Css/Pages/Login/Login.css`
- `Pages/Assets/JS/Pages/Login/Login.js`

Funcion:

- valida campos obligatorios
- busca al usuario por correo
- valida si el usuario esta activo
- verifica password con `password_verify`
- guarda sesion al autenticar correctamente

Datos guardados en sesion:

- `id_usuario`
- `nombres`
- `apellidos`
- `correo`
- `rol`

---

## 17. Registro

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
- evita duplicados
- guarda password con `password_hash`

---

## 18. Usuario base del sistema

El SQL crea un usuario inicial:

- Correo: `admin@kmllogistics.com`
- Password: `123456`

La password guardada en la base esta hasheada.

---

## 19. Carrusel de imagenes

La vista `Home` usa 5 imagenes.

Ubicacion:

- `Pages/Images/Carousel/slide-1.jpg`
- `Pages/Images/Carousel/slide-2.jpg`
- `Pages/Images/Carousel/slide-3.jpg`
- `Pages/Images/Carousel/slide-4.jpg`
- `Pages/Images/Carousel/slide-5.jpg`

Si una imagen no existe, la vista muestra un placeholder para no romper el diseno.

---

## 20. Como ejecutar el proyecto

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

Ejecutar:

- `BD/Empresa/KMLLogistics.sql`

Esto creara:

- base de datos
- tablas
- datos iniciales
- procedimientos almacenados
- consultas de apoyo

### Paso 4

Abrir:

```text
http://localhost/KMLLogistics/
```

---

## 21. Problemas comunes

### Error: tabla `categorias` no existe

Significa que aun no se ejecuto el SQL.

Solucion:

- importar `BD/Empresa/KMLLogistics.sql`

### Error de conexion PDO

Revisar:

- que MySQL este encendido
- que la base exista
- que el usuario sea `root`
- que la password sea `123456`
- que el puerto sea `3306`

### La tabla no carga o no cambia de pagina

Revisar:

- que jQuery se este cargando
- que `Api/Category/List.php` responda correctamente
- que los parametros `page` y `page_size` lleguen al endpoint
- que no haya error 500 en el endpoint

### No aparecen imagenes del carrusel

Revisar que existan los archivos `slide-1.jpg` a `slide-5.jpg` dentro de:

- `Pages/Images/Carousel/`

---

## 22. Resumen final

El proyecto fue organizado para que:

- todo pase por `index.php`
- `Home` y `Category` sean vistas separadas
- la conexion use PDO
- login y registro usen buenas practicas
- el listado de categorias tenga paginacion por AJAX
- la tabla de categorias sea responsive
- la navegacion entre vistas sea clara desde el menu principal
- la base tenga `created_at`, `updated_at` y `deleted_at`

Con esto el sistema queda mas ordenado, reutilizable y preparado para seguir creciendo.
