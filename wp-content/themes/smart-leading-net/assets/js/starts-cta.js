/**
 * Smart Leading Net — Starts CTA section
 */
(function () {
	'use strict';

	function getConfig() {
		return window.slnStartsCtaForm || {};
	}

	function isValidEmail(value) {
		return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
	}

	function initStartsCtaForm() {
		var form = document.querySelector('.starts-cta__form');

		if (!form) {
			return;
		}

		var config = getConfig();
		var input = form.querySelector('.starts-cta__input');
		var submitButton = form.querySelector('.starts-cta__submit');
		var submitText = submitButton ? submitButton.querySelector('.sls-btn__text') : null;
		var defaultLabel = submitText ? submitText.textContent : '';
		var messageEl = form.querySelector('.starts-cta__form-message');

		function showError(message) {
			if (!messageEl) {
				window.alert(message);
				return;
			}

			messageEl.textContent = message;
			messageEl.hidden = false;
			messageEl.classList.add('is-visible');
		}

		function hideError() {
			if (!messageEl) {
				return;
			}

			messageEl.hidden = true;
			messageEl.textContent = '';
			messageEl.classList.remove('is-visible');
		}

		form.addEventListener('submit', function (event) {
			event.preventDefault();
			hideError();

			if (!input) {
				return;
			}

			var email = input.value.trim();

			if (!isValidEmail(email)) {
				showError(config.invalidEmailMessage || 'Please enter a valid email address.');
				input.focus();
				return;
			}

			if (!config.ajaxUrl || !config.action || !config.nonce) {
				showError(config.errorMessage || 'Something went wrong. Please try again.');
				return;
			}

			if (submitButton) {
				submitButton.disabled = true;
				submitButton.setAttribute('aria-busy', 'true');
			}

			if (submitText) {
				submitText.textContent = config.submittingLabel || 'Submitting…';
			}

			var body = new FormData();
			body.append('action', config.action);
			body.append('nonce', config.nonce);
			body.append('email', email);

			fetch(config.ajaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				body: body,
			})
				.then(function (response) {
					return response.json().then(function (data) {
						return {
							ok: response.ok,
							data: data,
						};
					});
				})
				.then(function (result) {
					var data = result.data || {};

					if (data.success) {
						form.reset();
						form.classList.add('is-submitted');
						return;
					}

					showError(data.message || config.errorMessage || 'Something went wrong. Please try again.');
				})
				.catch(function () {
					showError(config.errorMessage || 'Something went wrong. Please try again.');
				})
				.finally(function () {
					if (form.classList.contains('is-submitted')) {
						return;
					}

					if (submitButton) {
						submitButton.disabled = false;
						submitButton.removeAttribute('aria-busy');
					}

					if (submitText) {
						submitText.textContent = defaultLabel;
					}
				});
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initStartsCtaForm);
	} else {
		initStartsCtaForm();
	}
})();
