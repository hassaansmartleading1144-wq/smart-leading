/**
 * Founders page meta box: tabs, photo pickers, course rows.
 */
(function ($) {
	'use strict';

	$(function () {
		var $box = $('.sfa');
		if (!$box.length) {
			return;
		}

		// Tabs.
		$box.on('click', '[data-sfa-tab]', function () {
			var key = $(this).data('sfa-tab');
			$box.find('[data-sfa-tab]').attr('aria-selected', 'false');
			$(this).attr('aria-selected', 'true');
			$box.find('[data-sfa-panel]').attr('hidden', true);
			$box.find('[data-sfa-panel="' + key + '"]').removeAttr('hidden');
		});

		// Photo picker.
		$box.on('click', '[data-sfa-select]', function (e) {
			e.preventDefault();
			var $wrap = $(this).closest('[data-sfa-media]');
			var frame = wp.media({
				title: 'Choose photo',
				button: { text: 'Use this photo' },
				library: { type: ['image'] },
				multiple: false
			});
			frame.on('select', function () {
				var a = frame.state().get('selection').first().toJSON();
				var url = a.sizes && a.sizes.medium ? a.sizes.medium.url : a.url;
				$wrap.find('[data-sfa-id]').val(a.id);
				$wrap.find('[data-sfa-preview]').text('').css('background-image', 'url(' + url + ')');
				$wrap.find('[data-sfa-remove]').removeAttr('hidden');
			});
			frame.open();
		});

		$box.on('click', '[data-sfa-remove]', function (e) {
			e.preventDefault();
			var $wrap = $(this).closest('[data-sfa-media]');
			$wrap.find('[data-sfa-id]').val('0');
			$wrap.find('[data-sfa-preview]').css('background-image', '').text('No photo');
			$(this).attr('hidden', true);
		});

		// Courses.
		$box.on('click', '[data-sfa-add-course]', function (e) {
			e.preventDefault();
			var $panel = $(this).closest('[data-sfa-panel]');
			var $list = $panel.find('[data-sfa-courses]');
			var next = parseInt($list.attr('data-next'), 10) || 0;
			var html = $panel.find('[data-sfa-course-template]').html().replace(/__CI__/g, String(next));
			$list.attr('data-next', next + 1).append(html);
			$list.find('[data-sfa-course]').last().find('input').first().trigger('focus');
		});

		$box.on('click', '[data-sfa-remove-course]', function (e) {
			e.preventDefault();
			$(this).closest('[data-sfa-course]').remove();
		});
	});
})(jQuery);
