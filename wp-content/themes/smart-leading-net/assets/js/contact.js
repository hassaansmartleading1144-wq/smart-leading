/**
 * Smart Leading Net — Contact Us page form (intl-tel-input)
 */
(function () {
	'use strict';

	var iti = null;

	function getConfig() {
		return window.slnContactForm || {};
	}

	function getSubmitButton(form) {
		return form.querySelector('.contact-page__submit');
	}

	function getSubmitLabel(button) {
		return button ? button.querySelector('.contact-page__submit-text') : null;
	}

	function setSubmitting(form, isSubmitting) {
		var config = getConfig();
		var button = getSubmitButton(form);
		var label = getSubmitLabel(button);

		if (!button || !label) {
			return;
		}

		if (isSubmitting) {
			button.disabled = true;
			button.setAttribute('aria-busy', 'true');
			label.textContent = config.submittingLabel || 'Submitting...';
			return;
		}

		button.disabled = false;
		button.removeAttribute('aria-busy');
		label.textContent = config.submitLabel || 'Submit';
	}

	function hideError(form) {
		var messageEl = form.querySelector('.contact-page__form-message');

		if (!messageEl) {
			return;
		}

		messageEl.hidden = true;
		messageEl.textContent = '';
		messageEl.classList.remove('is-visible');
	}

	function showError(form, message) {
		var config = getConfig();
		var messageEl = form.querySelector('.contact-page__form-message');

		if (!messageEl) {
			return;
		}

		messageEl.textContent = message || config.errorMessage || 'Sorry, something went wrong. Please try again.';
		messageEl.hidden = false;

		requestAnimationFrame(function () {
			messageEl.classList.add('is-visible');
		});
	}

	function initPhone(input) {
		if (!input || typeof window.intlTelInput !== 'function') {
			return null;
		}

		var config = getConfig();

		var instance = window.intlTelInput(input, {
			initialCountry: 'us',
			separateDialCode: true,
			preferredCountries: ['us', 'gb', 'au', 'no', 'fi'],
			utilsScript: config.utilsScript || undefined,
		});

		input.addEventListener('input', function () {
			input.value = input.value.replace(/[^0-9\s\-()]/g, '');
		});

		return instance;
	}

	function handleSubmit(event) {
		event.preventDefault();

		var form = event.currentTarget;
		var config = getConfig();

		hideError(form);

		var name = form.querySelector('[name="contact_name"]');
		var email = form.querySelector('[name="contact_email"]');
		var phone = form.querySelector('[name="contact_phone"]');
		var website = form.querySelector('[name="contact_website"]');
		var message = form.querySelector('[name="contact_message"]');

		if (!name || !name.value.trim()) {
			name.focus();
			return;
		}

		if (!email || !email.value.trim() || !email.checkValidity()) {
			email.focus();
			return;
		}

		if (!phone || !phone.value.trim()) {
			phone.focus();
			return;
		}

		if (iti && typeof iti.isValidNumber === 'function' && !iti.isValidNumber()) {
			phone.focus();
			showError(form, 'Please enter a valid phone number.');
			return;
		}

		if (!message || !message.value.trim()) {
			message.focus();
			return;
		}

		var fullPhone = phone.value.trim();
		var dialCode = '';

		if (iti) {
			if (typeof iti.getNumber === 'function') {
				fullPhone = iti.getNumber() || fullPhone;
			}

			if (typeof iti.getSelectedCountryData === 'function') {
				var country = iti.getSelectedCountryData();
				dialCode = country && country.dialCode ? '+' + country.dialCode : '';
			}
		}

		setSubmitting(form, true);

		var body = new FormData();
		body.append('action', config.action || 'sln_contact_submit_lead');
		body.append('nonce', config.nonce || '');
		body.append('name', name.value.trim());
		body.append('email', email.value.trim());
		body.append('country_code', dialCode);
		body.append('phone', fullPhone);
		body.append('website', website ? website.value.trim() : '');
		body.append('message', message.value.trim());

		fetch(config.ajaxUrl || form.action, {
			method: 'POST',
			body: body,
			credentials: 'same-origin',
		})
			.then(function (response) {
				return response.json().then(function (data) {
					return { ok: response.ok, data: data };
				});
			})
			.then(function (result) {
				if (result.data && result.data.success) {
					window.location.href = result.data.redirect_url || config.thankYouUrl || '/thank-you/';
					return;
				}

				setSubmitting(form, false);
				showError(form, result.data && result.data.message ? result.data.message : '');
			})
			.catch(function () {
				setSubmitting(form, false);
				showError(form, '');
			});
	}

	function initContactForm() {
		var form = document.getElementById('contact-page-form');

		if (!form) {
			return;
		}

		iti = initPhone(form.querySelector('[name="contact_phone"]'));
		form.addEventListener('submit', handleSubmit);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initContactForm);
	} else {
		initContactForm();
	}
})();
