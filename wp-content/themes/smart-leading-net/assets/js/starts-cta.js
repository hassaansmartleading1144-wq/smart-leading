/**
 * Smart Leading Net — Starts CTA (Quote CTA)
 *
 * Redirects to Contact Us and passes the website query param when provided.
 */
(function () {
	'use strict';

	function getContactUrl() {
		var config = window.slnStartsCta || {};
		return config.contactUrl || '/contact-us/';
	}

	function buildRedirectUrl(website) {
		var base = getContactUrl();
		var trimmed = website ? String(website).trim() : '';

		if (!trimmed) {
			return base;
		}

		var separator = base.indexOf('?') === -1 ? '?' : '&';
		return base + separator + 'website=' + encodeURIComponent(trimmed);
	}

	function initStartsCtaForm() {
		var form = document.querySelector('.starts-cta__form');

		if (!form) {
			return;
		}

		var input = form.querySelector('.starts-cta__input');

		form.addEventListener('submit', function (event) {
			event.preventDefault();

			var website = input ? input.value.trim() : '';
			window.location.href = buildRedirectUrl(website);
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initStartsCtaForm);
	} else {
		initStartsCtaForm();
	}
})();
