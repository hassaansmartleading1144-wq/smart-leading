/**
 * PPC sub-pages — interactive guide (tabs, autoplay, swipe), staggered
 * reveals, hero ad swap and smooth FAQ. Loaded after ppc-google-ads-page.js.
 */
(function () {
	'use strict';

	var page = document.querySelector('.sln-ppc-subpage');

	if (!page) {
		return;
	}

	var reducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	if (!reducedMotion && 'IntersectionObserver' in window) {
		page.classList.add('sln-ppc-anim');
	}

	/* Stagger cards inside grids */
	['.sln-ppc-reality-grid', '.sln-ppc-modules', '.sln-ppc-industry-grid', '.sln-ppc-faqs', '.sln-ppc-tiles'].forEach(function (selector) {
		page.querySelectorAll(selector).forEach(function (grid) {
			Array.prototype.forEach.call(grid.children, function (child, index) {
				child.style.setProperty('--i', String(index % 6));
			});
		});
	});

	/* Hero: re-animate the ad each time a new search starts typing */
	var query = page.querySelector('.sln-ppc-search-query');
	var result = page.querySelector('.sln-ppc-search-result');

	if (query && result && !reducedMotion && 'MutationObserver' in window) {
		new MutationObserver(function () {
			if (query.textContent.length === 1) {
				result.classList.remove('is-swapping');
				void result.offsetWidth;
				result.classList.add('is-swapping');
			}
		}).observe(query, { childList: true, characterData: true, subtree: true });
	}

	/* FAQ: smooth height animation */
	page.querySelectorAll('.sln-ppc-faq').forEach(function (details) {
		var summary = details.querySelector('summary');
		var answer = details.querySelector('.sln-ppc-faq-answer');

		if (!summary || !answer || reducedMotion || !answer.animate) {
			return;
		}

		summary.addEventListener('click', function (event) {
			event.preventDefault();

			if (details.open) {
				var closing = answer.animate([{ height: answer.offsetHeight + 'px', opacity: 1 }, { height: '0px', opacity: 0 }], { duration: 260, easing: 'ease' });
				closing.onfinish = function () {
					details.open = false;
				};
			} else {
				details.open = true;
				answer.animate([{ height: '0px', opacity: 0 }, { height: answer.offsetHeight + 'px', opacity: 1 }], { duration: 320, easing: 'cubic-bezier(.22,.61,.36,1)' });
			}
		});
	});

	/* Interactive guide */
	page.querySelectorAll('[data-sln-ppc-guide]').forEach(function (guide) {
		var tabs = Array.prototype.slice.call(guide.querySelectorAll('[role="tab"]'));
		var panels = Array.prototype.slice.call(guide.querySelectorAll('[role="tabpanel"]'));
		var panelWrap = guide.querySelector('[data-sln-ppc-guide-panels]');
		var counter = guide.querySelector('[data-sln-ppc-guide-current]');
		var duration = 7000;
		var current = 0;
		var timer = null;
		var stopped = reducedMotion;
		var inView = false;
		var hovering = false;

		if (!tabs.length || tabs.length !== panels.length) {
			return;
		}

		guide.classList.add('is-enhanced');
		guide.style.setProperty('--sln-guide-dur', duration / 1000 + 's');

		function show(index, focus) {
			current = (index + tabs.length) % tabs.length;

			tabs.forEach(function (tab, i) {
				var active = i === current;
				tab.classList.toggle('is-active', active);
				tab.setAttribute('aria-selected', active ? 'true' : 'false');
				tab.tabIndex = active ? 0 : -1;
			});

			panels.forEach(function (panel, i) {
				var active = i === current;
				panel.classList.remove('is-active');
				panel.hidden = !active;

				if (active) {
					void panel.offsetWidth;
					panel.classList.add('is-active');
				}
			});

			if (counter) {
				counter.textContent = String(current + 1);
			}

			if (focus) {
				tabs[current].focus();
			}

			var strip = tabs[current].parentElement;

			if (strip && strip.scrollWidth > strip.clientWidth) {
				strip.scrollTo({ left: tabs[current].offsetLeft - 16, behavior: reducedMotion ? 'auto' : 'smooth' });
			}

			schedule();
		}

		function clear() {
			if (timer) {
				window.clearTimeout(timer);
				timer = null;
			}
		}

		function schedule() {
			clear();
			guide.classList.remove('is-playing');

			if (stopped || !inView || hovering) {
				return;
			}

			void guide.offsetWidth;
			guide.classList.add('is-playing');
			timer = window.setTimeout(function () {
				show(current + 1, false);
			}, duration);
		}

		function stop() {
			stopped = true;
			clear();
			guide.classList.remove('is-playing');
		}

		tabs.forEach(function (tab, index) {
			tab.addEventListener('click', function () {
				stop();
				show(index, false);
			});

			tab.addEventListener('keydown', function (event) {
				var next = null;

				if (event.key === 'ArrowDown' || event.key === 'ArrowRight') {
					next = current + 1;
				} else if (event.key === 'ArrowUp' || event.key === 'ArrowLeft') {
					next = current - 1;
				} else if (event.key === 'Home') {
					next = 0;
				} else if (event.key === 'End') {
					next = tabs.length - 1;
				}

				if (next !== null) {
					event.preventDefault();
					stop();
					show(next, true);
				}
			});
		});

		guide.querySelectorAll('.sln-ppc-guide-arrow').forEach(function (arrow) {
			arrow.addEventListener('click', function () {
				stop();
				show(current + Number(arrow.dataset.dir || 1), false);
			});
		});

		guide.addEventListener('mouseenter', function () {
			hovering = true;
			clear();
			guide.classList.add('is-paused');
		});

		guide.addEventListener('mouseleave', function () {
			hovering = false;
			guide.classList.remove('is-paused');
			schedule();
		});

		/* Swipe between steps on touch screens */
		if (panelWrap) {
			var startX = 0;
			var startY = 0;

			panelWrap.addEventListener('touchstart', function (event) {
				startX = event.touches[0].clientX;
				startY = event.touches[0].clientY;
			}, { passive: true });

			panelWrap.addEventListener('touchend', function (event) {
				var dx = event.changedTouches[0].clientX - startX;
				var dy = event.changedTouches[0].clientY - startY;

				if (Math.abs(dx) > 50 && Math.abs(dx) > Math.abs(dy)) {
					stop();
					show(current + (dx < 0 ? 1 : -1), false);
				}
			}, { passive: true });
		}

		if ('IntersectionObserver' in window) {
			new IntersectionObserver(function (entries) {
				entries.forEach(function (entry) {
					var wasInView = inView;
					inView = entry.isIntersecting;

					if (inView && !wasInView) {
						show(current, false);
					} else if (!inView) {
						clear();
						guide.classList.remove('is-playing');
					}
				});
			}, { threshold: 0.35 }).observe(guide);
		}

		show(0, false);
	});
}());

/* Why SLS — problem → fix flip cards */
(function () {
	'use strict';

	var wrap = document.querySelector('.sln-ppc-subpage [data-sln-why]');

	if (!wrap) {
		return;
	}

	var reducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var cards = Array.prototype.slice.call(wrap.querySelectorAll('.sln-why-card'));
	var toggle = wrap.querySelector('.sln-why-toggle');
	var buttons = toggle ? Array.prototype.slice.call(toggle.querySelectorAll('.sln-why-toggle-btn')) : [];
	var timers = [];

	if (!cards.length || reducedMotion || !('IntersectionObserver' in window)) {
		return;
	}

	if (toggle) {
		toggle.hidden = false;
	}

	function clearTimers() {
		timers.forEach(window.clearTimeout);
		timers = [];
	}

	function setSide(side, stagger) {
		var them = side !== 'us';

		clearTimers();
		wrap.classList.toggle('is-them', them);
		wrap.classList.toggle('is-inhouse', side === 'inhouse');

		buttons.forEach(function (button) {
			var on = button.dataset.side === side;
			button.classList.toggle('is-on', on);
			button.setAttribute('aria-pressed', on ? 'true' : 'false');
		});

		cards.forEach(function (card, index) {
			timers.push(window.setTimeout(function () {
				card.classList.toggle('is-flipped', them);
				card.classList.remove('is-landed');

				if (!them) {
					timers.push(window.setTimeout(function () {
						card.classList.add('is-landed');
					}, 420));
				}
			}, stagger ? index * stagger : 0));
		});
	}

	// Start on the problem side; flip to SLS once the grid is in view.
	cards.forEach(function (card) {
		card.classList.add('is-flipped');
	});
	wrap.classList.add('is-them');
	buttons.forEach(function (button) {
		var on = button.dataset.side === 'them';
		button.classList.toggle('is-on', on);
		button.setAttribute('aria-pressed', on ? 'true' : 'false');
	});

	var observer = new IntersectionObserver(function (entries) {
		entries.forEach(function (entry) {
			if (entry.isIntersecting) {
				observer.disconnect();
				timers.push(window.setTimeout(function () {
					setSide('us', 380);
				}, 900));
			}
		});
	}, { threshold: 0.35 });

	observer.observe(wrap.querySelector('.sln-why-grid'));

	buttons.forEach(function (button) {
		button.addEventListener('click', function () {
			observer.disconnect();
			setSide(button.dataset.side, 120);
		});
	});
}());

/* Why SLS — tap (touch) or keyboard to peek at the SLS side of a flipped card */
(function () {
	'use strict';

	var wrap = document.querySelector('.sln-ppc-subpage [data-sln-why]');

	if (!wrap) {
		return;
	}

	wrap.classList.add('is-enhanced');

	wrap.querySelectorAll('.sln-why-card').forEach(function (card) {
		card.tabIndex = 0;

		function peek() {
			if (card.classList.contains('is-flipped')) {
				card.classList.toggle('is-peek');
			}
		}

		card.addEventListener('click', function () {
			if (!window.matchMedia('(hover: hover)').matches) {
				peek();
			}
		});

		card.addEventListener('keydown', function (event) {
			if (event.key === 'Enter' || event.key === ' ') {
				event.preventDefault();
				peek();
			}
		});
	});

	// Reset peeks whenever the toggle flips all cards.
	wrap.querySelectorAll('.sln-why-toggle-btn').forEach(function (button) {
		button.addEventListener('click', function () {
			wrap.querySelectorAll('.sln-why-card.is-peek').forEach(function (card) {
				card.classList.remove('is-peek');
			});
		});
	});
}());

/* Hub: SEO vs PPC toggle + FAQ tabs + Why SLS 3-way */
(function () {
	'use strict';

	var page = document.querySelector('.sln-ppc-subpage');

	if (!page) {
		return;
	}

	// SEO vs PPC
	page.querySelectorAll('[data-sln-svp]').forEach(function (wrap) {
		var buttons = Array.prototype.slice.call(wrap.querySelectorAll('.sln-svp-btn'));
		var panels = Array.prototype.slice.call(wrap.querySelectorAll('.sln-svp-panel'));

		wrap.classList.add('is-enhanced');
		wrap.dataset.side = buttons[0] ? buttons[0].dataset.side : 'ppc';

		function show(index) {
			buttons.forEach(function (button, i) {
				var on = i === index;
				button.classList.toggle('is-on', on);
				button.setAttribute('aria-selected', on ? 'true' : 'false');
				button.tabIndex = on ? 0 : -1;
			});
			panels.forEach(function (panel, i) {
				panel.classList.remove('is-active');
				panel.hidden = i !== index;
				if (i === index) {
					void panel.offsetWidth;
					panel.classList.add('is-active');
				}
			});
			wrap.dataset.side = buttons[index].dataset.side;
			wrap.dataset.index = String(index);
		}

		buttons.forEach(function (button, index) {
			button.addEventListener('click', function () {
				show(index);
			});
			button.addEventListener('keydown', function (event) {
				if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
					event.preventDefault();
					var next = (index + (event.key === 'ArrowRight' ? 1 : -1) + buttons.length) % buttons.length;
					show(next);
					buttons[next].focus();
				}
			});
		});

		show(0);
	});

	// FAQ tabs
	page.querySelectorAll('[data-sln-faq-tabs]').forEach(function (tabs) {
		var list = tabs.parentElement.querySelector('.sln-ppc-faqs');
		var buttons = Array.prototype.slice.call(tabs.querySelectorAll('.sln-faq-tab'));

		if (!list) {
			return;
		}

		list.classList.add('is-grouped');

		function show(group) {
			buttons.forEach(function (button) {
				var on = button.dataset.group === String(group);
				button.classList.toggle('is-on', on);
				button.setAttribute('aria-selected', on ? 'true' : 'false');
			});
			list.querySelectorAll('.sln-ppc-faq[data-group]').forEach(function (item) {
				item.hidden = item.dataset.group !== String(group);
				if (!item.hidden) {
					item.classList.add('sln-ppc-is-in');
				}
			});
		}

		buttons.forEach(function (button) {
			button.addEventListener('click', function () {
				show(button.dataset.group);
			});
		});

		show(0);
	});

}());

/* Free PPC Audit form */
(function () {
	'use strict';

	var form = document.querySelector('[data-sln-audit-form]');

	if (!form || !window.fetch || !window.FormData) {
		return;
	}

	var msg = form.querySelector('.sln-audform-msg');
	var button = form.querySelector('.sln-audform-submit');
	var success = form.parentElement.querySelector('.sln-audform-success');

	function showError(text) {
		msg.textContent = text;
		msg.hidden = false;
	}

	form.addEventListener('submit', function (event) {
		event.preventDefault();
		msg.hidden = true;

		var data = new FormData(form);

		if (!String(data.get('name') || '').trim()) {
			showError('Please enter your name.');
			return;
		}

		if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(data.get('email') || ''))) {
			showError('Please enter a valid email address.');
			return;
		}

		if (!data.getAll('platforms[]').length) {
			showError('Please choose at least one platform to audit.');
			return;
		}

		button.disabled = true;

		fetch(form.action, { method: 'POST', body: data, credentials: 'same-origin' })
			.then(function (response) {
				return response.json();
			})
			.then(function (json) {
				if (!json || !json.success) {
					throw new Error(json && json.message ? json.message : 'Something went wrong. Please try again.');
				}

				success.querySelector('[data-title]').textContent = json.title || 'Thank you!';
				success.querySelector('[data-message]').textContent = json.message || '';
				success.querySelector('[data-message-2]').textContent = json.message_2 || '';
				form.hidden = true;
				success.hidden = false;
			})
			.catch(function (error) {
				showError(error.message || 'Something went wrong. Please try again.');
			})
			.then(function () {
				button.disabled = false;
			});
	});
}());
