<?php
declare(strict_types=1);

// =========================================================
// INCLUDE: FOOTER
// Pie de pagina y carga global de jQuery y Bootstrap JS.
// =========================================================



// Render del pie de pagina y carga de scripts.
// Tecnologia asociada: jQuery para interacciones y Bootstrap JS para modals/componentes.
function renderFooter(array $scripts = []): void
{
    ?>
        <style>
            .footer-company-list li {
                color: rgba(255, 255, 255, 0.72);
                line-height: 1.65;
                margin-bottom: 0.45rem;
            }

            .footer-company-list span {
                color: #fff;
                font-weight: 700;
            }
        </style>
        <footer class="app-footer bg-dark text-white pt-5 pb-4">
            <div class="container">
                <div class="row g-4 align-items-start">
                    <div class="col-lg-4">
                        <h5 class="fw-bold mb-2">KML Logistic S.A.C.</h5>
                        <p class="text-white-50 mb-3">
                            Logistica internacional y transporte de mercancias.
                        </p>
                        <small class="text-white-50">KMLLogistics &copy; <?= date('Y'); ?>.</small>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <h6 class="fw-semibold text-uppercase mb-3">Datos generales</h6>
                        <ul class="list-unstyled footer-company-list mb-0">
                            <li><span>RUC:</span> 20556054755</li>
                            <li><span>Tipo empresa:</span> Sociedad Anonima Cerrada (S.A.C.)</li>
                            <li><span>Gerente General:</span> Gabriel Erickson Abreu Hoyos</li>
                        </ul>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <h6 class="fw-semibold text-uppercase mb-3">Ubicacion</h6>
                        <ul class="list-unstyled footer-company-list mb-0">
                            <li><span>Direccion:</span> Jr Francisco Lazo 1932, Dpto 404, Lince</li>
                            <li><span>Rubro:</span> Logistica internacional y transporte de mercancias</li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
        </div>
        <script src="Pages/Assets/JS/Framework/JQuery/jquery.js"></script>
        <script src="Pages/Assets/Css/Framework/BootStrap/js/bootstrap.bundle.min.js"></script>
        <script src="Pages/Assets/JS/Framework/AdminNotificationFlush.js"></script>
        <?php foreach ($scripts as $script): ?>
            <script src="<?= htmlspecialchars($script, ENT_QUOTES, 'UTF-8'); ?>" data-page-script="true"></script>
        <?php endforeach; ?>
        <script src="Pages/Assets/JS/Framework/AppNavigation.js"></script>
    </body>
    </html>
    <?php
}

