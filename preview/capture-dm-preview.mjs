import { createServer } from 'node:http';
import { readFile } from 'node:fs/promises';
import { extname, join } from 'node:path';
import puppeteer from 'puppeteer';

const ROOT = '/workspace';
const PORT = 8765;
const MIME = {
	'.html': 'text/html; charset=utf-8',
	'.css': 'text/css; charset=utf-8',
	'.js': 'text/javascript; charset=utf-8',
	'.svg': 'image/svg+xml',
	'.webp': 'image/webp',
	'.png': 'image/png',
	'.jpg': 'image/jpeg',
};

const server = createServer(async (req, res) => {
	try {
		const url = new URL(req.url || '/', `http://localhost:${PORT}`);
		const pathname = decodeURIComponent(url.pathname === '/' ? '/preview/digital-marketing-preview.html' : url.pathname);
		const filePath = join(ROOT, pathname);
		const data = await readFile(filePath);
		res.writeHead(200, { 'Content-Type': MIME[extname(filePath)] || 'application/octet-stream' });
		res.end(data);
	} catch {
		res.writeHead(404).end('Not found');
	}
});

server.listen(PORT);

const browser = await puppeteer.launch({
	headless: 'new',
	args: ['--no-sandbox', '--disable-setuid-sandbox'],
});
const page = await browser.newPage();

const shots = [
	{ name: 'hero-mobile', width: 390, height: 844, clip: { x: 0, y: 0, width: 390, height: 844 } },
	{ name: 'full-mobile', width: 390, height: 844, fullPage: true },
	{ name: 'desktop', width: 1280, height: 900, fullPage: true },
];

await page.goto(`http://localhost:${PORT}/preview/digital-marketing-preview.html`, { waitUntil: 'networkidle0' });
await page.evaluate(() => {
	document.querySelectorAll('.dm-page__reveal').forEach((el) => el.classList.add('is-visible'));
	const timeline = document.querySelector('[data-dm-timeline]');
	if (timeline) timeline.classList.add('is-visible');
	document.querySelectorAll('.dm-page__count').forEach((el) => {
		const prefix = el.getAttribute('data-pre') || '';
		const suffix = el.getAttribute('data-suf') || '';
		const decimals = parseInt(el.getAttribute('data-dec') || '0', 10);
		const target = parseFloat(el.getAttribute('data-val') || '0');
		el.textContent = prefix + target.toFixed(decimals) + suffix;
	});
});
await new Promise((r) => setTimeout(r, 500));

for (const shot of shots) {
	await page.setViewport({ width: shot.width, height: shot.height, deviceScaleFactor: 2 });
	const path = `/opt/cursor/artifacts/digital-marketing-${shot.name}.png`;
	if (shot.fullPage) {
		await page.screenshot({ path, fullPage: true });
	} else {
		await page.screenshot({ path, clip: shot.clip });
	}
	console.log('Saved', path);
}

await browser.close();
server.close();
