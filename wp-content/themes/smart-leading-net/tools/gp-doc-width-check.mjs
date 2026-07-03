import { chromium } from 'playwright';

const URL = process.argv[2] || 'http://localhost/new-smart-leading/test/';
const WIDTH = Number(process.argv[3] || 375);

const browser = await chromium.launch({ headless: true });
const page = await browser.newPage({ viewport: { width: WIDTH, height: 812 } });
await page.goto(URL, { waitUntil: 'networkidle', timeout: 60000 });

const result = await page.evaluate(() => {
	const vw = document.documentElement.clientWidth;
	const offenders = [];

	document.querySelectorAll('body *').forEach((el) => {
		const r = el.getBoundingClientRect();
		if (r.width <= vw + 5 && r.right > vw + 5 && r.right < vw + 400) {
			offenders.push({
				tag: el.tagName.toLowerCase(),
				className: String(el.className).slice(0, 100),
				rectW: Math.round(r.width),
				rectRight: Math.round(r.right),
				rectLeft: Math.round(r.left),
				overflowX: getComputedStyle(el).overflowX,
				parentClass: el.parentElement ? String(el.parentElement.className).slice(0, 60) : '',
			});
		}
	});

	offenders.sort((a, b) => a.rectRight - b.rectRight);
	return { vw, docScrollWidth: document.documentElement.scrollWidth, offenders: offenders.slice(0, 25) };
});

console.log(JSON.stringify(result, null, 2));
await browser.close();
