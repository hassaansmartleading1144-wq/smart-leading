/**
 * Smart Leading Net — SEO Services Page interactions
 */
(function () {
	'use strict';

	function initReveal() {
		var items = document.querySelectorAll('.seo-page__reveal');

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
		var items = document.querySelectorAll('.seo-page__faq-item');

		items.forEach(function (item) {
			var button = item.querySelector('.seo-page__faq-q');
			var answer = item.querySelector('.seo-page__faq-a');

			if (!button || !answer) {
				return;
			}

			button.addEventListener('click', function () {
				var isOpen = item.classList.contains('is-open');

				items.forEach(function (other) {
					other.classList.remove('is-open');
					var otherAnswer = other.querySelector('.seo-page__faq-a');
					var otherButton = other.querySelector('.seo-page__faq-q');

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

	function initSerpAnimation() {
		if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
			return;
		}

		var panel = document.querySelector('.seo-page__serp');
		var list = document.getElementById('seo-serp-list');
		var youRow = panel ? panel.querySelector('.seo-page__serp-row--you') : null;
		var youPos = document.getElementById('seo-you-pos');
		var rankNum = document.getElementById('seo-rank-num');
		var traffic = document.getElementById('seo-traffic-num');

		if (!panel || !list || !youRow || !youPos || !rankNum || !traffic) {
			return;
		}

		var positions = [];
		var trafficValues = [];

		try {
			positions = JSON.parse(panel.getAttribute('data-positions') || '[]');
			trafficValues = JSON.parse(panel.getAttribute('data-traffic') || '[]');
		} catch (error) {
			positions = [9, 6, 4, 2, 1];
			trafficValues = ['+12%', '+58%', '+140%', '+255%', '+312%'];
		}

		if (!positions.length) {
			return;
		}

		var index = 0;

		function relabelRows() {
			var rank = 1;

			Array.prototype.forEach.call(list.children, function (row) {
				if (row.classList.contains('seo-page__serp-row--you')) {
					rank++;
					return;
				}

				var posEl = row.querySelector('.seo-page__serp-pos');

				if (posEl) {
					posEl.textContent = String(rank);
					rank++;
				}
			});
		}

		function step() {
			var position = positions[index];
			var trafficValue = trafficValues[index] || trafficValues[trafficValues.length - 1];

			youPos.textContent = String(position);
			rankNum.textContent = String(position);
			traffic.textContent = trafficValue;

			var target = Math.min(position - 1, list.children.length - 1);
			var current = Array.prototype.indexOf.call(list.children, youRow);

			if (target < current) {
				list.insertBefore(youRow, list.children[target]);
			} else if (target > current) {
				list.insertBefore(youRow, list.children[target + 1] || null);
			}

			relabelRows();

			index = (index + 1) % positions.length;
			window.setTimeout(step, index === 0 ? 2600 : 1500);
		}

		window.setTimeout(step, 1200);
	}

	function getFormConfig() {
		return window.slnSeoForm || {};
	}

	function initSeoForm() {
		var form = document.getElementById('seo-page-form');
		var card = form ? form.closest('.seo-page__lead-card') : null;

		if (!form || !card) {
			return;
		}

		var config = getFormConfig();
		var submitButton = form.querySelector('button[type="submit"]');
		var submitText = submitButton ? submitButton.querySelector('.sls-btn__text') : null;
		var defaultLabel = submitText ? submitText.textContent : '';
		var messageEl = form.querySelector('.seo-page__form-message');
		var successBox = card.querySelector('.seo-page__success-box');
		var note = card.querySelector('.seo-page__lead-note');

		function hideMessage() {
			if (!messageEl) {
				return;
			}

			messageEl.hidden = true;
			messageEl.textContent = '';
			messageEl.classList.remove('is-visible', 'is-error');
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
			messageEl.classList.add('is-error', 'is-visible');
		}

		function showSuccess(data) {
			var scrollY = window.scrollY;

			hideMessage();
			form.reset();

			if (submitButton) {
				submitButton.blur();
			}

			form.classList.add('is-hidden');

			if (note) {
				note.hidden = true;
			}

			if (!successBox) {
				return;
			}

			var titleEl = successBox.querySelector('.seo-page__success-title');
			var textEls = successBox.querySelectorAll('.seo-page__success-text');

			if (titleEl && data.title) {
				titleEl.textContent = data.title;
			}

			if (textEls.length) {
				if (data.message && textEls[0]) {
					textEls[0].textContent = data.message;
				}

				if (data.message_2 && textEls[1]) {
					textEls[1].textContent = data.message_2;
				}
			}

			successBox.hidden = false;
			successBox.setAttribute('tabindex', '-1');

			window.requestAnimationFrame(function () {
				window.scrollTo(0, scrollY);
				successBox.focus({ preventScroll: true });
				successBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
			});
		}

		form.addEventListener('submit', function (event) {
			event.preventDefault();
			hideMessage();

			if (!config.ajaxUrl || !config.action || !config.nonce) {
				showError(config.errorMessage || 'Something went wrong. Please try again.');
				return;
			}

			var nameInput = form.querySelector('[name="seo_name"]');
			var emailInput = form.querySelector('[name="seo_email"]');
			var websiteInput = form.querySelector('[name="seo_website"]');
			var name = nameInput ? nameInput.value.trim() : '';
			var email = emailInput ? emailInput.value.trim() : '';
			var website = websiteInput ? websiteInput.value.trim() : '';

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
						showSuccess(data);
						return;
					}

					showError(data.message || config.errorMessage || 'Something went wrong. Please try again.');
				})
				.catch(function () {
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
		initReveal();
		initFaq();
		initSerpAnimation();
		initSeoForm();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
