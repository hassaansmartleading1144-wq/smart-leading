<?php
/**
 * SEO Services page — defaults, meta keys, and frontend data helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_SEO_SVC_TEMPLATE', 'seo-page-template.php' );
define( 'SLN_SEO_SVC_HERO_META', '_sln_seo_svc_hero' );
define( 'SLN_SEO_SVC_REALITY_SECTION_META', '_sln_seo_svc_reality_section' );
define( 'SLN_SEO_SVC_REALITY_CARDS_META', '_sln_seo_svc_reality_cards' );
define( 'SLN_SEO_SVC_PROGRAM_SECTION_META', '_sln_seo_svc_program_section' );
define( 'SLN_SEO_SVC_PROGRAM_CARDS_META', '_sln_seo_svc_program_cards' );
define( 'SLN_SEO_SVC_RESULTS_SECTION_META', '_sln_seo_svc_results_section' );
define( 'SLN_SEO_SVC_RESULTS_BLOCKS_META', '_sln_seo_svc_results_blocks' );
define( 'SLN_SEO_SVC_PROCESS_SECTION_META', '_sln_seo_svc_process_section' );
define( 'SLN_SEO_SVC_PROCESS_STEPS_META', '_sln_seo_svc_process_steps' );
define( 'SLN_SEO_SVC_CASE_STUDIES_SECTION_META', '_sln_seo_svc_case_studies_section' );
define( 'SLN_SEO_SVC_CASE_STUDIES_CARDS_META', '_sln_seo_svc_case_studies_cards' );
define( 'SLN_SEO_SVC_PRICING_SECTION_META', '_sln_seo_svc_pricing_section' );
define( 'SLN_SEO_SVC_PRICING_PLANS_META', '_sln_seo_svc_pricing_plans' );
define( 'SLN_SEO_SVC_TESTIMONIALS_SECTION_META', '_sln_seo_svc_testimonials_section' );
define( 'SLN_SEO_SVC_TESTIMONIALS_SUMMARY_META', '_sln_seo_svc_testimonials_summary' );
define( 'SLN_SEO_SVC_TESTIMONIALS_REVIEWS_META', '_sln_seo_svc_testimonials_reviews' );
define( 'SLN_SEO_SVC_CTA_FORM_META', '_sln_seo_svc_cta_form' );
define( 'SLN_SEO_SVC_FAQ_SECTION_META', '_sln_seo_svc_faq_section' );
define( 'SLN_SEO_SVC_FAQ_ITEMS_META', '_sln_seo_svc_faq_items' );

/**
 * Resolve post ID for SEO Services data.
 *
 * @param int|null $post_id Optional post ID.
 * @return int
 */
function sln_seo_services_resolve_post_id( $post_id = null ) {
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
 * Whether a page uses the SEO Services template.
 *
 * @param int|null $post_id Optional post ID.
 * @return bool
 */
function sln_seo_services_uses_template( $post_id = null ) {
	$post_id = sln_seo_services_resolve_post_id( $post_id );

	if ( ! $post_id ) {
		return function_exists( 'sln_is_seo_services_page' ) && sln_is_seo_services_page();
	}

	return SLN_SEO_SVC_TEMPLATE === get_page_template_slug( $post_id );
}

/**
 * Read stored meta or defaults when never saved.
 *
 * @param int    $post_id  Post ID.
 * @param string $meta_key Meta key.
 * @param mixed  $default  Default value.
 * @return mixed
 */
function sln_seo_services_get_meta_or_default( $post_id, $meta_key, $default ) {
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
function sln_seo_services_merge_section( $defaults, $stored ) {
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
function sln_seo_services_plain_text( $content ) {
	return trim( wp_strip_all_tags( (string) $content ) );
}

/**
 * Output sanitized rich text.
 *
 * @param string $content HTML content.
 * @return string
 */
function sln_seo_services_format_content( $content ) {
	if ( function_exists( 'sln_growth_page_format_wysiwyg_content' ) ) {
		return sln_growth_page_format_wysiwyg_content( $content );
	}

	return wp_kses_post( $content );
}

/**
 * Default hero section.
 *
 * @return array<string, mixed>
 */
function sln_seo_services_default_hero() {
	return array(
		'small_heading'          => __( 'SEO Services', 'smart-leading-net' ),
		'main_heading'           => __( 'Own the Searches That', 'smart-leading-net' ),
		'highlighted_text'       => __( 'Drive Your Revenue', 'smart-leading-net' ),
		'description'            => __( 'We build SEO programs around the keywords your buyers actually search — turning organic visibility into qualified traffic, leads, and measurable revenue you can track in your CRM.', 'smart-leading-net' ),
		'primary_button_text'    => __( 'Get My Free SEO Proposal', 'smart-leading-net' ),
		'primary_button_url'     => '#seo-proposal',
		'secondary_button_text'  => __( 'See Client Results', 'smart-leading-net' ),
		'secondary_button_url'   => '#seo-results',
		'hero_image_id'          => 0,
		'trust_badge_text'       => __( 'Google Partner Certified', 'smart-leading-net' ),
		'certified_team_text'    => __( 'avg. client rating', 'smart-leading-net' ),
		'hero_stat_value'        => '$50M+',
		'hero_stat_label'        => __( 'revenue driven', 'smart-leading-net' ),
		'hero_stat_2_value'      => '4.9★',
		'hero_stat_2_label'      => __( 'avg. client rating', 'smart-leading-net' ),
		'google_partner_url'     => 'https://www.google.com/partners/agency?id=7238450490',
		'serp_keyword'           => __( 'best [your service] near me', 'smart-leading-net' ),
		'serp_positions'         => array( 9, 6, 4, 2, 1 ),
		'serp_traffic'           => array( '+12%', '+58%', '+140%', '+255%', '+312%' ),
	);
}

/**
 * Hero section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_seo_services_hero( $post_id = null ) {
	$post_id  = sln_seo_services_resolve_post_id( $post_id );
	$defaults = sln_seo_services_default_hero();

	if ( ! $post_id || ! sln_seo_services_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_seo_services_merge_section(
		$defaults,
		sln_seo_services_get_meta_or_default( $post_id, SLN_SEO_SVC_HERO_META, $defaults )
	);
}

/**
 * Default reality section header + CTA bar.
 *
 * @return array<string, string>
 */
function sln_seo_services_default_reality_section() {
	return array(
		'small_heading'    => __( 'The Problem', 'smart-leading-net' ),
		'main_heading'     => __( 'Your Buyers Are Searching. Are They Finding You?', 'smart-leading-net' ),
		'description'      => __( 'If your site isn\'t showing up for the searches that matter, every click goes to a competitor instead. These are the gaps we see costing businesses revenue every month.', 'smart-leading-net' ),
		'cta_text'         => '<strong>' . esc_html__( 'Every one of these is fixable.', 'smart-leading-net' ) . '</strong> ' . esc_html__( 'We start with a free audit that pinpoints exactly what\'s costing you traffic.', 'smart-leading-net' ),
		'cta_button_text'  => __( 'Get My Free Audit', 'smart-leading-net' ),
		'cta_button_url'   => '#seo-proposal',
	);
}

/**
 * Default reality cards.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_seo_services_default_reality_cards() {
	return array(
		array( 'icon_id' => 0, 'icon_slug' => 'search-minus', 'title' => __( 'Invisible On Page One', 'smart-leading-net' ), 'description' => __( 'You rank below the fold — or nowhere — for the high-intent keywords your customers type when they\'re ready to buy.', 'smart-leading-net' ), 'url' => '', 'active' => true ),
		array( 'icon_id' => 0, 'icon_slug' => 'chart', 'title' => __( 'Traffic That Never Converts', 'smart-leading-net' ), 'description' => __( 'Visitors arrive but don\'t become leads. The wrong keywords, slow pages, and weak intent leave your funnel leaking.', 'smart-leading-net' ), 'url' => '', 'active' => true ),
		array( 'icon_id' => 0, 'icon_slug' => 'technical', 'title' => __( 'Technical Issues Holding You Back', 'smart-leading-net' ), 'description' => __( 'Crawl errors, slow Core Web Vitals, and broken architecture quietly cap your rankings no matter how good your content is.', 'smart-leading-net' ), 'url' => '', 'active' => true ),
		array( 'icon_id' => 0, 'icon_slug' => 'lock', 'title' => __( 'Agencies That Report Vanity Metrics', 'smart-leading-net' ), 'description' => __( 'Impressions and keyword counts look nice in a deck but never tie back to pipeline, revenue, or return on investment.', 'smart-leading-net' ), 'url' => '', 'active' => true ),
		array( 'icon_id' => 0, 'icon_slug' => 'clock', 'title' => __( 'Losing Ground To Competitors', 'smart-leading-net' ), 'description' => __( 'While you wait, competitors publish, earn links, and climb — compounding an advantage that gets harder to overtake.', 'smart-leading-net' ), 'url' => '', 'active' => true ),
		array( 'icon_id' => 0, 'icon_slug' => 'ai', 'title' => __( 'Invisible To AI Search', 'smart-leading-net' ), 'description' => __( 'AI Overviews and answer engines now intercept clicks. If your content isn\'t structured for them, you\'re missing the new front page.', 'smart-leading-net' ), 'url' => '', 'active' => true ),
	);
}

/**
 * Reality section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_get_seo_services_reality_section( $post_id = null ) {
	$post_id  = sln_seo_services_resolve_post_id( $post_id );
	$defaults = sln_seo_services_default_reality_section();

	if ( ! $post_id || ! sln_seo_services_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_seo_services_merge_section(
		$defaults,
		sln_seo_services_get_meta_or_default( $post_id, SLN_SEO_SVC_REALITY_SECTION_META, $defaults )
	);
}

/**
 * Active reality cards.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_seo_services_reality_cards( $post_id = null ) {
	$post_id  = sln_seo_services_resolve_post_id( $post_id );
	$defaults = sln_seo_services_default_reality_cards();

	if ( ! $post_id || ! sln_seo_services_uses_template( $post_id ) ) {
		return array_values( array_filter( $defaults, 'sln_seo_services_row_is_active' ) );
	}

	$stored = sln_seo_services_get_meta_or_default( $post_id, SLN_SEO_SVC_REALITY_CARDS_META, $defaults );
	$cards  = is_array( $stored ) && ! empty( $stored ) ? $stored : $defaults;
	$active = array();

	foreach ( $cards as $card ) {
		if ( ! is_array( $card ) || ! sln_seo_services_row_is_active( $card ) ) {
			continue;
		}

		$active[] = $card;
	}

	return $active;
}

/**
 * Default program section.
 *
 * @return array<string, string>
 */
function sln_seo_services_default_program_section() {
	return array(
		'small_heading' => __( 'What We Do', 'smart-leading-net' ),
		'main_heading'  => __( 'A Complete SEO Program, Built To Perform', 'smart-leading-net' ),
		'description'   => __( 'Every engagement combines technical foundations, content, and authority-building into one strategy aimed at the same outcome — measurable revenue growth.', 'smart-leading-net' ),
	);
}

/**
 * Default program cards.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_seo_services_default_program_cards() {
	return array(
		array( 'icon_id' => 0, 'title' => __( 'Technical SEO', 'smart-leading-net' ), 'description' => __( 'We clear the path for search engines and AI crawlers to find, render, and rank every page.', 'smart-leading-net' ), 'bullets' => array( __( 'Core Web Vitals & speed', 'smart-leading-net' ), __( 'Crawl, index & sitemap fixes', 'smart-leading-net' ), __( 'Schema & structured data', 'smart-leading-net' ) ), 'link_text' => '', 'link_url' => '', 'active' => true ),
		array( 'icon_id' => 0, 'title' => __( 'Keyword & Intent Strategy', 'smart-leading-net' ), 'description' => __( 'We map the searches your buyers use at every stage and prioritise the ones that drive pipeline.', 'smart-leading-net' ), 'bullets' => array( __( 'High-intent keyword research', 'smart-leading-net' ), __( 'Competitor gap analysis', 'smart-leading-net' ), __( 'Topic clusters & mapping', 'smart-leading-net' ) ), 'link_text' => '', 'link_url' => '', 'active' => true ),
		array( 'icon_id' => 0, 'title' => __( 'Content & On-Page SEO', 'smart-leading-net' ), 'description' => __( 'We create and optimise content that ranks, reads well, and earns the trust signals Google rewards.', 'smart-leading-net' ), 'bullets' => array( __( 'Optimised landing pages', 'smart-leading-net' ), __( 'Blog & pillar content', 'smart-leading-net' ), __( 'Title, meta & internal links', 'smart-leading-net' ) ), 'link_text' => '', 'link_url' => '', 'active' => true ),
		array( 'icon_id' => 0, 'title' => __( 'Authority & Link Building', 'smart-leading-net' ), 'description' => __( 'We earn relevant, high-quality backlinks that build the domain authority rankings depend on.', 'smart-leading-net' ), 'bullets' => array( __( 'Digital PR & outreach', 'smart-leading-net' ), __( 'Editorial backlinks', 'smart-leading-net' ), __( 'Toxic link cleanup', 'smart-leading-net' ) ), 'link_text' => '', 'link_url' => '', 'active' => true ),
		array( 'icon_id' => 0, 'title' => __( 'Local SEO', 'smart-leading-net' ), 'description' => __( 'We help you dominate the map pack and "near me" searches in every market you serve.', 'smart-leading-net' ), 'bullets' => array( __( 'Google Business Profile', 'smart-leading-net' ), __( 'Local landing pages', 'smart-leading-net' ), __( 'Citations & reviews', 'smart-leading-net' ) ), 'link_text' => '', 'link_url' => '', 'active' => true ),
		array( 'icon_id' => 0, 'title' => __( 'AI & Answer Engine SEO', 'smart-leading-net' ), 'description' => __( 'We structure your content to win citations in AI Overviews, ChatGPT, and answer engines.', 'smart-leading-net' ), 'bullets' => array( __( 'AI Overview optimisation', 'smart-leading-net' ), __( 'Entity & E-E-A-T signals', 'smart-leading-net' ), __( 'Answer-ready formatting', 'smart-leading-net' ) ), 'link_text' => '', 'link_url' => '', 'active' => true ),
	);
}

/**
 * Program section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_get_seo_services_program_section( $post_id = null ) {
	$post_id  = sln_seo_services_resolve_post_id( $post_id );
	$defaults = sln_seo_services_default_program_section();

	if ( ! $post_id || ! sln_seo_services_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_seo_services_merge_section(
		$defaults,
		sln_seo_services_get_meta_or_default( $post_id, SLN_SEO_SVC_PROGRAM_SECTION_META, $defaults )
	);
}

/**
 * Active program cards.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_seo_services_program_cards( $post_id = null ) {
	$post_id  = sln_seo_services_resolve_post_id( $post_id );
	$defaults = sln_seo_services_default_program_cards();

	if ( ! $post_id || ! sln_seo_services_uses_template( $post_id ) ) {
		return array_values( array_filter( $defaults, 'sln_seo_services_row_is_active' ) );
	}

	$stored = sln_seo_services_get_meta_or_default( $post_id, SLN_SEO_SVC_PROGRAM_CARDS_META, $defaults );
	$cards  = is_array( $stored ) && ! empty( $stored ) ? $stored : $defaults;
	$active = array();

	foreach ( $cards as $card ) {
		if ( ! is_array( $card ) || ! sln_seo_services_row_is_active( $card ) ) {
			continue;
		}

		$active[] = $card;
	}

	return $active;
}

/**
 * Default results section.
 *
 * @return array<string, string>
 */
function sln_seo_services_default_results_section() {
	return array(
		'small_heading'    => __( 'Why Smart Leading', 'smart-leading-net' ),
		'main_heading'     => __( 'SEO That\'s Measured in Revenue, Not Rankings Alone', 'smart-leading-net' ),
		'highlighted_word' => '',
		'description'      => __( 'Plenty of agencies can move a keyword. We tie every action back to traffic, leads, and revenue you can see in your numbers — and we report on it transparently every month.', 'smart-leading-net' ),
	);
}

/**
 * Default results blocks.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_seo_services_default_results_blocks() {
	return array(
		array( 'number' => '01', 'label' => __( 'Google Partner Certified', 'smart-leading-net' ), 'description' => __( 'Recognised expertise and direct access to best-practice standards — not guesswork or outdated tactics.', 'smart-leading-net' ), 'icon_id' => 0, 'url' => '', 'active' => true ),
		array( 'number' => '02', 'label' => __( 'Revenue-First Strategy', 'smart-leading-net' ), 'description' => __( 'We prioritise the keywords and pages that actually move pipeline, tracked through your CRM and analytics.', 'smart-leading-net' ), 'icon_id' => 0, 'url' => '', 'active' => true ),
		array( 'number' => '03', 'label' => __( 'Transparent Reporting', 'smart-leading-net' ), 'description' => __( 'Clear monthly reports show what we did, what it earned, and what\'s next. No vanity metrics, no jargon.', 'smart-leading-net' ), 'icon_id' => 0, 'url' => '', 'active' => true ),
		array( 'number' => '04', 'label' => __( 'A Dedicated Strategist', 'smart-leading-net' ), 'description' => __( 'One senior point of contact who knows your business — not a rotating cast or a ticket queue.', 'smart-leading-net' ), 'icon_id' => 0, 'url' => '', 'active' => true ),
		array( 'number' => '05', 'label' => __( 'Proven Across Industries', 'smart-leading-net' ), 'description' => __( 'From manufacturing to ecommerce, dental to home services — playbooks tailored to how each market searches.', 'smart-leading-net' ), 'icon_id' => 0, 'url' => '', 'active' => true ),
		array( 'number' => '06', 'label' => __( 'Try Before You Commit', 'smart-leading-net' ), 'description' => __( 'Start with a 7-day free trial of our strategy and process before signing on to a full SEO program.', 'smart-leading-net' ), 'icon_id' => 0, 'url' => '', 'active' => true ),
	);
}

/**
 * Results section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_get_seo_services_results_section( $post_id = null ) {
	$post_id  = sln_seo_services_resolve_post_id( $post_id );
	$defaults = sln_seo_services_default_results_section();

	if ( ! $post_id || ! sln_seo_services_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_seo_services_merge_section(
		$defaults,
		sln_seo_services_get_meta_or_default( $post_id, SLN_SEO_SVC_RESULTS_SECTION_META, $defaults )
	);
}

/**
 * Active results blocks.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_seo_services_results_blocks( $post_id = null ) {
	$post_id  = sln_seo_services_resolve_post_id( $post_id );
	$defaults = sln_seo_services_default_results_blocks();

	if ( ! $post_id || ! sln_seo_services_uses_template( $post_id ) ) {
		return array_values( array_filter( $defaults, 'sln_seo_services_row_is_active' ) );
	}

	$stored = sln_seo_services_get_meta_or_default( $post_id, SLN_SEO_SVC_RESULTS_BLOCKS_META, $defaults );
	$rows   = is_array( $stored ) && ! empty( $stored ) ? $stored : $defaults;
	$active = array();

	foreach ( $rows as $row ) {
		if ( ! is_array( $row ) || ! sln_seo_services_row_is_active( $row ) ) {
			continue;
		}

		$active[] = $row;
	}

	return $active;
}

/**
 * Default process section.
 *
 * @return array<string, string>
 */
function sln_seo_services_default_process_section() {
	return array(
		'small_heading' => __( 'How It Works', 'smart-leading-net' ),
		'main_heading'  => __( 'From Discovery to Durable Growth', 'smart-leading-net' ),
		'description'   => __( 'A clear, proven path that turns an SEO audit into rankings, traffic, and revenue you can rely on month over month.', 'smart-leading-net' ),
	);
}

/**
 * Default process steps.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_seo_services_default_process_steps() {
	return array(
		array( 'step_number' => '1', 'icon_id' => 0, 'title' => __( 'Audit & Discovery', 'smart-leading-net' ), 'description' => __( 'We dig into your site, rankings, competitors, and goals to find the highest-impact opportunities.', 'smart-leading-net' ), 'url' => '' ),
		array( 'step_number' => '2', 'icon_id' => 0, 'title' => __( 'Strategy & Roadmap', 'smart-leading-net' ), 'description' => __( 'We build a prioritised 90-day plan tied to the keywords and pages that drive revenue.', 'smart-leading-net' ), 'url' => '' ),
		array( 'step_number' => '3', 'icon_id' => 0, 'title' => __( 'Optimise & Build', 'smart-leading-net' ), 'description' => __( 'We fix technical issues, optimise pages, publish content, and earn authority links.', 'smart-leading-net' ), 'url' => '' ),
		array( 'step_number' => '4', 'icon_id' => 0, 'title' => __( 'Measure & Report', 'smart-leading-net' ), 'description' => __( 'We track rankings, traffic, and conversions, reporting results against your goals monthly.', 'smart-leading-net' ), 'url' => '' ),
		array( 'step_number' => '5', 'icon_id' => 0, 'title' => __( 'Refine & Scale', 'smart-leading-net' ), 'description' => __( 'We double down on what\'s working and adapt to every algorithm and market shift.', 'smart-leading-net' ), 'url' => '' ),
	);
}

/**
 * Process section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_get_seo_services_process_section( $post_id = null ) {
	$post_id  = sln_seo_services_resolve_post_id( $post_id );
	$defaults = sln_seo_services_default_process_section();

	if ( ! $post_id || ! sln_seo_services_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_seo_services_merge_section(
		$defaults,
		sln_seo_services_get_meta_or_default( $post_id, SLN_SEO_SVC_PROCESS_SECTION_META, $defaults )
	);
}

/**
 * Process steps.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_seo_services_process_steps( $post_id = null ) {
	$post_id  = sln_seo_services_resolve_post_id( $post_id );
	$defaults = sln_seo_services_default_process_steps();

	if ( ! $post_id || ! sln_seo_services_uses_template( $post_id ) ) {
		return $defaults;
	}

	$stored = sln_seo_services_get_meta_or_default( $post_id, SLN_SEO_SVC_PROCESS_STEPS_META, $defaults );

	return is_array( $stored ) && ! empty( $stored ) ? $stored : $defaults;
}

/**
 * Default case studies section.
 *
 * @return array<string, string>
 */
function sln_seo_services_default_case_studies_section() {
	return array(
		'small_heading'        => __( 'Case Studies', 'smart-leading-net' ),
		'main_heading'         => __( 'Proven Results For', 'smart-leading-net' ),
		'highlighted_word'     => __( 'Growth-Focused Businesses', 'smart-leading-net' ),
		'description'          => __( 'See how we help brands turn strategy, paid media, websites, and optimisation into measurable growth through smart execution and data-backed decisions.', 'smart-leading-net' ),
		'more_case_studies_text' => '',
		'more_case_studies_url'  => '',
	);
}

/**
 * Default case study cards.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_seo_services_default_case_studies_cards() {
	$icon_files = function_exists( 'sln_get_growth_page_case_studies_default_icon_files' )
		? sln_get_growth_page_case_studies_default_icon_files()
		: array( 'surface1.svg', 'ecommerce.svg', 'hospitality.svg' );

	return array(
		array( 'title' => __( 'Case Study: Manufacturing', 'smart-leading-net' ), 'icon_id' => 0, 'icon_fallback' => $icon_files[0], 'metric' => '30%', 'metric_description' => __( 'ROI Increase From Paid Search', 'smart-leading-net' ), 'graph_id' => 0, 'graph_fallback' => 'paid-chart.svg', 'footer_text' => '', 'card_url' => '', 'card_color' => '#12b8b8', 'active' => true ),
		array( 'title' => __( 'Case Study: ECommerce', 'smart-leading-net' ), 'icon_id' => 0, 'icon_fallback' => $icon_files[1], 'metric' => '4.2X', 'metric_description' => __( 'Higher Return On Ad Spend', 'smart-leading-net' ), 'graph_id' => 0, 'graph_fallback' => 'ad-spend-chart.svg', 'footer_text' => __( 'Data-Backed Strategies. Real Business Impact.', 'smart-leading-net' ), 'card_url' => '', 'card_color' => '#f36b32', 'active' => true ),
		array( 'title' => __( 'Case Study: Hospitality', 'smart-leading-net' ), 'icon_id' => 0, 'icon_fallback' => $icon_files[2], 'metric' => '260%', 'metric_description' => __( 'Increase In Organic Revenue', 'smart-leading-net' ), 'graph_id' => 0, 'graph_fallback' => 'organic-chart.svg', 'footer_text' => __( 'More Traffic, Higher Rankings, More Revenue', 'smart-leading-net' ), 'card_url' => '', 'card_color' => '#1f4e9e', 'active' => true ),
	);
}

/**
 * Case studies section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_get_seo_services_case_studies_section( $post_id = null ) {
	$post_id  = sln_seo_services_resolve_post_id( $post_id );
	$defaults = sln_seo_services_default_case_studies_section();

	if ( ! $post_id || ! sln_seo_services_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_seo_services_merge_section(
		$defaults,
		sln_seo_services_get_meta_or_default( $post_id, SLN_SEO_SVC_CASE_STUDIES_SECTION_META, $defaults )
	);
}

/**
 * Case studies data for growth template.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_seo_services_case_studies_data( $post_id = null ) {
	$section = sln_get_seo_services_case_studies_section( $post_id );
	$post_id = sln_seo_services_resolve_post_id( $post_id );
	$defaults = sln_seo_services_default_case_studies_cards();
	$stored   = sln_seo_services_get_meta_or_default( $post_id, SLN_SEO_SVC_CASE_STUDIES_CARDS_META, $defaults );
	$cards    = is_array( $stored ) && ! empty( $stored ) ? $stored : $defaults;
	$active   = array();
	$index    = 0;
	$chart_files = function_exists( 'sln_get_growth_page_case_studies_chart_files' )
		? sln_get_growth_page_case_studies_chart_files()
		: array( 'paid-chart.svg', 'ad-spend-chart.svg', 'organic-chart.svg' );

	foreach ( $cards as $card ) {
		if ( ! is_array( $card ) || ! sln_seo_services_row_is_active( $card ) ) {
			continue;
		}

		$tags = array();
		if ( ! empty( $card['footer_text'] ) ) {
			$tags = array_map( 'trim', explode( ',', (string) $card['footer_text'] ) );
			$tags = array_filter( $tags );
		}

		$active[] = array(
			'title'              => (string) ( $card['title'] ?? '' ),
			'metric_value'       => (string) ( $card['metric'] ?? '' ),
			'metric_description' => (string) ( $card['metric_description'] ?? '' ),
			'icon_id'            => absint( $card['icon_id'] ?? 0 ),
			'icon_fallback'      => (string) ( $card['icon_fallback'] ?? '' ),
			'theme_color'        => (string) ( $card['card_color'] ?? '#1f4e9e' ),
			'tags'               => $tags,
			'chart_file'         => ! empty( $card['graph_fallback'] ) ? (string) $card['graph_fallback'] : $chart_files[ $index % count( $chart_files ) ],
			'chart_id'           => absint( $card['graph_id'] ?? 0 ),
			'card_url'           => (string) ( $card['card_url'] ?? '' ),
		);

		++$index;
	}

	return array(
		'label'          => $section['small_heading'],
		'main_heading'   => $section['main_heading'],
		'highlight_word' => $section['highlighted_word'],
		'description'    => $section['description'],
		'more_link_text' => $section['more_case_studies_text'],
		'more_link_url'  => $section['more_case_studies_url'],
		'cards'          => $active,
	);
}

/**
 * Default pricing section.
 *
 * @return array<string, string>
 */
function sln_seo_services_default_pricing_section() {
	return array(
		'small_heading'    => __( 'Pricing Plans', 'smart-leading-net' ),
		'main_heading'     => __( 'Transparent', 'smart-leading-net' ),
		'highlighted_word' => __( 'Revenue Growth Pricing', 'smart-leading-net' ),
		'description'      => __( 'Tailored to your business goals. Whether you\'re just starting or ready to scale aggressively, we have a plan designed to turn your marketing spend into predictable revenue.', 'smart-leading-net' ),
	);
}

/**
 * Default pricing plans.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_seo_services_default_pricing_plans() {
	return array(
		array( 'plan_name' => __( 'BASIC', 'smart-leading-net' ), 'price' => '$999', 'price_suffix' => __( '/ month', 'smart-leading-net' ), 'description' => __( 'Essential paid media management for businesses ready to start generating consistent revenue from search and social.', 'smart-leading-net' ), 'features' => array( __( 'Google Ads campaign management', 'smart-leading-net' ), __( 'Monthly performance reporting', 'smart-leading-net' ), __( 'Landing page recommendations', 'smart-leading-net' ), __( 'Email support', 'smart-leading-net' ) ), 'button_text' => __( 'Get Started', 'smart-leading-net' ), 'button_url' => '#seo-proposal', 'is_popular' => false, 'active' => true ),
		array( 'plan_name' => __( 'GROWTH', 'smart-leading-net' ), 'price' => '$2,499', 'price_suffix' => __( '/ month', 'smart-leading-net' ), 'description' => __( 'Full-funnel growth for brands scaling paid acquisition with optimization across channels.', 'smart-leading-net' ), 'features' => array( __( 'Everything in Basic', 'smart-leading-net' ), __( 'Meta Ads management', 'smart-leading-net' ), __( 'Conversion rate optimization', 'smart-leading-net' ), __( 'Bi-weekly strategy calls', 'smart-leading-net' ), __( 'Dedicated account manager', 'smart-leading-net' ) ), 'button_text' => __( 'Get Started', 'smart-leading-net' ), 'button_url' => '#seo-proposal', 'is_popular' => true, 'active' => true ),
		array( 'plan_name' => __( 'PRO', 'smart-leading-net' ), 'price' => __( 'Custom pricing', 'smart-leading-net' ), 'price_suffix' => '', 'description' => __( 'Enterprise-level revenue growth partnerships with custom strategy, integrations, and dedicated support.', 'smart-leading-net' ), 'features' => array( __( 'Full-funnel revenue strategy', 'smart-leading-net' ), __( 'Multi-channel campaign management', 'smart-leading-net' ), __( 'Custom analytics & attribution', 'smart-leading-net' ), __( 'Priority support & consulting', 'smart-leading-net' ), __( 'Custom CRM & platform integrations', 'smart-leading-net' ) ), 'button_text' => __( 'Get a Custom Quote', 'smart-leading-net' ), 'button_url' => '#seo-proposal', 'is_popular' => false, 'active' => true ),
	);
}

/**
 * Pricing section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_get_seo_services_pricing_section( $post_id = null ) {
	$post_id  = sln_seo_services_resolve_post_id( $post_id );
	$defaults = sln_seo_services_default_pricing_section();

	if ( ! $post_id || ! sln_seo_services_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_seo_services_merge_section(
		$defaults,
		sln_seo_services_get_meta_or_default( $post_id, SLN_SEO_SVC_PRICING_SECTION_META, $defaults )
	);
}

/**
 * Pricing data for growth template.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_seo_services_pricing_data( $post_id = null ) {
	$section  = sln_get_seo_services_pricing_section( $post_id );
	$post_id  = sln_seo_services_resolve_post_id( $post_id );
	$defaults = sln_seo_services_default_pricing_plans();
	$stored   = sln_seo_services_get_meta_or_default( $post_id, SLN_SEO_SVC_PRICING_PLANS_META, $defaults );
	$plans    = is_array( $stored ) && ! empty( $stored ) ? $stored : $defaults;
	$active   = array();

	foreach ( $plans as $plan ) {
		if ( ! is_array( $plan ) || ! sln_seo_services_row_is_active( $plan ) ) {
			continue;
		}

		$active[] = function_exists( 'sln_sanitize_growth_page_price_plan_card' )
			? sln_sanitize_growth_page_price_plan_card(
				array(
					'plan_name'    => $plan['plan_name'] ?? '',
					'price'        => $plan['price'] ?? '',
					'price_suffix' => $plan['price_suffix'] ?? '',
					'description'  => $plan['description'] ?? '',
					'features'     => $plan['features'] ?? array(),
					'button_text'  => $plan['button_text'] ?? '',
					'button_url'   => $plan['button_url'] ?? '',
					'is_popular'   => ! empty( $plan['is_popular'] ),
					'badge_text'   => __( 'MOST POPULAR', 'smart-leading-net' ),
					'active'       => true,
				)
			)
			: $plan;
	}

	return array(
		'label'          => $section['small_heading'],
		'heading_lead'   => $section['main_heading'],
		'highlight_word' => $section['highlighted_word'],
		'heading_trail'  => '',
		'description'    => $section['description'],
		'cards'          => $active,
	);
}

/**
 * Default testimonials section.
 *
 * @return array<string, string>
 */
function sln_seo_services_default_testimonials_section() {
	return array(
		'small_heading'    => __( 'Testimonials', 'smart-leading-net' ),
		'main_heading'     => __( 'Trusted Partnerships Built On', 'smart-leading-net' ),
		'highlighted_word' => __( 'Results', 'smart-leading-net' ),
		'description'      => '',
	);
}

/**
 * Default testimonials summary.
 *
 * @return array<string, string>
 */
function sln_seo_services_default_testimonials_summary() {
	return array(
		'review_count'       => '28K+',
		'average_rating'     => '4.9★',
		'websites_built'     => '200+',
		'revenue_generated'  => '$50M+',
		'review_title'       => __( '28k+ Client Reviews', 'smart-leading-net' ),
		'star_rating'        => '5',
		'verified_text'      => __( 'Verified', 'smart-leading-net' ),
	);
}

/**
 * Default testimonial reviews.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_seo_services_default_testimonials_reviews() {
	return array(
		array( 'rating' => 5, 'testimonial' => __( 'Highly cooperative and honest with their work. They developed my business website and I am super happy with them. On time delivery and 24hrs support. They also manage our social media — they know what they\'re doing.', 'smart-leading-net' ), 'client_name' => __( 'Sarah Mitchell', 'smart-leading-net' ), 'client_position' => __( 'Growth Labs', 'smart-leading-net' ), 'client_initials' => 'SM', 'client_image_id' => 0, 'active' => true ),
		array( 'rating' => 5, 'testimonial' => __( 'Before working with Smart Leading, our campaigns lacked direction. They helped us build a clear strategy, improve conversions, and understand exactly where our growth was coming from.', 'smart-leading-net' ), 'client_name' => __( 'James Carter', 'smart-leading-net' ), 'client_position' => __( 'Managing Director', 'smart-leading-net' ), 'client_initials' => 'JC', 'client_image_id' => 0, 'active' => true ),
	);
}

/**
 * Testimonials section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_get_seo_services_testimonials_section( $post_id = null ) {
	$post_id  = sln_seo_services_resolve_post_id( $post_id );
	$defaults = sln_seo_services_default_testimonials_section();

	if ( ! $post_id || ! sln_seo_services_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_seo_services_merge_section(
		$defaults,
		sln_seo_services_get_meta_or_default( $post_id, SLN_SEO_SVC_TESTIMONIALS_SECTION_META, $defaults )
	);
}

/**
 * Testimonials summary block.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_get_seo_services_testimonials_summary( $post_id = null ) {
	$post_id  = sln_seo_services_resolve_post_id( $post_id );
	$defaults = sln_seo_services_default_testimonials_summary();

	if ( ! $post_id || ! sln_seo_services_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_seo_services_merge_section(
		$defaults,
		sln_seo_services_get_meta_or_default( $post_id, SLN_SEO_SVC_TESTIMONIALS_SUMMARY_META, $defaults )
	);
}

/**
 * Testimonials data for growth template.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_seo_services_testimonials_data( $post_id = null ) {
	$section = sln_get_seo_services_testimonials_section( $post_id );
	$summary = sln_get_seo_services_testimonials_summary( $post_id );
	$post_id = sln_seo_services_resolve_post_id( $post_id );
	$defaults = sln_seo_services_default_testimonials_reviews();
	$stored   = sln_seo_services_get_meta_or_default( $post_id, SLN_SEO_SVC_TESTIMONIALS_REVIEWS_META, $defaults );
	$reviews  = is_array( $stored ) && ! empty( $stored ) ? $stored : $defaults;
	$icons    = function_exists( 'sln_get_growth_page_testimonials_default_stat_icons' )
		? sln_get_growth_page_testimonials_default_stat_icons()
		: array( 'reviews.svg', 'rating.svg', 'website-build.svg', 'revenue.svg' );

	$stats = array(
		array( 'icon_fallback' => $icons[0], 'counter_value' => preg_replace( '/\D/', '', $summary['review_count'] ) ?: '28', 'counter_suffix' => 'K+', 'label' => __( 'Client Reviews', 'smart-leading-net' ), 'display_number' => $summary['review_count'] ),
		array( 'icon_fallback' => $icons[1], 'counter_value' => preg_replace( '/[^0-9.]/', '', $summary['average_rating'] ) ?: '4.9', 'counter_suffix' => '★', 'label' => __( 'Average Rating', 'smart-leading-net' ), 'display_number' => $summary['average_rating'] ),
		array( 'icon_fallback' => $icons[2], 'counter_value' => preg_replace( '/\D/', '', $summary['websites_built'] ) ?: '200', 'counter_suffix' => '+', 'label' => __( 'Website Build', 'smart-leading-net' ), 'display_number' => $summary['websites_built'] ),
		array( 'icon_fallback' => $icons[3], 'counter_value' => preg_replace( '/[^0-9.]/', '', $summary['revenue_generated'] ) ?: '50', 'counter_prefix' => '$', 'counter_suffix' => 'M+', 'label' => __( 'Revenue Generated', 'smart-leading-net' ), 'display_number' => $summary['revenue_generated'] ),
	);

	$active_reviews = array();
	foreach ( $reviews as $review ) {
		if ( ! is_array( $review ) || ! sln_seo_services_row_is_active( $review ) ) {
			continue;
		}

		$active_reviews[] = array(
			'rating'          => absint( $review['rating'] ?? 5 ),
			'text'            => (string) ( $review['testimonial'] ?? '' ),
			'author_initials' => (string) ( $review['client_initials'] ?? '' ),
			'author_name'     => (string) ( $review['client_name'] ?? '' ),
			'author_title'    => (string) ( $review['client_position'] ?? '' ),
			'author_image_id' => absint( $review['client_image_id'] ?? 0 ),
			'active'          => true,
		);
	}

	$uploads_url = trailingslashit( content_url( '/uploads/' . ( defined( 'SLN_GP_TESTIMONIALS_UPLOADS' ) ? SLN_GP_TESTIMONIALS_UPLOADS : '2026/05/' ) ) );
	$bg_file     = WP_CONTENT_DIR . '/uploads/' . ( defined( 'SLN_GP_TESTIMONIALS_UPLOADS' ) ? SLN_GP_TESTIMONIALS_UPLOADS : '2026/05/' ) . 'testimonials-bg.webp';
	$background_url = file_exists( $bg_file ) ? $uploads_url . rawurlencode( 'testimonials-bg.webp' ) : $uploads_url . 'case-studies-bg.webp';

	return array(
		'label'          => $section['small_heading'],
		'heading_lead'   => $section['main_heading'],
		'highlight_word' => $section['highlighted_word'],
		'description'    => $section['description'],
		'background_url' => $background_url,
		'stats'          => $stats,
		'summary'        => array(
			'review_title'  => $summary['review_title'],
			'star_rating'   => (float) $summary['star_rating'],
			'verified_text' => $summary['verified_text'],
		),
		'reviews'        => $active_reviews,
	);
}

/**
 * Default CTA form section.
 *
 * @return array<string, string>
 */
function sln_seo_services_default_cta_form() {
	return array(
		'small_heading'       => __( 'Get Started', 'smart-leading-net' ),
		'main_heading'        => __( 'Your Revenue Growth Starts With One Search', 'smart-leading-net' ),
		'description'         => __( 'Tell us about your business and we\'ll build a custom SEO proposal showing exactly how we\'ll grow your traffic, leads, and revenue.', 'smart-leading-net' ),
		'form_heading'        => __( 'Get Your Free SEO Proposal', 'smart-leading-net' ),
		'name_placeholder'    => __( 'Jane Doe', 'smart-leading-net' ),
		'email_placeholder'   => __( 'jane@company.com', 'smart-leading-net' ),
		'phone_placeholder'   => '',
		'website_placeholder' => __( 'https://yourbusiness.com', 'smart-leading-net' ),
		'button_text'         => __( 'Get My Free Proposal', 'smart-leading-net' ),
		'thank_you_page_url'  => '',
	);
}

/**
 * CTA form section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_get_seo_services_cta_form( $post_id = null ) {
	$post_id  = sln_seo_services_resolve_post_id( $post_id );
	$defaults = sln_seo_services_default_cta_form();

	if ( ! $post_id || ! sln_seo_services_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_seo_services_merge_section(
		$defaults,
		sln_seo_services_get_meta_or_default( $post_id, SLN_SEO_SVC_CTA_FORM_META, $defaults )
	);
}

/**
 * Default FAQ section.
 *
 * @return array<string, string>
 */
function sln_seo_services_default_faq_section() {
	return array(
		'small_heading'    => __( 'FAQ', 'smart-leading-net' ),
		'main_heading'     => __( 'SEO Questions, Answered', 'smart-leading-net' ),
		'description'      => __( 'Still deciding? Here are the things businesses ask us most before getting started.', 'smart-leading-net' ),
		'cta_button_text'  => __( 'Ask Us Anything', 'smart-leading-net' ),
		'cta_button_url'   => '#seo-proposal',
	);
}

/**
 * Default FAQ items.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_seo_services_default_faq_items() {
	return array(
		array( 'question' => __( 'How long until I see SEO results?', 'smart-leading-net' ), 'answer' => __( 'Most clients see early movement in 60–90 days, with meaningful traffic and lead growth between months 4 and 6. SEO compounds — the gains build and become more durable the longer we work together. Your roadmap sets clear expectations for each phase.', 'smart-leading-net' ), 'active' => true ),
		array( 'question' => __( 'How do you measure success?', 'smart-leading-net' ), 'answer' => __( 'We tie SEO to business outcomes: organic traffic, keyword rankings, qualified leads, conversions, and revenue tracked through your analytics and CRM. You get a transparent monthly report showing exactly what we did and what it earned.', 'smart-leading-net' ), 'active' => true ),
		array( 'question' => __( 'Are there long-term contracts?', 'smart-leading-net' ), 'answer' => __( 'No. Our SEO plans are month-to-month, and you can start with a 7-day free trial of our strategy and process. We earn your business with results, not lock-in clauses.', 'smart-leading-net' ), 'active' => true ),
		array( 'question' => __( 'Do you optimise for AI search and Google AI Overviews?', 'smart-leading-net' ), 'answer' => __( 'Yes. We structure your content and entities to earn citations in AI Overviews and answer engines like ChatGPT and Perplexity, alongside traditional rankings — so you stay visible as search evolves.', 'smart-leading-net' ), 'active' => true ),
		array( 'question' => __( 'Who will manage my account?', 'smart-leading-net' ), 'answer' => __( 'You\'ll have a dedicated senior strategist as your main point of contact, supported by specialists in technical SEO, content, and link building. No rotating reps and no ticket queues.', 'smart-leading-net' ), 'active' => true ),
		array( 'question' => __( 'Will SEO work for my industry?', 'smart-leading-net' ), 'answer' => __( 'We\'ve driven results across manufacturing, ecommerce, hospitality, dental, home services, and more. Your free audit will show the specific organic opportunity in your market before you commit to anything.', 'smart-leading-net' ), 'active' => true ),
	);
}

/**
 * FAQ section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_get_seo_services_faq_section( $post_id = null ) {
	$post_id  = sln_seo_services_resolve_post_id( $post_id );
	$defaults = sln_seo_services_default_faq_section();

	if ( ! $post_id || ! sln_seo_services_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_seo_services_merge_section(
		$defaults,
		sln_seo_services_get_meta_or_default( $post_id, SLN_SEO_SVC_FAQ_SECTION_META, $defaults )
	);
}

/**
 * Active FAQ items.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, string>>
 */
function sln_get_seo_services_faq_items( $post_id = null ) {
	$post_id  = sln_seo_services_resolve_post_id( $post_id );
	$defaults = sln_seo_services_default_faq_items();

	if ( ! $post_id || ! sln_seo_services_uses_template( $post_id ) ) {
		return array_values(
			array_map(
				static function ( $item ) {
					return array(
						'question' => $item['question'],
						'answer'   => $item['answer'],
					);
				},
				array_filter( $defaults, 'sln_seo_services_row_is_active' )
			)
		);
	}

	$stored = sln_seo_services_get_meta_or_default( $post_id, SLN_SEO_SVC_FAQ_ITEMS_META, $defaults );
	$items  = is_array( $stored ) && ! empty( $stored ) ? $stored : $defaults;
	$active = array();

	foreach ( $items as $item ) {
		if ( ! is_array( $item ) || ! sln_seo_services_row_is_active( $item ) ) {
			continue;
		}

		$active[] = array(
			'question' => (string) ( $item['question'] ?? '' ),
			'answer'   => (string) ( $item['answer'] ?? '' ),
		);
	}

	return $active;
}

/**
 * FAQ JSON-LD schema.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_seo_services_faq_schema( $post_id = null ) {
	$entities = array();

	foreach ( sln_get_seo_services_faq_items( $post_id ) as $item ) {
		$entities[] = array(
			'@type'          => 'Question',
			'name'           => $item['question'],
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => wp_strip_all_tags( $item['answer'] ),
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
 * Whether a repeater row is active.
 *
 * @param array<string, mixed> $row Row data.
 * @return bool
 */
function sln_seo_services_row_is_active( $row ) {
	return ! array_key_exists( 'active', $row ) || ! empty( $row['active'] );
}

/**
 * Sanitize URL field — pages, growth pages, anchors, external.
 *
 * @param string $url Raw URL.
 * @return string
 */
function sln_seo_services_sanitize_url( $url ) {
	$url = trim( (string) $url );

	if ( '' === $url || '#' === $url ) {
		return $url;
	}

	if ( 0 === strpos( $url, '#' ) ) {
		return sanitize_text_field( $url );
	}

	return esc_url_raw( $url );
}
