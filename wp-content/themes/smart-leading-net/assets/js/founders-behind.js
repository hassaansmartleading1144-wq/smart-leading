/**
 * Founders page: entrance reveal + "show all courses" toggle.
 * Content is fully visible without this script.
 */
(function () {
	'use strict';

	var root = document.querySelector('.sls-founders-page');
	if (!root) {
		return;
	}

	// Collapse long course lists.
	root.querySelectorAll('[data-sfp-collapse]').forEach(function (list) {
		var limit = parseInt(list.getAttribute('data-sfp-collapse'), 10) || 6;
		var items = list.querySelectorAll('.sfp-course');
		if (items.length <= limit) {
			return;
		}
		items.forEach(function (item, i) {
			if (i >= limit) {
				item.classList.add('is-extra');
			}
		});
		list.classList.add('is-collapsed');
		if (!list.id) {
			list.id = 'sfp-courses-' + Math.random().toString(36).slice(2, 8);
		}

		var btn = document.createElement('button');
		btn.type = 'button';
		btn.className = 'sfp-courses__toggle';
		btn.setAttribute('aria-expanded', 'false');
		btn.setAttribute('aria-controls', list.id);
		var label = document.createElement('span');
		label.textContent = 'Show all ' + items.length + ' courses';
		btn.appendChild(label);
		btn.insertAdjacentHTML('beforeend', '<svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 6l4 4 4-4"/></svg>');
		btn.addEventListener('click', function () {
			var open = list.classList.toggle('is-collapsed') === false;
			btn.setAttribute('aria-expanded', open ? 'true' : 'false');
			label.textContent = open ? 'Show fewer' : 'Show all ' + items.length + ' courses';
			if (open) {
				items[limit].querySelector('.sfp-course__title').setAttribute('tabindex', '-1');
				items[limit].querySelector('.sfp-course__title').focus({ preventScroll: true });
			}
		});
		list.parentNode.insertBefore(btn, list.nextSibling);
	});

	// Entrance reveal.
	if (!('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}
	root.classList.add('sfp-motion');
	var io = new IntersectionObserver(function (entries) {
		entries.forEach(function (e) {
			if (e.isIntersecting) {
				e.target.classList.add('is-visible');
				io.unobserve(e.target);
			}
		});
	}, { rootMargin: '0px 0px -8% 0px', threshold: 0.1 });
	root.querySelectorAll('.sfp-reveal').forEach(function (el) {
		io.observe(el);
	});
})();
