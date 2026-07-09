/**
 * Smart Leading Net — Digital Marketing Services Page interactions
 */
(function () {
	'use strict';

	function initReveal() {
		var items = document.querySelectorAll('.dm-page__reveal');

		if (!items.length || !('IntersectionObserver' in window)) {
			items.forEach(function (el) {
				el.classList.add('is-visible');
			});
			return;
		}

		var observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (!entry.isIntersecting) {
						return;
					}

					entry.target.classList.add('is-visible');
					observer.unobserve(entry.target);
				});
			},
			{
				threshold: 0.12,
				rootMargin: '0px 0px -40px 0px',
			}
		);

		items.forEach(function (el, index) {
			el.style.transitionDelay = (index % 3) * 70 + 'ms';
			observer.observe(el);
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initReveal);
	} else {
		initReveal();
	}
})();
