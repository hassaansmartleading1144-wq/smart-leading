<?php
/**
 * Gutenberg block patterns for the AI SEO Services page.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register AI SEO pattern category and patterns.
 */
function sln_ai_seo_register_block_patterns() {
	register_block_pattern_category(
		'sln-ai-seo',
		array(
			'label' => __( 'AI SEO Services', 'smart-leading-net' ),
		)
	);

	register_block_pattern(
		'sln-ai-seo/full-page',
		array(
			'title'       => __( 'AI SEO Services — Full Page', 'smart-leading-net' ),
			'description' => __( 'Complete AI SEO landing page matching the design prototype section sequence.', 'smart-leading-net' ),
			'categories'  => array( 'sln-ai-seo' ),
			'content'     => '<!-- wp:group {"tagName":"section","className":"sls-hero","layout":{"type":"default"}} -->
<section class="wp-block-group sls-hero" aria-labelledby="ai-search-h1">
<!-- wp:html -->
<div class="sls-wrap"><nav class="sls-crumbs" aria-label="Breadcrumb"><ol><li><a href="/">Home</a></li><li><a href="/seo-services/">SEO Services</a></li><li><span aria-current="page">AI SEO Services</span></li></ol></nav><div class="sls-hero__grid"><div><p class="sls-eyebrow">AI SEO &amp; LLM Visibility</p><h1 id="ai-search-h1">AI SEO Services for a Clearer Presence in <span class="sls-hl">AI Search</span></h1><p class="sls-lead sls-hero__lead">Help people find clear, useful information about your business as they research through AI-assisted search. Start a conversation about the content, technical foundations and measurement your strategy needs.</p><div class="sls-actions"><a class="sls-btn sls-btn--primary sls-btn--arrow" href="#ai-search-enquiry">Discuss My AI Search Goals<span class="sls-btn__arrow" aria-hidden="true"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></span></a><a class="sls-btn sls-btn--secondary" href="#ai-search-approach">Explore the Approach</a></div></div><figure class="sls-journey" aria-labelledby="ai-journey-cap"><figcaption class="sls-journey__cap" id="ai-journey-cap"><span class="sls-journey__title">Illustrative discovery journey</span><span class="sls-journey__sub">A conceptual example, not a real result</span></figcaption><ol><li class="sls-journey__step"><span class="sls-journey__dot" aria-hidden="true">1</span><div><p class="sls-journey__name">Customer question</p><p class="sls-journey__text">A buyer asks a research question in plain language, such as how to compare providers or what a service involves.</p></div></li><li class="sls-journey__step"><span class="sls-journey__dot" aria-hidden="true">2</span><div><p class="sls-journey__name">Relevant public sources</p><p class="sls-journey__text">Websites, business profiles, articles and other public information that may relate to the question.</p></div></li><li class="sls-journey__step"><span class="sls-journey__dot" aria-hidden="true">3</span><div><p class="sls-journey__name">AI-generated answer</p><p class="sls-journey__text">A summary that may reference or link to some of those sources.</p><div class="sls-answer" aria-hidden="true"><div class="sls-answer__lines"><span style="width:100%"></span><span style="width:88%"></span><span style="width:64%"></span></div><div class="sls-answer__src"><span>Source A</span><span>Source B</span><span>Source C</span></div></div></div></li><li class="sls-journey__step sls-journey__step--end"><span class="sls-journey__dot" aria-hidden="true">4</span><div><p class="sls-journey__name">Further research or enquiry</p><p class="sls-journey__text">The buyer checks sources, compares options and may contact a business directly.</p></div></li></ol></figure></div></div>
<!-- /wp:html -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"sls-sec","layout":{"type":"default"},"anchor":"ai-search-meaning"} -->
<section id="ai-search-meaning" class="wp-block-group sls-sec" aria-labelledby="ai-search-meaning-h">
<!-- wp:html -->
<div class="sls-wrap sls-split"><div class="sls-meaning__copy"><p class="sls-eyebrow">The terminology</p><h2 id="ai-search-meaning-h">What Does AI Search Optimization Actually Mean?</h2><p class="sls-lead">AI SEO connects familiar search foundations with the ways people research through generated answers. Generative engine optimization, or GEO, focuses on how a business and its information may appear in those answers.</p><p>The starting point is useful, accessible information—not a promise of a recommendation. In practice, the work builds on the same foundations as <a class="sls-tbc" href="/seo-services/">SEO services</a> and <a class="sls-tbc" href="/local-seo-services/">local SEO services</a>: pages that are easy to reach, facts that are easy to verify and answers that genuinely help.</p><p>When we talk about LLM optimization on this page, we mean improving how clearly your public website and brand information can be found and understood. It does not mean training, fine-tuning or building language models.</p></div><div><dl class="sls-terms"><div class="sls-term"><dt>SEO<small>Search engine optimization</small></dt><dd><strong>Supports organic discovery</strong>Helps relevant pages get found in search results through useful content, technical accessibility and a clear site structure.</dd></div><div class="sls-term"><dt>GEO<small>Generative engine optimization</small></dt><dd><strong>Focuses on generated-answer visibility</strong>Looks at how a business and its information may appear, be described or be cited in AI-generated answers.</dd></div><div class="sls-term"><dt>AEO<small>Answer engine optimization</small></dt><dd><strong>Emphasizes answering questions clearly</strong>Structures content around the real questions people ask, so answers are easy to find and understand.</dd></div></dl><div class="sls-overlap"><svg aria-hidden="true" width="40" height="28" viewBox="0 0 40 28" fill="none"><circle cx="12" cy="14" r="10.5" stroke="#1F4E9E" stroke-width="2"></circle><circle cx="20" cy="14" r="10.5" stroke="#1F4E9E" stroke-width="2"></circle><circle cx="28" cy="14" r="10.5" stroke="#F36B32" stroke-width="2"></circle></svg><p>These labels overlap and describe related priorities. None of them replaces a sound website or genuinely useful content.</p></div></div></div>
<!-- /wp:html -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"sls-sec sls-sec--tint","layout":{"type":"default"},"anchor":"ai-search-benefits"} -->
<section id="ai-search-benefits" class="wp-block-group sls-sec sls-sec--tint" aria-labelledby="ai-search-benefits-h">
<!-- wp:html -->
<div class="sls-wrap"><div class="sls-center"><h2 id="ai-search-benefits-h">Strengthen <span class="sls-hl2">Your Visibility</span> With AI SEO Services</h2><p class="sls-lead">When you work with Smart Leading Solutions, the work is aimed at helping you:</p></div><ul class="sls-grid3"><li class="sls-card sls-benefit"><svg class="sls-benefit__icon" aria-hidden="true" viewBox="0 0 64 64" fill="none"><circle cx="28" cy="36" r="22" fill="#E2EEFF"></circle><path d="M28 36V14a22 22 0 0 1 22 22H28z" fill="#1F4E9E"></path><path d="M34 8a24 24 0 0 1 22 22" stroke="#F36B32" stroke-width="4" stroke-linecap="round"></path></svg><h3>Build visibility across search experiences</h3><p>Support discovery in traditional search results and AI-generated answers with content and technical foundations that both can use.</p></li><li class="sls-card sls-benefit"><svg class="sls-benefit__icon" aria-hidden="true" viewBox="0 0 64 64" fill="none"><ellipse cx="32" cy="46" rx="22" ry="12" fill="#E2EEFF"></ellipse><path d="M32 44V12M20 44V24M44 44V24M20 24l-6-6M44 24l6-6" stroke="#1F4E9E" stroke-width="3" stroke-linecap="round"></path><circle cx="32" cy="10" r="4" fill="#F36B32"></circle><circle cx="13" cy="17" r="3.5" fill="#1F4E9E"></circle><circle cx="51" cy="17" r="3.5" fill="#1F4E9E"></circle></svg><h3>Reach customers beyond a single search engine</h3><p>Plan around the search tools your audience actually uses, such as Google’s AI features, ChatGPT, Perplexity and Copilot, rather than one channel alone.</p></li><li class="sls-card sls-benefit"><svg class="sls-benefit__icon" aria-hidden="true" viewBox="0 0 64 64" fill="none"><path d="M10 28c0-11 9-18 21-18s21 7 21 18-9 18-21 18c-3 0-5-.3-8-1l-10 6 2-9c-3-3-5-8-5-14z" fill="#E2EEFF"></path><path d="M22 28l6 6 13-13" stroke="#1F4E9E" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path><circle cx="50" cy="46" r="7" fill="#F36B32"></circle></svg><h3>Help AI answers describe you accurately</h3><p>Make it easier for AI-assisted search to describe your services, strengths and business details correctly, using clear and consistent public information.</p></li></ul></div>
<!-- /wp:html -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"sls-sec","layout":{"type":"default"},"anchor":"ai-search-platforms"} -->
<section id="ai-search-platforms" class="wp-block-group sls-sec" aria-labelledby="ai-search-platforms-h">
<!-- wp:html -->
<div class="sls-wrap sls-platforms"><div class="sls-platforms__intro"><p class="sls-eyebrow">Platform context</p><h2 id="ai-search-platforms-h">Focus on the Search Experiences Your Customers Use</h2><p>Different questions lead people to different search tools. Platform selection should follow your audience, market and research needs.</p><p class="sls-platforms__note">The names shown are examples of where AI-assisted research happens. They do not indicate a partnership or integration with Smart Leading Solutions, and the platforms included in any engagement are agreed during scoping.</p></div><div><ul class="sls-chips" aria-label="Examples of AI-assisted research surfaces"><li class="sls-chips__label" aria-hidden="true">Illustrative research surfaces</li><li>Google AI Overviews &amp; AI Mode</li><li>ChatGPT Search</li><li>Perplexity</li><li>Gemini</li><li>Copilot</li></ul></div></div>
<!-- /wp:html -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"sls-sec sls-sec--tint","layout":{"type":"default"},"anchor":"ai-search-approach"} -->
<section id="ai-search-approach" class="wp-block-group sls-sec sls-sec--tint" aria-labelledby="ai-search-approach-h">
<!-- wp:html -->
<div class="sls-wrap"><div class="sls-head"><div><p class="sls-eyebrow">Proposed scope</p><h2 id="ai-search-approach-h">Build an AI Search Strategy Around the Right Foundations</h2></div><p class="sls-lead">Six areas of work that can shape an AI search optimization program. Which modules apply to your business, and who carries out each part, is agreed during scoping.</p></div><ul class="sls-grid3"><li class="sls-card"><span class="sls-card__num" aria-hidden="true">01</span><h3>Visibility assessment</h3><p>Review a selected set of questions and the sources that appear in sampled AI answers. The result is a starting point: where your business appears, where it does not, and which sources tend to shape the answers.</p></li><li class="sls-card"><span class="sls-card__num" aria-hidden="true">02</span><h3>Audience and question research</h3><p>Identify the questions that matter when prospective customers compare options, check requirements and look for help, so effort goes to questions with real business relevance.</p></li><li class="sls-card"><span class="sls-card__num" aria-hidden="true">03</span><h3>Technical SEO foundations</h3><p>Review accessibility, indexability and other technical barriers that can stop public information being found or understood. Fixes are specified after assessment, including appropriate structured data where it supports factual clarity.</p></li><li class="sls-card"><span class="sls-card__num" aria-hidden="true">04</span><h3>Helpful, answer-focused content</h3><p>Clarify service pages, comparisons and practical questions using your real expertise and evidence. The aim is content a person finds useful and that is clear enough to be summarized accurately.</p></li><li class="sls-card"><span class="sls-card__num" aria-hidden="true">05</span><h3>Consistent business information</h3><p>Check that core facts such as services, locations, contact details and descriptions are consistent across your website and relevant external profiles or references.</p></li><li class="sls-card"><span class="sls-card__num" aria-hidden="true">06</span><h3>Measurement and refinement</h3><p>Revisit an agreed sample of questions and use available referral and enquiry data to inform what to prioritize next.</p></li></ul></div>
<!-- /wp:html -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"sls-sec","layout":{"type":"default"},"anchor":"ai-search-pricing"} -->
<section id="ai-search-pricing" class="wp-block-group sls-sec" aria-labelledby="ai-search-pricing-h">
<!-- wp:group {"className":"sls-wrap sls-pricing","layout":{"type":"default"}} -->
<div class="wp-block-group sls-wrap sls-pricing">
<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:html -->
<p class="sls-eyebrow">Pricing</p><h2 id="ai-search-pricing-h">Transparent Pricing for AI SEO Services, Scoped to Your Goals</h2><p class="sls-lead">AI SEO programs are priced around the work your website and goals require: content, technical foundations and measurement across the AI search experiences your customers use. Share your website to start a conversation about fit and a tailored proposal.</p>
<!-- /wp:html -->
<!-- wp:sln/ai-seo-mini-lead {"placeholder":"Enter your website","buttonLabel":"Send Me a Proposal","variant":"default","ariaLabel":"Start a proposal request","inputId":"ai-mini-website-1","note":"Continues to the short enquiry form below. Nothing is sent at this step.","showArrow":true} /-->
</div>
<!-- /wp:group -->
<!-- wp:html -->
<div class="sls-price"><span class="sls-price__icon" aria-hidden="true"><svg width="44" height="44" viewBox="0 0 48 48" fill="none"><circle cx="20" cy="28" r="14" fill="#E2EEFF"></circle><path d="M10 34l9-9 6 6 13-15" stroke="#1F4E9E" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M30 16h8v8" stroke="#F36B32" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"></path></svg></span><p class="sls-price__label">AI SEO Programs Starting At</p><p class="sls-price__amt">[$X,XXX]<span>/month</span></p><span class="sls-price__bar" aria-hidden="true"></span><p class="sls-price__for">For businesses ready to adapt their search strategy</p><p class="sls-price__fine">Final pricing depends on the scope agreed for your business.</p></div>
<!-- /wp:html -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"sls-sec sls-sec--tint","layout":{"type":"default"},"anchor":"ai-search-process"} -->
<section id="ai-search-process" class="wp-block-group sls-sec sls-sec--tint" aria-labelledby="ai-search-process-h">
<!-- wp:html -->
<div class="sls-wrap"><div class="sls-head"><div><p class="sls-eyebrow">How it works</p><h2 id="ai-search-process-h">From an Initial Picture to Clear Priorities</h2></div><p class="sls-lead">A practical sequence that keeps decisions visible: what was looked at, what was found and what happens next.</p></div><ol class="sls-steps"><li class="sls-step"><span class="sls-step__n" aria-hidden="true">1</span><h3>Understand the business and audience</h3><p>Discuss your services, markets, existing search work and the questions prospective customers ask.</p><p class="sls-step__out"><b>Output:</b> a shared view of goals and the questions worth assessing.</p></li><li class="sls-step"><span class="sls-step__n" aria-hidden="true">2</span><h3>Establish a sampled baseline</h3><p>Review an agreed sample of questions across relevant AI search experiences and note which sources appear.</p><p class="sls-step__out"><b>Output:</b> a dated snapshot to compare against later.</p></li><li class="sls-step"><span class="sls-step__n" aria-hidden="true">3</span><h3>Agree scope and priorities</h3><p>Weigh content, technical and information-consistency opportunities against relevance and effort.</p><p class="sls-step__out"><b>Output:</b> agreed priorities and responsibilities.</p></li><li class="sls-step"><span class="sls-step__n" aria-hidden="true">4</span><h3>Review changes and refine</h3><p>Revisit the sample alongside available referral and enquiry data, and adjust priorities based on what is observed.</p><p class="sls-step__out"><b>Output:</b> observations kept separate from assumptions.</p></li></ol><div class="sls-process__cta"><p>Want to talk through where your business would start?</p><a class="sls-btn sls-btn--primary sls-btn--arrow" href="#ai-search-enquiry">Discuss My AI Search Goals<span class="sls-btn__arrow" aria-hidden="true"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></span></a></div></div>
<!-- /wp:html -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"sls-sec","layout":{"type":"default"},"anchor":"ai-search-measurement"} -->
<section id="ai-search-measurement" class="wp-block-group sls-sec" aria-labelledby="ai-search-measurement-h">
<!-- wp:html -->
<div class="sls-wrap"><div class="sls-head"><div><p class="sls-eyebrow">AI visibility tracking</p><h2 id="ai-search-measurement-h">Measure Visibility Alongside Business Outcomes</h2></div><p class="sls-lead">A mention is useful context. An enquiry tells a different part of the story. A measurement plan should keep those signals distinct.</p></div><div class="sls-table-wrap"><table class="sls-table"><caption class="sls-sr">Four measurement signals, what each can tell a team, and its limits</caption><thead><tr><th scope="col">Signal</th><th scope="col">What it can tell your team</th><th scope="col">What it cannot show</th></tr></thead><tbody><tr><th scope="row"><span aria-hidden="true">01</span>Sampled mentions and citations</th><td data-label="What it can tell your team">Whether and how your business appears for the questions in an agreed sample, and which competitors or sources appear alongside it.</td><td data-label="What it cannot show">How every user sees every answer. A sample is a snapshot of selected questions at a point in time.</td></tr><tr><th scope="row"><span aria-hidden="true">02</span>Source and factual accuracy review</th><td data-label="What it can tell your team">Which pages and profiles answers appear to draw on, and whether your services and details are described correctly.</td><td data-label="What it cannot show">Sources a platform uses but does not display. Not every answer shows its sources.</td></tr><tr><th scope="row"><span aria-hidden="true">03</span>Identifiable AI referral visits</th><td data-label="What it can tell your team">Visits from AI platforms that pass referral information, and which pages those visitors land on.</td><td data-label="What it cannot show">AI-assisted journeys that arrive without referral data, such as when someone later searches your name or types your address.</td></tr><tr><th scope="row"><span aria-hidden="true">04</span>Recorded enquiries and qualification</th><td data-label="What it can tell your team">Whether enquiries arrive and, where your forms and records support it, how relevant those enquiries are.</td><td data-label="What it cannot show">A complete attribution path. Results depend on the tracking and records already in place.</td></tr></tbody></table></div><div class="sls-caveat"><svg aria-hidden="true" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1F4E9E" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 11v6"></path><path d="M12 7.5v.01"></path></svg><p>AI answers can vary by question, timing and user context. A tracked sample is a snapshot, and referral data does not capture every AI-assisted journey.</p></div></div>
<!-- /wp:html -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"sls-sec sls-sec--blue","layout":{"type":"default"},"anchor":"ai-search-principles"} -->
<section id="ai-search-principles" class="wp-block-group sls-sec sls-sec--blue" aria-labelledby="ai-search-principles-h">
<!-- wp:html -->
<div class="sls-wrap sls-principles"><div class="sls-principles__intro"><p class="sls-eyebrow">How we approach the work</p><h2 id="ai-search-principles-h">Start With Clear Questions. Agree on Clear Work.</h2><p>AI search is still changing. These principles are meant to keep the work understandable as it develops, for your team and for ours.</p></div><ol class="sls-plist"><li><span class="sls-plist__n" aria-hidden="true">01</span><div><h3>Define the questions being assessed</h3><p>Agree which customer questions, markets and platforms are in view, so everyone knows what a finding refers to.</p></div></li><li><span class="sls-plist__n" aria-hidden="true">02</span><div><h3>Explain the priority behind each recommendation</h3><p>Each recommendation should state the problem it addresses and why it comes before other work.</p></div></li><li><span class="sls-plist__n" aria-hidden="true">03</span><div><h3>Separate observed changes from assumptions</h3><p>Report what was seen in the sample and the data, and label interpretation as interpretation.</p></div></li></ol></div>
<!-- /wp:html -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"sls-sec sls-sec--warm","layout":{"type":"default"},"anchor":"ai-search-scope"} -->
<section id="ai-search-scope" class="wp-block-group sls-sec sls-sec--warm" aria-labelledby="ai-search-scope-h">
<!-- wp:html -->
<div class="sls-wrap sls-scope"><div class="sls-scope__copy"><p class="sls-eyebrow">Scope and engagement</p><h2 id="ai-search-scope-h">What Would the Right Scope Look Like for Your Business?</h2><p class="sls-lead">The right starting point depends on your website, audience, existing search work and the questions you want to assess. Share that context to begin a discussion about fit.</p><div class="sls-actions"><a class="sls-btn sls-btn--primary sls-btn--arrow" href="#ai-search-enquiry">Discuss My AI Search Goals<span class="sls-btn__arrow" aria-hidden="true"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></span></a></div></div><div class="sls-scope__card"><ul aria-label="What shapes the scope"><li><span class="sls-tick" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg></span><div><b>Size and complexity of the website</b><span class="d">How many pages, templates and services are involved.</span></div></li><li><span class="sls-tick" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg></span><div><b>Current content and technical foundations</b><span class="d">What already works well and what may need attention first.</span></div></li><li><span class="sls-tick" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg></span><div><b>Markets and services</b><span class="d">Which offerings, audiences and locations are in view.</span></div></li><li><span class="sls-tick" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg></span><div><b>Assessment and measurement needs</b><span class="d">Which questions to sample and what data you already track.</span></div></li></ul></div></div>
<!-- /wp:html -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"sls-sec sls-sec--banner","layout":{"type":"default"},"anchor":"ai-search-banner"} -->
<section id="ai-search-banner" class="wp-block-group sls-sec sls-sec--banner" aria-labelledby="ai-search-banner-h">
<!-- wp:group {"className":"sls-wrap","layout":{"type":"default"}} -->
<div class="wp-block-group sls-wrap">
<!-- wp:group {"className":"sls-banner","layout":{"type":"default"}} -->
<div class="wp-block-group sls-banner">
<!-- wp:html -->
<div class="sls-banner__proof"><figure class="sls-banner__photo wp-block-image"><img src="/wp-content/themes/smart-leading-net/assets/images/ai-seo/team-photo-placeholder.svg" alt="SLS team member" loading="lazy" decoding="async" width="170" height="250"/></figure><div class="sls-banner__stat"><p class="sls-banner__k">SLS AI Search Results</p><p class="sls-banner__n">[Verified figure]</p><p class="sls-banner__l">[What the figure measures]</p></div></div>
<!-- /wp:html -->
<!-- wp:group {"className":"sls-banner__copy","layout":{"type":"default"}} -->
<div class="wp-block-group sls-banner__copy">
<!-- wp:html -->
<h2 id="ai-search-banner-h">Be Visible Wherever Your Customers Research</h2><p>Build a clearer presence across Google, ChatGPT, Perplexity and the other AI search experiences your audience uses. Share your website to request a custom strategy conversation.</p>
<!-- /wp:html -->
<!-- wp:sln/ai-seo-mini-lead {"placeholder":"Enter your website","buttonLabel":"Request My Custom Strategy","variant":"onblue","ariaLabel":"Start a strategy request","inputId":"ai-mini-website-2","note":"","showArrow":false} /-->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"sls-sec","layout":{"type":"default"},"anchor":"ai-search-faq"} -->
<section id="ai-search-faq" class="wp-block-group sls-sec" aria-labelledby="ai-search-faq-h">
<!-- wp:html -->
<div class="sls-wrap sls-faqwrap"><div class="sls-faqwrap__intro"><p class="sls-eyebrow">FAQs</p><h2 id="ai-search-faq-h">AI SEO Services: Common Questions</h2><p>Have a question that is not covered here? Include it in your <a href="#ai-search-enquiry">AI search enquiry</a>.</p></div><div class="sls-faq">
<!-- /wp:html -->
<!-- wp:details {"showContent":true} -->
<details class="wp-block-details" open>
<summary>What are AI SEO services?</summary>
<!-- wp:paragraph -->
<p>AI SEO services focus on the website and brand information that may support discovery in AI-assisted search. The work can involve content, technical foundations and measurement. The specific scope of any engagement is agreed separately during scoping.</p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:details -->
<details class="wp-block-details">
<summary>What is the difference between SEO, GEO and AEO?</summary>
<!-- wp:paragraph -->
<p>They are overlapping terms. SEO covers organic search discovery, GEO focuses on visibility in generated answers, and AEO emphasizes answering questions clearly. They describe related priorities rather than entirely separate systems.</p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:details -->
<details class="wp-block-details">
<summary>Does LLM optimization mean training a model?</summary>
<!-- wp:paragraph -->
<p>No. On this page, LLM optimization means work on how clearly your website and brand information can be found and understood. Model development and fine-tuning are outside the proposed scope.</p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:details -->
<details class="wp-block-details">
<summary>Can you guarantee a ChatGPT mention or AI citation?</summary>
<!-- wp:paragraph -->
<p>No. Placement in AI answers should not be promised by anyone. Each platform determines its own answers, and those answers can vary across questions, timing and context.</p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:details -->
<details class="wp-block-details">
<summary>Does AI search optimization replace SEO?</summary>
<!-- wp:paragraph -->
<p>It is best treated as a related discovery consideration rather than a replacement. A useful, technically accessible website remains a sensible foundation for both.</p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:details -->
<details class="wp-block-details">
<summary>How is AI search visibility measured?</summary>
<!-- wp:paragraph -->
<p>An agreed sample of questions can be reviewed for mentions and citations, alongside available referral and enquiry data. Each of these measures has limits, so they are reported separately rather than combined into a single score.</p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:details -->
<details class="wp-block-details">
<summary>How long will results take?</summary>
<!-- wp:paragraph -->
<p>There is no fixed timeline. Timing depends on the work involved, your starting point and how each platform behaves.</p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:details -->
<details class="wp-block-details">
<summary>Which platforms and deliverables would my engagement include?</summary>
<!-- wp:paragraph -->
<p>Platform coverage and responsibilities are confirmed during scoping. It should not be assumed that every platform named on this page, or every proposed module, is included.</p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:html -->
</div></div>
<!-- /wp:html -->
</section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"sls-sec sls-sec--tint","layout":{"type":"default"},"anchor":"ai-search-enquiry"} -->
<section id="ai-search-enquiry" class="wp-block-group sls-sec sls-sec--tint" aria-labelledby="ai-search-enquiry-h">
<!-- wp:group {"className":"sls-wrap sls-enq","layout":{"type":"default"}} -->
<div class="wp-block-group sls-wrap sls-enq">
<!-- wp:html -->
<div class="sls-enq__copy"><p class="sls-eyebrow">Start a conversation</p><h2 id="ai-search-enquiry-h">Let’s Discuss Your AI Search Goals</h2><p class="sls-lead">Tell us about your business, your website and what you want to understand about AI search.</p><ul class="sls-enq__list"><li>The services and markets that matter most to you</li><li>Questions you would like to see your business answer well</li><li>Any existing SEO work or tracking already in place</li></ul><p class="sls-enq__next">After you send this form, a Smart Leading team member will review your details and follow up to discuss fit and next steps.</p></div>
<!-- /wp:html -->
<!-- wp:sln/ai-seo-enquiry-form {"privacyUrl":"/privacy-policy/"} /-->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->',
		)
	);

	register_block_pattern(
		'sln-ai-seo/enquiry-form-block',
		array(
			'title'       => __( 'AI SEO — Enquiry Form Block', 'smart-leading-net' ),
			'description' => __( 'Custom Gutenberg enquiry form with editable labels (Inspector).', 'smart-leading-net' ),
			'categories'  => array( 'sln-ai-seo' ),
			'content'     => '<!-- wp:sln/ai-seo-enquiry-form /-->',
		)
	);

	register_block_pattern(
		'sln-ai-seo/mini-lead-block',
		array(
			'title'       => __( 'AI SEO — Mini Lead Form Block', 'smart-leading-net' ),
			'description' => __( 'Website field plus CTA that scrolls to the enquiry section.', 'smart-leading-net' ),
			'categories'  => array( 'sln-ai-seo' ),
			'content'     => '<!-- wp:sln/ai-seo-mini-lead {"note":"Continues to the short enquiry form below. Nothing is sent at this step."} /-->',
		)
	);

	register_block_pattern(
		'sln-ai-seo/hero',
		array(
			'title'       => __( 'AI SEO — Hero', 'smart-leading-net' ),
			'description' => __( 'Hero with breadcrumb, CTAs and discovery journey.', 'smart-leading-net' ),
			'categories'  => array( 'sln-ai-seo' ),
			'content'     => '<!-- wp:group {"tagName":"section","className":"sls-hero","layout":{"type":"default"}} -->
<section class="wp-block-group sls-hero" aria-labelledby="ai-search-h1">
<!-- wp:html -->
<div class="sls-wrap"><nav class="sls-crumbs" aria-label="Breadcrumb"><ol><li><a href="/">Home</a></li><li><a href="/seo-services/">SEO Services</a></li><li><span aria-current="page">AI SEO Services</span></li></ol></nav><div class="sls-hero__grid"><div><p class="sls-eyebrow">AI SEO &amp; LLM Visibility</p><h1 id="ai-search-h1">AI SEO Services for a Clearer Presence in <span class="sls-hl">AI Search</span></h1><p class="sls-lead sls-hero__lead">Help people find clear, useful information about your business as they research through AI-assisted search. Start a conversation about the content, technical foundations and measurement your strategy needs.</p><div class="sls-actions"><a class="sls-btn sls-btn--primary sls-btn--arrow" href="#ai-search-enquiry">Discuss My AI Search Goals<span class="sls-btn__arrow" aria-hidden="true"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></span></a><a class="sls-btn sls-btn--secondary" href="#ai-search-approach">Explore the Approach</a></div></div><figure class="sls-journey" aria-labelledby="ai-journey-cap"><figcaption class="sls-journey__cap" id="ai-journey-cap"><span class="sls-journey__title">Illustrative discovery journey</span><span class="sls-journey__sub">A conceptual example, not a real result</span></figcaption><ol><li class="sls-journey__step"><span class="sls-journey__dot" aria-hidden="true">1</span><div><p class="sls-journey__name">Customer question</p><p class="sls-journey__text">A buyer asks a research question in plain language, such as how to compare providers or what a service involves.</p></div></li><li class="sls-journey__step"><span class="sls-journey__dot" aria-hidden="true">2</span><div><p class="sls-journey__name">Relevant public sources</p><p class="sls-journey__text">Websites, business profiles, articles and other public information that may relate to the question.</p></div></li><li class="sls-journey__step"><span class="sls-journey__dot" aria-hidden="true">3</span><div><p class="sls-journey__name">AI-generated answer</p><p class="sls-journey__text">A summary that may reference or link to some of those sources.</p><div class="sls-answer" aria-hidden="true"><div class="sls-answer__lines"><span style="width:100%"></span><span style="width:88%"></span><span style="width:64%"></span></div><div class="sls-answer__src"><span>Source A</span><span>Source B</span><span>Source C</span></div></div></div></li><li class="sls-journey__step sls-journey__step--end"><span class="sls-journey__dot" aria-hidden="true">4</span><div><p class="sls-journey__name">Further research or enquiry</p><p class="sls-journey__text">The buyer checks sources, compares options and may contact a business directly.</p></div></li></ol></figure></div></div>
<!-- /wp:html -->
</section>
<!-- /wp:group -->',
		)
	);

	register_block_pattern(
		'sln-ai-seo/meaning',
		array(
			'title'       => __( 'AI SEO — Meaning', 'smart-leading-net' ),
			'description' => __( 'SEO, GEO and AEO terminology.', 'smart-leading-net' ),
			'categories'  => array( 'sln-ai-seo' ),
			'content'     => '<!-- wp:group {"tagName":"section","className":"sls-sec","layout":{"type":"default"},"anchor":"ai-search-meaning"} -->
<section id="ai-search-meaning" class="wp-block-group sls-sec" aria-labelledby="ai-search-meaning-h">
<!-- wp:html -->
<div class="sls-wrap sls-split"><div class="sls-meaning__copy"><p class="sls-eyebrow">The terminology</p><h2 id="ai-search-meaning-h">What Does AI Search Optimization Actually Mean?</h2><p class="sls-lead">AI SEO connects familiar search foundations with the ways people research through generated answers. Generative engine optimization, or GEO, focuses on how a business and its information may appear in those answers.</p><p>The starting point is useful, accessible information—not a promise of a recommendation. In practice, the work builds on the same foundations as <a class="sls-tbc" href="/seo-services/">SEO services</a> and <a class="sls-tbc" href="/local-seo-services/">local SEO services</a>: pages that are easy to reach, facts that are easy to verify and answers that genuinely help.</p><p>When we talk about LLM optimization on this page, we mean improving how clearly your public website and brand information can be found and understood. It does not mean training, fine-tuning or building language models.</p></div><div><dl class="sls-terms"><div class="sls-term"><dt>SEO<small>Search engine optimization</small></dt><dd><strong>Supports organic discovery</strong>Helps relevant pages get found in search results through useful content, technical accessibility and a clear site structure.</dd></div><div class="sls-term"><dt>GEO<small>Generative engine optimization</small></dt><dd><strong>Focuses on generated-answer visibility</strong>Looks at how a business and its information may appear, be described or be cited in AI-generated answers.</dd></div><div class="sls-term"><dt>AEO<small>Answer engine optimization</small></dt><dd><strong>Emphasizes answering questions clearly</strong>Structures content around the real questions people ask, so answers are easy to find and understand.</dd></div></dl><div class="sls-overlap"><svg aria-hidden="true" width="40" height="28" viewBox="0 0 40 28" fill="none"><circle cx="12" cy="14" r="10.5" stroke="#1F4E9E" stroke-width="2"></circle><circle cx="20" cy="14" r="10.5" stroke="#1F4E9E" stroke-width="2"></circle><circle cx="28" cy="14" r="10.5" stroke="#F36B32" stroke-width="2"></circle></svg><p>These labels overlap and describe related priorities. None of them replaces a sound website or genuinely useful content.</p></div></div></div>
<!-- /wp:html -->
</section>
<!-- /wp:group -->',
		)
	);

	register_block_pattern(
		'sln-ai-seo/benefits',
		array(
			'title'       => __( 'AI SEO — Benefits', 'smart-leading-net' ),
			'description' => __( 'Three benefit cards.', 'smart-leading-net' ),
			'categories'  => array( 'sln-ai-seo' ),
			'content'     => '<!-- wp:group {"tagName":"section","className":"sls-sec sls-sec--tint","layout":{"type":"default"},"anchor":"ai-search-benefits"} -->
<section id="ai-search-benefits" class="wp-block-group sls-sec sls-sec--tint" aria-labelledby="ai-search-benefits-h">
<!-- wp:html -->
<div class="sls-wrap"><div class="sls-center"><h2 id="ai-search-benefits-h">Strengthen <span class="sls-hl2">Your Visibility</span> With AI SEO Services</h2><p class="sls-lead">When you work with Smart Leading Solutions, the work is aimed at helping you:</p></div><ul class="sls-grid3"><li class="sls-card sls-benefit"><svg class="sls-benefit__icon" aria-hidden="true" viewBox="0 0 64 64" fill="none"><circle cx="28" cy="36" r="22" fill="#E2EEFF"></circle><path d="M28 36V14a22 22 0 0 1 22 22H28z" fill="#1F4E9E"></path><path d="M34 8a24 24 0 0 1 22 22" stroke="#F36B32" stroke-width="4" stroke-linecap="round"></path></svg><h3>Build visibility across search experiences</h3><p>Support discovery in traditional search results and AI-generated answers with content and technical foundations that both can use.</p></li><li class="sls-card sls-benefit"><svg class="sls-benefit__icon" aria-hidden="true" viewBox="0 0 64 64" fill="none"><ellipse cx="32" cy="46" rx="22" ry="12" fill="#E2EEFF"></ellipse><path d="M32 44V12M20 44V24M44 44V24M20 24l-6-6M44 24l6-6" stroke="#1F4E9E" stroke-width="3" stroke-linecap="round"></path><circle cx="32" cy="10" r="4" fill="#F36B32"></circle><circle cx="13" cy="17" r="3.5" fill="#1F4E9E"></circle><circle cx="51" cy="17" r="3.5" fill="#1F4E9E"></circle></svg><h3>Reach customers beyond a single search engine</h3><p>Plan around the search tools your audience actually uses, such as Google’s AI features, ChatGPT, Perplexity and Copilot, rather than one channel alone.</p></li><li class="sls-card sls-benefit"><svg class="sls-benefit__icon" aria-hidden="true" viewBox="0 0 64 64" fill="none"><path d="M10 28c0-11 9-18 21-18s21 7 21 18-9 18-21 18c-3 0-5-.3-8-1l-10 6 2-9c-3-3-5-8-5-14z" fill="#E2EEFF"></path><path d="M22 28l6 6 13-13" stroke="#1F4E9E" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path><circle cx="50" cy="46" r="7" fill="#F36B32"></circle></svg><h3>Help AI answers describe you accurately</h3><p>Make it easier for AI-assisted search to describe your services, strengths and business details correctly, using clear and consistent public information.</p></li></ul></div>
<!-- /wp:html -->
</section>
<!-- /wp:group -->',
		)
	);

	register_block_pattern(
		'sln-ai-seo/platforms',
		array(
			'title'       => __( 'AI SEO — Platforms', 'smart-leading-net' ),
			'description' => __( 'Illustrative AI research surfaces.', 'smart-leading-net' ),
			'categories'  => array( 'sln-ai-seo' ),
			'content'     => '<!-- wp:group {"tagName":"section","className":"sls-sec","layout":{"type":"default"},"anchor":"ai-search-platforms"} -->
<section id="ai-search-platforms" class="wp-block-group sls-sec" aria-labelledby="ai-search-platforms-h">
<!-- wp:html -->
<div class="sls-wrap sls-platforms"><div class="sls-platforms__intro"><p class="sls-eyebrow">Platform context</p><h2 id="ai-search-platforms-h">Focus on the Search Experiences Your Customers Use</h2><p>Different questions lead people to different search tools. Platform selection should follow your audience, market and research needs.</p><p class="sls-platforms__note">The names shown are examples of where AI-assisted research happens. They do not indicate a partnership or integration with Smart Leading Solutions, and the platforms included in any engagement are agreed during scoping.</p></div><div><ul class="sls-chips" aria-label="Examples of AI-assisted research surfaces"><li class="sls-chips__label" aria-hidden="true">Illustrative research surfaces</li><li>Google AI Overviews &amp; AI Mode</li><li>ChatGPT Search</li><li>Perplexity</li><li>Gemini</li><li>Copilot</li></ul></div></div>
<!-- /wp:html -->
</section>
<!-- /wp:group -->',
		)
	);

	register_block_pattern(
		'sln-ai-seo/approach',
		array(
			'title'       => __( 'AI SEO — Approach', 'smart-leading-net' ),
			'description' => __( 'Six foundation modules.', 'smart-leading-net' ),
			'categories'  => array( 'sln-ai-seo' ),
			'content'     => '<!-- wp:group {"tagName":"section","className":"sls-sec sls-sec--tint","layout":{"type":"default"},"anchor":"ai-search-approach"} -->
<section id="ai-search-approach" class="wp-block-group sls-sec sls-sec--tint" aria-labelledby="ai-search-approach-h">
<!-- wp:html -->
<div class="sls-wrap"><div class="sls-head"><div><p class="sls-eyebrow">Proposed scope</p><h2 id="ai-search-approach-h">Build an AI Search Strategy Around the Right Foundations</h2></div><p class="sls-lead">Six areas of work that can shape an AI search optimization program. Which modules apply to your business, and who carries out each part, is agreed during scoping.</p></div><ul class="sls-grid3"><li class="sls-card"><span class="sls-card__num" aria-hidden="true">01</span><h3>Visibility assessment</h3><p>Review a selected set of questions and the sources that appear in sampled AI answers. The result is a starting point: where your business appears, where it does not, and which sources tend to shape the answers.</p></li><li class="sls-card"><span class="sls-card__num" aria-hidden="true">02</span><h3>Audience and question research</h3><p>Identify the questions that matter when prospective customers compare options, check requirements and look for help, so effort goes to questions with real business relevance.</p></li><li class="sls-card"><span class="sls-card__num" aria-hidden="true">03</span><h3>Technical SEO foundations</h3><p>Review accessibility, indexability and other technical barriers that can stop public information being found or understood. Fixes are specified after assessment, including appropriate structured data where it supports factual clarity.</p></li><li class="sls-card"><span class="sls-card__num" aria-hidden="true">04</span><h3>Helpful, answer-focused content</h3><p>Clarify service pages, comparisons and practical questions using your real expertise and evidence. The aim is content a person finds useful and that is clear enough to be summarized accurately.</p></li><li class="sls-card"><span class="sls-card__num" aria-hidden="true">05</span><h3>Consistent business information</h3><p>Check that core facts such as services, locations, contact details and descriptions are consistent across your website and relevant external profiles or references.</p></li><li class="sls-card"><span class="sls-card__num" aria-hidden="true">06</span><h3>Measurement and refinement</h3><p>Revisit an agreed sample of questions and use available referral and enquiry data to inform what to prioritize next.</p></li></ul></div>
<!-- /wp:html -->
</section>
<!-- /wp:group -->',
		)
	);

	register_block_pattern(
		'sln-ai-seo/pricing',
		array(
			'title'       => __( 'AI SEO — Pricing', 'smart-leading-net' ),
			'description' => __( 'Pricing intro, mini lead form and price card.', 'smart-leading-net' ),
			'categories'  => array( 'sln-ai-seo' ),
			'content'     => '<!-- wp:group {"tagName":"section","className":"sls-sec","layout":{"type":"default"},"anchor":"ai-search-pricing"} -->
<section id="ai-search-pricing" class="wp-block-group sls-sec" aria-labelledby="ai-search-pricing-h">
<!-- wp:group {"className":"sls-wrap sls-pricing","layout":{"type":"default"}} -->
<div class="wp-block-group sls-wrap sls-pricing">
<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group">
<!-- wp:html -->
<p class="sls-eyebrow">Pricing</p><h2 id="ai-search-pricing-h">Transparent Pricing for AI SEO Services, Scoped to Your Goals</h2><p class="sls-lead">AI SEO programs are priced around the work your website and goals require: content, technical foundations and measurement across the AI search experiences your customers use. Share your website to start a conversation about fit and a tailored proposal.</p>
<!-- /wp:html -->
<!-- wp:sln/ai-seo-mini-lead {"placeholder":"Enter your website","buttonLabel":"Send Me a Proposal","variant":"default","ariaLabel":"Start a proposal request","inputId":"ai-mini-website-1","note":"Continues to the short enquiry form below. Nothing is sent at this step.","showArrow":true} /-->
</div>
<!-- /wp:group -->
<!-- wp:html -->
<div class="sls-price"><span class="sls-price__icon" aria-hidden="true"><svg width="44" height="44" viewBox="0 0 48 48" fill="none"><circle cx="20" cy="28" r="14" fill="#E2EEFF"></circle><path d="M10 34l9-9 6 6 13-15" stroke="#1F4E9E" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M30 16h8v8" stroke="#F36B32" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"></path></svg></span><p class="sls-price__label">AI SEO Programs Starting At</p><p class="sls-price__amt">[$X,XXX]<span>/month</span></p><span class="sls-price__bar" aria-hidden="true"></span><p class="sls-price__for">For businesses ready to adapt their search strategy</p><p class="sls-price__fine">Final pricing depends on the scope agreed for your business.</p></div>
<!-- /wp:html -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->',
		)
	);

	register_block_pattern(
		'sln-ai-seo/process',
		array(
			'title'       => __( 'AI SEO — Process', 'smart-leading-net' ),
			'description' => __( 'Four-step process.', 'smart-leading-net' ),
			'categories'  => array( 'sln-ai-seo' ),
			'content'     => '<!-- wp:group {"tagName":"section","className":"sls-sec sls-sec--tint","layout":{"type":"default"},"anchor":"ai-search-process"} -->
<section id="ai-search-process" class="wp-block-group sls-sec sls-sec--tint" aria-labelledby="ai-search-process-h">
<!-- wp:html -->
<div class="sls-wrap"><div class="sls-head"><div><p class="sls-eyebrow">How it works</p><h2 id="ai-search-process-h">From an Initial Picture to Clear Priorities</h2></div><p class="sls-lead">A practical sequence that keeps decisions visible: what was looked at, what was found and what happens next.</p></div><ol class="sls-steps"><li class="sls-step"><span class="sls-step__n" aria-hidden="true">1</span><h3>Understand the business and audience</h3><p>Discuss your services, markets, existing search work and the questions prospective customers ask.</p><p class="sls-step__out"><b>Output:</b> a shared view of goals and the questions worth assessing.</p></li><li class="sls-step"><span class="sls-step__n" aria-hidden="true">2</span><h3>Establish a sampled baseline</h3><p>Review an agreed sample of questions across relevant AI search experiences and note which sources appear.</p><p class="sls-step__out"><b>Output:</b> a dated snapshot to compare against later.</p></li><li class="sls-step"><span class="sls-step__n" aria-hidden="true">3</span><h3>Agree scope and priorities</h3><p>Weigh content, technical and information-consistency opportunities against relevance and effort.</p><p class="sls-step__out"><b>Output:</b> agreed priorities and responsibilities.</p></li><li class="sls-step"><span class="sls-step__n" aria-hidden="true">4</span><h3>Review changes and refine</h3><p>Revisit the sample alongside available referral and enquiry data, and adjust priorities based on what is observed.</p><p class="sls-step__out"><b>Output:</b> observations kept separate from assumptions.</p></li></ol><div class="sls-process__cta"><p>Want to talk through where your business would start?</p><a class="sls-btn sls-btn--primary sls-btn--arrow" href="#ai-search-enquiry">Discuss My AI Search Goals<span class="sls-btn__arrow" aria-hidden="true"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></span></a></div></div>
<!-- /wp:html -->
</section>
<!-- /wp:group -->',
		)
	);

	register_block_pattern(
		'sln-ai-seo/measurement',
		array(
			'title'       => __( 'AI SEO — Measurement', 'smart-leading-net' ),
			'description' => __( 'Measurement signals table.', 'smart-leading-net' ),
			'categories'  => array( 'sln-ai-seo' ),
			'content'     => '<!-- wp:group {"tagName":"section","className":"sls-sec","layout":{"type":"default"},"anchor":"ai-search-measurement"} -->
<section id="ai-search-measurement" class="wp-block-group sls-sec" aria-labelledby="ai-search-measurement-h">
<!-- wp:html -->
<div class="sls-wrap"><div class="sls-head"><div><p class="sls-eyebrow">AI visibility tracking</p><h2 id="ai-search-measurement-h">Measure Visibility Alongside Business Outcomes</h2></div><p class="sls-lead">A mention is useful context. An enquiry tells a different part of the story. A measurement plan should keep those signals distinct.</p></div><div class="sls-table-wrap"><table class="sls-table"><caption class="sls-sr">Four measurement signals, what each can tell a team, and its limits</caption><thead><tr><th scope="col">Signal</th><th scope="col">What it can tell your team</th><th scope="col">What it cannot show</th></tr></thead><tbody><tr><th scope="row"><span aria-hidden="true">01</span>Sampled mentions and citations</th><td data-label="What it can tell your team">Whether and how your business appears for the questions in an agreed sample, and which competitors or sources appear alongside it.</td><td data-label="What it cannot show">How every user sees every answer. A sample is a snapshot of selected questions at a point in time.</td></tr><tr><th scope="row"><span aria-hidden="true">02</span>Source and factual accuracy review</th><td data-label="What it can tell your team">Which pages and profiles answers appear to draw on, and whether your services and details are described correctly.</td><td data-label="What it cannot show">Sources a platform uses but does not display. Not every answer shows its sources.</td></tr><tr><th scope="row"><span aria-hidden="true">03</span>Identifiable AI referral visits</th><td data-label="What it can tell your team">Visits from AI platforms that pass referral information, and which pages those visitors land on.</td><td data-label="What it cannot show">AI-assisted journeys that arrive without referral data, such as when someone later searches your name or types your address.</td></tr><tr><th scope="row"><span aria-hidden="true">04</span>Recorded enquiries and qualification</th><td data-label="What it can tell your team">Whether enquiries arrive and, where your forms and records support it, how relevant those enquiries are.</td><td data-label="What it cannot show">A complete attribution path. Results depend on the tracking and records already in place.</td></tr></tbody></table></div><div class="sls-caveat"><svg aria-hidden="true" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1F4E9E" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 11v6"></path><path d="M12 7.5v.01"></path></svg><p>AI answers can vary by question, timing and user context. A tracked sample is a snapshot, and referral data does not capture every AI-assisted journey.</p></div></div>
<!-- /wp:html -->
</section>
<!-- /wp:group -->',
		)
	);

	register_block_pattern(
		'sln-ai-seo/principles',
		array(
			'title'       => __( 'AI SEO — Principles', 'smart-leading-net' ),
			'description' => __( 'Blue principles section.', 'smart-leading-net' ),
			'categories'  => array( 'sln-ai-seo' ),
			'content'     => '<!-- wp:group {"tagName":"section","className":"sls-sec sls-sec--blue","layout":{"type":"default"},"anchor":"ai-search-principles"} -->
<section id="ai-search-principles" class="wp-block-group sls-sec sls-sec--blue" aria-labelledby="ai-search-principles-h">
<!-- wp:html -->
<div class="sls-wrap sls-principles"><div class="sls-principles__intro"><p class="sls-eyebrow">How we approach the work</p><h2 id="ai-search-principles-h">Start With Clear Questions. Agree on Clear Work.</h2><p>AI search is still changing. These principles are meant to keep the work understandable as it develops, for your team and for ours.</p></div><ol class="sls-plist"><li><span class="sls-plist__n" aria-hidden="true">01</span><div><h3>Define the questions being assessed</h3><p>Agree which customer questions, markets and platforms are in view, so everyone knows what a finding refers to.</p></div></li><li><span class="sls-plist__n" aria-hidden="true">02</span><div><h3>Explain the priority behind each recommendation</h3><p>Each recommendation should state the problem it addresses and why it comes before other work.</p></div></li><li><span class="sls-plist__n" aria-hidden="true">03</span><div><h3>Separate observed changes from assumptions</h3><p>Report what was seen in the sample and the data, and label interpretation as interpretation.</p></div></li></ol></div>
<!-- /wp:html -->
</section>
<!-- /wp:group -->',
		)
	);

	register_block_pattern(
		'sln-ai-seo/scope',
		array(
			'title'       => __( 'AI SEO — Scope', 'smart-leading-net' ),
			'description' => __( 'Scope considerations.', 'smart-leading-net' ),
			'categories'  => array( 'sln-ai-seo' ),
			'content'     => '<!-- wp:group {"tagName":"section","className":"sls-sec sls-sec--warm","layout":{"type":"default"},"anchor":"ai-search-scope"} -->
<section id="ai-search-scope" class="wp-block-group sls-sec sls-sec--warm" aria-labelledby="ai-search-scope-h">
<!-- wp:html -->
<div class="sls-wrap sls-scope"><div class="sls-scope__copy"><p class="sls-eyebrow">Scope and engagement</p><h2 id="ai-search-scope-h">What Would the Right Scope Look Like for Your Business?</h2><p class="sls-lead">The right starting point depends on your website, audience, existing search work and the questions you want to assess. Share that context to begin a discussion about fit.</p><div class="sls-actions"><a class="sls-btn sls-btn--primary sls-btn--arrow" href="#ai-search-enquiry">Discuss My AI Search Goals<span class="sls-btn__arrow" aria-hidden="true"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></span></a></div></div><div class="sls-scope__card"><ul aria-label="What shapes the scope"><li><span class="sls-tick" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg></span><div><b>Size and complexity of the website</b><span class="d">How many pages, templates and services are involved.</span></div></li><li><span class="sls-tick" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg></span><div><b>Current content and technical foundations</b><span class="d">What already works well and what may need attention first.</span></div></li><li><span class="sls-tick" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg></span><div><b>Markets and services</b><span class="d">Which offerings, audiences and locations are in view.</span></div></li><li><span class="sls-tick" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg></span><div><b>Assessment and measurement needs</b><span class="d">Which questions to sample and what data you already track.</span></div></li></ul></div></div>
<!-- /wp:html -->
</section>
<!-- /wp:group -->',
		)
	);

	register_block_pattern(
		'sln-ai-seo/banner',
		array(
			'title'       => __( 'AI SEO — Banner CTA', 'smart-leading-net' ),
			'description' => __( 'Proof banner with image and lead form.', 'smart-leading-net' ),
			'categories'  => array( 'sln-ai-seo' ),
			'content'     => '<!-- wp:group {"tagName":"section","className":"sls-sec sls-sec--banner","layout":{"type":"default"},"anchor":"ai-search-banner"} -->
<section id="ai-search-banner" class="wp-block-group sls-sec sls-sec--banner" aria-labelledby="ai-search-banner-h">
<!-- wp:group {"className":"sls-wrap","layout":{"type":"default"}} -->
<div class="wp-block-group sls-wrap">
<!-- wp:group {"className":"sls-banner","layout":{"type":"default"}} -->
<div class="wp-block-group sls-banner">
<!-- wp:html -->
<div class="sls-banner__proof"><figure class="sls-banner__photo wp-block-image"><img src="/wp-content/themes/smart-leading-net/assets/images/ai-seo/team-photo-placeholder.svg" alt="SLS team member" loading="lazy" decoding="async" width="170" height="250"/></figure><div class="sls-banner__stat"><p class="sls-banner__k">SLS AI Search Results</p><p class="sls-banner__n">[Verified figure]</p><p class="sls-banner__l">[What the figure measures]</p></div></div>
<!-- /wp:html -->
<!-- wp:group {"className":"sls-banner__copy","layout":{"type":"default"}} -->
<div class="wp-block-group sls-banner__copy">
<!-- wp:html -->
<h2 id="ai-search-banner-h">Be Visible Wherever Your Customers Research</h2><p>Build a clearer presence across Google, ChatGPT, Perplexity and the other AI search experiences your audience uses. Share your website to request a custom strategy conversation.</p>
<!-- /wp:html -->
<!-- wp:sln/ai-seo-mini-lead {"placeholder":"Enter your website","buttonLabel":"Request My Custom Strategy","variant":"onblue","ariaLabel":"Start a strategy request","inputId":"ai-mini-website-2","note":"","showArrow":false} /-->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->',
		)
	);

	register_block_pattern(
		'sln-ai-seo/faq',
		array(
			'title'       => __( 'AI SEO — FAQ', 'smart-leading-net' ),
			'description' => __( 'Editable Details FAQ accordion.', 'smart-leading-net' ),
			'categories'  => array( 'sln-ai-seo' ),
			'content'     => '<!-- wp:group {"tagName":"section","className":"sls-sec","layout":{"type":"default"},"anchor":"ai-search-faq"} -->
<section id="ai-search-faq" class="wp-block-group sls-sec" aria-labelledby="ai-search-faq-h">
<!-- wp:html -->
<div class="sls-wrap sls-faqwrap"><div class="sls-faqwrap__intro"><p class="sls-eyebrow">FAQs</p><h2 id="ai-search-faq-h">AI SEO Services: Common Questions</h2><p>Have a question that is not covered here? Include it in your <a href="#ai-search-enquiry">AI search enquiry</a>.</p></div><div class="sls-faq">
<!-- /wp:html -->
<!-- wp:details {"showContent":true} -->
<details class="wp-block-details" open>
<summary>What are AI SEO services?</summary>
<!-- wp:paragraph -->
<p>AI SEO services focus on the website and brand information that may support discovery in AI-assisted search. The work can involve content, technical foundations and measurement. The specific scope of any engagement is agreed separately during scoping.</p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:details -->
<details class="wp-block-details">
<summary>What is the difference between SEO, GEO and AEO?</summary>
<!-- wp:paragraph -->
<p>They are overlapping terms. SEO covers organic search discovery, GEO focuses on visibility in generated answers, and AEO emphasizes answering questions clearly. They describe related priorities rather than entirely separate systems.</p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:details -->
<details class="wp-block-details">
<summary>Does LLM optimization mean training a model?</summary>
<!-- wp:paragraph -->
<p>No. On this page, LLM optimization means work on how clearly your website and brand information can be found and understood. Model development and fine-tuning are outside the proposed scope.</p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:details -->
<details class="wp-block-details">
<summary>Can you guarantee a ChatGPT mention or AI citation?</summary>
<!-- wp:paragraph -->
<p>No. Placement in AI answers should not be promised by anyone. Each platform determines its own answers, and those answers can vary across questions, timing and context.</p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:details -->
<details class="wp-block-details">
<summary>Does AI search optimization replace SEO?</summary>
<!-- wp:paragraph -->
<p>It is best treated as a related discovery consideration rather than a replacement. A useful, technically accessible website remains a sensible foundation for both.</p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:details -->
<details class="wp-block-details">
<summary>How is AI search visibility measured?</summary>
<!-- wp:paragraph -->
<p>An agreed sample of questions can be reviewed for mentions and citations, alongside available referral and enquiry data. Each of these measures has limits, so they are reported separately rather than combined into a single score.</p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:details -->
<details class="wp-block-details">
<summary>How long will results take?</summary>
<!-- wp:paragraph -->
<p>There is no fixed timeline. Timing depends on the work involved, your starting point and how each platform behaves.</p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:details -->
<details class="wp-block-details">
<summary>Which platforms and deliverables would my engagement include?</summary>
<!-- wp:paragraph -->
<p>Platform coverage and responsibilities are confirmed during scoping. It should not be assumed that every platform named on this page, or every proposed module, is included.</p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:html -->
</div></div>
<!-- /wp:html -->
</section>
<!-- /wp:group -->',
		)
	);

	register_block_pattern(
		'sln-ai-seo/enquiry',
		array(
			'title'       => __( 'AI SEO — Enquiry', 'smart-leading-net' ),
			'description' => __( 'Enquiry copy and form.', 'smart-leading-net' ),
			'categories'  => array( 'sln-ai-seo' ),
			'content'     => '<!-- wp:group {"tagName":"section","className":"sls-sec sls-sec--tint","layout":{"type":"default"},"anchor":"ai-search-enquiry"} -->
<section id="ai-search-enquiry" class="wp-block-group sls-sec sls-sec--tint" aria-labelledby="ai-search-enquiry-h">
<!-- wp:group {"className":"sls-wrap sls-enq","layout":{"type":"default"}} -->
<div class="wp-block-group sls-wrap sls-enq">
<!-- wp:html -->
<div class="sls-enq__copy"><p class="sls-eyebrow">Start a conversation</p><h2 id="ai-search-enquiry-h">Let’s Discuss Your AI Search Goals</h2><p class="sls-lead">Tell us about your business, your website and what you want to understand about AI search.</p><ul class="sls-enq__list"><li>The services and markets that matter most to you</li><li>Questions you would like to see your business answer well</li><li>Any existing SEO work or tracking already in place</li></ul><p class="sls-enq__next">After you send this form, a Smart Leading team member will review your details and follow up to discuss fit and next steps.</p></div>
<!-- /wp:html -->
<!-- wp:sln/ai-seo-enquiry-form {"privacyUrl":"/privacy-policy/"} /-->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->',
		)
	);
}
add_action( 'init', 'sln_ai_seo_register_block_patterns' );
