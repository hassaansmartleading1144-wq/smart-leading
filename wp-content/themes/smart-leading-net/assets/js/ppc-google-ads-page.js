(function () {
	'use strict';

	var page = document.querySelector('.sln-ppc-page');

	if (!page) {
		return;
	}

	var reducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	function onView(selector, callback, threshold) {
		var nodes = page.querySelectorAll(selector);

		if (!nodes.length) {
			return;
		}

		if (!('IntersectionObserver' in window) || reducedMotion) {
			nodes.forEach(callback);
			return;
		}

		var observer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) {
					return;
				}

				callback(entry.target);
				observer.unobserve(entry.target);
			});
		}, { threshold: threshold || 0.2 });

		nodes.forEach(function (node) {
			observer.observe(node);
		});
	}

	function parseQueries(root) {
		var queries = [];

		if (root.dataset.queries) {
			try {
				queries = JSON.parse(root.dataset.queries);
			} catch (error) {
				queries = [];
			}
		}

		if (!queries.length) {
			root.querySelectorAll('[data-query]').forEach(function (node) {
				if (node.textContent.trim()) {
					queries.push(node.textContent.trim());
				}
			});
		}

		return queries.filter(Boolean);
	}

	function enhanceSearch(root) {
		var queryEl = root.querySelector('.sln-ppc-search-query');
		var result = root.querySelector('.sln-ppc-search-result');
		var queries = parseQueries(root);
		var index = 0;
		var typingTimer;

		if (result) {
			result.classList.add('sln-ppc-is-visible');
		}

		if (!queryEl || reducedMotion || queries.length < 2) {
			return;
		}

		function typeText(text, position) {
			queryEl.textContent = text.slice(0, position);

			if (position < text.length) {
				typingTimer = window.setTimeout(function () {
					typeText(text, position + 1);
				}, 45);
				return;
			}

			typingTimer = window.setTimeout(nextQuery, 2200);
		}

		function nextQuery() {
			index = (index + 1) % queries.length;
			typeText(queries[index], 1);
		}

		window.setTimeout(nextQuery, 1800);

		root.addEventListener('mouseleave', function () {
			if (!typingTimer) {
				nextQuery();
			}
		});
	}

	function ensureBarHeights(root) {
		root.querySelectorAll('.sln-ppc-bar').forEach(function (bar) {
			var height = bar.dataset.h || parseFloat(window.getComputedStyle(bar).getPropertyValue('--h')) || 62;
			var value = String(height).replace('%', '') + '%';

			bar.style.setProperty('--h', value);
			bar.style.height = value;
		});
	}

	function ensureWidth(node) {
		var width = node.dataset.w || window.getComputedStyle(node).getPropertyValue('--w') || '100';
		var value = String(width).trim();

		if (value && value.indexOf('%') === -1) {
			value += '%';
		}

		node.style.setProperty('--w', value);
		node.style.width = value;
	}

	function formatMoney(value) {
		return '$' + Math.round(value).toLocaleString();
	}

	function formatControlValue(input) {
		var prefix = input.dataset.prefix || '';
		var suffix = input.dataset.suffix || '';
		var value = Number(input.value || 0);

		return prefix + Math.round(value).toLocaleString() + suffix;
	}

	function paintRange(input) {
		var min = Number(input.min || 0);
		var max = Number(input.max || 100);
		var value = Number(input.value || 0);
		var percent = max > min ? ((value - min) / (max - min)) * 100 : 0;

		input.style.background = 'linear-gradient(90deg, var(--sln-ppc-azure) ' + percent + '%, #DCE3EE ' + percent + '%)';
	}

	function enhanceCounts(root) {
		if (reducedMotion) {
			return;
		}

		root.querySelectorAll('.sln-ppc-count').forEach(function (el) {
			var pre = el.dataset.pre || '';
			var suf = el.dataset.suf || '';
			var dec = Number(el.dataset.dec || 0);
			var target = parseFloat(el.dataset.val);

			if (isNaN(target)) {
				return;
			}

			var duration = 1100;
			var start = window.performance.now();

			function tick(now) {
				var progress = Math.min(1, (now - start) / duration);
				var eased = 1 - Math.pow(1 - progress, 3);
				el.textContent = pre + (target * eased).toFixed(dec) + suf;

				if (progress < 1) {
					window.requestAnimationFrame(tick);
				}
			}

			window.requestAnimationFrame(tick);
		});
	}

	function enhanceRoi(root) {
		var budgetInput = root.querySelector('[data-sln-ppc-roi-control="budget"] input[type="range"]');
		var valueInput = root.querySelector('[data-sln-ppc-roi-control="value"] input[type="range"]');
		var cpc = Number(root.dataset.cpc || 2.5);
		var cvr = Number(root.dataset.cvr || 0.03);

		if (!budgetInput || !valueInput || !cpc) {
			return;
		}

		function output(key) {
			return root.querySelector('[data-sln-ppc-roi-output="' + key + '"]');
		}

		function controlValue(key) {
			return root.querySelector('[data-sln-ppc-roi-control-value="' + key + '"]');
		}

		function update() {
			var budget = Number(budgetInput.value || 0);
			var value = Number(valueInput.value || 0);
			var clicks = budget / cpc;
			var customers = clicks * cvr;
			var revenue = customers * value;
			var roas = budget > 0 ? revenue / budget : 0;
			var budgetValue = controlValue('budget');
			var customerValue = controlValue('value');
			var clicksOutput = output('clicks');
			var customersOutput = output('customers');
			var revenueOutput = output('revenue');
			var roasOutput = output('roas');

			if (budgetValue) {
				budgetValue.textContent = formatControlValue(budgetInput);
			}

			if (customerValue) {
				customerValue.textContent = formatControlValue(valueInput);
			}

			if (clicksOutput) {
				clicksOutput.textContent = Math.round(clicks).toLocaleString();
			}

			if (customersOutput) {
				customersOutput.textContent = Math.round(customers).toLocaleString();
			}

			if (revenueOutput) {
				revenueOutput.textContent = formatMoney(revenue);
			}

			if (roasOutput) {
				roasOutput.textContent = roas.toFixed(1) + 'x';
			}

			paintRange(budgetInput);
			paintRange(valueInput);
		}

		budgetInput.addEventListener('input', update);
		valueInput.addEventListener('input', update);
		update();
	}

	page.querySelectorAll('[data-sln-ppc-search]').forEach(enhanceSearch);
	onView('.sln-ppc-reveal', function (node) {
		node.classList.add('sln-ppc-is-in');
	}, 0.15);
	onView('[data-sln-ppc-counts]', enhanceCounts, 0.4);
	onView('[data-sln-ppc-bars]', ensureBarHeights, 0.35);
	onView('[data-sln-ppc-process]', function (node) {
		node.classList.add('sln-ppc-is-in');
	}, 0.3);
	onView('[data-sln-ppc-leak]', ensureWidth, 0.4);
	onView('[data-sln-ppc-progress]', ensureWidth, 0.4);
	page.querySelectorAll('[data-sln-ppc-roi]').forEach(enhanceRoi);
}());
