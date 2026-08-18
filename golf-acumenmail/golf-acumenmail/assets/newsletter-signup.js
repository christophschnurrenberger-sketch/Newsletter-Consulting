/**
 * Anmeldung zum Newsletter direkt auf der Startseite.
 *
 * Ohne JavaScript funktioniert das Formular ebenfalls: dann verarbeitet
 * newsletter/subscribe.php die Anmeldung und leitet auf die Landingpage
 * weiter. Dieses Skript spart lediglich den Seitenwechsel.
 */
(function () {
    'use strict';

    var form = document.getElementById('newsletter-form');
    if (!form) {
        return;
    }

    var status = document.getElementById('newsletter-status');
    var button = form.querySelector('button[type="submit"]');

    function show(message, type) {
        if (!status) {
            return;
        }
        status.textContent = message;
        status.classList.add('is-visible');
        status.classList.toggle('is-success', type === 'success');
        status.classList.toggle('is-error', type === 'error');
    }

    form.addEventListener('submit', function (event) {
        if (!form.reportValidity()) {
            return;
        }
        event.preventDefault();

        var original = button ? button.textContent : '';
        if (button) {
            button.disabled = true;
            button.textContent = 'Wird gesendet …';
        }

        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        })
            .then(function (response) {
                return response.json().catch(function () {
                    throw new Error('Unerwartete Antwort des Servers.');
                });
            })
            .then(function (data) {
                if (data && data.ok) {
                    show(data.message || 'Bitte bestätigen Sie die Anmeldung in Ihrem Postfach.', 'success');
                    form.reset();
                } else {
                    show((data && data.message) || 'Die Anmeldung hat nicht geklappt.', 'error');
                }
            })
            .catch(function () {
                // Netzwerkproblem: klassisch abschicken, damit nichts verloren geht
                form.submit();
            })
            .finally(function () {
                if (button) {
                    button.disabled = false;
                    button.textContent = original;
                }
            });
    });
})();
