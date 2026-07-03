/**
 * Smart Leading Net — Growing section (lead form + optional reveal)
 */
(function () {
	'use strict';

	var successHideTimer = null;

	function getConfig() {
		return window.slnGrowingForm || {};
	}

	function getSectionRoot() {
		return document.querySelector('.growing__container');
	}

	function hideErrorMessage(container) {
		var messageEl = container.querySelector('.growing__form-message');

		if (!messageEl) {
			return;
		}

		messageEl.hidden = true;
		messageEl.textContent = '';
		messageEl.classList.remove('is-error', 'is-visible');
	}

	function showErrorMessage(container, message) {
		var messageEl = container.querySelector('.growing__form-message');
		var successBox = container.querySelector('.growing__success-box');

		if (successBox) {
			successBox.hidden = true;
			successBox.classList.remove('is-visible', 'is-fading-out');
		}

		if (!messageEl) {
			return;
		}

		messageEl.textContent = message;
		messageEl.hidden = false;
		messageEl.classList.remove('is-success');
		messageEl.classList.add('is-error');

		requestAnimationFrame(function () {
			messageEl.classList.add('is-visible');
		});
	}

	function clearSuccessTimer() {
		if (successHideTimer) {
			window.clearTimeout(successHideTimer);
			successHideTimer = null;
		}
	}

	function showSuccessState(container, config) {
		var form = container.querySelector('.growing__form');
		var successBox = container.querySelector('.growing__success-box');
		var note = container.querySelector('.growing__note--form');

		hideErrorMessage(container);
		clearSuccessTimer();

		if (form) {
			form.reset();
			form.classList.add('is-hidden');
			form.setAttribute('aria-hidden', 'true');
		}

		if (note) {
			note.hidden = true;
		}

		if (!successBox) {
			return;
		}

		var titleEl = successBox.querySelector('.growing__success-title');
		var textEls = successBox.querySelectorAll('.growing__success-text');

		if (titleEl && config.successTitle) {
			titleEl.textContent = config.successTitle;
		}

		if (textEls.length && config.successLines && config.successLines.length) {
			textEls.forEach(function (el, index) {
				if (config.successLines[index]) {
					el.textContent = config.successLines[index];
				}
			});
		}

		successBox.hidden = false;
		successBox.classList.remove('is-fading-out');

		requestAnimationFrame(function () {
			successBox.classList.add('is-visible');
		});

		var visibleMs = parseInt(config.successVisibleMs, 10);

		if (Number.isNaN(visibleMs) || visibleMs < 1000) {
			visibleMs = 10000;
		}

		successHideTimer = window.setTimeout(function () {
			successBox.classList.add('is-fading-out');
			successBox.classList.remove('is-visible');
		}, visibleMs);
	}

	function initGrowingForm() {
		var container = getSectionRoot();
		var form = container ? container.querySelector('.growing__form') : null;

		if (!form || !container) {
			return;
		}

		var config = getConfig();
		var submitButton = form.querySelector('.growing__submit');
		var defaultButtonText = submitButton ? submitButton.textContent : '';

		form.addEventListener('submit', function (event) {
			event.preventDefault();
			hideErrorMessage(container);

			if (!config.ajaxUrl || !config.action || !config.nonce) {
				showErrorMessage(container, config.errorMessage || 'Something went wrong. Please try again or contact us directly.');
				return;
			}

			var nameInput = form.querySelector('[name="growing_name"]');
			var emailInput = form.querySelector('[name="growing_email"]');
			var websiteInput = form.querySelector('[name="growing_website"]');

			var name = nameInput ? nameInput.value.trim() : '';
			var email = emailInput ? emailInput.value.trim() : '';
			var website = websiteInput ? websiteInput.value.trim() : '';

			if (!name) {
				showErrorMessage(container, 'Please enter your full name.');
				if (nameInput) {
					nameInput.focus();
				}
				return;
			}

			if (!email || email.indexOf('@') === -1) {
				showErrorMessage(container, 'Please enter a valid email address.');
				if (emailInput) {
					emailInput.focus();
				}
				return;
			}

			if (submitButton) {
				submitButton.disabled = true;
				submitButton.setAttribute('aria-busy', 'true');
				submitButton.textContent = config.sendingLabel || 'Sending…';
			}

			var body = new FormData();
			body.append('action', config.action);
			body.append('nonce', config.nonce);
			body.append('name', name);
			body.append('email', email);
			body.append('website', website);

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
						var successConfig = Object.assign({}, config, {
							successTitle: data.title || config.successTitle,
							successLines: [
								data.message || (config.successLines && config.successLines[0]) || '',
								data.message_2 || (config.successLines && config.successLines[1]) || '',
							],
						});

						showSuccessState(container, successConfig);
						return;
					}

					showErrorMessage(
						container,
						config.errorMessage || 'Something went wrong. Please try again or contact us directly.'
					);
				})
				.catch(function () {
					showErrorMessage(
						container,
						config.errorMessage || 'Something went wrong. Please try again or contact us directly.'
					);
				})
				.finally(function () {
					if (submitButton) {
						submitButton.disabled = false;
						submitButton.removeAttribute('aria-busy');
						submitButton.textContent = defaultButtonText;
					}
				});
		});
	}

	function initGrowingReveal() {
		var section = document.querySelector('.growing');

		if (section) {
			section.classList.add('is-visible');
		}
	}

	function init() {
		initGrowingForm();
		initGrowingReveal();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();