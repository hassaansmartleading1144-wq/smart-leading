/**
 * Smart Leading Net — Hero Banner interactions
 */
(function () {
	'use strict';

	function getContactUrl() {
		var config = window.slnHeroBanner || {};
		return config.contactUrl || '/contact-us/';
	}

	function buildRedirectUrl(website) {
		var base = getContactUrl();
		var trimmed = website ? String(website).trim() : '';

		if (!trimmed) {
			return base;
		}

		var separator = base.indexOf('?') === -1 ? '?' : '&';
		return base + separator + 'website=' + encodeURIComponent(trimmed);
	}

	function initHeroCtaForm(hero) {
		var form = hero.querySelector('.hero-banner__cta-form');

		if (!form) {
			return;
		}

		var input = form.querySelector('.hero-banner__cta-input');

		form.addEventListener('submit', function (event) {
			event.preventDefault();

			var website = input ? input.value.trim() : '';
			window.location.href = buildRedirectUrl(website);
		});
	}

	function initHeroBanner() {
		var hero = document.querySelector('.hero-banner');

		if (!hero) {
			return;
		}

		var progressFill = hero.querySelector('.hero-banner__progress-fill');
		var progressValue = hero.querySelector('.hero-banner__progress-value');

		if (progressFill && progressValue) {
			var target = parseInt(progressValue.getAttribute('data-progress-target'), 10) || 87;
			var circumference = 201.1;
			var endOffset = circumference * (1 - target / 100);

			progressFill.style.setProperty('--hero-progress-end', String(endOffset));
		}

		initHeroCtaForm(hero);

		window.requestAnimationFrame(function () {
			hero.classList.add('is-ready');

			if (progressValue && progressFill) {
				var targetValue = parseInt(progressValue.getAttribute('data-progress-target'), 10) || 87;
				var start = null;
				var duration = 850;

				function countProgress(timestamp) {
					if (!start) {
						start = timestamp;
					}

					var elapsed = timestamp - start;
					var progress = Math.min(elapsed / duration, 1);
					var current = Math.round(targetValue * progress);

					progressValue.textContent = current + '%';

					if (progress < 1) {
						window.requestAnimationFrame(countProgress);
					}
				}

				window.setTimeout(function () {
					window.requestAnimationFrame(countProgress);
				}, 950);
			}
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initHeroBanner);
	} else {
		initHeroBanner();
	}
})();
