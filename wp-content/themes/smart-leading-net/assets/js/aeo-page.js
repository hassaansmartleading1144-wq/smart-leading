/**
 * Smart Leading Net — AEO Services page interactions
 * Progressive enhancement only: content stays visible if JS fails.
 */
(function () {
	'use strict';

	var page = document.querySelector('.sln-aeo-page');

	if (!page) {
		return;
	}

	var motionOk = !window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	if (motionOk) {
		page.classList.add('sln-aeo-motion');
	}

	function initReveal() {
		var items = page.querySelectorAll('.sln-aeo-reveal');

		if (!items.length) {
			return;
		}

		if (!motionOk || !('IntersectionObserver' in window)) {
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

		items.forEach(function (el) {
			observer.observe(el);
		});
	}

	function initFaq() {
		var items = page.querySelectorAll('.sln-aeo-faq__item');

		items.forEach(function (item) {
			var button = item.querySelector('.sln-aeo-faq__q');
			var answer = item.querySelector('.sln-aeo-faq__a');

			if (!button || !answer) {
				return;
			}

			if (item.classList.contains('is-open')) {
				answer.style.maxHeight = answer.scrollHeight + 'px';
			}

			button.addEventListener('click', function () {
				var isOpen = item.classList.contains('is-open');

				items.forEach(function (other) {
					other.classList.remove('is-open');
					var otherAnswer = other.querySelector('.sln-aeo-faq__a');
					var otherButton = other.querySelector('.sln-aeo-faq__q');

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

	function init() {
		initReveal();
		initFaq();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
