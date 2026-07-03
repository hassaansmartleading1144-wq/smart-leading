/**
 * Smart Leading Net — Our Projects admin settings
 */
(function ($) {
	'use strict';

	var mediaFrame;
	var optionName = 'sln_our_projects_settings';

	function reindexProjectRows($list) {
		$list.find('.sln-op-admin__project-row').each(function (index) {
			$(this)
				.find('[name*="[projects]"]')
				.each(function () {
					this.name = this.name.replace(/\[projects\]\[\d+\]/, '[projects][' + index + ']');
				});
		});
	}

	function bindMediaField($field) {
		$field.find('.sln-os-admin__media-select').off('click').on('click', function (event) {
			event.preventDefault();

			var $wrap = $(this).closest('.sln-os-admin__media-field');
			var $input = $wrap.find('.sln-os-admin__media-id');
			var $preview = $wrap.find('.sln-os-admin__media-preview');

			if (mediaFrame) {
				mediaFrame.close();
			}

			mediaFrame = wp.media({
				title: 'Select File',
				button: { text: 'Use this file' },
				library: { type: ['image/webp', 'image'] },
				multiple: false,
			});

			mediaFrame.on('select', function () {
				var attachment = mediaFrame.state().get('selection').first().toJSON();
				$input.val(attachment.id);
				$preview.html(
					'<img class="sln-os-admin__media-thumb" src="' +
						(attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url) +
						'" alt="" />'
				);
			});

			mediaFrame.open();
		});

		$field.find('.sln-os-admin__media-remove').off('click').on('click', function (event) {
			event.preventDefault();
			var $wrap = $(this).closest('.sln-os-admin__media-field');
			$wrap.find('.sln-os-admin__media-id').val('0');
			$wrap.find('.sln-os-admin__media-preview').empty();
		});
	}

	function initOurProjectsAdmin() {
		var $list = $('.sln-op-admin__projects-list');

		if (!$list.length) {
			return;
		}

		$list.sortable({
			handle: '.sln-os-admin__drag-handle',
			axis: 'y',
			update: function () {
				reindexProjectRows($list);
			},
		});

		$('.sln-os-admin__media-field').each(function () {
			bindMediaField($(this));
		});

		$('.sln-op-admin__add-project').on('click', function (event) {
			event.preventDefault();

			var template = $('#sln-op-project-row-template').html();
			var index = $list.find('.sln-op-admin__project-row').length;

			$list.append(template.replace(/\{\{index\}\}/g, index));
			bindMediaField($list.find('.sln-op-admin__project-row').last().find('.sln-os-admin__media-field'));
		});

		$list.on('click', '.sln-os-admin__remove-row', function (event) {
			event.preventDefault();

			if ($list.find('.sln-op-admin__project-row').length <= 1) {
				return;
			}

			$(this).closest('.sln-op-admin__project-row').remove();
			reindexProjectRows($list);
		});
	}

	$(initOurProjectsAdmin);
})(jQuery);
