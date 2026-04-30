// =========================================================
// SCRIPT: APP NAVIGATION
// Navegacion interna por AJAX para cambiar vistas sin recargar todo el navegador.
// =========================================================
(function () {
    if (window.__kmlAppNavigationInitialized) {
        return;
    }

    window.__kmlAppNavigationInitialized = true;

    var APP_SHELL_SELECTOR = '#app-shell';
    var PAGE_STYLE_SELECTOR = 'link[data-page-style="true"]';
    var PAGE_SCRIPT_SELECTOR = 'script[data-page-script="true"]';
    var activeRequestId = 0;

    function normalizeAppPath(pathname) {
        return pathname.replace(/\/index\.php$/i, '/');
    }

    // Detecta enlaces internos que pueden cargarse dentro del shell principal.
    function isInternalNavigationLink(anchor) {
        if (!anchor || !anchor.href) {
            return false;
        }

        if (anchor.target && anchor.target !== '_self') {
            return false;
        }

        if (anchor.hasAttribute('download') || anchor.getAttribute('rel') === 'external') {
            return false;
        }

        var url = new URL(anchor.href, window.location.href);

        if (url.origin !== window.location.origin) {
            return false;
        }

        if (normalizeAppPath(url.pathname) !== normalizeAppPath(window.location.pathname)) {
            return false;
        }

        if (!url.searchParams.has('page')) {
            return normalizeAppPath(url.pathname) === normalizeAppPath(window.location.pathname);
        }

        return url.searchParams.get('page') !== 'logout';
    }

    // Reemplaza los estilos propios de la vista cargada por AJAX.
    function updatePageStyles(doc) {
        document.querySelectorAll(PAGE_STYLE_SELECTOR).forEach(function (styleNode) {
            styleNode.remove();
        });

        doc.querySelectorAll(PAGE_STYLE_SELECTOR).forEach(function (styleNode) {
            document.head.appendChild(styleNode.cloneNode(true));
        });
    }

    // Reinserta scripts de pagina para que sus eventos se registren nuevamente.
    function runPageScripts(doc) {
        document.querySelectorAll(PAGE_SCRIPT_SELECTOR).forEach(function (scriptNode) {
            scriptNode.remove();
        });

        doc.querySelectorAll(PAGE_SCRIPT_SELECTOR).forEach(function (scriptNode) {
            var newScript = document.createElement('script');

            Array.from(scriptNode.attributes).forEach(function (attribute) {
                newScript.setAttribute(attribute.name, attribute.value);
            });

            if (scriptNode.src) {
                newScript.src = scriptNode.getAttribute('src');
            } else {
                newScript.textContent = scriptNode.textContent;
            }

            document.body.appendChild(newScript);
        });
    }

    // Sustituye solo el contenido navegable y conserva el contexto global.
    function replaceShell(doc) {
        var currentShell = document.querySelector(APP_SHELL_SELECTOR);
        var nextShell = doc.querySelector(APP_SHELL_SELECTOR);

        if (!currentShell || !nextShell) {
            window.location.reload();
            return false;
        }

        currentShell.innerHTML = nextShell.innerHTML;
        return true;
    }

    // Carga la vista solicitada y actualiza historial, estilos y scripts.
    function loadPage(url, shouldPushState) {
        activeRequestId += 1;
        var requestId = activeRequestId;
        var shell = document.querySelector(APP_SHELL_SELECTOR);

        if (shell) {
            shell.style.opacity = '0.65';
            shell.style.pointerEvents = 'none';
            shell.style.transition = 'opacity 0.18s ease';
        }

        return fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('No se pudo cargar la vista solicitada.');
                }

                return response.text();
            })
            .then(function (html) {
                if (requestId !== activeRequestId) {
                    return;
                }

                var parser = new DOMParser();
                var doc = parser.parseFromString(html, 'text/html');

                updatePageStyles(doc);

                if (!replaceShell(doc)) {
                    return;
                }

                document.title = doc.title || document.title;

                if (shouldPushState) {
                    window.history.pushState({ url: url }, '', url);
                }

                runPageScripts(doc);
                window.scrollTo({ top: 0, behavior: 'auto' });
            })
            .catch(function () {
                window.location.href = url;
            })
            .finally(function () {
                if (requestId !== activeRequestId) {
                    return;
                }

                var refreshedShell = document.querySelector(APP_SHELL_SELECTOR);

                if (refreshedShell) {
                    refreshedShell.style.opacity = '1';
                    refreshedShell.style.pointerEvents = '';
                }
            });
    }

    document.addEventListener('click', function (event) {
        var anchor = event.target.closest('a');

        if (!anchor || event.defaultPrevented) {
            return;
        }

        if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || event.button !== 0) {
            return;
        }

        if (!isInternalNavigationLink(anchor)) {
            return;
        }

        event.preventDefault();
        loadPage(anchor.href, true);
    });

    window.addEventListener('popstate', function () {
        loadPage(window.location.href, false);
    });
})();
