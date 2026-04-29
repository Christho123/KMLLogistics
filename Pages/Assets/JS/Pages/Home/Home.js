// =========================================================
// SCRIPT: HOME
// Interacciones visuales para la pagina principal.
// =========================================================
$(function () {
    var values = [];
    var $valueData = $('#homeValuesData');
    var $valueTabs = $('.value-tab');
    var $valueDetailIcon = $('#valueDetailIcon');
    var $valueDetailTitle = $('#valueDetailTitle');
    var $valueDetailText = $('#valueDetailText');

    function loadValues() {
        try {
            values = JSON.parse($valueData.text() || '[]');
        } catch (error) {
            values = [];
        }
    }

    function showValue(index) {
        var value = values[index];

        if (!value) {
            return;
        }

        $valueTabs.removeClass('active');
        $valueTabs.filter('[data-value-index="' + index + '"]').addClass('active');
        $valueDetailIcon.attr('class', 'fas ' + value.icon);
        $valueDetailTitle.text(value.title);
        $valueDetailText.text(value.text);
    }

    function animateCounter(element) {
        var $element = $(element);
        var target = Number($element.data('count')) || 0;
        var current = 0;
        var steps = 34;
        var increment = target / steps;

        if ($element.data('counted')) {
            return;
        }

        $element.data('counted', true);

        var timer = window.setInterval(function () {
            current += increment;

            if (current >= target) {
                current = target;
                window.clearInterval(timer);
            }

            $element.text(Math.round(current));
        }, 28);
    }

    function bindRevealAnimations() {
        var observer;

        if (!('IntersectionObserver' in window)) {
            $('.js-reveal').addClass('is-visible');
            $('.js-count').each(function (_, element) {
                animateCounter(element);
            });
            return;
        }

        observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }

                $(entry.target).addClass('is-visible');

                if ($(entry.target).find('.js-count').length) {
                    $(entry.target).find('.js-count').each(function (_, element) {
                        animateCounter(element);
                    });
                }

                if ($(entry.target).hasClass('js-count')) {
                    animateCounter(entry.target);
                }

                observer.unobserve(entry.target);
            });
        }, {
            threshold: 0.18
        });

        $('.js-reveal, .js-count').each(function (_, element) {
            observer.observe(element);
        });
    }

    function bindInteractions() {
        $valueTabs.on('click', function () {
            showValue(Number($(this).data('value-index')) || 0);
        });

        $('.service-more').on('click', function () {
            var $card = $(this).closest('.service-card');
            $('.service-card').not($card).removeClass('is-highlighted');
            $card.toggleClass('is-highlighted');
        });
    }

    loadValues();
    bindRevealAnimations();
    bindInteractions();
});
