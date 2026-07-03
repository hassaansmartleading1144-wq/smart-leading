/**
 * Lazy-load AI chat assets after idle or first interaction.
 */
(function () {
	'use strict';

	var loaded = false;

	function loadChatAssets() {
		if (loaded) {
			return;
		}

		loaded = true;

		var loader = window.slsAiChatLoader || {};
		var config = loader.config || {};

		if (loader.css) {
			var link = document.createElement('link');
			link.rel = 'stylesheet';
			link.href = loader.css;
			document.head.appendChild(link);
		}

		window.slsAiChat = config;

		if (!loader.js) {
			return;
		}

		var script = document.createElement('script');
		script.src = loader.js;
		script.defer = true;
		document.body.appendChild(script);
	}

	function scheduleIdleLoad() {
		if ('requestIdleCallback' in window) {
			window.requestIdleCallback(loadChatAssets, { timeout: 3500 });
			return;
		}

		window.setTimeout(loadChatAssets, 3000);
	}

	function init() {
		var chatRoot = document.querySelector('.sls-ai-chat');

		if (!chatRoot) {
			return;
		}

		chatRoot.addEventListener('pointerdown', loadChatAssets, { once: true, capture: true });
		chatRoot.addEventListener('focusin', loadChatAssets, { once: true, capture: true });

		if (document.readyState === 'complete') {
			scheduleIdleLoad();
		} else {
			window.addEventListener('load', scheduleIdleLoad, { once: true });
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
