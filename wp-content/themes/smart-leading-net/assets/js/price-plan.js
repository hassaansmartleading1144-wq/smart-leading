/**
 * Smart Leading Net — Price Plan mobile slider
 * Vanilla carousel for max-width 767px; desktop grid is CSS-only.
 */
(function () {
	'use strict';

	var MOBILE_QUERY = '(max-width: 767.98px)';
	var SWIPE_THRESHOLD = 48;

	function initPricePlanSlider(slider) {
		var viewport = slider.querySelector('.price-plan__viewport');
		var track = slider.querySelector('.price-plan__track');
		var cards = slider.querySelectorAll('.price-plan__card');
		var prevBtn = slider.querySelector('.price-plan__prev');
		var nextBtn = slider.querySelector('.price-plan__next');
		var dots = slider.querySelectorAll('.price-plan__dot');
		var mediaQuery = window.matchMedia(MOBILE_QUERY);
		var current = 0;
		var touchStartX = 0;
		var touchCurrentX = 0;
		var isDragging = false;

		if (!viewport || !track || !cards.length) {
			return;
		}

		function isMobile() {
			return mediaQuery.matches;
		}

		function clampIndex(index) {
			var total = cards.length;

			if (total < 1) {
				return 0;
			}

			return ((index % total) + total) % total;
		}

		function updateDots() {
			dots.forEach(function (dot, index) {
				var isActive = index === current;

				dot.classList.toggle('is-active', isActive);
				dot.setAttribute('aria-selected', isActive ? 'true' : 'false');
			});
		}

		function applyTransform(offsetPercent) {
			track.style.transform = 'translate3d(' + offsetPercent + '%, 0, 0)';
		}

		function goTo(index, animate) {
			if (!isMobile()) {
				track.style.transform = '';
				return;
			}

			current = clampIndex(index);

			if (animate === false) {
				track.style.transition = 'none';
			}

			applyTransform(current * -100);

			if (animate === false) {
				track.offsetHeight;
				track.style.transition = '';
			}

			updateDots();
		}

		function goNext() {
			goTo(current + 1);
		}

		function goPrev() {
			goTo(current - 1);
		}

		function onTouchStart(event) {
			if (!isMobile() || cards.length < 2) {
				return;
			}

			isDragging = true;
			touchStartX = event.touches[0].clientX;
			touchCurrentX = touchStartX;
			track.style.transition = 'none';
		}

		function onTouchMove(event) {
			if (!isDragging || !isMobile()) {
				return;
			}

			touchCurrentX = event.touches[0].clientX;
			var delta = touchCurrentX - touchStartX;
			var offset = current * -100 + (delta / viewport.offsetWidth) * 100;

			applyTransform(offset);
		}

		function onTouchEnd() {
			if (!isDragging || !isMobile()) {
				return;
			}

			isDragging = false;
			track.style.transition = '';

			var delta = touchCurrentX - touchStartX;

			if (Math.abs(delta) >= SWIPE_THRESHOLD) {
				if (delta < 0) {
					goNext();
				} else {
					goPrev();
				}
			} else {
				goTo(current);
			}
		}

		function onMediaChange() {
			if (isMobile()) {
				goTo(current, false);
			} else {
				track.style.transform = '';
				track.style.transition = '';
			}
		}

		if (prevBtn) {
			prevBtn.addEventListener('click', function () {
				goPrev();
			});
		}

		if (nextBtn) {
			nextBtn.addEventListener('click', function () {
				goNext();
			});
		}

		dots.forEach(function (dot) {
			dot.addEventListener('click', function () {
				var index = parseInt(dot.getAttribute('data-slide'), 10);

				if (!isNaN(index)) {
					goTo(index);
				}
			});
		});

		viewport.addEventListener('touchstart', onTouchStart, { passive: true });
		viewport.addEventListener('touchmove', onTouchMove, { passive: true });
		viewport.addEventListener('touchend', onTouchEnd);
		viewport.addEventListener('touchcancel', onTouchEnd);

		if (typeof mediaQuery.addEventListener === 'function') {
			mediaQuery.addEventListener('change', onMediaChange);
		} else if (typeof mediaQuery.addListener === 'function') {
			mediaQuery.addListener(onMediaChange);
		}

		window.addEventListener('resize', onMediaChange);

		onMediaChange();
	}

	function initPricePlanSliders() {
		var sliders = document.querySelectorAll('[data-price-plan-slider]');

		sliders.forEach(function (slider) {
			initPricePlanSlider(slider);
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initPricePlanSliders);
	} else {
		initPricePlanSliders();
	}
})();
