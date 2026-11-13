/* Case study page: scroll reveals, number count-up, "On this page" highlight, website preview. */
(function () {
	'use strict';

	var root = document.querySelector('.sls-csd');
	if (!root) { return; }

	var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var hasIO = 'IntersectionObserver' in window;

	/* ---------- Scroll reveal ---------- */
	if (!reduce && hasIO) {
		root.classList.add('sls-csd--anim');

		// Stagger children inside groups.
		root.querySelectorAll('[data-reveal-group]').forEach(function (group) {
			Array.prototype.forEach.call(group.querySelectorAll('[data-reveal]'), function (el, i) {
				el.style.setProperty('--reveal-delay', Math.min(i * 110, 660) + 'ms');
			});
		});

		var revealIO = new IntersectionObserver(function (entries) {
			entries.forEach(function (e) {
				if (!e.isIntersecting) { return; }
				var el = e.target;
				el.classList.add('is-in');
				revealIO.unobserve(el);
				// Drop the stagger delay afterwards so hover effects respond instantly.
				setTimeout(function () { el.style.setProperty('--reveal-delay', '0ms'); }, 1600);
			});
		}, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

		root.querySelectorAll('[data-reveal]').forEach(function (el) { revealIO.observe(el); });
	}

	/* ---------- Count-up for result numbers ---------- */
	function parseCount(text) {
		var m = String(text).match(/^([^\d]*)([\d,]*\.?\d+)(.*)$/);
		if (!m) { return null; }
		var raw = m[2].replace(/,/g, '');
		return {
			prefix: m[1],
			value: parseFloat(raw),
			decimals: raw.indexOf('.') > -1 ? raw.split('.')[1].length : 0,
			comma: m[2].indexOf(',') > -1,
			suffix: m[3]
		};
	}

	function format(n, p) {
		var s = n.toFixed(p.decimals);
		if (p.comma) { s = Number(s).toLocaleString('en-US', { minimumFractionDigits: p.decimals, maximumFractionDigits: p.decimals }); }
		return p.prefix + s + p.suffix;
	}

	function countUp(el) {
		var p = parseCount(el.getAttribute('data-count'));
		if (!p || !isFinite(p.value)) { return; }
		var start = null;
		var dur = 1400;
		function tick(t) {
			if (!start) { start = t; }
			var k = Math.min(1, (t - start) / dur);
			var eased = 1 - Math.pow(1 - k, 3);
			el.textContent = format(p.value * eased, p);
			if (k < 1) { requestAnimationFrame(tick); } else { el.textContent = el.getAttribute('data-count'); }
		}
		requestAnimationFrame(tick);
	}

	if (!reduce && hasIO) {
		var counters = root.querySelectorAll('[data-count]');
		var countIO = new IntersectionObserver(function (entries) {
			entries.forEach(function (e) {
				if (!e.isIntersecting) { return; }
				countUp(e.target);
				countIO.unobserve(e.target);
			});
		}, { threshold: 0.6 });
		counters.forEach(function (el) {
			var p = parseCount(el.getAttribute('data-count'));
			if (p) { el.textContent = format(0, p); countIO.observe(el); }
		});
	}

	/* ---------- Website preview: scroll distance matches the screenshot ---------- */
	var project = root.querySelector('.sls-csd__project');
	if (project) {
		var view = project.querySelector('.sls-csd__project-view');
		var img = project.querySelector('.sls-csd__project-img');
		var setDistance = function () {
			if (!view || !img || !img.offsetHeight) { return; }
			var distance = Math.max(0, img.offsetHeight - view.offsetHeight);
			project.style.setProperty('--scroll-distance', '-' + distance + 'px');
			// ~ 220px per second feels like a calm read of the page.
			project.style.setProperty('--scroll-time', Math.max(8, Math.min(24, distance / 220)) + 's');
		};
		if (img) {
			if (img.complete) { setDistance(); } else { img.addEventListener('load', setDistance); }
			window.addEventListener('resize', setDistance);
		}
		// Touch screens have no hover: tap toggles the scroll instead of following the link the first time.
		if (window.matchMedia('(hover: none)').matches) {
			project.addEventListener('click', function (e) {
				if (!project.classList.contains('is-scrolling')) {
					e.preventDefault();
					project.classList.add('is-scrolling');
				}
			});
		}
	}

	/* ---------- Mobile sticky CTA: after the hero, hidden again at the final CTA ---------- */
	var sticky = root.querySelector('.sls-csd__sticky-cta');
	var hero = root.querySelector('.sls-csd__hero');
	var endCta = root.querySelector('.sls-csd__cta');
	if (sticky && hero && hasIO) {
		var pastHero = false;
		var atEnd = false;
		var setSticky = function () {
			var show = pastHero && !atEnd;
			sticky.classList.toggle('is-visible', show);
			sticky.setAttribute('aria-hidden', show ? 'false' : 'true');
			var btn = sticky.querySelector('a');
			if (btn) { btn.tabIndex = show ? 0 : -1; }
		};
		new IntersectionObserver(function (e) { pastHero = !e[0].isIntersecting && e[0].boundingClientRect.top < 0; setSticky(); }).observe(hero);
		if (endCta) {
			new IntersectionObserver(function (e) { atEnd = e[0].isIntersecting || e[0].boundingClientRect.top < 0; setSticky(); }).observe(endCta);
		}
	}

	/* ---------- "On this page" highlight ---------- */
	var links = Array.prototype.slice.call(root.querySelectorAll('.sls-csd__toc a'));
	if (!links.length || !hasIO) { return; }
	var map = {};
	links.forEach(function (a) { map[a.getAttribute('href').slice(1)] = a; });
	var tocIO = new IntersectionObserver(function (entries) {
		entries.forEach(function (e) {
			if (!e.isIntersecting) { return; }
			links.forEach(function (a) { a.removeAttribute('aria-current'); });
			if (map[e.target.id]) { map[e.target.id].setAttribute('aria-current', 'true'); }
		});
	}, { rootMargin: '-30% 0px -60% 0px' });
	Object.keys(map).forEach(function (id) {
		var el = document.getElementById(id);
		if (el) { tocIO.observe(el); }
	});
})();
