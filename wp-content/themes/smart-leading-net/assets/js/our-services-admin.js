/**
 * Smart Leading Net — Our Services admin settings
 */
(function ($) {
	'use strict';

	var mediaFrame;
	var minServices = 6;

	function reindexServiceRows($list) {
		var tabIndex = $list.data('tab-index');

		$list.find('.sln-os-admin__service-row').each(function (serviceIndex) {
			$(this)
				.find('[name*="[services]"]')
				.each(function () {
					this.name = this.name.replace(
						/\[tabs\]\[\d+\]\[services\]\[\d+\]/,
						'[tabs][' + tabIndex + '][services][' + serviceIndex + ']'
					);
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
				library: { type: ['image/svg+xml', 'image/webp', 'image'] },
				multiple: false,
			});

			mediaFrame.on('select', function () {
				var attachment = mediaFrame.state().get('selection').first().toJSON();
				$input.val(attachment.id);

				if (attachment.subtype === 'svg+xml' || attachment.url.slice(-4) === '.svg') {
					$preview.html('<img class="sln-os-admin__media-thumb" src="' + attachment.url + '" alt="" />');
				} else {
					$preview.html('<img class="sln-os-admin__media-thumb" src="' + (attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url) + '" alt="" />');
				}
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

	function bindResultTypeToggle() {
		$('.sln-os-admin__result-type').off('change').on('change', function () {
			var $row = $(this).closest('.sln-os-admin__result-row');
			var type = $(this).val();

			$row.find('.sln-os-admin__result-fields--number').prop('hidden', type !== 'number');
			$row.find('.sln-os-admin__result-fields--logo').prop('hidden', type !== 'logo');
		});
	}

	function initOurServicesAdmin() {
		$('.sln-os-admin__media-field').each(function () {
			bindMediaField($(this));
		});

		$('.sln-os-admin__services-list').each(function () {
			var $list = $(this);

			$list.sortable({
				handle: '.sln-os-admin__drag-handle',
				axis: 'y',
				update: function () {
					reindexServiceRows($list);
				},
			});
		});

		$(document).on('click', '.sln-os-admin__add-service', function (event) {
			event.preventDefault();

			var tabIndex = $(this).data('tab-index');
			var $list = $('.sln-os-admin__services-list[data-tab-index="' + tabIndex + '"]');
			var template = $('#sln-os-service-row-template').html();
			var index = $list.find('.sln-os-admin__service-row').length;
			var html = template
				.replace(/\{\{tabIndex\}\}/g, tabIndex)
				.replace(/\{\{index\}\}/g, index);

			$list.append(html);
			bindMediaField($list.find('.sln-os-admin__service-row').last().find('.sln-os-admin__media-field'));
		});

		$(document).on('click', '.sln-os-admin__remove-row', function (event) {
			event.preventDefault();

			var $list = $(this).closest('.sln-os-admin__services-list');

			if ($list.find('.sln-os-admin__service-row').length <= minServices) {
				window.alert('Each tab must keep at least ' + minServices + ' service items.');
				return;
			}

			$(this).closest('.sln-os-admin__service-row').remove();
			reindexServiceRows($list);
		});

		bindResultTypeToggle();
	}

	$(initOurServicesAdmin);
})(jQuery);
