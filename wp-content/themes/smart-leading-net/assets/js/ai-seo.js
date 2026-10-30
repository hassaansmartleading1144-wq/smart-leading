/**
 * AI SEO Services page — lightweight interactions.
 * Mini lead forms scroll to enquiry; enquiry form validates and submits via AJAX.
 */
(function () {
	'use strict';

	var root = document.querySelector('.ai-seo-page');
	if (!root) {
		return;
	}

	var reduceMotion = false;
	try {
		reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	} catch (e) {
		reduceMotion = false;
	}

	function scrollToEnquiry(websiteValue) {
		var section = document.getElementById('ai-search-enquiry');
		var mainSite = document.getElementById('ai-enq-website');
		if (mainSite && websiteValue) {
			mainSite.value = websiteValue;
		}
		if (section) {
			section.scrollIntoView({
				behavior: reduceMotion ? 'auto' : 'smooth',
				block: 'start',
			});
		}
		var nameField = document.getElementById('ai-enq-name');
		if (nameField) {
			window.setTimeout(
				function () {
					nameField.focus({ preventScroll: true });
				},
				reduceMotion ? 0 : 450
			);
		}
	}

	var faqRoot = root.querySelector('.sls-faq');
	if (faqRoot) {
		faqRoot.querySelectorAll('details').forEach(function (item) {
			item.open = false;
		});
		faqRoot.addEventListener('click', function (event) {
			var summary = event.target.closest('summary');
			if (!summary || !faqRoot.contains(summary)) {
				return;
			}
			var item = summary.closest('details');
			faqRoot.querySelectorAll('details').forEach(function (other) {
				if (other !== item) {
					other.open = false;
				}
			});
		});
	}

	root.querySelectorAll('form.sls-mini').forEach(function (form) {
		form.addEventListener('submit', function (event) {
			event.preventDefault();
			var input = form.elements.namedItem('website');
			var value = input ? String(input.value || '').trim() : '';
			scrollToEnquiry(value);
		});
	});

	var enquiryForm = root.querySelector('form.sls-form[data-ai-seo-enquiry]');
	if (!enquiryForm) {
		return;
	}

	var statusEl = enquiryForm.querySelector('[data-ai-seo-status]');
	var cfg = window.slnAiSeoForm || {};

	function clearErrors() {
		enquiryForm.querySelectorAll('.sls-err').forEach(function (el) {
			el.hidden = true;
			el.textContent = '';
		});
		enquiryForm.querySelectorAll('.sls-input--error').forEach(function (el) {
			el.classList.remove('sls-input--error');
			el.removeAttribute('aria-invalid');
		});
		if (statusEl) {
			statusEl.hidden = true;
			statusEl.textContent = '';
		}
	}

	function showFieldError(name, message) {
		var field = enquiryForm.elements.namedItem(name);
		var err = enquiryForm.querySelector('#ai-enq-' + name + '-error');
		if (field) {
			field.classList.add('sls-input--error');
			field.setAttribute('aria-invalid', 'true');
		}
		if (err) {
			err.hidden = false;
			err.textContent = message;
		}
	}

	function getValue(name) {
		var el = enquiryForm.elements.namedItem(name);
		return el ? String(el.value || '').trim() : '';
	}

	enquiryForm.addEventListener('submit', function (event) {
		event.preventDefault();
		clearErrors();

		var errors = {};
		var name = getValue('name');
		var email = getValue('email');
		var website = getValue('website');
		var goal = getValue('goal');

		if (!name) {
			errors.name = cfg.nameRequired || 'Enter your name.';
		}
		if (!email) {
			errors.email = cfg.emailRequired || 'Enter your business email address.';
		} else if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email)) {
			errors.email = cfg.emailInvalid || 'Enter an email address in the format name@company.com.';
		}
		if (website && !/^(https?:\/\/)?[^\s/.]+(\.[^\s/.]+)+(\/\S*)?$/i.test(website)) {
			errors.website = cfg.websiteInvalid || 'Enter a website address such as yourcompany.com, or leave this field blank.';
		}
		if (!goal) {
			errors.goal = cfg.goalRequired || 'Tell us what you would like to understand about AI search.';
		}

		var order = ['name', 'email', 'website', 'goal'];
		var first = null;
		order.forEach(function (key) {
			if (errors[key] && !first) {
				first = key;
			}
			if (errors[key]) {
				showFieldError(key, errors[key]);
			}
		});

		if (first) {
			var target = enquiryForm.elements.namedItem(first);
			if (target && target.focus) {
				target.focus();
			}
			return;
		}

		var submitBtn = enquiryForm.querySelector('[type="submit"]');
		var originalLabel = submitBtn ? submitBtn.innerHTML : '';
		if (submitBtn) {
			submitBtn.disabled = true;
			submitBtn.setAttribute('aria-busy', 'true');
			if (cfg.sendingLabel) {
				submitBtn.textContent = cfg.sendingLabel;
			}
		}

		var body = new window.FormData();
		body.append('action', cfg.action || 'sln_ai_seo_submit_lead');
		body.append('nonce', cfg.nonce || '');
		body.append('name', name);
		body.append('email', email);
		body.append('website', website);
		body.append('goal', goal);

		window
			.fetch(cfg.ajaxUrl || '/wp-admin/admin-ajax.php', {
				method: 'POST',
				credentials: 'same-origin',
				body: body,
			})
			.then(function (res) {
				return res.json().then(function (data) {
					return { ok: res.ok, data: data };
				});
			})
			.then(function (result) {
				var data = result.data || {};
				if (statusEl) {
					statusEl.hidden = false;
					statusEl.textContent =
						data.message ||
						(result.ok && data.success
							? cfg.successMessage || 'Your enquiry has been sent.'
							: cfg.errorMessage || 'Something went wrong. Please try again.');
				}
				if (result.ok && data.success) {
					enquiryForm.reset();
					if (data.redirect) {
						window.location.href = data.redirect;
					}
				}
			})
			.catch(function () {
				if (statusEl) {
					statusEl.hidden = false;
					statusEl.textContent = cfg.errorMessage || 'Something went wrong. Please try again.';
				}
			})
			.finally(function () {
				if (submitBtn) {
					submitBtn.disabled = false;
					submitBtn.removeAttribute('aria-busy');
					submitBtn.innerHTML = originalLabel;
				}
			});
	});
})();
