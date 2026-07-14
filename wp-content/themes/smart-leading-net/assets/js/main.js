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

				var nestedToggle = event.target.closest('.sln-mobile-nested-toggle');



				if (nestedToggle) {

					event.preventDefault();

					event.stopPropagation();



					var parentLi = nestedToggle.closest('.menu-item-has-children');



					if (parentLi) {

						var isOpen = parentLi.classList.toggle('sln-submenu-open');

						nestedToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

					}



					return;

				}



				var link = event.target.closest('.dropdown-item');



				if (link && !link.classList.contains('dropdown-toggle') && !link.classList.contains('sln-mobile-nested-toggle')) {

					var offcanvas = bootstrap.Offcanvas.getInstance(mobileMenu);



					if (offcanvas) {

						offcanvas.hide();

					}

				}

			});

		}

	});

})();


