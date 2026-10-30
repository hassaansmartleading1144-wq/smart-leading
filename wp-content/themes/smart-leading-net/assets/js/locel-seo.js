/**
 * Locale SEO page — FAQ accordion, CRM slider, audit form.
 */
(function () {
	'use strict';

	var root = document.querySelector('.sls-locel-seo');
	if (!root) {
		return;
	}

	var reduceMotion = false;
	try {
		reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	} catch (e) {
		reduceMotion = false;
	}

	var igScriptState = 'idle';
	var igWaiters = [];

	function withInstagramEmbed(done) {
		if (window.instgrm && window.instgrm.Embeds) {
			done(true);
			return;
		}
		igWaiters.push(done);
		if (igScriptState === 'loading') {
			return;
		}
		if (igScriptState === 'error') {
			done(false);
			return;
		}
		igScriptState = 'loading';
		var existing = document.getElementById('sln-instagram-embed');
		function flush(ok) {
			igScriptState = ok ? 'ready' : 'error';
			var queue = igWaiters.slice();
			igWaiters = [];
			queue.forEach(function (fn) {
				fn(ok);
			});
		}
		if (existing) {
			existing.addEventListener('load', function () {
				flush(true);
			});
			existing.addEventListener('error', function () {
				flush(false);
			});
			return;
		}
		var script = document.createElement('script');
		script.id = 'sln-instagram-embed';
		script.async = true;
		script.src = 'https://www.instagram.com/embed.js';
		script.onload = function () {
			flush(true);
		};
		script.onerror = function () {
			flush(false);
		};
		document.body.appendChild(script);
	}

	function activateInstagramPlayer(frame) {
		if (!frame || frame.classList.contains('is-playing')) {
			return;
		}
		var permalink = frame.getAttribute('data-ig-permalink');
		var host = frame.querySelector('[data-lseo-ig-host]');
		var overlay = frame.querySelector('.video__inner');
		var play = frame.querySelector('[data-lseo-ig-play]');
		if (!permalink || !host) {
			return;
		}
		frame.classList.add('is-playing');
		if (overlay) {
			overlay.hidden = true;
		}
		if (play) {
			play.setAttribute('aria-expanded', 'true');
		}
		host.hidden = false;
		if (!host.querySelector('.instagram-media')) {
			var quote = document.createElement('blockquote');
			quote.className = 'instagram-media';
			quote.setAttribute('data-instgrm-permalink', permalink + (permalink.indexOf('?') === -1 ? '?utm_source=ig_embed' : ''));
			quote.setAttribute('data-instgrm-version', '14');
			var link = document.createElement('a');
			link.href = permalink;
			link.target = '_blank';
			link.rel = 'noopener noreferrer';
			link.textContent = 'View this post on Instagram';
			quote.appendChild(link);
			host.appendChild(quote);
		}
		withInstagramEmbed(function (ok) {
			if (ok && window.instgrm && window.instgrm.Embeds) {
				window.instgrm.Embeds.process();
				return;
			}
			frame.classList.add('is-fallback');
			if (overlay) {
				overlay.hidden = false;
			}
		});
		window.setTimeout(function () {
			if (!host.querySelector('iframe')) {
				frame.classList.add('is-fallback');
			}
		}, 5000);
	}

	root.querySelectorAll('[data-lseo-ig-player]').forEach(function (frame) {
		var play = frame.querySelector('[data-lseo-ig-play]');
		if (!play) {
			return;
		}
		play.setAttribute('aria-expanded', 'false');
		play.addEventListener('click', function (event) {
			event.preventDefault();
			activateInstagramPlayer(frame);
		});
	});

	root.querySelectorAll('[data-lseo-faq]').forEach(function (list) {
		list.querySelectorAll('.faq-item').forEach(function (item) {
			var btn = item.querySelector('.faq-q');
			var panel = item.querySelector('.faq-a');
			if (!btn || !panel) {
				return;
			}
			function setOpen(open) {
				item.classList.toggle('is-open', open);
				btn.setAttribute('aria-expanded', open ? 'true' : 'false');
				panel.setAttribute('aria-hidden', open ? 'false' : 'true');
				if (reduceMotion) {
					panel.style.transition = 'none';
					panel.style.maxHeight = open ? 'none' : '0px';
					return;
				}
				panel.style.maxHeight = open ? panel.scrollHeight + 'px' : '0px';
			}
			item.classList.remove('is-open');
			setOpen(false);
			btn.addEventListener('click', function () {
				setOpen(!item.classList.contains('is-open'));
			});
		});
	});

	root.querySelectorAll('[data-lseo-slider]').forEach(function (slider) {
		var track = slider.querySelector('[data-lseo-track]');
		if (!track) {
			return;
		}
		var slides = Array.prototype.slice.call(track.children);
		var bar = slider.parentNode ? slider.parentNode.querySelector('.slider__bar') : null;
		var prev = bar ? bar.querySelector('[data-lseo-prev]') : slider.querySelector('[data-lseo-prev]');
		var next = bar ? bar.querySelector('[data-lseo-next]') : slider.querySelector('[data-lseo-next]');
		var count = bar ? bar.querySelector('[data-lseo-count]') : null;
		var total = bar ? bar.querySelector('[data-lseo-total]') : null;
		var dotsWrap = bar ? bar.querySelector('[data-lseo-dots]') : null;
		var index = 0;

		if (total) {
			total.textContent = String(slides.length);
		}
		if (dotsWrap) {
			dotsWrap.innerHTML = '';
			slides.forEach(function () {
				dotsWrap.appendChild(document.createElement('i'));
			});
		}

		function go(n) {
			index = Math.max(0, Math.min(slides.length - 1, n));
			if (reduceMotion) {
				track.style.transition = 'none';
			}
			track.style.transform = 'translateX(' + -100 * index + '%)';
			if (prev) {
				prev.disabled = index === 0;
			}
			if (next) {
				next.disabled = index === slides.length - 1;
			}
			if (count) {
				count.textContent = String(index + 1);
			}
			slides.forEach(function (slide, i) {
				slide.setAttribute('aria-hidden', i !== index ? 'true' : 'false');
				if (dotsWrap && dotsWrap.children[i]) {
					dotsWrap.children[i].classList.toggle('is-on', i === index);
				}
			});
		}

		if (prev) {
			prev.addEventListener('click', function () {
				go(index - 1);
			});
		}
		if (next) {
			next.addEventListener('click', function () {
				go(index + 1);
			});
		}
		slider.addEventListener('keydown', function (event) {
			if (event.key === 'ArrowLeft') {
				go(index - 1);
			}
			if (event.key === 'ArrowRight') {
				go(index + 1);
			}
		});
		go(0);
	});

	var cfg = window.slnLocelSeoForm || {};

	root.querySelectorAll('form[data-lseo-audit]').forEach(function (form) {
		var statusEl = form.querySelector('[data-lseo-status]');
		var statusText = statusEl ? statusEl.querySelector('span') : null;

		function clearErrors() {
			form.querySelectorAll('.error').forEach(function (el) {
				el.textContent = '';
			});
			form.querySelectorAll('[aria-invalid]').forEach(function (el) {
				el.removeAttribute('aria-invalid');
			});
			if (statusEl) {
				statusEl.hidden = true;
			}
		}

		function showError(name, message) {
			var field = form.elements.namedItem(name);
			var err = form.querySelector('[data-error-for="' + name + '"]');
			if (field) {
				field.setAttribute('aria-invalid', 'true');
			}
			if (err) {
				err.textContent = message;
			}
		}

		function getValue(name) {
			var el = form.elements.namedItem(name);
			return el ? String(el.value || '').trim() : '';
		}

		form.addEventListener('submit', function (event) {
			event.preventDefault();
			clearErrors();

			var errors = {};
			var name = getValue('name');
			var email = getValue('email');
			var website = getValue('website');
			var area = getValue('area');
			var extra = getValue('extra');

			if (!name) {
				errors.name = cfg.nameRequired || 'Enter your full name.';
			}
			if (!email) {
				errors.email = cfg.emailRequired || 'Enter your business email address.';
			} else if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email)) {
				errors.email = cfg.emailInvalid || 'Enter an email address in the format name@company.com.';
			}
			if (website && website !== 'https://' && !/^(https?:\/\/)?[^\s/.]+(\.[^\s/.]+)+(\/\S*)?$/i.test(website)) {
				errors.website = cfg.websiteInvalid || 'Enter a website address such as yourcompany.com, or leave this field blank.';
			}
			if (!area) {
				errors.area = cfg.areaRequired || 'Enter your city or service area.';
			}

			var order = ['name', 'email', 'website', 'area'];
			var first = null;
			order.forEach(function (key) {
				if (errors[key] && !first) {
					first = key;
				}
				if (errors[key]) {
					showError(key, errors[key]);
				}
			});

			if (first) {
				var target = form.elements.namedItem(first);
				if (target && target.focus) {
					target.focus();
				}
				return;
			}

			if (!cfg.ajaxUrl || !cfg.action || !cfg.nonce) {
				if (statusEl) {
					statusEl.hidden = false;
					if (statusText) {
						statusText.textContent = cfg.errorMessage || 'Something went wrong. Please try again or contact us directly.';
					}
				}
				return;
			}

			var submitBtn = form.querySelector('button[type="submit"]');
			var original = submitBtn ? submitBtn.innerHTML : '';
			if (submitBtn) {
				submitBtn.disabled = true;
				var textEl = submitBtn.querySelector('.sls-btn__text');
				if (textEl && cfg.sendingLabel) {
					textEl.textContent = cfg.sendingLabel;
				}
			}

			var body = new window.FormData();
			body.append('action', cfg.action);
			body.append('nonce', cfg.nonce);
			body.append('name', name);
			body.append('email', email);
			body.append('website', website);
			body.append('area', area);
			body.append('extra', extra);

			window
				.fetch(cfg.ajaxUrl, {
					method: 'POST',
					credentials: 'same-origin',
					body: body,
				})
				.then(function (res) {
					return res.json().then(function (data) {
						return { ok: res.ok, data: data };
					});
				})
				.then(function (result) {
					var data = result.data || {};
					if (data.success && data.redirect) {
						window.location.href = data.redirect;
						return;
					}
					if (statusEl) {
						statusEl.hidden = false;
						if (statusText) {
							statusText.textContent = data.message || (data.success ? cfg.successMessage : cfg.errorMessage);
						}
					}
				})
				.catch(function () {
					if (statusEl) {
						statusEl.hidden = false;
						if (statusText) {
							statusText.textContent = cfg.errorMessage || 'Something went wrong. Please try again or contact us directly.';
						}
					}
				})
				.then(function () {
					if (submitBtn) {
						submitBtn.disabled = false;
						if (original) {
							submitBtn.innerHTML = original;
						}
					}
				});
		});
	});
})();
