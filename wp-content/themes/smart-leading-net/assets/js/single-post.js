(function () {
	'use strict';

	var root = document.querySelector('.sln-blog');
	if (!root) {
		return;
	}

	var body = root.querySelector('#articleBody');
	if (!body) {
		return;
	}

	var progressWrap = root.querySelector('.sln-blog-progress');
	var header = document.querySelector('.header-main');
	if (progressWrap && header) {
		header.appendChild(progressWrap);
	}

	var bar = document.getElementById('readBar');
	var pct = document.getElementById('tocPct');
	var toast = document.getElementById('slnBlogToast');
	var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	var skipClosest = '.takeaways, .quick-answer, .steps, .proscons, .callout, .inline-cta, .faq, .author-box, .post-footer, .toc-card, .toc-mobile';
	var includeH3 = root.getAttribute('data-toc-h3') === '1';
	var usedIds = {};

	function uniqueHeadingId(heading) {
		var existing = heading.getAttribute('id');
		if (existing && !usedIds[existing]) {
			usedIds[existing] = true;
			return existing;
		}
		var base = (heading.getAttribute('id') || heading.textContent || 'section')
			.toLowerCase()
			.replace(/[^a-z0-9]+/g, '-')
			.replace(/^-+|-+$/g, '') || 'section';
		var slug = base;
		var n = 2;
		while (usedIds[slug] || (document.getElementById(slug) && document.getElementById(slug) !== heading)) {
			slug = base + '-' + n;
			n += 1;
		}
		usedIds[slug] = true;
		heading.id = slug;
		return slug;
	}

	var heads = Array.prototype.slice.call(body.querySelectorAll(includeH3 ? 'h2, h3' : 'h2')).filter(function (h) {
		if (h.closest(skipClosest)) {
			return false;
		}
		if ((h.textContent || '').trim() === '') {
			return false;
		}
		uniqueHeadingId(h);
		return h.id !== 'tldr';
	});

	var tocs = Array.prototype.slice.call(root.querySelectorAll('ol[data-toc-list]'));
	var tocCard = root.querySelector('.toc-card');
	var tocMobile = root.querySelector('.toc-mobile');
	var tocScroller = root.querySelector('[data-toc-scroll]');
	var activeIndex = -1;
	var ticking = false;
	var clicking = false;
	var tocUserUntil = 0;
	var programmaticTocScroll = false;

	function headerOffset() {
		var token = getComputedStyle(document.documentElement).getPropertyValue('--header-height');
		var parsed = parseInt(token, 10);
		if (parsed) {
			return parsed + 16;
		}
		return (header ? header.offsetHeight : 100) + 16;
	}

	if (!heads.length) {
		if (tocCard) {
			tocCard.classList.add('is-empty');
		}
		if (tocMobile) {
			tocMobile.classList.add('is-empty');
		}
	} else {
		tocs.forEach(function (list) {
			heads.forEach(function (h) {
				var li = document.createElement('li');
				var a = document.createElement('a');
				if (h.tagName === 'H3') {
					li.classList.add('is-h3');
				}
				a.href = '#' + h.id;
				a.textContent = h.getAttribute('data-toc') || (h.textContent || '').trim();
				li.appendChild(a);
				list.appendChild(li);
			});
		});
	}

	function tocItems(list) {
		return Array.prototype.slice.call(list.querySelectorAll('li:not(.toc__fill)'));
	}

	function scrollTocToItem(item) {
		if (!tocScroller || !item || Date.now() < tocUserUntil) {
			return;
		}
		var sRect = tocScroller.getBoundingClientRect();
		var iRect = item.getBoundingClientRect();
		var pad = 16;
		var delta = 0;
		if (iRect.top < sRect.top + pad) {
			delta = iRect.top - sRect.top - pad;
		} else if (iRect.bottom > sRect.bottom - pad) {
			delta = iRect.bottom - sRect.bottom + pad;
		}
		if (!delta) {
			return;
		}
		programmaticTocScroll = true;
		tocScroller.scrollTop += delta;
		requestAnimationFrame(function () {
			programmaticTocScroll = false;
		});
	}

	function applyActive(current, done) {
		activeIndex = current;
		tocs.forEach(function (list) {
			var items = tocItems(list);
			var fill = list.querySelector('.toc__fill');
			items.forEach(function (li, i) {
				li.classList.toggle('is-done', i < current || (done >= 0.995 && i === current));
				li.classList.toggle('is-active', i === current && done < 0.995);
				var a = li.querySelector('a');
				if (!a) {
					return;
				}
				if (i === current) {
					a.setAttribute('aria-current', 'true');
				} else {
					a.removeAttribute('aria-current');
				}
			});
			var target = items[current];
			if (fill) {
				var h = 0;
				if (target) {
					h = target.offsetTop + 17 - fill.offsetTop;
				}
				fill.style.height = Math.max(h, 0) + 'px';
			}
			if (target && list.closest('.toc-card')) {
				requestAnimationFrame(function () {
					scrollTocToItem(target);
				});
			}
		});
	}

	function currentHeadingIndex(done) {
		if (!heads.length) {
			return -1;
		}
		if (done >= 0.995) {
			return heads.length - 1;
		}
		var line = headerOffset();
		var current = 0;
		heads.forEach(function (h, i) {
			if (h.getBoundingClientRect().top - line <= 8) {
				current = i;
			}
		});
		return current;
	}

	function update() {
		var vh = window.innerHeight;
		var r = body.getBoundingClientRect();
		var total = Math.max(body.offsetHeight - vh * 0.5, 1);
		var done = Math.min(Math.max((-r.top + vh * 0.35) / total, 0), 1);
		if (bar) {
			bar.style.width = (done * 100).toFixed(2) + '%';
		}
		if (pct) {
			pct.textContent = Math.round(done * 100) + '% read';
		}
		if (!clicking) {
			applyActive(currentHeadingIndex(done), done);
		}
		ticking = false;
	}

	function onWinScroll() {
		if (Date.now() >= tocUserUntil - 400) {
			tocUserUntil = 0;
		}
		if (!ticking) {
			ticking = true;
			requestAnimationFrame(update);
		}
	}

	window.addEventListener('scroll', onWinScroll, { passive: true });
	window.addEventListener('resize', update);

	if ('IntersectionObserver' in window && heads.length) {
		var io = new IntersectionObserver(
			function () {
				if (!ticking) {
					ticking = true;
					requestAnimationFrame(update);
				}
			},
			{
				root: null,
				rootMargin: '-20% 0px -55% 0px',
				threshold: [0, 0.25, 1]
			}
		);
		heads.forEach(function (h) {
			io.observe(h);
		});
	}

	if (tocScroller) {
		['wheel', 'touchstart', 'pointerdown'].forEach(function (evt) {
			tocScroller.addEventListener(
				evt,
				function () {
					tocUserUntil = Date.now() + 1600;
				},
				{ passive: true }
			);
		});
		tocScroller.addEventListener(
			'scroll',
			function () {
				if (!programmaticTocScroll) {
					tocUserUntil = Date.now() + 1200;
				}
			},
			{ passive: true }
		);
		tocScroller.addEventListener('keydown', function (e) {
			var keys = ['ArrowDown', 'ArrowUp', 'PageDown', 'PageUp', 'Home', 'End'];
			if (keys.indexOf(e.key) === -1) {
				return;
			}
			e.preventDefault();
			tocUserUntil = Date.now() + 1600;
			var delta = e.key === 'ArrowDown' ? 40 : e.key === 'ArrowUp' ? -40 : e.key === 'PageDown' ? tocScroller.clientHeight - 24 : e.key === 'PageUp' ? -(tocScroller.clientHeight - 24) : 0;
			if (e.key === 'Home') {
				tocScroller.scrollTop = 0;
				return;
			}
			if (e.key === 'End') {
				tocScroller.scrollTop = tocScroller.scrollHeight;
				return;
			}
			tocScroller.scrollTop += delta;
		});
	}

	tocs.forEach(function (list) {
		list.addEventListener('click', function (e) {
			var a = e.target.closest('a');
			if (!a) {
				return;
			}
			var id = (a.getAttribute('href') || '').replace(/^#/, '');
			if (!id) {
				return;
			}
			var d = list.closest('details');
			if (d) {
				d.open = false;
			}
			clicking = true;
			tocUserUntil = 0;
			heads.forEach(function (h, i) {
				if (h.id === id) {
					applyActive(i, i === heads.length - 1 ? 1 : 0);
				}
			});
			window.setTimeout(function () {
				clicking = false;
				update();
			}, reduce ? 80 : 650);
		});
	});

	update();

	var shareUrl = encodeURIComponent(window.location.href);
	var shareTitle = encodeURIComponent(document.title);
	var shareMap = {
		linkedin: 'https://www.linkedin.com/sharing/share-offsite/?url=' + shareUrl,
		facebook: 'https://www.facebook.com/sharer/sharer.php?u=' + shareUrl,
		x: 'https://twitter.com/intent/tweet?url=' + shareUrl + '&text=' + shareTitle,
		email: 'mailto:?subject=' + shareTitle + '&body=' + shareUrl
	};
	root.querySelectorAll('[data-share]').forEach(function (a) {
		var key = a.getAttribute('data-share');
		if (shareMap[key]) {
			a.href = shareMap[key];
		}
	});

	var prompt = encodeURIComponent('Summarize the key points of this article: ' + window.location.href);
	var aiMap = {
		chatgpt: 'https://chatgpt.com/?q=' + prompt,
		claude: 'https://claude.ai/new?q=' + prompt,
		perplexity: 'https://www.perplexity.ai/search?q=' + prompt
	};
	root.querySelectorAll('[data-ai]').forEach(function (a) {
		var key = a.getAttribute('data-ai');
		if (aiMap[key]) {
			a.href = aiMap[key];
		}
	});

	function showToast(msg) {
		if (!toast) {
			return;
		}
		toast.textContent = msg;
		toast.classList.add('is-visible');
		clearTimeout(showToast.t);
		showToast.t = setTimeout(function () {
			toast.classList.remove('is-visible');
		}, 2200);
	}

	root.querySelectorAll('[data-copy]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			if (navigator.clipboard && window.isSecureContext) {
				navigator.clipboard.writeText(window.location.href).then(
					function () {
						showToast('Link copied');
					},
					function () {
						showToast("Couldn't copy. Copy the link from your address bar.");
					}
				);
			} else {
				showToast("Couldn't copy. Copy the link from your address bar.");
			}
		});
	});

	var helpful = root.querySelector('#helpful');
	if (helpful) {
		helpful.querySelectorAll('[data-vote]').forEach(function (b) {
			b.addEventListener('click', function () {
				helpful.classList.add('is-done');
			});
		});
	}

	root.querySelectorAll('.faq').forEach(function (faq) {
		var items = Array.prototype.slice.call(faq.querySelectorAll('details'));
		items.forEach(function (item) {
			var summary = item.querySelector('summary');
			if (!summary) {
				return;
			}
			summary.addEventListener('click', function (e) {
				e.preventDefault();
				var shouldOpen = !item.open;
				items.forEach(function (other) {
					other.open = false;
				});
				item.open = shouldOpen;
			});
		});
	});

	if (reduce) {
		document.documentElement.style.scrollBehavior = 'auto';
	}
})();
