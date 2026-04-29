# Modulo Home

## Objetivo

El modulo `Home` funciona como pantalla principal del sistema. Presenta informacion corporativa de KML Logistic S.A.C., servicios, proceso logistico, mision, vision, valores y carrusel visual.

## Estructura del modulo

```text
KMLLogistics/
|-- index.php
`-- Pages/
    |-- Views/
    |   `-- Home/
    |       `-- Home.php
    |-- Includes/
    |   |-- Footer/
    |   |   `-- Footer.php
    |   |-- Header/
    |   |   `-- Header.php
    |   `-- Menu/
    |       `-- Menu.php
    |-- Assets/
    |   |-- Css/
    |   |   `-- Pages/
    |   |       `-- Home/
    |   |           `-- Home.css
    |   `-- JS/
    |       `-- Pages/
    |           `-- Home/
    |               `-- Home.js
    `-- Images/
        `-- Carousel/
            |-- slide-1.jpg
            |-- slide-2.jpg
            |-- slide-3.jpg
            |-- slide-4.jpg
            `-- slide-5.jpg
```

## Arquitectura

```text
index.php?page=home
    -> Pages/Views/Home/Home.php
        -> renderHeader()
        -> renderMenu()
        -> Home.css
        -> Home.js
        -> renderFooter()
```

Este modulo no usa modelo ni controlador propio porque es una vista informativa. Aun asi, respeta la estructura general del proyecto al cargar `Header`, `Menu`, `Footer`, CSS y JS por separado.

## Tecnologias utilizadas

- **PHP:** render de la vista y arrays de datos para slides, metricas, servicios, proceso y valores.
- **Bootstrap:** carrusel, grid responsive, botones y layout.
- **Font Awesome:** iconos en servicios, metricas y valores.
- **CSS propio:** tarjetas, paneles, animaciones visuales y responsive.
- **jQuery:** eventos de interaccion en Home.
- **JavaScript:** animaciones de scroll, contadores y valores interactivos.
- **AJAX:** no aplica en Home porque no consume endpoints ni modifica datos.
- **MVC:** participa como vista dentro del router principal del proyecto.

## JavaScript y jQuery

Archivo principal: `C:\xampp\htdocs\KMLLogistics\Pages\Assets\JS\Pages\Home\Home.js`.

Funciones principales:

- Animar tarjetas al entrar en pantalla con `IntersectionObserver`.
- Ejecutar contadores visuales para metricas.
- Cambiar el valor destacado al hacer click.
- Resaltar tarjetas de servicios.

Uso de `addClass()` y `removeClass()`:

```js
$valueTabs.removeClass('active');
$valueTabs.filter('[data-value-index="' + index + '"]').addClass('active');
```

Tambien se usa `addClass('is-visible')` para revelar elementos cuando aparecen en pantalla.

## Interfaz

- Carrusel principal con imagenes.
- Panel de metricas.
- Tarjetas de informacion corporativa.
- Tarjetas de servicios.
- Bloques de mision y vision.
- Linea de proceso logistico.
- Valores interactivos.

## Datos mostrados

El contenido incluye informacion corporativa: rubro logistico, transporte de mercancias, importacion/exportacion, gestion de carga, asesoria aduanera, historia, mision, vision y valores.
