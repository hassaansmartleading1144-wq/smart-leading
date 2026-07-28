/**
 * Smart Leading Net — Careers Page interactions
 */
(function () {
	'use strict';

	function getConfig() {
		return window.slnCareersForm || {};
	}

	function initReveal() {
		var items = document.querySelectorAll('.careers-page__reveal');

		if (!items.length || !('IntersectionObserver' in window)) {
			items.forEach(function (el) {
				el.classList.add('is-visible');
			});
			return;
		}

		var observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (!entry.isIntersecting) {
						return;
					}

					entry.target.classList.add('is-visible');
					observer.unobserve(entry.target);
				});
			},
			{
				threshold: 0.12,
				rootMargin: '0px 0px -40px 0px',
			}
		);

		items.forEach(function (el, index) {
			el.style.transitionDelay = (index % 3) * 70 + 'ms';
			observer.observe(el);
		});
	}

	function initFaq() {
		var items = document.querySelectorAll('.careers-page__faq-item');

		items.forEach(function (item) {
			var button = item.querySelector('.careers-page__faq-q');
			var answer = item.querySelector('.careers-page__faq-a');

			if (!button || !answer) {
				return;
			}

			button.addEventListener('click', function () {
				var isOpen = item.classList.contains('is-open');

				items.forEach(function (other) {
					other.classList.remove('is-open');
					var otherAnswer = other.querySelector('.careers-page__faq-a');
					var otherButton = other.querySelector('.careers-page__faq-q');

					if (otherAnswer) {
						otherAnswer.style.maxHeight = null;
					}

					if (otherButton) {
						otherButton.setAttribute('aria-expanded', 'false');
					}
				});

				if (!isOpen) {
					item.classList.add('is-open');
					answer.style.maxHeight = answer.scrollHeight + 'px';
					button.setAttribute('aria-expanded', 'true');
				}
			});
		});
	}

	function initCounters() {
		var counters = document.querySelectorAll('[data-careers-counter]');

		if (!counters.length) {
			return;
		}

		function animateCounter(el) {
			var target = parseInt(el.getAttribute('data-value') || '0', 10);
			var suffix = el.getAttribute('data-suffix') || '';
			var duration = 1400;
			var start = null;

			function frame(timestamp) {
				if (!start) {
					start = timestamp;
				}

				var progress = Math.min((timestamp - start) / duration, 1);
				var eased = 1 - Math.pow(1 - progress, 3);
				var value = Math.round(target * eased);

				el.textContent = String(value) + suffix;

				if (progress < 1) {
					window.requestAnimationFrame(frame);
				}
			}

			window.requestAnimationFrame(frame);
		}

		if (!('IntersectionObserver' in window)) {
			counters.forEach(animateCounter);
			return;
		}

		var observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (!entry.isIntersecting) {
						return;
					}

					animateCounter(entry.target);
					observer.unobserve(entry.target);
				});
			},
			{ threshold: 0.4 }
		);

		counters.forEach(function (el) {
			observer.observe(el);
		});
	}

	function initTestimonials() {
		var root = document.querySelector('[data-careers-testi-slider]');

		if (!root) {
			return;
		}

		var slides = root.querySelectorAll('[data-careers-testi-slide]');
		var dots = root.querySelectorAll('.careers-page__testi-dot');
		var prev = root.querySelector('.careers-page__testi-prev');
		var next = root.querySelector('.careers-page__testi-next');
		var index = 0;

		if (slides.length < 2) {
			return;
		}

		function goTo(nextIndex) {
			index = (nextIndex + slides.length) % slides.length;

			slides.forEach(function (slide, i) {
				slide.classList.toggle('is-active', i === index);
			});

			dots.forEach(function (dot, i) {
				var active = i === index;
				dot.classList.toggle('is-active', active);
				dot.setAttribute('aria-selected', active ? 'true' : 'false');
			});
		}

		if (prev) {
			prev.addEventListener('click', function () {
				goTo(index - 1);
			});
		}

		if (next) {
			next.addEventListener('click', function () {
				goTo(index + 1);
			});
		}

		dots.forEach(function (dot) {
			dot.addEventListener('click', function () {
				var slideIndex = parseInt(dot.getAttribute('data-slide') || '0', 10);
				goTo(slideIndex);
			});
		});
	}

	function setPositionSelect(position) {
		var select = document.getElementById('careers-position');

		if (!select || !position) {
			return;
		}

		var options = Array.prototype.slice.call(select.options);
		var match = options.find(function (option) {
			return option.value === position;
		});

		if (match) {
			select.value = position;
		}
	}

	function initJobApplyLinks() {
		document.querySelectorAll('.careers-page__job-card').forEach(function (card) {
			var position = card.getAttribute('data-careers-position') || '';
			var link = card.querySelector('.careers-page__job-btn');

			if (!link || !position) {
				return;
			}

			link.addEventListener('click', function () {
				setPositionSelect(position);
			});
		});

		var params = new URLSearchParams(window.location.search);
		var queryPosition = params.get('position');

		if (queryPosition) {
			setPositionSelect(queryPosition);
		}

		if (window.location.hash === '#careers-apply' && queryPosition) {
			setPositionSelect(queryPosition);
		}
	}

	function initApplyForm() {
		var form = document.getElementById('careers-page-form');
		var card = form ? form.closest('.careers-page__apply-card') : null;
		var config = getConfig();

		if (!form || !card) {
			return;
		}

		var submitButton = form.querySelector('button[type="submit"]');
		var submitText = submitButton ? submitButton.querySelector('.sls-btn__text') : null;
		var defaultLabel = submitText ? submitText.textContent : '';
		var messageEl = form.querySelector('.careers-page__form-message');
		var successBox = card.querySelector('.careers-page__success-box');
		var successTitle = successBox ? successBox.querySelector('.careers-page__success-title') : null;
		var successTexts = successBox ? successBox.querySelectorAll('.careers-page__success-text') : [];

		function hideMessage() {
			if (!messageEl) {
				return;
			}

			messageEl.hidden = true;
			messageEl.textContent = '';
			messageEl.classList.remove('is-error');
		}

		function showError(message) {
			if (!messageEl) {
				return;
			}

			messageEl.textContent = message;
			messageEl.hidden = false;
			messageEl.classList.add('is-error');
		}

		function showSuccess(data) {
			var scrollY = window.scrollY;

			hideMessage();
			form.reset();

			if (submitButton) {
				submitButton.blur();
			}

			form.classList.add('is-hidden');

			if (successBox) {
				if (successTitle && data && data.title) {
					successTitle.textContent = data.title;
				}

				if (successTexts.length && data) {
					if (data.message) {
						successTexts[0].textContent = data.message;
					}

					if (successTexts[1] && data.message_2) {
						successTexts[1].textContent = data.message_2;
					}
				}

				successBox.hidden = false;
				successBox.setAttribute('tabindex', '-1');
			}

			window.requestAnimationFrame(function () {
				window.scrollTo(0, scrollY);

				if (successBox) {
					successBox.focus({ preventScroll: true });
					successBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
				}
			});
		}

		function isValidEmail(value) {
			return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
		}

		function isValidUrl(value) {
			if (!value) {
				return true;
			}

			try {
				var url = new URL(value);
				return url.protocol === 'http:' || url.protocol === 'https:';
			} catch (error) {
				return false;
			}
		}

		function resetSubmitState() {
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
		}

		form.addEventListener('submit', function (event) {
			event.preventDefault();
			hideMessage();

			if (!config.ajaxUrl || !config.action || !config.nonce) {
				showError(config.errorMessage || 'Something went wrong. Please try again.');
				return;
			}

			var name = (form.querySelector('[name="careers_name"]') || {}).value || '';
			var email = (form.querySelector('[name="careers_email"]') || {}).value || '';
			var phone = (form.querySelector('[name="careers_phone"]') || {}).value || '';
			var position = (form.querySelector('[name="careers_position"]') || {}).value || '';
			var resume = form.querySelector('[name="careers_resume"]');
			var linkedin = (form.querySelector('[name="careers_linkedin"]') || {}).value || '';
			var portfolio = (form.querySelector('[name="careers_portfolio"]') || {}).value || '';
			var message = (form.querySelector('[name="careers_message"]') || {}).value || '';

			name = name.trim();
			email = email.trim();
			phone = phone.trim();
			position = position.trim();
			linkedin = linkedin.trim();
			portfolio = portfolio.trim();
			message = message.trim();

			if (!name) {
				showError('Please enter your full name.');
				return;
			}

			if (!isValidEmail(email)) {
				showError('Please enter a valid email address.');
				return;
			}

			if (!phone || phone.replace(/\D/g, '').length < 7) {
				showError('Please enter a valid phone number.');
				return;
			}

			if (!position) {
				showError('Please select a position.');
				return;
			}

			if (!resume || !resume.files || !resume.files.length) {
				showError('Please upload your resume.');
				return;
			}

			var file = resume.files[0];
			var maxBytes = 5 * 1024 * 1024;
			var allowed = /\.(pdf|doc|docx)$/i;

			if (!allowed.test(file.name)) {
				showError('Resume must be a PDF or DOC/DOCX file.');
				return;
			}

			if (file.size > maxBytes) {
				showError('Resume must be 5MB or smaller.');
				return;
			}

			if (!isValidUrl(linkedin)) {
				showError('Please enter a valid LinkedIn URL.');
				return;
			}

			if (!isValidUrl(portfolio)) {
				showError('Please enter a valid portfolio URL.');
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
			body.append('name', name);
			body.append('email', email);
			body.append('phone', phone);
			body.append('position', position);
			body.append('linkedin', linkedin);
			body.append('portfolio', portfolio);
			body.append('message', message);
			body.append('resume', file);

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
						showSuccess(data);
						return;
					}

					showError(data.message || config.errorMessage || 'Something went wrong. Please try again.');
				})
				.catch(function () {
					showError(config.errorMessage || 'Something went wrong. Please try again.');
				})
				.finally(resetSubmitState);
		});
	}

	function init() {
		initReveal();
		initFaq();
		initCounters();
		initTestimonials();
		initJobApplyLinks();
		initApplyForm();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
