(function (wp) {
	'use strict';

	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var registerBlockType = wp.blocks.registerBlockType;
	var InnerBlocks = wp.blockEditor.InnerBlocks;
	var RichText = wp.blockEditor.RichText;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var PanelBody = wp.components.PanelBody;
	var SelectControl = wp.components.SelectControl;
	var TextControl = wp.components.TextControl;
	var TextareaControl = wp.components.TextareaControl;
	var ToggleControl = wp.components.ToggleControl;
	var ExternalLink = wp.components.ExternalLink;
	var __ = wp.i18n.__;
	var DEFAULT_BEYOND_VIDEO = 'https://www.instagram.com/reels/DcTFVBJjw_Y/';

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

	function listLabel(attrs, fallback) {
		var text = stripHtml(attrs.title || attrs.question || attrs.label || attrs.heading || '');
		if (text.length > 52) {
			text = text.slice(0, 49) + '…';
		}
		return text || fallback;
	}

	function headingPanel(tag, setAttributes) {
		return el(
			PanelBody,
			{ title: __('Heading', 'smart-leading-net'), initialOpen: true },
			el(SelectControl, {
				label: __('Heading level', 'smart-leading-net'),
				value: tag || 'h2',
				options: HEADING_OPTIONS,
				help: __('Choose the HTML heading level. Keep one H1 per page.', 'smart-leading-net'),
				onChange: function (headingTag) {
					setAttributes({ headingTag: headingTag });
				},
			})
		);
	}

	function buttonPanel(title, prefix, attributes, setAttributes, extra) {
		var controls = [
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
				help: __('Page path or full URL, e.g. #local-seo-audit-request', 'smart-leading-net'),
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
			}),
		];
		if (extra) {
			controls = controls.concat(extra);
		}
		return el.apply(null, [PanelBody, { title: title, initialOpen: false }].concat(controls));
	}

	function copyPanel(title, fields, attributes, setAttributes) {
		var controls = fields.map(function (field) {
			var props = {
				label: field.label,
				value: field.strip ? stripHtml(attributes[field.key]) : (attributes[field.key] || ''),
				help: field.help || '',
				onChange: function (value) {
					var next = {};
					next[field.key] = value;
					setAttributes(next);
				},
			};
			if (field.rows) {
				props.rows = field.rows;
			}
			return el(field.rows ? TextareaControl : TextControl, props);
		});
		return el.apply(null, [PanelBody, { title: title, initialOpen: true }].concat(controls));
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
	function leafSave() {
		return null;
	}

	function inners(allowed, template) {
		return el(InnerBlocks, {
			allowedBlocks: allowed,
			template: template,
			templateLock: false,
			renderAppender: allowed && allowed.length ? InnerBlocks.ButtonBlockAppender : undefined,
		});
	}

	var ICON_OPTIONS = [
		{ label: __('Search', 'smart-leading-net'), value: 'search' },
		{ label: __('Map pin', 'smart-leading-net'), value: 'pin' },
		{ label: __('Website', 'smart-leading-net'), value: 'web' },
		{ label: __('Phone', 'smart-leading-net'), value: 'phone' },
		{ label: __('Search (not found)', 'smart-leading-net'), value: 'searchx' },
		{ label: __('Pin with question', 'smart-leading-net'), value: 'pinq' },
		{ label: __('Pointer', 'smart-leading-net'), value: 'pointer' },
		{ label: __('Chart with question', 'smart-leading-net'), value: 'chartq' },
		{ label: __('Shop / profile', 'smart-leading-net'), value: 'shop' },
		{ label: __('Search plus', 'smart-leading-net'), value: 'searchplus' },
		{ label: __('Document', 'smart-leading-net'), value: 'doc' },
		{ label: __('Technical', 'smart-leading-net'), value: 'wrench' },
		{ label: __('Listings', 'smart-leading-net'), value: 'list' },
		{ label: __('Reviews / chat', 'smart-leading-net'), value: 'chat' },
		{ label: __('Priorities', 'smart-leading-net'), value: 'priorities' },
		{ label: __('Scope / clipboard', 'smart-leading-net'), value: 'clip' },
		{ label: __('Reporting bars', 'smart-leading-net'), value: 'bars' },
		{ label: __('Visibility', 'smart-leading-net'), value: 'eye' },
		{ label: __('Form', 'smart-leading-net'), value: 'form' },
		{ label: __('Qualified outcome', 'smart-leading-net'), value: 'checksq' },
	];

	var ICON_PATHS = {
		pin: '<path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21z"/><circle class="ac" cx="12" cy="9.5" r="2.5"/>',
		web: '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9h18"/><path class="ac" d="M7 13h6M7 16h4"/>',
		phone: '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/>',
		searchx: '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/><path class="ac" d="M8.8 8.8l4.4 4.4M13.2 8.8l-4.4 4.4"/>',
		pinq: '<path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21z"/><path class="ac" d="M10.2 8a1.9 1.9 0 1 1 2.6 1.8c-.5.2-.8.6-.8 1.2"/>',
		pointer: '<path d="M5 3l14 6-6 2-2 6z"/><path class="ac" d="m13.5 13.5 5 5"/>',
		chartq: '<path d="M4 20h16"/><path d="M7 16v-5M12 16V8"/><path class="ac" d="M15.6 6.8a2 2 0 1 1 2.7 1.9"/>',
		shop: '<path d="M4 9l1.5-5h13L20 9"/><path d="M4 9v11h16V9"/><path class="ac" d="M10 20v-5h4v5"/>',
		searchplus: '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/><path class="ac" d="M8 11h6M11 8v6"/>',
		doc: '<path d="M14 3H6a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8z"/><path d="M14 3v5h5"/>',
		wrench: '<path d="M14.7 6.3a4 4 0 0 0-5.4 5.2L3 17.8V21h3.2l6.3-6.3a4 4 0 0 0 5.2-5.4z"/>',
		list: '<path d="M10 6h10M10 12h10M10 18h10"/><path class="ac" d="m3.5 6 1.5 1.5L7.5 5"/>',
		chat: '<path d="M21 12a8 8 0 0 1-11.8 7L4 20l1.1-4.6A8 8 0 1 1 21 12z"/>',
		priorities: '<path d="M4 6h16M4 12h10M4 18h7"/>',
		clip: '<rect x="7" y="3" width="10" height="18" rx="2"/><path d="M9 8h6"/>',
		bars: '<path d="M6 20V10M12 20V4M18 20v-7"/>',
		eye: '<path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>',
		form: '<rect x="5" y="3" width="14" height="18" rx="2"/><path d="M8 8h8M8 12h8M8 16h5"/>',
		checksq: '<rect x="4" y="4" width="16" height="16" rx="2"/><path class="ac" d="m8 12 3 3 5-6"/>',
		search: '<circle cx="11" cy="11" r="7"/><path class="ac" d="m20 20-3.5-3.5"/>',
		tick: '<path d="m5 12.5 4.5 4.5L19 7.5"/>',
		target: '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle class="acf" cx="12" cy="12" r="2"/>',
		link: '<path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1"/><path class="ac" d="M14 10a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1-1"/>',
		play: '<path d="M8 5v14l11-7z" fill="currentColor" stroke="none"/>'
	};

	function svgIcon(name) {
		return el('svg', {
			className: 'ic',
			viewBox: '0 0 24 24',
			'aria-hidden': true,
			dangerouslySetInnerHTML: { __html: ICON_PATHS[name] || ICON_PATHS.pin }
		});
	}

	function factorArt(key) {
		if (key === 'distance') {
			return '<svg viewBox="0 0 200 64" aria-hidden="true"><circle cx="80" cy="32" r="29" fill="none" stroke="#1F4E9E" stroke-opacity=".3" stroke-width="1.5" stroke-dasharray="4 4"/><circle cx="80" cy="32" r="17" fill="#ECF2FC" stroke="#1F4E9E" stroke-opacity=".4" stroke-width="1.5" stroke-dasharray="4 4"/><path d="M80 40s7-6.6 7-11.6a7 7 0 0 0-14 0C73 33.4 80 40 80 40z" fill="#F36B32"/><circle cx="80" cy="28.5" r="2.6" fill="#fff"/><path d="M96 32h62" stroke="#F36B32" stroke-width="2" stroke-dasharray="2 6" stroke-linecap="round"/><circle cx="170" cy="25" r="6" fill="#1F4E9E"/><path d="M160 44a10 10 0 0 1 20 0" fill="#1F4E9E"/></svg>';
		}
		if (key === 'prominence') {
			return '<svg viewBox="0 0 200 64" aria-hidden="true"><g fill="#F36B32"><path transform="translate(8 6)" d="M11 0l3.2 7.3 7.8.7-5.9 5.2 1.8 7.7L11 16.8 4.1 20.9 5.9 13.2 0 8l7.8-.7z"/><path transform="translate(38 6)" d="M11 0l3.2 7.3 7.8.7-5.9 5.2 1.8 7.7L11 16.8 4.1 20.9 5.9 13.2 0 8l7.8-.7z"/><path transform="translate(68 6)" d="M11 0l3.2 7.3 7.8.7-5.9 5.2 1.8 7.7L11 16.8 4.1 20.9 5.9 13.2 0 8l7.8-.7z"/><path transform="translate(98 6)" d="M11 0l3.2 7.3 7.8.7-5.9 5.2 1.8 7.7L11 16.8 4.1 20.9 5.9 13.2 0 8l7.8-.7z"/><path transform="translate(128 6)" d="M11 0l3.2 7.3 7.8.7-5.9 5.2 1.8 7.7L11 16.8 4.1 20.9 5.9 13.2 0 8l7.8-.7z"/></g><rect x="8" y="38" width="150" height="7" rx="3.5" fill="#E2EEFF"/><rect x="8" y="51" width="104" height="7" rx="3.5" fill="#E2EEFF"/></svg>';
		}
		return '<svg viewBox="0 0 200 64" aria-hidden="true"><rect x="6" y="14" width="152" height="36" rx="18" fill="#F4F7FF" stroke="#E2EEFF" stroke-width="1.5"/><circle cx="30" cy="31" r="7" fill="none" stroke="#1F4E9E" stroke-width="2"/><path d="m35 36 5 5" stroke="#F36B32" stroke-width="2" stroke-linecap="round"/><rect x="50" y="28" width="78" height="8" rx="4" fill="#E2EEFF"/><circle cx="178" cy="32" r="15" fill="#19B36B"/><path d="m171 32 5 5 9-10" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>';
	}

	var MAP_DECORATION = '<svg class="map__streets" viewBox="0 0 600 600" preserveAspectRatio="xMidYMid slice" aria-hidden="true"><rect width="600" height="600" fill="#E6EEFA"></rect><g fill="#DBE5F6"><rect x="16" y="76" width="90" height="62" rx="8"></rect><rect x="214" y="10" width="92" height="48" rx="8"></rect><rect x="460" y="166" width="66" height="96" rx="8"></rect><rect x="96" y="300" width="88" height="104" rx="8"></rect><rect x="214" y="436" width="94" height="68" rx="8"></rect><rect x="556" y="296" width="60" height="112" rx="8"></rect></g><rect x="334" y="440" width="94" height="148" rx="16" fill="#DCEFE5"></rect><g fill="none" stroke="#fff" stroke-linecap="round"><path d="M-20 64H620M-20 286H620M-20 524H620M86 -20V620M322 -20V620M548 -20V620" stroke-width="8"></path><path d="M-20 152H620M-20 420H620M200 -20V620M444 -20V620" stroke-width="18"></path><path d="M-40 610 640 40" stroke-width="24"></path></g></svg><svg class="map__route" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true"><path d="M30 19 C 30 32, 52 30, 58 40 M30 19 C 22 34, 18 38, 24 47 M60 52 C 66 58, 72 58, 73 62 M38 70 C 46 76, 58 70, 72 64 M73 64 L 66 82"></path></svg><div class="map__pin" aria-hidden="true"><i></i><i></i><i></i><svg viewBox="0 0 34 44"><path d="M17 43s15-14.6 15-26A15 15 0 0 0 2 17c0 11.4 15 26 15 26z" fill="#F36B32"></path><circle cx="17" cy="17" r="6" fill="#fff"></circle></svg></div>';

	var MAP_CARD_NAMES = {
		search: __('Search Journey Card', 'smart-leading-net'),
		profile: __('Business Profile Card', 'smart-leading-net'),
		site: __('Website Card', 'smart-leading-net'),
		goal: __('Customer Action Card', 'smart-leading-net'),
	};

	function chipItems(chips) {
		return String(chips || '').split('|').map(function (item) {
			return item.trim();
		}).filter(Boolean).map(function (item) {
			return el('li', null, item);
		});
	}

	registerBlockType('smartleading/locel-seo-hero', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h1';
			var blockProps = useBlockProps({ className: 'sls-locel-seo hero' });
			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(PanelBody, { title: __('Heading', 'smart-leading-net'), initialOpen: true },
						el(TextControl, { label: __('Eyebrow label', 'smart-leading-net'), value: a.eyebrow || '', onChange: function (v) { set({ eyebrow: v }); } }),
						el(TextareaControl, { label: __('Main heading', 'smart-leading-net'), value: stripHtml(a.heading), rows: 3, onChange: function (v) { set({ heading: v }); } }),
						el(SelectControl, {
							label: __('Heading level', 'smart-leading-net'),
							value: tag,
							options: HEADING_OPTIONS,
							help: __('Use H1 for this hero. Other sections should stay H2.', 'smart-leading-net'),
							onChange: function (headingTag) { set({ headingTag: headingTag }); },
						})
					),
					el(PanelBody, { title: __('Description', 'smart-leading-net'), initialOpen: true },
						el(TextareaControl, { label: __('Paragraph', 'smart-leading-net'), value: stripHtml(a.description), rows: 4, onChange: function (v) { set({ description: v }); } })
					),
					buttonPanel(__('Primary button', 'smart-leading-net'), 'primary', a, set),
					buttonPanel(__('Secondary button', 'smart-leading-net'), 'secondary', a, set),
					el(PanelBody, { title: __('Hero map', 'smart-leading-net'), initialOpen: false },
						el('p', { style: { margin: '0 0 8px', fontSize: '13px', color: '#5a5a5a' } },
							__('Select a map card in List View to edit its heading, search text and action labels. The map illustration is part of the design and is not edited as SVG code.', 'smart-leading-net')
						)
					),
					el(PanelBody, { title: __('Design & layout', 'smart-leading-net'), initialOpen: false },
						el(ToggleControl, {
							label: __('Show breadcrumb', 'smart-leading-net'),
							checked: a.showBreadcrumb !== false,
							onChange: function (v) { set({ showBreadcrumb: v }); },
						}),
						el(TextControl, { label: __('Home label', 'smart-leading-net'), value: a.crumbHomeLabel || '', onChange: function (v) { set({ crumbHomeLabel: v }); } }),
						el(TextControl, { label: __('Home URL', 'smart-leading-net'), value: a.crumbHomeUrl || '', onChange: function (v) { set({ crumbHomeUrl: v }); } }),
						el(TextControl, { label: __('Services label', 'smart-leading-net'), value: a.crumbMid1Label || '', onChange: function (v) { set({ crumbMid1Label: v }); } }),
						el(TextControl, { label: __('SEO label', 'smart-leading-net'), value: a.crumbMid2Label || '', onChange: function (v) { set({ crumbMid2Label: v }); } }),
						el(TextControl, { label: __('SEO URL', 'smart-leading-net'), value: a.crumbMid2Url || '', onChange: function (v) { set({ crumbMid2Url: v }); } }),
						el(TextControl, { label: __('Current page label', 'smart-leading-net'), value: a.crumbCurrent || '', onChange: function (v) { set({ crumbCurrent: v }); } })
					)
				),
				el(
					'section',
					blockProps,
					el('div', { className: 'lseo-wrap' },
						a.showBreadcrumb === false ? null : el('nav', { className: 'crumbs', 'aria-label': __('Breadcrumb', 'smart-leading-net') },
							el('ol', null,
								el('li', null, a.crumbHomeLabel || 'Home'),
								a.crumbMid1Label ? el('li', null, a.crumbMid1Label) : null,
								a.crumbMid2Label ? el('li', null, a.crumbMid2Label) : null,
								el('li', null, a.crumbCurrent || 'Local SEO')
							)
						),
						el('div', { className: 'hero__grid' },
							el('div', null,
								rt('p', 'eyebrow', a.eyebrow, function (v) { set({ eyebrow: v }); }, __('Eyebrow', 'smart-leading-net')),
								rt(tag, '', a.heading, function (v) { set({ heading: v }); }, __('Heading', 'smart-leading-net')),
								rt('p', 'hero__lead', a.description, function (v) { set({ description: v }); }, __('Description', 'smart-leading-net')),
								el('div', { className: 'hero__cta' },
									a.primaryShow === false ? null : btnEl(a.primaryLabel),
									a.secondaryShow === false ? null : btnEl(a.secondaryLabel, 'outline')
								)
							),
							el('figure', { className: 'hero__fig' },
								el('div', { className: 'map' },
									el('div', { className: 'lseo-map-deco', 'aria-hidden': true, dangerouslySetInnerHTML: { __html: MAP_DECORATION } }),
									inners(
										['smartleading/locel-seo-map-card'],
										[
											['smartleading/locel-seo-map-card', { variant: 'search', number: '1', label: 'A nearby customer searches', searchText: '[your service] near me' }],
											['smartleading/locel-seo-map-card', { variant: 'profile', number: '2', label: 'Business Profile', title: 'Your Business', meta: 'Category · Service area · Hours', chips: 'Call|Directions|Website' }],
											['smartleading/locel-seo-map-card', { variant: 'site', number: '2', label: 'Website', title: 'Service & location page', chips: 'Clear details|Next step' }],
											['smartleading/locel-seo-map-card', { variant: 'goal', number: '3', label: 'They take the next step', chips: 'Call|Quote request|Appointment|Visit' }],
										]
									)
								)
							)
						)
					)
				)
			);
		},
		save: innerSave,
	});

	registerBlockType('smartleading/locel-seo-map-card', {
		__experimentalLabel: function (attrs) {
			return MAP_CARD_NAMES[attrs.variant] || listLabel(attrs, __('Map Card', 'smart-leading-net'));
		},
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var variant = a.variant || 'search';
			var chips = chipItems(a.chips);
			var blockProps = useBlockProps({ className: 'mcard mcard--' + variant });
			return el(
				Fragment,
				null,
				el(InspectorControls, null,
					el(PanelBody, { title: __('Map card', 'smart-leading-net'), initialOpen: true },
						el(SelectControl, {
							label: __('Card type', 'smart-leading-net'),
							value: variant,
							options: [
								{ label: MAP_CARD_NAMES.search, value: 'search' },
								{ label: MAP_CARD_NAMES.profile, value: 'profile' },
								{ label: MAP_CARD_NAMES.site, value: 'site' },
								{ label: MAP_CARD_NAMES.goal, value: 'goal' },
							],
							onChange: function (v) { set({ variant: v }); },
						}),
						el(TextControl, { label: __('Step number', 'smart-leading-net'), value: a.number || '', onChange: function (v) { set({ number: v }); } }),
						el(TextControl, { label: __('Card heading', 'smart-leading-net'), value: a.label || '', onChange: function (v) { set({ label: v }); } }),
						variant === 'search'
							? el(TextControl, { label: __('Search text', 'smart-leading-net'), value: a.searchText || '', onChange: function (v) { set({ searchText: v }); } })
							: el(Fragment, null,
								el(TextControl, { label: __('Title', 'smart-leading-net'), value: a.title || '', onChange: function (v) { set({ title: v }); } }),
								el(TextControl, { label: __('Supporting text', 'smart-leading-net'), value: a.meta || '', onChange: function (v) { set({ meta: v }); } })
							),
						el(TextControl, {
							label: __('Action labels', 'smart-leading-net'),
							value: a.chips || '',
							help: __('Separate labels with |  e.g. Call|Directions|Website', 'smart-leading-net'),
							onChange: function (v) { set({ chips: v }); },
						})
					)
				),
				el('div', blockProps,
					el('p', { className: 'mcard__label' },
						el('span', { className: 'mcard__n', 'aria-hidden': true }, a.number || ''),
						rt('span', '', a.label, function (v) { set({ label: v }); }, __('Card heading', 'smart-leading-net'))
					),
					variant === 'search'
						? el('div', { className: 'mcard__search' },
							svgIcon('search'),
							rt('span', '', a.searchText, function (v) { set({ searchText: v }); })
						)
						: el(Fragment, null,
							rt('p', 'mcard__title', a.title, function (v) { set({ title: v }); }),
							rt('p', 'mcard__meta', a.meta, function (v) { set({ meta: v }); })
						),
					chips.length ? el('ul', { className: 'chips' + (variant === 'goal' ? ' chips--goal' : '') }, chips) : null
				)
			);
		},
		save: leafSave,
	});

	registerBlockType('smartleading/locel-seo-discovery', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'sls-locel-seo lseo-sec' });
			return el(Fragment, null,
				el(InspectorControls, null,
					headingPanel(tag, set),
					el(PanelBody, { title: __('Section text', 'smart-leading-net'), initialOpen: true },
						el(TextareaControl, { label: __('Heading', 'smart-leading-net'), value: stripHtml(a.heading), rows: 2, onChange: function (v) { set({ heading: v }); } }),
						el(TextareaControl, { label: __('Introduction', 'smart-leading-net'), value: stripHtml(a.lead), rows: 3, onChange: function (v) { set({ lead: v }); } }),
						el(TextControl, { label: __('Connector label', 'smart-leading-net'), value: stripHtml(a.connectorLabel), onChange: function (v) { set({ connectorLabel: v }); } }),
						el(TextareaControl, { label: __('Goal label', 'smart-leading-net'), value: stripHtml(a.goalText), rows: 2, onChange: function (v) { set({ goalText: v }); } }),
						el(TextareaControl, { label: __('Ranking factors title', 'smart-leading-net'), value: stripHtml(a.factorsTitle), rows: 2, onChange: function (v) { set({ factorsTitle: v }); } }),
						el(TextareaControl, { label: __('Ranking factors note', 'smart-leading-net'), value: stripHtml(a.factorsNote), rows: 2, onChange: function (v) { set({ factorsNote: v }); } })
					)
				),
				el('section', blockProps,
					el('div', { className: 'lseo-wrap' },
						el('div', { className: 'head' },
							rt(tag, 'title', a.heading, function (v) { set({ heading: v }); }),
							rt('p', 'lead', a.lead, function (v) { set({ lead: v }); })
						),
						el('div', { className: 'lseo-discovery-edit' },
							inners(
								['smartleading/locel-seo-stop', 'smartleading/locel-seo-merge-col', 'smartleading/locel-seo-factor'],
								[
									['smartleading/locel-seo-stop', { icon: 'pin', title: 'Profile discovery', text: 'An accurate, complete Business Profile helps you appear for searches that genuinely match what you do.' }],
									['smartleading/locel-seo-stop', { icon: 'web', title: 'Relevant website information', text: 'Clear service and location pages answer the questions people have before they decide to contact you.' }],
									['smartleading/locel-seo-stop', { icon: 'phone', title: 'A clear call, booking or quote path', text: 'Phone numbers, forms and booking links that are easy to find make it simple to take the next step.' }],
									['smartleading/locel-seo-merge-col', { title: 'Map and Business Profile visibility', text: 'How your business shows up in map results and local listings.', tick1: 'Profile categories, services and details', tick2: 'Consistent business information across listings', tick3: 'Genuine customer reviews' }],
									['smartleading/locel-seo-merge-col', { title: 'Website performance', text: 'How well your site is found, understood and used once people arrive.', tick1: 'Service and location content', tick2: 'Technical health and page structure', tick3: 'Easy-to-find contact options' }],
									['smartleading/locel-seo-factor', { art: 'relevance', title: 'Relevance', text: 'How well your profile and information match what someone is searching for.' }],
									['smartleading/locel-seo-factor', { art: 'distance', title: 'Distance', text: 'How far your business is from the searcher or the location they search. This is not something any agency controls.' }],
									['smartleading/locel-seo-factor', { art: 'prominence', title: 'Prominence', text: 'How well known your business is, including reviews and information about you across the web.' }],
								]
							)
						),
						el('p', { className: 'merge__link' },
							svgIcon('link'),
							rt('span', '', a.connectorLabel, function (v) { set({ connectorLabel: v }); }, 'Connected, but different')
						),
						el('p', { className: 'merge__goal' },
							svgIcon('target'),
							rt('span', '', a.goalText, function (v) { set({ goalText: v }); })
						),
						el('div', { className: 'factors__intro' },
							rt('strong', '', a.factorsTitle, function (v) { set({ factorsTitle: v }); }),
							rt('span', '', a.factorsNote, function (v) { set({ factorsNote: v }); })
						)
					)
				)
			);
		},
		save: innerSave,
	});

	function simpleItem(name, className, fields, layout) {
		registerBlockType(name, {
			__experimentalLabel: function (attrs) {
				return listLabel(attrs, undefined);
			},
			edit: function (props) {
				var a = props.attributes;
				var set = props.setAttributes;
				var extraClass = className || '';
				if (a.wide) {
					extraClass += ' tile--wide';
				}
				if (a.blue) {
					extraClass += ' tile--blue';
				}
				var blockProps = useBlockProps({ className: extraClass });
				var kids;
				if (layout === 'challenge') {
					kids = [
						el('div', { className: 'signcard__top' },
							el('span', { className: 'signcard__icon', 'aria-hidden': true }, svgIcon(a.icon || 'searchx')),
							rt('h3', '', a.title, function (v) { set({ title: v }); }, __('Card heading', 'smart-leading-net'))
						),
						rt('p', '', a.text, function (v) { set({ text: v }); }, __('Description', 'smart-leading-net')),
						rt('p', 'signcard__sign', a.sign, function (v) { set({ sign: v }); }, __('Possible sign', 'smart-leading-net'))
					];
				} else if (layout === 'stop') {
					kids = [
						el('span', { className: 'stop__pin', 'aria-hidden': true }, svgIcon(a.icon || 'pin')),
						el('div', null,
							rt('h3', '', a.title, function (v) { set({ title: v }); }, __('Step heading', 'smart-leading-net')),
							rt('p', '', a.text, function (v) { set({ text: v }); }, __('Step description', 'smart-leading-net'))
						)
					];
				} else if (layout === 'factor') {
					kids = [
						el('div', { className: 'factor__art', dangerouslySetInnerHTML: { __html: factorArt(a.art) } }),
						rt('h3', '', a.title, function (v) { set({ title: v }); }, __('Factor name', 'smart-leading-net')),
						rt('p', '', a.text, function (v) { set({ text: v }); }, __('Factor description', 'smart-leading-net'))
					];
				} else if (layout === 'merge') {
					kids = [
						rt('h3', '', a.title, function (v) { set({ title: v }); }),
						rt('p', '', a.text, function (v) { set({ text: v }); }),
						el('ul', { className: 'ticks' },
							el('li', null, svgIcon('tick'), rt('span', '', a.tick1, function (v) { set({ tick1: v }); })),
							el('li', null, svgIcon('tick'), rt('span', '', a.tick2, function (v) { set({ tick2: v }); })),
							el('li', null, svgIcon('tick'), rt('span', '', a.tick3, function (v) { set({ tick3: v }); }))
						)
					];
				} else if (layout === 'point') {
					kids = [
						el('span', { className: 'points__n', 'aria-hidden': true }, a.number || ''),
						el('div', null,
							rt('h3', '', a.title, function (v) { set({ title: v }); }),
							rt('p', '', a.text, function (v) { set({ text: v }); })
						)
					];
				} else if (layout === 'tile') {
					kids = [
						el('div', { className: 'tile__body' },
							el('span', { className: 'tile__icon', 'aria-hidden': true }, svgIcon(a.icon || 'shop')),
							rt('h3', '', a.title, function (v) { set({ title: v }); }),
							rt('p', '', a.text, function (v) { set({ text: v }); }),
							el('p', { className: 'tile__focus' },
								el('b', null, __('Focus:', 'smart-leading-net') + ' '),
								rt('span', '', a.focus, function (v) { set({ focus: v }); })
							)
						)
					];
				} else if (layout === 'funnel') {
					kids = [
						el('span', { className: 'funnel__icon', 'aria-hidden': true }, svgIcon(a.icon || 'eye')),
						el('div', null,
							rt('h3', '', a.title, function (v) { set({ title: v }); }),
							rt('p', '', a.text, function (v) { set({ text: v }); }),
							rt('span', 'funnel__q', a.question, function (v) { set({ question: v }); })
						)
					];
				} else if (layout === 'slide') {
					kids = [
						el('div', null,
							el('span', { className: 'tag' }, a.tag || __('Concept', 'smart-leading-net')),
							rt('h3', '', a.title, function (v) { set({ title: v }); }),
							rt('p', '', a.text, function (v) { set({ text: v }); }),
							el('ul', null,
								el('li', null, rt('span', '', a.item1, function (v) { set({ item1: v }); })),
								el('li', null, rt('span', '', a.item2, function (v) { set({ item2: v }); })),
								el('li', null, rt('span', '', a.item3, function (v) { set({ item3: v }); }))
							)
						),
						el('div', { className: 'slide__mock', 'aria-hidden': true })
					];
				} else if (layout === 'value') {
					kids = [
						el('span', { className: 'value__icon', 'aria-hidden': true }, svgIcon(a.icon || 'priorities')),
						rt('h3', '', a.title, function (v) { set({ title: v }); }),
						rt('p', '', a.text, function (v) { set({ text: v }); })
					];
				} else if (layout === 'compare') {
					kids = [
						el('div', { className: 'lseo-compare-row__label' }, rt('strong', '', a.label, function (v) { set({ label: v }); })),
						el('div', { className: 'cell is-sls' }, svgIcon('tick'), rt('span', '', a.sls, function (v) { set({ sls: v }); })),
						el('div', { className: 'cell cell--dot' }, svgIcon('tick'), rt('span', '', a.agency, function (v) { set({ agency: v }); })),
						el('div', { className: 'cell cell--dot' }, svgIcon('tick'), rt('span', '', a.diy, function (v) { set({ diy: v }); }))
					];
				} else if (layout === 'faq') {
					kids = [
						el('h3', null, rt('span', '', a.question, function (v) { set({ question: v }); }, __('Question', 'smart-leading-net'))),
						el('div', { className: 'faq-a' }, rt('p', '', a.answer, function (v) { set({ answer: v }); }, __('Answer', 'smart-leading-net')))
					];
				} else if (layout === 'stair') {
					kids = [
						rt('p', 'stair__n', a.number, function (v) { set({ number: v }); }),
						rt('h3', '', a.title, function (v) { set({ title: v }); }),
						rt('p', '', a.text, function (v) { set({ text: v }); })
					];
				} else {
					kids = [];
					if (a.icon) {
						kids.push(el('span', { className: 'lseo-card-icon', 'aria-hidden': true }, svgIcon(a.icon)));
					}
					fields.forEach(function (f) {
						kids.push(rt(f.tag || 'p', f.className || '', a[f.key], function (v) {
							var n = {};
							n[f.key] = v;
							set(n);
						}, f.placeholder));
					});
				}
				var contentFields = fields.length ? fields : [
					{ key: 'title', label: __('Heading', 'smart-leading-net') },
					{ key: 'text', label: __('Description', 'smart-leading-net') },
					{ key: 'sign', label: __('Possible sign', 'smart-leading-net') },
					{ key: 'question', label: __('Question', 'smart-leading-net') },
					{ key: 'answer', label: __('Answer', 'smart-leading-net') },
					{ key: 'tick1', label: __('Bullet 1', 'smart-leading-net') },
					{ key: 'tick2', label: __('Bullet 2', 'smart-leading-net') },
					{ key: 'tick3', label: __('Bullet 3', 'smart-leading-net') },
				];
				var contentControls = contentFields.filter(function (f) {
					return a[f.key] !== undefined;
				}).map(function (f) {
					return el(TextareaControl, {
						label: f.label || f.key,
						value: stripHtml(a[f.key]),
						rows: (f.key === 'answer' || f.key === 'text') ? 4 : 2,
						onChange: function (v) {
							var n = {};
							n[f.key] = v;
							set(n);
						},
					});
				});
				var extra = [
					el(InspectorControls, null,
						contentControls.length ? el(PanelBody, { title: __('Content', 'smart-leading-net'), initialOpen: true }, contentControls) : null,
						el(PanelBody, { title: __('Card settings', 'smart-leading-net'), initialOpen: false },
							a.icon !== undefined ? el(SelectControl, {
								label: __('Icon', 'smart-leading-net'),
								value: a.icon || 'pin',
								options: ICON_OPTIONS,
								onChange: function (v) { set({ icon: v }); },
								help: __('Choose a preset illustration. You do not need to edit SVG code.', 'smart-leading-net'),
							}) : null,
							a.art !== undefined ? el(SelectControl, {
								label: __('Illustration', 'smart-leading-net'),
								value: a.art || 'relevance',
								options: [
									{ label: __('Relevance', 'smart-leading-net'), value: 'relevance' },
									{ label: __('Distance', 'smart-leading-net'), value: 'distance' },
									{ label: __('Prominence', 'smart-leading-net'), value: 'prominence' },
								],
								onChange: function (v) { set({ art: v }); },
							}) : null,
							a.wide !== undefined ? el(ToggleControl, { label: __('Wide card', 'smart-leading-net'), checked: !!a.wide, onChange: function (v) { set({ wide: v }); } }) : null,
							a.blue !== undefined ? el(ToggleControl, { label: __('Blue card', 'smart-leading-net'), checked: !!a.blue, onChange: function (v) { set({ blue: v }); } }) : null,
							/* FAQ items stay closed on the published page; do not persist an open-by-default toggle. */
							a.mock !== undefined ? el(SelectControl, {
								label: __('Illustration mock', 'smart-leading-net'),
								value: a.mock || 'none',
								options: [
									{ label: __('None', 'smart-leading-net'), value: 'none' },
									{ label: __('Profile', 'smart-leading-net'), value: 'profile' },
									{ label: __('Meters', 'smart-leading-net'), value: 'meters' },
									{ label: __('Listings', 'smart-leading-net'), value: 'listings' },
								],
								onChange: function (v) { set({ mock: v }); },
							}) : null
						)
					),
				];
				return el(Fragment, null, extra, el('div', blockProps, kids));
			},
			save: leafSave,
		});
	}

	simpleItem('smartleading/locel-seo-stop', 'stop', [], 'stop');
	simpleItem('smartleading/locel-seo-merge-col', 'merge__col card', [], 'merge');
	simpleItem('smartleading/locel-seo-factor', 'factor', [], 'factor');

	registerBlockType('smartleading/locel-seo-challenges', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'sls-locel-seo lseo-sec lseo-sec--soft' });
			return el(Fragment, null,
				el(InspectorControls, null,
					headingPanel(tag, set),
					el(PanelBody, { title: __('Section text', 'smart-leading-net'), initialOpen: true },
						el(TextareaControl, { label: __('Heading', 'smart-leading-net'), value: stripHtml(a.heading), rows: 2, onChange: function (v) { set({ heading: v }); } }),
						el(TextareaControl, { label: __('Introduction', 'smart-leading-net'), value: stripHtml(a.lead), rows: 3, onChange: function (v) { set({ lead: v }); } })
					),
					buttonPanel(__('Button', 'smart-leading-net'), 'primary', a, set)
				),
				el('section', blockProps,
					el('div', { className: 'lseo-wrap' },
						el('div', { className: 'head' },
							rt(tag, 'title', a.heading, function (v) { set({ heading: v }); }),
							el('div', { className: 'head__side' },
								rt('p', 'lead', a.lead, function (v) { set({ lead: v }); }),
								btnEl(a.primaryLabel, 'outline')
							)
						),
						el('div', { className: 'signs' },
							inners(['smartleading/locel-seo-challenge'], [
								['smartleading/locel-seo-challenge', { icon: 'searchx', title: 'Are you hard to find for the services you actually offer?', text: 'Your profile or pages may not clearly reflect the work you do, so you may not appear for relevant searches.', sign: 'Possible sign: few profile views for core services' }],
								['smartleading/locel-seo-challenge', { icon: 'pinq', title: 'Is your location and service-area information unclear?', text: 'Inconsistent addresses, service areas or hours can confuse customers and search engines alike.', sign: 'Possible sign: details differ between listings' }],
								['smartleading/locel-seo-challenge', { icon: 'pointer', title: 'Are people visiting without getting in touch?', text: 'Visitors may arrive but not find what they need, or not see an easy way to call, book or ask for a quote.', sign: 'Possible sign: visits, but few calls or forms' }],
								['smartleading/locel-seo-challenge', { icon: 'chartq', title: 'Are you unsure what your marketing is actually producing?', text: 'Reports full of rankings and traffic can make it hard to see whether real enquiries are coming in.', sign: 'Possible sign: reports you can’t act on' }],
							])
						)
					)
				)
			);
		},
		save: innerSave,
	});
	simpleItem('smartleading/locel-seo-challenge', 'signcard card', [], 'challenge');

	registerBlockType('smartleading/locel-seo-beyond', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'sls-locel-seo lseo-sec band on-dark' });
			return el(Fragment, null,
				el(InspectorControls, null,
					headingPanel(tag, set),
					copyPanel(__('Heading & content', 'smart-leading-net'), [
						{ key: 'eyebrow', label: __('Eyebrow', 'smart-leading-net') },
						{ key: 'heading', label: __('Heading', 'smart-leading-net'), rows: 2, strip: true },
						{ key: 'lead', label: __('Introduction', 'smart-leading-net'), rows: 3, strip: true },
					], a, set),
					el(PanelBody, { title: __('Images & media', 'smart-leading-net'), initialOpen: true },
						el(TextControl, {
							label: __('Video URL', 'smart-leading-net'),
							value: a.videoUrl || DEFAULT_BEYOND_VIDEO,
							onChange: function (v) { set({ videoUrl: v }); },
							help: __('Paste an Instagram Reel, YouTube or Vimeo URL. The published page uses the official Instagram embed for Reel links.', 'smart-leading-net'),
						}),
						el(ExternalLink, { href: a.videoUrl || DEFAULT_BEYOND_VIDEO }, __('Open Reel', 'smart-leading-net')),
						el(TextControl, { label: __('Placeholder label', 'smart-leading-net'), value: a.placeholderTag || '', onChange: function (v) { set({ placeholderTag: v }); } }),
						el(TextControl, { label: __('Video title', 'smart-leading-net'), value: a.videoTitle || '', onChange: function (v) { set({ videoTitle: v }); } }),
						el(TextareaControl, { label: __('Caption', 'smart-leading-net'), value: stripHtml(a.videoCaption), rows: 2, onChange: function (v) { set({ videoCaption: v }); } })
					)
				),
				el('section', blockProps,
					el('div', { className: 'lseo-wrap' },
						el('div', { className: 'head head--center' },
							rt('p', 'eyebrow eyebrow--center', a.eyebrow, function (v) { set({ eyebrow: v }); }),
							rt(tag, 'title', a.heading, function (v) { set({ heading: v }); }),
							rt('p', 'lead', a.lead, function (v) { set({ lead: v }); })
						),
						el('figure', { className: 'video' },
							el('div', { className: 'video__frame is-reel is-placeholder' },
								el('span', { className: 'tag' }, __('Instagram Reel', 'smart-leading-net')),
								el('div', { className: 'video__inner' },
									rt('p', 'video__title', a.videoTitle, function (v) { set({ videoTitle: v }); }),
									el('span', { className: 'video__play', 'aria-hidden': true }, svgIcon('play'))
								)
							),
							rt('figcaption', '', a.videoCaption, function (v) { set({ videoCaption: v }); }),
							el('p', { className: 'video__watch-preview' },
								el(ExternalLink, { href: a.videoUrl || DEFAULT_BEYOND_VIDEO }, __('Open Reel', 'smart-leading-net'))
							)
						),
						el('div', { className: 'points' },
							inners(['smartleading/locel-seo-point'], [
								['smartleading/locel-seo-point', { number: '1', title: 'Start from your business goals', text: 'The services, areas and customers that matter most to you set the priorities.' }],
								['smartleading/locel-seo-point', { number: '2', title: 'Remove what stands between searches and enquiries', text: 'Profile, listing, content and website issues are tackled in order of likely impact.' }],
								['smartleading/locel-seo-point', { number: '3', title: 'Measure enquiries, not just rankings', text: 'Reporting looks at recorded enquiries and, where you can share them, real outcomes.' }],
							])
						)
					)
				)
			);
		},
		save: innerSave,
	});
	simpleItem('smartleading/locel-seo-point', '', [
		{ key: 'number', tag: 'p', label: __('Number', 'smart-leading-net') },
		{ key: 'title', tag: 'h3' },
		{ key: 'text', tag: 'p' },
	], 'point');

	registerBlockType('smartleading/locel-seo-approach', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'sls-locel-seo lseo-sec' });
			return el(Fragment, null,
				el(InspectorControls, null,
					headingPanel(tag, set),
					el(PanelBody, { title: __('Section text', 'smart-leading-net'), initialOpen: true },
						el(TextControl, { label: __('Eyebrow', 'smart-leading-net'), value: a.eyebrow || '', onChange: function (v) { set({ eyebrow: v }); } }),
						el(TextareaControl, { label: __('Heading', 'smart-leading-net'), value: stripHtml(a.heading), rows: 2, onChange: function (v) { set({ heading: v }); } }),
						el(TextareaControl, { label: __('Introduction', 'smart-leading-net'), value: stripHtml(a.lead), rows: 3, onChange: function (v) { set({ lead: v }); } })
					)
				),
				el('section', blockProps,
					el('div', { className: 'lseo-wrap' },
						el('div', { className: 'head' },
							el('div', null,
								rt('p', 'eyebrow', a.eyebrow, function (v) { set({ eyebrow: v }); }),
								rt(tag, 'title', a.heading, function (v) { set({ heading: v }); })
							),
							rt('p', 'lead', a.lead, function (v) { set({ lead: v }); })
						),
						el('div', { className: 'bento' },
							inners(['smartleading/locel-seo-tile'], [
								['smartleading/locel-seo-tile', { icon: 'shop', wide: true, blue: true, mock: 'profile', title: 'Business Profile review & optimization', text: 'Check categories, services, hours, photos and details so your profile reflects the real business accurately.', focus: 'relevance and completeness' }],
								['smartleading/locel-seo-tile', { icon: 'searchplus', title: 'Local search & competitor research', text: 'Understand which searches matter in your service area and how nearby competitors present themselves.', focus: 'realistic local opportunities' }],
								['smartleading/locel-seo-tile', { icon: 'doc', title: 'Service & location content', text: 'Pages that explain what you do and where you do it, built around places you genuinely serve.', focus: 'answering customer questions' }],
								['smartleading/locel-seo-tile', { icon: 'wrench', wide: true, mock: 'meters', title: 'Technical & on-page improvement', text: 'Address crawl, speed, structure and on-page issues that make your website harder to find and use.', focus: 'a site search engines can understand' }],
								['smartleading/locel-seo-tile', { icon: 'list', wide: true, mock: 'listings', title: 'Listing accuracy', text: 'Keep your business name, address, phone number and key details consistent across the listings that matter.', focus: 'consistent, trustworthy information' }],
								['smartleading/locel-seo-tile', { icon: 'chat', title: 'Review-process guidance', text: 'A simple, guideline-friendly way to ask real customers for feedback. No fake, bought or filtered reviews.', focus: 'genuine customer proof' }],
							])
						)
					)
				)
			);
		},
		save: innerSave,
	});
	simpleItem('smartleading/locel-seo-tile', 'tile', [
		{ key: 'title', tag: 'h3' },
		{ key: 'text', tag: 'p' },
		{ key: 'focus', tag: 'p', className: 'tile__focus', label: __('Focus label', 'smart-leading-net') },
	], 'tile');

	registerBlockType('smartleading/locel-seo-ticket', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'sls-locel-seo ticket-sec' });
			return el(Fragment, null,
				el(InspectorControls, null,
					headingPanel(tag, set),
					copyPanel(__('Heading & content', 'smart-leading-net'), [
						{ key: 'heading', label: __('Heading', 'smart-leading-net'), rows: 2, strip: true },
						{ key: 'text', label: __('Description', 'smart-leading-net'), rows: 3, strip: true },
					], a, set),
					buttonPanel(__('Buttons & links', 'smart-leading-net'), 'primary', a, set)
				),
				el('section', blockProps,
					el('div', { className: 'lseo-wrap' },
						el('div', { className: 'ticket on-dark' },
							el('div', { className: 'ticket__main' },
								svgIcon('form'),
								el('div', null,
									rt(tag, '', a.heading, function (v) { set({ heading: v }); }),
									rt('p', '', a.text, function (v) { set({ text: v }); })
								)
							),
							el('div', { className: 'ticket__cta' }, btnEl(a.primaryLabel, 'light'))
						)
					)
				)
			);
		},
		save: leafSave,
	});

	registerBlockType('smartleading/locel-seo-case', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'sls-locel-seo lseo-sec lseo-sec--warm' });
			return el(Fragment, null,
				el(InspectorControls, null,
					headingPanel(tag, set),
					copyPanel(__('Heading & content', 'smart-leading-net'), [
						{ key: 'banner', label: __('Demo notice', 'smart-leading-net'), rows: 2, strip: true },
						{ key: 'eyebrow', label: __('Eyebrow', 'smart-leading-net') },
						{ key: 'heading', label: __('Heading', 'smart-leading-net'), rows: 2, strip: true },
					], a, set),
					el(PanelBody, { title: __('Metric card', 'smart-leading-net'), initialOpen: true },
						el(TextControl, { label: __('From value', 'smart-leading-net'), value: a.metricFrom || '', onChange: function (v) { set({ metricFrom: v }); } }),
						el(TextControl, { label: __('To value', 'smart-leading-net'), value: a.metricTo || '', onChange: function (v) { set({ metricTo: v }); } }),
						el(TextControl, { label: __('Unit label', 'smart-leading-net'), value: a.metricUnit || '', onChange: function (v) { set({ metricUnit: v }); } }),
						el(TextControl, { label: __('Caption', 'smart-leading-net'), value: a.metricCap || '', onChange: function (v) { set({ metricCap: v }); } }),
						el(TextControl, { label: __('Client / logo label', 'smart-leading-net'), value: a.metricLogo || '', help: __('Keep unverified figures labelled as sample or pending.', 'smart-leading-net'), onChange: function (v) { set({ metricLogo: v }); } }),
						el(TextControl, { label: __('Tag', 'smart-leading-net'), value: a.metricTag || '', onChange: function (v) { set({ metricTag: v }); } })
					)
				),
				el('section', blockProps,
					el('div', { className: 'lseo-wrap' },
						rt('p', 'demo-banner', a.banner, function (v) { set({ banner: v }); }),
						rt('p', 'eyebrow', a.eyebrow, function (v) { set({ eyebrow: v }); }),
						rt(tag, 'title', a.heading, function (v) { set({ heading: v }); }),
						el('div', { className: 'case' },
							el('div', { className: 'story' },
								inners(['smartleading/locel-seo-story-item'], [
									['smartleading/locel-seo-story-item', { label: 'Challenge — sample', text: '[Verified summary of the business’s starting situation and what was holding back local enquiries. Two to three sentences.]' }],
									['smartleading/locel-seo-story-item', { label: 'Work — sample', text: '[Verified list of the Local SEO work actually carried out, e.g. profile, content or technical changes. Two to four short points.]' }],
									['smartleading/locel-seo-story-item', { label: 'Outcome — awaiting verified results', text: '[Verified outcome, measurement method and period, published only with client permission.]' }],
								])
							),
							el('div', { className: 'metric' },
								el('span', { className: 'tag' }, a.metricTag || __('Sample', 'smart-leading-net')),
								el('p', { className: 'metric__value' }, a.metricFrom || '', ' → ', a.metricTo || ''),
								rt('p', 'metric__unit', a.metricUnit, function (v) { set({ metricUnit: v }); }),
								rt('p', 'metric__cap', a.metricCap, function (v) { set({ metricCap: v }); }),
								rt('p', 'metric__logo', a.metricLogo, function (v) { set({ metricLogo: v }); })
							)
						)
					)
				)
			);
		},
		save: innerSave,
	});
	simpleItem('smartleading/locel-seo-story-item', '', [
		{ key: 'label', tag: 'p', className: 'story__label' },
		{ key: 'text', tag: 'p' },
	]);

	registerBlockType('smartleading/locel-seo-process', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'sls-locel-seo lseo-sec' });
			return el(Fragment, null,
				el(InspectorControls, null,
					headingPanel(tag, set),
					el(PanelBody, { title: __('Section text', 'smart-leading-net'), initialOpen: true },
						el(TextControl, { label: __('Eyebrow', 'smart-leading-net'), value: a.eyebrow || '', onChange: function (v) { set({ eyebrow: v }); } }),
						el(TextareaControl, { label: __('Heading', 'smart-leading-net'), value: stripHtml(a.heading), rows: 2, onChange: function (v) { set({ heading: v }); } }),
						el(TextareaControl, { label: __('Introduction', 'smart-leading-net'), value: stripHtml(a.lead), rows: 3, onChange: function (v) { set({ lead: v }); } }),
						el(TextareaControl, { label: __('Footnote', 'smart-leading-net'), value: stripHtml(a.note), rows: 2, onChange: function (v) { set({ note: v }); } })
					)
				),
				el('section', blockProps,
					el('div', { className: 'lseo-wrap' },
						rt('p', 'eyebrow', a.eyebrow, function (v) { set({ eyebrow: v }); }),
						rt(tag, 'title', a.heading, function (v) { set({ heading: v }); }),
						rt('p', 'lead', a.lead, function (v) { set({ lead: v }); }),
						el('div', { className: 'stairs' },
							inners(['smartleading/locel-seo-stair'], [
								['smartleading/locel-seo-stair', { number: '01', title: 'Understand your business', text: 'Your services, the areas you serve and the kinds of enquiries that matter most to you.' }],
								['smartleading/locel-seo-stair', { number: '02', title: 'Assess opportunities', text: 'Review your profile, website and local search landscape to see where the gaps are.' }],
								['smartleading/locel-seo-stair', { number: '03', title: 'Agree priorities', text: 'Decide together what to tackle first, based on likely impact and what’s realistic.' }],
								['smartleading/locel-seo-stair', { number: '04', title: 'Implement and review', text: 'Carry out the agreed work, then review what changed and decide what comes next.' }],
							])
						),
						rt('p', 'foot-note', a.note, function (v) { set({ note: v }); })
					)
				)
			);
		},
		save: innerSave,
	});
	simpleItem('smartleading/locel-seo-stair', 'stair card', [
		{ key: 'number', tag: 'p', className: 'stair__n', label: __('Step number', 'smart-leading-net') },
		{ key: 'title', tag: 'h3' },
		{ key: 'text', tag: 'p' },
	], 'stair');

	registerBlockType('smartleading/locel-seo-reporting', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'sls-locel-seo lseo-sec lseo-sec--soft' });
			return el(Fragment, null,
				el(InspectorControls, null,
					headingPanel(tag, set),
					copyPanel(__('Heading & content', 'smart-leading-net'), [
						{ key: 'eyebrow', label: __('Eyebrow', 'smart-leading-net') },
						{ key: 'heading', label: __('Heading', 'smart-leading-net'), rows: 2, strip: true },
						{ key: 'lead', label: __('Introduction', 'smart-leading-net'), rows: 3, strip: true },
					], a, set),
					el(PanelBody, { title: __('CRM illustration labels', 'smart-leading-net'), initialOpen: false },
						el(TextControl, { label: __('Demo note', 'smart-leading-net'), value: a.crmNote || '', onChange: function (v) { set({ crmNote: v }); } }),
						el(TextControl, { label: __('Brand', 'smart-leading-net'), value: a.crmBrand || '', onChange: function (v) { set({ crmBrand: v }); } }),
						el(TextControl, { label: __('Tab 1', 'smart-leading-net'), value: a.tab1 || '', onChange: function (v) { set({ tab1: v }); } }),
						el(TextControl, { label: __('Tab 2', 'smart-leading-net'), value: a.tab2 || '', onChange: function (v) { set({ tab2: v }); } }),
						el(TextControl, { label: __('Tab 3', 'smart-leading-net'), value: a.tab3 || '', onChange: function (v) { set({ tab3: v }); } })
					)
				),
				el('section', blockProps,
					el('div', { className: 'lseo-wrap report' },
						el('div', null,
							rt('p', 'eyebrow', a.eyebrow, function (v) { set({ eyebrow: v }); }),
							rt(tag, 'title', a.heading, function (v) { set({ heading: v }); }),
							rt('p', 'lead', a.lead, function (v) { set({ lead: v }); }),
							el('div', { className: 'funnel' },
								inners(['smartleading/locel-seo-funnel-step'], [
									['smartleading/locel-seo-funnel-step', { icon: 'eye', title: 'Search visibility', text: 'Where and how often your business appears in local searches.', question: 'Are people seeing you?' }],
									['smartleading/locel-seo-funnel-step', { icon: 'form', title: 'Recorded enquiries', text: 'Calls, forms and requests that can be tracked. Not every interaction is a lead.', question: 'Did they get in touch?' }],
									['smartleading/locel-seo-funnel-step', { icon: 'checksq', title: 'Qualified sales outcomes', text: 'Which enquiries were genuine opportunities, confirmed with your own records.', question: 'Did it turn into real business?' }],
								])
							)
						),
						el('figure', { className: 'crm' },
							rt('p', 'crm__note', a.crmNote, function (v) { set({ crmNote: v }); }),
							el('div', { className: 'crm__body' },
								el('div', { className: 'crm__top' },
									el('span', { className: 'crm__brand' }, a.crmBrand || 'SLS Leads CRM'),
									el('ul', { className: 'crm__tabs' },
										el('li', null, a.tab1 || 'Visibility'),
										el('li', { className: 'is-on' }, a.tab2 || 'Enquiries'),
										el('li', null, a.tab3 || 'Outcomes')
									)
								)
							)
						)
					)
				)
			);
		},
		save: innerSave,
	});
	simpleItem('smartleading/locel-seo-funnel-step', '', [
		{ key: 'title', tag: 'h3' },
		{ key: 'text', tag: 'p' },
		{ key: 'question', tag: 'p', className: 'funnel__q', label: __('Prompt', 'smart-leading-net') },
	], 'funnel');

	registerBlockType('smartleading/locel-seo-tech', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'sls-locel-seo lseo-sec' });
			return el(Fragment, null,
				el(InspectorControls, null,
					headingPanel(tag, set),
					copyPanel(__('Heading & content', 'smart-leading-net'), [
						{ key: 'eyebrow', label: __('Eyebrow', 'smart-leading-net') },
						{ key: 'heading', label: __('Heading', 'smart-leading-net'), rows: 2, strip: true },
						{ key: 'lead', label: __('Introduction', 'smart-leading-net'), rows: 3, strip: true },
						{ key: 'conceptTag', label: __('Concept tag', 'smart-leading-net') },
						{ key: 'linkLabel', label: __('CRM link label', 'smart-leading-net') },
						{ key: 'linkPending', label: __('Pending link note', 'smart-leading-net') },
					], a, set)
				),
				el('section', blockProps,
					el('div', { className: 'lseo-wrap' },
						el('div', { className: 'head' },
							el('div', null,
								rt('p', 'eyebrow', a.eyebrow, function (v) { set({ eyebrow: v }); }),
								rt(tag, 'title', a.heading, function (v) { set({ heading: v }); })
							),
							el('div', { className: 'tech-side' },
								rt('p', 'lead', a.lead, function (v) { set({ lead: v }); }),
								el('span', { className: 'tag' }, a.conceptTag || ''),
								el('p', { className: 'tech-link' },
									rt('span', '', a.linkLabel, function (v) { set({ linkLabel: v }); }),
									rt('span', 'tbc', a.linkPending, function (v) { set({ linkPending: v }); })
								)
							)
						),
						el('div', { className: 'slider' },
							el('div', { className: 'slider__track' },
								inners(['smartleading/locel-seo-slide', 'smartleading/locel-seo-stat'], [
									['smartleading/locel-seo-slide', { title: 'Bring lead sources together', text: 'See calls, form submissions and other recorded enquiries in one place instead of several tools.', item1: 'Source shown for each enquiry', item2: 'Calls and forms side by side', item3: 'One view for your team' }],
									['smartleading/locel-seo-slide', { title: 'Track enquiry status', text: 'Mark enquiries as new, reviewed, qualified or not a fit, so reports reflect real opportunities.', item1: 'Simple status labels', item2: 'Separate genuine enquiries from noise', item3: 'Notes for follow-up' }],
									['smartleading/locel-seo-slide', { title: 'Report on outcomes', text: 'Connect visibility, recorded enquiries and qualified outcomes in one reporting view.', item1: 'Three clear reporting levels', item2: 'Trends over agreed periods', item3: 'No claim of perfect attribution' }],
									['smartleading/locel-seo-slide', { title: 'Share progress clearly', text: 'Give owners a plain view of what changed, what it led to and what comes next.', item1: 'Plain-language summaries', item2: 'Agreed priorities alongside results', item3: 'Easy to share with your team' }],
									['smartleading/locel-seo-stat', {}],
									['smartleading/locel-seo-stat', {}],
									['smartleading/locel-seo-stat', {}],
								])
							),
							el('div', { className: 'slider__bar' },
								el('p', { className: 'slider__count' }, __('All slides are listed below so you can edit each one. Previous/next run on the published page.', 'smart-leading-net')),
								el('div', { className: 'slider__btns', 'aria-hidden': true },
									el('span', { className: 'lseo-slider-btn' }, '←'),
									el('span', { className: 'lseo-slider-btn' }, '→')
								)
							)
						)
					)
				)
			);
		},
		save: innerSave,
	});
	simpleItem('smartleading/locel-seo-slide', 'slide', [
		{ key: 'title', tag: 'h3' },
		{ key: 'text', tag: 'p' },
		{ key: 'item1', tag: 'p', label: __('Bullet 1', 'smart-leading-net') },
		{ key: 'item2', tag: 'p', label: __('Bullet 2', 'smart-leading-net') },
		{ key: 'item3', tag: 'p', label: __('Bullet 3', 'smart-leading-net') },
	], 'slide');
	simpleItem('smartleading/locel-seo-stat', '', [
		{ key: 'value', tag: 'p', className: 'tbc' },
		{ key: 'label', tag: 'strong' },
		{ key: 'note', tag: 'p', className: 'stats__note' },
	]);

	registerBlockType('smartleading/locel-seo-working', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'sls-locel-seo lseo-sec lseo-sec--soft' });
			return el(Fragment, null,
				el(InspectorControls, null,
					headingPanel(tag, set),
					copyPanel(__('Heading & content', 'smart-leading-net'), [
						{ key: 'eyebrow', label: __('Eyebrow', 'smart-leading-net') },
						{ key: 'heading', label: __('Heading', 'smart-leading-net'), rows: 2, strip: true },
						{ key: 'statement', label: __('Statement', 'smart-leading-net'), rows: 4, strip: true },
					], a, set)
				),
				el('section', blockProps,
					el('div', { className: 'lseo-wrap' },
						el('div', { className: 'head head--center' },
							rt('p', 'eyebrow eyebrow--center', a.eyebrow, function (v) { set({ eyebrow: v }); }),
							rt(tag, 'title', a.heading, function (v) { set({ heading: v }); })
						),
						el('svg', { className: 'quote-mark', viewBox: '0 0 54 42', 'aria-hidden': true, dangerouslySetInnerHTML: { __html: '<path fill="currentColor" d="M0 42V27C0 12.6 6.6 3.6 19.8 0l3 5.4C15.6 8.4 12.3 13.2 12 20.4h10.8V42zm31.2 0V27c0-14.4 6.6-23.4 19.8-27l3 5.4c-7.2 3-10.5 7.8-10.8 15H54V42z"></path>' } }),
						rt('p', 'statement', a.statement, function (v) { set({ statement: v }); }),
						el('div', { className: 'values' },
							inners(['smartleading/locel-seo-value'], [
								['smartleading/locel-seo-value', { icon: 'priorities', title: 'Clear priorities', text: 'Work is ordered by what is most likely to help, so effort isn’t spread across tasks that don’t matter yet.' }],
								['smartleading/locel-seo-value', { icon: 'clip', title: 'Explicit scope', text: 'You know what is included before work begins, and what isn’t.' }],
								['smartleading/locel-seo-value', { icon: 'bars', title: 'Meaningful reporting', text: 'Reports focused on enquiries and progress you can act on, not just a list of rankings.' }],
							])
						)
					)
				)
			);
		},
		save: innerSave,
	});
	simpleItem('smartleading/locel-seo-value', 'value', [
		{ key: 'title', tag: 'h3' },
		{ key: 'text', tag: 'p' },
	], 'value');

	registerBlockType('smartleading/locel-seo-compare', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'sls-locel-seo lseo-sec' });
			return el(Fragment, null,
				el(InspectorControls, null,
					headingPanel(tag, set),
					copyPanel(__('Heading & content', 'smart-leading-net'), [
						{ key: 'eyebrow', label: __('Eyebrow', 'smart-leading-net') },
						{ key: 'heading', label: __('Heading', 'smart-leading-net'), rows: 2, strip: true },
						{ key: 'lead', label: __('Introduction', 'smart-leading-net'), rows: 3, strip: true },
						{ key: 'foot', label: __('Supporting text', 'smart-leading-net'), rows: 2, strip: true },
					], a, set),
					el(PanelBody, { title: __('Column headings', 'smart-leading-net'), initialOpen: true },
						el(TextControl, { label: __('SLS name', 'smart-leading-net'), value: a.col1Name || '', onChange: function (v) { set({ col1Name: v }); } }),
						el(TextControl, { label: __('SLS subtitle', 'smart-leading-net'), value: a.col1Sub || '', onChange: function (v) { set({ col1Sub: v }); } }),
						el(TextControl, { label: __('Agency name', 'smart-leading-net'), value: a.col2Name || '', onChange: function (v) { set({ col2Name: v }); } }),
						el(TextControl, { label: __('DIY name', 'smart-leading-net'), value: a.col3Name || '', onChange: function (v) { set({ col3Name: v }); } })
					)
				),
				el('section', blockProps,
					el('div', { className: 'lseo-wrap' },
						el('div', { className: 'head head--center' },
							rt('p', 'eyebrow eyebrow--center', a.eyebrow, function (v) { set({ eyebrow: v }); }),
							rt(tag, 'title', a.heading, function (v) { set({ heading: v }); }),
							rt('p', 'lead', a.lead, function (v) { set({ lead: v }); })
						),
						el('div', { className: 'lseo-compare-head' },
							el('div', null, ''),
							el('div', { className: 'is-sls' }, el('strong', null, a.col1Name || ''), el('span', null, a.col1Sub || '')),
							el('div', null, el('strong', null, a.col2Name || '')),
							el('div', null, el('strong', null, a.col3Name || ''))
						),
						el('div', { className: 'lseo-compare-edit' },
							inners(['smartleading/locel-seo-compare-row'], [
								['smartleading/locel-seo-compare-row', { label: 'Who does the work', sls: 'The SLS team carries out the agreed work', agency: 'Some advise, some implement — ask before you sign', diy: 'Your team, usually alongside other duties' }],
								['smartleading/locel-seo-compare-row', { label: 'Local SEO focus', sls: 'Local SEO is a core service, across many industries', agency: 'Depends on the agency’s specialism', diy: 'You know your business best; SEO depth varies' }],
								['smartleading/locel-seo-compare-row', { label: 'Tools', sls: 'Agency tools plus SLS Leads CRM for lead data', agency: 'Varies — check what’s included in the fee', diy: 'You choose, learn and pay for your own tools' }],
								['smartleading/locel-seo-compare-row', { label: 'Reporting focus', sls: 'Enquiries and progress, not rankings alone', agency: 'Ask whether reports go beyond rankings and traffic', diy: 'As detailed as your team has time to make it' }],
								['smartleading/locel-seo-compare-row', { label: 'A good fit when', sls: 'You want an outside team with a clear scope', agency: 'Their scope and reporting match what you need', diy: 'You have the time, skills and steady capacity' }],
							])
						),
						rt('p', 'options-foot', a.foot, function (v) { set({ foot: v }); })
					)
				)
			);
		},
		save: innerSave,
	});
	simpleItem('smartleading/locel-seo-compare-row', 'lseo-compare-row', [
		{ key: 'label', tag: 'h3', label: __('Row label', 'smart-leading-net') },
		{ key: 'sls', tag: 'p', label: __('SLS column', 'smart-leading-net') },
		{ key: 'agency', tag: 'p', label: __('Agency column', 'smart-leading-net') },
		{ key: 'diy', tag: 'p', label: __('DIY column', 'smart-leading-net') },
	], 'compare');

	registerBlockType('smartleading/locel-seo-faq', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'sls-locel-seo lseo-sec lseo-sec--faq' });
			return el(Fragment, null,
				el(InspectorControls, null,
					headingPanel(tag, set),
					el(PanelBody, { title: __('Section text', 'smart-leading-net'), initialOpen: true },
						el(TextControl, { label: __('Eyebrow', 'smart-leading-net'), value: a.eyebrow || '', onChange: function (v) { set({ eyebrow: v }); } }),
						el(TextareaControl, { label: __('Heading', 'smart-leading-net'), value: stripHtml(a.heading), rows: 2, onChange: function (v) { set({ heading: v }); } }),
						el(TextareaControl, { label: __('Introduction', 'smart-leading-net'), value: stripHtml(a.lead), rows: 3, onChange: function (v) { set({ lead: v }); } })
					)
				),
				el('section', blockProps,
					el('div', { className: 'lseo-wrap' },
						rt('p', 'eyebrow eyebrow--center', a.eyebrow, function (v) { set({ eyebrow: v }); }),
						rt(tag, 'title', a.heading, function (v) { set({ heading: v }); }),
						rt('p', 'lead', a.lead, function (v) { set({ lead: v }); }),
						el('div', { className: 'faq-list' },
							inners(['smartleading/locel-seo-faq-item'], [
								['smartleading/locel-seo-faq-item', { question: 'What do local SEO services include?', answer: 'Local SEO usually covers your Business Profile, listing consistency, service and location content, technical and on-page website improvements, and guidance on earning genuine reviews. The right mix depends on where your business is starting from.' }],
								['smartleading/locel-seo-faq-item', { question: 'Is Local SEO the same as Business Profile management?', answer: 'No. Managing your Business Profile is one part of Local SEO. Your website, listings elsewhere and reviews also affect how relevant and trustworthy your business appears, and whether visitors get in touch.' }],
								['smartleading/locel-seo-faq-item', { question: 'What affects the time needed to see progress?', answer: 'It depends on your starting point, how competitive your market is, how much needs fixing, and how quickly changes can be made. Some fixes are quick; building content and reputation takes longer.' }],
								['smartleading/locel-seo-faq-item', { question: 'How do I choose the best local SEO company for my business?', answer: 'Look for a local SEO agency that explains what it will do and why, sets a clear scope, avoids guarantees about rankings, and reports on enquiries rather than rankings alone. Ask who owns your accounts and data before you start.' }],
								['smartleading/locel-seo-faq-item', { question: 'Can tools replace a local SEO service?', answer: 'Local SEO tools are useful for tracking listings, checking technical issues and monitoring reviews. They show you data; they don’t decide priorities, write content or make the fixes. Many businesses use tools alongside an expert.' }],
								['smartleading/locel-seo-faq-item', { question: 'What does the free audit include?', answer: '[Answer pending owner confirmation of audit scope]' }],
								['smartleading/locel-seo-faq-item', { question: 'How are enquiries measured?', answer: 'In general, enquiries are measured by tracking actions that can be recorded, such as calls, form submissions and booking requests, then reviewing which ones were genuine. No tracking setup captures every interaction perfectly. [SLS measurement method pending owner confirmation]' }],
							])
						)
					)
				)
			);
		},
		save: innerSave,
	});
	simpleItem('smartleading/locel-seo-faq-item', 'faq-item', [
		{ key: 'question', tag: 'h3', label: __('Question', 'smart-leading-net') },
		{ key: 'answer', tag: 'p', label: __('Answer', 'smart-leading-net') },
	], 'faq');

	registerBlockType('smartleading/locel-seo-audit', {
		edit: function (props) {
			var a = props.attributes;
			var set = props.setAttributes;
			var tag = a.headingTag || 'h2';
			var blockProps = useBlockProps({ className: 'sls-locel-seo lseo-sec audit on-dark' });
			return el(Fragment, null,
				el(InspectorControls, null,
					headingPanel(tag, set),
					copyPanel(__('Heading & content', 'smart-leading-net'), [
						{ key: 'eyebrow', label: __('Eyebrow', 'smart-leading-net') },
						{ key: 'heading', label: __('Heading', 'smart-leading-net'), rows: 2, strip: true },
						{ key: 'lead', label: __('Introduction', 'smart-leading-net'), rows: 3, strip: true },
						{ key: 'tick1', label: __('Supporting point 1', 'smart-leading-net') },
						{ key: 'tick2', label: __('Supporting point 2', 'smart-leading-net') },
					], a, set),
					el(PanelBody, { title: __('Form labels', 'smart-leading-net'), initialOpen: true },
						el(TextControl, { label: __('Name', 'smart-leading-net'), value: a.nameLabel || '', onChange: function (v) { set({ nameLabel: v }); } }),
						el(TextControl, { label: __('Email', 'smart-leading-net'), value: a.emailLabel || '', onChange: function (v) { set({ emailLabel: v }); } }),
						el(TextControl, { label: __('Website', 'smart-leading-net'), value: a.websiteLabel || '', onChange: function (v) { set({ websiteLabel: v }); } }),
						el(TextControl, { label: __('Optional label', 'smart-leading-net'), value: a.optionalLabel || '', onChange: function (v) { set({ optionalLabel: v }); } }),
						el(TextControl, { label: __('Website hint', 'smart-leading-net'), value: a.websiteHint || '', onChange: function (v) { set({ websiteHint: v }); } }),
						el(TextControl, { label: __('Area', 'smart-leading-net'), value: a.areaLabel || '', onChange: function (v) { set({ areaLabel: v }); } }),
						el(TextareaControl, { label: __('Extra field label', 'smart-leading-net'), value: a.extraLabel || '', rows: 2, onChange: function (v) { set({ extraLabel: v }); } }),
						el(TextControl, { label: __('Submit button', 'smart-leading-net'), value: a.buttonLabel || '', onChange: function (v) { set({ buttonLabel: v }); } }),
						el(TextareaControl, { label: __('Privacy text', 'smart-leading-net'), value: a.privacyText || '', rows: 3, onChange: function (v) { set({ privacyText: v }); } }),
						el(TextControl, { label: __('Privacy URL', 'smart-leading-net'), value: a.privacyUrl || '', onChange: function (v) { set({ privacyUrl: v }); } }),
						el(TextControl, { label: __('Privacy link label', 'smart-leading-net'), value: a.privacyLinkLabel || '', onChange: function (v) { set({ privacyLinkLabel: v }); } })
					)
				),
				el('section', blockProps,
					el('div', { className: 'lseo-wrap' },
						el('div', null,
							rt('p', 'eyebrow', a.eyebrow, function (v) { set({ eyebrow: v }); }),
							rt(tag, 'title', a.heading, function (v) { set({ heading: v }); }),
							rt('p', 'lead', a.lead, function (v) { set({ lead: v }); }),
							el('ul', { className: 'ticks' },
								el('li', null, svgIcon('tick'), rt('span', '', a.tick1, function (v) { set({ tick1: v }); })),
								el('li', null, svgIcon('tick'), rt('span', '', a.tick2, function (v) { set({ tick2: v }); }))
							)
						),
						el('div', { className: 'form-card' },
							el('label', null, a.nameLabel),
							el('label', null, a.emailLabel),
							el('label', null, a.websiteLabel, ' ', el('span', null, a.optionalLabel), el('small', null, a.websiteHint)),
							el('label', null, a.areaLabel),
							el('label', null, a.extraLabel),
							btnEl(a.buttonLabel),
							rt('p', 'privacy', a.privacyText, function (v) { set({ privacyText: v }); })
						)
					)
				)
			);
		},
		save: leafSave,
	});
})(window.wp);
