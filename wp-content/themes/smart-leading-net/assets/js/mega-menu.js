/**
 * Services mega menu and About Us dropdown (inc/mega-menu.php).
 * One panel open at a time.
 * Opens on click / Enter / Space, or on hover after a short delay (mouse
 * only). Closes on Esc (focus returns to the toggle), click outside, focus
 * leaving the menu, or the pointer leaving for a moment.
 */
(function () {
	'use strict';

	var OPEN_DELAY = 120;
	var CLOSE_DELAY = 280;
	// Each item's close function, so opening one closes the others.
	var closers = [];

	function init(item) {
		var toggle = item.querySelector('.sln-mega__toggle');
		var panel = item.querySelector('.sln-mega');
		var openTimer = null;
		var closeTimer = null;

		if (!toggle || !panel) {
			return;
		}

		function setOpen(open) {
			window.clearTimeout(openTimer);
			window.clearTimeout(closeTimer);
			if (open === item.classList.contains('is-open')) {
				return;
			}
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
			if (open) {
				var wasOpen = closers.some(function (close) {
					return close !== closeNow && close();
				});
				panel.hidden = false;
				// Switching between categories: no fade, the panel just swaps.
				item.classList.toggle('is-switch', wasOpen);
				// Apply the closed styles first so the open animation runs.
				void panel.offsetHeight;
				item.classList.add('is-open');
			} else {
				item.classList.remove('is-open');
				panel.hidden = true;
			}
		}

		// Close at once; returns whether it was open.
		function closeNow() {
			var was = item.classList.contains('is-open') || !panel.hidden;
			window.clearTimeout(openTimer);
			window.clearTimeout(closeTimer);
			if (was) {
				toggle.setAttribute('aria-expanded', 'false');
				item.classList.remove('is-open', 'is-switch');
				panel.hidden = true;
			}
			return was;
		}
		closers.push(closeNow);

		toggle.addEventListener('click', function () {
			setOpen(!item.classList.contains('is-open'));
		});

		item.addEventListener('pointerenter', function (event) {
			if (event.pointerType !== 'mouse') {
				return;
			}
			window.clearTimeout(closeTimer);
			// Moving across the bar while a panel is open: switch right away.
			var anyOpen = document.querySelector('[data-sln-mega].is-open');
			openTimer = window.setTimeout(function () {
				setOpen(true);
			}, anyOpen && anyOpen !== item ? 40 : OPEN_DELAY);
		});

		item.addEventListener('pointerleave', function (event) {
			if (event.pointerType !== 'mouse') {
				return;
			}
			window.clearTimeout(openTimer);
			closeTimer = window.setTimeout(function () {
				setOpen(false);
			}, CLOSE_DELAY);
		});

		item.addEventListener('keydown', function (event) {
			if (event.key === 'Escape' && item.classList.contains('is-open')) {
				setOpen(false);
				toggle.focus();
			}
		});

		item.addEventListener('focusout', function (event) {
			if (event.relatedTarget && !item.contains(event.relatedTarget)) {
				setOpen(false);
			}
		});

		document.addEventListener('click', function (event) {
			if (!item.contains(event.target)) {
				setOpen(false);
			}
		});
	}

	function start() {
		document.querySelectorAll('[data-sln-mega]').forEach(init);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', start);
	} else {
		start();
	}
})();
