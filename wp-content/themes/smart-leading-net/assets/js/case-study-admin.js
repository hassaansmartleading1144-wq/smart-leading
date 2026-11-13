/* Case Study admin: tabs, repeaters, media pickers. */
(function ($) {
	'use strict';
	var $box = $('.sln-csa');
	if (!$box.length) { return; }

	$box.on('click', '.sln-csa__tabs button', function () {
		var tab = $(this).data('tab');
		$box.find('.sln-csa__tabs button').removeClass('is-active');
		$(this).addClass('is-active');
		$box.find('.sln-csa__panel').removeClass('is-active').filter('[data-panel="' + tab + '"]').addClass('is-active');
	});

	$box.on('click', '.sln-csa__add', function () {
		var $rep = $(this).closest('.sln-csa__repeater');
		var html = $rep.children('.sln-csa__tpl').html().replace(/__i__/g, 'n' + Date.now());
		$rep.children('.sln-csa__rows').append(html);
	});

	$box.on('click', '.sln-csa__row-remove', function () {
		$(this).closest('.sln-csa__row').remove();
	});

	if ($.fn.sortable) {
		$box.find('.sln-csa__rows').sortable({ handle: '.sln-csa__row-handle', axis: 'y' });
	}

	$box.on('click', '.sln-csa__media-select', function (e) {
		e.preventDefault();
		var $field = $(this).closest('.sln-csa__media');
		var frame = wp.media({ title: 'Select image', button: { text: 'Use this image' }, library: { type: 'image' }, multiple: false });
		frame.on('select', function () {
			var a = frame.state().get('selection').first().toJSON();
			var url = a.sizes && a.sizes.medium ? a.sizes.medium.url : a.url;
			$field.find('.sln-csa__media-id').val(a.id);
			$field.find('.sln-csa__media-preview').html($('<img>', { src: url, alt: '' }));
		});
		frame.open();
	});

	$box.on('click', '.sln-csa__media-remove', function (e) {
		e.preventDefault();
		var $field = $(this).closest('.sln-csa__media');
		$field.find('.sln-csa__media-id').val('');
		$field.find('.sln-csa__media-preview').empty();
	});
})(jQuery);
