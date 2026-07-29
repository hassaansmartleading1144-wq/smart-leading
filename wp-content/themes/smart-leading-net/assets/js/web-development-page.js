/**
 * Web Development page — Figma layout interactions.
 */
(function () {
	'use strict';

	var page = document.querySelector('.web-development-page');

	if (!page) {
		return;
	}

	var motionOk = !window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	if (motionOk) {
		page.classList.add('web-development-motion');
	}

	function observeOnce(elements, className, threshold) {
		if (!elements.length) {
			return;
		}

		if (!('IntersectionObserver' in window)) {
			elements.forEach(function (el) {
				el.classList.add(className);
			});
			return;
		}

		var observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (!entry.isIntersecting) {
						return;
					}
					entry.target.classList.add(className);
					observer.unobserve(entry.target);
				});
			},
			{ threshold: threshold, rootMargin: '0px 0px -8% 0px' }
		);

		elements.forEach(function (el) {
			observer.observe(el);
		});
	}

	function initAnimate() {
		var items = page.querySelectorAll('.wd-animate');
		if (!motionOk) {
			items.forEach(function (el) {
				el.classList.add('is-in');
			});
			return;
		}
		observeOnce(items, 'is-in', 0.14);
	}

	function initFaq() {
		var list = page.querySelector('[data-wd-faq]');
		if (!list) {
			return;
		}

		var items = Array.prototype.slice.call(list.querySelectorAll('.wd-faq__item'));

		function setOpen(item, open) {
			var button = item.querySelector('.wd-faq__button');
			var panel = item.querySelector('.wd-faq__panel');
			if (!button || !panel) {
				return;
			}
			button.setAttribute('aria-expanded', open ? 'true' : 'false');
			panel.hidden = !open;
			item.classList.toggle('is-open', open);
		}

		function closeOthers(except) {
			items.forEach(function (item) {
				if (item !== except) {
					setOpen(item, false);
				}
			});
		}

		items.forEach(function (item) {
			var button = item.querySelector('.wd-faq__button');
			if (!button) {
				return;
			}

			button.addEventListener('click', function () {
				var isOpen = button.getAttribute('aria-expanded') === 'true';
				closeOthers(item);
				setOpen(item, !isOpen);
			});

			button.addEventListener('keydown', function (event) {
				var index = items.indexOf(item);
				var next = null;
				if (event.key === 'ArrowDown') {
					event.preventDefault();
					next = items[Math.min(index + 1, items.length - 1)];
				} else if (event.key === 'ArrowUp') {
					event.preventDefault();
					next = items[Math.max(index - 1, 0)];
				} else if (event.key === 'Home') {
					event.preventDefault();
					next = items[0];
				} else if (event.key === 'End') {
					event.preventDefault();
					next = items[items.length - 1];
				}
				if (next) {
					var nextButton = next.querySelector('.wd-faq__button');
					if (nextButton) {
						nextButton.focus();
					}
				}
			});
		});
	}

	function initForm() {
		var form = document.getElementById('wd-page-form');
		var card = form ? form.closest('.wd-contact__form') : null;
		if (!form || !card) {
			return;
		}

		var config = window.slnWebDevForm || {};
		var submitButton = form.querySelector('button[type="submit"]');
		var submitText = submitButton ? submitButton.querySelector('.sls-btn__text') : null;
		var defaultLabel = submitText ? submitText.textContent : '';
		var messageEl = form.querySelector('.wd-form__message');
		var successBox = card.querySelector('.wd-form__success');
		var note = card.querySelector('.wd-contact__form-note');
		var isSubmitting = false;

		function hideMessage() {
			if (!messageEl) {
				return;
			}
			messageEl.hidden = true;
			messageEl.textContent = '';
		}

		function showError(message) {
			if (successBox) {
				successBox.hidden = true;
			}
			if (!messageEl) {
				return;
			}
			messageEl.textContent = message;
			messageEl.hidden = false;
		}

		function showSuccess(data) {
			hideMessage();
			form.reset();
			form.classList.add('is-hidden');
			if (note) {
				note.hidden = true;
			}
			if (successBox) {
				successBox.hidden = false;
			}
			if (submitButton) {
				submitButton.disabled = true;
				submitButton.setAttribute('aria-busy', 'false');
			}
			var redirectUrl =
				(data && data.redirect_url) ||
				form.getAttribute('data-thank-you-url') ||
				'/thank-you/';
			window.setTimeout(function () {
				window.location.href = redirectUrl;
			}, 1200);
		}

		form.addEventListener('submit', function (event) {
			event.preventDefault();
			hideMessage();

			if (isSubmitting) {
				return;
			}

			if (!config.ajaxUrl || !config.action || !config.nonce) {
				showError(config.errorMessage || 'Something went wrong. Please try again.');
				return;
			}

			var nameInput = form.querySelector('[name="name"]');
			var emailInput = form.querySelector('[name="email"]');
			var messageInput = form.querySelector('[name="message"]');
			var name = nameInput ? nameInput.value.trim() : '';
			var email = emailInput ? emailInput.value.trim() : '';
			var message = messageInput ? messageInput.value.trim() : '';

			if (!name) {
				showError('Please enter your name.');
				if (nameInput) {
					nameInput.focus();
				}
				return;
			}
			if (!email || email.indexOf('@') === -1) {
				showError('Please enter a valid email address.');
				if (emailInput) {
					emailInput.focus();
				}
				return;
			}
			if (!message) {
				showError('Please tell us a bit about your project.');
				if (messageInput) {
					messageInput.focus();
				}
				return;
			}

			isSubmitting = true;
			if (submitButton) {
				submitButton.disabled = true;
				submitButton.setAttribute('aria-busy', 'true');
			}
			if (submitText) {
				submitText.textContent = config.sendingLabel || 'Sending…';
			}

			var body = new FormData();
			body.append('action', config.action);
			body.append('nonce', config.nonce);
			body.append('name', name);
			body.append('email', email);
			body.append('website', (form.querySelector('[name="website"]') || {}).value || '');
			body.append('country', (form.querySelector('[name="country"]') || {}).value || '');
			body.append('need', (form.querySelector('[name="need"]') || {}).value || '');
			body.append('budget', (form.querySelector('[name="budget"]') || {}).value || '');
			body.append('message', message);

			fetch(config.ajaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				body: body,
			})
				.then(function (response) {
					return response.json().then(function (data) {
						return { ok: response.ok, data: data };
					});
				})
				.then(function (result) {
					var data = result.data || {};
					if (data.success) {
						showSuccess(data.data || data);
						return;
					}
					isSubmitting = false;
					showError(
						data.message ||
							(data.data && data.data.message) ||
							config.errorMessage ||
							'Something went wrong. Please try again.'
					);
				})
				.catch(function () {
					isSubmitting = false;
					showError(config.errorMessage || 'Something went wrong. Please try again.');
				})
				.finally(function () {
					if (form.classList.contains('is-hidden')) {
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

	function init() {
		initAnimate();
		initFaq();
		initForm();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
