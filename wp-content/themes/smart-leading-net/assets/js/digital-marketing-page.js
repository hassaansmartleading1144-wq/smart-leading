/**
 * Smart Leading Net — Digital Marketing landing page interactions
 * Progressive enhancement only: content stays visible if JS fails.
 */
(function () {
	'use strict';

	var page = document.querySelector('.sln-dm-page');

	if (!page) {
		return;
	}

	var motionOk = !window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	if (motionOk) {
		page.classList.add('sln-dm-motion');
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
			{ threshold: threshold }
		);

		elements.forEach(function (el) {
			observer.observe(el);
		});
	}

	function initAnimate() {
		var items = page.querySelectorAll('.sln-dm-animate');

		if (!motionOk) {
			items.forEach(function (el) {
				el.classList.add('is-in');
			});
			return;
		}

		observeOnce(items, 'is-in', 0.16);
	}

	function initTimeline() {
		var timeline = page.querySelector('[data-sln-dm-timeline]');

		if (!timeline) {
			return;
		}

		if (!motionOk) {
			timeline.classList.add('is-in');
			return;
		}

		if (!('IntersectionObserver' in window)) {
			timeline.classList.add('is-in');
			return;
		}

		var observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (!entry.isIntersecting) {
						return;
					}

					timeline.classList.add('is-in');
					observer.disconnect();
				});
			},
			{ threshold: 0.25 }
		);

		observer.observe(timeline);
	}

	function initDashboard() {
		var dash = page.querySelector('[data-sln-dm-dash]');

		if (!dash) {
			return;
		}

		if (!motionOk) {
			dash.classList.add('is-in');
			return;
		}

		if (!('IntersectionObserver' in window)) {
			dash.classList.add('is-in');
			return;
		}

		var observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (!entry.isIntersecting) {
						return;
					}

					dash.classList.add('is-in');
					observer.disconnect();
				});
			},
			{ threshold: 0.3 }
		);

		observer.observe(dash);
	}

	function initQuoteGraph() {
		var quote = page.querySelector('[data-sln-dm-quote]');

		if (!quote) {
			return;
		}

		if (!motionOk) {
			quote.classList.add('is-in');
			return;
		}

		if (!('IntersectionObserver' in window)) {
			quote.classList.add('is-in');
			return;
		}

		var observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (!entry.isIntersecting) {
						return;
					}

					quote.classList.add('is-in');
					observer.disconnect();
				});
			},
			{ threshold: 0.25 }
		);

		observer.observe(quote);
	}

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

	function initCountUp() {
		if (!motionOk) {
			return;
		}

		var grids = page.querySelectorAll('[data-sln-dm-countup]');

		if (!grids.length) {
			return;
		}

		var counted = new WeakSet();

		if (!('IntersectionObserver' in window)) {
			grids.forEach(function (grid) {
				grid.querySelectorAll('.sln-dm-stat__count').forEach(animateCount);
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
					entry.target.querySelectorAll('.sln-dm-stat__count').forEach(animateCount);
				});
			},
			{ threshold: 0.4 }
		);

		grids.forEach(function (grid) {
			observer.observe(grid);
		});
	}

	function init() {
		initAnimate();
		initTimeline();
		initDashboard();
		initQuoteGraph();
		initCountUp();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
