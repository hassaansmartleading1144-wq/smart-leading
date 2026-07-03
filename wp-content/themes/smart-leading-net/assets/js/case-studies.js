/**
 * Smart Leading Net — Case Studies section
 */
(function () {
	'use strict';

	function initCaseStudies() {
		var section = document.querySelector('.case-studies');

		if (!section) {
			return;
		}

		var cards = section.querySelectorAll('.case-studies__card');

		if (!cards.length) {
			return;
		}

		var hasRevealed = false;

		function revealCards() {
			if (hasRevealed) {
				return;
			}

			hasRevealed = true;

			cards.forEach(function (card) {
				card.classList.add('is-visible');
			});
		}

		function isSectionInView() {
			var rect = section.getBoundingClientRect();
			var viewHeight = window.innerHeight || document.documentElement.clientHeight;

			return rect.top < viewHeight * 0.85 && rect.bottom > 0;
		}

		if (!('IntersectionObserver' in window)) {
			revealCards();
			return;
		}

		if (isSectionInView()) {
			revealCards();
			return;
		}

		var observer = new IntersectionObserver(
			function (entries, obs) {
				entries.forEach(function (entry) {
					if (!entry.isIntersecting) {
						return;
					}

					revealCards();
					obs.disconnect();
				});
			},
			{
				threshold: 0.15,
				rootMargin: '0px 0px -5% 0px',
			}
		);

		observer.observe(section);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initCaseStudies);
	} else {
		initCaseStudies();
	}
})();
