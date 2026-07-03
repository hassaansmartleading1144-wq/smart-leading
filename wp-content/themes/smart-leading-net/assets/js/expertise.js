/**
 * Smart Leading Net — Expertise section
 */
(function () {
	'use strict';

	function initExpertise() {
		var section = document.querySelector('.expertise');

		if (!section) {
			return;
		}

		if (section.classList.contains('is-visible')) {
			return;
		}

		function revealSection() {
			section.classList.add('is-visible');
		}

		function isSectionInView() {
			var rect = section.getBoundingClientRect();
			var viewHeight = window.innerHeight || document.documentElement.clientHeight;

			return rect.top < viewHeight * 0.85 && rect.bottom > 0;
		}

		if (!('IntersectionObserver' in window)) {
			revealSection();
			return;
		}

		if (isSectionInView()) {
			revealSection();
			return;
		}

		var observer = new IntersectionObserver(
			function (entries, obs) {
				entries.forEach(function (entry) {
					if (!entry.isIntersecting) {
						return;
					}

					revealSection();
					obs.disconnect();
				});
			},
			{
				threshold: 0.2,
				rootMargin: '0px 0px -5% 0px',
			}
		);

		observer.observe(section);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initExpertise);
	} else {
		initExpertise();
	}
})();
