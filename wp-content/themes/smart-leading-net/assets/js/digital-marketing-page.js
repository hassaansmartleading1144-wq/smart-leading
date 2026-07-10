/**
 * Smart Leading Net — Digital Marketing landing page interactions
 */
(function () {
	'use strict';

	function initReveal() {
		var items = document.querySelectorAll('.dm-page__reveal');

		if (!items.length) {
			return;
		}

		if (!('IntersectionObserver' in window)) {
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
			{ threshold: 0.16 }
		);

		items.forEach(function (el) {
			observer.observe(el);
		});
	}

	function initTimeline() {
		var timeline = document.querySelector('[data-dm-timeline]');

		if (!timeline) {
			return;
		}

		if (!('IntersectionObserver' in window)) {
			timeline.classList.add('is-visible');
			return;
		}

		var observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (!entry.isIntersecting) {
						return;
					}

					timeline.classList.add('is-visible');
					observer.disconnect();
				});
			},
			{ threshold: 0.25 }
		);

		observer.observe(timeline);
	}

	function initCountUp() {
		if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
			return;
		}

		var grids = document.querySelectorAll('[data-dm-countup]');

		if (!grids.length) {
			return;
		}

		var counted = new WeakSet();

		function animateCount(el) {
			var prefix = el.getAttribute('data-pre') || '';
			var suffix = el.getAttribute('data-suf') || '';
			var decimals = parseInt(el.getAttribute('data-dec') || '0', 10);
			var target = parseFloat(el.getAttribute('data-val') || '0');
			var duration = 1100;
			var start = performance.now();

			function tick(now) {
				var progress = Math.min(1, (now - start) / duration);
				var eased = 1 - Math.pow(1 - progress, 3);
				el.textContent = prefix + (target * eased).toFixed(decimals) + suffix;

				if (progress < 1) {
					requestAnimationFrame(tick);
				}
			}

			requestAnimationFrame(tick);
		}

		if (!('IntersectionObserver' in window)) {
			grids.forEach(function (grid) {
				grid.querySelectorAll('.dm-page__count').forEach(animateCount);
			});
			return;
		}

		var observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (!entry.isIntersecting || counted.has(entry.target)) {
						return;
					}

					counted.add(entry.target);
					entry.target.querySelectorAll('.dm-page__count').forEach(animateCount);
				});
			},
			{ threshold: 0.4 }
		);

		grids.forEach(function (grid) {
			observer.observe(grid);
		});
	}

	function init() {
		initReveal();
		initTimeline();
		initCountUp();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();