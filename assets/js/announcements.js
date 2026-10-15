/*!
 * Plugiit Announcements – bars (dismiss, rotation, countdown) and popups.
 */
(function () {
    'use strict';

    if (window.Announcements) {
        return;
    }

    var config = {texts: {}};
    var configElement = document.getElementById('plugiit-ann-config');

    try {
        config = JSON.parse(configElement.textContent) || config;
    } catch (e) {
        // Use the defaults.
    }

    var texts = config.texts || {};

    /* ------------------------------------------------------------------ */
    /* Storage helpers (private browsing may throw)                        */
    /* ------------------------------------------------------------------ */

    function storageGet(storage, key) {
        try {
            return window[storage].getItem(key);
        } catch (e) {
            return null;
        }
    }

    function storageSet(storage, key, value) {
        try {
            window[storage].setItem(key, value);
        } catch (e) {
            // Ignored: the announcement will simply be displayed again.
        }
    }

    /* ------------------------------------------------------------------ */
    /* Countdowns                                                          */
    /* ------------------------------------------------------------------ */

    function pad(value) {
        return value < 10 ? '0' + value : String(value);
    }

    function formatRemaining(ms) {
        var total = Math.floor(ms / 1000);
        var days = Math.floor(total / 86400);
        var hours = Math.floor((total % 86400) / 3600);
        var minutes = Math.floor((total % 3600) / 60);
        var seconds = total % 60;
        var parts = [];

        if (days > 0) {
            parts.push(days + (texts.days || 'd'));
        }

        parts.push(pad(hours) + (texts.hours || 'h'));
        parts.push(pad(minutes) + (texts.minutes || 'm'));
        parts.push(pad(seconds) + (texts.seconds || 's'));

        return (texts.prefix ? texts.prefix + ' ' : '') + parts.join(' ');
    }

    function updateCountdowns() {
        var now = Date.now();
        var elements = document.querySelectorAll('[data-plugiit-ann-countdown]');

        Array.prototype.forEach.call(elements, function (element) {
            var end = Date.parse(element.getAttribute('data-plugiit-ann-countdown'));

            if (isNaN(end)) {
                return;
            }

            var remaining = end - now;

            if (remaining <= 0) {
                element.textContent = texts.ended || '';
                var bar = element.closest('.plugiit-ann-bar');

                if (bar) {
                    removeBar(bar);
                }

                element.removeAttribute('data-plugiit-ann-countdown');

                return;
            }

            element.textContent = formatRemaining(remaining);
            element.setAttribute('aria-label', element.textContent);
        });

        return elements.length > 0;
    }

    /* ------------------------------------------------------------------ */
    /* Bars                                                                */
    /* ------------------------------------------------------------------ */

    var container = document.getElementById('plugiit-ann-bars');
    var rotation = {timer: null, index: 0, paused: false, dots: null};

    function bars() {
        return container ? Array.prototype.slice.call(container.querySelectorAll('.plugiit-ann-bar')) : [];
    }

    function removeBar(bar) {
        if (bar.parentNode) {
            bar.parentNode.removeChild(bar);
        }

        if (container && bars().length === 0) {
            stopRotation();
            container.parentNode && container.parentNode.removeChild(container);
            container = null;

            return;
        }

        if (isRotating()) {
            rotation.index = Math.min(rotation.index, bars().length - 1);
            buildDots();
            show(rotation.index);
        }
    }

    function isRotating() {
        return container && container.getAttribute('data-plugiit-ann-mode') === 'rotate';
    }

    function show(index) {
        var list = bars();

        if (list.length === 0) {
            return;
        }

        rotation.index = (index + list.length) % list.length;

        list.forEach(function (bar, i) {
            bar.classList.toggle('plugiit-ann-inactive', i !== rotation.index);
        });

        if (rotation.dots) {
            Array.prototype.forEach.call(rotation.dots.children, function (dot, i) {
                dot.setAttribute('aria-current', i === rotation.index ? 'true' : 'false');
            });
        }
    }

    function buildDots() {
        if (rotation.dots) {
            rotation.dots.parentNode && rotation.dots.parentNode.removeChild(rotation.dots);
            rotation.dots = null;
        }

        var list = bars();

        if (list.length < 2) {
            return;
        }

        var dots = document.createElement('div');
        dots.className = 'plugiit-ann-dots';

        list.forEach(function (bar, i) {
            var dot = document.createElement('button');
            dot.type = 'button';
            dot.className = 'plugiit-ann-dot';
            dot.setAttribute('aria-label', (texts.show || 'Announcement') + ' ' + (i + 1) + '/' + list.length);
            dot.addEventListener('click', function () {
                show(i);
            });
            dots.appendChild(dot);
        });

        container.appendChild(dots);
        rotation.dots = dots;
    }

    function stopRotation() {
        if (rotation.timer) {
            window.clearInterval(rotation.timer);
            rotation.timer = null;
        }
    }

    function startRotation() {
        if (!isRotating() || bars().length < 2) {
            return;
        }

        var delay = parseInt(container.getAttribute('data-plugiit-ann-delay'), 10) || 6;
        var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        buildDots();
        show(0);

        if (reduceMotion) {
            return;
        }

        rotation.timer = window.setInterval(function () {
            if (!rotation.paused && !document.hidden) {
                show(rotation.index + 1);
            }
        }, delay * 1000);

        ['mouseenter', 'focusin'].forEach(function (name) {
            container.addEventListener(name, function () {
                rotation.paused = true;
            });
        });

        ['mouseleave', 'focusout'].forEach(function (name) {
            container.addEventListener(name, function () {
                rotation.paused = false;
            });
        });
    }

    if (container) {
        container.addEventListener('click', function (event) {
            var button = event.target.closest('[data-plugiit-ann-close]');

            if (!button) {
                return;
            }

            var bar = button.closest('.plugiit-ann-bar');

            storageSet('localStorage', 'plugiit-ann-dismissed-' + bar.getAttribute('data-plugiit-ann-id'), bar.getAttribute('data-plugiit-ann-version'));
            removeBar(bar);
        });

        startRotation();
    }

    /* ------------------------------------------------------------------ */
    /* Popup (at most one per page load)                                   */
    /* ------------------------------------------------------------------ */

    var popup = null;
    var lastFocus = null;

    function alreadySeen(element) {
        var key = 'plugiit-ann-seen-' + element.getAttribute('data-plugiit-ann-id');
        var version = element.getAttribute('data-plugiit-ann-version');

        switch (element.getAttribute('data-plugiit-ann-frequency')) {
            case 'once':
                return storageGet('localStorage', key) === version;
            case 'session':
                return storageGet('sessionStorage', key) === version;
            default:
                return false;
        }
    }

    function markSeen(element) {
        var key = 'plugiit-ann-seen-' + element.getAttribute('data-plugiit-ann-id');
        var version = element.getAttribute('data-plugiit-ann-version');
        var frequency = element.getAttribute('data-plugiit-ann-frequency');

        if (frequency === 'once') {
            storageSet('localStorage', key, version);
        } else if (frequency === 'session') {
            storageSet('sessionStorage', key, version);
        }
    }

    function focusables(element) {
        return Array.prototype.filter.call(
            element.querySelectorAll('a[href], button, input, select, textarea, [tabindex]:not([tabindex="-1"])'),
            function (item) {
                return !item.disabled && item.offsetParent !== null;
            }
        );
    }

    function openPopup() {
        lastFocus = document.activeElement;
        popup.hidden = false;
        markSeen(popup);
        popup.querySelector('.plugiit-ann-dialog').focus();
        document.addEventListener('keydown', onKeydown);
    }

    function closePopup() {
        if (!popup || popup.hidden) {
            return;
        }

        popup.hidden = true;
        document.removeEventListener('keydown', onKeydown);

        if (lastFocus && typeof lastFocus.focus === 'function' && document.body.contains(lastFocus)) {
            lastFocus.focus();
        }

        document.dispatchEvent(new CustomEvent('plugiit-ann:popup-closed'));
    }

    function onKeydown(event) {
        if (event.key === 'Escape') {
            event.preventDefault();
            closePopup();

            return;
        }

        if (event.key !== 'Tab') {
            return;
        }

        var dialog = popup.querySelector('.plugiit-ann-dialog');
        var elements = focusables(dialog);

        if (elements.length === 0) {
            event.preventDefault();

            return;
        }

        var first = elements[0];
        var last = elements[elements.length - 1];

        if (event.shiftKey && (document.activeElement === first || document.activeElement === dialog)) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        } else if (!dialog.contains(document.activeElement)) {
            event.preventDefault();
            first.focus();
        }
    }

    var candidates = document.querySelectorAll('[data-plugiit-ann-popup]');

    for (var i = 0; i < candidates.length; i++) {
        if (!popup && !alreadySeen(candidates[i])) {
            popup = candidates[i];
        } else {
            candidates[i].parentNode.removeChild(candidates[i]);
        }
    }

    if (popup) {
        // Keep the dialog in a predictable stacking context.
        document.body.appendChild(popup);

        popup.addEventListener('click', function (event) {
            if (event.target === popup || event.target.closest('[data-plugiit-ann-close]')) {
                closePopup();
            }
        });

        var schedulePopup = function () {
            window.setTimeout(openPopup, 600);
        };

        // Do not stack the popup over the cookie consent banner (plugiit-cookie-consent).
        var consent = window.PlugiitCookieConsent;

        if (consent && typeof consent.isOpen === 'function' && consent.isOpen()) {
            document.addEventListener('plugiit-cc:closed', schedulePopup, {once: true});
        } else if (!consent && document.getElementById('plugiit-cc-config')) {
            // The consent script is not initialized yet (deferred scripts order).
            document.addEventListener('plugiit-cc:ready', function (event) {
                if (event.detail && event.detail.pending) {
                    document.addEventListener('plugiit-cc:closed', schedulePopup, {once: true});
                } else {
                    schedulePopup();
                }
            }, {once: true});
        } else {
            schedulePopup();
        }
    }

    /* ------------------------------------------------------------------ */

    if (updateCountdowns()) {
        window.setInterval(updateCountdowns, 1000);
    }

    window.Announcements = {
        closePopup: closePopup
    };
})();
