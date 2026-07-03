/**
 * Smart Leading Net — Home Hero interactions & animations
 */
(function () {
	'use strict';

	function initHomeHero() {
		var hero = document.querySelector('.home-hero');

		if (!hero) {
			return;
		}

		var chartLine = hero.querySelector('.home-hero__chart-line');
		var progressFill = hero.querySelector('.home-hero__progress-fill');
		var chatForm = hero.querySelector('.home-hero__chat-form');
		var chatInput = hero.querySelector('.home-hero__chat-input');
		var chatResponse = hero.querySelector('.home-hero__chat-response');

		if (chartLine && typeof chartLine.getTotalLength === 'function') {
			var lineLength = chartLine.getTotalLength();
			chartLine.style.strokeDasharray = String(lineLength);
			chartLine.style.strokeDashoffset = String(lineLength);
		}

		if (progressFill) {
			var radius = progressFill.r.baseVal.value;
			var circumference = 2 * Math.PI * radius;
			var progressTarget = circumference * 0.13;

			progressFill.style.strokeDasharray = String(circumference);
			progressFill.style.strokeDashoffset = String(circumference);
			hero.style.setProperty('--hero-progress-end', String(progressTarget));
		}

		window.requestAnimationFrame(function () {
			hero.classList.add('is-ready');
		});

		if (!chatForm || !chatInput || !chatResponse) {
			return;
		}

		function handleChatSubmit(event) {
			if (event) {
				event.preventDefault();
			}

			var message = chatInput.value.trim();

			if (!message) {
				chatInput.focus();
				return;
			}

			console.log('[Home Hero Chat]', message);

			chatResponse.hidden = false;
			chatResponse.textContent = 'Thanks! We received your message: "' + message + '"';

			chatInput.value = '';
			chatInput.focus();
		}

		chatForm.addEventListener('submit', handleChatSubmit);

		chatInput.addEventListener('keydown', function (event) {
			if (event.key === 'Enter') {
				handleChatSubmit(event);
			}
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initHomeHero);
	} else {
		initHomeHero();
	}
})();
