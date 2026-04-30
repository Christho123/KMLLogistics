// =========================================================
// SCRIPT: ADMIN NOTIFICATION FLUSH
// Dispara el envio de correos administrativos despues de acciones AJAX.
// =========================================================
(function () {
    if (window.__kmlAdminNotificationFlushInitialized || !window.jQuery) {
        return;
    }

    window.__kmlAdminNotificationFlushInitialized = true;

    var flushUrl = 'Api/Audit/FlushAdminNotifications.php';
    var flushTimer = null;

    function isMutationRequest(settings) {
        var method = String(settings.type || settings.method || 'GET').toUpperCase();
        var url = String(settings.url || '');

        if (['POST', 'PUT', 'DELETE'].indexOf(method) === -1) {
            return false;
        }

        return url.indexOf('Api/') !== -1 && url.indexOf(flushUrl) === -1;
    }

    function flushNotifications() {
        if (navigator.sendBeacon) {
            var payload = new Blob(['{}'], { type: 'application/json' });
            navigator.sendBeacon(flushUrl, payload);
            return;
        }

        fetch(flushUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json; charset=UTF-8'
            },
            body: '{}',
            keepalive: true
        }).catch(function () {});
    }

    function scheduleFlush() {
        if (flushTimer) {
            window.clearTimeout(flushTimer);
        }

        flushTimer = window.setTimeout(flushNotifications, 120);
    }

    jQuery(document).ajaxSuccess(function (_, xhr, settings) {
        if (!isMutationRequest(settings)) {
            return;
        }

        var response = xhr.responseJSON;

        if (response && response.success === false) {
            return;
        }

        scheduleFlush();
    });
})();
