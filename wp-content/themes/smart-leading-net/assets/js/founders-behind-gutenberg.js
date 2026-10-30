(function (wp) {
	'use strict';

	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var registerBlockType = wp.blocks.registerBlockType;
	var InnerBlocks = wp.blockEditor.InnerBlocks;
	var RichText = wp.blockEditor.RichText;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var MediaUpload = wp.blockEditor.MediaUpload;
	var MediaUploadCheck = wp.blockEditor.MediaUploadCheck;
	var PanelBody = wp.components.PanelBody;
	var SelectControl = wp.components.SelectControl;
	var TextControl = wp.components.TextControl;
	var TextareaControl = wp.components.TextareaControl;
	var ToggleControl = wp.components.ToggleControl;
	var Button = wp.components.Button;
	var __ = wp.i18n.__;

	var HEADING_OPTIONS = [
		{ label: 'H1', value: 'h1' },
		{ label: 'H2', value: 'h2' },
		{ label: 'H3', value: 'h3' },
		{ label: 'H4', value: 'h4' },
		{ label: 'H5', value: 'h5' },
		{ label: 'H6', value: 'h6' },
		{ label: 'Paragraph', value: 'p' },
	];

	function stripHtml(value) {
		return String(value || '').replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
	}

	function headingPanel(tag, setAttributes) {
		return el(
			PanelBody,
			{ title: __('Heading', 'smart-leading-net'), initialOpen: false },
			el(SelectControl, {
				label: __('Heading level', 'smart-leading-net'),
				value: tag || 'h2',
				options: HEADING_OPTIONS,
				help: __('Keep one H1 on the page (the hero).', 'smart-leading-net'),
				onChange: function (headingTag) {
					setAttributes({ headingTag: headingTag });
				},
			})
		);
	}

	function visibilityPanel(attributes, setAttributes, pending) {
		return el(
			PanelBody,
			{ title: __('Section Visibility', 'smart-leading-net'), initialOpen: !!pending },
			el(ToggleControl, {
				label: __('Show on the public page', 'smart-leading-net'),
				checked: attributes.isPublished !== false,
				help: pending
					? __('This section is pending approval. Leave this off until the copy is confirmed.', 'smart-leading-net')
					: __('Turn off to keep this section in the editor without publishing it.', 'smart-leading-net'),
				onChange: function (isPublished) {
					setAttributes({ isPublished: isPublished });
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
				label: __('Destination URL', 'smart-leading-net'),
				value: attributes[prefix + 'Url'] || '',
				onChange: function (value) {
					var next = {};
					next[prefix + 'Url'] = value;
					setAttributes(next);
				},
			}),
			el(ToggleControl, {
				label: __('Open in a new tab', 'smart-leading-net'),
				checked: !!attributes[prefix + 'NewTab'],
				onChange: function (value) {
					var next = {};
					next[prefix + 'NewTab'] = value;
					setAttributes(next);
				},
			})
		);
	}

	function imagePanel(attributes, setAttributes, title, help) {
		return el(
			PanelBody,
			{ title: title || __('Images & Media', 'smart-leading-net'), initialOpen: true },
			el('p', { style: { margin: '0 0 12px', fontSize: '13px', color: '#5a5a5a' } }, help || __('Choose an approved photo from the Media Library. Leave empty if no photo is ready.', 'smart-leading-net')),
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

	function btnEl(label, style) {
		if (!label) {
			return null;
		}
		var className = 'btn-primary-custom sls-btn';
		var showArrow = true;
		if (style === 'outline') {
			className = 'btn-outline-custom sls-btn sls-btn--text-only';
			showArrow = false;
		} else if (style === 'light') {
			className = 'btn-white-custom sls-btn';
		}
		var children = [
			el('span', { className: 'sls-btn__text' }, label),
		];
		if (showArrow) {
			children.push(
				el('span', { className: 'sls-btn__icon', 'aria-hidden': true },
					el('span', { className: 'sls-btn__arrow' }, '→')
				)
			);
		}
		return el('span', { className: className }, children);
	}

	function rt(tag, className, value, onChange, placeholder) {
		return el(RichText, {
			tagName: tag,
			className: className,
			value: value,
			onChange: onChange,
			placeholder: placeholder || '',
			allowedFormats: ['core/bold', 'core/italic', 'core/link'],
		});
	}

	function innerSave() {
		return el(InnerBlocks.Content);
	}

	function inners(allowed, template) {
		return el(InnerBlocks, {
			allowedBlocks: allowed,
			template: template,
			templateLock: false,
			renderAppender: InnerBlocks.ButtonBlockAppender,
		});
	}

	function pendingNote(text) {
		return el('p', { className: 'sf-editor-note' }, text);
	}

	function portraitPreview(a, wide, label, detail) {
		if (a.imageUrl) {
			return el('figure', { className: 'sf-portrait' + (wide ? ' sf-portrait--wide' : '') },
				el('img', { src: a.imageUrl, alt: a.imageAlt || '' })
			);
		}
		return el('div', { className: 'sf-portrait sf-portrait--empty' + (wide ? ' sf-portrait--wide' : '') },
			el('span', null, label),
			el('small', null, detail)
		);
	}

	registerBlockType('smartleading/founders-hero', {
		__experimentalLabel: function () {
			return __('Founders Behind — Hero', 'smart-leading-net');
		},
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h1';
			var hasImg = !!a.imageUrl;
			var blockProps = useBlockProps({
				className: 'sln-founders sf-section sf-hero ' + (hasImg ? 'sf-hero--has-media' : 'sf-hero--text'),
			});
			return el(Fragment, null,
				el(InspectorControls, null,
					headingPanel(tag, set),
					el(PanelBody, { title: __('Headings & Content', 'smart-leading-net'), initialOpen: true },
						el(TextControl, { label: __('Eyebrow', 'smart-leading-net'), value: a.eyebrow || '', onChange: function (v) { set({ eyebrow: v }); } }),
						el(TextareaControl, { label: __('Heading', 'smart-leading-net'), value: stripHtml(a.heading), rows: 2, onChange: function (v) { set({ heading: v }); } }),
						el(TextareaControl, { label: __('Introduction', 'smart-leading-net'), value: stripHtml(a.lead), rows: 3, onChange: function (v) { set({ lead: v }); } }),
						el(TextareaControl, {
							label: __('Optional extra sentence', 'smart-leading-net'),
							value: stripHtml(a.note),
							rows: 2,
							help: __('Leave empty until an approved connecting sentence is supplied.', 'smart-leading-net'),
							onChange: function (v) { set({ note: v }); },
						})
					),
					imagePanel(a, set, __('Images & Media', 'smart-leading-net'), __('Optional joint founder photo. If none is approved, the hero stays text-led.')),
					buttonPanel(__('Primary button', 'smart-leading-net'), 'primary', a, set),
					buttonPanel(__('Secondary button', 'smart-leading-net'), 'secondary', a, set),
					visibilityPanel(a, set, false)
				),
				el('section', blockProps,
					el('div', { className: 'sf-wrap sf-hero__grid' },
						el('div', { className: 'sf-hero__copy' },
							rt('p', 'sf-eyebrow', a.eyebrow, function (v) { set({ eyebrow: v }); }),
							rt(tag, 'sf-hero__title', a.heading, function (v) { set({ heading: v }); }),
							rt('p', 'sf-lead', a.lead, function (v) { set({ lead: v }); }),
							a.note ? rt('p', 'sf-hero__note', a.note, function (v) { set({ note: v }); }) : null,
							el('div', { className: 'sf-actions' },
								a.primaryShow !== false ? btnEl(a.primaryLabel, 'primary') : null,
								a.secondaryShow !== false ? btnEl(a.secondaryLabel, 'outline') : null
							)
						),
						hasImg ? el('div', { className: 'sf-hero__media' }, portraitPreview(a, true)) : null
					)
				)
			);
		},
		save: function () {
			return null;
		},
	});

	registerBlockType('smartleading/founders-profiles', {
		__experimentalLabel: function () {
			return __('Founders Behind — Founder Profiles', 'smart-leading-net');
		},
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'sln-founders sf-section sf-bg-white' });
			return el(Fragment, null,
				el(InspectorControls, null,
					headingPanel(tag, set),
					el(PanelBody, { title: __('Headings & Content', 'smart-leading-net'), initialOpen: true },
						el(TextControl, { label: __('Eyebrow', 'smart-leading-net'), value: a.eyebrow || '', onChange: function (v) { set({ eyebrow: v }); } }),
						el(TextareaControl, { label: __('Heading', 'smart-leading-net'), value: stripHtml(a.heading), rows: 2, onChange: function (v) { set({ heading: v }); } })
					),
					visibilityPanel(a, set, false)
				),
				el('section', blockProps,
					el('div', { className: 'sf-wrap' },
						el('div', { className: 'sf-section-head sf-read' },
							rt('p', 'sf-eyebrow', a.eyebrow, function (v) { set({ eyebrow: v }); }),
							rt(tag, 'sf-section-title', a.heading, function (v) { set({ heading: v }); })
						),
						el('div', { className: 'sf-founders-list' },
							inners(['smartleading/founders-profile'], [
								['smartleading/founders-profile', {
									name: 'Aqib Khan',
									role1: 'CEO',
									role2: 'Growth Hacker',
									role3: 'Marketing Specialist',
									bio: 'Aqib Khan is the CEO of Smart Leading Solutions, a growth hacker and marketing specialist. His approach brings audience understanding, digital presence and advertising strategy together around a clear business direction. In his professional writing, he emphasizes consistent engagement, performance measurement and continuous testing as foundations for sustainable growth.',
									socialUrl: 'https://www.linkedin.com/in/aqib-khan-sls/',
									socialLabel: 'Connect with Aqib on LinkedIn',
									socialNewTab: true,
								}],
								['smartleading/founders-profile', {
									name: 'Farooq Saleh',
									role1: 'Director',
									role2: 'Data Scientist',
									role3: 'Executive',
									bio: 'Farooq Saleh is a Director at Smart Leading Solutions, with a background in data science and executive leadership. His work connects analytical thinking with business priorities, helping translate complex information into a clearer direction for the company.',
									reverse: true,
								}],
							])
						)
					)
				)
			);
		},
		save: innerSave,
	});

	registerBlockType('smartleading/founders-profile', {
		__experimentalLabel: function (attrs) {
			return attrs.name ? __('Founder Profile — ', 'smart-leading-net') + attrs.name : __('Founder Profile', 'smart-leading-net');
		},
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var blockProps = useBlockProps({ className: 'sf-founder' + (a.reverse ? ' sf-founder--reverse' : '') });
			return el(Fragment, null,
				el(InspectorControls, null,
					el(PanelBody, { title: __('Founder Details', 'smart-leading-net'), initialOpen: true },
						el(TextControl, { label: __('Name', 'smart-leading-net'), value: a.name || '', onChange: function (v) { set({ name: v }); } }),
						el(TextControl, { label: __('Role 1', 'smart-leading-net'), value: a.role1 || '', onChange: function (v) { set({ role1: v }); } }),
						el(TextControl, { label: __('Role 2', 'smart-leading-net'), value: a.role2 || '', onChange: function (v) { set({ role2: v }); } }),
						el(TextControl, { label: __('Role 3', 'smart-leading-net'), value: a.role3 || '', onChange: function (v) { set({ role3: v }); } }),
						el(TextareaControl, { label: __('Biography', 'smart-leading-net'), value: stripHtml(a.bio), rows: 6, onChange: function (v) { set({ bio: v }); } }),
						el(TextareaControl, {
							label: __('Additional approved content', 'smart-leading-net'),
							value: stripHtml(a.extra),
							rows: 3,
							help: __('Leave empty until background copy is approved.', 'smart-leading-net'),
							onChange: function (v) { set({ extra: v }); },
						})
					),
					imagePanel(a, set, __('Images & Media', 'smart-leading-net'), __('Approved portrait only. Empty on the public page until a photo is selected.')),
					el(PanelBody, { title: __('Buttons & Links', 'smart-leading-net'), initialOpen: false },
						el(TextControl, { label: __('Social URL', 'smart-leading-net'), value: a.socialUrl || '', onChange: function (v) { set({ socialUrl: v }); } }),
						el(TextControl, { label: __('Link label', 'smart-leading-net'), value: a.socialLabel || '', onChange: function (v) { set({ socialLabel: v }); } }),
						el(ToggleControl, { label: __('Open in a new tab', 'smart-leading-net'), checked: a.socialNewTab !== false, onChange: function (v) { set({ socialNewTab: v }); } })
					),
					el(PanelBody, { title: __('Design & Layout', 'smart-leading-net'), initialOpen: false },
						el(ToggleControl, {
							label: __('Image on the right', 'smart-leading-net'),
							checked: !!a.reverse,
							onChange: function (v) { set({ reverse: v }); },
						})
					)
				),
				el('article', blockProps,
					el('div', { className: 'sf-founder__media' },
						portraitPreview(a, false, __('Founder portrait — add in settings', 'smart-leading-net'), __('Public page hides this until a photo is selected.', 'smart-leading-net'))
					),
					el('div', { className: 'sf-founder__body' },
						rt('h3', '', a.name, function (v) { set({ name: v }); }, __('Founder name', 'smart-leading-net')),
						el('p', { className: 'sf-founder__role' },
							a.role1 ? el('span', null, a.role1) : null,
							a.role2 ? el('span', null, a.role2) : null,
							a.role3 ? el('span', null, a.role3) : null
						),
						rt('p', 'sf-founder__bio', a.bio, function (v) { set({ bio: v }); }, __('Biography', 'smart-leading-net')),
						a.extra ? rt('p', 'sf-founder__extra', a.extra, function (v) { set({ extra: v }); }) : null,
						a.socialUrl && a.socialLabel ? el('span', { className: 'sf-founder__link' }, el('span', null, a.socialLabel)) : null
					)
				)
			);
		},
		save: function () {
			return null;
		},
	});

	registerBlockType('smartleading/founders-origin', {
		__experimentalLabel: function () {
			return __('Founders Behind — Origin Story', 'smart-leading-net');
		},
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'sln-founders sf-section sf-bg-blue' });
			return el(Fragment, null,
				el(InspectorControls, null,
					headingPanel(tag, set),
					el(PanelBody, { title: __('Headings & Content', 'smart-leading-net'), initialOpen: true },
						el(TextControl, { label: __('Eyebrow', 'smart-leading-net'), value: a.eyebrow || '', onChange: function (v) { set({ eyebrow: v }); } }),
						el(TextareaControl, { label: __('Heading', 'smart-leading-net'), value: stripHtml(a.heading), rows: 2, onChange: function (v) { set({ heading: v }); } }),
						el(TextareaControl, {
							label: __('Origin story', 'smart-leading-net'),
							value: a.story || '',
							rows: 6,
							help: __('Do not invent a founding year or timeline. This stays unpublished until approved.', 'smart-leading-net'),
							onChange: function (v) { set({ story: v }); },
						})
					),
					imagePanel(a, set, __('Images & Media', 'smart-leading-net'), __('Optional authentic working photo.')),
					visibilityPanel(a, set, true)
				),
				el('section', blockProps,
					pendingNote(__('Pending approval — hidden on the public page until “Show on the public page” is turned on.', 'smart-leading-net')),
					el('div', { className: 'sf-wrap sf-origin__grid' },
						el('div', { className: 'sf-read' },
							rt('p', 'sf-eyebrow', a.eyebrow, function (v) { set({ eyebrow: v }); }),
							rt(tag, 'sf-section-title', a.heading, function (v) { set({ heading: v }); }),
							rt('p', '', a.story, function (v) { set({ story: v }); }, __('Approved origin story', 'smart-leading-net'))
						),
						portraitPreview(a, true, __('Working photo — optional', 'smart-leading-net'), __('Omit if unavailable.', 'smart-leading-net'))
					)
				)
			);
		},
		save: function () {
			return null;
		},
	});

	registerBlockType('smartleading/founders-roles', {
		__experimentalLabel: function () {
			return __('Founders Behind — Complementary Roles', 'smart-leading-net');
		},
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'sln-founders sf-section sf-bg-white' });
			return el(Fragment, null,
				el(InspectorControls, null,
					headingPanel(tag, set),
					el(PanelBody, { title: __('Headings & Content', 'smart-leading-net'), initialOpen: true },
						el(TextControl, { label: __('Eyebrow', 'smart-leading-net'), value: a.eyebrow || '', onChange: function (v) { set({ eyebrow: v }); } }),
						el(TextareaControl, { label: __('Heading', 'smart-leading-net'), value: stripHtml(a.heading), rows: 2, onChange: function (v) { set({ heading: v }); } }),
						el(TextareaControl, { label: __('Introduction', 'smart-leading-net'), value: stripHtml(a.lead), rows: 2, onChange: function (v) { set({ lead: v }); } }),
						el(TextareaControl, {
							label: __('Shared collaboration text', 'smart-leading-net'),
							value: stripHtml(a.shared),
							rows: 3,
							help: __('Must not imply founder involvement on every client account.', 'smart-leading-net'),
							onChange: function (v) { set({ shared: v }); },
						})
					),
					visibilityPanel(a, set, true)
				),
				el('section', blockProps,
					pendingNote(__('Pending approval — hidden on the public page until enabled.', 'smart-leading-net')),
					el('div', { className: 'sf-wrap' },
						el('div', { className: 'sf-section-head sf-read' },
							rt('p', 'sf-eyebrow', a.eyebrow, function (v) { set({ eyebrow: v }); }),
							rt(tag, 'sf-section-title', a.heading, function (v) { set({ heading: v }); }),
							rt('p', 'sf-lead', a.lead, function (v) { set({ lead: v }); })
						),
						el('div', { className: 'sf-roles' },
							inners(['smartleading/founders-role'], [
								['smartleading/founders-role', { name: 'Aqib Khan', role: 'CEO · Growth Hacker · Marketing Specialist', text: '' }],
								['smartleading/founders-role', { name: 'Farooq Saleh', role: 'Director · Data Scientist · Executive', text: '' }],
							])
						),
						rt('p', 'sf-roles__shared', a.shared, function (v) { set({ shared: v }); }, __('How the founders collaborate', 'smart-leading-net'))
					)
				)
			);
		},
		save: innerSave,
	});

	registerBlockType('smartleading/founders-role', {
		__experimentalLabel: function (attrs) {
			return attrs.name ? __('Founder Role — ', 'smart-leading-net') + attrs.name : __('Founder Role', 'smart-leading-net');
		},
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var blockProps = useBlockProps({ className: 'sf-role' });
			return el(Fragment, null,
				el(InspectorControls, null,
					el(PanelBody, { title: __('Cards & Items', 'smart-leading-net'), initialOpen: true },
						el(TextControl, { label: __('Name', 'smart-leading-net'), value: a.name || '', onChange: function (v) { set({ name: v }); } }),
						el(TextControl, { label: __('Role', 'smart-leading-net'), value: a.role || '', onChange: function (v) { set({ role: v }); } }),
						el(TextareaControl, { label: __('Description', 'smart-leading-net'), value: stripHtml(a.text), rows: 4, onChange: function (v) { set({ text: v }); } })
					)
				),
				el('div', blockProps,
					rt('p', 'sf-role__name', a.name, function (v) { set({ name: v }); }),
					rt('h3', '', a.role, function (v) { set({ role: v }); }),
					rt('p', '', a.text, function (v) { set({ text: v }); }, __('Confirmed areas this founder leads', 'smart-leading-net'))
				)
			);
		},
		save: function () {
			return null;
		},
	});

	registerBlockType('smartleading/founders-principles', {
		__experimentalLabel: function () {
			return __('Founders Behind — Principles', 'smart-leading-net');
		},
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'sln-founders sf-section sf-bg-orange' });
			return el(Fragment, null,
				el(InspectorControls, null,
					headingPanel(tag, set),
					el(PanelBody, { title: __('Headings & Content', 'smart-leading-net'), initialOpen: true },
						el(TextControl, { label: __('Eyebrow', 'smart-leading-net'), value: a.eyebrow || '', onChange: function (v) { set({ eyebrow: v }); } }),
						el(TextareaControl, { label: __('Heading', 'smart-leading-net'), value: stripHtml(a.heading), rows: 2, onChange: function (v) { set({ heading: v }); } })
					),
					visibilityPanel(a, set, true)
				),
				el('section', blockProps,
					pendingNote(__('Proposed principles — hidden on the public page until approved and enabled.', 'smart-leading-net')),
					el('div', { className: 'sf-wrap' },
						el('div', { className: 'sf-section-head sf-read' },
							a.eyebrow ? rt('p', 'sf-eyebrow', a.eyebrow, function (v) { set({ eyebrow: v }); }) : null,
							rt(tag, 'sf-section-title', a.heading, function (v) { set({ heading: v }); })
						),
						el('div', { className: 'sf-principles' },
							inners(['smartleading/founders-principle'], [
								['smartleading/founders-principle', { heading: 'Understand the business first.', text: '' }],
								['smartleading/founders-principle', { heading: 'Measure what matters.', text: '' }],
								['smartleading/founders-principle', { heading: 'Make decisions clear.', text: '' }],
							])
						)
					)
				)
			);
		},
		save: innerSave,
	});

	registerBlockType('smartleading/founders-principle', {
		__experimentalLabel: function (attrs) {
			var label = stripHtml(attrs.heading || '');
			if (label.indexOf('Understand') === 0) {
				return __('Principle — Understand the Business', 'smart-leading-net');
			}
			if (label.indexOf('Measure') === 0) {
				return __('Principle — Measure What Matters', 'smart-leading-net');
			}
			if (label.indexOf('Make decisions') === 0 || label.indexOf('Clear') === 0) {
				return __('Principle — Clear Decisions', 'smart-leading-net');
			}
			return label || __('Principle', 'smart-leading-net');
		},
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var blockProps = useBlockProps({ className: 'sf-principle' });
			return el(Fragment, null,
				el(InspectorControls, null,
					el(PanelBody, { title: __('Cards & Items', 'smart-leading-net'), initialOpen: true },
						el(TextControl, { label: __('Heading', 'smart-leading-net'), value: stripHtml(a.heading), onChange: function (v) { set({ heading: v }); } }),
						el(TextareaControl, { label: __('Description', 'smart-leading-net'), value: stripHtml(a.text), rows: 4, onChange: function (v) { set({ text: v }); } })
					)
				),
				el('div', blockProps,
					rt('h3', '', a.heading, function (v) { set({ heading: v }); }),
					rt('p', '', a.text, function (v) { set({ text: v }); }, __('Approved explanation', 'smart-leading-net'))
				)
			);
		},
		save: function () {
			return null;
		},
	});

	registerBlockType('smartleading/founders-cta', {
		__experimentalLabel: function () {
			return __('Founders Behind — Closing CTA', 'smart-leading-net');
		},
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'sln-founders sf-section sf-bg-white' });
			return el(Fragment, null,
				el(InspectorControls, null,
					headingPanel(tag, set),
					el(PanelBody, { title: __('Headings & Content', 'smart-leading-net'), initialOpen: true },
						el(TextareaControl, { label: __('Heading', 'smart-leading-net'), value: stripHtml(a.heading), rows: 2, onChange: function (v) { set({ heading: v }); } }),
						el(TextareaControl, { label: __('Description', 'smart-leading-net'), value: stripHtml(a.lead), rows: 3, onChange: function (v) { set({ lead: v }); } })
					),
					buttonPanel(__('Primary button', 'smart-leading-net'), 'primary', a, set),
					buttonPanel(__('Secondary link', 'smart-leading-net'), 'secondary', a, set),
					visibilityPanel(a, set, false)
				),
				el('section', blockProps,
					el('div', { className: 'sf-wrap' },
						el('div', { className: 'sf-close' },
							el('div', null,
								rt(tag, 'sf-close__title', a.heading, function (v) { set({ heading: v }); }),
								rt('p', '', a.lead, function (v) { set({ lead: v }); })
							),
							el('div', { className: 'sf-actions' },
								a.primaryShow !== false ? btnEl(a.primaryLabel, 'light') : null,
								a.secondaryShow !== false && a.secondaryLabel ? el('span', { className: 'sf-close__link' }, a.secondaryLabel) : null
							)
						)
					)
				)
			);
		},
		save: function () {
			return null;
		},
	});
})(window.wp);
