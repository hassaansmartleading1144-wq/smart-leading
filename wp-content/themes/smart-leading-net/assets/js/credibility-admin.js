/**
 * Smart Leading Net — Credibility admin settings
 */
(function ($) {
	'use strict';

	var mediaFrame;

	function reindexLogoRows($list) {
		$list.find('.sln-cr-admin__logo-row').each(function (index) {
			$(this)
				.find('[name*="[logos]"]')
				.each(function () {
					this.name = this.name.replace(/\[logos\]\[\d+\]/, '[logos][' + index + ']');
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
				library: { type: ['image'] },
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

	function initCredibilityAdmin() {
		var $list = $('.sln-cr-admin__logos-list');

		if (!$list.length) {
			return;
		}

		$list.sortable({
			handle: '.sln-os-admin__drag-handle',
			axis: 'y',
			update: function () {
				reindexLogoRows($list);
			},
		});

		$('.sln-os-admin__media-field').each(function () {
			bindMediaField($(this));
		});

		$('.sln-cr-admin__add-logo').on('click', function (event) {
			event.preventDefault();

			var template = $('#sln-cr-logo-row-template').html();
			var index = $list.find('.sln-cr-admin__logo-row').length;

			$list.append(template.replace(/\{\{index\}\}/g, index));
			bindMediaField($list.find('.sln-cr-admin__logo-row').last().find('.sln-os-admin__media-field'));
		});

		$list.on('click', '.sln-os-admin__remove-row', function (event) {
			event.preventDefault();

			if ($list.find('.sln-cr-admin__logo-row').length <= 1) {
				return;
			}

			$(this).closest('.sln-cr-admin__logo-row').remove();
			reindexLogoRows($list);
		});
	}

	$(initCredibilityAdmin);
})(jQuery);
