<?php
/**
 * AEO Services page — helpers, URLs, and section content.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_AEO_TEMPLATE', 'aeo-page-template.php' );
define( 'SLN_AEO_SLUG', 'answer-engine-optimization-aeo' );
define( 'SLN_AEO_PARENT_SLUG', 'digital-marketing-services' );
define( 'SLN_AEO_META_DESCRIPTION', 'Answer Engine Optimization services that help your brand get surfaced in AI Overviews, ChatGPT, Perplexity, and voice search — not just ranked in blue links.' );

/**
 * Whether the current request uses the AEO Services template.
 *
 * @return bool
 */
function sln_is_aeo_services_page() {
	return is_page_template( SLN_AEO_TEMPLATE );
}

/**
 * AEO page button — reuses the global sls-btn CTA.
 *
 * @param array $args Button args.
 */
function sln_render_aeo_page_button( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'text'    => '',
			'url'     => '#',
			'variant' => 'primary',
			'class'   => '',
			'arrow'   => true,
			'type'    => 'link',
		)
	);

	if ( '' === $args['text'] ) {
		return;
	}

	if ( ! function_exists( 'sln_render_cta_button' ) ) {
		return;
	}

	sln_render_cta_button(
		array(
			'text'       => $args['text'],
			'url'        => $args['url'],
			'type'       => $args['type'],
			'variant'    => $args['variant'],
			'show_arrow' => ! empty( $args['arrow'] ),
			'class'      => trim( 'sln-aeo-cta ' . $args['class'] ),
		)
	);
}

/**
 * Contact page URL.
 *
 * @return string
 */
function sln_aeo_get_contact_url() {
	$page = get_page_by_path( 'contact-us' );

	return $page instanceof WP_Post ? get_permalink( $page ) : home_url( '/contact-us/' );
}

/**
 * Digital Marketing Services parent URL.
 *
 * @return string
 */
function sln_aeo_get_digital_marketing_url() {
	$page = get_page_by_path( SLN_AEO_PARENT_SLUG );

	return $page instanceof WP_Post ? get_permalink( $page ) : home_url( '/' . SLN_AEO_PARENT_SLUG . '/' );
}

/**
 * SEO Services page URL.
 *
 * @return string
 */
function sln_aeo_get_seo_services_url() {
	$page = get_page_by_path( 'seo-services' );

	if ( $page instanceof WP_Post ) {
		return get_permalink( $page );
	}

	$pages = get_pages(
		array(
			'meta_key'   => '_wp_page_template',
			'meta_value' => 'seo-page-template.php',
			'number'     => 1,
		)
	);

	if ( ! empty( $pages[0] ) && $pages[0] instanceof WP_Post ) {
		return get_permalink( $pages[0] );
	}

	return home_url( '/seo-services/' );
}

/**
 * Inline SVG icon by key.
 *
 * @param string $icon Icon key.
 * @return string
 */
function sln_aeo_icon( $icon ) {
	$icon = sanitize_key( (string) $icon );

	$icons = array(
		'star'       => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3l2.2 5.8L20 11l-5.8 2.2L12 19l-2.2-5.8L4 11l5.8-2.2L12 3z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>',
		'list'       => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 6h16M4 12h10M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
		'check'      => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'arrow'      => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 12h16M14 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'help'       => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6"/><path d="M9 10c0-1.7 1.3-3 3-3s3 1.3 3 3c0 2-3 2-3 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
		'plus'       => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 4v16M4 12h16" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
		'mic'        => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="9" y="3" width="6" height="12" rx="3" stroke="currentColor" stroke-width="1.6"/><path d="M6 11a6 6 0 0012 0M12 19v2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
		'lines'      => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 12h18M3 6h18M3 18h11" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
		'doc'        => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="4" y="4" width="16" height="16" rx="3" stroke="currentColor" stroke-width="1.6"/><path d="M8 10h8M8 14h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
		'search'     => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8"/><path d="M21 21l-4.3-4.3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>',
		'square'     => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="4" y="4" width="16" height="16" rx="4" stroke="currentColor" stroke-width="1.8"/></svg>',
		'nodes'      => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="7" cy="7" r="2.5" stroke="currentColor" stroke-width="1.6"/><circle cx="17" cy="7" r="2.5" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="17" r="2.5" stroke="currentColor" stroke-width="1.6"/></svg>',
		'graph'      => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="7" cy="7" r="2.5" stroke="currentColor" stroke-width="1.6"/><circle cx="17" cy="7" r="2.5" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="17" r="2.5" stroke="currentColor" stroke-width="1.6"/><path d="M9 8.5L10.5 15M15 8.5L13.5 15M9.5 7h5" stroke="currentColor" stroke-width="1.4"/></svg>',
		'blocks'     => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 5h16v6H4zM4 15h10v4H4z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>',
		'crosshair'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3v18M3 12h18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6"/></svg>',
		'voice'      => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 2a4 4 0 014 4v5a4 4 0 01-8 0V6a4 4 0 014-4z" stroke="currentColor" stroke-width="1.6"/><path d="M6 11a6 6 0 0012 0M12 19v3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
		'bag'        => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 8l1-4h14l1 4M4 8h16M4 8l1 12h14l1-12" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>',
		'health'     => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3v18M5 8h14M5 16h14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
		'home'       => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 21V9l8-6 8 6v12M9 21v-6h6v6" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>',
		'pin'        => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 21c4-4 7-7.5 7-11a7 7 0 10-14 0c0 3.5 3 7 7 11z" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="10" r="2.4" stroke="currentColor" stroke-width="1.6"/></svg>',
		'card'       => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="6" width="18" height="12" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M8 10h3M8 14h6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
		'award'      => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 3h6l1 5-4 3-4-3 1-5zM6 21l3-6h6l3 6" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>',
		'trend'      => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 17l5-5 4 4 7-8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'tick'       => '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'faq'        => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.5"/></svg>',
	);

	return isset( $icons[ $icon ] ) ? $icons[ $icon ] : '';
}

/**
 * Search evolution stages.
 *
 * @return array<int, array<string, string>>
 */
function sln_aeo_get_evolution_stages() {
	return array(
		array(
			'icon'        => 'list',
			'label'       => __( 'Stage 01', 'smart-leading-net' ),
			'title'       => __( 'Traditional Search', 'smart-leading-net' ),
			'description' => __( 'Ten blue links. The user clicks through, compares pages, and decides for themselves.', 'smart-leading-net' ),
			'modifier'    => '',
		),
		array(
			'icon'        => 'star',
			'label'       => __( 'Stage 02', 'smart-leading-net' ),
			'title'       => __( 'AI-Powered Search', 'smart-leading-net' ),
			'description' => __( 'Google, Bing, and assistants read across many sources and synthesize a summary before any link appears.', 'smart-leading-net' ),
			'modifier'    => 'mid',
		),
		array(
			'icon'        => 'check',
			'label'       => __( 'Stage 03', 'smart-leading-net' ),
			'title'       => __( 'Direct Answers', 'smart-leading-net' ),
			'description' => __( 'The user gets a complete answer with citations — sometimes never visiting a website at all.', 'smart-leading-net' ),
			'modifier'    => 'final',
		),
	);
}

/**
 * Why AEO matters cards.
 *
 * @return array<int, array<string, string|bool>>
 */
function sln_aeo_get_why_cards() {
	return array(
		array(
			'icon'        => 'star',
			'title'       => __( 'Google AI Overviews', 'smart-leading-net' ),
			'description' => __( 'Now appear above traditional results for a large share of informational queries, often answering the question before a click ever happens.', 'smart-leading-net' ),
			'wide'        => true,
		),
		array(
			'icon'        => 'help',
			'title'       => __( 'ChatGPT', 'smart-leading-net' ),
			'description' => __( 'Used as a search substitute for research and recommendations.', 'smart-leading-net' ),
			'wide'        => false,
		),
		array(
			'icon'        => 'plus',
			'title'       => __( 'Perplexity', 'smart-leading-net' ),
			'description' => __( 'Built entirely around cited, conversational answers.', 'smart-leading-net' ),
			'wide'        => false,
		),
		array(
			'icon'        => 'mic',
			'title'       => __( 'Voice Assistants', 'smart-leading-net' ),
			'description' => __( 'Read back a single answer — there\'s no "page two."', 'smart-leading-net' ),
			'wide'        => false,
		),
		array(
			'icon'        => 'lines',
			'title'       => __( 'Zero-click & conversational search', 'smart-leading-net' ),
			'description' => __( 'More searches now end without a click-through. Brand visibility increasingly depends on being the source an AI system chooses to cite, not just the page it links to.', 'smart-leading-net' ),
			'wide'        => true,
		),
	);
}

/**
 * Strategy timeline steps.
 *
 * @return array<int, array<string, string>>
 */
function sln_aeo_get_strategy_steps() {
	return array(
		array(
			'number'      => '01',
			'title'       => __( 'Research & Question Mapping', 'smart-leading-net' ),
			'description' => __( 'We identify the real questions your audience asks answer engines, in their own words.', 'smart-leading-net' ),
		),
		array(
			'number'      => '02',
			'title'       => __( 'Search Intent Analysis', 'smart-leading-net' ),
			'description' => __( 'We map each question to what the asker actually needs — a fact, a comparison, or a decision.', 'smart-leading-net' ),
		),
		array(
			'number'      => '03',
			'title'       => __( 'Content Optimization', 'smart-leading-net' ),
			'description' => __( 'Pages are restructured to answer clearly and directly, in a format AI systems can extract.', 'smart-leading-net' ),
		),
		array(
			'number'      => '04',
			'title'       => __( 'Entity Optimization', 'smart-leading-net' ),
			'description' => __( 'We clarify who you are and what you offer so AI systems correctly identify your brand as an entity.', 'smart-leading-net' ),
		),
		array(
			'number'      => '05',
			'title'       => __( 'Structured Data', 'smart-leading-net' ),
			'description' => __( 'Schema markup makes your content machine-readable, not just human-readable.', 'smart-leading-net' ),
		),
		array(
			'number'      => '06',
			'title'       => __( 'Authority Signals', 'smart-leading-net' ),
			'description' => __( 'We build the citations and mentions that answer engines use to judge trustworthiness.', 'smart-leading-net' ),
		),
		array(
			'number'      => '07',
			'title'       => __( 'AI Visibility Tracking', 'smart-leading-net' ),
			'description' => __( 'We monitor where and how your brand appears across AI Overviews, ChatGPT, and Perplexity.', 'smart-leading-net' ),
		),
	);
}

/**
 * AEO services cards.
 *
 * @return array<int, array<string, string>>
 */
function sln_aeo_get_services() {
	return array(
		array(
			'icon'        => 'star',
			'title'       => __( 'AI Search Optimization', 'smart-leading-net' ),
			'description' => __( 'Optimizing your site to be understood, indexed, and quoted by AI-driven search systems.', 'smart-leading-net' ),
		),
		array(
			'icon'        => 'doc',
			'title'       => __( 'Featured Snippet Optimization', 'smart-leading-net' ),
			'description' => __( 'Structuring content to win the answer box position in traditional search.', 'smart-leading-net' ),
		),
		array(
			'icon'        => 'lines',
			'title'       => __( 'AI Overview Optimization', 'smart-leading-net' ),
			'description' => __( 'Formatting and sourcing content so it\'s a strong candidate for Google\'s AI Overviews.', 'smart-leading-net' ),
		),
		array(
			'icon'        => 'help',
			'title'       => __( 'Q&A Content Strategy', 'smart-leading-net' ),
			'description' => __( 'Building content around the exact questions your customers ask AI assistants.', 'smart-leading-net' ),
		),
		array(
			'icon'        => 'mic',
			'title'       => __( 'Conversational Search Optimization', 'smart-leading-net' ),
			'description' => __( 'Writing for natural, multi-turn, spoken-language queries — not just keywords.', 'smart-leading-net' ),
		),
		array(
			'icon'        => 'graph',
			'title'       => __( 'Entity & Knowledge Graph Optimization', 'smart-leading-net' ),
			'description' => __( 'Strengthening how AI systems identify and connect your brand as a distinct entity.', 'smart-leading-net' ),
		),
		array(
			'icon'        => 'blocks',
			'title'       => __( 'Structured Data Optimization', 'smart-leading-net' ),
			'description' => __( 'Implementing schema so machines can accurately parse your content.', 'smart-leading-net' ),
		),
		array(
			'icon'        => 'lines',
			'title'       => __( 'Semantic Content Optimization', 'smart-leading-net' ),
			'description' => __( 'Writing with the topical depth and clarity language models reward.', 'smart-leading-net' ),
		),
		array(
			'icon'        => 'crosshair',
			'title'       => __( 'Topical Authority Development', 'smart-leading-net' ),
			'description' => __( 'Building content clusters that establish deep expertise on your core subjects.', 'smart-leading-net' ),
		),
		array(
			'icon'        => 'voice',
			'title'       => __( 'Voice Search Optimization', 'smart-leading-net' ),
			'description' => __( 'Targeting the natural-language, question-based phrasing voice search relies on.', 'smart-leading-net' ),
		),
		array(
			'icon'        => 'check',
			'title'       => __( 'Brand Mention & Citation Strategy', 'smart-leading-net' ),
			'description' => __( 'Earning the third-party mentions AI models use as trust signals.', 'smart-leading-net' ),
		),
	);
}

/**
 * How AEO works flow nodes.
 *
 * @return array<int, array<string, string|bool>>
 */
function sln_aeo_get_flow_nodes() {
	return array(
		array(
			'icon'        => 'search',
			'title'       => __( 'User Question', 'smart-leading-net' ),
			'description' => __( 'Someone asks a real question, often in natural language.', 'smart-leading-net' ),
			'highlight'   => true,
		),
		array(
			'icon'        => 'square',
			'title'       => __( 'Search / AI System', 'smart-leading-net' ),
			'description' => __( 'The engine parses intent and searches its index.', 'smart-leading-net' ),
			'highlight'   => false,
		),
		array(
			'icon'        => 'lines',
			'title'       => __( 'Relevant Sources', 'smart-leading-net' ),
			'description' => __( 'It shortlists pages that clearly answer the question.', 'smart-leading-net' ),
			'highlight'   => false,
		),
		array(
			'icon'        => 'nodes',
			'title'       => __( 'Understanding / Entities', 'smart-leading-net' ),
			'description' => __( 'It confirms who you are and whether you\'re a credible source.', 'smart-leading-net' ),
			'highlight'   => false,
		),
		array(
			'icon'        => 'star',
			'title'       => __( 'Generated Answer', 'smart-leading-net' ),
			'description' => __( 'It synthesizes an answer, drawing from top sources.', 'smart-leading-net' ),
			'highlight'   => false,
		),
		array(
			'icon'        => 'check',
			'title'       => __( 'Brand Visibility', 'smart-leading-net' ),
			'description' => __( 'Your brand appears in the answer, with or without a click.', 'smart-leading-net' ),
			'highlight'   => true,
		),
	);
}

/**
 * Traditional SEO vs AEO rows.
 *
 * @return array<int, array<string, string>>
 */
function sln_aeo_get_comparison_rows() {
	return array(
		array(
			'seo' => __( 'Ranking pages', 'smart-leading-net' ),
			'aeo' => __( 'Being referenced inside answers', 'smart-leading-net' ),
		),
		array(
			'seo' => __( 'Keyword targeting', 'smart-leading-net' ),
			'aeo' => __( 'Questions & underlying intent', 'smart-leading-net' ),
		),
		array(
			'seo' => __( 'Blue links', 'smart-leading-net' ),
			'aeo' => __( 'AI-generated responses', 'smart-leading-net' ),
		),
		array(
			'seo' => __( 'SERPs', 'smart-leading-net' ),
			'aeo' => __( 'AI search experiences', 'smart-leading-net' ),
		),
		array(
			'seo' => __( 'Click-through rankings', 'smart-leading-net' ),
			'aeo' => __( 'Brand visibility & citations', 'smart-leading-net' ),
		),
	);
}

/**
 * Answer engine platforms.
 *
 * @return array<int, string>
 */
function sln_aeo_get_platforms() {
	return array(
		__( 'Google AI Overviews', 'smart-leading-net' ),
		__( 'ChatGPT', 'smart-leading-net' ),
		__( 'Perplexity', 'smart-leading-net' ),
		__( 'Microsoft Copilot', 'smart-leading-net' ),
		__( 'Google Gemini', 'smart-leading-net' ),
		__( 'Voice Search', 'smart-leading-net' ),
	);
}

/**
 * Industries served.
 *
 * @return array<int, array<string, string>>
 */
function sln_aeo_get_industries() {
	return array(
		array( 'icon' => 'square', 'title' => __( 'SaaS', 'smart-leading-net' ) ),
		array( 'icon' => 'bag', 'title' => __( 'Ecommerce', 'smart-leading-net' ) ),
		array( 'icon' => 'health', 'title' => __( 'Healthcare', 'smart-leading-net' ) ),
		array( 'icon' => 'home', 'title' => __( 'Professional Services', 'smart-leading-net' ) ),
		array( 'icon' => 'pin', 'title' => __( 'Local Businesses', 'smart-leading-net' ) ),
		array( 'icon' => 'card', 'title' => __( 'B2B', 'smart-leading-net' ) ),
		array( 'icon' => 'award', 'title' => __( 'Technology', 'smart-leading-net' ) ),
		array( 'icon' => 'trend', 'title' => __( 'Finance', 'smart-leading-net' ) ),
	);
}

/**
 * Expected results.
 *
 * @return array<int, array<string, string>>
 */
function sln_aeo_get_results() {
	return array(
		array(
			'title'       => __( 'Increased AI search visibility', 'smart-leading-net' ),
			'description' => __( 'More consistent appearances inside AI Overviews and assistant answers.', 'smart-leading-net' ),
		),
		array(
			'title'       => __( 'More brand mentions', 'smart-leading-net' ),
			'description' => __( 'Wider citation of your brand across AI-generated responses.', 'smart-leading-net' ),
		),
		array(
			'title'       => __( 'Increased qualified traffic', 'smart-leading-net' ),
			'description' => __( 'Visitors arriving already primed with intent, from answer citations.', 'smart-leading-net' ),
		),
		array(
			'title'       => __( 'Greater topical authority', 'smart-leading-net' ),
			'description' => __( 'A stronger footprint across the questions that matter to your industry.', 'smart-leading-net' ),
		),
		array(
			'title'       => __( 'Better conversational visibility', 'smart-leading-net' ),
			'description' => __( 'Stronger presence in multi-turn, natural-language queries.', 'smart-leading-net' ),
		),
		array(
			'title'       => __( 'More zero-click opportunity', 'smart-leading-net' ),
			'description' => __( 'Presence and trust built even when the user never clicks through.', 'smart-leading-net' ),
		),
	);
}

/**
 * FAQ items.
 *
 * @return array<int, array<string, string>>
 */
function sln_aeo_get_faq_items() {
	return array(
		array(
			'question' => __( 'What is Answer Engine Optimization?', 'smart-leading-net' ),
			'answer'   => __( 'AEO is the practice of structuring and positioning content so AI-powered search tools — like Google AI Overviews, ChatGPT, and Perplexity — can understand it, trust it, and cite it directly in the answers they generate.', 'smart-leading-net' ),
		),
		array(
			'question' => __( 'How is AEO different from SEO?', 'smart-leading-net' ),
			'answer'   => __( 'SEO focuses on ranking pages in a list of links. AEO focuses on being the source an AI system references when it writes a direct answer, which requires different content structure, entity clarity, and technical markup.', 'smart-leading-net' ),
		),
		array(
			'question' => __( 'Does AEO replace SEO?', 'smart-leading-net' ),
			'answer'   => __( 'No. AEO builds on strong technical and on-page SEO foundations — it doesn\'t work as a standalone replacement for them.', 'smart-leading-net' ),
		),
		array(
			'question' => __( 'How does AEO work with Google AI Overviews?', 'smart-leading-net' ),
			'answer'   => __( 'We structure content to directly and clearly answer specific questions, use supporting schema, and build the authority signals AI Overviews weigh when selecting which sources to summarize and cite.', 'smart-leading-net' ),
		),
		array(
			'question' => __( 'Can AEO help my brand appear in ChatGPT?', 'smart-leading-net' ),
			'answer'   => __( 'AEO improves the underlying signals — clarity, structure, third-party mentions — that models draw on when trained or when retrieving live information, which can improve how often your brand is referenced.', 'smart-leading-net' ),
		),
		array(
			'question' => __( 'Does AEO work for ecommerce websites?', 'smart-leading-net' ),
			'answer'   => __( 'Yes. Product Q&A content, comparison content, and structured product data all help ecommerce brands surface in AI-driven shopping and research queries.', 'smart-leading-net' ),
		),
		array(
			'question' => __( 'How long does AEO take?', 'smart-leading-net' ),
			'answer'   => __( 'Timelines vary by site size, current authority, and competitiveness of your space. As with SEO, AEO is an ongoing process rather than a one-time project.', 'smart-leading-net' ),
		),
		array(
			'question' => __( 'Why is AEO important for future search?', 'smart-leading-net' ),
			'answer'   => __( 'As more queries are resolved directly inside AI answers, brands that aren\'t structured to be cited risk losing visibility even while still ranking well in traditional search.', 'smart-leading-net' ),
		),
	);
}

/**
 * FAQ schema for JSON-LD.
 *
 * @return array<string, mixed>
 */
function sln_aeo_get_faq_schema() {
	$entities = array();

	foreach ( sln_aeo_get_faq_items() as $item ) {
		$entities[] = array(
			'@type'          => 'Question',
			'name'           => $item['question'],
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => $item['answer'],
			),
		);
	}

	return array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $entities,
	);
}

/**
 * Output page title for the AEO template.
 *
 * @param array<string, string> $title Title parts.
 * @return array<string, string>
 */
function sln_aeo_filter_document_title( $title ) {
	if ( ! sln_is_aeo_services_page() ) {
		return $title;
	}

	$title['title'] = __( 'Answer Engine Optimization (AEO) Services', 'smart-leading-net' );

	return $title;
}
add_filter( 'document_title_parts', 'sln_aeo_filter_document_title' );

/**
 * Output meta description for the AEO template.
 */
function sln_aeo_output_meta_description() {
	if ( ! sln_is_aeo_services_page() ) {
		return;
	}

	printf(
		'<meta name="description" content="%s">' . "\n",
		esc_attr( SLN_AEO_META_DESCRIPTION )
	);
}
add_action( 'wp_head', 'sln_aeo_output_meta_description', 4 );
