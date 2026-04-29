# Home

## Estructura del modulo

```text
Pages/Views/Home/Home.php
Pages/Assets/Css/Pages/Home/Home.css
index.php
```

## Como esta hecho

La vista Home funciona como pantalla principal del sistema. Se carga desde el router `index.php` y reutiliza header, menu y footer.

## Tecnologias

- PHP para render de vista.
- Bootstrap para carrusel y layout.
- CSS propio para estilos visuales.
- Font Awesome para iconografia.

## Flujo

```text
index.php?page=home -> Pages/Views/Home/Home.php
```

## Puntos clave

- Pantalla de bienvenida.
- Carrusel visual.
- Usa `renderHeader()`, `renderMenu()` y `renderFooter()`.
