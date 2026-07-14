/**
 * Smart Leading Net — Portfolio page admin
 */
(function ($) {
	'use strict';

	function syncEditors() {
		try {
			if (window.tinymce && typeof window.tinymce.triggerSave === 'function') {
				window.tinymce.triggerSave();
			}
			if (window.wp && wp.editor && typeof wp.editor.save === 'function') {
				$('.sln-portfolio-admin__editor-field textarea[id]').each(function () {
					try {
						wp.editor.save(this.id);
					} catch (error) {}
				});
			}
		} catch (error) {}
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
					title: 'Select Project Image',
					button: { text: 'Use this image' },
					library: { type: 'image' },
					multiple: false,
				});

				frame.on('select', function () {
					var attachment = frame.state().get('selection').first().toJSON();
					$input.val(attachment.id);
					$preview.empty();

					if (attachment.sizes && attachment.sizes.thumbnail) {
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

	function getCurrentTemplate() {
		var classic = $('#page_template').val();

		if (classic) {
			return classic;
		}

		if (window.slnPortfolioAdmin && window.slnPortfolioAdmin.currentTemplate) {
			return window.slnPortfolioAdmin.currentTemplate;
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

	function shouldShowMetaBox() {
		var config = window.slnPortfolioAdmin || {};
		var template = getCurrentTemplate();

		if (template) {
			return template === (config.template || 'portfolio-page-template.php');
		}

		return !!config.isTargetPage;
	}

	function toggleMetaBoxes() {
		var show = shouldShowMetaBox();
		$('#sln_portfolio_hero, #sln_portfolio_projects').toggle(show);
	}

	function bindRepeaters() {
		var $wrap = $('.sln-portfolio-admin__repeatable');

		if (!$wrap.length) {
			return;
		}

		var rowSelector = $wrap.data('row-selector');
		var namePrefix = $wrap.data('name-prefix');
		var $list = $wrap.find('.sln-portfolio-admin__repeatable-list');

		$list.sortable({
			handle: '.sln-portfolio-admin__row-head',
			update: function () {
				reindexRepeater($list, rowSelector, namePrefix);
			},
		});

		$wrap.on('click', '.sln-portfolio-admin__move-up', function (event) {
			event.preventDefault();
			syncEditors();
			var $row = $(this).closest(rowSelector);
			var $prev = $row.prev(rowSelector);

			if ($prev.length) {
				$row.insertBefore($prev);
				reindexRepeater($list, rowSelector, namePrefix);
			}
		});

		$wrap.on('click', '.sln-portfolio-admin__move-down', function (event) {
			event.preventDefault();
			syncEditors();
			var $row = $(this).closest(rowSelector);
			var $next = $row.next(rowSelector);

			if ($next.length) {
				$row.insertAfter($next);
				reindexRepeater($list, rowSelector, namePrefix);
			}
		});

		$wrap.on('click', '.sln-portfolio-admin__remove-row', function (event) {
			event.preventDefault();
			syncEditors();
			$(this).closest(rowSelector).remove();
			reindexRepeater($list, rowSelector, namePrefix);
		});

		$wrap.on('click', '.sln-portfolio-admin__add-row', function (event) {
			event.preventDefault();
			syncEditors();
			var $rows = $list.find(rowSelector);
			var $clone = $rows.last().clone(false, false);
			$clone.find('input[type="text"], input[type="url"], textarea').val('');
			$clone.find('input[type="checkbox"]').prop('checked', true);
			$clone.find('.sln-os-admin__media-id').val('');
			$clone.find('.sln-os-admin__media-preview').empty();
			$list.append($clone);
			reindexRepeater($list, rowSelector, namePrefix);
			bindMediaFields($clone);
		});
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
		toggleMetaBoxes();
		bindBlockEditorTemplateWatcher();
		$('#page_template').on('change', toggleMetaBoxes);
		$('#post').on('submit', syncEditors);
		$('#publish, #save-post').on('mousedown', syncEditors);
	});
})(jQuery);
