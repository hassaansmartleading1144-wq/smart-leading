(function ($) {
	'use strict';

	function takeawayRow() {
		return (
			'<p class="sln-repeater-row">' +
			'<input type="text" class="widefat" name="sln_blog_takeaways[]" value="" /> ' +
			'<button type="button" class="button sln-repeater-remove">Remove</button>' +
			'</p>'
		);
	}

	function faqRow() {
		return (
			'<div class="sln-repeater-row" style="margin-bottom:12px;padding:12px;border:1px solid #dcdcde;background:#fff;">' +
			'<p><label>Question<br /><input type="text" class="widefat" name="sln_blog_faq_q[]" value="" /></label></p>' +
			'<p><label>Answer<br /><textarea class="widefat" rows="3" name="sln_blog_faq_a[]"></textarea></label></p>' +
			'<button type="button" class="button sln-repeater-remove">Remove FAQ</button>' +
			'</div>'
		);
	}

	$(document).on('click', '[data-sln-add]', function () {
		var target = $($(this).data('sln-add'));
		var tpl = $(this).data('sln-tpl');
		target.append(tpl === 'faq' ? faqRow() : takeawayRow());
	});

	$(document).on('click', '.sln-repeater-remove', function () {
		var wrap = $(this).closest('[data-sln-repeater]');
		$(this).closest('.sln-repeater-row').remove();
		if (!wrap.children('.sln-repeater-row').length) {
			wrap.append(wrap.is('#sln_blog_faqs') ? faqRow() : takeawayRow());
		}
	});

	var frame;
	$('#sln_blog_reviewer_image_btn').on('click', function (e) {
		e.preventDefault();
		if (frame) {
			frame.open();
			return;
		}
		frame = wp.media({
			title: 'Select reviewer image',
			button: { text: 'Use image' },
			multiple: false
		});
		frame.on('select', function () {
			var att = frame.state().get('selection').first().toJSON();
			$('#sln_blog_reviewer_image').val(att.id);
			var src = att.sizes && att.sizes.thumbnail ? att.sizes.thumbnail.url : att.url;
			$('#sln_blog_reviewer_image_preview').html(
				'<img src="' + src + '" alt="" style="width:48px;height:48px;object-fit:cover;border-radius:50%;" />'
			);
		});
		frame.open();
	});

	$('#sln_blog_reviewer_image_clear').on('click', function (e) {
		e.preventDefault();
		$('#sln_blog_reviewer_image').val('');
		$('#sln_blog_reviewer_image_preview').empty();
	});
})(jQuery);
