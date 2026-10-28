<?php
/**
 * Reusable Gutenberg patterns for single blog designed components.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register blog pattern category and patterns.
 */
function sln_blog_register_block_patterns() {
	register_block_pattern_category(
		'sln-blog',
		array(
			'label' => __( 'Blog components', 'smart-leading-net' ),
		)
	);

	register_block_pattern(
		'sln-blog/comparison-table',
		array(
			'title'       => __( 'Comparison table', 'smart-leading-net' ),
			'description' => __( 'Blue-header comparison table with optional winning indicators.', 'smart-leading-net' ),
			'categories'  => array( 'sln-blog' ),
			'content'     => '<!-- wp:html -->
<p class="table-hint" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12h16M8 8l-4 4 4 4M16 8l4 4-4 4"/></svg>Swipe the table to compare</p>
<div class="table-wrap">
<table class="compare-table">
<caption>Replace this caption with a short note about the comparison.</caption>
<thead><tr><th scope="col">Factor</th><th scope="col">Option A</th><th scope="col">Option B</th></tr></thead>
<tbody>
<tr><th scope="row">Factor one</th><td>Detail<br><span class="win">Easier</span></td><td>Detail</td></tr>
<tr><th scope="row">Factor two</th><td>Detail</td><td>Detail<br><span class="win">More control</span></td></tr>
</tbody>
</table>
</div>
<!-- /wp:html -->',
		)
	);

	register_block_pattern(
		'sln-blog/pros-cons',
		array(
			'title'       => __( 'Pros and cons', 'smart-leading-net' ),
			'description' => __( 'Two-column likes and limitations cards.', 'smart-leading-net' ),
			'categories'  => array( 'sln-blog' ),
			'content'     => '<!-- wp:html -->
<div class="proscons">
<div class="pros">
<h4><span aria-hidden="true">+</span>What we like</h4>
<ul class="pc-list">
<li>First advantage</li>
<li>Second advantage</li>
</ul>
</div>
<div class="cons">
<h4><span aria-hidden="true">–</span>Where it falls short</h4>
<ul class="pc-list">
<li>First limitation</li>
<li>Second limitation</li>
</ul>
</div>
</div>
<!-- /wp:html -->',
		)
	);

	register_block_pattern(
		'sln-blog/developer-tip',
		array(
			'title'       => __( 'Developer tip', 'smart-leading-net' ),
			'description' => __( 'Blue information callout.', 'smart-leading-net' ),
			'categories'  => array( 'sln-blog' ),
			'content'     => '<!-- wp:html -->
<div class="callout callout--tip">
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01"/></svg>
<p><strong>Developer tip</strong> Replace this with the practical note for this article.</p>
</div>
<!-- /wp:html -->',
		)
	);

	register_block_pattern(
		'sln-blog/watch-out',
		array(
			'title'       => __( 'Watch out', 'smart-leading-net' ),
			'description' => __( 'Orange warning callout.', 'smart-leading-net' ),
			'categories'  => array( 'sln-blog' ),
			'content'     => '<!-- wp:html -->
<div class="callout callout--warn">
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3 2 20h20z"/><path d="M12 10v4M12 17h.01"/></svg>
<p><strong>Watch out</strong> Replace this with the risk or caveat for this article.</p>
</div>
<!-- /wp:html -->',
		)
	);

	register_block_pattern(
		'sln-blog/five-steps',
		array(
			'title'       => __( 'Numbered steps', 'smart-leading-net' ),
			'description' => __( 'Vertical numbered process with connecting line.', 'smart-leading-net' ),
			'categories'  => array( 'sln-blog' ),
			'content'     => '<!-- wp:html -->
<ol class="steps">
<li>
<h3>First step</h3>
<p>Describe the first action.</p>
</li>
<li>
<h3>Second step</h3>
<p>Describe the second action.</p>
</li>
<li>
<h3>Third step</h3>
<p>Describe the third action.</p>
</li>
</ol>
<!-- /wp:html -->',
		)
	);

	register_block_pattern(
		'sln-blog/pull-quote',
		array(
			'title'       => __( 'Pull quote', 'smart-leading-net' ),
			'description' => __( 'Accent-bordered quote with optional attribution.', 'smart-leading-net' ),
			'categories'  => array( 'sln-blog' ),
			'content'     => '<!-- wp:html -->
<blockquote class="pull-quote">
<p>“Replace this with the quote.”</p>
<footer>
<span class="avatar avatar--initials" aria-hidden="true">SL</span>
<span><b>Name</b>Role</span>
</footer>
</blockquote>
<!-- /wp:html -->',
		)
	);

	register_block_pattern(
		'sln-blog/issues-list',
		array(
			'title'       => __( 'Issue list', 'smart-leading-net' ),
			'description' => __( 'Icon list for risks or common problems.', 'smart-leading-net' ),
			'categories'  => array( 'sln-blog' ),
			'content'     => '<!-- wp:html -->
<ul class="issues">
<li>
<span aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13.5a4.5 4.5 0 0 0 6.4.4l2.7-2.7a4.5 4.5 0 0 0-6.4-6.4"/><path d="M14 10.5a4.5 4.5 0 0 0-6.4-.4l-2.7 2.7a4.5 4.5 0 0 0 6.4 6.4"/><path d="m3 3 18 18"/></svg></span>
<div><b>Issue title</b><p>Short explanation of the issue and how to avoid it.</p></div>
</li>
</ul>
<!-- /wp:html -->',
		)
	);

	register_block_pattern(
		'sln-blog/inline-cta',
		array(
			'title'       => __( 'Inline article CTA', 'smart-leading-net' ),
			'description' => __( 'Blue in-article call to action with buttons.', 'smart-leading-net' ),
			'categories'  => array( 'sln-blog' ),
			'content'     => '<!-- wp:html -->
<aside class="inline-cta" aria-label="Consultation">
<div>
<h3>Not sure which option fits?</h3>
<p>Tell us about your business. We will recommend a plan.</p>
</div>
<div class="inline-cta__actions">
<a class="sln-blog-btn sln-blog-btn--accent" href="/contact-us/">Book a free consultation</a>
<a class="sln-blog-btn sln-blog-btn--ghost-light" href="/portfolio/">See our portfolio</a>
</div>
</aside>
<!-- /wp:html -->',
		)
	);
}
add_action( 'init', 'sln_blog_register_block_patterns' );
