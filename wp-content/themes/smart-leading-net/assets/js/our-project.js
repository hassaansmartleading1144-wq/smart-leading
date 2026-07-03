/**
 * Smart Leading Net — Our Projects section
 */
(function () {
	'use strict';

	function initOurProjectsSlider() {
		var section = document.querySelector('.our-projects');

		if (!section || typeof Swiper === 'undefined') {
			return;
		}

		var sliderEl = section.querySelector('.our-projects__slider');

		if (!sliderEl || sliderEl.classList.contains('swiper-initialized')) {
			return;
		}

		var slideCount = sliderEl.querySelectorAll('.swiper-slide').length;
		var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

		new Swiper(sliderEl, {
			slidesPerView: 1,
			spaceBetween: 24,
			centeredSlides: true,
			loop: slideCount > 3,
			watchOverflow: true,
			speed: 600,
			autoplay: prefersReducedMotion
				? false
				: {
						delay: 5000,
						disableOnInteraction: false,
						pauseOnMouseEnter: true,
					},
			pagination: {
				el: section.querySelector('.our-projects__pagination'),
				clickable: true,
				bulletClass: 'slider-dot',
				bulletActiveClass: 'active',
			},
			breakpoints: {
				768: {
					slidesPerView: 2,
					spaceBetween: 24,
				},
				1200: {
					slidesPerView: 3,
					spaceBetween: 24,
				},
			},
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initOurProjectsSlider);
	} else {
		initOurProjectsSlider();
	}
})();
