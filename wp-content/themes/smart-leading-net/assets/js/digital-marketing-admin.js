/**
 * Smart Leading Net — Digital Marketing admin
 */
(function ($) {
	'use strict';

	function syncEditors() {
		try {
			if (window.tinymce && typeof window.tinymce.triggerSave === 'function') {
				window.tinymce.triggerSave();
			}
			if (window.wp && wp.editor && typeof wp.editor.save === 'function') {
				$('.sln-dm-admin__editor-field textarea[id], .sln-gp-admin__wysiwyg[id]').each(function () {
					try {
						wp.editor.save(this.id);
					} catch (e) {}
				});
			}
		} catch (e) {}
	}

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

	function reindexNestedList($list) {
		var prefix = $list.data('prefix');
		var type = $list.data('type') || 'bullets';

		$list.find('.sln-dm-admin__nested-row').each(function (index) {
			if (type === 'metrics') {
				$(this).find('input').eq(0).attr('name', prefix + '[' + index + '][value]');
				$(this).find('input').eq(1).attr('name', prefix + '[' + index + '][label]');
				return;
			}

			if (type === 'benefits') {
				$(this).find('input[type="text"]').attr('name', prefix + '[' + index + '][text]');
				$(this).find('input[type="checkbox"]').attr('name', prefix + '[' + index + '][active]');
				return;
			}

			$(this).find('input[type="text"]').attr('name', prefix + '[' + index + ']');
		});
	}

	function resetNestedLists($context) {
		$context.find('.sln-dm-admin__nested-list').each(function () {
			var $list = $(this);
			var type = $list.data('type') || 'bullets';
			var $rows = $list.find('.sln-dm-admin__nested-row');

			$rows.not(':first').remove();
			$rows = $list.find('.sln-dm-admin__nested-row');

			if (type === 'metrics') {
				$rows.first().find('input').val('');
				return;
			}

			if (type === 'benefits') {
				$rows.first().find('input[type="text"]').val('');
				$rows.first().find('input[type="checkbox"]').prop('checked', true);
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
		$('.sln-dm-admin__repeatable').each(function () {
			var $wrap = $(this);
			var rowSelector = $wrap.data('row-selector');
			var namePrefix = $wrap.data('name-prefix');
			var $list = $wrap.find('.sln-dm-admin__repeatable-list');

			$list.sortable({
				handle: '.sln-dm-admin__row-head',
				update: function () {
					reindexRepeater($list, rowSelector, namePrefix);
				},
			});

			$wrap.on('click', '.sln-dm-admin__move-up', function (event) {
				event.preventDefault();
				syncEditors();
				var $row = $(this).closest(rowSelector);
				var $prev = $row.prev(rowSelector);
				if ($prev.length) {
					$row.insertBefore($prev);
					reindexRepeater($list, rowSelector, namePrefix);
				}
			});

			$wrap.on('click', '.sln-dm-admin__move-down', function (event) {
				event.preventDefault();
				syncEditors();
				var $row = $(this).closest(rowSelector);
				var $next = $row.next(rowSelector);
				if ($next.length) {
					$row.insertAfter($next);
					reindexRepeater($list, rowSelector, namePrefix);
				}
			});

			$wrap.on('click', '.sln-dm-admin__remove-row', function (event) {
				event.preventDefault();
				syncEditors();
				$(this).closest(rowSelector).remove();
				reindexRepeater($list, rowSelector, namePrefix);
			});

			$wrap.on('click', '.sln-dm-admin__add-row', function (event) {
				event.preventDefault();
				syncEditors();
				var $rows = $list.find(rowSelector);
				var $clone = $rows.last().clone(false, false);
				$clone.find('input[type="text"], input[type="url"], textarea').val('');
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
			});
		});
	}

	function bindNestedLists() {
		$(document).on('click', '.sln-dm-admin__add-nested', function (event) {
			event.preventDefault();
			var $list = $(this).closest('.sln-dm-admin__nested-list');
			var prefix = $list.data('prefix');
			var type = $list.data('type') || 'bullets';
			var index = $list.find('.sln-dm-admin__nested-row').length;
			var $row;

			if (type === 'metrics') {
				$row = $(
					'<div class="sln-dm-admin__nested-row sln-dm-admin__nested-row--metrics">' +
						'<input type="text" class="regular-text" placeholder="Value" />' +
						'<input type="text" class="regular-text" placeholder="Label" />' +
						'<button type="button" class="button-link-delete sln-dm-admin__remove-nested">Remove</button>' +
					'</div>'
				);
				$row.find('input').eq(0).attr('name', prefix + '[' + index + '][value]');
				$row.find('input').eq(1).attr('name', prefix + '[' + index + '][label]');
			} else if (type === 'benefits') {
				$row = $(
					'<div class="sln-dm-admin__nested-row sln-dm-admin__nested-row--benefits">' +
						'<input type="text" class="regular-text" />' +
						'<label><input type="checkbox" value="1" checked /> Active</label>' +
						'<button type="button" class="button-link-delete sln-dm-admin__remove-nested">Remove</button>' +
					'</div>'
				);
				$row.find('input[type="text"]').attr('name', prefix + '[' + index + '][text]');
				$row.find('input[type="checkbox"]').attr('name', prefix + '[' + index + '][active]');
			} else {
				$row = $(
					'<div class="sln-dm-admin__nested-row">' +
						'<input type="text" class="regular-text" />' +
						'<button type="button" class="button-link-delete sln-dm-admin__remove-nested">Remove</button>' +
					'</div>'
				);
				$row.find('input').attr('name', prefix + '[' + index + ']');
			}

			$(this).before($row);
		});

		$(document).on('click', '.sln-dm-admin__remove-nested', function (event) {
			event.preventDefault();
			var $list = $(this).closest('.sln-dm-admin__nested-list');
			$(this).closest('.sln-dm-admin__nested-row').remove();
			reindexNestedList($list);
		});
	}

	function getCurrentTemplate() {
		var classic = $('#page_template').val();

		if (classic) {
			return classic;
		}

		if (window.slnDigitalMarketingAdmin && window.slnDigitalMarketingAdmin.currentTemplate) {
			return window.slnDigitalMarketingAdmin.currentTemplate;
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
		var config = window.slnDigitalMarketingAdmin || {};
		var template = getCurrentTemplate();

		if (template) {
			return template === (config.template || 'digital-marketing-page-template.php');
		}

		return !!config.isTargetPage;
	}

	function toggleMetaBoxes() {
		var show = shouldShowMetaBoxes();
		$('.postbox[id^="sln_dm_"]').toggle(show);
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
