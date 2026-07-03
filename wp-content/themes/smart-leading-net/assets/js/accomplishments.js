/**
 * Smart Leading Net — Accomplishments counter animation
 */
(function () {
	'use strict';

	function easeOutCubic(t) {
		return 1 - Math.pow(1 - t, 3);
	}

	function formatCounterValue(value, prefix, suffix) {
		return prefix + Math.round(value) + suffix;
	}

	function animateCounter(element) {
		if (element.dataset.counterAnimated === 'true') {
			return;
		}

		element.dataset.counterAnimated = 'true';

		var target = parseFloat(element.getAttribute('data-counter-value'));
		var prefix = element.getAttribute('data-counter-prefix') || '';
		var suffix = element.getAttribute('data-counter-suffix') || '';
		var duration = 1600;
		var start = null;

		if (Number.isNaN(target)) {
			return;
		}

		function step(timestamp) {
			if (!start) {
				start = timestamp;
			}

			var progress = Math.min((timestamp - start) / duration, 1);
			var current = target * easeOutCubic(progress);

			element.textContent = formatCounterValue(current, prefix, suffix);

			if (progress < 1) {
				window.requestAnimationFrame(step);
			} else {
				element.textContent = formatCounterValue(target, prefix, suffix);
			}
		}

		window.requestAnimationFrame(step);
	}

	function initAccomplishments() {
		var section = document.querySelector('.accomplishments');

		if (!section) {
			return;
		}

		var counters = section.querySelectorAll('.accomplishments__number[data-counter-value]');

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
		document.addEventListener('DOMContentLoaded', initAccomplishments);
	} else {
		initAccomplishments();
	}
})();
