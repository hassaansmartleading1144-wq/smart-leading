/**
 * Smart Leading Net — AEO Services admin
 */
(function ($) {
	'use strict';

	function reindexRepeater($list, rowSelector, namePrefix) {
		$list.find(rowSelector).each(function (index) {
			$(this)
				.find('[name^="' + namePrefix + '"]')
				.each(function () {
					var pattern = new RegExp(namePrefix.replace(/\[/g, '\\[').replace(/\]/g, '\\]') + '\\[\\d+\\]');
					this.name = this.name.replace(pattern, namePrefix + '[' + index + ']');
				});
		});
	}

	function bindRepeaters() {
		$('.sln-aeo-admin__repeatable').each(function () {
			var $wrap = $(this);
			var rowSelector = $wrap.data('row-selector');
			var namePrefix = $wrap.data('name-prefix');
			var $list = $wrap.find('.sln-aeo-admin__repeatable-list');

			$list.sortable({
				handle: '.sln-aeo-admin__row-head',
				update: function () {
					reindexRepeater($list, rowSelector, namePrefix);
				},
			});

			$wrap.on('click', '.sln-aeo-admin__move-up', function (event) {
				event.preventDefault();
				var $row = $(this).closest(rowSelector);
				var $prev = $row.prev(rowSelector);
				if ($prev.length) {
					$row.insertBefore($prev);
					reindexRepeater($list, rowSelector, namePrefix);
				}
			});

			$wrap.on('click', '.sln-aeo-admin__move-down', function (event) {
				event.preventDefault();
				var $row = $(this).closest(rowSelector);
				var $next = $row.next(rowSelector);
				if ($next.length) {
					$row.insertAfter($next);
					reindexRepeater($list, rowSelector, namePrefix);
				}
			});

			$wrap.on('click', '.sln-aeo-admin__remove-row', function (event) {
				event.preventDefault();
				$(this).closest(rowSelector).remove();
				reindexRepeater($list, rowSelector, namePrefix);
			});

			$wrap.on('click', '.sln-aeo-admin__add-row', function (event) {
				event.preventDefault();
				var $rows = $list.find(rowSelector);
				if (!$rows.length) {
					return;
				}
				var $clone = $rows.last().clone(false, false);
				$clone.find('input[type="text"], input[type="url"], textarea').val('');
				$clone.find('input[type="checkbox"]').prop('checked', true);
				$clone.find('select').each(function () {
					this.selectedIndex = 0;
				});
				$list.append($clone);
				reindexRepeater($list, rowSelector, namePrefix);
			});
		});
	}

	function getCurrentTemplate() {
		var classic = $('#page_template').val();

		if (classic) {
			return classic;
		}

		if (window.slnAeoAdmin && window.slnAeoAdmin.currentTemplate) {
			return window.slnAeoAdmin.currentTemplate;
		}

		if (window.wp && wp.data && wp.data.select) {
			try {
				var blockTemplate = wp.data.select('core/editor').getEditedPostAttribute('template');

				if (blockTemplate) {
					return blockTemplate;
				}
			} catch (error) {}
		}

		return '';
	}

	function shouldShowMetaBoxes() {
		var config = window.slnAeoAdmin || {};
		var expected = config.template || 'aeo-page-template.php';

		return getCurrentTemplate() === expected || !!config.isTargetPage;
	}

	function toggleMetaBoxes() {
		var show = shouldShowMetaBoxes();
		$('.postbox[id^="sln_aeo_"]').toggle(show);
	}

	function bindBlockEditorTemplateWatcher() {
		if (!window.wp || !wp.data || !wp.data.subscribe) {
			return;
		}

		var previous = getCurrentTemplate();

		wp.data.subscribe(function () {
			var current = getCurrentTemplate();

			if (current !== previous) {
				previous = current;
				toggleMetaBoxes();
			}
		});
	}

	$(function () {
		bindRepeaters();
		toggleMetaBoxes();
		bindBlockEditorTemplateWatcher();
		$('#page_template').on('change', toggleMetaBoxes);
	});
})(jQuery);
