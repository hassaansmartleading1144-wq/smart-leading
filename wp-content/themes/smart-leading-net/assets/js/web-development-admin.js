/**
 * Smart Leading Net — Web Development admin
 */
(function ($) {
	'use strict';

	function syncEditors() {
		try {
			if (window.tinymce && typeof window.tinymce.triggerSave === 'function') {
				window.tinymce.triggerSave();
			}
			if (window.wp && wp.editor && typeof wp.editor.save === 'function') {
				$('.sln-wd-admin__editor-field textarea[id], .sln-gp-admin__wysiwyg[id]').each(function () {
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
		var type = $list.data('type') || 'features';

		$list.find('.sln-wd-admin__nested-row').each(function (index) {
			if (type === 'benefits') {
				$(this).find('input[type="text"]').attr('name', prefix + '[' + index + '][text]');
				$(this).find('input[type="checkbox"]').attr('name', prefix + '[' + index + '][active]');
				return;
			}

			if (type === 'steps') {
				$(this).find('input[type="text"]').eq(0).attr('name', prefix + '[' + index + '][number]');
				$(this).find('input[type="text"]').eq(1).attr('name', prefix + '[' + index + '][title]');
				$(this).find('textarea').attr('name', prefix + '[' + index + '][description]');
				$(this).find('input[type="checkbox"]').attr('name', prefix + '[' + index + '][active]');
				return;
			}

			$(this).find('input[type="text"]').attr('name', prefix + '[' + index + ']');
		});
	}

	function resetNestedLists($context) {
		$context.find('.sln-wd-admin__nested-list').each(function () {
			var $list = $(this);
			var type = $list.data('type') || 'features';
			var $rows = $list.find('.sln-wd-admin__nested-row');

			$rows.not(':first').remove();
			$rows = $list.find('.sln-wd-admin__nested-row');

			if (type === 'benefits') {
				$rows.first().find('input[type="text"]').val('');
				$rows.first().find('input[type="checkbox"]').prop('checked', true);
				return;
			}

			if (type === 'steps') {
				$rows.first().find('input[type="text"]').val('');
				$rows.first().find('textarea').val('');
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
		$('.sln-wd-admin__repeatable').each(function () {
			var $wrap = $(this);
			var rowSelector = $wrap.data('row-selector');
			var namePrefix = $wrap.data('name-prefix');
			var $list = $wrap.find('.sln-wd-admin__repeatable-list');

			$list.sortable({
				handle: '.sln-wd-admin__row-head',
				update: function () {
					reindexRepeater($list, rowSelector, namePrefix);
				},
			});

			$wrap.on('click', '.sln-wd-admin__move-up', function (event) {
				event.preventDefault();
				syncEditors();
				var $row = $(this).closest(rowSelector);
				var $prev = $row.prev(rowSelector);
				if ($prev.length) {
					$row.insertBefore($prev);
					reindexRepeater($list, rowSelector, namePrefix);
				}
			});

			$wrap.on('click', '.sln-wd-admin__move-down', function (event) {
				event.preventDefault();
				syncEditors();
				var $row = $(this).closest(rowSelector);
				var $next = $row.next(rowSelector);
				if ($next.length) {
					$row.insertAfter($next);
					reindexRepeater($list, rowSelector, namePrefix);
				}
			});

			$wrap.on('click', '.sln-wd-admin__remove-row', function (event) {
				event.preventDefault();
				syncEditors();
				$(this).closest(rowSelector).remove();
				reindexRepeater($list, rowSelector, namePrefix);
			});

			$wrap.on('click', '.sln-wd-admin__add-row', function (event) {
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
		$(document).on('click', '.sln-wd-admin__add-nested', function (event) {
			event.preventDefault();
			var $list = $(this).closest('.sln-wd-admin__nested-list');
			var prefix = $list.data('prefix');
			var type = $list.data('type') || 'features';
			var index = $list.find('.sln-wd-admin__nested-row').length;
			var $row;

			if (type === 'benefits') {
				$row = $(
					'<div class="sln-wd-admin__nested-row sln-wd-admin__nested-row--benefits">' +
						'<input type="text" class="regular-text" />' +
						'<label><input type="checkbox" value="1" checked /> Active</label>' +
						'<button type="button" class="button-link-delete sln-wd-admin__remove-nested">Remove</button>' +
					'</div>'
				);
				$row.find('input[type="text"]').attr('name', prefix + '[' + index + '][text]');
				$row.find('input[type="checkbox"]').attr('name', prefix + '[' + index + '][active]');
			} else if (type === 'steps') {
				$row = $(
					'<div class="sln-wd-admin__nested-row sln-wd-admin__nested-row--steps">' +
						'<input type="text" class="small-text" placeholder="Number" />' +
						'<input type="text" class="regular-text" placeholder="Title" />' +
						'<textarea class="large-text" rows="2" placeholder="Description"></textarea>' +
						'<label><input type="checkbox" value="1" checked /> Active</label>' +
						'<button type="button" class="button-link-delete sln-wd-admin__remove-nested">Remove</button>' +
					'</div>'
				);
				$row.find('input[type="text"]').eq(0).attr('name', prefix + '[' + index + '][number]');
				$row.find('input[type="text"]').eq(1).attr('name', prefix + '[' + index + '][title]');
				$row.find('textarea').attr('name', prefix + '[' + index + '][description]');
				$row.find('input[type="checkbox"]').attr('name', prefix + '[' + index + '][active]');
			} else {
				$row = $(
					'<div class="sln-wd-admin__nested-row sln-wd-admin__nested-row--features">' +
						'<input type="text" class="regular-text" />' +
						'<button type="button" class="button-link-delete sln-wd-admin__remove-nested">Remove</button>' +
					'</div>'
				);
				$row.find('input').attr('name', prefix + '[' + index + ']');
			}

			$(this).before($row);
		});

		$(document).on('click', '.sln-wd-admin__remove-nested', function (event) {
			event.preventDefault();
			var $list = $(this).closest('.sln-wd-admin__nested-list');
			$(this).closest('.sln-wd-admin__nested-row').remove();
			reindexNestedList($list);
		});
	}

	function getCurrentTemplate() {
		var classic = $('#page_template').val();

		if (classic) {
			return classic;
		}

		if (window.slnWebDevelopmentAdmin && window.slnWebDevelopmentAdmin.currentTemplate) {
			return window.slnWebDevelopmentAdmin.currentTemplate;
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
		var config = window.slnWebDevelopmentAdmin || {};
		var template = getCurrentTemplate();

		if (template) {
			return template === (config.template || 'web-development-page-template.php');
		}

		return !!config.isTargetPage;
	}

	function toggleMetaBoxes() {
		var show = shouldShowMetaBoxes();
		$('.postbox[id^="sln_wd_"]').toggle(show);
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
