/**
 * Smart Leading Net — Our Services section
 */
(function () {
	'use strict';

	function easeOutCubic(t) {
		return 1 - Math.pow(1 - t, 3);
	}

	function formatCounterValue(value, prefix, suffix) {
		return prefix + Math.round(value) + suffix;
	}

	function getCounterSettings(section) {
		var localized = window.slnOurServices || {};
		var enabled = localized.counterEnabled !== false && localized.counterEnabled !== '0';
		var duration = parseInt(localized.counterDuration, 10);

		if (section) {
			if (section.dataset.counterEnabled === '0') {
				enabled = false;
			}

			if (section.dataset.counterDuration) {
				duration = parseInt(section.dataset.counterDuration, 10);
			}
		}

		if (Number.isNaN(duration) || duration < 500) {
			duration = 2000;
		}

		return {
			enabled: enabled,
			duration: duration,
		};
	}

	function animateCounter(element, settings) {
		if (element.dataset.counterAnimated === 'true') {
			return;
		}

		element.dataset.counterAnimated = 'true';

		var target = parseFloat(element.getAttribute('data-counter-value'));
		var prefix = element.getAttribute('data-counter-prefix') || '';
		var suffix = element.getAttribute('data-counter-suffix') || '';
		var duration = settings.duration;
		var start = null;

		if (Number.isNaN(target)) {
			return;
		}

		if (!settings.enabled) {
			element.textContent = formatCounterValue(target, prefix, suffix);
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

	function animatePanelCounters(panel, settings) {
		if (!panel) {
			return;
		}

		panel.querySelectorAll('.our-services__result-value[data-counter-value]').forEach(function (counter) {
			animateCounter(counter, settings);
		});
	}

	function isMobileTabsViewport() {
		return window.matchMedia('(max-width: 767.98px)').matches;
	}

	function scrollActiveTabIntoView(section, tab) {
		if (!isMobileTabsViewport() || !tab) {
			return;
		}

		var tabsContainer = section.querySelector('.our-services__tabs');

		if (!tabsContainer) {
			return;
		}

		if (typeof tab.scrollIntoView === 'function') {
			tab.scrollIntoView({
				behavior: 'smooth',
				inline: 'center',
				block: 'nearest',
			});
			return;
		}

		var tabLeft = tab.offsetLeft;
		var tabWidth = tab.offsetWidth;
		var containerWidth = tabsContainer.clientWidth;
		var maxScroll = tabsContainer.scrollWidth - containerWidth;
		var targetScroll = tabLeft - containerWidth / 2 + tabWidth / 2;

		tabsContainer.scrollTo({
			left: Math.min(Math.max(0, targetScroll), maxScroll),
			behavior: 'smooth',
		});
	}

	function activateTab(section, tab) {
		var tabId = tab.getAttribute('data-tab');
		var tabs = section.querySelectorAll('.our-services__tab');
		var panels = section.querySelectorAll('.our-services__panel');
		var activePanel = section.querySelector('.our-services__panel[data-panel="' + tabId + '"]');
		var counterSettings = getCounterSettings(section);

		tabs.forEach(function (item) {
			item.classList.remove('is-active');
			item.setAttribute('aria-selected', 'false');
		});

		panels.forEach(function (panel) {
			panel.classList.remove('is-active');
			panel.hidden = true;
		});

		tab.classList.add('is-active');
		tab.setAttribute('aria-selected', 'true');

		if (activePanel) {
			activePanel.classList.add('is-active');
			activePanel.hidden = false;
			animatePanelCounters(activePanel, counterSettings);
		}

		scrollActiveTabIntoView(section, tab);
	}

	function initOurServicesTabs(section) {
		var tabs = section.querySelectorAll('.our-services__tab');

		if (!tabs.length) {
			return;
		}

		tabs.forEach(function (tab) {
			tab.addEventListener('click', function () {
				if (tab.classList.contains('is-active')) {
					return;
				}

				activateTab(section, tab);
			});
		});
	}

	function initOurServicesCounters(section) {
		var activePanel = section.querySelector('.our-services__panel.is-active');
		var counterSettings = getCounterSettings(section);

		if (!activePanel) {
			return;
		}

		if (!('IntersectionObserver' in window)) {
			animatePanelCounters(activePanel, counterSettings);
			return;
		}

		var observer = new IntersectionObserver(
			function (entries, obs) {
				entries.forEach(function (entry) {
					if (!entry.isIntersecting) {
						return;
					}

					animatePanelCounters(entry.target, counterSettings);
					obs.unobserve(entry.target);
				});
			},
			{
				threshold: 0.35,
				rootMargin: '0px 0px -10% 0px',
			}
		);

		observer.observe(activePanel);
	}

	function initOurServices() {
		var section = document.querySelector('.our-services');

		if (!section) {
			return;
		}

		initOurServicesTabs(section);
		initOurServicesCounters(section);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initOurServices);
	} else {
		initOurServices();
	}
})();
