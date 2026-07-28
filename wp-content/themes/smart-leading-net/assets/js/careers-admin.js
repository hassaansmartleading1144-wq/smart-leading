/**
 * Smart Leading Net — Careers page admin
 */
(function ($) {
	'use strict';

	function bindMediaField($field) {
		if ($field.data('bound')) {
			return;
		}

		$field.data('bound', true);

		var $input = $field.find('.sln-os-admin__media-id');
		var $preview = $field.find('.sln-os-admin__media-preview');

		$field.find('.sln-os-admin__media-select').on('click', function (event) {
			event.preventDefault();

			var frame = wp.media({
				title: 'Select Image',
				button: { text: 'Use image' },
				multiple: false,
			});

			frame.on('select', function () {
				var attachment = frame.state().get('selection').first().toJSON();
				$input.val(attachment.id);
				$preview.html(
					'<img class="sln-os-admin__media-thumb" src="' +
						(attachment.sizes && attachment.sizes.thumbnail
							? attachment.sizes.thumbnail.url
							: attachment.url) +
						'" alt="" />'
				);
			});

			frame.open();
		});

		$field.find('.sln-os-admin__media-remove').on('click', function (event) {
			event.preventDefault();
			$input.val('0');
			$preview.empty();
		});
	}

	function syncTemplateVisibility() {
		var config = window.slnCareersAdmin || {};
		var $template = $('#page_template');
		var current = $template.length ? $template.val() : config.currentTemplate || '';
		var isTarget = current === config.template;

		$('#sln_careers_settings, #sln_careers_media').toggle(isTarget);
	}

	$(function () {
		$('.sln-os-admin__media-field').each(function () {
			bindMediaField($(this));
		});

		syncTemplateVisibility();
		$(document).on('change', '#page_template', syncTemplateVisibility);
	});
})(jQuery);
