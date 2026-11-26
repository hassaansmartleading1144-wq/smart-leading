/**
 * Smart Leading Net — Homepage sections: hero slides and dashboard,
 * sliders, scroll reveal and counters.
 */
(function () {
	'use strict';

	var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	document.documentElement.classList.add('sls-home-js');

	/* Hero dashboard: one view per slide ----------------------------------- */
	function dashCount(el) {
		var target = parseInt(el.getAttribute('data-dash-count'), 10);
		var start = null;
		var duration = 1200;

		if (isNaN(target)) {
			return;
		}

		if (reduceMotion) {
			el.textContent = target.toLocaleString('en-US');
			return;
		}

		function step(ts) {
			if (!start) {
				start = ts;
			}
			var p = Math.min((ts - start) / duration, 1);
			el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3))).toLocaleString('en-US');
			if (p < 1) {
				window.requestAnimationFrame(step);
			}
		}

		el.textContent = '0';
		window.setTimeout(function () {
			window.requestAnimationFrame(step);
		}, 350);
	}

	function syncDashboard(scope, index) {
		scope.querySelectorAll('[data-dash-view]').forEach(function (view) {
			var active = parseInt(view.getAttribute('data-dash-view'), 10) === index;
			view.classList.toggle('is-active', active);
			if (active) {
				view.querySelectorAll('[data-dash-count]').forEach(dashCount);
			}
		});

		scope.querySelectorAll('[data-dash-nav]').forEach(function (nav) {
			nav.classList.toggle('is-active', parseInt(nav.getAttribute('data-dash-nav'), 10) === index);
		});
	}

	/* Hero slides ---------------------------------------------------------- */
	function initHero() {
		var hero = document.querySelector('[data-home-hero]');
		if (!hero) {
			return;
		}

		var slides = Array.prototype.slice.call(hero.querySelectorAll('[data-home-slide]'));
		var dots = Array.prototype.slice.call(hero.querySelectorAll('[data-home-dot]'));
		var interval = 7000;
		var current = 0;
		var timer = null;
		var paused = false;

		syncDashboard(hero, 0);

		if (slides.length < 2) {
			return;
		}

		hero.style.setProperty('--home-hero-interval', interval + 'ms');

		function show(index) {
			current = (index + slides.length) % slides.length;
			hero.classList.add('has-rotated');

			slides.forEach(function (slide, i) {
				var active = i === current;
				slide.classList.toggle('is-active', active);
				if (active) {
					slide.removeAttribute('aria-hidden');
					slide.removeAttribute('inert');
				} else {
					slide.setAttribute('aria-hidden', 'true');
					slide.setAttribute('inert', '');
				}
			});

			syncDashboard(hero, current);

			dots.forEach(function (dot, i) {
				var active = i === current;
				dot.classList.toggle('is-active', active);
				dot.setAttribute('aria-selected', active ? 'true' : 'false');
				// Restart the progress animation.
				var fill = dot.querySelector('.home-hero__dot-fill');
				if (fill && active) {
					fill.style.animation = 'none';
					void fill.offsetWidth;
					fill.style.animation = '';
				}
			});
		}

		function stop() {
			window.clearInterval(timer);
			timer = null;
		}

		function start() {
			if (reduceMotion) {
				return;
			}
			stop();
			hero.classList.add('is-autoplay');
			timer = window.setInterval(function () {
				if (!paused && !document.hidden) {
					show(current + 1);
				}
			}, interval);
		}

		dots.forEach(function (dot, i) {
			dot.addEventListener('click', function () {
				show(i);
				start();
			});
		});

		function pause() {
			paused = true;
			hero.classList.add('is-paused');
		}

		function resume() {
			paused = false;
			hero.classList.remove('is-paused');
		}

		var content = hero.querySelector('.hero-banner__content');
		if (content) {
			content.addEventListener('mouseenter', pause);
			content.addEventListener('mouseleave', resume);
			content.addEventListener('focusin', pause);
			content.addEventListener('focusout', resume);
		}

		start();
	}

	/* Service tabs --------------------------------------------------------- */
	function initTabs() {
		document.querySelectorAll('[data-home-tabs]').forEach(function (section) {
			var tabs = Array.prototype.slice.call(section.querySelectorAll('[role="tab"]'));

			function activate(tab, focus) {
				tabs.forEach(function (t) {
					var active = t === tab;
					var panel = document.getElementById(t.getAttribute('aria-controls'));
					t.classList.toggle('is-active', active);
					t.setAttribute('aria-selected', active ? 'true' : 'false');
					t.setAttribute('tabindex', active ? '0' : '-1');
					if (panel) {
						panel.hidden = !active;
						panel.classList.toggle('is-active', active);
					}
				});

				if (focus) {
					tab.focus();
				}

				if (tab.scrollIntoView && window.matchMedia('(max-width: 991.98px)').matches) {
					tab.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', inline: 'center', block: 'nearest' });
				}
			}

			tabs.forEach(function (tab, i) {
				tab.addEventListener('click', function () {
					activate(tab, false);
				});

				tab.addEventListener('keydown', function (event) {
					var next = null;
					if (event.key === 'ArrowDown' || event.key === 'ArrowRight') {
						next = tabs[(i + 1) % tabs.length];
					} else if (event.key === 'ArrowUp' || event.key === 'ArrowLeft') {
						next = tabs[(i - 1 + tabs.length) % tabs.length];
					} else if (event.key === 'Home') {
						next = tabs[0];
					} else if (event.key === 'End') {
						next = tabs[tabs.length - 1];
					}
					if (next) {
						event.preventDefault();
						activate(next, true);
					}
				});
			});
		});
	}

	/* Counter ---------------------------------------------------------------- */
	function countUp(el) {
		var target = parseInt(el.getAttribute('data-home-count'), 10);
		var suffix = el.getAttribute('data-home-suffix') || '';
		var start = null;
		var duration = 1400;

		if (isNaN(target) || reduceMotion) {
			return;
		}

		function step(ts) {
			if (!start) {
				start = ts;
			}
			var p = Math.min((ts - start) / duration, 1);
			el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3))) + suffix;
			if (p < 1) {
				window.requestAnimationFrame(step);
			}
		}

		el.textContent = '0' + suffix;
		window.requestAnimationFrame(step);
	}

	/* Scroll reveal ------------------------------------------------------------ */
	function initReveal() {
		var items = document.querySelectorAll('[data-home-reveal], [data-home-count]');

		if (!('IntersectionObserver' in window)) {
			items.forEach(function (el) {
				el.classList.add('is-revealed');
			});
			return;
		}

		var observer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) {
					return;
				}
				var el = entry.target;
				el.classList.add('is-revealed');
				if (el.hasAttribute('data-home-count')) {
					countUp(el);
				}
				observer.unobserve(el);
			});
		}, { rootMargin: '0px 0px -10% 0px', threshold: 0.15 });

		items.forEach(function (el) {
			observer.observe(el);
		});
	}

	/* Partner slider (mobile: two badges on screen) ------------------------ */
	function initPartners() {
		var section = document.querySelector('[data-home-partners]');
		if (!section) {
			return;
		}

		var list = section.querySelector('.home-partners__list');
		var items = Array.prototype.slice.call(list.children);
		var dots = Array.prototype.slice.call(section.querySelectorAll('.home-partners__dot'));
		var mobile = window.matchMedia('(max-width: 767.98px)');
		var timer = null;
		var userBusy = false;

		function perView() {
			return 2;
		}

		function positions() {
			return Math.max(1, items.length - perView() + 1);
		}

		function currentIndex() {
			var step = items[1] ? items[1].offsetLeft - items[0].offsetLeft : list.clientWidth;
			return Math.round(list.scrollLeft / step);
		}

		function updateDots() {
			var count = positions();
			var index = Math.min(currentIndex(), count - 1);
			dots.forEach(function (dot, i) {
				dot.hidden = i >= count;
				dot.classList.toggle('is-active', i === index);
			});
		}

		function goTo(index) {
			var target = items[index];
			if (target) {
				list.scrollTo({ left: target.offsetLeft - items[0].offsetLeft, behavior: reduceMotion ? 'auto' : 'smooth' });
			}
		}

		function stop() {
			window.clearInterval(timer);
			timer = null;
		}

		function start() {
			stop();
			if (!mobile.matches || reduceMotion) {
				return;
			}
			timer = window.setInterval(function () {
				if (userBusy || document.hidden) {
					return;
				}
				var next = currentIndex() + 1;
				goTo(next >= positions() ? 0 : next);
			}, 3000);
		}

		list.addEventListener('scroll', function () {
			window.requestAnimationFrame(updateDots);
		}, { passive: true });

		list.addEventListener('touchstart', function () {
			userBusy = true;
		}, { passive: true });

		list.addEventListener('touchend', function () {
			window.setTimeout(function () {
				userBusy = false;
			}, 4000);
		}, { passive: true });

		if (mobile.addEventListener) {
			mobile.addEventListener('change', function () {
				updateDots();
				start();
			});
		}

		updateDots();
		start();
	}

	/* Services slider (mobile) --------------------------------------------- */
	function initServiceSlider() {
		var section = document.querySelector('[data-home-slider]');
		if (!section) {
			return;
		}

		var list = section.querySelector('.home-services__grid');
		var items = Array.prototype.slice.call(list.children);
		var nav = section.querySelector('[data-slider-nav]');
		var dotsWrap = section.querySelector('[data-slider-dots]');
		var prev = section.querySelector('[data-slider-prev]');
		var next = section.querySelector('[data-slider-next]');
		var mobile = window.matchMedia('(max-width: 767.98px)');
		var dots = [];

		if (!list || !nav || items.length < 2) {
			return;
		}

		function offset(i) {
			return items[i].offsetLeft - items[0].offsetLeft;
		}

		function currentIndex() {
			var best = 0;
			var bestDist = Infinity;
			var maxScroll = list.scrollWidth - list.clientWidth;
			items.forEach(function (item, i) {
				var dist = Math.abs(Math.min(offset(i), maxScroll) - list.scrollLeft);
				if (dist < bestDist - 1) {
					best = i;
					bestDist = dist;
				}
			});
			return best;
		}

		function goTo(i) {
			i = Math.max(0, Math.min(items.length - 1, i));
			list.scrollTo({ left: offset(i), behavior: reduceMotion ? 'auto' : 'smooth' });
		}

		function update() {
			var index = currentIndex();
			dots.forEach(function (dot, i) {
				var active = i === index;
				dot.classList.toggle('is-active', active);
				dot.setAttribute('aria-current', active ? 'true' : 'false');
			});
			prev.disabled = index === 0;
			next.disabled = index === items.length - 1;
		}

		items.forEach(function (item, i) {
			var dot = document.createElement('button');
			var title = item.querySelector('.home-services__title');
			dot.type = 'button';
			dot.className = 'home-services__dot';
			dot.setAttribute('aria-label', 'Show ' + (title ? title.textContent : 'service ' + (i + 1)));
			dot.addEventListener('click', function () {
				goTo(i);
			});
			dotsWrap.appendChild(dot);
			dots.push(dot);
		});

		prev.addEventListener('click', function () {
			goTo(currentIndex() - 1);
		});

		next.addEventListener('click', function () {
			goTo(currentIndex() + 1);
		});

		list.addEventListener('scroll', function () {
			window.requestAnimationFrame(update);
		}, { passive: true });

		function sync() {
			nav.hidden = !mobile.matches;
			update();
		}

		if (mobile.addEventListener) {
			mobile.addEventListener('change', sync);
		}

		sync();
	}

	function init() {
		initHero();
		initPartners();
		initServiceSlider();
		initTabs();
		initReveal();
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
