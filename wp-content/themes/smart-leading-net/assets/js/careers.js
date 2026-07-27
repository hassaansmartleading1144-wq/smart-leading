/**
 * Smart Leading Net — Careers Page interactions
 */
(function () {
	'use strict';

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

	function initApplyForm() {
		var form = document.getElementById('careers-page-form');
		var card = form ? form.closest('.careers-page__apply-card') : null;

		if (!form || !card) {
			return;
		}

		var submitButton = form.querySelector('button[type="submit"]');
		var submitText = submitButton ? submitButton.querySelector('.sls-btn__text') : null;
		var defaultLabel = submitText ? submitText.textContent : '';
		var messageEl = form.querySelector('.careers-page__form-message');
		var successBox = card.querySelector('.careers-page__success-box');

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

		function showSuccess() {
			var scrollY = window.scrollY;

			hideMessage();
			form.reset();

			if (submitButton) {
				submitButton.blur();
			}

			form.classList.add('is-hidden');

			if (successBox) {
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

		form.addEventListener('submit', function (event) {
			event.preventDefault();
			hideMessage();

			var name = (form.querySelector('[name="careers_name"]') || {}).value || '';
			var email = (form.querySelector('[name="careers_email"]') || {}).value || '';
			var phone = (form.querySelector('[name="careers_phone"]') || {}).value || '';
			var position = (form.querySelector('[name="careers_position"]') || {}).value || '';
			var resume = form.querySelector('[name="careers_resume"]');
			var linkedin = (form.querySelector('[name="careers_linkedin"]') || {}).value || '';
			var portfolio = (form.querySelector('[name="careers_portfolio"]') || {}).value || '';

			name = name.trim();
			email = email.trim();
			phone = phone.trim();
			position = position.trim();
			linkedin = linkedin.trim();
			portfolio = portfolio.trim();

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
				submitText.textContent = 'Submitting…';
			}

			window.setTimeout(function () {
				showSuccess();

				if (!form.classList.contains('is-hidden') && submitButton) {
					submitButton.disabled = false;
					submitButton.removeAttribute('aria-busy');
				}

				if (!form.classList.contains('is-hidden') && submitText) {
					submitText.textContent = defaultLabel;
				}
			}, 450);
		});
	}

	function init() {
		initReveal();
		initFaq();
		initCounters();
		initTestimonials();
		initApplyForm();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
