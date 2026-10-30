/**
 * Extract Local SEO prototype CSS and scope it under .sls-locel-seo.
 */
const fs = require('fs');
const path = require('path');

const htmlPath = 'D:\\Users\\Mughalsoft\\Downloads\\local-seo-services-new-design.html';
const outPath = path.join(__dirname, '..', '..', 'assets', 'css', 'locel-seo.css');

const html = fs.readFileSync(htmlPath, 'utf8');
const match = html.match(/<style>([\s\S]*?)<\/style>/);
if (!match) {
  throw new Error('No style block found');
}

let raw = match[1];
raw = raw.replace(/\/\*[\s\S]*?\*\//g, '');
raw = raw.replace(/\.container\b/g, '.lseo-wrap');
raw = raw.replace(/\.btn__/g, '.lseo-btn__');
raw = raw.replace(/\.btn\b/g, '.lseo-btn');
raw = raw.replace(/\.sec\b/g, '.lseo-sec');

const DROP_SEL = /\.(topbar|header|nav|drawer|backdrop|menu-btn|footer|skip-link)([^\w-]|$)/;
const DROP_GLOBAL = /^(html|body|\*|h1,h2,h3|p|ul,ol|a|img,svg|button,input,textarea|:focus-visible)$/;

function scopeSelector(sel) {
  let s = sel.trim().replace(/\s+/g, ' ');
  if (!s) return '';
  if (DROP_SEL.test(s)) return '';
  if (s === ':root') return '.sls-locel-seo';
  if (s.startsWith('.sls-locel-seo')) return s;
  if (s.startsWith(':')) return '.sls-locel-seo ' + s;
  return '.sls-locel-seo ' + s;
}

function scopeSelectors(list) {
  return list
    .split(',')
    .map(scopeSelector)
    .filter(Boolean)
    .join(', ');
}

function processBody(body) {
  let out = '';
  let i = 0;
  while (i < body.length) {
    const at = body.indexOf('@', i);
    const brace = body.indexOf('{', i);
    if (brace === -1) {
      out += body.slice(i);
      break;
    }
    if (at !== -1 && at < brace) {
      const header = body.slice(at, brace).trim();
      const { inner, end } = extractBlock(body, brace);
      if (header.startsWith('@media')) {
        const processed = processBody(inner).trim();
        if (processed) {
          out += header + '{' + processed + '}';
        }
      }
      i = end + 1;
      continue;
    }
    const selectors = body.slice(i, brace).trim();
    const { inner, end } = extractBlock(body, brace);
    i = end + 1;
    if (!selectors || selectors.startsWith('/*')) continue;
    const compact = selectors.replace(/\s+/g, ' ');
    if (DROP_GLOBAL.test(compact)) continue;
    const scoped = scopeSelectors(selectors);
    if (!scoped) continue;
    out += scoped + '{' + inner + '}';
  }
  return out;
}

function extractBlock(src, openIdx) {
  let depth = 0;
  for (let i = openIdx; i < src.length; i++) {
    if (src[i] === '{') depth++;
    else if (src[i] === '}') {
      depth--;
      if (depth === 0) {
        return { inner: src.slice(openIdx + 1, i), end: i };
      }
    }
  }
  return { inner: src.slice(openIdx + 1), end: src.length - 1 };
}

let css = processBody(raw);

const header = `/**
 * Locale SEO page styles. Scoped to .sls-locel-seo so global theme chrome is unchanged.
 */
.sls-locel-seo img,
.sls-locel-seo svg{display:block;max-width:100%}
.sls-locel-seo button,
.sls-locel-seo input,
.sls-locel-seo textarea{font:inherit}
.sls-locel-seo ul,
.sls-locel-seo ol{margin:0;padding:0;list-style:none}
.sls-locel-seo p{margin:0}
.sls-locel-seo h1,
.sls-locel-seo h2,
.sls-locel-seo h3{font-family:var(--font, Inter, sans-serif);color:var(--heading);margin:0}
.sls-locel-seo a{color:var(--primary);text-decoration:none;transition:color .25s ease}
.sls-locel-seo a:hover{color:var(--accent-dark)}
.sls-locel-seo [hidden]{display:none!important}
.sls-locel-seo section[id]{scroll-margin-top:90px}
.sls-locel-seo :focus-visible{outline:3px solid var(--primary);outline-offset:3px}
.sls-locel-seo .on-dark :focus-visible{outline-color:#fff}
.sls-locel-seo .lseo-btn--primary:hover,
.sls-locel-seo .lseo-btn--primary:focus{outline:none;box-shadow:none;border-color:transparent}
.sls-locel-seo .lseo-btn--primary:focus-visible{outline:3px solid #fff;outline-offset:3px}
@media (prefers-reduced-motion:reduce){
  .sls-locel-seo *,
  .sls-locel-seo *::before,
  .sls-locel-seo *::after{transition:none!important;animation:none!important}
  .sls-locel-seo .slider__track{transition:none}
}

`;

const extras = `
.sls-locel-seo .wp-block-smartleading-locel-seo-map-card,
.sls-locel-seo .wp-block-smartleading-locel-seo-stop,
.sls-locel-seo .wp-block-smartleading-locel-seo-merge-col,
.sls-locel-seo .wp-block-smartleading-locel-seo-factor,
.sls-locel-seo .wp-block-smartleading-locel-seo-challenge,
.sls-locel-seo .wp-block-smartleading-locel-seo-point,
.sls-locel-seo .wp-block-smartleading-locel-seo-tile,
.sls-locel-seo .wp-block-smartleading-locel-seo-story-item,
.sls-locel-seo .wp-block-smartleading-locel-seo-stair,
.sls-locel-seo .wp-block-smartleading-locel-seo-funnel-step,
.sls-locel-seo .wp-block-smartleading-locel-seo-slide,
.sls-locel-seo .wp-block-smartleading-locel-seo-stat,
.sls-locel-seo .wp-block-smartleading-locel-seo-value,
.sls-locel-seo .wp-block-smartleading-locel-seo-compare-row,
.sls-locel-seo .wp-block-smartleading-locel-seo-faq-item{margin:0}
.sls-locel-seo iframe.lseo-video__embed{width:100%;height:100%;border:0;position:absolute;inset:0}
.sls-locel-seo .video__frame{position:relative}
.sls-locel-seo .video__frame.is-placeholder .video__play{cursor:default}
.sls-locel-seo .video__frame.is-placeholder .video__play:hover{transform:none}
`;

fs.mkdirSync(path.dirname(outPath), { recursive: true });
fs.writeFileSync(outPath, header + css + extras);
console.log('Wrote', outPath, fs.statSync(outPath).size);
