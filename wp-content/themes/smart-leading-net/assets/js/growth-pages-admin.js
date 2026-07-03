/**
 * Smart Leading Net — Growth Pages admin repeaters
 */
(function ($) {
	'use strict';

	function syncAllEditors() {
		// TinyMCE stores content in the iframe until triggerSave() copies it to the textarea.
		// Without this, Visual tab edits save stale textarea values (looks like content "reverts").
		try {
			if (typeof window.tinymce !== 'undefined' && typeof window.tinymce.triggerSave === 'function') {
				window.tinymce.triggerSave();
			}

			if (window.wp && wp.editor && typeof wp.editor.save === 'function') {
				$('.sln-gp-admin__wysiwyg[id]').each(function () {
					try {
						wp.editor.save(this.id);
					} catch (editorError) {
						// Skip editors that were removed from the DOM (e.g. deleted repeater row).
					}
				});
			}
		} catch (syncError) {
			// Never block the native Update/Publish submit because one editor failed to sync.
		}
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

	function bindMoveControls($list, rowSelector, namePrefix) {
		$list.on('click', '.sln-gp-admin__move-up, .sln-gp-admin__step-move-up, .sln-gp-admin__result-move-up, .sln-gp-admin__how-work-tab-move-up, .sln-gp-admin__gs-move-up, .sln-gp-admin__cs-move-up, .sln-gp-admin__wc-move-up, .sln-gp-admin__pp-move-up, .sln-gp-admin__tm-stat-move-up, .sln-gp-admin__tm-review-move-up', function (event) {
			if (!$(event.target).closest($list).length && !$(this).closest($list).length) {
				return;
			}

			event.preventDefault();
			syncAllEditors();

			var $row = $(this).closest(rowSelector);
			var $prev = $row.prev(rowSelector);

			if ($prev.length) {
				$row.insertBefore($prev);
				reindexRepeater($list, rowSelector, namePrefix);
			}
		});

		$list.on('click', '.sln-gp-admin__move-down, .sln-gp-admin__step-move-down, .sln-gp-admin__result-move-down, .sln-gp-admin__how-work-tab-move-down, .sln-gp-admin__gs-move-down, .sln-gp-admin__cs-move-down, .sln-gp-admin__wc-move-down, .sln-gp-admin__pp-move-down, .sln-gp-admin__tm-stat-move-down, .sln-gp-admin__tm-review-move-down', function (event) {
			event.preventDefault();
			syncAllEditors();

			var $row = $(this).closest(rowSelector);
			var $next = $row.next(rowSelector);

			if ($next.length) {
				$row.insertAfter($next);
				reindexRepeater($list, rowSelector, namePrefix);
			}
		});
	}

	function getRowEditorId($row) {
		return $row.find('.sln-gp-admin__editor-field textarea[id]').first().attr('id');
	}

	function removeRowEditor($row) {
		$row.find('.sln-gp-admin__editor-field textarea[id]').each(function () {
			if (this.id && window.wp && wp.editor && wp.editor.remove) {
				wp.editor.remove(this.id);
			}
		});
	}

	function initDynamicEditor(editorId) {
		if (!editorId || !window.wp || !wp.editor || !wp.editor.initialize) {
			return;
		}

		if (window.tinymce && window.tinymce.get(editorId)) {
			return;
		}

		wp.editor.initialize(
			editorId,
			window.slnGrowthPagesAdmin ? window.slnGrowthPagesAdmin.editorSettings : {}
		);
	}

	function getCardTemplate(index, editorId) {
		return (
			'<div class="sln-os-admin__repeatable-row sln-gp-admin__card-row">' +
				'<div class="sln-gp-admin__card-controls">' +
					'<button type="button" class="button button-small sln-gp-admin__move-up">Move Up</button>' +
					'<button type="button" class="button button-small sln-gp-admin__move-down">Move Down</button>' +
				'</div>' +
				'<div class="sln-os-admin__repeatable-fields sln-gp-admin__card-fields">' +
					'<label><span class="sln-os-admin__field-label">Card Number</span>' +
					'<input type="text" class="small-text" name="sln_gp_services_cards[' + index + '][number]" value="" placeholder="01" /></label>' +
					'<label><span class="sln-os-admin__field-label">Service Title</span>' +
					'<input type="text" class="regular-text" name="sln_gp_services_cards[' + index + '][title]" value="" /></label>' +
					'<label class="sln-gp-admin__field-full sln-gp-admin__editor-field">' +
					'<span class="sln-os-admin__field-label">Service Description</span>' +
					'<textarea id="' + editorId + '" class="sln-gp-admin__wysiwyg large-text" rows="8" name="sln_gp_services_cards[' + index + '][description]"></textarea>' +
					'</label>' +
					'<label><span class="sln-os-admin__field-label">Bullet Point 1</span>' +
					'<input type="text" class="large-text" name="sln_gp_services_cards[' + index + '][bullet_1]" value="" /></label>' +
					'<label><span class="sln-os-admin__field-label">Bullet Point 2</span>' +
					'<input type="text" class="large-text" name="sln_gp_services_cards[' + index + '][bullet_2]" value="" /></label>' +
					'<label><span class="sln-os-admin__field-label">Bullet Point 3</span>' +
					'<input type="text" class="large-text" name="sln_gp_services_cards[' + index + '][bullet_3]" value="" /></label>' +
					'<label><span class="sln-os-admin__field-label">Bullet Point 4</span>' +
					'<input type="text" class="large-text" name="sln_gp_services_cards[' + index + '][bullet_4]" value="" /></label>' +
					'<label><span class="sln-os-admin__field-label">Active Card</span>' +
					'<select name="sln_gp_services_cards[' + index + '][active]">' +
					'<option value="1" selected>Yes</option><option value="0">No</option></select></label>' +
				'</div>' +
				'<button type="button" class="button-link-delete sln-gp-admin__remove-card">Remove Card</button>' +
			'</div>'
		);
	}

	function getStepTemplate(index, editorId) {
		return (
			'<div class="sln-os-admin__repeatable-row sln-gp-admin__step-row">' +
				'<div class="sln-gp-admin__card-controls">' +
					'<button type="button" class="button button-small sln-gp-admin__step-move-up">Move Up</button>' +
					'<button type="button" class="button button-small sln-gp-admin__step-move-down">Move Down</button>' +
				'</div>' +
				'<div class="sln-os-admin__repeatable-fields sln-gp-admin__card-fields">' +
					'<label><span class="sln-os-admin__field-label">Step Number</span>' +
					'<input type="text" class="small-text" name="sln_gp_client_story_steps[' + index + '][number]" value="" placeholder="01" /></label>' +
					'<label><span class="sln-os-admin__field-label">Step Title</span>' +
					'<input type="text" class="large-text" name="sln_gp_client_story_steps[' + index + '][title]" value="" /></label>' +
					'<label class="sln-gp-admin__field-full sln-gp-admin__editor-field">' +
					'<span class="sln-os-admin__field-label">Step Description</span>' +
					'<textarea id="' + editorId + '" class="sln-gp-admin__wysiwyg large-text" rows="8" name="sln_gp_client_story_steps[' + index + '][description]"></textarea>' +
					'</label>' +
				'</div>' +
				'<button type="button" class="button-link-delete sln-gp-admin__remove-step">Remove Step</button>' +
			'</div>'
		);
	}

	function getResultTemplate(index) {
		return (
			'<div class="sln-os-admin__repeatable-row sln-gp-admin__result-row">' +
				'<div class="sln-gp-admin__card-controls">' +
					'<button type="button" class="button button-small sln-gp-admin__result-move-up">Move Up</button>' +
					'<button type="button" class="button button-small sln-gp-admin__result-move-down">Move Down</button>' +
				'</div>' +
				'<div class="sln-os-admin__repeatable-fields sln-gp-admin__card-fields">' +
					'<label><span class="sln-os-admin__field-label">Metric</span>' +
					'<input type="text" class="large-text" name="sln_gp_client_story_results[' + index + '][metric]" value="" /></label>' +
					'<label><span class="sln-os-admin__field-label">Before SLS</span>' +
					'<input type="text" class="large-text" name="sln_gp_client_story_results[' + index + '][before]" value="" /></label>' +
					'<label><span class="sln-os-admin__field-label">After SLS</span>' +
					'<input type="text" class="large-text" name="sln_gp_client_story_results[' + index + '][after]" value="" /></label>' +
				'</div>' +
				'<button type="button" class="button-link-delete sln-gp-admin__remove-result">Remove Row</button>' +
			'</div>'
		);
	}

	function initServiceCardsAdmin() {
		var $list = $('.sln-gp-admin__cards-list');

		if (!$list.length) {
			return;
		}

		bindMoveControls($list, '.sln-gp-admin__card-row', 'sln_gp_services_cards');

		$(document).on('click', '.sln-gp-admin__add-card', function (event) {
			event.preventDefault();
			var index = $list.find('.sln-gp-admin__card-row').length;
			var editorId = 'sln_gp_service_description_' + Date.now();

			$list.append(getCardTemplate(index, editorId));
			initDynamicEditor(editorId);
		});

		$(document).on('click', '.sln-gp-admin__remove-card', function (event) {
			event.preventDefault();
			var $row = $(this).closest('.sln-gp-admin__card-row');

			removeRowEditor($row);
			$row.remove();
			reindexRepeater($list, '.sln-gp-admin__card-row', 'sln_gp_services_cards');
		});
	}

	function initClientStoryAdmin() {
		var $stepsList = $('.sln-gp-admin__steps-list');
		var $resultsList = $('.sln-gp-admin__results-list');

		if ($stepsList.length) {
			bindMoveControls($stepsList, '.sln-gp-admin__step-row', 'sln_gp_client_story_steps');

			$(document).on('click', '.sln-gp-admin__add-step', function (event) {
				event.preventDefault();
				var index = $stepsList.find('.sln-gp-admin__step-row').length;
				var editorId = 'sln_gp_client_story_step_description_' + Date.now();

				$stepsList.append(getStepTemplate(index, editorId));
				initDynamicEditor(editorId);
			});

			$(document).on('click', '.sln-gp-admin__remove-step', function (event) {
				event.preventDefault();
				var $row = $(this).closest('.sln-gp-admin__step-row');

				removeRowEditor($row);
				$row.remove();
				reindexRepeater($stepsList, '.sln-gp-admin__step-row', 'sln_gp_client_story_steps');
			});
		}

		if ($resultsList.length) {
			bindMoveControls($resultsList, '.sln-gp-admin__result-row', 'sln_gp_client_story_results');

			$(document).on('click', '.sln-gp-admin__add-result', function (event) {
				event.preventDefault();
				var index = $resultsList.find('.sln-gp-admin__result-row').length;
				$resultsList.append(getResultTemplate(index));
			});

			$(document).on('click', '.sln-gp-admin__remove-result', function (event) {
				event.preventDefault();
				$(this).closest('.sln-gp-admin__result-row').remove();
				reindexRepeater($resultsList, '.sln-gp-admin__result-row', 'sln_gp_client_story_results');
			});
		}
	}

	function getActivityRowTemplate(tabIndex, actIndex) {
		return (
			'<div class="sln-os-admin__repeatable-row sln-gp-admin__activity-row">' +
				'<div class="sln-gp-admin__card-controls">' +
					'<button type="button" class="button button-small sln-gp-admin__activity-move-up">Move Up</button>' +
					'<button type="button" class="button button-small sln-gp-admin__activity-move-down">Move Down</button>' +
				'</div>' +
				'<label><span class="sln-os-admin__field-label">Activity Text</span>' +
				'<input type="text" class="large-text" name="sln_gp_how_work_tabs[' + tabIndex + '][activities][' + actIndex + ']" value="" /></label>' +
				'<button type="button" class="button-link-delete sln-gp-admin__remove-activity">Remove Activity</button>' +
			'</div>'
		);
	}

	function reindexActivities($list) {
		var tabIndex = $list.data('tab-index');

		$list.find('.sln-gp-admin__activity-row').each(function (actIndex) {
			$(this)
				.find('input[name*="[activities]"]')
				.each(function () {
					this.name = 'sln_gp_how_work_tabs[' + tabIndex + '][activities][' + actIndex + ']';
				});
		});
	}

	function getHowWorkTabTemplate(index, editorId, cardEditorId) {
		var statFields = '';
		var i;

		for (i = 1; i <= 3; i += 1) {
			statFields +=
				'<label><span class="sln-os-admin__field-label">Stat ' + i + ' Number</span>' +
				'<input type="text" class="regular-text" name="sln_gp_how_work_tabs[' + index + '][stat_' + i + '_number]" value="" /></label>' +
				'<label><span class="sln-os-admin__field-label">Stat ' + i + ' Label</span>' +
				'<input type="text" class="large-text" name="sln_gp_how_work_tabs[' + index + '][stat_' + i + '_label]" value="" /></label>';
		}

		return (
			'<div class="sln-os-admin__repeatable-row sln-gp-admin__how-work-tab-row">' +
				'<div class="sln-gp-admin__card-controls">' +
					'<button type="button" class="button button-small sln-gp-admin__how-work-tab-move-up">Move Up</button>' +
					'<button type="button" class="button button-small sln-gp-admin__how-work-tab-move-down">Move Down</button>' +
				'</div>' +
				'<div class="sln-os-admin__repeatable-fields sln-gp-admin__card-fields">' +
					'<label><span class="sln-os-admin__field-label">Tab Label</span>' +
					'<input type="text" class="regular-text" name="sln_gp_how_work_tabs[' + index + '][tab_label]" value="" /></label>' +
					'<label><span class="sln-os-admin__field-label">Content Heading</span>' +
					'<input type="text" class="large-text" name="sln_gp_how_work_tabs[' + index + '][content_heading]" value="" /></label>' +
					'<label class="sln-gp-admin__field-full sln-gp-admin__editor-field">' +
					'<span class="sln-os-admin__field-label">Content Description</span>' +
					'<textarea id="' + editorId + '" class="sln-gp-admin__wysiwyg large-text" rows="8" name="sln_gp_how_work_tabs[' + index + '][content_description]"></textarea>' +
					'</label>' +
					'<div class="sln-os-admin__subsection sln-gp-admin__activities-wrap">' +
						'<h4>Key Activities</h4>' +
						'<div class="sln-os-admin__repeatable sln-gp-admin__activities-list" data-tab-index="' + index + '"></div>' +
						'<p><button type="button" class="button button-secondary sln-gp-admin__add-activity" data-tab-index="' + index + '">Add Activity</button></p>' +
					'</div>' +
					'<label><span class="sln-os-admin__field-label">Card Heading</span>' +
					'<input type="text" class="large-text" name="sln_gp_how_work_tabs[' + index + '][card_heading]" value="" /></label>' +
					'<label class="sln-gp-admin__field-full sln-gp-admin__editor-field">' +
					'<span class="sln-os-admin__field-label">Card Description</span>' +
					'<textarea id="' + cardEditorId + '" class="sln-gp-admin__wysiwyg large-text" rows="6" name="sln_gp_how_work_tabs[' + index + '][card_description]"></textarea>' +
					'</label>' +
					statFields +
					'<label><span class="sln-os-admin__field-label">Active Tab</span>' +
					'<select name="sln_gp_how_work_tabs[' + index + '][active]">' +
					'<option value="1" selected>Yes</option><option value="0">No</option></select></label>' +
				'</div>' +
				'<button type="button" class="button-link-delete sln-gp-admin__remove-how-work-tab">Remove Tab</button>' +
			'</div>'
		);
	}

	function initHowWorkActivitiesAdmin() {
		$(document).on('click', '.sln-gp-admin__add-activity', function (event) {
			event.preventDefault();
			var tabIndex = $(this).data('tab-index');
			var $list = $(this).closest('.sln-gp-admin__activities-wrap').find('.sln-gp-admin__activities-list');
			var actIndex = $list.find('.sln-gp-admin__activity-row').length;

			$list.append(getActivityRowTemplate(tabIndex, actIndex));
		});

		$(document).on('click', '.sln-gp-admin__remove-activity', function (event) {
			event.preventDefault();
			var $row = $(this).closest('.sln-gp-admin__activity-row');
			var $list = $row.closest('.sln-gp-admin__activities-list');

			$row.remove();
			reindexActivities($list);
		});

		$(document).on('click', '.sln-gp-admin__activity-move-up', function (event) {
			event.preventDefault();
			var $row = $(this).closest('.sln-gp-admin__activity-row');
			var $list = $row.closest('.sln-gp-admin__activities-list');
			var $prev = $row.prev('.sln-gp-admin__activity-row');

			if ($prev.length) {
				$row.insertBefore($prev);
				reindexActivities($list);
			}
		});

		$(document).on('click', '.sln-gp-admin__activity-move-down', function (event) {
			event.preventDefault();
			var $row = $(this).closest('.sln-gp-admin__activity-row');
			var $list = $row.closest('.sln-gp-admin__activities-list');
			var $next = $row.next('.sln-gp-admin__activity-row');

			if ($next.length) {
				$row.insertAfter($next);
				reindexActivities($list);
			}
		});
	}

	function initHowWorkAdmin() {
		var $tabsList = $('.sln-gp-admin__how-work-tabs-list');

		if (!$tabsList.length) {
			return;
		}

		bindMoveControls($tabsList, '.sln-gp-admin__how-work-tab-row', 'sln_gp_how_work_tabs');

		$(document).on('click', '.sln-gp-admin__add-how-work-tab', function (event) {
			event.preventDefault();
			var index = $tabsList.find('.sln-gp-admin__how-work-tab-row').length;
			var editorId = 'sln_gp_how_work_tab_description_' + Date.now();
			var cardEditorId = 'sln_gp_how_work_card_description_' + Date.now();

			$tabsList.append(getHowWorkTabTemplate(index, editorId, cardEditorId));
			initDynamicEditor(editorId);
			initDynamicEditor(cardEditorId);
		});

		$(document).on('click', '.sln-gp-admin__remove-how-work-tab', function (event) {
			event.preventDefault();
			var $row = $(this).closest('.sln-gp-admin__how-work-tab-row');

			removeRowEditor($row);
			$row.remove();
			reindexRepeater($tabsList, '.sln-gp-admin__how-work-tab-row', 'sln_gp_how_work_tabs');
		});
	}

	function initGrowthServicesAdmin() {
		var $cardsList = $('.sln-gp-admin__gs-cards-list');
		var gsMediaFrame;

		if (!$cardsList.length) {
			return;
		}

		function bindGrowthServicesMediaField($field) {
			if ($field.data('gs-media-bound')) {
				return;
			}

			$field.data('gs-media-bound', true);

			$field.find('.sln-os-admin__media-select').on('click', function (event) {
				event.preventDefault();

				var $wrap = $(this).closest('.sln-os-admin__media-field');
				var $input = $wrap.find('.sln-os-admin__media-id');
				var $preview = $wrap.find('.sln-os-admin__media-preview');

				if (gsMediaFrame) {
					gsMediaFrame.close();
				}

				gsMediaFrame = wp.media({
					title: 'Select SVG Icon',
					button: { text: 'Use this SVG' },
					library: { type: 'image/svg+xml' },
					multiple: false,
				});

				gsMediaFrame.on('select', function () {
					var attachment = gsMediaFrame.state().get('selection').first().toJSON();
					$input.val(attachment.id);
					$preview.html('<img class="sln-os-admin__media-thumb" src="' + attachment.url + '" alt="" />');
				});

				gsMediaFrame.open();
			});

			$field.find('.sln-os-admin__media-remove').on('click', function (event) {
				event.preventDefault();
				var $wrap = $(this).closest('.sln-os-admin__media-field');
				$wrap.find('.sln-os-admin__media-id').val('0');
				$wrap.find('.sln-os-admin__media-preview').empty();
			});
		}

		function bindGrowthServicesMediaFields($context) {
			$context.find('.sln-os-admin__media-field').each(function () {
				bindGrowthServicesMediaField($(this));
			});
		}

		function getGrowthServicesCardTemplate(index, editorId) {
			return (
				'<div class="sln-os-admin__repeatable-row sln-gp-admin__gs-card-row">' +
					'<div class="sln-gp-admin__card-controls">' +
						'<button type="button" class="button button-small sln-gp-admin__gs-move-up">Move Up</button>' +
						'<button type="button" class="button button-small sln-gp-admin__gs-move-down">Move Down</button>' +
					'</div>' +
					'<div class="sln-os-admin__repeatable-fields sln-gp-admin__card-fields">' +
						'<label class="sln-gp-admin__field-full">' +
						'<span class="sln-os-admin__field-label">SVG Icon Upload</span>' +
						'<div class="sln-os-admin__media-field" data-mime-hint="SVG">' +
							'<input type="hidden" name="sln_gp_growth_services_cards[' + index + '][icon_id]" value="0" class="sln-os-admin__media-id" />' +
							'<div class="sln-os-admin__media-preview"></div>' +
							'<p class="sln-os-admin__media-actions">' +
								'<button type="button" class="button sln-os-admin__media-select">Select File</button>' +
								'<button type="button" class="button-link-delete sln-os-admin__media-remove">Remove</button>' +
							'</p>' +
						'</div></label>' +
						'<label><span class="sln-os-admin__field-label">Card Title</span>' +
						'<input type="text" class="large-text" name="sln_gp_growth_services_cards[' + index + '][title]" value="" /></label>' +
						'<label class="sln-gp-admin__field-full sln-gp-admin__editor-field">' +
						'<span class="sln-os-admin__field-label">Card Description</span>' +
						'<textarea id="' + editorId + '" class="sln-gp-admin__wysiwyg large-text" rows="6" name="sln_gp_growth_services_cards[' + index + '][description]"></textarea></label>' +
						'<label><span class="sln-os-admin__field-label">Active Card</span>' +
						'<select name="sln_gp_growth_services_cards[' + index + '][active]">' +
						'<option value="1" selected>Yes</option><option value="0">No</option></select></label>' +
					'</div>' +
					'<button type="button" class="button-link-delete sln-gp-admin__gs-remove-card">Remove Card</button>' +
				'</div>'
			);
		}

		bindGrowthServicesMediaFields($cardsList);
		bindMoveControls($cardsList, '.sln-gp-admin__gs-card-row', 'sln_gp_growth_services_cards');

		$(document).on('click', '.sln-gp-admin__add-gs-card', function (event) {
			event.preventDefault();
			var index = $cardsList.find('.sln-gp-admin__gs-card-row').length;
			var editorId = 'growth_service_card_desc_' + Date.now();
			var $row = $(getGrowthServicesCardTemplate(index, editorId));

			$cardsList.append($row);
			bindGrowthServicesMediaField($row.find('.sln-os-admin__media-field'));
			initDynamicEditor(editorId);
		});

		$(document).on('click', '.sln-gp-admin__gs-remove-card', function (event) {
			event.preventDefault();
			var $row = $(this).closest('.sln-gp-admin__gs-card-row');

			removeRowEditor($row);
			$row.remove();
			reindexRepeater($cardsList, '.sln-gp-admin__gs-card-row', 'sln_gp_growth_services_cards');
		});
	}

	function reindexCaseStudiesTags($list) {
		var cardIndex = $list.data('card-index');

		$list.find('.sln-gp-admin__cs-tag-row').each(function (tagIndex) {
			$(this)
				.find('input[name^="sln_gp_case_studies_cards"]')
				.attr('name', 'sln_gp_case_studies_cards[' + cardIndex + '][tags][' + tagIndex + ']');
		});
	}

	function getCaseStudiesTagTemplate(cardIndex, tagIndex) {
		return (
			'<div class="sln-gp-admin__cs-tag-row">' +
				'<input type="text" class="regular-text" name="sln_gp_case_studies_cards[' + cardIndex + '][tags][' + tagIndex + ']" value="" />' +
				'<button type="button" class="button-link-delete sln-gp-admin__cs-remove-tag">Remove Tag</button>' +
			'</div>'
		);
	}

	function getCaseStudiesCardTemplate(index) {
		return (
			'<div class="sln-os-admin__repeatable-row sln-gp-admin__cs-card-row">' +
				'<div class="sln-gp-admin__card-controls">' +
					'<button type="button" class="button button-small sln-gp-admin__cs-move-up">Move Up</button>' +
					'<button type="button" class="button button-small sln-gp-admin__cs-move-down">Move Down</button>' +
				'</div>' +
				'<div class="sln-os-admin__repeatable-fields sln-gp-admin__card-fields">' +
					'<label><span class="sln-os-admin__field-label">Card Title</span>' +
					'<input type="text" class="large-text" name="sln_gp_case_studies_cards[' + index + '][title]" value="" /></label>' +
					'<label><span class="sln-os-admin__field-label">Metric Value</span>' +
					'<input type="text" class="regular-text" name="sln_gp_case_studies_cards[' + index + '][metric_value]" value="" /></label>' +
					'<label><span class="sln-os-admin__field-label">Metric Description</span>' +
					'<input type="text" class="large-text" name="sln_gp_case_studies_cards[' + index + '][metric_description]" value="" /></label>' +
					'<label class="sln-gp-admin__field-full"><span class="sln-os-admin__field-label">SVG Icon Upload</span>' +
					'<div class="sln-os-admin__media-field" data-mime-hint="SVG">' +
						'<input type="hidden" name="sln_gp_case_studies_cards[' + index + '][icon_id]" value="0" class="sln-os-admin__media-id" />' +
						'<div class="sln-os-admin__media-preview"></div>' +
						'<p class="sln-os-admin__media-actions">' +
							'<button type="button" class="button sln-os-admin__media-select">Select File</button>' +
							'<button type="button" class="button-link-delete sln-os-admin__media-remove">Remove</button>' +
						'</p>' +
					'</div></label>' +
					'<label><span class="sln-os-admin__field-label">Card Theme Color</span>' +
					'<input type="color" class="sln-gp-admin__color-picker" name="sln_gp_case_studies_cards[' + index + '][theme_color]" value="#1f4e9e" /></label>' +
					'<div class="sln-os-admin__subsection sln-gp-admin__cs-tags-wrap">' +
						'<h4>Bottom Tags</h4>' +
						'<div class="sln-os-admin__repeatable sln-gp-admin__cs-tags-list" data-card-index="' + index + '"></div>' +
						'<p><button type="button" class="button button-secondary sln-gp-admin__cs-add-tag" data-card-index="' + index + '">Add Tag</button></p>' +
					'</div>' +
					'<label><span class="sln-os-admin__field-label">Active Card</span>' +
					'<select name="sln_gp_case_studies_cards[' + index + '][active]">' +
					'<option value="1" selected>Yes</option><option value="0">No</option></select></label>' +
				'</div>' +
				'<button type="button" class="button-link-delete sln-gp-admin__cs-remove-card">Remove Card</button>' +
			'</div>'
		);
	}

	function initCaseStudiesAdmin() {
		var $cardsList = $('.sln-gp-admin__cs-cards-list');
		var csMediaFrame;

		if (!$cardsList.length) {
			return;
		}

		function bindCaseStudiesMediaField($field) {
			if ($field.data('cs-media-bound')) {
				return;
			}

			$field.data('cs-media-bound', true);

			$field.find('.sln-os-admin__media-select').on('click', function (event) {
				event.preventDefault();

				var $wrap = $(this).closest('.sln-os-admin__media-field');
				var $input = $wrap.find('.sln-os-admin__media-id');
				var $preview = $wrap.find('.sln-os-admin__media-preview');

				if (csMediaFrame) {
					csMediaFrame.close();
				}

				csMediaFrame = wp.media({
					title: 'Select SVG Icon',
					button: { text: 'Use this SVG' },
					library: { type: 'image/svg+xml' },
					multiple: false,
				});

				csMediaFrame.on('select', function () {
					var attachment = csMediaFrame.state().get('selection').first().toJSON();
					$input.val(attachment.id);
					$preview.html('<img class="sln-os-admin__media-thumb" src="' + attachment.url + '" alt="" />');
				});

				csMediaFrame.open();
			});

			$field.find('.sln-os-admin__media-remove').on('click', function (event) {
				event.preventDefault();
				var $wrap = $(this).closest('.sln-os-admin__media-field');
				$wrap.find('.sln-os-admin__media-id').val('0');
				$wrap.find('.sln-os-admin__media-preview').empty();
			});
		}

		function bindCaseStudiesMediaFields($context) {
			$context.find('.sln-os-admin__media-field').each(function () {
				bindCaseStudiesMediaField($(this));
			});
		}

		function reindexCaseStudiesCards() {
			$cardsList.find('.sln-gp-admin__cs-card-row').each(function (cardIndex) {
				$(this)
					.find('[name^="sln_gp_case_studies_cards"]')
					.not('[name*="[tags]"]')
					.each(function () {
						this.name = this.name.replace(/sln_gp_case_studies_cards\[\d+\]/, 'sln_gp_case_studies_cards[' + cardIndex + ']');
					});

				$(this).find('.sln-gp-admin__cs-tags-list').attr('data-card-index', cardIndex);
				$(this).find('.sln-gp-admin__cs-add-tag').attr('data-card-index', cardIndex);
				reindexCaseStudiesTags($(this).find('.sln-gp-admin__cs-tags-list'));
			});
		}

		bindCaseStudiesMediaFields($cardsList);
		bindMoveControls($cardsList, '.sln-gp-admin__cs-card-row', 'sln_gp_case_studies_cards');

		$cardsList.on('click', '.sln-gp-admin__cs-move-up, .sln-gp-admin__cs-move-down', function () {
			reindexCaseStudiesCards();
		});

		$(document).on('click', '.sln-gp-admin__add-cs-card', function (event) {
			event.preventDefault();
			var index = $cardsList.find('.sln-gp-admin__cs-card-row').length;
			var $row = $(getCaseStudiesCardTemplate(index));

			$cardsList.append($row);
			bindCaseStudiesMediaField($row.find('.sln-os-admin__media-field'));
		});

		$(document).on('click', '.sln-gp-admin__cs-remove-card', function (event) {
			event.preventDefault();
			$(this).closest('.sln-gp-admin__cs-card-row').remove();
			reindexCaseStudiesCards();
		});

		$(document).on('click', '.sln-gp-admin__cs-add-tag', function (event) {
			event.preventDefault();
			var cardIndex = $(this).data('card-index');
			var $list = $(this).closest('.sln-gp-admin__cs-tags-wrap').find('.sln-gp-admin__cs-tags-list');
			var tagIndex = $list.find('.sln-gp-admin__cs-tag-row').length;

			$list.append(getCaseStudiesTagTemplate(cardIndex, tagIndex));
		});

		$(document).on('click', '.sln-gp-admin__cs-remove-tag', function (event) {
			event.preventDefault();
			var $list = $(this).closest('.sln-gp-admin__cs-tags-list');

			$(this).closest('.sln-gp-admin__cs-tag-row').remove();
			reindexCaseStudiesTags($list);
		});
	}

	function getWhyChooseRowTemplate(index) {
		return (
			'<div class="sln-os-admin__repeatable-row sln-gp-admin__wc-row">' +
				'<div class="sln-gp-admin__card-controls">' +
					'<button type="button" class="button button-small sln-gp-admin__wc-move-up">Move Up</button>' +
					'<button type="button" class="button button-small sln-gp-admin__wc-move-down">Move Down</button>' +
				'</div>' +
				'<div class="sln-os-admin__repeatable-fields sln-gp-admin__card-fields">' +
					'<label class="sln-gp-admin__field-full"><span class="sln-os-admin__field-label">Feature Name</span>' +
					'<input type="text" class="large-text" name="sln_gp_why_choose_rows[' + index + '][feature]" value="" /></label>' +
					'<label><span class="sln-os-admin__field-label">Smart Leading Value</span>' +
					'<input type="text" class="regular-text" name="sln_gp_why_choose_rows[' + index + '][smart_leading]" value="check" placeholder="check" /></label>' +
					'<label><span class="sln-os-admin__field-label">In-House Value</span>' +
					'<input type="text" class="regular-text" name="sln_gp_why_choose_rows[' + index + '][in_house]" value="" placeholder="Sometimes / cross / check" /></label>' +
					'<label><span class="sln-os-admin__field-label">Agency Value</span>' +
					'<input type="text" class="regular-text" name="sln_gp_why_choose_rows[' + index + '][agency]" value="" placeholder="Limited / Varies / cross" /></label>' +
					'<p class="description">Use check, cross, or warning text such as Sometimes, Limited, Varies, or Extra Cost.</p>' +
					'<label><span class="sln-os-admin__field-label">Active Row</span>' +
					'<select name="sln_gp_why_choose_rows[' + index + '][active]">' +
					'<option value="1" selected>Yes</option><option value="0">No</option></select></label>' +
				'</div>' +
				'<button type="button" class="button-link-delete sln-gp-admin__wc-remove-row">Remove Row</button>' +
			'</div>'
		);
	}

	function initWhyChooseAdmin() {
		var $rowsList = $('.sln-gp-admin__wc-rows-list');

		if (!$rowsList.length) {
			return;
		}

		bindMoveControls($rowsList, '.sln-gp-admin__wc-row', 'sln_gp_why_choose_rows');

		$(document).on('click', '.sln-gp-admin__add-wc-row', function (event) {
			event.preventDefault();
			var index = $rowsList.find('.sln-gp-admin__wc-row').length;
			$rowsList.append(getWhyChooseRowTemplate(index));
		});

		$(document).on('click', '.sln-gp-admin__wc-remove-row', function (event) {
			event.preventDefault();
			$(this).closest('.sln-gp-admin__wc-row').remove();
			reindexRepeater($rowsList, '.sln-gp-admin__wc-row', 'sln_gp_why_choose_rows');
		});
	}

	function reindexPricePlanFeatures($list) {
		var cardIndex = $list.data('card-index');

		$list.find('.sln-gp-admin__pp-feature-row').each(function (featureIndex) {
			$(this)
				.find('input[name^="sln_gp_price_plan_cards"]')
				.attr('name', 'sln_gp_price_plan_cards[' + cardIndex + '][features][' + featureIndex + ']');
		});
	}

	function getPricePlanFeatureTemplate(cardIndex, featureIndex) {
		return (
			'<div class="sln-gp-admin__pp-feature-row">' +
				'<input type="text" class="large-text" name="sln_gp_price_plan_cards[' + cardIndex + '][features][' + featureIndex + ']" value="" />' +
				'<button type="button" class="button-link-delete sln-gp-admin__pp-remove-feature">Remove Feature</button>' +
			'</div>'
		);
	}

	function getPricePlanCardTemplate(index, editorId) {
		return (
			'<div class="sln-os-admin__repeatable-row sln-gp-admin__pp-card-row">' +
				'<div class="sln-gp-admin__card-controls">' +
					'<button type="button" class="button button-small sln-gp-admin__pp-move-up">Move Up</button>' +
					'<button type="button" class="button button-small sln-gp-admin__pp-move-down">Move Down</button>' +
				'</div>' +
				'<div class="sln-os-admin__repeatable-card__body sln-gp-admin__card__body">' +
					'<label><span class="sln-os-admin__field-label">Plan Name</span>' +
					'<input type="text" class="regular-text" name="sln_gp_price_plan_cards[' + index + '][plan_name]" value="" /></label>' +
					'<label><span class="sln-os-admin__field-label">Price</span>' +
					'<input type="text" class="regular-text" name="sln_gp_price_plan_cards[' + index + '][price]" value="" placeholder="$999" /></label>' +
					'<label><span class="sln-os-admin__field-label">Price Suffix</span>' +
					'<input type="text" class="regular-text" name="sln_gp_price_plan_cards[' + index + '][price_suffix]" value="" placeholder="/ month" /></label>' +
					'<label class="sln-gp-admin__field-full sln-gp-admin__editor-field">' +
					'<span class="sln-os-admin__field-label">Description</span>' +
					'<textarea id="' + editorId + '" class="sln-gp-admin__wysiwyg large-text" rows="4" name="sln_gp_price_plan_cards[' + index + '][description]"></textarea></label>' +
					'<div class="sln-os-admin__subsection sln-gp-admin__pp-features-wrap">' +
						'<h4>Features</h4>' +
						'<div class="sln-os-admin__repeatable sln-gp-admin__pp-features-list" data-card-index="' + index + '"></div>' +
						'<p><button type="button" class="button button-secondary sln-gp-admin__pp-add-feature" data-card-index="' + index + '">Add Feature</button></p>' +
					'</div>' +
					'<label><span class="sln-os-admin__field-label">Button Text</span>' +
					'<input type="text" class="regular-text" name="sln_gp_price_plan_cards[' + index + '][button_text]" value="" /></label>' +
					'<label><span class="sln-os-admin__field-label">Button URL</span>' +
					'<input type="url" class="large-text" name="sln_gp_price_plan_cards[' + index + '][button_url]" value="" /></label>' +
					'<label><span class="sln-os-admin__field-label">Popular Badge Text</span>' +
					'<input type="text" class="regular-text" name="sln_gp_price_plan_cards[' + index + '][badge_text]" value="MOST POPULAR" /></label>' +
					'<label><span class="sln-os-admin__field-label">Popular Badge</span>' +
					'<select name="sln_gp_price_plan_cards[' + index + '][is_popular]">' +
					'<option value="1">Yes</option><option value="0" selected>No</option></select></label>' +
					'<label><span class="sln-os-admin__field-label">Active Card</span>' +
					'<select name="sln_gp_price_plan_cards[' + index + '][active]">' +
					'<option value="1" selected>Yes</option><option value="0">No</option></select></label>' +
				'</div>' +
				'<button type="button" class="button-link-delete sln-gp-admin__pp-remove-card">Remove Card</button>' +
			'</div>'
		);
	}

	function initPricePlanAdmin() {
		var $cardsList = $('.sln-gp-admin__pp-cards-list');

		if (!$cardsList.length) {
			return;
		}

		function reindexPricePlanCards() {
			$cardsList.find('.sln-gp-admin__pp-card-row').each(function (cardIndex) {
				var $row = $(this);

				$row.find('[name^="sln_gp_price_plan_cards"]').not('[name*="[features]"]').each(function () {
					this.name = this.name.replace(/sln_gp_price_plan_cards\[\d+\]/, 'sln_gp_price_plan_cards[' + cardIndex + ']');
				});

				$row.find('.sln-gp-admin__pp-features-list').attr('data-card-index', cardIndex);
				$row.find('.sln-gp-admin__pp-add-feature').attr('data-card-index', cardIndex);
				reindexPricePlanFeatures($row.find('.sln-gp-admin__pp-features-list'));
			});
		}

		bindMoveControls($cardsList, '.sln-gp-admin__pp-card-row', 'sln_gp_price_plan_cards');

		$cardsList.on('click', '.sln-gp-admin__pp-move-up, .sln-gp-admin__pp-move-down', function () {
			reindexPricePlanCards();
		});

		$(document).on('click', '.sln-gp-admin__add-pp-card', function (event) {
			event.preventDefault();
			syncAllEditors();

			var index = $cardsList.find('.sln-gp-admin__pp-card-row').length;
			var editorId = 'sln_gp_price_plan_card_desc_' + Date.now();
			var $row = $(getPricePlanCardTemplate(index, editorId));

			$cardsList.append($row);
			initDynamicEditor(editorId);
		});

		$(document).on('click', '.sln-gp-admin__pp-remove-card', function (event) {
			event.preventDefault();
			syncAllEditors();

			var $row = $(this).closest('.sln-gp-admin__pp-card-row');

			removeRowEditor($row);
			$row.remove();
			reindexPricePlanCards();
		});

		$(document).on('click', '.sln-gp-admin__pp-add-feature', function (event) {
			event.preventDefault();
			var cardIndex = $(this).data('card-index');
			var $list = $(this).closest('.sln-gp-admin__pp-features-wrap').find('.sln-gp-admin__pp-features-list');
			var featureIndex = $list.find('.sln-gp-admin__pp-feature-row').length;

			$list.append(getPricePlanFeatureTemplate(cardIndex, featureIndex));
		});

		$(document).on('click', '.sln-gp-admin__pp-remove-feature', function (event) {
			event.preventDefault();
			var $list = $(this).closest('.sln-gp-admin__pp-features-list');

			$(this).closest('.sln-gp-admin__pp-feature-row').remove();
			reindexPricePlanFeatures($list);
		});
	}

	function getTestimonialsStatTemplate(index) {
		return (
			'<div class="sln-os-admin__repeatable-row sln-gp-admin__tm-stat-row">' +
				'<div class="sln-gp-admin__card-controls">' +
					'<button type="button" class="button button-small sln-gp-admin__tm-stat-move-up">Move Up</button>' +
					'<button type="button" class="button button-small sln-gp-admin__tm-stat-move-down">Move Down</button>' +
				'</div>' +
				'<div class="sln-os-admin__repeatable-fields sln-gp-admin__card-fields">' +
					'<label class="sln-gp-admin__field-full"><span class="sln-os-admin__field-label">Icon SVG Upload</span>' +
					'<div class="sln-os-admin__media-field" data-mime-hint="SVG">' +
						'<input type="hidden" name="sln_gp_testimonials_stats[' + index + '][icon_id]" value="0" class="sln-os-admin__media-id" />' +
						'<div class="sln-os-admin__media-preview"></div>' +
						'<p class="sln-os-admin__media-actions">' +
							'<button type="button" class="button sln-os-admin__media-select">Select File</button>' +
							'<button type="button" class="button-link-delete sln-os-admin__media-remove">Remove</button>' +
						'</p>' +
					'</div></label>' +
					'<label><span class="sln-os-admin__field-label">Number</span>' +
					'<input type="text" class="regular-text" name="sln_gp_testimonials_stats[' + index + '][counter_value]" value="" placeholder="28" /></label>' +
					'<label><span class="sln-os-admin__field-label">Number Prefix</span>' +
					'<input type="text" class="regular-text" name="sln_gp_testimonials_stats[' + index + '][counter_prefix]" value="" placeholder="$" /></label>' +
					'<label><span class="sln-os-admin__field-label">Number Suffix</span>' +
					'<input type="text" class="regular-text" name="sln_gp_testimonials_stats[' + index + '][counter_suffix]" value="" placeholder="K+" /></label>' +
					'<label><span class="sln-os-admin__field-label">Decimal Places</span>' +
					'<input type="number" min="0" max="2" step="1" class="small-text" name="sln_gp_testimonials_stats[' + index + '][counter_decimals]" value="0" /></label>' +
					'<label><span class="sln-os-admin__field-label">Label</span>' +
					'<input type="text" class="large-text" name="sln_gp_testimonials_stats[' + index + '][label]" value="" /></label>' +
				'</div>' +
				'<button type="button" class="button-link-delete sln-gp-admin__tm-remove-stat">Remove Stat</button>' +
			'</div>'
		);
	}

	function getTestimonialsReviewTemplate(index) {
		return (
			'<div class="sln-os-admin__repeatable-row sln-gp-admin__tm-review-row">' +
				'<div class="sln-gp-admin__card-controls">' +
					'<button type="button" class="button button-small sln-gp-admin__tm-review-move-up">Move Up</button>' +
					'<button type="button" class="button button-small sln-gp-admin__tm-review-move-down">Move Down</button>' +
				'</div>' +
				'<div class="sln-os-admin__repeatable-fields sln-gp-admin__card-fields">' +
					'<label><span class="sln-os-admin__field-label">Rating</span>' +
					'<input type="number" min="1" max="5" step="1" class="small-text" name="sln_gp_testimonials_reviews[' + index + '][rating]" value="5" /></label>' +
					'<label class="sln-gp-admin__field-full"><span class="sln-os-admin__field-label">Testimonial Text</span>' +
					'<textarea class="large-text" rows="4" name="sln_gp_testimonials_reviews[' + index + '][text]"></textarea></label>' +
					'<label><span class="sln-os-admin__field-label">Author Initials</span>' +
					'<input type="text" class="regular-text" name="sln_gp_testimonials_reviews[' + index + '][author_initials]" value="" /></label>' +
					'<label><span class="sln-os-admin__field-label">Author Name</span>' +
					'<input type="text" class="large-text" name="sln_gp_testimonials_reviews[' + index + '][author_name]" value="" /></label>' +
					'<label><span class="sln-os-admin__field-label">Author Title</span>' +
					'<input type="text" class="large-text" name="sln_gp_testimonials_reviews[' + index + '][author_title]" value="" /></label>' +
					'<label><span class="sln-os-admin__field-label">Active Testimonial</span>' +
					'<select name="sln_gp_testimonials_reviews[' + index + '][active]">' +
					'<option value="1" selected>Yes</option><option value="0">No</option></select></label>' +
				'</div>' +
				'<button type="button" class="button-link-delete sln-gp-admin__tm-remove-review">Remove Testimonial</button>' +
			'</div>'
		);
	}

	function initTestimonialsAdmin() {
		var $statsList = $('.sln-gp-admin__tm-stats-list');
		var $reviewsList = $('.sln-gp-admin__tm-reviews-list');
		var tmMediaFrame;

		if (!$statsList.length && !$reviewsList.length) {
			return;
		}

		function bindTestimonialsMediaField($field) {
			if ($field.data('tm-media-bound')) {
				return;
			}

			$field.data('tm-media-bound', true);

			$field.find('.sln-os-admin__media-select').on('click', function (event) {
				event.preventDefault();

				var $wrap = $(this).closest('.sln-os-admin__media-field');
				var $input = $wrap.find('.sln-os-admin__media-id');
				var $preview = $wrap.find('.sln-os-admin__media-preview');

				if (tmMediaFrame) {
					tmMediaFrame.close();
				}

				tmMediaFrame = wp.media({
					title: 'Select SVG Icon',
					button: { text: 'Use this SVG' },
					library: { type: 'image/svg+xml' },
					multiple: false,
				});

				tmMediaFrame.on('select', function () {
					var attachment = tmMediaFrame.state().get('selection').first().toJSON();
					$input.val(attachment.id);
					$preview.html('<img class="sln-os-admin__media-thumb" src="' + attachment.url + '" alt="" />');
				});

				tmMediaFrame.open();
			});

			$field.find('.sln-os-admin__media-remove').on('click', function (event) {
				event.preventDefault();
				var $wrap = $(this).closest('.sln-os-admin__media-field');
				$wrap.find('.sln-os-admin__media-id').val('0');
				$wrap.find('.sln-os-admin__media-preview').empty();
			});
		}

		function bindTestimonialsMediaFields($context) {
			$context.find('.sln-os-admin__media-field').each(function () {
				bindTestimonialsMediaField($(this));
			});
		}

		if ($statsList.length) {
			bindTestimonialsMediaFields($statsList);
			bindMoveControls($statsList, '.sln-gp-admin__tm-stat-row', 'sln_gp_testimonials_stats');

			$(document).on('click', '.sln-gp-admin__add-tm-stat', function (event) {
				event.preventDefault();
				var index = $statsList.find('.sln-gp-admin__tm-stat-row').length;
				var $row = $(getTestimonialsStatTemplate(index));

				$statsList.append($row);
				bindTestimonialsMediaField($row.find('.sln-os-admin__media-field'));
			});

			$(document).on('click', '.sln-gp-admin__tm-remove-stat', function (event) {
				event.preventDefault();
				$(this).closest('.sln-gp-admin__tm-stat-row').remove();
				reindexRepeater($statsList, '.sln-gp-admin__tm-stat-row', 'sln_gp_testimonials_stats');
			});
		}

		if ($reviewsList.length) {
			bindMoveControls($reviewsList, '.sln-gp-admin__tm-review-row', 'sln_gp_testimonials_reviews');

			$(document).on('click', '.sln-gp-admin__add-tm-review', function (event) {
				event.preventDefault();
				var index = $reviewsList.find('.sln-gp-admin__tm-review-row').length;
				$reviewsList.append(getTestimonialsReviewTemplate(index));
			});

			$(document).on('click', '.sln-gp-admin__tm-remove-review', function (event) {
				event.preventDefault();
				$(this).closest('.sln-gp-admin__tm-review-row').remove();
				reindexRepeater($reviewsList, '.sln-gp-admin__tm-review-row', 'sln_gp_testimonials_reviews');
			});
		}
	}

	function bindGrowthPageSaveSync() {
		var $postForm = $('#post');

		if (!$postForm.length) {
			return;
		}

		// Sync before native submit — mousedown/click only; never hook submit with preventDefault().
		$(document).on('mousedown.slnGpSave click.slnGpSave', '#publish, #save-post', syncAllEditors);
	}

	function initGrowthPagesAdmin() {
		bindGrowthPageSaveSync();

		initServiceCardsAdmin();
		initClientStoryAdmin();
		initHowWorkAdmin();
		initHowWorkActivitiesAdmin();
		initGrowthServicesAdmin();
		initCaseStudiesAdmin();
		initWhyChooseAdmin();
		initPricePlanAdmin();
		initTestimonialsAdmin();
	}

	$(initGrowthPagesAdmin);
})(jQuery);
