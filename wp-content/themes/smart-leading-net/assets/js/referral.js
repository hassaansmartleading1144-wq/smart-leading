/**
 * Smart Leading Net — Referral Partners page: scroll reveal and the
 * partner application form (intl-tel-input + AJAX to GoHighLevel).
 */
(function () {
	'use strict';

	var config = window.slnReferralForm || {};
	var iti = null;

	document.documentElement.classList.add('rp-js');

	function initReveal() {
		var items = document.querySelectorAll('[data-rp-reveal]');

		if (!('IntersectionObserver' in window)) {
			items.forEach(function (el) {
				el.classList.add('is-revealed');
			});
			return;
		}

		var observer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-revealed');
					observer.unobserve(entry.target);
				}
			});
		}, { rootMargin: '0px 0px -10% 0px', threshold: 0.12 });

		items.forEach(function (el) {
			observer.observe(el);
		});
	}

	function showError(form, text) {
		var el = form.querySelector('.rp-form__message');
		if (!el) {
			return;
		}
		el.textContent = text || config.errorMessage || 'Sorry, something went wrong. Please try again.';
		el.hidden = false;
	}

	function hideError(form) {
		var el = form.querySelector('.rp-form__message');
		if (el) {
			el.hidden = true;
			el.textContent = '';
		}
	}

	function setSubmitting(form, busy) {
		var button = form.querySelector('.rp-submit');
		var label = button ? button.querySelector('.rp-submit__text') : null;
		if (!button || !label) {
			return;
		}
		if (!label.getAttribute('data-label')) {
			label.setAttribute('data-label', label.textContent);
		}
		button.disabled = busy;
		button.setAttribute('aria-busy', busy ? 'true' : 'false');
		label.textContent = busy ? (config.submittingLabel || 'Sending...') : label.getAttribute('data-label');
	}

	function handleSubmit(event) {
		event.preventDefault();

		var form = event.currentTarget;
		var name = form.querySelector('[name="rp_name"]');
		var email = form.querySelector('[name="rp_email"]');
		var phone = form.querySelector('[name="rp_phone"]');
		var website = form.querySelector('[name="rp_website"]');
		var message = form.querySelector('[name="rp_message"]');
		var type = form.querySelector('[name="rp_type"]:checked');

		hideError(form);

		if (!name.value.trim()) {
			name.focus();
			return;
		}

		if (!email.value.trim() || !email.checkValidity()) {
			email.focus();
			return;
		}

		if (!phone.value.trim()) {
			phone.focus();
			return;
		}

		if (iti && typeof iti.isValidNumber === 'function' && !iti.isValidNumber()) {
			phone.focus();
			showError(form, config.phoneMessage);
			return;
		}

		if (!type) {
			var firstType = form.querySelector('[name="rp_type"]');
			if (firstType) {
				firstType.focus();
			}
			showError(form, 'Please tell us who you are.');
			return;
		}

		var fullPhone = phone.value.trim();
		if (iti && typeof iti.getNumber === 'function') {
			fullPhone = iti.getNumber() || fullPhone;
		}

		setSubmitting(form, true);

		var body = new FormData();
		body.append('action', config.action || 'sln_referral_submit_lead');
		body.append('nonce', config.nonce || '');
		body.append('name', name.value.trim());
		body.append('email', email.value.trim());
		body.append('phone', fullPhone);
		body.append('website', website ? website.value.trim() : '');
		body.append('partner_type', type.value);
		body.append('message', message ? message.value.trim() : '');

		fetch(config.ajaxUrl || form.action, {
			method: 'POST',
			body: body,
			credentials: 'same-origin'
		})
			.then(function (response) {
				return response.json();
			})
			.then(function (data) {
				if (data && data.success) {
					window.location.href = data.redirect_url || config.thankYouUrl || '/thank-you/';
					return;
				}
				setSubmitting(form, false);
				showError(form, data && data.message ? data.message : '');
			})
			.catch(function () {
				setSubmitting(form, false);
				showError(form, '');
			});
	}

	function initForm() {
		var form = document.getElementById('rp-form');
		if (!form) {
			return;
		}

		var phone = form.querySelector('[name="rp_phone"]');
		if (phone && typeof window.intlTelInput === 'function') {
			iti = window.intlTelInput(phone, {
				initialCountry: 'us',
				separateDialCode: true,
				preferredCountries: ['us', 'au', 'gb', 'ca'],
				utilsScript: config.utilsScript || undefined
			});
		}

		form.addEventListener('submit', handleSubmit);
	}

	function init() {
		initReveal();
		initForm();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
