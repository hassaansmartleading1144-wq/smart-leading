<?php
/**
 * AEO Services page — defaults, meta keys, and frontend data helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_AEO_HERO_META', '_sln_aeo_hero' );
define( 'SLN_AEO_HERO_PROOF_META', '_sln_aeo_hero_proof' );
define( 'SLN_AEO_EVOLUTION_SECTION_META', '_sln_aeo_evolution_section' );
define( 'SLN_AEO_EVOLUTION_STAGES_META', '_sln_aeo_evolution_stages' );
define( 'SLN_AEO_WHY_SECTION_META', '_sln_aeo_why_section' );
define( 'SLN_AEO_WHY_CARDS_META', '_sln_aeo_why_cards' );
define( 'SLN_AEO_STRATEGY_SECTION_META', '_sln_aeo_strategy_section' );
define( 'SLN_AEO_STRATEGY_STEPS_META', '_sln_aeo_strategy_steps' );
define( 'SLN_AEO_SERVICES_SECTION_META', '_sln_aeo_services_section' );
define( 'SLN_AEO_SERVICES_ITEMS_META', '_sln_aeo_services_items' );
define( 'SLN_AEO_HOW_SECTION_META', '_sln_aeo_how_section' );
define( 'SLN_AEO_HOW_STEPS_META', '_sln_aeo_how_steps' );
define( 'SLN_AEO_COMPARISON_SECTION_META', '_sln_aeo_comparison_section' );
define( 'SLN_AEO_COMPARISON_ROWS_META', '_sln_aeo_comparison_rows' );
define( 'SLN_AEO_PLATFORMS_SECTION_META', '_sln_aeo_platforms_section' );
define( 'SLN_AEO_PLATFORM_ITEMS_META', '_sln_aeo_platform_items' );
define( 'SLN_AEO_INDUSTRIES_SECTION_META', '_sln_aeo_industries_section' );
define( 'SLN_AEO_INDUSTRY_ITEMS_META', '_sln_aeo_industry_items' );
define( 'SLN_AEO_RESULTS_SECTION_META', '_sln_aeo_results_section' );
define( 'SLN_AEO_RESULT_ITEMS_META', '_sln_aeo_result_items' );
define( 'SLN_AEO_FAQ_SECTION_META', '_sln_aeo_faq_section' );
define( 'SLN_AEO_FAQ_ITEMS_META', '_sln_aeo_faq_items' );
define( 'SLN_AEO_FINAL_CTA_META', '_sln_aeo_final_cta' );

/**
 * Resolve post ID for AEO data.
 *
 * @param int|null $post_id Optional post ID.
 * @return int
 */
function sln_aeo_resolve_post_id( $post_id = null ) {
	if ( $post_id ) {
		return absint( $post_id );
	}

	$queried = get_queried_object();

	if ( $queried instanceof WP_Post && 'page' === $queried->post_type ) {
		return (int) $queried->ID;
	}

	return (int) get_the_ID();
}

/**
 * Whether a page uses the AEO Services template.
 *
 * @param int|null $post_id Optional post ID.
 * @return bool
 */
function sln_aeo_uses_template( $post_id = null ) {
	$post_id = sln_aeo_resolve_post_id( $post_id );

	if ( ! $post_id ) {
		return function_exists( 'sln_is_aeo_services_page' ) && sln_is_aeo_services_page();
	}

	return SLN_AEO_TEMPLATE === get_page_template_slug( $post_id );
}

/**
 * Read stored meta or defaults when never saved.
 *
 * @param int    $post_id  Post ID.
 * @param string $meta_key Meta key.
 * @param mixed  $default  Default value.
 * @return mixed
 */
function sln_aeo_get_meta_or_default( $post_id, $meta_key, $default ) {
	if ( ! metadata_exists( 'post', $post_id, $meta_key ) ) {
		return $default;
	}

	return get_post_meta( $post_id, $meta_key, true );
}

/**
 * Merge associative section settings with defaults.
 *
 * @param array<string, mixed> $defaults Defaults.
 * @param mixed                $stored   Stored value.
 * @return array<string, mixed>
 */
function sln_aeo_merge_section( $defaults, $stored ) {
	if ( ! is_array( $stored ) ) {
		return $defaults;
	}

	return array_merge( $defaults, $stored );
}

/**
 * Strip tags for plain text output.
 *
 * @param string $content HTML content.
 * @return string
 */
function sln_aeo_plain_text( $content ) {
	return trim( wp_strip_all_tags( (string) $content ) );
}

/**
 * Whether a repeater row is active.
 *
 * @param array<string, mixed> $row Row data.
 * @return bool
 */
function sln_aeo_row_is_active( $row ) {
	return ! array_key_exists( 'active', $row ) || ! empty( $row['active'] );
}

/**
 * Sanitize URL field — pages, anchors, external.
 *
 * @param string $url Raw URL.
 * @return string
 */
function sln_aeo_sanitize_url( $url ) {
	$url = trim( (string) $url );

	if ( '' === $url || '#' === $url ) {
		return $url;
	}

	if ( 0 === strpos( $url, '#' ) ) {
		return sanitize_text_field( $url );
	}

	return esc_url_raw( $url );
}

/**
 * Sanitize an AEO icon key.
 *
 * @param string $icon Raw icon key.
 * @return string
 */
function sln_aeo_sanitize_icon_key( $icon ) {
	$icon    = sanitize_key( (string) $icon );
	$choices = function_exists( 'sln_aeo_get_icon_choices' ) ? sln_aeo_get_icon_choices() : array();

	return isset( $choices[ $icon ] ) ? $icon : '';
}

/**
 * Sanitize evolution stage modifier.
 *
 * @param string $modifier Raw modifier.
 * @return string
 */
function sln_aeo_sanitize_stage_modifier( $modifier ) {
	$modifier = sanitize_key( (string) $modifier );

	return in_array( $modifier, array( 'mid', 'final' ), true ) ? $modifier : '';
}

/**
 * Resolve a CTA URL, falling back when empty.
 *
 * @param string $url      Stored URL.
 * @param string $fallback Fallback URL.
 * @return string
 */
function sln_aeo_resolve_cta_url( $url, $fallback ) {
	$url = trim( (string) $url );

	return '' !== $url ? $url : $fallback;
}

/**
 * Associative section data with defaults.
 *
 * @param int|null             $post_id  Optional post ID.
 * @param string               $meta_key Meta key.
 * @param array<string, mixed> $defaults Defaults.
 * @return array<string, mixed>
 */
function sln_aeo_get_section( $post_id, $meta_key, $defaults ) {
	$post_id = sln_aeo_resolve_post_id( $post_id );

	if ( ! $post_id || ! sln_aeo_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_aeo_merge_section(
		$defaults,
		sln_aeo_get_meta_or_default( $post_id, $meta_key, $defaults )
	);
}

/**
 * Active repeater rows with defaults when empty.
 *
 * @param int|null                   $post_id  Optional post ID.
 * @param string                     $meta_key Meta key.
 * @param array<int, array<string, mixed>> $defaults Defaults.
 * @return array<int, array<string, mixed>>
 */
function sln_aeo_get_repeater_items( $post_id, $meta_key, $defaults ) {
	$post_id = sln_aeo_resolve_post_id( $post_id );

	if ( ! $post_id || ! sln_aeo_uses_template( $post_id ) ) {
		return sln_aeo_filter_active_rows( $defaults );
	}

	if ( ! metadata_exists( 'post', $post_id, $meta_key ) ) {
		return sln_aeo_filter_active_rows( $defaults );
	}

	$stored = get_post_meta( $post_id, $meta_key, true );
	$rows   = is_array( $stored ) ? $stored : array();

	return sln_aeo_filter_active_rows( $rows );
}

/**
 * Filter repeater rows to active items.
 *
 * @param mixed $rows Raw rows.
 * @return array<int, array<string, mixed>>
 */
function sln_aeo_filter_active_rows( $rows ) {
	if ( ! is_array( $rows ) ) {
		return array();
	}

	$active = array();

	foreach ( $rows as $row ) {
		if ( ! is_array( $row ) || ! sln_aeo_row_is_active( $row ) ) {
			continue;
		}

		$active[] = $row;
	}

	return $active;
}

/**
 * Default hero section.
 *
 * @return array<string, string>
 */
function sln_aeo_default_hero() {
	return array(
		'eyebrow'             => __( 'SEO Services / AEO', 'smart-leading-net' ),
		'heading'             => __( 'Answer Engine Optimization', 'smart-leading-net' ),
		'accent'              => __( '(AEO)', 'smart-leading-net' ),
		'heading_suffix'      => __( 'Services', 'smart-leading-net' ),
		'description'         => __( 'Search doesn\'t end in a list of blue links anymore. AI Overviews, ChatGPT, and Perplexity now hand people a finished answer — and only a few brands get quoted in it. AEO is how we get yours in that answer.', 'smart-leading-net' ),
		'primary_cta_text'    => __( 'Get a Free AEO Audit', 'smart-leading-net' ),
		'primary_cta_url'     => sln_aeo_get_contact_url(),
		'secondary_cta_text'  => __( 'See How It Works', 'smart-leading-net' ),
		'secondary_cta_url'   => '#how-it-works',
	);
}

/**
 * Default hero proof items.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_aeo_default_hero_proof() {
	return array(
		array( 'label' => __( 'Google Partner agency', 'smart-leading-net' ), 'active' => true ),
		array( 'label' => __( 'Works alongside your existing SEO', 'smart-leading-net' ), 'active' => true ),
		array( 'label' => __( 'No fabricated guarantees, just process', 'smart-leading-net' ), 'active' => true ),
	);
}

/**
 * Hero section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_aeo_get_hero( $post_id = null ) {
	return sln_aeo_get_section( $post_id, SLN_AEO_HERO_META, sln_aeo_default_hero() );
}

/**
 * Hero proof labels.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, string>
 */
function sln_aeo_get_hero_proof( $post_id = null ) {
	$items  = sln_aeo_get_repeater_items( $post_id, SLN_AEO_HERO_PROOF_META, sln_aeo_default_hero_proof() );
	$labels = array();

	foreach ( $items as $item ) {
		$label = sln_aeo_plain_text( $item['label'] ?? '' );

		if ( '' !== $label ) {
			$labels[] = $label;
		}
	}

	return $labels;
}

/**
 * Default search evolution section.
 *
 * @return array<string, string>
 */
function sln_aeo_default_evolution_section() {
	return array(
		'eyebrow'     => __( 'Why this exists', 'smart-leading-net' ),
		'heading'     => __( 'Search changed shape', 'smart-leading-net' ),
		'description' => __( 'Three ways the same question gets answered today — and where your brand needs to show up in each one.', 'smart-leading-net' ),
	);
}

/**
 * Default search evolution stages.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_aeo_default_evolution_stages() {
	return array(
		array(
			'icon'        => 'list',
			'label'       => __( 'Stage 01', 'smart-leading-net' ),
			'title'       => __( 'Traditional Search', 'smart-leading-net' ),
			'description' => __( 'Ten blue links. The user clicks through, compares pages, and decides for themselves.', 'smart-leading-net' ),
			'modifier'    => '',
			'active'      => true,
		),
		array(
			'icon'        => 'star',
			'label'       => __( 'Stage 02', 'smart-leading-net' ),
			'title'       => __( 'AI-Powered Search', 'smart-leading-net' ),
			'description' => __( 'Google, Bing, and assistants read across many sources and synthesize a summary before any link appears.', 'smart-leading-net' ),
			'modifier'    => 'mid',
			'active'      => true,
		),
		array(
			'icon'        => 'check',
			'label'       => __( 'Stage 03', 'smart-leading-net' ),
			'title'       => __( 'Direct Answers', 'smart-leading-net' ),
			'description' => __( 'The user gets a complete answer with citations — sometimes never visiting a website at all.', 'smart-leading-net' ),
			'modifier'    => 'final',
			'active'      => true,
		),
	);
}

/**
 * Search evolution section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_aeo_get_evolution_section( $post_id = null ) {
	return sln_aeo_get_section( $post_id, SLN_AEO_EVOLUTION_SECTION_META, sln_aeo_default_evolution_section() );
}

/**
 * Search evolution stages.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_aeo_get_evolution_stages( $post_id = null ) {
	return sln_aeo_get_repeater_items( $post_id, SLN_AEO_EVOLUTION_STAGES_META, sln_aeo_default_evolution_stages() );
}

/**
 * Default why AEO matters section.
 *
 * @return array<string, string>
 */
function sln_aeo_default_why_section() {
	return array(
		'eyebrow'     => __( 'Why it matters', 'smart-leading-net' ),
		'heading'     => __( 'Your customers are already asking AI', 'smart-leading-net' ),
		'description' => __( 'The channels below aren\'t emerging anymore — they\'re where a growing share of research and buying decisions already happen.', 'smart-leading-net' ),
	);
}

/**
 * Default why AEO matters cards.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_aeo_default_why_cards() {
	return array(
		array(
			'icon'        => 'star',
			'title'       => __( 'Google AI Overviews', 'smart-leading-net' ),
			'description' => __( 'Now appear above traditional results for a large share of informational queries, often answering the question before a click ever happens.', 'smart-leading-net' ),
			'wide'        => true,
			'active'      => true,
		),
		array(
			'icon'        => 'help',
			'title'       => __( 'ChatGPT', 'smart-leading-net' ),
			'description' => __( 'Used as a search substitute for research and recommendations.', 'smart-leading-net' ),
			'wide'        => false,
			'active'      => true,
		),
		array(
			'icon'        => 'plus',
			'title'       => __( 'Perplexity', 'smart-leading-net' ),
			'description' => __( 'Built entirely around cited, conversational answers.', 'smart-leading-net' ),
			'wide'        => false,
			'active'      => true,
		),
		array(
			'icon'        => 'mic',
			'title'       => __( 'Voice Assistants', 'smart-leading-net' ),
			'description' => __( 'Read back a single answer — there\'s no "page two."', 'smart-leading-net' ),
			'wide'        => false,
			'active'      => true,
		),
		array(
			'icon'        => 'lines',
			'title'       => __( 'Zero-click & conversational search', 'smart-leading-net' ),
			'description' => __( 'More searches now end without a click-through. Brand visibility increasingly depends on being the source an AI system chooses to cite, not just the page it links to.', 'smart-leading-net' ),
			'wide'        => true,
			'active'      => true,
		),
	);
}

/**
 * Why AEO matters section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_aeo_get_why_section( $post_id = null ) {
	return sln_aeo_get_section( $post_id, SLN_AEO_WHY_SECTION_META, sln_aeo_default_why_section() );
}

/**
 * Why AEO matters cards.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_aeo_get_why_cards( $post_id = null ) {
	return sln_aeo_get_repeater_items( $post_id, SLN_AEO_WHY_CARDS_META, sln_aeo_default_why_cards() );
}

/**
 * Default strategy section.
 *
 * @return array<string, string>
 */
function sln_aeo_default_strategy_section() {
	return array(
		'eyebrow'     => __( 'Our approach', 'smart-leading-net' ),
		'heading'     => __( 'The AEO strategy, step by step', 'smart-leading-net' ),
		'description' => __( 'A structured process for earning citations inside AI-generated answers — not a repackaged SEO checklist.', 'smart-leading-net' ),
	);
}

/**
 * Default strategy timeline steps.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_aeo_default_strategy_steps() {
	return array(
		array(
			'number'      => '01',
			'title'       => __( 'Research & Question Mapping', 'smart-leading-net' ),
			'description' => __( 'We identify the real questions your audience asks answer engines, in their own words.', 'smart-leading-net' ),
			'active'      => true,
		),
		array(
			'number'      => '02',
			'title'       => __( 'Search Intent Analysis', 'smart-leading-net' ),
			'description' => __( 'We map each question to what the asker actually needs — a fact, a comparison, or a decision.', 'smart-leading-net' ),
			'active'      => true,
		),
		array(
			'number'      => '03',
			'title'       => __( 'Content Optimization', 'smart-leading-net' ),
			'description' => __( 'Pages are restructured to answer clearly and directly, in a format AI systems can extract.', 'smart-leading-net' ),
			'active'      => true,
		),
		array(
			'number'      => '04',
			'title'       => __( 'Entity Optimization', 'smart-leading-net' ),
			'description' => __( 'We clarify who you are and what you offer so AI systems correctly identify your brand as an entity.', 'smart-leading-net' ),
			'active'      => true,
		),
		array(
			'number'      => '05',
			'title'       => __( 'Structured Data', 'smart-leading-net' ),
			'description' => __( 'Schema markup makes your content machine-readable, not just human-readable.', 'smart-leading-net' ),
			'active'      => true,
		),
		array(
			'number'      => '06',
			'title'       => __( 'Authority Signals', 'smart-leading-net' ),
			'description' => __( 'We build the citations and mentions that answer engines use to judge trustworthiness.', 'smart-leading-net' ),
			'active'      => true,
		),
		array(
			'number'      => '07',
			'title'       => __( 'AI Visibility Tracking', 'smart-leading-net' ),
			'description' => __( 'We monitor where and how your brand appears across AI Overviews, ChatGPT, and Perplexity.', 'smart-leading-net' ),
			'active'      => true,
		),
	);
}

/**
 * Strategy section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_aeo_get_strategy_section( $post_id = null ) {
	return sln_aeo_get_section( $post_id, SLN_AEO_STRATEGY_SECTION_META, sln_aeo_default_strategy_section() );
}

/**
 * Strategy timeline steps.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_aeo_get_strategy_steps( $post_id = null ) {
	return sln_aeo_get_repeater_items( $post_id, SLN_AEO_STRATEGY_STEPS_META, sln_aeo_default_strategy_steps() );
}

/**
 * Default AEO services section.
 *
 * @return array<string, string>
 */
function sln_aeo_default_services_section() {
	return array(
		'eyebrow'     => __( 'What\'s included', 'smart-leading-net' ),
		'heading'     => __( 'AEO services', 'smart-leading-net' ),
		'description' => __( 'Everything needed to move your brand from ranked link to cited answer.', 'smart-leading-net' ),
	);
}

/**
 * Default AEO service cards.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_aeo_default_services() {
	return array(
		array(
			'icon'        => 'star',
			'title'       => __( 'AI Search Optimization', 'smart-leading-net' ),
			'description' => __( 'Optimizing your site to be understood, indexed, and quoted by AI-driven search systems.', 'smart-leading-net' ),
			'active'      => true,
		),
		array(
			'icon'        => 'doc',
			'title'       => __( 'Featured Snippet Optimization', 'smart-leading-net' ),
			'description' => __( 'Structuring content to win the answer box position in traditional search.', 'smart-leading-net' ),
			'active'      => true,
		),
		array(
			'icon'        => 'lines',
			'title'       => __( 'AI Overview Optimization', 'smart-leading-net' ),
			'description' => __( 'Formatting and sourcing content so it\'s a strong candidate for Google\'s AI Overviews.', 'smart-leading-net' ),
			'active'      => true,
		),
		array(
			'icon'        => 'help',
			'title'       => __( 'Q&A Content Strategy', 'smart-leading-net' ),
			'description' => __( 'Building content around the exact questions your customers ask AI assistants.', 'smart-leading-net' ),
			'active'      => true,
		),
		array(
			'icon'        => 'mic',
			'title'       => __( 'Conversational Search Optimization', 'smart-leading-net' ),
			'description' => __( 'Writing for natural, multi-turn, spoken-language queries — not just keywords.', 'smart-leading-net' ),
			'active'      => true,
		),
		array(
			'icon'        => 'graph',
			'title'       => __( 'Entity & Knowledge Graph Optimization', 'smart-leading-net' ),
			'description' => __( 'Strengthening how AI systems identify and connect your brand as a distinct entity.', 'smart-leading-net' ),
			'active'      => true,
		),
		array(
			'icon'        => 'blocks',
			'title'       => __( 'Structured Data Optimization', 'smart-leading-net' ),
			'description' => __( 'Implementing schema so machines can accurately parse your content.', 'smart-leading-net' ),
			'active'      => true,
		),
		array(
			'icon'        => 'lines',
			'title'       => __( 'Semantic Content Optimization', 'smart-leading-net' ),
			'description' => __( 'Writing with the topical depth and clarity language models reward.', 'smart-leading-net' ),
			'active'      => true,
		),
		array(
			'icon'        => 'crosshair',
			'title'       => __( 'Topical Authority Development', 'smart-leading-net' ),
			'description' => __( 'Building content clusters that establish deep expertise on your core subjects.', 'smart-leading-net' ),
			'active'      => true,
		),
		array(
			'icon'        => 'voice',
			'title'       => __( 'Voice Search Optimization', 'smart-leading-net' ),
			'description' => __( 'Targeting the natural-language, question-based phrasing voice search relies on.', 'smart-leading-net' ),
			'active'      => true,
		),
		array(
			'icon'        => 'check',
			'title'       => __( 'Brand Mention & Citation Strategy', 'smart-leading-net' ),
			'description' => __( 'Earning the third-party mentions AI models use as trust signals.', 'smart-leading-net' ),
			'active'      => true,
		),
	);
}

/**
 * AEO services section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_aeo_get_services_section( $post_id = null ) {
	return sln_aeo_get_section( $post_id, SLN_AEO_SERVICES_SECTION_META, sln_aeo_default_services_section() );
}

/**
 * AEO service cards.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_aeo_get_services( $post_id = null ) {
	return sln_aeo_get_repeater_items( $post_id, SLN_AEO_SERVICES_ITEMS_META, sln_aeo_default_services() );
}

/**
 * Default how AEO works section.
 *
 * @return array<string, string>
 */
function sln_aeo_default_how_section() {
	return array(
		'eyebrow'     => __( 'Under the hood', 'smart-leading-net' ),
		'heading'     => __( 'How answer engines choose what to cite', 'smart-leading-net' ),
		'description' => __( 'A simplified look at the path from a user\'s question to your brand appearing in the answer.', 'smart-leading-net' ),
	);
}

/**
 * Default how AEO works flow nodes.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_aeo_default_flow_nodes() {
	return array(
		array(
			'icon'        => 'search',
			'title'       => __( 'User Question', 'smart-leading-net' ),
			'description' => __( 'Someone asks a real question, often in natural language.', 'smart-leading-net' ),
			'highlight'   => true,
			'active'      => true,
		),
		array(
			'icon'        => 'square',
			'title'       => __( 'Search / AI System', 'smart-leading-net' ),
			'description' => __( 'The engine parses intent and searches its index.', 'smart-leading-net' ),
			'highlight'   => false,
			'active'      => true,
		),
		array(
			'icon'        => 'lines',
			'title'       => __( 'Relevant Sources', 'smart-leading-net' ),
			'description' => __( 'It shortlists pages that clearly answer the question.', 'smart-leading-net' ),
			'highlight'   => false,
			'active'      => true,
		),
		array(
			'icon'        => 'nodes',
			'title'       => __( 'Understanding / Entities', 'smart-leading-net' ),
			'description' => __( 'It confirms who you are and whether you\'re a credible source.', 'smart-leading-net' ),
			'highlight'   => false,
			'active'      => true,
		),
		array(
			'icon'        => 'star',
			'title'       => __( 'Generated Answer', 'smart-leading-net' ),
			'description' => __( 'It synthesizes an answer, drawing from top sources.', 'smart-leading-net' ),
			'highlight'   => false,
			'active'      => true,
		),
		array(
			'icon'        => 'check',
			'title'       => __( 'Brand Visibility', 'smart-leading-net' ),
			'description' => __( 'Your brand appears in the answer, with or without a click.', 'smart-leading-net' ),
			'highlight'   => true,
			'active'      => true,
		),
	);
}

/**
 * How AEO works section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_aeo_get_how_section( $post_id = null ) {
	return sln_aeo_get_section( $post_id, SLN_AEO_HOW_SECTION_META, sln_aeo_default_how_section() );
}

/**
 * How AEO works flow nodes.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_aeo_get_flow_nodes( $post_id = null ) {
	return sln_aeo_get_repeater_items( $post_id, SLN_AEO_HOW_STEPS_META, sln_aeo_default_flow_nodes() );
}

/**
 * Default comparison section.
 *
 * @return array<string, string>
 */
function sln_aeo_default_comparison_section() {
	return array(
		'eyebrow'     => __( 'The shift', 'smart-leading-net' ),
		'heading'     => __( 'Traditional SEO vs. AEO', 'smart-leading-net' ),
		'description' => __( 'AEO doesn\'t replace SEO — it builds on it for a search landscape where the "result" is often a written answer.', 'smart-leading-net' ),
		'seo_heading' => __( 'Traditional SEO', 'smart-leading-net' ),
		'aeo_heading' => __( 'Answer Engine Optimization', 'smart-leading-net' ),
	);
}

/**
 * Default comparison rows.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_aeo_default_comparison_rows() {
	return array(
		array(
			'seo'    => __( 'Ranking pages', 'smart-leading-net' ),
			'aeo'    => __( 'Being referenced inside answers', 'smart-leading-net' ),
			'active' => true,
		),
		array(
			'seo'    => __( 'Keyword targeting', 'smart-leading-net' ),
			'aeo'    => __( 'Questions & underlying intent', 'smart-leading-net' ),
			'active' => true,
		),
		array(
			'seo'    => __( 'Blue links', 'smart-leading-net' ),
			'aeo'    => __( 'AI-generated responses', 'smart-leading-net' ),
			'active' => true,
		),
		array(
			'seo'    => __( 'SERPs', 'smart-leading-net' ),
			'aeo'    => __( 'AI search experiences', 'smart-leading-net' ),
			'active' => true,
		),
		array(
			'seo'    => __( 'Click-through rankings', 'smart-leading-net' ),
			'aeo'    => __( 'Brand visibility & citations', 'smart-leading-net' ),
			'active' => true,
		),
	);
}

/**
 * Comparison section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_aeo_get_comparison_section( $post_id = null ) {
	return sln_aeo_get_section( $post_id, SLN_AEO_COMPARISON_SECTION_META, sln_aeo_default_comparison_section() );
}

/**
 * Traditional SEO vs AEO rows.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_aeo_get_comparison_rows( $post_id = null ) {
	return sln_aeo_get_repeater_items( $post_id, SLN_AEO_COMPARISON_ROWS_META, sln_aeo_default_comparison_rows() );
}

/**
 * Default platforms section.
 *
 * @return array<string, string>
 */
function sln_aeo_default_platforms_section() {
	return array(
		'eyebrow' => __( 'Where you\'ll show up', 'smart-leading-net' ),
		'heading' => __( 'Built for every major answer engine', 'smart-leading-net' ),
	);
}

/**
 * Default platform items.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_aeo_default_platform_items() {
	return array(
		array( 'name' => __( 'Google AI Overviews', 'smart-leading-net' ), 'active' => true ),
		array( 'name' => __( 'ChatGPT', 'smart-leading-net' ), 'active' => true ),
		array( 'name' => __( 'Perplexity', 'smart-leading-net' ), 'active' => true ),
		array( 'name' => __( 'Microsoft Copilot', 'smart-leading-net' ), 'active' => true ),
		array( 'name' => __( 'Google Gemini', 'smart-leading-net' ), 'active' => true ),
		array( 'name' => __( 'Voice Search', 'smart-leading-net' ), 'active' => true ),
	);
}

/**
 * Platforms section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_aeo_get_platforms_section( $post_id = null ) {
	return sln_aeo_get_section( $post_id, SLN_AEO_PLATFORMS_SECTION_META, sln_aeo_default_platforms_section() );
}

/**
 * Answer engine platform names.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, string>
 */
function sln_aeo_get_platforms( $post_id = null ) {
	$items = sln_aeo_get_repeater_items( $post_id, SLN_AEO_PLATFORM_ITEMS_META, sln_aeo_default_platform_items() );
	$names = array();

	foreach ( $items as $item ) {
		$name = sln_aeo_plain_text( $item['name'] ?? '' );

		if ( '' !== $name ) {
			$names[] = $name;
		}
	}

	return $names;
}

/**
 * Default industries section.
 *
 * @return array<string, string>
 */
function sln_aeo_default_industries_section() {
	return array(
		'eyebrow' => __( 'Who we work with', 'smart-leading-net' ),
		'heading' => __( 'Industries we serve', 'smart-leading-net' ),
	);
}

/**
 * Default industry items.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_aeo_default_industries() {
	return array(
		array( 'icon' => 'square', 'title' => __( 'SaaS', 'smart-leading-net' ), 'active' => true ),
		array( 'icon' => 'bag', 'title' => __( 'Ecommerce', 'smart-leading-net' ), 'active' => true ),
		array( 'icon' => 'health', 'title' => __( 'Healthcare', 'smart-leading-net' ), 'active' => true ),
		array( 'icon' => 'home', 'title' => __( 'Professional Services', 'smart-leading-net' ), 'active' => true ),
		array( 'icon' => 'pin', 'title' => __( 'Local Businesses', 'smart-leading-net' ), 'active' => true ),
		array( 'icon' => 'card', 'title' => __( 'B2B', 'smart-leading-net' ), 'active' => true ),
		array( 'icon' => 'award', 'title' => __( 'Technology', 'smart-leading-net' ), 'active' => true ),
		array( 'icon' => 'trend', 'title' => __( 'Finance', 'smart-leading-net' ), 'active' => true ),
	);
}

/**
 * Industries section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_aeo_get_industries_section( $post_id = null ) {
	return sln_aeo_get_section( $post_id, SLN_AEO_INDUSTRIES_SECTION_META, sln_aeo_default_industries_section() );
}

/**
 * Industries served.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_aeo_get_industries( $post_id = null ) {
	return sln_aeo_get_repeater_items( $post_id, SLN_AEO_INDUSTRY_ITEMS_META, sln_aeo_default_industries() );
}

/**
 * Default results section.
 *
 * @return array<string, string>
 */
function sln_aeo_default_results_section() {
	return array(
		'eyebrow'     => __( 'What you can expect', 'smart-leading-net' ),
		'heading'     => __( 'What AEO makes possible', 'smart-leading-net' ),
		'description' => __( 'Outcomes are directional, not guaranteed overnight — AEO is an ongoing discipline, not a one-time fix.', 'smart-leading-net' ),
	);
}

/**
 * Default result items.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_aeo_default_results() {
	return array(
		array(
			'title'       => __( 'Increased AI search visibility', 'smart-leading-net' ),
			'description' => __( 'More consistent appearances inside AI Overviews and assistant answers.', 'smart-leading-net' ),
			'active'      => true,
		),
		array(
			'title'       => __( 'More brand mentions', 'smart-leading-net' ),
			'description' => __( 'Wider citation of your brand across AI-generated responses.', 'smart-leading-net' ),
			'active'      => true,
		),
		array(
			'title'       => __( 'Increased qualified traffic', 'smart-leading-net' ),
			'description' => __( 'Visitors arriving already primed with intent, from answer citations.', 'smart-leading-net' ),
			'active'      => true,
		),
		array(
			'title'       => __( 'Greater topical authority', 'smart-leading-net' ),
			'description' => __( 'A stronger footprint across the questions that matter to your industry.', 'smart-leading-net' ),
			'active'      => true,
		),
		array(
			'title'       => __( 'Better conversational visibility', 'smart-leading-net' ),
			'description' => __( 'Stronger presence in multi-turn, natural-language queries.', 'smart-leading-net' ),
			'active'      => true,
		),
		array(
			'title'       => __( 'More zero-click opportunity', 'smart-leading-net' ),
			'description' => __( 'Presence and trust built even when the user never clicks through.', 'smart-leading-net' ),
			'active'      => true,
		),
	);
}

/**
 * Results section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_aeo_get_results_section( $post_id = null ) {
	return sln_aeo_get_section( $post_id, SLN_AEO_RESULTS_SECTION_META, sln_aeo_default_results_section() );
}

/**
 * Expected results.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_aeo_get_results( $post_id = null ) {
	return sln_aeo_get_repeater_items( $post_id, SLN_AEO_RESULT_ITEMS_META, sln_aeo_default_results() );
}

/**
 * Default FAQ section.
 *
 * @return array<string, string>
 */
function sln_aeo_default_faq_section() {
	return array(
		'eyebrow' => __( 'Questions', 'smart-leading-net' ),
		'heading' => __( 'AEO, answered', 'smart-leading-net' ),
	);
}

/**
 * Default FAQ items.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_aeo_default_faq_items() {
	return array(
		array(
			'question' => __( 'What is Answer Engine Optimization?', 'smart-leading-net' ),
			'answer'   => __( 'AEO is the practice of structuring and positioning content so AI-powered search tools — like Google AI Overviews, ChatGPT, and Perplexity — can understand it, trust it, and cite it directly in the answers they generate.', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'question' => __( 'How is AEO different from SEO?', 'smart-leading-net' ),
			'answer'   => __( 'SEO focuses on ranking pages in a list of links. AEO focuses on being the source an AI system references when it writes a direct answer, which requires different content structure, entity clarity, and technical markup.', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'question' => __( 'Does AEO replace SEO?', 'smart-leading-net' ),
			'answer'   => __( 'No. AEO builds on strong technical and on-page SEO foundations — it doesn\'t work as a standalone replacement for them.', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'question' => __( 'How does AEO work with Google AI Overviews?', 'smart-leading-net' ),
			'answer'   => __( 'We structure content to directly and clearly answer specific questions, use supporting schema, and build the authority signals AI Overviews weigh when selecting which sources to summarize and cite.', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'question' => __( 'Can AEO help my brand appear in ChatGPT?', 'smart-leading-net' ),
			'answer'   => __( 'AEO improves the underlying signals — clarity, structure, third-party mentions — that models draw on when trained or when retrieving live information, which can improve how often your brand is referenced.', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'question' => __( 'Does AEO work for ecommerce websites?', 'smart-leading-net' ),
			'answer'   => __( 'Yes. Product Q&A content, comparison content, and structured product data all help ecommerce brands surface in AI-driven shopping and research queries.', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'question' => __( 'How long does AEO take?', 'smart-leading-net' ),
			'answer'   => __( 'Timelines vary by site size, current authority, and competitiveness of your space. As with SEO, AEO is an ongoing process rather than a one-time project.', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'question' => __( 'Why is AEO important for future search?', 'smart-leading-net' ),
			'answer'   => __( 'As more queries are resolved directly inside AI answers, brands that aren\'t structured to be cited risk losing visibility even while still ranking well in traditional search.', 'smart-leading-net' ),
			'active'   => true,
		),
	);
}

/**
 * FAQ section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_aeo_get_faq_section( $post_id = null ) {
	return sln_aeo_get_section( $post_id, SLN_AEO_FAQ_SECTION_META, sln_aeo_default_faq_section() );
}

/**
 * FAQ items.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_aeo_get_faq_items( $post_id = null ) {
	return sln_aeo_get_repeater_items( $post_id, SLN_AEO_FAQ_ITEMS_META, sln_aeo_default_faq_items() );
}

/**
 * Default final CTA section.
 *
 * @return array<string, string>
 */
function sln_aeo_default_final_cta() {
	return array(
		'eyebrow'            => __( 'Ready when you are', 'smart-leading-net' ),
		'heading'            => __( 'Get Your Brand Ready for AI Search', 'smart-leading-net' ),
		'description'        => __( 'Answer engines are already deciding which brands get quoted and which get skipped. The sooner your content is structured for that, the sooner you start showing up in it.', 'smart-leading-net' ),
		'primary_cta_text'   => __( 'Get Started', 'smart-leading-net' ),
		'primary_cta_url'    => sln_aeo_get_contact_url(),
		'secondary_cta_text' => __( 'Explore All SEO Services', 'smart-leading-net' ),
		'secondary_cta_url'  => sln_aeo_get_seo_services_url(),
	);
}

/**
 * Final CTA section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_aeo_get_final_cta( $post_id = null ) {
	return sln_aeo_get_section( $post_id, SLN_AEO_FINAL_CTA_META, sln_aeo_default_final_cta() );
}
