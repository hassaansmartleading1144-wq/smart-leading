(function (wp) {
	'use strict';

	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var registerBlockType = wp.blocks.registerBlockType;
	var InnerBlocks = wp.blockEditor.InnerBlocks;
	var RichText = wp.blockEditor.RichText;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var MediaUpload = wp.blockEditor.MediaUpload;
	var MediaUploadCheck = wp.blockEditor.MediaUploadCheck;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var PanelBody = wp.components.PanelBody;
	var SelectControl = wp.components.SelectControl;
	var TextControl = wp.components.TextControl;
	var TextareaControl = wp.components.TextareaControl;
	var ToggleControl = wp.components.ToggleControl;
	var Button = wp.components.Button;
	var ColorPalette = wp.components.ColorPalette;
	var __ = wp.i18n.__;
	var registerFormatType = wp.richText.registerFormatType;
	var toggleFormat = wp.richText.toggleFormat;
	var RichTextToolbarButton = wp.blockEditor.RichTextToolbarButton;

	var HEADING_OPTIONS = [
		{ label: 'H1', value: 'h1' },
		{ label: 'H2', value: 'h2' },
		{ label: 'H3', value: 'h3' },
		{ label: 'H4', value: 'h4' },
		{ label: 'H5', value: 'h5' },
		{ label: 'H6', value: 'h6' },
		{ label: 'Paragraph', value: 'p' },
	];

	var BG_OPTIONS = [
		{ label: __('White', 'smart-leading-net'), value: 'default' },
		{ label: __('Blue tint', 'smart-leading-net'), value: 'tint' },
		{ label: __('Warm', 'smart-leading-net'), value: 'warm' },
		{ label: __('Brand blue', 'smart-leading-net'), value: 'blue' },
	];

	var BRAND_COLORS = [
		{ name: 'White', color: '#FFFFFF' },
		{ name: 'Tint', color: '#F4F7FF' },
		{ name: 'Warm', color: '#FFF9F7' },
		{ name: 'Blue', color: '#1F4E9E' },
	];

	var BG_FROM_COLOR = {
		'#FFFFFF': 'default',
		'#ffffff': 'default',
		'#F4F7FF': 'tint',
		'#f4f7ff': 'tint',
		'#FFF9F7': 'warm',
		'#fff9f7': 'warm',
		'#1F4E9E': 'blue',
		'#1f4e9e': 'blue',
	};

	var COLOR_FROM_BG = {
		default: '#FFFFFF',
		tint: '#F4F7FF',
		warm: '#FFF9F7',
		blue: '#1F4E9E',
	};

	if (registerFormatType) {
		registerFormatType('smartleading/highlight', {
			title: __('Orange underline', 'smart-leading-net'),
			tagName: 'span',
			className: 'sls-hl',
			edit: function (props) {
				return el(RichTextToolbarButton, {
					icon: 'editor-underline',
					title: __('Orange underline', 'smart-leading-net'),
					onClick: function () {
						props.onChange(toggleFormat(props.value, { type: 'smartleading/highlight' }));
					},
					isActive: props.isActive,
				});
			},
		});
		registerFormatType('smartleading/highlight-2', {
			title: __('Orange heading highlight', 'smart-leading-net'),
			tagName: 'span',
			className: 'sls-hl2',
			edit: function (props) {
				return el(RichTextToolbarButton, {
					icon: 'editor-textcolor',
					title: __('Orange heading highlight', 'smart-leading-net'),
					onClick: function () {
						props.onChange(toggleFormat(props.value, { type: 'smartleading/highlight-2' }));
					},
					isActive: props.isActive,
				});
			},
		});
	}

	function headingPanel(tag, setAttributes) {
		return el(
			PanelBody,
			{ title: __('HTML Element', 'smart-leading-net'), initialOpen: true },
			el(SelectControl, {
				label: __('Heading', 'smart-leading-net'),
				value: tag || 'h2',
				options: HEADING_OPTIONS,
				onChange: function (headingTag) {
					setAttributes({ headingTag: headingTag });
				},
				help: __('Changes the semantic HTML tag. Visual style stays the same.', 'smart-leading-net'),
			})
		);
	}

	function backgroundPanel(background, setAttributes) {
		return el(
			PanelBody,
			{ title: __('Section background', 'smart-leading-net'), initialOpen: false },
			el(SelectControl, {
				label: __('Preset', 'smart-leading-net'),
				value: background || 'default',
				options: BG_OPTIONS,
				onChange: function (value) {
					setAttributes({ background: value });
				},
			}),
			el('p', { className: 'components-base-control__label' }, __('Colour', 'smart-leading-net')),
			el(ColorPalette, {
				colors: BRAND_COLORS,
				value: COLOR_FROM_BG[background] || '#FFFFFF',
				disableCustomColors: true,
				onChange: function (color) {
					setAttributes({ background: BG_FROM_COLOR[color] || 'default' });
				},
			})
		);
	}

	function buttonPanel(title, prefix, attributes, setAttributes) {
		return el(
			PanelBody,
			{ title: title, initialOpen: false },
			el(ToggleControl, {
				label: __('Show button', 'smart-leading-net'),
				checked: attributes[prefix + 'Show'] !== false,
				onChange: function (value) {
					var next = {};
					next[prefix + 'Show'] = value;
					setAttributes(next);
				},
			}),
			el(TextControl, {
				label: __('Button label', 'smart-leading-net'),
				value: attributes[prefix + 'Label'] || '',
				onChange: function (value) {
					var next = {};
					next[prefix + 'Label'] = value;
					setAttributes(next);
				},
			}),
			el(TextControl, {
				label: __('Button URL', 'smart-leading-net'),
				value: attributes[prefix + 'Url'] || '',
				onChange: function (value) {
					var next = {};
					next[prefix + 'Url'] = value;
					setAttributes(next);
				},
			}),
			el(ToggleControl, {
				label: __('Open in new tab', 'smart-leading-net'),
				checked: !!attributes[prefix + 'NewTab'],
				onChange: function (value) {
					var next = {};
					next[prefix + 'NewTab'] = value;
					setAttributes(next);
				},
			})
		);
	}

	function imagePanel(attributes, setAttributes) {
		return el(
			PanelBody,
			{ title: __('Section Image', 'smart-leading-net'), initialOpen: false },
			el(SelectControl, {
				label: __('Image position', 'smart-leading-net'),
				value: attributes.imagePosition || 'none',
				options: [
					{ label: __('Hidden (show journey)', 'smart-leading-net'), value: 'none' },
					{ label: __('Right', 'smart-leading-net'), value: 'right' },
					{ label: __('Left', 'smart-leading-net'), value: 'left' },
				],
				onChange: function (imagePosition) {
					setAttributes({ imagePosition: imagePosition });
				},
			}),
			el(MediaUploadCheck, null,
				el(MediaUpload, {
					onSelect: function (media) {
						setAttributes({
							imageId: media.id,
							imageUrl: media.url,
							imageAlt: media.alt || attributes.imageAlt || '',
						});
					},
					allowedTypes: ['image'],
					value: attributes.imageId,
					render: function (obj) {
						return el(Button, { variant: 'secondary', onClick: obj.open },
							attributes.imageUrl ? __('Replace image', 'smart-leading-net') : __('Select image', 'smart-leading-net')
						);
					},
				})
			),
			attributes.imageUrl
				? el(Button, {
						variant: 'link',
						isDestructive: true,
						onClick: function () {
							setAttributes({ imageId: 0, imageUrl: '', imageAlt: '' });
						},
				  }, __('Remove image', 'smart-leading-net'))
				: null,
			el(TextControl, {
				label: __('Alt text', 'smart-leading-net'),
				value: attributes.imageAlt || '',
				onChange: function (imageAlt) {
					setAttributes({ imageAlt: imageAlt });
				},
			})
		);
	}

	function btnEl(label, url, style) {
		if (!label) {
			return null;
		}
		var className = 'btn-primary-custom sls-btn';
		var showArrow = true;
		if (style === 'secondary') {
			className = 'btn-outline-custom sls-btn';
			showArrow = false;
		} else if (style === 'onblue') {
			className = 'btn-white-custom sls-btn sls-btn--text-only';
			showArrow = false;
		}
		var children = [
			el('span', { className: 'sls-btn__bubble', 'aria-hidden': true }),
			el('span', { className: 'sls-btn__bubble', 'aria-hidden': true }),
			el('span', { className: 'sls-btn__bubble', 'aria-hidden': true }),
			el('span', { className: 'sls-btn__bubble', 'aria-hidden': true }),
			el('span', { className: 'sls-btn__text' }, label),
		];
		if (showArrow) {
			children.push(
				el('span', { className: 'sls-btn__icon', 'aria-hidden': true },
					el('span', { className: 'sls-btn__arrow' }, '→'),
					el('span', { className: 'sls-btn__arrow-copy' }, '→')
				)
			);
		}
		return el('a', { className: className, href: url || '#' }, children);
	}

	function sectionClass(background) {
		var map = {
			default: 'sls-sec',
			tint: 'sls-sec sls-sec--tint',
			warm: 'sls-sec sls-sec--warm',
			blue: 'sls-sec sls-sec--blue',
			banner: 'sls-sec sls-sec--banner',
		};
		return 'ai-seo-page ' + (map[background] || 'sls-sec');
	}

	function innerSave() {
		return el(InnerBlocks.Content);
	}

	function leafSave() {
		return null;
	}

	/* ---------- Hero ---------- */
	registerBlockType('smartleading/ai-seo-hero', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			if (a.heading && a.heading.indexOf('sls-hl') !== -1) {
				set({ heading: String(a.heading).replace(/<span[^>]*class="sls-hl"[^>]*>/g, '').replace(/<\/span>/g, '') });
			}
			var tag = a.headingTag || 'h1';
			var blockProps = useBlockProps({ className: 'ai-seo-page sls-hero' });
			return el(Fragment, null,
				el(InspectorControls, null,
					headingPanel(tag, set),
					imagePanel(a, set),
					buttonPanel(__('Primary Button', 'smart-leading-net'), 'primary', a, set),
					buttonPanel(__('Secondary Button', 'smart-leading-net'), 'secondary', a, set),
					el(PanelBody, { title: __('Breadcrumb', 'smart-leading-net'), initialOpen: false },
						el(ToggleControl, { label: __('Show breadcrumb', 'smart-leading-net'), checked: a.showBreadcrumb !== false, onChange: function (v) { set({ showBreadcrumb: v }); } }),
						el(TextControl, { label: __('Home label', 'smart-leading-net'), value: a.crumbHomeLabel, onChange: function (v) { set({ crumbHomeLabel: v }); } }),
						el(TextControl, { label: __('Home URL', 'smart-leading-net'), value: a.crumbHomeUrl, onChange: function (v) { set({ crumbHomeUrl: v }); } }),
						el(TextControl, { label: __('Middle label', 'smart-leading-net'), value: a.crumbMidLabel, onChange: function (v) { set({ crumbMidLabel: v }); } }),
						el(TextControl, { label: __('Middle URL', 'smart-leading-net'), value: a.crumbMidUrl, onChange: function (v) { set({ crumbMidUrl: v }); } }),
						el(TextControl, { label: __('Current page', 'smart-leading-net'), value: a.crumbCurrent, onChange: function (v) { set({ crumbCurrent: v }); } })
					),
					el(PanelBody, { title: __('Journey card', 'smart-leading-net'), initialOpen: false },
						el(ToggleControl, { label: __('Show journey', 'smart-leading-net'), checked: a.showJourney !== false, onChange: function (v) { set({ showJourney: v }); } }),
						el(TextControl, { label: __('Journey title', 'smart-leading-net'), value: a.journeyTitle, onChange: function (v) { set({ journeyTitle: v }); } }),
						el(TextControl, { label: __('Journey subtitle', 'smart-leading-net'), value: a.journeySub, onChange: function (v) { set({ journeySub: v }); } })
					)
				),
				el('section', blockProps,
					el('div', { className: 'sls-wrap' },
						a.showBreadcrumb !== false ? el('nav', { className: 'sls-crumbs' },
							el('ol', null,
								el('li', null, a.crumbHomeLabel),
								a.crumbMidLabel ? el('li', null, a.crumbMidLabel) : null,
								el('li', null, el('span', null, a.crumbCurrent))
							)
						) : null,
						el('div', { className: 'sls-hero__grid' },
							el('div', null,
								el(RichText, { tagName: 'p', className: 'sls-eyebrow', value: a.eyebrow, onChange: function (v) { set({ eyebrow: v }); }, placeholder: __('Eyebrow', 'smart-leading-net') }),
								el(RichText, { tagName: tag, className: 'sls-title-hero', value: a.heading, onChange: function (v) { set({ heading: v }); }, placeholder: __('Heading', 'smart-leading-net'), allowedFormats: ['core/bold', 'core/italic'] }),
								el(RichText, { tagName: 'p', className: 'sls-lead sls-hero__lead', value: a.description, onChange: function (v) { set({ description: v }); }, placeholder: __('Description', 'smart-leading-net') }),
								el('div', { className: 'sls-actions' },
									a.primaryShow !== false ? btnEl(a.primaryLabel, a.primaryUrl, 'primary-arrow') : null,
									a.secondaryShow !== false ? btnEl(a.secondaryLabel, a.secondaryUrl, 'secondary') : null
								)
							),
							a.imagePosition !== 'none' && a.imageUrl
								? el('figure', { className: 'sls-hero__media' }, el('img', { src: a.imageUrl, alt: a.imageAlt || '' }))
								: el('figure', { className: 'sls-journey' },
									el('figcaption', { className: 'sls-journey__cap' },
										el('span', { className: 'sls-journey__title' }, a.journeyTitle),
										el('span', { className: 'sls-journey__sub' }, a.journeySub)
									),
									el('ol', null, el(InnerBlocks, {
										allowedBlocks: ['smartleading/ai-seo-journey-step'],
										template: [
											['smartleading/ai-seo-journey-step', { number: '1', title: 'Customer question', text: 'A buyer asks a research question in plain language, such as how to compare providers or what a service involves.' }],
											['smartleading/ai-seo-journey-step', { number: '2', title: 'Relevant public sources', text: 'Websites, business profiles, articles and other public information that may relate to the question.' }],
											['smartleading/ai-seo-journey-step', { number: '3', title: 'AI-generated answer', text: 'A summary that may reference or link to some of those sources.', showAnswer: true }],
											['smartleading/ai-seo-journey-step', { number: '4', title: 'Further research or enquiry', text: 'The buyer checks sources, compares options and may contact a business directly.', isEnd: true }],
										],
										templateLock: false,
									}))
								)
						)
					)
				)
			);
		},
		save: innerSave,
	});

	registerBlockType('smartleading/ai-seo-journey-step', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var blockProps = useBlockProps({ className: 'sls-journey__step' + (a.isEnd ? ' sls-journey__step--end' : '') });
			return el(Fragment, null,
				el(InspectorControls, null,
					el(PanelBody, { title: __('Step', 'smart-leading-net'), initialOpen: true },
						el(TextControl, { label: __('Number', 'smart-leading-net'), value: a.number, onChange: function (v) { set({ number: v }); } }),
						el(ToggleControl, { label: __('End step style', 'smart-leading-net'), checked: !!a.isEnd, onChange: function (v) { set({ isEnd: v }); } }),
						el(ToggleControl, { label: __('Show answer sketch', 'smart-leading-net'), checked: !!a.showAnswer, onChange: function (v) { set({ showAnswer: v }); } })
					)
				),
				el('li', blockProps,
					el('span', { className: 'sls-journey__dot' }, a.number),
					el('div', null,
						el(RichText, { tagName: 'p', className: 'sls-journey__name', value: a.title, onChange: function (v) { set({ title: v }); } }),
						el(RichText, { tagName: 'p', className: 'sls-journey__text', value: a.text, onChange: function (v) { set({ text: v }); } })
					)
				)
			);
		},
		save: leafSave,
	});

	/* ---------- Meaning ---------- */
	registerBlockType('smartleading/ai-seo-meaning', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'ai-seo-page ' + (a.background === 'tint' ? 'sls-sec sls-sec--tint' : 'sls-sec') });
			return el(Fragment, null,
				el(InspectorControls, null, headingPanel(tag, set), backgroundPanel(a.background, set)),
				el('section', blockProps,
					el('div', { className: 'sls-wrap sls-split' },
						el('div', { className: 'sls-meaning__copy' },
							el(RichText, { tagName: 'p', className: 'sls-eyebrow', value: a.eyebrow, onChange: function (v) { set({ eyebrow: v }); } }),
							el(RichText, { tagName: tag, className: 'sls-title-section', value: a.heading, onChange: function (v) { set({ heading: v }); } }),
							el(RichText, { tagName: 'p', className: 'sls-lead', value: a.lead, onChange: function (v) { set({ lead: v }); } }),
							el(RichText, { tagName: 'p', value: a.body, onChange: function (v) { set({ body: v }); } }),
							el(RichText, { tagName: 'p', value: a.note, onChange: function (v) { set({ note: v }); } })
						),
						el('div', null,
							el('dl', { className: 'sls-terms' },
								el(InnerBlocks, {
									allowedBlocks: ['smartleading/ai-seo-term'],
									template: [
										['smartleading/ai-seo-term', { abbr: 'SEO', full: 'Search engine optimization', title: 'Supports organic discovery', text: 'Helps relevant pages get found in search results through useful content, technical accessibility and a clear site structure.' }],
										['smartleading/ai-seo-term', { abbr: 'GEO', full: 'Generative engine optimization', title: 'Focuses on generated-answer visibility', text: 'Looks at how a business and its information may appear, be described or be cited in AI-generated answers.' }],
										['smartleading/ai-seo-term', { abbr: 'AEO', full: 'Answer engine optimization', title: 'Emphasizes answering questions clearly', text: 'Structures content around the real questions people ask, so answers are easy to find and understand.' }],
									],
									templateLock: false,
								})
							),
							el('div', { className: 'sls-overlap' },
								el(RichText, { tagName: 'p', value: a.overlapText, onChange: function (v) { set({ overlapText: v }); } })
							)
						)
					)
				)
			);
		},
		save: innerSave,
	});

	registerBlockType('smartleading/ai-seo-term', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var blockProps = useBlockProps({ className: 'sls-term' });
			return el(Fragment, null,
				el(InspectorControls, null,
					el(PanelBody, { title: __('Term', 'smart-leading-net'), initialOpen: true },
						el(TextControl, { label: __('Abbreviation', 'smart-leading-net'), value: a.abbr, onChange: function (v) { set({ abbr: v }); } }),
						el(TextControl, { label: __('Full name', 'smart-leading-net'), value: a.full, onChange: function (v) { set({ full: v }); } })
					)
				),
				el('div', blockProps,
					el('dt', null, a.abbr, el('small', null, a.full)),
					el('dd', null,
						el(RichText, { tagName: 'strong', value: a.title, onChange: function (v) { set({ title: v }); } }),
						el(RichText, { tagName: 'span', value: a.text, onChange: function (v) { set({ text: v }); } })
					)
				)
			);
		},
		save: leafSave,
	});

	/* ---------- Benefits ---------- */
	registerBlockType('smartleading/ai-seo-benefits', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'ai-seo-page sls-sec sls-sec--tint' });
			return el(Fragment, null,
				el(InspectorControls, null, headingPanel(tag, set), backgroundPanel(a.background, set)),
				el('section', blockProps,
					el('div', { className: 'sls-wrap' },
						el('div', { className: 'sls-center' },
							el(RichText, { tagName: tag, className: 'sls-title-section', value: a.heading, onChange: function (v) { set({ heading: v }); }, allowedFormats: ['core/bold', 'smartleading/highlight-2'] }),
							el(RichText, { tagName: 'p', className: 'sls-lead', value: a.lead, onChange: function (v) { set({ lead: v }); } })
						),
						el('ul', { className: 'sls-grid3' },
							el(InnerBlocks, {
								allowedBlocks: ['smartleading/ai-seo-benefit-card'],
								orientation: 'horizontal',
								template: [
									['smartleading/ai-seo-benefit-card', { icon: 'pie', title: 'Build visibility across search experiences', text: 'Support discovery in traditional search results and AI-generated answers with content and technical foundations that both can use.' }],
									['smartleading/ai-seo-benefit-card', { icon: 'network', title: 'Reach customers beyond a single search engine', text: 'Plan around the search tools your audience actually uses, such as Google’s AI features, ChatGPT, Perplexity and Copilot, rather than one channel alone.' }],
									['smartleading/ai-seo-benefit-card', { icon: 'accuracy', title: 'Help AI answers describe you accurately', text: 'Make it easier for AI-assisted search to describe your services, strengths and business details correctly, using clear and consistent public information.' }],
								],
								templateLock: false,
							})
						)
					)
				)
			);
		},
		save: innerSave,
	});

	registerBlockType('smartleading/ai-seo-benefit-card', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h3';
			var blockProps = useBlockProps({ className: 'sls-card sls-benefit' });
			return el(Fragment, null,
				el(InspectorControls, null,
					headingPanel(tag, set),
					el(PanelBody, { title: __('Icon', 'smart-leading-net') },
						el(SelectControl, {
							label: __('Icon', 'smart-leading-net'),
							value: a.icon || 'pie',
							options: [
								{ label: 'Pie / visibility', value: 'pie' },
								{ label: 'Network', value: 'network' },
								{ label: 'Accuracy', value: 'accuracy' },
							],
							onChange: function (v) { set({ icon: v }); },
						})
					)
				),
				el('li', blockProps,
					el('div', { className: 'sls-benefit__icon sls-benefit__icon--' + (a.icon || 'pie'), 'aria-hidden': true }),
					el(RichText, { tagName: tag, className: 'sls-title-card', value: a.title, onChange: function (v) { set({ title: v }); } }),
					el(RichText, { tagName: 'p', value: a.text, onChange: function (v) { set({ text: v }); } })
				)
			);
		},
		save: leafSave,
	});

	/* ---------- Platforms ---------- */
	registerBlockType('smartleading/ai-seo-platforms', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'ai-seo-page sls-sec' });
			return el(Fragment, null,
				el(InspectorControls, null, headingPanel(tag, set), backgroundPanel(a.background, set)),
				el('section', blockProps,
					el('div', { className: 'sls-wrap sls-platforms' },
						el('div', { className: 'sls-platforms__intro' },
							el(RichText, { tagName: 'p', className: 'sls-eyebrow', value: a.eyebrow, onChange: function (v) { set({ eyebrow: v }); } }),
							el(RichText, { tagName: tag, className: 'sls-title-section', value: a.heading, onChange: function (v) { set({ heading: v }); } }),
							el(RichText, { tagName: 'p', value: a.text, onChange: function (v) { set({ text: v }); } }),
							el(RichText, { tagName: 'p', className: 'sls-platforms__note', value: a.note, onChange: function (v) { set({ note: v }); } })
						),
						el('ul', { className: 'sls-chips' },
							el('li', { className: 'sls-chips__label' }, a.chipsHeading),
							el(InnerBlocks, {
								allowedBlocks: ['smartleading/ai-seo-chip'],
								template: [
									['smartleading/ai-seo-chip', { label: 'Google AI Overviews & AI Mode' }],
									['smartleading/ai-seo-chip', { label: 'ChatGPT Search' }],
									['smartleading/ai-seo-chip', { label: 'Perplexity' }],
									['smartleading/ai-seo-chip', { label: 'Gemini' }],
									['smartleading/ai-seo-chip', { label: 'Copilot' }],
								],
								templateLock: false,
							})
						)
					)
				)
			);
		},
		save: innerSave,
	});

	registerBlockType('smartleading/ai-seo-chip', {
		edit: function (props) {
			var blockProps = useBlockProps({ className: 'sls-chip-editor' });
			return el('li', blockProps,
				el(RichText, { tagName: 'span', value: props.attributes.label, onChange: function (v) { props.setAttributes({ label: v }); } })
			);
		},
		save: leafSave,
	});

	/* ---------- Approach ---------- */
	registerBlockType('smartleading/ai-seo-approach', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'ai-seo-page sls-sec sls-sec--tint' });
			return el(Fragment, null,
				el(InspectorControls, null, headingPanel(tag, set), backgroundPanel(a.background, set)),
				el('section', blockProps,
					el('div', { className: 'sls-wrap' },
						el('div', { className: 'sls-head' },
							el('div', null,
								el(RichText, { tagName: 'p', className: 'sls-eyebrow', value: a.eyebrow, onChange: function (v) { set({ eyebrow: v }); } }),
								el(RichText, { tagName: tag, className: 'sls-title-section', value: a.heading, onChange: function (v) { set({ heading: v }); } })
							),
							el(RichText, { tagName: 'p', className: 'sls-lead', value: a.lead, onChange: function (v) { set({ lead: v }); } })
						),
						el('ul', { className: 'sls-grid3' },
							el(InnerBlocks, {
								allowedBlocks: ['smartleading/ai-seo-card'],
								template: [
									['smartleading/ai-seo-card', { number: '01', title: 'Visibility assessment', text: 'Review a selected set of questions and the sources that appear in sampled AI answers. The result is a starting point: where your business appears, where it does not, and which sources tend to shape the answers.' }],
									['smartleading/ai-seo-card', { number: '02', title: 'Audience and question research', text: 'Identify the questions that matter when prospective customers compare options, check requirements and look for help, so effort goes to questions with real business relevance.' }],
									['smartleading/ai-seo-card', { number: '03', title: 'Technical SEO foundations', text: 'Review accessibility, indexability and other technical barriers that can stop public information being found or understood. Fixes are specified after assessment, including appropriate structured data where it supports factual clarity.' }],
									['smartleading/ai-seo-card', { number: '04', title: 'Helpful, answer-focused content', text: 'Clarify service pages, comparisons and practical questions using your real expertise and evidence. The aim is content a person finds useful and that is clear enough to be summarized accurately.' }],
									['smartleading/ai-seo-card', { number: '05', title: 'Consistent business information', text: 'Check that core facts such as services, locations, contact details and descriptions are consistent across your website and relevant external profiles or references.' }],
									['smartleading/ai-seo-card', { number: '06', title: 'Measurement and refinement', text: 'Revisit an agreed sample of questions and use available referral and enquiry data to inform what to prioritize next.' }],
								],
								templateLock: false,
							})
						)
					)
				)
			);
		},
		save: innerSave,
	});

	registerBlockType('smartleading/ai-seo-card', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h3';
			var blockProps = useBlockProps({ className: 'sls-card' });
			return el(Fragment, null,
				el(InspectorControls, null, headingPanel(tag, set),
					el(PanelBody, { title: __('Card', 'smart-leading-net') },
						el(TextControl, { label: __('Number', 'smart-leading-net'), value: a.number, onChange: function (v) { set({ number: v }); } })
					)
				),
				el('li', blockProps,
					el('span', { className: 'sls-card__num' }, a.number),
					el(RichText, { tagName: tag, className: 'sls-title-card', value: a.title, onChange: function (v) { set({ title: v }); } }),
					el(RichText, { tagName: 'p', value: a.text, onChange: function (v) { set({ text: v }); } })
				)
			);
		},
		save: leafSave,
	});

	/* ---------- Pricing ---------- */
	registerBlockType('smartleading/ai-seo-pricing', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'ai-seo-page sls-sec' });
			return el(Fragment, null,
				el(InspectorControls, null,
					headingPanel(tag, set),
					backgroundPanel(a.background, set),
					el(PanelBody, { title: __('Mini lead form', 'smart-leading-net'), initialOpen: false },
						el(TextControl, { label: __('Placeholder', 'smart-leading-net'), value: a.placeholder, onChange: function (v) { set({ placeholder: v }); } }),
						el(TextControl, { label: __('Button label', 'smart-leading-net'), value: a.buttonLabel, onChange: function (v) { set({ buttonLabel: v }); } }),
						el(TextareaControl, { label: __('Note', 'smart-leading-net'), value: a.note, onChange: function (v) { set({ note: v }); } })
					)
				),
				el('section', blockProps,
					el('div', { className: 'sls-wrap sls-pricing' },
						el('div', null,
							el(RichText, { tagName: 'p', className: 'sls-eyebrow', value: a.eyebrow, onChange: function (v) { set({ eyebrow: v }); } }),
							el(RichText, { tagName: tag, className: 'sls-title-section', value: a.heading, onChange: function (v) { set({ heading: v }); } }),
							el(RichText, { tagName: 'p', className: 'sls-lead', value: a.lead, onChange: function (v) { set({ lead: v }); } }),
							el('div', { className: 'sls-mini' },
								el('input', { className: 'sls-input', readOnly: true, placeholder: a.placeholder }),
								btnEl(a.buttonLabel, '#ai-search-enquiry', 'primary-arrow')
							)
						),
						el('div', { className: 'sls-price' },
							el(RichText, { tagName: 'p', className: 'sls-price__label', value: a.priceLabel, onChange: function (v) { set({ priceLabel: v }); } }),
							el(RichText, { tagName: 'p', className: 'sls-price__amt', value: a.priceAmount, onChange: function (v) { set({ priceAmount: v }); } }),
							el('span', { className: 'sls-price__bar' }),
							el(RichText, { tagName: 'p', className: 'sls-price__for', value: a.priceFor, onChange: function (v) { set({ priceFor: v }); } }),
							el(RichText, { tagName: 'p', className: 'sls-price__fine', value: a.priceFine, onChange: function (v) { set({ priceFine: v }); } })
						)
					)
				)
			);
		},
		save: leafSave,
	});

	/* ---------- Process ---------- */
	registerBlockType('smartleading/ai-seo-process', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'ai-seo-page sls-sec sls-sec--tint' });
			return el(Fragment, null,
				el(InspectorControls, null, headingPanel(tag, set), backgroundPanel(a.background, set),
					buttonPanel(__('Primary Button', 'smart-leading-net'), 'primary', a, set)),
				el('section', blockProps,
					el('div', { className: 'sls-wrap' },
						el('div', { className: 'sls-head' },
							el('div', null,
								el(RichText, { tagName: 'p', className: 'sls-eyebrow', value: a.eyebrow, onChange: function (v) { set({ eyebrow: v }); } }),
								el(RichText, { tagName: tag, className: 'sls-title-section', value: a.heading, onChange: function (v) { set({ heading: v }); } })
							),
							el(RichText, { tagName: 'p', className: 'sls-lead', value: a.lead, onChange: function (v) { set({ lead: v }); } })
						),
						el('ol', { className: 'sls-steps' },
							el(InnerBlocks, {
								allowedBlocks: ['smartleading/ai-seo-process-step'],
								template: [
									['smartleading/ai-seo-process-step', { number: '1', title: 'Understand the business and audience', text: 'Discuss your services, markets, existing search work and the questions prospective customers ask.', output: 'a shared view of goals and the questions worth assessing.' }],
									['smartleading/ai-seo-process-step', { number: '2', title: 'Establish a sampled baseline', text: 'Review an agreed sample of questions across relevant AI search experiences and note which sources appear.', output: 'a dated snapshot to compare against later.' }],
									['smartleading/ai-seo-process-step', { number: '3', title: 'Agree scope and priorities', text: 'Weigh content, technical and information-consistency opportunities against relevance and effort.', output: 'agreed priorities and responsibilities.' }],
									['smartleading/ai-seo-process-step', { number: '4', title: 'Review changes and refine', text: 'Revisit the sample alongside available referral and enquiry data, and adjust priorities based on what is observed.', output: 'observations kept separate from assumptions.' }],
								],
								templateLock: false,
							})
						),
						a.primaryShow !== false ? el('div', { className: 'sls-process__cta' },
							btnEl(a.primaryLabel, a.primaryUrl, 'primary-arrow')
						) : null
					)
				)
			);
		},
		save: innerSave,
	});

	registerBlockType('smartleading/ai-seo-process-step', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h3';
			var blockProps = useBlockProps({ className: 'sls-step' });
			return el(Fragment, null,
				el(InspectorControls, null, headingPanel(tag, set),
					el(PanelBody, { title: __('Step', 'smart-leading-net') },
						el(TextControl, { label: __('Number', 'smart-leading-net'), value: a.number, onChange: function (v) { set({ number: v }); } }),
						el(TextControl, { label: __('Output label', 'smart-leading-net'), value: a.outputLabel, onChange: function (v) { set({ outputLabel: v }); } })
					)
				),
				el('li', blockProps,
					el('span', { className: 'sls-step__n' }, a.number),
					el(RichText, { tagName: tag, className: 'sls-title-card', value: a.title, onChange: function (v) { set({ title: v }); } }),
					el(RichText, { tagName: 'p', value: a.text, onChange: function (v) { set({ text: v }); } }),
					el('p', { className: 'sls-step__out' },
						el('b', null, a.outputLabel + ' '),
						el(RichText, { tagName: 'span', value: a.output, onChange: function (v) { set({ output: v }); } })
					)
				)
			);
		},
		save: leafSave,
	});

	/* ---------- Measurement ---------- */
	registerBlockType('smartleading/ai-seo-measurement', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'ai-seo-page sls-sec' });
			return el(Fragment, null,
				el(InspectorControls, null, headingPanel(tag, set), backgroundPanel(a.background, set),
					el(PanelBody, { title: __('Table headings', 'smart-leading-net'), initialOpen: false },
						el(TextControl, { label: __('Signal column', 'smart-leading-net'), value: a.colSignal, onChange: function (v) { set({ colSignal: v }); } }),
						el(TextControl, { label: __('Can tell column', 'smart-leading-net'), value: a.colCan, onChange: function (v) { set({ colCan: v }); } }),
						el(TextControl, { label: __('Cannot show column', 'smart-leading-net'), value: a.colCannot, onChange: function (v) { set({ colCannot: v }); } })
					)
				),
				el('section', blockProps,
					el('div', { className: 'sls-wrap' },
						el('div', { className: 'sls-head' },
							el('div', null,
								el(RichText, { tagName: 'p', className: 'sls-eyebrow', value: a.eyebrow, onChange: function (v) { set({ eyebrow: v }); } }),
								el(RichText, { tagName: tag, className: 'sls-title-section', value: a.heading, onChange: function (v) { set({ heading: v }); } })
							),
							el(RichText, { tagName: 'p', className: 'sls-lead', value: a.lead, onChange: function (v) { set({ lead: v }); } })
						),
						el('div', { className: 'sls-table-wrap' },
							el('table', { className: 'sls-table' },
								el('thead', null, el('tr', null,
									el('th', null, a.colSignal),
									el('th', null, a.colCan),
									el('th', null, a.colCannot)
								)),
								el('tbody', null, el(InnerBlocks, {
									allowedBlocks: ['smartleading/ai-seo-signal-row'],
									template: [
										['smartleading/ai-seo-signal-row', { number: '01', title: 'Sampled mentions and citations', canTell: 'Whether and how your business appears for the questions in an agreed sample, and which competitors or sources appear alongside it.', cannotShow: 'How every user sees every answer. A sample is a snapshot of selected questions at a point in time.' }],
										['smartleading/ai-seo-signal-row', { number: '02', title: 'Source and factual accuracy review', canTell: 'Which pages and profiles answers appear to draw on, and whether your services and details are described correctly.', cannotShow: 'Sources a platform uses but does not display. Not every answer shows its sources.' }],
										['smartleading/ai-seo-signal-row', { number: '03', title: 'Identifiable AI referral visits', canTell: 'Visits from AI platforms that pass referral information, and which pages those visitors land on.', cannotShow: 'AI-assisted journeys that arrive without referral data, such as when someone later searches your name or types your address.' }],
										['smartleading/ai-seo-signal-row', { number: '04', title: 'Recorded enquiries and qualification', canTell: 'Whether enquiries arrive and, where your forms and records support it, how relevant those enquiries are.', cannotShow: 'A complete attribution path. Results depend on the tracking and records already in place.' }],
									],
									templateLock: false,
								}))
							)
						)
					)
				)
			);
		},
		save: innerSave,
	});

	registerBlockType('smartleading/ai-seo-signal-row', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var blockProps = useBlockProps();
			return el(Fragment, null,
				el(InspectorControls, null,
					el(PanelBody, { title: __('Row', 'smart-leading-net') },
						el(TextControl, { label: __('Number', 'smart-leading-net'), value: a.number, onChange: function (v) { set({ number: v }); } })
					)
				),
				el('tr', blockProps,
					el('th', null, el('span', null, a.number), el(RichText, { tagName: 'span', value: a.title, onChange: function (v) { set({ title: v }); } })),
					el('td', null, el(RichText, { tagName: 'span', value: a.canTell, onChange: function (v) { set({ canTell: v }); } })),
					el('td', null, el(RichText, { tagName: 'span', value: a.cannotShow, onChange: function (v) { set({ cannotShow: v }); } }))
				)
			);
		},
		save: leafSave,
	});

	/* ---------- Principles ---------- */
	registerBlockType('smartleading/ai-seo-principles', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'ai-seo-page sls-sec sls-sec--blue' });
			return el(Fragment, null,
				el(InspectorControls, null, headingPanel(tag, set), backgroundPanel(a.background, set)),
				el('section', blockProps,
					el('div', { className: 'sls-wrap sls-principles' },
						el('div', { className: 'sls-principles__intro' },
							el(RichText, { tagName: 'p', className: 'sls-eyebrow', value: a.eyebrow, onChange: function (v) { set({ eyebrow: v }); } }),
							el(RichText, { tagName: tag, className: 'sls-title-section', value: a.heading, onChange: function (v) { set({ heading: v }); } }),
							el(RichText, { tagName: 'p', value: a.text, onChange: function (v) { set({ text: v }); } })
						),
						el('ol', { className: 'sls-plist' },
							el(InnerBlocks, {
								allowedBlocks: ['smartleading/ai-seo-principle'],
								template: [
									['smartleading/ai-seo-principle', { number: '01', title: 'Define the questions being assessed', text: 'Agree which customer questions, markets and platforms are in view, so everyone knows what a finding refers to.' }],
									['smartleading/ai-seo-principle', { number: '02', title: 'Explain the priority behind each recommendation', text: 'Each recommendation should state the problem it addresses and why it comes before other work.' }],
									['smartleading/ai-seo-principle', { number: '03', title: 'Separate observed changes from assumptions', text: 'Report what was seen in the sample and the data, and label interpretation as interpretation.' }],
								],
								templateLock: false,
							})
						)
					)
				)
			);
		},
		save: innerSave,
	});

	registerBlockType('smartleading/ai-seo-principle', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h3';
			var blockProps = useBlockProps();
			return el(Fragment, null,
				el(InspectorControls, null, headingPanel(tag, set),
					el(PanelBody, { title: __('Item', 'smart-leading-net') },
						el(TextControl, { label: __('Number', 'smart-leading-net'), value: a.number, onChange: function (v) { set({ number: v }); } })
					)
				),
				el('li', blockProps,
					el('span', { className: 'sls-plist__n' }, a.number),
					el('div', null,
						el(RichText, { tagName: tag, className: 'sls-title-card', value: a.title, onChange: function (v) { set({ title: v }); } }),
						el(RichText, { tagName: 'p', value: a.text, onChange: function (v) { set({ text: v }); } })
					)
				)
			);
		},
		save: leafSave,
	});

	/* ---------- Scope ---------- */
	registerBlockType('smartleading/ai-seo-scope', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: sectionClass(a.background || 'default') });
			return el(Fragment, null,
				el(InspectorControls, null, headingPanel(tag, set), backgroundPanel(a.background, set),
					buttonPanel(__('Primary Button', 'smart-leading-net'), 'primary', a, set)),
				el('section', blockProps,
					el('div', { className: 'sls-wrap sls-scope' },
						el('div', { className: 'sls-scope__copy' },
							el(RichText, { tagName: 'p', className: 'sls-eyebrow', value: a.eyebrow, onChange: function (v) { set({ eyebrow: v }); } }),
							el(RichText, { tagName: tag, className: 'sls-title-section', value: a.heading, onChange: function (v) { set({ heading: v }); } }),
							el(RichText, { tagName: 'p', className: 'sls-lead', value: a.lead, onChange: function (v) { set({ lead: v }); } }),
							el('div', { className: 'sls-actions' }, a.primaryShow !== false ? btnEl(a.primaryLabel, a.primaryUrl, 'primary-arrow') : null)
						),
						el('div', { className: 'sls-scope__card' },
							el('ul', null, el(InnerBlocks, {
								allowedBlocks: ['smartleading/ai-seo-scope-item'],
								template: [
									['smartleading/ai-seo-scope-item', { title: 'Size and complexity of the website', text: 'How many pages, templates and services are involved.' }],
									['smartleading/ai-seo-scope-item', { title: 'Current content and technical foundations', text: 'What already works well and what may need attention first.' }],
									['smartleading/ai-seo-scope-item', { title: 'Markets and services', text: 'Which offerings, audiences and locations are in view.' }],
									['smartleading/ai-seo-scope-item', { title: 'Assessment and measurement needs', text: 'Which questions to sample and what data you already track.' }],
								],
								templateLock: false,
							}))
						)
					)
				)
			);
		},
		save: innerSave,
	});

	registerBlockType('smartleading/ai-seo-scope-item', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var blockProps = useBlockProps();
			return el('li', blockProps,
				el('span', { className: 'sls-tick', 'aria-hidden': true }),
				el('div', null,
					el(RichText, { tagName: 'b', value: a.title, onChange: function (v) { set({ title: v }); } }),
					el(RichText, { tagName: 'span', className: 'd', value: a.text, onChange: function (v) { set({ text: v }); } })
				)
			);
		},
		save: leafSave,
	});

	/* ---------- Banner ---------- */
	registerBlockType('smartleading/ai-seo-banner', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'ai-seo-page sls-sec sls-sec--banner' });
			return el(Fragment, null,
				el(InspectorControls, null,
					headingPanel(tag, set),
					el(PanelBody, { title: __('Section Image', 'smart-leading-net'), initialOpen: true },
						el(MediaUploadCheck, null,
							el(MediaUpload, {
								onSelect: function (media) {
									set({ imageId: media.id, imageUrl: media.url, imageAlt: media.alt || a.imageAlt });
								},
								allowedTypes: ['image'],
								value: a.imageId,
								render: function (obj) {
									return el(Button, { variant: 'secondary', onClick: obj.open },
										a.imageUrl ? __('Replace image', 'smart-leading-net') : __('Select image', 'smart-leading-net')
									);
								},
							})
						),
						a.imageUrl ? el(Button, { variant: 'link', isDestructive: true, onClick: function () { set({ imageId: 0, imageUrl: '' }); } }, __('Remove image', 'smart-leading-net')) : null,
						el(TextControl, { label: __('Alt text', 'smart-leading-net'), value: a.imageAlt, onChange: function (v) { set({ imageAlt: v }); } })
					),
					el(PanelBody, { title: __('Mini lead form', 'smart-leading-net'), initialOpen: false },
						el(TextControl, { label: __('Placeholder', 'smart-leading-net'), value: a.placeholder, onChange: function (v) { set({ placeholder: v }); } }),
						el(TextControl, { label: __('Button label', 'smart-leading-net'), value: a.buttonLabel, onChange: function (v) { set({ buttonLabel: v }); } })
					)
				),
				el('section', blockProps,
					el('div', { className: 'sls-wrap' },
						el('div', { className: 'sls-banner' },
							el('div', { className: 'sls-banner__proof' },
								a.imageUrl
									? el('figure', { className: 'sls-banner__photo' }, el('img', { src: a.imageUrl, alt: a.imageAlt || '' }))
									: el('div', { className: 'sls-banner__photo' }, a.photoPlaceholder),
								el('div', { className: 'sls-banner__stat' },
									el(RichText, { tagName: 'p', className: 'sls-banner__k', value: a.statKicker, onChange: function (v) { set({ statKicker: v }); } }),
									el(RichText, { tagName: 'p', className: 'sls-banner__n', value: a.statNumber, onChange: function (v) { set({ statNumber: v }); } }),
									el(RichText, { tagName: 'p', className: 'sls-banner__l', value: a.statLabel, onChange: function (v) { set({ statLabel: v }); } })
								)
							),
							el('div', { className: 'sls-banner__copy' },
								el(RichText, { tagName: tag, className: 'sls-title-section', value: a.heading, onChange: function (v) { set({ heading: v }); } }),
								el(RichText, { tagName: 'p', value: a.text, onChange: function (v) { set({ text: v }); } }),
								el('div', { className: 'sls-mini' },
									el('input', { className: 'sls-input', readOnly: true, placeholder: a.placeholder }),
									btnEl(a.buttonLabel, '#ai-search-enquiry', 'onblue')
								)
							)
						)
					)
				)
			);
		},
		save: leafSave,
	});

	/* ---------- FAQ ---------- */
	registerBlockType('smartleading/ai-seo-faq', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'ai-seo-page sls-sec' });
			return el(Fragment, null,
				el(InspectorControls, null, headingPanel(tag, set), backgroundPanel(a.background, set)),
				el('section', blockProps,
					el('div', { className: 'sls-wrap sls-faqwrap' },
						el('div', { className: 'sls-faqwrap__intro' },
							el(RichText, { tagName: 'p', className: 'sls-eyebrow', value: a.eyebrow, onChange: function (v) { set({ eyebrow: v }); } }),
							el(RichText, { tagName: tag, className: 'sls-title-section', value: a.heading, onChange: function (v) { set({ heading: v }); } }),
							el(RichText, { tagName: 'p', value: a.intro, onChange: function (v) { set({ intro: v }); } })
						),
						el('div', { className: 'sls-faq' },
							el(InnerBlocks, {
								allowedBlocks: ['smartleading/ai-seo-faq-item'],
								template: [
									['smartleading/ai-seo-faq-item', { question: 'What are AI SEO services?', answer: 'AI SEO services focus on the website and brand information that may support discovery in AI-assisted search. The work can involve content, technical foundations and measurement. The specific scope of any engagement is agreed separately during scoping.' }],
									['smartleading/ai-seo-faq-item', { question: 'What is the difference between SEO, GEO and AEO?', answer: 'They are overlapping terms. SEO covers organic search discovery, GEO focuses on visibility in generated answers, and AEO emphasizes answering questions clearly. They describe related priorities rather than entirely separate systems.' }],
									['smartleading/ai-seo-faq-item', { question: 'Does LLM optimization mean training a model?', answer: 'No. On this page, LLM optimization means work on how clearly your website and brand information can be found and understood. Model development and fine-tuning are outside the proposed scope.' }],
									['smartleading/ai-seo-faq-item', { question: 'Can you guarantee a ChatGPT mention or AI citation?', answer: 'No. Placement in AI answers should not be promised by anyone. Each platform determines its own answers, and those answers can vary across questions, timing and context.' }],
									['smartleading/ai-seo-faq-item', { question: 'Does AI search optimization replace SEO?', answer: 'It is best treated as a related discovery consideration rather than a replacement. A useful, technically accessible website remains a sensible foundation for both.' }],
									['smartleading/ai-seo-faq-item', { question: 'How is AI search visibility measured?', answer: 'An agreed sample of questions can be reviewed for mentions and citations, alongside available referral and enquiry data. Each of these measures has limits, so they are reported separately rather than combined into a single score.' }],
									['smartleading/ai-seo-faq-item', { question: 'How long will results take?', answer: 'There is no fixed timeline. Timing depends on the work involved, your starting point and how each platform behaves.' }],
									['smartleading/ai-seo-faq-item', { question: 'Which platforms and deliverables would my engagement include?', answer: 'Platform coverage and responsibilities are confirmed during scoping. It should not be assumed that every platform named on this page, or every proposed module, is included.' }],
								],
								templateLock: false,
							})
						)
					)
				)
			);
		},
		save: innerSave,
	});

	registerBlockType('smartleading/ai-seo-faq-item', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var blockProps = useBlockProps({ className: 'wp-block-details' });
			return el('div', blockProps,
				el(RichText, { tagName: 'p', className: 'sls-faq-q', value: a.question, onChange: function (v) { set({ question: v }); }, placeholder: __('Question', 'smart-leading-net') }),
				el(RichText, { tagName: 'p', className: 'sls-faq-a', value: a.answer, onChange: function (v) { set({ answer: v }); }, placeholder: __('Answer', 'smart-leading-net') })
			);
		},
		save: leafSave,
	});

	/* ---------- Enquiry ---------- */
	registerBlockType('smartleading/ai-seo-enquiry', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'ai-seo-page sls-sec sls-sec--tint' });
			return el(Fragment, null,
				el(InspectorControls, null,
					headingPanel(tag, set),
					backgroundPanel(a.background, set),
					el(PanelBody, { title: __('Form labels', 'smart-leading-net'), initialOpen: false },
						el(TextControl, { label: __('Name label', 'smart-leading-net'), value: a.nameLabel, onChange: function (v) { set({ nameLabel: v }); } }),
						el(TextControl, { label: __('Email label', 'smart-leading-net'), value: a.emailLabel, onChange: function (v) { set({ emailLabel: v }); } }),
						el(TextControl, { label: __('Website label', 'smart-leading-net'), value: a.websiteLabel, onChange: function (v) { set({ websiteLabel: v }); } }),
						el(TextControl, { label: __('Button label', 'smart-leading-net'), value: a.buttonLabel, onChange: function (v) { set({ buttonLabel: v }); } }),
						el(TextControl, { label: __('Privacy URL', 'smart-leading-net'), value: a.privacyUrl, onChange: function (v) { set({ privacyUrl: v }); } })
					)
				),
				el('section', blockProps,
					el('div', { className: 'sls-wrap sls-enq' },
						el('div', { className: 'sls-enq__copy' },
							el(RichText, { tagName: 'p', className: 'sls-eyebrow', value: a.eyebrow, onChange: function (v) { set({ eyebrow: v }); } }),
							el(RichText, { tagName: tag, className: 'sls-title-section', value: a.heading, onChange: function (v) { set({ heading: v }); } }),
							el(RichText, { tagName: 'p', className: 'sls-lead', value: a.lead, onChange: function (v) { set({ lead: v }); } }),
							el('ul', { className: 'sls-enq__list' },
								el('li', null, el(RichText, { tagName: 'span', value: a.point1, onChange: function (v) { set({ point1: v }); } })),
								el('li', null, el(RichText, { tagName: 'span', value: a.point2, onChange: function (v) { set({ point2: v }); } })),
								el('li', null, el(RichText, { tagName: 'span', value: a.point3, onChange: function (v) { set({ point3: v }); } }))
							),
							el(RichText, { tagName: 'p', className: 'sls-enq__next', value: a.nextText, onChange: function (v) { set({ nextText: v }); } })
						),
						el('div', { className: 'sls-form' },
							el('p', null, el('strong', null, a.nameLabel)),
							el('p', null, el('strong', null, a.emailLabel)),
							el('p', null, el('strong', null, a.websiteLabel)),
							el('p', null, el('strong', null, a.goalLabel)),
							btnEl(a.buttonLabel, '#', 'primary-arrow'),
							el(RichText, { tagName: 'p', className: 'sls-privacy', value: a.privacyText, onChange: function (v) { set({ privacyText: v }); } })
						)
					)
				)
			);
		},
		save: leafSave,
	});
})(window.wp);
