/**
 * Smart Leading Net — PPC & Google Ads admin
 */
(function ($) {
	'use strict';

	function syncEditors() {
		try {
			if (window.tinymce && typeof window.tinymce.triggerSave === 'function') {
				window.tinymce.triggerSave();
			}
			if (window.wp && wp.editor && typeof wp.editor.save === 'function') {
				$('.sln-ppc-admin__editor-field textarea[id], .sln-gp-admin__wysiwyg[id]').each(function () {
					try {
						wp.editor.save(this.id);
					} catch (e) {}
				});
			}
		} catch (e) {}
	}

	function escapeForRegExp(value) {
		return value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
	}

	function refreshNestedPrefix($nestedList) {
		var type = $nestedList.data('type') || 'bullets';
		var $first = $nestedList.find(':input[name]').first();
		var name = $first.attr('name');
		var prefix;

		if (!name) {
			return;
		}

		if (type === 'bullets') {
			prefix = name.replace(/\[\d+\]$/, '');
		} else {
			prefix = name.replace(/\[\d+\]\[[^\]]+\]$/, '');
		}

		$nestedList.attr('data-prefix', prefix).data('prefix', prefix);
	}

	function reindexRepeater($list, rowSelector, namePrefix) {
		$list.find(rowSelector).each(function (index) {
			var $row = $(this);
			var pattern = new RegExp(escapeForRegExp(namePrefix) + '\\[\\d+\\]');

			$row.find('[name^="' + namePrefix + '"]').each(function () {
				this.name = this.name.replace(pattern, namePrefix + '[' + index + ']');
			});

			$row.find('.sln-ppc-admin__nested-list').each(function () {
				refreshNestedPrefix($(this));
			});
		});
	}

	function reindexNestedList($list) {
		var prefix = $list.data('prefix');
		var type = $list.data('type') || 'bullets';

		$list.find('.sln-ppc-admin__nested-row').each(function (index) {
			var $row = $(this);

			if (type === 'metrics') {
				$row.find('[data-metric-key="prefix"]').attr('name', prefix + '[' + index + '][prefix]');
				$row.find('[data-metric-key="value"]').attr('name', prefix + '[' + index + '][value]');
				$row.find('[data-metric-key="decimals"]').attr('name', prefix + '[' + index + '][decimals]');
				$row.find('[data-metric-key="suffix"]').attr('name', prefix + '[' + index + '][suffix]');
				$row.find('[data-metric-key="display_value"]').attr('name', prefix + '[' + index + '][display_value]');
				$row.find('[data-metric-key="label"]').attr('name', prefix + '[' + index + '][label]');
				$row.find('[data-metric-key="visual_style"]').attr('name', prefix + '[' + index + '][visual_style]');
				return;
			}

			if (type === 'features') {
				$row.find('input[type="text"]').attr('name', prefix + '[' + index + '][text]');
				$row.find('input[data-feature-key="highlight"]').attr('name', prefix + '[' + index + '][highlight]');
				$row.find('input[data-feature-key="active"]').attr('name', prefix + '[' + index + '][active]');
				return;
			}

			$row.find('input[type="text"]').attr('name', prefix + '[' + index + ']');
		});
	}

	function addMetricDataKeys($context) {
		$context.find('.sln-ppc-admin__nested-list--metrics .sln-ppc-admin__nested-row').each(function () {
			var $fields = $(this).find('input, select');

			$fields.eq(0).attr('data-metric-key', 'prefix');
			$fields.eq(1).attr('data-metric-key', 'value');
			$fields.eq(2).attr('data-metric-key', 'decimals');
			$fields.eq(3).attr('data-metric-key', 'suffix');
			$fields.eq(4).attr('data-metric-key', 'display_value');
			$fields.eq(5).attr('data-metric-key', 'label');
			$fields.eq(6).attr('data-metric-key', 'visual_style');
		});
	}

	function addFeatureDataKeys($context) {
		$context.find('.sln-ppc-admin__nested-list--features .sln-ppc-admin__nested-row').each(function () {
			$(this).find('input[type="checkbox"]').eq(0).attr('data-feature-key', 'highlight');
			$(this).find('input[type="checkbox"]').eq(1).attr('data-feature-key', 'active');
		});
	}

	function resetNestedLists($context) {
		$context.find('.sln-ppc-admin__nested-list').each(function () {
			var $list = $(this);
			var type = $list.data('type') || 'bullets';
			var $rows = $list.find('.sln-ppc-admin__nested-row');

			$rows.not(':first').remove();
			$rows = $list.find('.sln-ppc-admin__nested-row');

			if (type === 'metrics') {
				$rows.first().find('input').val('');
				$rows.first().find('select').each(function () {
					this.selectedIndex = 0;
				});
				addMetricDataKeys($rows.first());
				return;
			}

			if (type === 'features') {
				$rows.first().find('input[type="text"]').val('');
				$rows.first().find('input[type="checkbox"]').eq(0).prop('checked', false);
				$rows.first().find('input[type="checkbox"]').eq(1).prop('checked', true);
				addFeatureDataKeys($rows.first());
				return;
			}

			$rows.first().find('input').val('');
		});
	}

	function bindMediaFields($context) {
		$context.find('.sln-os-admin__media-field').each(function () {
			var $field = $(this);
			if ($field.data('bound')) {
				return;
			}
			$field.data('bound', true);

			var $input = $field.find('.sln-os-admin__media-id');
			var $preview = $field.find('.sln-os-admin__media-preview');
			var frame;

			$field.on('click', '.sln-os-admin__media-select', function (event) {
				event.preventDefault();
				if (frame) {
					frame.open();
					return;
				}
				frame = wp.media({
					title: 'Select File',
					button: { text: 'Use this file' },
					multiple: false,
				});
				frame.on('select', function () {
					var attachment = frame.state().get('selection').first().toJSON();
					$input.val(attachment.id);
					$preview.empty();
					if (attachment.url && attachment.url.indexOf('.svg') !== -1) {
						$preview.append($('<img>', { src: attachment.url, alt: '' }));
					} else if (attachment.sizes && attachment.sizes.thumbnail) {
						$preview.append($('<img>', { src: attachment.sizes.thumbnail.url, alt: '' }));
					} else if (attachment.url) {
						$preview.append($('<img>', { src: attachment.url, alt: '' }));
					}
				});
				frame.open();
			});

			$field.on('click', '.sln-os-admin__media-remove', function (event) {
				event.preventDefault();
				$input.val('');
				$preview.empty();
			});
		});
	}

	function bindRepeaters() {
		$('.sln-ppc-admin__repeatable').each(function () {
			var $wrap = $(this);
			var rowSelector = $wrap.data('row-selector');
			var namePrefix = $wrap.data('name-prefix');
			var $list = $wrap.find('.sln-ppc-admin__repeatable-list');

			$list.sortable({
				handle: '.sln-ppc-admin__row-head',
				update: function () {
					reindexRepeater($list, rowSelector, namePrefix);
				},
			});

			$wrap.on('click', '.sln-ppc-admin__move-up', function (event) {
				event.preventDefault();
				syncEditors();
				var $row = $(this).closest(rowSelector);
				var $prev = $row.prev(rowSelector);
				if ($prev.length) {
					$row.insertBefore($prev);
					reindexRepeater($list, rowSelector, namePrefix);
				}
			});

			$wrap.on('click', '.sln-ppc-admin__move-down', function (event) {
				event.preventDefault();
				syncEditors();
				var $row = $(this).closest(rowSelector);
				var $next = $row.next(rowSelector);
				if ($next.length) {
					$row.insertAfter($next);
					reindexRepeater($list, rowSelector, namePrefix);
				}
			});

			$wrap.on('click', '.sln-ppc-admin__remove-row', function (event) {
				event.preventDefault();
				syncEditors();
				$(this).closest(rowSelector).remove();
				reindexRepeater($list, rowSelector, namePrefix);
			});

			$wrap.on('click', '.sln-ppc-admin__add-row', function (event) {
				event.preventDefault();
				syncEditors();
				var $rows = $list.find(rowSelector);
				var $clone = $rows.last().clone(false, false);
				$clone.find('input[type="text"], input[type="url"], input[type="number"], textarea').val('');
				$clone.find('input[type="checkbox"]').prop('checked', true);
				$clone.find('select').each(function () {
					this.selectedIndex = 0;
				});
				$clone.find('.sln-os-admin__media-id').val('');
				$clone.find('.sln-os-admin__media-preview').empty();
				resetNestedLists($clone);
				$list.append($clone);
				reindexRepeater($list, rowSelector, namePrefix);
				bindMediaFields($clone);
				addMetricDataKeys($clone);
				addFeatureDataKeys($clone);
			});
		});
	}

	function metricRowHtml() {
		var options = '';
		var styles = ['default', 'orange', 'blue', 'green'];
		var labels = ['Default', 'Orange', 'Blue', 'Green'];

		styles.forEach(function (style, index) {
			options += '<option value="' + style + '">' + labels[index] + '</option>';
		});

		return (
			'<div class="sln-ppc-admin__nested-row sln-ppc-admin__nested-row--metrics">' +
				'<input type="text" class="small-text" data-metric-key="prefix" placeholder="Prefix" />' +
				'<input type="text" class="small-text" data-metric-key="value" placeholder="Value" />' +
				'<input type="number" class="small-text" data-metric-key="decimals" placeholder="Decimals" />' +
				'<input type="text" class="small-text" data-metric-key="suffix" placeholder="Suffix" />' +
				'<input type="text" class="regular-text" data-metric-key="display_value" placeholder="Display" />' +
				'<input type="text" class="regular-text" data-metric-key="label" placeholder="Label" />' +
				'<select data-metric-key="visual_style">' + options + '</select>' +
				'<button type="button" class="button-link-delete sln-ppc-admin__remove-nested">Remove</button>' +
			'</div>'
		);
	}

	function featureRowHtml() {
		return (
			'<div class="sln-ppc-admin__nested-row sln-ppc-admin__nested-row--features">' +
				'<input type="text" class="regular-text" />' +
				'<label><input type="checkbox" value="1" data-feature-key="highlight" /> Highlight</label>' +
				'<label><input type="checkbox" value="1" data-feature-key="active" checked /> Active</label>' +
				'<button type="button" class="button-link-delete sln-ppc-admin__remove-nested">Remove</button>' +
			'</div>'
		);
	}

	function bindNestedLists() {
		$(document).on('click', '.sln-ppc-admin__add-nested', function (event) {
			event.preventDefault();
			var $list = $(this).closest('.sln-ppc-admin__nested-list');
			var prefix = $list.data('prefix');
			var type = $list.data('type') || 'bullets';
			var index = $list.find('.sln-ppc-admin__nested-row').length;
			var $row;

			if (type === 'metrics') {
				$row = $(metricRowHtml());
			} else if (type === 'features') {
				$row = $(featureRowHtml());
			} else {
				$row = $(
					'<div class="sln-ppc-admin__nested-row">' +
						'<input type="text" class="regular-text" />' +
						'<button type="button" class="button-link-delete sln-ppc-admin__remove-nested">Remove</button>' +
					'</div>'
				);
			}

			$(this).before($row);
			reindexNestedList($list);

			if (type !== 'bullets') {
				$list.find('.sln-ppc-admin__nested-row').last().find(':input[name]').each(function () {
					this.name = this.name.replace('[' + (index - 1) + ']', '[' + index + ']');
				});
			}

			refreshNestedPrefix($list);
		});

		$(document).on('click', '.sln-ppc-admin__remove-nested', function (event) {
			event.preventDefault();
			var $list = $(this).closest('.sln-ppc-admin__nested-list');
			$(this).closest('.sln-ppc-admin__nested-row').remove();
			reindexNestedList($list);
		});
	}

	function getCurrentTemplate() {
		var classic = $('#page_template').val();

		if (classic) {
			return classic;
		}

		if (window.slnPpcGoogleAdsAdmin && window.slnPpcGoogleAdsAdmin.currentTemplate) {
			return window.slnPpcGoogleAdsAdmin.currentTemplate;
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
		var config = window.slnPpcGoogleAdsAdmin || {};
		var template = getCurrentTemplate();

		if (template) {
			return template === (config.template || 'ppc-google-ads-page-template.php');
		}

		return !!config.isTargetPage;
	}

	function toggleMetaBoxes() {
		var show = shouldShowMetaBoxes();
		$('.postbox[id^="sln_ppc_"]').toggle(show);
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
		addMetricDataKeys($(document));
		addFeatureDataKeys($(document));
		bindMediaFields($(document));
		bindRepeaters();
		bindNestedLists();
		toggleMetaBoxes();
		bindBlockEditorTemplateWatcher();
		$('#page_template').on('change', toggleMetaBoxes);
		$('#post').on('submit', syncEditors);
		$('#publish, #save-post').on('mousedown', syncEditors);
	});
})(jQuery);
