const fs = require('fs');
const path = require('path');
const root = __dirname;

function supports() {
	return { anchor: true, customClassName: true, html: false, align: false, className: true };
}
function str(def) {
	return { type: 'string', default: def || '' };
}
function bool(def) {
	return { type: 'boolean', default: !!def };
}
function num(def) {
	return { type: 'number', default: typeof def === 'number' ? def : 0 };
}
function heading(def) {
	return { type: 'string', default: def };
}

const blocks = [
	{
		slug: 'hero',
		name: 'smartleading/founders-hero',
		title: 'Founders Behind — Hero',
		icon: 'groups',
		attributes: {
			headingTag: heading('h1'),
			eyebrow: str('Our Founders'),
			heading: str('The Founders Behind Smart Leading'),
			lead: str('Meet the people behind Smart Leading Solutions.'),
			note: str(''),
			primaryLabel: str('Start a Conversation'),
			primaryUrl: str('/contact-us/'),
			primaryNewTab: bool(false),
			primaryShow: bool(true),
			secondaryLabel: str('Meet the Founders'),
			secondaryUrl: str('#founders'),
			secondaryNewTab: bool(false),
			secondaryShow: bool(true),
			imageId: num(0),
			imageUrl: str(''),
			imageAlt: str(''),
			isPublished: bool(true),
		},
	},
	{
		slug: 'profiles',
		name: 'smartleading/founders-profiles',
		title: 'Founders Behind — Founder Profiles',
		icon: 'id',
		attributes: {
			headingTag: heading('h2'),
			eyebrow: str('The people behind Smart Leading'),
			heading: str('Meet the Founders'),
			anchor: str('founders'),
			isPublished: bool(true),
		},
	},
	{
		slug: 'profile',
		name: 'smartleading/founders-profile',
		title: 'Founder Profile',
		parent: ['smartleading/founders-profiles'],
		icon: 'admin-users',
		attributes: {
			name: str(''),
			role1: str(''),
			role2: str(''),
			role3: str(''),
			bio: str(''),
			extra: str(''),
			imageId: num(0),
			imageUrl: str(''),
			imageAlt: str(''),
			socialUrl: str(''),
			socialLabel: str(''),
			socialNewTab: bool(true),
			reverse: bool(false),
		},
	},
	{
		slug: 'origin',
		name: 'smartleading/founders-origin',
		title: 'Founders Behind — Origin Story',
		icon: 'book-alt',
		attributes: {
			headingTag: heading('h2'),
			eyebrow: str('Our story'),
			heading: str('Why We Started Smart Leading'),
			story: str(''),
			imageId: num(0),
			imageUrl: str(''),
			imageAlt: str(''),
			isPublished: bool(false),
		},
	},
	{
		slug: 'roles',
		name: 'smartleading/founders-roles',
		title: 'Founders Behind — Complementary Roles',
		icon: 'columns',
		attributes: {
			headingTag: heading('h2'),
			eyebrow: str('How they work together'),
			heading: str('Different Strengths. A Shared Direction.'),
			lead: str(''),
			shared: str(''),
			isPublished: bool(false),
		},
	},
	{
		slug: 'role',
		name: 'smartleading/founders-role',
		title: 'Founder Role',
		parent: ['smartleading/founders-roles'],
		icon: 'nametag',
		attributes: {
			name: str(''),
			role: str(''),
			text: str(''),
		},
	},
	{
		slug: 'principles',
		name: 'smartleading/founders-principles',
		title: 'Founders Behind — Principles',
		icon: 'editor-ol',
		attributes: {
			headingTag: heading('h2'),
			eyebrow: str(''),
			heading: str('What Guides Our Work'),
			isPublished: bool(false),
		},
	},
	{
		slug: 'principle',
		name: 'smartleading/founders-principle',
		title: 'Principle',
		parent: ['smartleading/founders-principles'],
		icon: 'yes-alt',
		attributes: {
			heading: str(''),
			text: str(''),
		},
	},
	{
		slug: 'cta',
		name: 'smartleading/founders-cta',
		title: 'Founders Behind — Closing CTA',
		icon: 'megaphone',
		attributes: {
			headingTag: heading('h2'),
			heading: str('Let’s Talk About Your Business.'),
			lead: str('Tell us what you’re working toward and where you need support.'),
			primaryLabel: str('Start a Conversation'),
			primaryUrl: str('/contact-us/'),
			primaryNewTab: bool(false),
			primaryShow: bool(true),
			secondaryLabel: str('About Smart Leading'),
			secondaryUrl: str('/about-us/'),
			secondaryNewTab: bool(false),
			secondaryShow: bool(true),
			isPublished: bool(true),
		},
	},
];

blocks.forEach((block) => {
	const json = {
		$schema: 'https://schemas.wp.org/trunk/block.json',
		apiVersion: 3,
		name: block.name,
		title: block.title,
		category: 'smartleading-founders',
		description: block.title,
		icon: block.icon,
		keywords: ['founders', 'smart leading'],
		textdomain: 'smart-leading-net',
		supports: supports(),
		attributes: block.attributes,
		editorScript: 'smartleading-founders-editor',
		editorStyle: 'smartleading-founders-editor-style',
		style: 'sln-founders-behind',
	};
	if (block.parent) {
		json.parent = block.parent;
	}
	const dir = path.join(root, block.slug);
	fs.mkdirSync(dir, { recursive: true });
	fs.writeFileSync(path.join(dir, 'block.json'), JSON.stringify(json, null, 2) + '\n');
});

console.log('Wrote', blocks.length, 'founders block.json files');
