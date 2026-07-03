/**
 * Smart Leading Net — main scripts
 */
(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		var headerMain = document.querySelector('.header-main');
		var mobileMenu = document.getElementById('slnMobileMenu');

		if (headerMain) {
			window.addEventListener('scroll', function () {
				if (window.scrollY > 10) {
					headerMain.classList.add('is-sticky');
				} else {
					headerMain.classList.remove('is-sticky');
				}
			});
		}

		if (mobileMenu) {
			mobileMenu.addEventListener('click', function (event) {
				var link = event.target.closest('.dropdown-item');

				if (link && !link.classList.contains('dropdown-toggle')) {
					var offcanvas = bootstrap.Offcanvas.getInstance(mobileMenu);
					if (offcanvas) {
						offcanvas.hide();
					}
				}
			});
		}
	});
})();
