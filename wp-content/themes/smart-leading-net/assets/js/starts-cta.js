/**
 * Smart Leading Net — Starts CTA section
 */
(function () {
	'use strict';

	function normalizeWebsite(value) {
		var trimmed = value.trim();

		if (!trimmed) {
			return '';
		}

		if (!/^https?:\/\//i.test(trimmed)) {
			return 'https://' + trimmed;
		}

		return trimmed;
	}

	function initStartsCtaForm() {
		var form = document.querySelector('.starts-cta__form');

		if (!form) {
			return;
		}

		var input = form.querySelector('.starts-cta__input');

		form.addEventListener('submit', function (event) {
			event.preventDefault();

			if (!input) {
				return;
			}

			var website = normalizeWebsite(input.value);

			if (!website) {
				input.focus();
				return;
			}

			input.value = website;
			form.classList.add('is-submitted');
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initStartsCtaForm);
	} else {
		initStartsCtaForm();
	}
})();
