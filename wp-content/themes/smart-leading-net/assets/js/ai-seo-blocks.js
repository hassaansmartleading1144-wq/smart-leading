(function (wp) {
	'use strict';

	var registerBlockType = wp.blocks.registerBlockType;
	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var PanelBody = wp.components.PanelBody;
	var TextControl = wp.components.TextControl;
	var TextareaControl = wp.components.TextareaControl;
	var ToggleControl = wp.components.ToggleControl;
	var SelectControl = wp.components.SelectControl;
	var ServerSideRender = wp.serverSideRender;
	var __ = wp.i18n.__;

	registerBlockType('sln/ai-seo-mini-lead', {
		apiVersion: 2,
		title: __('AI SEO Mini Lead Form', 'smart-leading-net'),
		description: __('Website field plus CTA that scrolls to the enquiry section.', 'smart-leading-net'),
		icon: 'email',
		category: 'widgets',
		keywords: ['ai seo', 'lead', 'proposal'],
		supports: {
			html: false,
			align: false,
			multiple: true,
			reusable: true,
		},
		attributes: {
			placeholder: { type: 'string', default: 'Enter your website' },
			buttonLabel: { type: 'string', default: 'Send Me a Proposal' },
			variant: { type: 'string', default: 'default' },
			ariaLabel: { type: 'string', default: 'Start a proposal request' },
			inputId: { type: 'string', default: 'ai-mini-website' },
			note: { type: 'string', default: '' },
			showArrow: { type: 'boolean', default: true },
		},
		edit: function (props) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;
			var blockProps = useBlockProps({ className: 'sln-ai-seo-mini-lead-editor' });

			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __('Form settings', 'smart-leading-net'), initialOpen: true },
						el(TextControl, {
							label: __('Placeholder', 'smart-leading-net'),
							value: attributes.placeholder,
							onChange: function (value) {
								setAttributes({ placeholder: value });
							},
						}),
						el(TextControl, {
							label: __('Button label', 'smart-leading-net'),
							value: attributes.buttonLabel,
							onChange: function (value) {
								setAttributes({ buttonLabel: value });
							},
						}),
						el(SelectControl, {
							label: __('Button style', 'smart-leading-net'),
							value: attributes.variant,
							options: [
								{ label: __('Primary with arrow', 'smart-leading-net'), value: 'default' },
								{ label: __('On blue background', 'smart-leading-net'), value: 'onblue' },
							],
							onChange: function (value) {
								setAttributes({ variant: value });
							},
						}),
						el(ToggleControl, {
							label: __('Show arrow icon', 'smart-leading-net'),
							checked: !!attributes.showArrow,
							onChange: function (value) {
								setAttributes({ showArrow: value });
							},
						}),
						el(TextControl, {
							label: __('Input ID', 'smart-leading-net'),
							value: attributes.inputId,
							onChange: function (value) {
								setAttributes({ inputId: value });
							},
						}),
						el(TextControl, {
							label: __('Aria label', 'smart-leading-net'),
							value: attributes.ariaLabel,
							onChange: function (value) {
								setAttributes({ ariaLabel: value });
							},
						}),
						el(TextareaControl, {
							label: __('Note under form', 'smart-leading-net'),
							value: attributes.note,
							onChange: function (value) {
								setAttributes({ note: value });
							},
						})
					)
				),
				el(
					'div',
					blockProps,
					el(ServerSideRender, {
						block: 'sln/ai-seo-mini-lead',
						attributes: attributes,
					})
				)
			);
		},
		save: function () {
			return null;
		},
	});

	registerBlockType('sln/ai-seo-enquiry-form', {
		apiVersion: 2,
		title: __('AI SEO Enquiry Form', 'smart-leading-net'),
		description: __('Editable enquiry form for the AI SEO Services page.', 'smart-leading-net'),
		icon: 'feedback',
		category: 'widgets',
		keywords: ['ai seo', 'enquiry', 'form'],
		supports: {
			html: false,
			multiple: true,
			reusable: true,
		},
		attributes: {
			nameLabel: { type: 'string', default: 'Name' },
			emailLabel: { type: 'string', default: 'Business email' },
			websiteLabel: { type: 'string', default: 'Website' },
			websiteHint: { type: 'string', default: 'For example, yourcompany.com' },
			goalLabel: {
				type: 'string',
				default: 'What would you like to understand about AI search?',
			},
			buttonLabel: { type: 'string', default: 'Send My Enquiry' },
			privacyText: {
				type: 'string',
				default: 'How we handle your information is described in our privacy policy.',
			},
			privacyUrl: { type: 'string', default: '/privacy-policy/' },
			privacyLinkLabel: { type: 'string', default: 'Privacy policy' },
		},
		edit: function (props) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;
			var blockProps = useBlockProps({ className: 'sln-ai-seo-enquiry-editor' });

			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __('Labels', 'smart-leading-net'), initialOpen: true },
						el(TextControl, {
							label: __('Name label', 'smart-leading-net'),
							value: attributes.nameLabel,
							onChange: function (v) {
								setAttributes({ nameLabel: v });
							},
						}),
						el(TextControl, {
							label: __('Email label', 'smart-leading-net'),
							value: attributes.emailLabel,
							onChange: function (v) {
								setAttributes({ emailLabel: v });
							},
						}),
						el(TextControl, {
							label: __('Website label', 'smart-leading-net'),
							value: attributes.websiteLabel,
							onChange: function (v) {
								setAttributes({ websiteLabel: v });
							},
						}),
						el(TextControl, {
							label: __('Website hint', 'smart-leading-net'),
							value: attributes.websiteHint,
							onChange: function (v) {
								setAttributes({ websiteHint: v });
							},
						}),
						el(TextareaControl, {
							label: __('Goal label', 'smart-leading-net'),
							value: attributes.goalLabel,
							onChange: function (v) {
								setAttributes({ goalLabel: v });
							},
						}),
						el(TextControl, {
							label: __('Button label', 'smart-leading-net'),
							value: attributes.buttonLabel,
							onChange: function (v) {
								setAttributes({ buttonLabel: v });
							},
						})
					),
					el(
						PanelBody,
						{ title: __('Privacy', 'smart-leading-net'), initialOpen: false },
						el(TextareaControl, {
							label: __('Privacy text', 'smart-leading-net'),
							value: attributes.privacyText,
							onChange: function (v) {
								setAttributes({ privacyText: v });
							},
						}),
						el(TextControl, {
							label: __('Privacy URL', 'smart-leading-net'),
							value: attributes.privacyUrl,
							onChange: function (v) {
								setAttributes({ privacyUrl: v });
							},
						}),
						el(TextControl, {
							label: __('Privacy link label', 'smart-leading-net'),
							value: attributes.privacyLinkLabel,
							onChange: function (v) {
								setAttributes({ privacyLinkLabel: v });
							},
						})
					)
				),
				el(
					'div',
					blockProps,
					el(ServerSideRender, {
						block: 'sln/ai-seo-enquiry-form',
						attributes: attributes,
					})
				)
			);
		},
		save: function () {
			return null;
		},
	});
})(window.wp);
