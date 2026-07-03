/**
 * Smart Leading Net — How Work section tabs
 * Reuses our-services tab markup and behavior.
 */
(function () {
	'use strict';

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
		}

		scrollActiveTabIntoView(section, tab);
	}

	function initHowWorkTabs(section) {
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

	function initHowWork() {
		var sections = document.querySelectorAll('.how-work');

		sections.forEach(function (section) {
			initHowWorkTabs(section);
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initHowWork);
	} else {
		initHowWork();
	}
})();
