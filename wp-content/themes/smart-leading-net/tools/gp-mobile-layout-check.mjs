import { chromium } from 'playwright';

const URL = process.argv[2] || 'http://localhost/new-smart-leading/test/';
const WIDTH = Number(process.argv[3] || 375);

const browser = await chromium.launch({ headless: true });
const page = await browser.newPage({ viewport: { width: WIDTH, height: 812 } });
await page.goto(URL, { waitUntil: 'networkidle', timeout: 60000 });

const result = await page.evaluate(() => {
	const vw = document.documentElement.clientWidth;
	const docW = document.documentElement.scrollWidth;
	const hero = document.querySelector('.growth-page-hero');
	const stage = document.querySelector('.growth-page-hero__stage');
	const content = document.querySelector('.growth-page-hero__content');
	const visual = document.querySelector('.growth-page-hero__visual');

	function rect(el) {
		if (!el) return null;
		const r = el.getBoundingClientRect();
		return {
			className: el.className,
			width: r.width,
			left: r.left,
			right: r.right,
			scrollWidth: el.scrollWidth,
		};
	}

	const wide = [];
	document.querySelectorAll('body *').forEach((el) => {
		const r = el.getBoundingClientRect();
		if (r.right > vw + 2 || el.scrollWidth > vw + 2) {
			const style = getComputedStyle(el);
			if (parseFloat(r.width) > vw || r.right > vw + 2) {
				wide.push({
					tag: el.tagName.toLowerCase(),
					className: String(el.className).slice(0, 80),
					rectW: Math.round(r.width),
					rectRight: Math.round(r.right),
					scrollWidth: el.scrollWidth,
					overflowX: style.overflowX,
					position: style.position,
					transform: style.transform,
				});
			}
		}
	});

	wide.sort((a, b) => b.rectRight - a.rectRight);

	return {
		vw,
		docScrollWidth: docW,
		hero: rect(hero),
		stage: rect(stage),
		content: rect(content),
		visual: rect(visual),
		wide: wide.slice(0, 20),
	};
});

console.log(JSON.stringify(result, null, 2));
await browser.close();
