/**
 * Smart Leading Net — Testimonials counter animation
 */
(function () {
	'use strict';

	function easeOutCubic(t) {
		return 1 - Math.pow(1 - t, 3);
	}

	function formatCounterValue(value, prefix, suffix, decimals) {
		var formatted = decimals > 0 ? value.toFixed(decimals) : String(Math.round(value));

		return prefix + formatted + suffix;
	}

	function animateCounter(element) {
		if (element.dataset.counterAnimated === 'true') {
			return;
		}

		element.dataset.counterAnimated = 'true';

		var target = parseFloat(element.getAttribute('data-counter-value'));
		var prefix = element.getAttribute('data-counter-prefix') || '';
		var suffix = element.getAttribute('data-counter-suffix') || '';
		var decimals = parseInt(element.getAttribute('data-counter-decimals') || '0', 10);
		var duration = 1600;
		var start = null;

		if (Number.isNaN(target)) {
			return;
		}

		if (Number.isNaN(decimals) || decimals < 0) {
			decimals = 0;
		}

		function step(timestamp) {
			if (!start) {
				start = timestamp;
			}

			var progress = Math.min((timestamp - start) / duration, 1);
			var current = target * easeOutCubic(progress);

			element.textContent = formatCounterValue(current, prefix, suffix, decimals);

			if (progress < 1) {
				window.requestAnimationFrame(step);
			} else {
				element.textContent = formatCounterValue(target, prefix, suffix, decimals);
			}
		}

		window.requestAnimationFrame(step);
	}

	function initTestimonials() {
		var section = document.querySelector('.testimonials');

		if (!section) {
			return;
		}

		var counters = section.querySelectorAll('.testimonials__stat-number[data-counter-value]');

		if (!counters.length) {
			return;
		}

		if (!('IntersectionObserver' in window)) {
			counters.forEach(animateCounter);
			return;
		}

		var observer = new IntersectionObserver(
			function (entries, obs) {
				entries.forEach(function (entry) {
					if (!entry.isIntersecting) {
						return;
					}

					animateCounter(entry.target);
					obs.unobserve(entry.target);
				});
			},
			{
				threshold: 0.35,
				rootMargin: '0px 0px -10% 0px',
			}
		);

		counters.forEach(function (counter) {
			observer.observe(counter);
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initTestimonials);
	} else {
		initTestimonials();
	}
})();
