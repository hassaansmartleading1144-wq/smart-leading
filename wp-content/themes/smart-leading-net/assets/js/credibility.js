/**
 * Smart Leading Net — Credibility section
 * Vanilla slider — toggles .active only; all logos exist in HTML on load.
 */
(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		var slider = document.querySelector('.credibility-slider');

		if (!slider) {
			return;
		}

		var slides = Array.prototype.slice.call(
			slider.querySelectorAll('.credibility-slide')
		);
		var dots = Array.prototype.slice.call(
			slider.querySelectorAll('.credibility-dot')
		);

		if (slides.length < 2) {
			return;
		}

		var current = 0;
		var timer = null;

		slides.forEach(function (slide, index) {
			if (slide.classList.contains('active')) {
				current = index;
			}
		});

		function setActiveSlide(index) {
			if (index < 0 || index >= slides.length) {
				return;
			}

			slides.forEach(function (slide, slideIndex) {
				slide.classList.toggle('active', slideIndex === index);
			});

			dots.forEach(function (dot, dotIndex) {
				var isActive = dotIndex === index;
				dot.classList.toggle('active', isActive);
				dot.setAttribute('aria-selected', isActive ? 'true' : 'false');
			});

			current = index;
		}

		function nextSlide() {
			setActiveSlide((current + 1) % slides.length);
		}

		function stopAutoplay() {
			if (timer !== null) {
				window.clearInterval(timer);
				timer = null;
			}
		}

		function startAutoplay() {
			stopAutoplay();
			timer = window.setInterval(nextSlide, 5000);
		}

		dots.forEach(function (dot, index) {
			dot.addEventListener('click', function (event) {
				event.preventDefault();

				var dotIndex = parseInt(
					dot.getAttribute('data-credibility-index') || String(index),
					10
				);

				if (isNaN(dotIndex)) {
					dotIndex = index;
				}

				setActiveSlide(dotIndex);
				startAutoplay();
			});
		});

		slider.addEventListener('mouseenter', stopAutoplay);
		slider.addEventListener('mouseleave', startAutoplay);

		startAutoplay();
	});
})();
