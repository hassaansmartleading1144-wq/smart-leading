import { chromium } from 'playwright';

const URL = process.argv[2] || 'http://localhost/new-smart-leading/test/';
const WIDTH = Number(process.argv[3] || 375);

const browser = await chromium.launch({ headless: true });
const page = await browser.newPage({ viewport: { width: WIDTH, height: 812 } });
await page.goto(URL, { waitUntil: 'networkidle', timeout: 60000 });

const overflows = await page.evaluate(() => {
	const vw = document.documentElement.clientWidth;
	const items = [];

	document.querySelectorAll('*').forEach((el) => {
		if (el.scrollWidth > vw + 1) {
			items.push({
				tag: el.tagName.toLowerCase(),
				className: el.className,
				scrollWidth: el.scrollWidth,
				clientWidth: el.clientWidth,
				overflow: vw,
			});
		}
	});

	items.sort((a, b) => b.scrollWidth - a.scrollWidth);
	return { vw, docScrollWidth: document.documentElement.scrollWidth, items: items.slice(0, 15) };
});

console.log(JSON.stringify(overflows, null, 2));
await browser.close();
