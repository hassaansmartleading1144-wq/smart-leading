/**
 * Careers page — jobs repeater admin.
 */
(function ($) {
	'use strict';

	function reindex($list) {
		$list.find('.sln-careers-jobs-admin__row').each(function (index) {
			$(this)
				.find('[name^="sln_careers_jobs"]')
				.each(function () {
			this.name = this.name.replace(/sln_careers_jobs\[(?:\d+|__INDEX__)\]/, 'sln_careers_jobs[' + index + ']');
				});
		});
	}

	function updateLabel($row) {
		var title = $.trim($row.find('.sln-careers-jobs-admin__title').val() || '');
		$row.find('.sln-careers-jobs-admin__label').text(title || 'New Job');
	}

	function toggleBox() {
		var $box = $('#sln_careers_jobs');
		var target = window.slnCareersJobsAdmin && slnCareersJobsAdmin.template;
		var selected = $('#page_template').val() || '';
		var saved = (window.slnCareersJobsAdmin && slnCareersJobsAdmin.currentTemplate) || '';
		var isCareers = selected === target || (!selected && saved === target) || !!(window.slnCareersJobsAdmin && slnCareersJobsAdmin.isTargetPage && selected === target);

		if (!selected && window.slnCareersJobsAdmin && slnCareersJobsAdmin.isTargetPage) {
			isCareers = true;
		}

		$box.toggle(!!isCareers);
	}

	$(function () {
		var $wrap = $('.sln-careers-jobs-admin');

		if (!$wrap.length) {
			return;
		}

		var $list = $wrap.find('.sln-careers-jobs-admin__list');
		var $template = $wrap.find('.sln-careers-jobs-admin__template');

		$list.sortable({
			handle: '.sln-careers-jobs-admin__handle',
			items: '.sln-careers-jobs-admin__row',
			update: function () {
				reindex($list);
			},
		});

		$wrap.on('click', '.sln-careers-jobs-admin__add', function (event) {
			event.preventDefault();
			var html = $template.html().replace(/__INDEX__/g, String($list.children().length));
			var $row = $(html);
			$list.append($row);
			reindex($list);
			updateLabel($row);
		});

		$wrap.on('click', '.sln-careers-jobs-admin__remove', function (event) {
			event.preventDefault();
			$(this).closest('.sln-careers-jobs-admin__row').remove();
			reindex($list);
		});

		$wrap.on('input', '.sln-careers-jobs-admin__title', function () {
			updateLabel($(this).closest('.sln-careers-jobs-admin__row'));
		});

		$('#page_template').on('change', toggleBox);
		toggleBox();
	});
})(jQuery);
