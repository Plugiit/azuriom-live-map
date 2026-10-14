(function () {
    'use strict';

    const LOAD_TIMEOUT = 15000;

    function requestFullscreen(element) {
        if (element.requestFullscreen) {
            return element.requestFullscreen();
        }

        if (element.webkitRequestFullscreen) {
            return element.webkitRequestFullscreen();
        }
    }

    function exitFullscreen() {
        if (document.exitFullscreen) {
            return document.exitFullscreen();
        }

        if (document.webkitExitFullscreen) {
            return document.webkitExitFullscreen();
        }
    }

    function fullscreenElement() {
        return document.fullscreenElement || document.webkitFullscreenElement || null;
    }

    function initMap(container) {
        const map = container.querySelector('.plm-map');
        const iframe = container.querySelector('iframe');
        const loader = container.querySelector('[data-plm-loader]');
        const fallback = container.querySelector('[data-plm-fallback]');
        const fullscreenButton = container.querySelector('[data-plm-fullscreen]');
        const retryButton = container.querySelector('[data-plm-retry]');
        let loaded = false;
        let timer = null;

        if (!map || !iframe) {
            return;
        }

        function startTimer() {
            clearTimeout(timer);
            timer = setTimeout(function () {
                if (!loaded && fallback) {
                    if (loader) {
                        loader.hidden = true;
                    }
                    fallback.hidden = false;
                }
            }, LOAD_TIMEOUT);
        }

        iframe.addEventListener('load', function () {
            if (!iframe.getAttribute('src') || iframe.getAttribute('src') === 'about:blank') {
                return;
            }

            loaded = true;
            clearTimeout(timer);

            if (loader) {
                loader.hidden = true;
            }

            if (fallback) {
                fallback.hidden = true;
            }
        });

        iframe.addEventListener('error', function () {
            if (fallback) {
                if (loader) {
                    loader.hidden = true;
                }
                fallback.hidden = false;
            }
        });

        // Start the fallback timer only once the (lazy) iframe is about to load.
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function (entries) {
                if (entries.some(function (entry) { return entry.isIntersecting; })) {
                    observer.disconnect();
                    startTimer();
                }
            }, { rootMargin: '200px' });
            observer.observe(map);
        } else {
            startTimer();
        }

        if (retryButton) {
            retryButton.addEventListener('click', function () {
                loaded = false;
                fallback.hidden = true;
                if (loader) {
                    loader.hidden = false;
                }
                const src = iframe.getAttribute('src');
                iframe.setAttribute('src', 'about:blank');
                setTimeout(function () {
                    iframe.setAttribute('src', src);
                    startTimer();
                }, 50);
            });
        }

        if (fullscreenButton) {
            const enabled = document.fullscreenEnabled || document.webkitFullscreenEnabled;

            if (!enabled) {
                fullscreenButton.hidden = true;
            }

            const label = fullscreenButton.querySelector('[data-plm-fullscreen-label]');
            const icon = fullscreenButton.querySelector('i');

            fullscreenButton.addEventListener('click', function () {
                if (fullscreenElement() === map) {
                    exitFullscreen();
                } else {
                    requestFullscreen(map);
                }
            });

            ['fullscreenchange', 'webkitfullscreenchange'].forEach(function (event) {
                document.addEventListener(event, function () {
                    const active = fullscreenElement() === map;

                    if (label) {
                        label.textContent = active ? fullscreenButton.dataset.exitLabel : fullscreenButton.dataset.enterLabel;
                    }

                    if (icon) {
                        icon.className = active ? 'bi bi-fullscreen-exit' : 'bi bi-arrows-fullscreen';
                    }
                });
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-plm]').forEach(initMap);

        document.querySelectorAll('[data-plm-select]').forEach(function (select) {
            select.addEventListener('change', function () {
                if (select.value) {
                    window.location.href = select.value;
                }
            });
        });
    });
})();
