<?php
/**
 * PPC & Google Ads page — defaults, meta keys, and frontend data helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_PPC_TEMPLATE', 'ppc-google-ads-page-template.php' );
define( 'SLN_PPC_HERO_META', '_sln_ppc_hero' );
define( 'SLN_PPC_HERO_SEARCH_QUERIES_META', '_sln_ppc_hero_search_queries' );
define( 'SLN_PPC_HERO_METRICS_META', '_sln_ppc_hero_metrics' );
define( 'SLN_PPC_HERO_CHART_META', '_sln_ppc_hero_chart' );
define( 'SLN_PPC_KEYWORD_MARQUEE_META', '_sln_ppc_keyword_marquee' );
define( 'SLN_PPC_STATS_META', '_sln_ppc_stats' );
define( 'SLN_PPC_TRUST_SECTION_META', '_sln_ppc_trust_section' );
define( 'SLN_PPC_TRUST_PLATFORMS_META', '_sln_ppc_trust_platforms' );
define( 'SLN_PPC_REALITY_SECTION_META', '_sln_ppc_reality_section' );
define( 'SLN_PPC_REALITY_BUDGET_META', '_sln_ppc_reality_budget' );
define( 'SLN_PPC_REALITY_CHALLENGES_META', '_sln_ppc_reality_challenges' );
define( 'SLN_PPC_APPROACH_SECTION_META', '_sln_ppc_approach_section' );
define( 'SLN_PPC_APPROACH_ITEMS_META', '_sln_ppc_approach_items' );
define( 'SLN_PPC_TRUTH_SECTION_META', '_sln_ppc_truth_section' );
define( 'SLN_PPC_WHY_SECTION_META', '_sln_ppc_why_section' );
define( 'SLN_PPC_WHY_COMPARISON_META', '_sln_ppc_why_comparison' );
define( 'SLN_PPC_WHY_BADGES_META', '_sln_ppc_why_badges' );
define( 'SLN_PPC_SERVICES_SECTION_META', '_sln_ppc_services_section' );
define( 'SLN_PPC_SERVICES_ITEMS_META', '_sln_ppc_services_items' );
define( 'SLN_PPC_INDUSTRIES_SECTION_META', '_sln_ppc_industries_section' );
define( 'SLN_PPC_INDUSTRIES_ITEMS_META', '_sln_ppc_industries_items' );
define( 'SLN_PPC_ROI_SECTION_META', '_sln_ppc_roi_section' );
define( 'SLN_PPC_ROI_CONTROLS_META', '_sln_ppc_roi_controls' );
define( 'SLN_PPC_ROI_OUTPUTS_META', '_sln_ppc_roi_outputs' );
define( 'SLN_PPC_PROCESS_SECTION_META', '_sln_ppc_process_section' );
define( 'SLN_PPC_PROCESS_STEPS_META', '_sln_ppc_process_steps' );
define( 'SLN_PPC_MID_CTA_META', '_sln_ppc_mid_cta' );
define( 'SLN_PPC_PROOF_SECTION_META', '_sln_ppc_proof_section' );
define( 'SLN_PPC_CASE_STUDIES_META', '_sln_ppc_case_studies' );
define( 'SLN_PPC_PRICING_SECTION_META', '_sln_ppc_pricing_section' );
define( 'SLN_PPC_PRICING_PLANS_META', '_sln_ppc_pricing_plans' );
define( 'SLN_PPC_FAQ_SECTION_META', '_sln_ppc_faq_section' );
define( 'SLN_PPC_FAQ_ITEMS_META', '_sln_ppc_faq_items' );
define( 'SLN_PPC_FINAL_CTA_META', '_sln_ppc_final_cta' );

/**
 * Resolve post ID for PPC & Google Ads data.
 *
 * @param int|null $post_id Optional post ID.
 * @return int
 */
function sln_ppc_resolve_post_id( $post_id = null ) {
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
 * Whether a page uses the PPC & Google Ads template.
 *
 * @param int|null $post_id Optional post ID.
 * @return bool
 */
function sln_ppc_uses_template( $post_id = null ) {
	$post_id = sln_ppc_resolve_post_id( $post_id );

	if ( ! $post_id ) {
		return function_exists( 'sln_is_ppc_google_ads_page' ) && sln_is_ppc_google_ads_page();
	}

	return SLN_PPC_TEMPLATE === get_page_template_slug( $post_id );
}

/**
 * Read stored meta or defaults when never saved.
 *
 * @param int    $post_id  Post ID.
 * @param string $meta_key Meta key.
 * @param mixed  $default  Default value.
 * @return mixed
 */
function sln_ppc_get_meta_or_default( $post_id, $meta_key, $default ) {
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
function sln_ppc_merge_section( $defaults, $stored ) {
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
function sln_ppc_plain_text( $content ) {
	return trim( wp_strip_all_tags( (string) $content ) );
}

/**
 * Output sanitized rich text.
 *
 * @param string $content HTML content.
 * @return string
 */
function sln_ppc_format_content( $content ) {
	if ( function_exists( 'sln_growth_page_format_wysiwyg_content' ) ) {
		return sln_growth_page_format_wysiwyg_content( $content );
	}

	return wp_kses_post( $content );
}

/**
 * Whether a repeater row is active.
 *
 * @param array<string, mixed> $row Row data.
 * @return bool
 */
function sln_ppc_row_is_active( $row ) {
	return ! array_key_exists( 'active', $row ) || ! empty( $row['active'] );
}

/**
 * Sanitize URL field — pages, growth pages, anchors, external.
 *
 * @param string $url Raw URL.
 * @return string
 */
function sln_ppc_sanitize_url( $url ) {
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
 * Filter repeater rows to active entries only.
 *
 * @param mixed             $rows     Stored or posted rows.
 * @param array<int, mixed> $defaults Default rows when stored is empty.
 * @return array<int, array<string, mixed>>
 */
function sln_ppc_filter_active_rows( $rows, $defaults = array() ) {
	if ( ! is_array( $rows ) || empty( $rows ) ) {
		$rows = $defaults;
	}

	$active = array();

	foreach ( $rows as $row ) {
		if ( ! is_array( $row ) || ! sln_ppc_row_is_active( $row ) ) {
			continue;
		}

		$active[] = $row;
	}

	return $active;
}

/**
 * Get merged section data for this template.
 *
 * @param int|null             $post_id  Optional post ID.
 * @param string               $meta_key Meta key.
 * @param array<string, mixed> $defaults Defaults.
 * @return array<string, mixed>
 */
function sln_ppc_get_section_data( $post_id, $meta_key, $defaults ) {
	$post_id = sln_ppc_resolve_post_id( $post_id );

	if ( ! $post_id || ! sln_ppc_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_ppc_merge_section(
		$defaults,
		sln_ppc_get_meta_or_default( $post_id, $meta_key, $defaults )
	);
}

/**
 * Get active repeater rows for this template.
 *
 * @param int|null          $post_id  Optional post ID.
 * @param string            $meta_key Meta key.
 * @param array<int, mixed> $defaults Defaults.
 * @return array<int, array<string, mixed>>
 */
function sln_ppc_get_rows_data( $post_id, $meta_key, $defaults ) {
	$post_id = sln_ppc_resolve_post_id( $post_id );

	if ( ! $post_id || ! sln_ppc_uses_template( $post_id ) ) {
		return sln_ppc_filter_active_rows( $defaults, $defaults );
	}

	$stored = sln_ppc_get_meta_or_default( $post_id, $meta_key, $defaults );

	return sln_ppc_filter_active_rows( $stored, $defaults );
}

/**
 * Default hero section.
 *
 * @return array<string, mixed>
 */
function sln_ppc_default_hero() {
	return array(
		'small_heading'              => __( 'PPC & Google Ads Management', 'smart-leading-net' ),
		'main_heading'               => __( 'Google Ads That', 'smart-leading-net' ),
		'highlighted_text'           => __( 'Actually Pay You Back.', 'smart-leading-net' ),
		'description'                => __( 'High-intent Google Ads campaigns — engineered, tracked and relentlessly optimised to turn paid clicks into booked customers and measurable ROI.', 'smart-leading-net' ),
		'primary_button_text'        => __( 'Get a Free PPC Audit', 'smart-leading-net' ),
		'primary_button_url'         => '#contact',
		'secondary_button_text'      => __( 'See how it works', 'smart-leading-net' ),
		'secondary_button_url'       => '#process',
		'search_label'               => __( 'When your buyers search — you show up first', 'smart-leading-net' ),
		'search_result_ad_label'     => __( 'Ad', 'smart-leading-net' ),
		'search_result_url'          => __( 'www.smartleading.net', 'smart-leading-net' ),
		'search_result_title'        => __( 'Smart Leading Solutions — #1 Rated Marketing Partner', 'smart-leading-net' ),
		'search_result_description'  => __( 'Book a free strategy call. 3.2x average ROAS · +300% qualified leads · Transparent reporting.', 'smart-leading-net' ),
		'dashboard_title'            => __( '30-day Campaign Snapshot', 'smart-leading-net' ),
		'chart_label'                => __( 'CLICKS · LAST 30 DAYS', 'smart-leading-net' ),
		'live_label'                 => __( 'LIVE', 'smart-leading-net' ),
		'active'                     => true,
	);
}

/**
 * Hero section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_ppc_hero( $post_id = null ) {
	return sln_ppc_get_section_data( $post_id, SLN_PPC_HERO_META, sln_ppc_default_hero() );
}

/**
 * Default hero search queries.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_ppc_default_hero_search_queries() {
	return array(
		array( 'query' => __( 'digital marketing agency near me', 'smart-leading-net' ), 'active' => true ),
		array( 'query' => __( 'emergency plumber', 'smart-leading-net' ), 'active' => true ),
		array( 'query' => __( 'best dentist near me', 'smart-leading-net' ), 'active' => true ),
		array( 'query' => __( 'law firm free consultation', 'smart-leading-net' ), 'active' => true ),
		array( 'query' => __( 'roof repair quote', 'smart-leading-net' ), 'active' => true ),
		array( 'query' => __( 'hvac installation cost', 'smart-leading-net' ), 'active' => true ),
	);
}

/**
 * Active hero search queries.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_ppc_hero_search_queries( $post_id = null ) {
	return sln_ppc_get_rows_data( $post_id, SLN_PPC_HERO_SEARCH_QUERIES_META, sln_ppc_default_hero_search_queries() );
}

/**
 * Default hero metrics.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_ppc_default_hero_metrics() {
	return array(
		array( 'label' => __( 'SPENDING', 'smart-leading-net' ), 'prefix' => '$', 'value' => '8.99', 'decimals' => '2', 'suffix' => 'k', 'display_value' => '$8.99k', 'visual_style' => 'orange', 'active' => true ),
		array( 'label' => __( 'COST / LEAD', 'smart-leading-net' ), 'prefix' => '$', 'value' => '12.54', 'decimals' => '2', 'suffix' => '', 'display_value' => '$12.54', 'visual_style' => 'green', 'active' => true ),
		array( 'label' => __( 'QUALIFIED LEADS', 'smart-leading-net' ), 'prefix' => '', 'value' => '608', 'decimals' => '0', 'suffix' => '', 'display_value' => '608', 'visual_style' => 'blue', 'active' => true ),
		array( 'label' => __( 'CONV RATE', 'smart-leading-net' ), 'prefix' => '', 'value' => '22.67', 'decimals' => '2', 'suffix' => '%', 'display_value' => '22.67%', 'visual_style' => 'green', 'active' => true ),
	);
}

/**
 * Active hero metrics.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_ppc_hero_metrics( $post_id = null ) {
	return sln_ppc_get_rows_data( $post_id, SLN_PPC_HERO_METRICS_META, sln_ppc_default_hero_metrics() );
}

/**
 * Default hero chart bars.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_ppc_default_hero_chart() {
	return array(
		array( 'height' => 44, 'label' => __( 'Day 1', 'smart-leading-net' ), 'active' => true ),
		array( 'height' => 58, 'label' => __( 'Day 5', 'smart-leading-net' ), 'active' => true ),
		array( 'height' => 50, 'label' => __( 'Day 10', 'smart-leading-net' ), 'active' => true ),
		array( 'height' => 72, 'label' => __( 'Day 15', 'smart-leading-net' ), 'active' => true ),
		array( 'height' => 62, 'label' => __( 'Day 20', 'smart-leading-net' ), 'active' => true ),
		array( 'height' => 86, 'label' => __( 'Day 25', 'smart-leading-net' ), 'active' => true ),
		array( 'height' => 100, 'label' => __( 'Day 30', 'smart-leading-net' ), 'active' => true ),
	);
}

/**
 * Active hero chart bars.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_ppc_hero_chart( $post_id = null ) {
	return sln_ppc_get_rows_data( $post_id, SLN_PPC_HERO_CHART_META, sln_ppc_default_hero_chart() );
}

/**
 * Default keyword marquee rows.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_ppc_default_keyword_marquee() {
	return array(
		array( 'keyword' => __( 'digital marketing agency near me', 'smart-leading-net' ), 'icon_text' => '⌕', 'active' => true ),
		array( 'keyword' => __( 'emergency plumber', 'smart-leading-net' ), 'icon_text' => '⌕', 'active' => true ),
		array( 'keyword' => __( 'best dentist near me', 'smart-leading-net' ), 'icon_text' => '⌕', 'active' => true ),
		array( 'keyword' => __( 'buy running shoes online', 'smart-leading-net' ), 'icon_text' => '⌕', 'active' => true ),
		array( 'keyword' => __( 'law firm free consultation', 'smart-leading-net' ), 'icon_text' => '⌕', 'active' => true ),
		array( 'keyword' => __( 'roof repair quote', 'smart-leading-net' ), 'icon_text' => '⌕', 'active' => true ),
		array( 'keyword' => __( 'med spa booking', 'smart-leading-net' ), 'icon_text' => '⌕', 'active' => true ),
		array( 'keyword' => __( 'hvac installation cost', 'smart-leading-net' ), 'icon_text' => '⌕', 'active' => true ),
		array( 'keyword' => __( 'b2b software demo', 'smart-leading-net' ), 'icon_text' => '⌕', 'active' => true ),
		array( 'keyword' => __( 'personal injury lawyer', 'smart-leading-net' ), 'icon_text' => '⌕', 'active' => true ),
		array( 'keyword' => __( 'kitchen remodel near me', 'smart-leading-net' ), 'icon_text' => '⌕', 'active' => true ),
		array( 'keyword' => __( 'dental implants price', 'smart-leading-net' ), 'icon_text' => '⌕', 'active' => true ),
	);
}

/**
 * Active keyword marquee rows.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_ppc_keyword_marquee( $post_id = null ) {
	return sln_ppc_get_rows_data( $post_id, SLN_PPC_KEYWORD_MARQUEE_META, sln_ppc_default_keyword_marquee() );
}

/**
 * Default stat band.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_ppc_default_stats() {
	return array(
		array( 'prefix' => '', 'number' => '3.2', 'decimals' => '1', 'suffix' => '', 'unit' => 'x', 'display_value' => '3.2x', 'label' => __( 'AVERAGE ROAS', 'smart-leading-net' ), 'active' => true ),
		array( 'prefix' => '−', 'number' => '42', 'decimals' => '0', 'suffix' => '', 'unit' => '%', 'display_value' => '−42%', 'label' => __( 'LOWER COST / LEAD', 'smart-leading-net' ), 'active' => true ),
		array( 'prefix' => '+', 'number' => '300', 'decimals' => '0', 'suffix' => '', 'unit' => '%', 'display_value' => '+300%', 'label' => __( 'MORE QUALIFIED LEADS', 'smart-leading-net' ), 'active' => true ),
		array( 'prefix' => '$', 'number' => '50', 'decimals' => '0', 'suffix' => '', 'unit' => 'M+', 'display_value' => '$50M+', 'label' => __( 'SALES DRIVEN', 'smart-leading-net' ), 'active' => true ),
		array( 'prefix' => '', 'number' => '2', 'decimals' => '0', 'suffix' => '', 'unit' => '–4 wks', 'display_value' => '2–4 wks', 'label' => __( 'TO FIRST RESULTS', 'smart-leading-net' ), 'active' => true ),
	);
}

/**
 * Active stat band.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_ppc_stats( $post_id = null ) {
	return sln_ppc_get_rows_data( $post_id, SLN_PPC_STATS_META, sln_ppc_default_stats() );
}

/**
 * Default trust section.
 *
 * @return array<string, mixed>
 */
function sln_ppc_default_trust_section() {
	return array(
		'label'  => __( 'Certified & battle-tested across every platform that moves the needle', 'smart-leading-net' ),
		'active' => true,
	);
}

/**
 * Trust section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_ppc_trust_section( $post_id = null ) {
	return sln_ppc_get_section_data( $post_id, SLN_PPC_TRUST_SECTION_META, sln_ppc_default_trust_section() );
}

/**
 * Default trust platforms.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_ppc_default_trust_platforms() {
	return array(
		array( 'name' => __( 'Google Ads', 'smart-leading-net' ), 'active' => true ),
		array( 'name' => __( 'Google Partner', 'smart-leading-net' ), 'active' => true ),
		array( 'name' => __( 'Performance Max', 'smart-leading-net' ), 'active' => true ),
		array( 'name' => __( 'Google Shopping', 'smart-leading-net' ), 'active' => true ),
		array( 'name' => __( 'YouTube Ads', 'smart-leading-net' ), 'active' => true ),
		array( 'name' => __( 'Local Service Ads', 'smart-leading-net' ), 'active' => true ),
		array( 'name' => __( 'Business Profile Ads', 'smart-leading-net' ), 'active' => true ),
		array( 'name' => __( 'GA4 & Looker', 'smart-leading-net' ), 'active' => true ),
	);
}

/**
 * Active trust platforms.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_ppc_trust_platforms( $post_id = null ) {
	return sln_ppc_get_rows_data( $post_id, SLN_PPC_TRUST_PLATFORMS_META, sln_ppc_default_trust_platforms() );
}

/**
 * Default reality section.
 *
 * @return array<string, mixed>
 */
function sln_ppc_default_reality_section() {
	return array(
		'small_heading'    => __( 'The Reality', 'smart-leading-net' ),
		'main_heading'     => __( 'Where Most Google Ads Budgets', 'smart-leading-net' ),
		'highlighted_text' => __( 'Quietly Leak Away.', 'smart-leading-net' ),
		'description'      => __( 'Most businesses don\'t have a traffic problem — they have a system problem. If any of this sounds like your account, you\'re in the right place.', 'smart-leading-net' ),
		'bottom_note'      => __( 'If even one of these is costing you — <b>your ad account needs a system, not another guess.</b>', 'smart-leading-net' ),
		'active'           => true,
	);
}

/**
 * Reality section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_ppc_reality_section( $post_id = null ) {
	return sln_ppc_get_section_data( $post_id, SLN_PPC_REALITY_SECTION_META, sln_ppc_default_reality_section() );
}

/**
 * Default reality budget leak panel.
 *
 * @return array<string, mixed>
 */
function sln_ppc_default_reality_budget() {
	return array(
		'lead_text'        => __( 'Where the typical unmanaged account\'s budget actually goes', 'smart-leading-net' ),
		'waste_percent'    => 65,
		'working_percent'  => 35,
		'waste_big_text'   => __( '65% wasted', 'smart-leading-net' ),
		'flip_text'        => __( 'Our job?', 'smart-leading-net' ),
		'flip_highlight'   => __( 'Flip it.', 'smart-leading-net' ),
		'wasted_label'     => __( '✕  Wasted on the wrong clicks', 'smart-leading-net' ),
		'working_label'    => __( 'Actually working  ✓', 'smart-leading-net' ),
		'caption'          => __( 'Illustrative — every account differs. The point: most spend leaks long before it ever reaches a ready buyer.', 'smart-leading-net' ),
		'active'           => true,
	);
}

/**
 * Reality budget leak panel data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_ppc_reality_budget( $post_id = null ) {
	return sln_ppc_get_section_data( $post_id, SLN_PPC_REALITY_BUDGET_META, sln_ppc_default_reality_budget() );
}

/**
 * Default reality challenges.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_ppc_default_reality_challenges() {
	return array(
		array( 'icon_text' => '!', 'title' => __( 'Ad Spend, No Sales', 'smart-leading-net' ), 'description' => __( 'You\'re paying for clicks that were never going to become paying customers — and it\'s eating straight into your profit.', 'smart-leading-net' ), 'impact' => __( 'WASTED SPEND', 'smart-leading-net' ), 'active' => true ),
		array( 'icon_text' => '!', 'title' => __( 'Clicks, Not Customers', 'smart-leading-net' ), 'description' => __( 'Your ads are getting clicked, but almost none of it is turning into actual leads or sales.', 'smart-leading-net' ), 'impact' => __( 'NO SALES', 'smart-leading-net' ), 'active' => true ),
		array( 'icon_text' => '!', 'title' => __( 'Don\'t Know What\'s Working', 'smart-leading-net' ), 'description' => __( 'You\'re spending money every day without knowing which campaigns actually bring in revenue — and which just burn budget.', 'smart-leading-net' ), 'impact' => __( 'ZERO CLARITY', 'smart-leading-net' ), 'active' => true ),
		array( 'icon_text' => '!', 'title' => __( 'Competitors Outrank You', 'smart-leading-net' ), 'description' => __( 'Rivals show up above you and close the deal first — with buyers you already paid to reach.', 'smart-leading-net' ), 'impact' => __( 'LOST DEALS', 'smart-leading-net' ), 'active' => true ),
		array( 'icon_text' => '!', 'title' => __( 'Paying For Bad Clicks', 'smart-leading-net' ), 'description' => __( 'Without tight control, your budget goes to people who were never going to become customers in the first place.', 'smart-leading-net' ), 'impact' => __( 'BUDGET DRAIN', 'smart-leading-net' ), 'active' => true ),
		array( 'icon_text' => '!', 'title' => __( 'Clicks Don\'t Convert', 'smart-leading-net' ), 'description' => __( 'You\'ve already paid for that lead — but a weak page loses them before they pick up the phone or fill out a form.', 'smart-leading-net' ), 'impact' => __( 'LOST LEADS', 'smart-leading-net' ), 'active' => true ),
		array( 'icon_text' => '!', 'title' => __( 'Every Click Costs More', 'smart-leading-net' ), 'description' => __( 'As competition creeps up, each new customer costs more to win — quietly shrinking your margin month after month.', 'smart-leading-net' ), 'impact' => __( 'RISING COSTS', 'smart-leading-net' ), 'active' => true ),
		array( 'icon_text' => '!', 'title' => __( 'Can\'t Prove It\'s Profitable', 'smart-leading-net' ), 'description' => __( 'Without clean tracking back to real sales, you can\'t tell which campaigns are making you money and which are draining your budget.', 'smart-leading-net' ), 'impact' => __( 'FLYING BLIND', 'smart-leading-net' ), 'active' => true ),
		array( 'icon_text' => '!', 'title' => __( 'No Clue on Ad ROI', 'smart-leading-net' ), 'description' => __( 'Vague reporting means you can\'t tell if your ad budget is actually paying you back — or just paying the agency.', 'smart-leading-net' ), 'impact' => __( 'NO TRANSPARENCY', 'smart-leading-net' ), 'active' => true ),
	);
}

/**
 * Active reality challenges.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_ppc_reality_challenges( $post_id = null ) {
	return sln_ppc_get_rows_data( $post_id, SLN_PPC_REALITY_CHALLENGES_META, sln_ppc_default_reality_challenges() );
}

/**
 * Default approach section.
 *
 * @return array<string, mixed>
 */
function sln_ppc_default_approach_section() {
	return array(
		'small_heading'    => __( 'Our Approach', 'smart-leading-net' ),
		'main_heading'     => __( 'From Leaks', 'smart-leading-net' ),
		'highlighted_text' => __( 'to Leads.', 'smart-leading-net' ),
		'description'      => __( 'Every dollar you\'re losing has a direct, engineered fix. Here\'s exactly how we turn a leaking account into a profit engine.', 'smart-leading-net' ),
		'active'           => true,
	);
}

/**
 * Approach section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_ppc_approach_section( $post_id = null ) {
	return sln_ppc_get_section_data( $post_id, SLN_PPC_APPROACH_SECTION_META, sln_ppc_default_approach_section() );
}

/**
 * Default approach items.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_ppc_default_approach_items() {
	return array(
		array( 'problem' => __( 'Wasting budget on junk clicks', 'smart-leading-net' ), 'solution' => __( 'A forensic account audit and rebuild — tight match types, negatives, and <b>every dollar tracked.</b>', 'smart-leading-net' ), 'active' => true ),
		array( 'problem' => __( 'Invisible when buyers search', 'smart-leading-net' ), 'solution' => __( '<b>High-intent Search campaigns</b> that put you at the top the moment they\'re ready to buy.', 'smart-leading-net' ), 'active' => true ),
		array( 'problem' => __( 'Clicks that never convert', 'smart-leading-net' ), 'solution' => __( '<b>Conversion-first landing pages</b> and funnels built to turn paid clicks into real enquiries.', 'smart-leading-net' ), 'active' => true ),
		array( 'problem' => __( 'Flying blind on ROI', 'smart-leading-net' ), 'solution' => __( '<b>Full conversion tracking</b> — GA4, call tracking and a dashboard in plain language.', 'smart-leading-net' ), 'active' => true ),
		array( 'problem' => __( 'Rising costs, shrinking returns', 'smart-leading-net' ), 'solution' => __( '<b>Quality Score & smart-bidding</b> optimisation that lowers your CPC and lifts ROAS.', 'smart-leading-net' ), 'active' => true ),
	);
}

/**
 * Active approach items.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_ppc_approach_items( $post_id = null ) {
	return sln_ppc_get_rows_data( $post_id, SLN_PPC_APPROACH_ITEMS_META, sln_ppc_default_approach_items() );
}

/**
 * Default truth section.
 *
 * @return array<string, mixed>
 */
function sln_ppc_default_truth_section() {
	return array(
		'statement'        => __( 'Clicks were never the goal.', 'smart-leading-net' ),
		'highlighted_text' => __( 'Customers are.', 'smart-leading-net' ),
		'body'             => __( 'It\'s easy to buy traffic — anyone can spend a budget. The hard part, the part that actually grows a business, is turning the <em>right</em> clicks into booked, paying customers, predictably, month after month. That isn\'t luck. That\'s a system.', 'smart-leading-net' ),
		'quote'            => __( 'You deserve ad campaigns that pay for themselves —', 'smart-leading-net' ),
		'quote_highlight'  => __( 'and then some.', 'smart-leading-net' ),
		'attribution'      => __( '— What we promise every client on day one.', 'smart-leading-net' ),
		'button_text'      => __( 'Get a Free PPC Audit', 'smart-leading-net' ),
		'button_url'       => '#contact',
		'active'           => true,
	);
}

/**
 * Truth section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_ppc_truth_section( $post_id = null ) {
	return sln_ppc_get_section_data( $post_id, SLN_PPC_TRUTH_SECTION_META, sln_ppc_default_truth_section() );
}

/**
 * Default why section.
 *
 * @return array<string, mixed>
 */
function sln_ppc_default_why_section() {
	return array(
		'small_heading'    => __( 'Why SLS', 'smart-leading-net' ),
		'main_heading'     => __( 'Built to Fix What', 'smart-leading-net' ),
		'highlighted_text' => __( 'Most Agencies Get Wrong.', 'smart-leading-net' ),
		'description'      => __( 'Every line below is a direct answer to a specific way agencies quietly let clients down. See the difference for yourself.', 'smart-leading-net' ),
		'left_heading'     => __( 'Typical Agency', 'smart-leading-net' ),
		'right_heading'    => __( 'Smart Leading Solutions', 'smart-leading-net' ),
		'active'           => true,
	);
}

/**
 * Why section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_ppc_why_section( $post_id = null ) {
	return sln_ppc_get_section_data( $post_id, SLN_PPC_WHY_SECTION_META, sln_ppc_default_why_section() );
}

/**
 * Default why comparison rows.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_ppc_default_why_comparison() {
	return array(
		array( 'typical' => __( 'Campaigns live inside <b>their</b> ad account — leverage over you if you ever leave', 'smart-leading-net' ), 'sls' => __( 'Built inside <b>your own</b> Google Ads account — 100% yours, always', 'smart-leading-net' ), 'active' => true ),
		array( 'typical' => __( 'Your budget gets handed to a junior after the sales call ends', 'smart-leading-net' ), 'sls' => __( 'Managed start to finish by <b>senior media buyers</b> — never a trainee', 'smart-leading-net' ), 'active' => true ),
		array( 'typical' => __( 'A vague monthly PDF, if the report shows up at all', 'smart-leading-net' ), 'sls' => __( '<b>Live dashboards</b>, plain-language reporting, whenever you want it', 'smart-leading-net' ), 'active' => true ),
		array( 'typical' => __( '12-month lock-in contracts with steep cancellation fees', 'smart-leading-net' ), 'sls' => __( '<b>Month-to-month</b> — we earn every renewal with results, not fine print', 'smart-leading-net' ), 'active' => true ),
		array( 'typical' => __( 'Bids and budgets reviewed occasionally, if at all', 'smart-leading-net' ), 'sls' => __( '<b>Weekly optimisation</b> of bids, budgets and creative — every account, every week', 'smart-leading-net' ), 'active' => true ),
	);
}

/**
 * Active why comparison rows.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_ppc_why_comparison( $post_id = null ) {
	return sln_ppc_get_rows_data( $post_id, SLN_PPC_WHY_COMPARISON_META, sln_ppc_default_why_comparison() );
}

/**
 * Default why badges.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_ppc_default_why_badges() {
	return array(
		array( 'text' => __( 'Google Partner-certified', 'smart-leading-net' ), 'active' => true ),
		array( 'text' => __( 'Senior media buyers only', 'smart-leading-net' ), 'active' => true ),
		array( 'text' => __( 'No lock-in contracts', 'smart-leading-net' ), 'active' => true ),
		array( 'text' => __( 'Live reporting dashboard', 'smart-leading-net' ), 'active' => true ),
	);
}

/**
 * Active why badges.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_ppc_why_badges( $post_id = null ) {
	return sln_ppc_get_rows_data( $post_id, SLN_PPC_WHY_BADGES_META, sln_ppc_default_why_badges() );
}

/**
 * Default services section.
 *
 * @return array<string, mixed>
 */
function sln_ppc_default_services_section() {
	return array(
		'small_heading'    => __( 'What We Do', 'smart-leading-net' ),
		'main_heading'     => __( 'Every High-Intent Channel,', 'smart-leading-net' ),
		'highlighted_text' => __( 'Under One Roof.', 'smart-leading-net' ),
		'description'      => __( 'A complete paid-search system — one strategy, one team, one transparent set of numbers.', 'smart-leading-net' ),
		'active'           => true,
	);
}

/**
 * Services section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_ppc_services_section( $post_id = null ) {
	return sln_ppc_get_section_data( $post_id, SLN_PPC_SERVICES_SECTION_META, sln_ppc_default_services_section() );
}

/**
 * Default services items.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_ppc_default_services_items() {
	return array(
		array( 'icon_key' => 'search', 'icon_text' => '⌕', 'icon_style' => 'blue', 'tag' => __( 'SEARCH', 'smart-leading-net' ), 'title' => __( 'Google Search Ads', 'smart-leading-net' ), 'description' => __( 'Capture high-intent buyers at the exact moment they search for what you sell.', 'smart-leading-net' ), 'active' => true ),
		array( 'icon_key' => 'pmax', 'icon_text' => '▣', 'icon_style' => 'blue', 'tag' => __( 'PMAX', 'smart-leading-net' ), 'title' => __( 'Performance Max & Shopping', 'smart-leading-net' ), 'description' => __( 'Full-funnel reach and product ads engineered for e-commerce growth.', 'smart-leading-net' ), 'active' => true ),
		array( 'icon_key' => 'youtube', 'icon_text' => '▶', 'icon_style' => 'blue', 'tag' => __( 'YOUTUBE', 'smart-leading-net' ), 'title' => __( 'YouTube & Display', 'smart-leading-net' ), 'description' => __( 'Demand and awareness that warm up audiences and feed your search campaigns.', 'smart-leading-net' ), 'active' => true ),
		array( 'icon_key' => 'lsa', 'icon_text' => '●', 'icon_style' => 'blue', 'tag' => __( 'LSA', 'smart-leading-net' ), 'title' => __( 'Local Service Ads', 'smart-leading-net' ), 'description' => __( 'Pay-per-lead placement at the very top of results, backed by the Google Guarantee badge.', 'smart-leading-net' ), 'active' => true ),
		array( 'icon_key' => 'profile', 'icon_text' => '⌂', 'icon_style' => 'blue', 'tag' => __( 'PROFILE', 'smart-leading-net' ), 'title' => __( 'Business Profile Ads', 'smart-leading-net' ), 'description' => __( 'A fully optimised Google Business Profile that turns local searches into calls and visits.', 'smart-leading-net' ), 'active' => true ),
		array( 'icon_key' => 'ai', 'icon_text' => '✦', 'icon_style' => 'blue', 'tag' => __( 'AI', 'smart-leading-net' ), 'title' => __( 'AI Campaign Optimization', 'smart-leading-net' ), 'description' => __( 'Smart-bidding and creative testing tuned continuously by AI, reviewed weekly by a human.', 'smart-leading-net' ), 'active' => true ),
		array( 'icon_key' => 'retarget', 'icon_text' => '↺', 'icon_style' => 'blue', 'tag' => __( 'RETARGET', 'smart-leading-net' ), 'title' => __( 'Retargeting', 'smart-leading-net' ), 'description' => __( 'Win back the visitors who clicked, left, and didn\'t convert the first time.', 'smart-leading-net' ), 'active' => true ),
		array( 'icon_key' => 'cro', 'icon_text' => '▤', 'icon_style' => 'blue', 'tag' => __( 'CRO', 'smart-leading-net' ), 'title' => __( 'Landing Pages & CRO', 'smart-leading-net' ), 'description' => __( 'Pages engineered to lift conversion rate — not just win the click.', 'smart-leading-net' ), 'active' => true ),
		array( 'icon_key' => 'data', 'icon_text' => '▥', 'icon_style' => 'blue', 'tag' => __( 'DATA', 'smart-leading-net' ), 'title' => __( 'Tracking & Analytics', 'smart-leading-net' ), 'description' => __( 'Clean conversion data so every bid and budget decision is grounded in fact.', 'smart-leading-net' ), 'active' => true ),
	);
}

/**
 * Active services items.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_ppc_services_items( $post_id = null ) {
	return sln_ppc_get_rows_data( $post_id, SLN_PPC_SERVICES_ITEMS_META, sln_ppc_default_services_items() );
}

/**
 * Default industries section.
 *
 * @return array<string, mixed>
 */
function sln_ppc_default_industries_section() {
	return array(
		'small_heading'    => __( 'Industries We Scale', 'smart-leading-net' ),
		'main_heading'     => __( 'Built for High-Intent,', 'smart-leading-net' ),
		'highlighted_text' => __( 'High-Value Markets.', 'smart-leading-net' ),
		'description'      => __( 'If your customers search before they buy, paid search can work for you. A few of the industries we grow:', 'smart-leading-net' ),
		'active'           => true,
	);
}

/**
 * Industries section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_ppc_industries_section( $post_id = null ) {
	return sln_ppc_get_section_data( $post_id, SLN_PPC_INDUSTRIES_SECTION_META, sln_ppc_default_industries_section() );
}

/**
 * Default industries items.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_ppc_default_industries_items() {
	return array(
		array( 'icon_text' => '🛒', 'title' => __( 'E-commerce & Retail', 'smart-leading-net' ), 'description' => __( 'Shopping, PMax & Search that scale online revenue.', 'smart-leading-net' ), 'active' => true ),
		array( 'icon_text' => '🦷', 'title' => __( 'Dental & Medical', 'smart-leading-net' ), 'description' => __( 'Fill the calendar with high-value local patients.', 'smart-leading-net' ), 'active' => true ),
		array( 'icon_text' => '⚖️', 'title' => __( 'Legal & Professional', 'smart-leading-net' ), 'description' => __( 'High-intent case leads at a controlled cost.', 'smart-leading-net' ), 'active' => true ),
		array( 'icon_text' => '🔧', 'title' => __( 'Home Services', 'smart-leading-net' ), 'description' => __( 'Phones ringing with ready-to-book jobs.', 'smart-leading-net' ), 'active' => true ),
		array( 'icon_text' => '💻', 'title' => __( 'SaaS & B2B', 'smart-leading-net' ), 'description' => __( 'Demos and pipeline from real decision-makers.', 'smart-leading-net' ), 'active' => true ),
		array( 'icon_text' => '🏠', 'title' => __( 'Real Estate', 'smart-leading-net' ), 'description' => __( 'Buyer and seller leads in your target areas.', 'smart-leading-net' ), 'active' => true ),
		array( 'icon_text' => '💪', 'title' => __( 'Fitness & Wellness', 'smart-leading-net' ), 'description' => __( 'Members and bookings from your local market.', 'smart-leading-net' ), 'active' => true ),
		array( 'icon_text' => '🚗', 'title' => __( 'Automotive', 'smart-leading-net' ), 'description' => __( 'Test drives, quotes and service bookings.', 'smart-leading-net' ), 'active' => true ),
	);
}

/**
 * Active industries items.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_ppc_industries_items( $post_id = null ) {
	return sln_ppc_get_rows_data( $post_id, SLN_PPC_INDUSTRIES_ITEMS_META, sln_ppc_default_industries_items() );
}

/**
 * Default ROI section.
 *
 * @return array<string, mixed>
 */
function sln_ppc_default_roi_section() {
	return array(
		'small_heading'    => __( 'ROI Estimator', 'smart-leading-net' ),
		'main_heading'     => __( 'See What Paid Search', 'smart-leading-net' ),
		'highlighted_text' => __( 'Could Return.', 'smart-leading-net' ),
		'description'      => __( 'Move the sliders to model a realistic month — then we\'ll pressure-test it against your real market.', 'smart-leading-net' ),
		'assumption_text'  => __( 'Assumes a $2.50 average cost-per-click and a 3% click-to-customer rate — a realistic starting point we refine for every account.', 'smart-leading-net' ),
		'cpc'              => 2.5,
		'cvr'              => 0.03,
		'disclaimer'       => __( 'Illustrative only — not a guarantee. Book a free audit for numbers modelled on your actual market.', 'smart-leading-net' ),
		'active'           => true,
	);
}

/**
 * ROI section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_ppc_roi_section( $post_id = null ) {
	return sln_ppc_get_section_data( $post_id, SLN_PPC_ROI_SECTION_META, sln_ppc_default_roi_section() );
}

/**
 * Default ROI controls.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_ppc_default_roi_controls() {
	return array(
		array( 'key' => 'budget', 'label' => __( 'Monthly ad budget', 'smart-leading-net' ), 'min' => 500, 'max' => 20000, 'step' => 250, 'default' => 3000, 'display_value' => '$3,000', 'prefix' => '$', 'suffix' => '', 'active' => true ),
		array( 'key' => 'value', 'label' => __( 'Average customer value', 'smart-leading-net' ), 'min' => 50, 'max' => 5000, 'step' => 50, 'default' => 400, 'display_value' => '$400', 'prefix' => '$', 'suffix' => '', 'active' => true ),
	);
}

/**
 * Active ROI controls.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_ppc_roi_controls( $post_id = null ) {
	return sln_ppc_get_rows_data( $post_id, SLN_PPC_ROI_CONTROLS_META, sln_ppc_default_roi_controls() );
}

/**
 * Default ROI outputs.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_ppc_default_roi_outputs() {
	return array(
		array( 'key' => 'clicks', 'label' => __( 'Clicks / month', 'smart-leading-net' ), 'calc_type' => 'clicks=budget/cpc', 'display_value' => '1,200', 'prefix' => '', 'suffix' => '', 'visual_style' => 'default', 'active' => true ),
		array( 'key' => 'customers', 'label' => __( 'New customers / month', 'smart-leading-net' ), 'calc_type' => 'customers=clicks*cvr', 'display_value' => '36', 'prefix' => '', 'suffix' => '', 'visual_style' => 'blue', 'active' => true ),
		array( 'key' => 'revenue', 'label' => __( 'Revenue / month', 'smart-leading-net' ), 'calc_type' => 'revenue=customers*value', 'display_value' => '$14,400', 'prefix' => '$', 'suffix' => '', 'visual_style' => 'default', 'active' => true ),
		array( 'key' => 'roas', 'label' => __( 'Estimated ROAS', 'smart-leading-net' ), 'calc_type' => 'roas=revenue/budget', 'display_value' => '4.8x', 'prefix' => '', 'suffix' => 'x', 'visual_style' => 'orange', 'highlight' => true, 'active' => true ),
	);
}

/**
 * Active ROI outputs.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_ppc_roi_outputs( $post_id = null ) {
	return sln_ppc_get_rows_data( $post_id, SLN_PPC_ROI_OUTPUTS_META, sln_ppc_default_roi_outputs() );
}

/**
 * Default process section.
 *
 * @return array<string, mixed>
 */
function sln_ppc_default_process_section() {
	return array(
		'small_heading'    => __( 'How We Work', 'smart-leading-net' ),
		'main_heading'     => __( 'From Audit to', 'smart-leading-net' ),
		'highlighted_text' => __( 'Predictable Scale.', 'smart-leading-net' ),
		'description'      => __( 'Four phases, clear ownership, and measurable results at every stage of your account.', 'smart-leading-net' ),
		'bottom_note'      => __( 'You always know what\'s running, what it costs, and what it\'s returning.', 'smart-leading-net' ),
		'active'           => true,
	);
}

/**
 * Process section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_ppc_process_section( $post_id = null ) {
	return sln_ppc_get_section_data( $post_id, SLN_PPC_PROCESS_SECTION_META, sln_ppc_default_process_section() );
}

/**
 * Default process steps.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_ppc_default_process_steps() {
	return array(
		array(
			'number'  => '01',
			'title'   => __( 'Audit & Discover', 'smart-leading-net' ),
			'bullets' => array(
				__( 'Free account audit', 'smart-leading-net' ),
				__( 'Goal & funnel mapping', 'smart-leading-net' ),
				__( 'Competitor & keyword research', 'smart-leading-net' ),
				__( 'Quick-win spotting', 'smart-leading-net' ),
			),
			'active'  => true,
		),
		array(
			'number'  => '02',
			'title'   => __( 'Structure & Strategy', 'smart-leading-net' ),
			'bullets' => array(
				__( 'Custom campaign architecture', 'smart-leading-net' ),
				__( 'Match-type & budget plan', 'smart-leading-net' ),
				__( 'Conversion tracking setup', 'smart-leading-net' ),
				__( 'Ad & landing page plan', 'smart-leading-net' ),
			),
			'active'  => true,
		),
		array(
			'number'  => '03',
			'title'   => __( 'Launch', 'smart-leading-net' ),
			'bullets' => array(
				__( 'Campaigns built & live', 'smart-leading-net' ),
				__( 'Tracking installed', 'smart-leading-net' ),
				__( 'Ads & pages published', 'smart-leading-net' ),
				__( 'Bids dialed in', 'smart-leading-net' ),
			),
			'active'  => true,
		),
		array(
			'number'  => '04',
			'title'   => __( 'Optimise & Scale', 'smart-leading-net' ),
			'bullets' => array(
				__( 'Weekly bid management', 'smart-leading-net' ),
				__( 'Constant A/B testing', 'smart-leading-net' ),
				__( 'Budget reallocation', 'smart-leading-net' ),
				__( 'Monthly ROI report', 'smart-leading-net' ),
			),
			'active'  => true,
		),
	);
}

/**
 * Active process steps.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_ppc_process_steps( $post_id = null ) {
	return sln_ppc_get_rows_data( $post_id, SLN_PPC_PROCESS_STEPS_META, sln_ppc_default_process_steps() );
}

/**
 * Default mid-page CTA.
 *
 * @return array<string, mixed>
 */
function sln_ppc_default_mid_cta() {
	return array(
		'heading'     => __( 'See These Results in Your Own Account.', 'smart-leading-net' ),
		'description' => __( 'Book a free, no-pressure PPC audit and get a clear breakdown of what\'s possible for your business.', 'smart-leading-net' ),
		'button_text' => __( 'Get a Free PPC Audit', 'smart-leading-net' ),
		'button_url'  => '#contact',
		'active'      => true,
	);
}

/**
 * Mid-page CTA data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_ppc_mid_cta( $post_id = null ) {
	return sln_ppc_get_section_data( $post_id, SLN_PPC_MID_CTA_META, sln_ppc_default_mid_cta() );
}

/**
 * Default proof section.
 *
 * @return array<string, mixed>
 */
function sln_ppc_default_proof_section() {
	return array(
		'small_heading'    => __( 'Proof of Work', 'smart-leading-net' ),
		'main_heading'     => __( 'Real Accounts.', 'smart-leading-net' ),
		'highlighted_text' => __( 'Real Returns.', 'smart-leading-net' ),
		'description'      => __( 'Not projections — outcomes from paid-search accounts just like yours.', 'smart-leading-net' ),
		'disclaimer'       => __( 'Real client outcomes. Individual results vary by industry, budget and market.', 'smart-leading-net' ),
		'active'           => true,
	);
}

/**
 * Proof section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_ppc_proof_section( $post_id = null ) {
	return sln_ppc_get_section_data( $post_id, SLN_PPC_PROOF_SECTION_META, sln_ppc_default_proof_section() );
}

/**
 * Default case studies.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_ppc_default_case_studies() {
	return array(
		array(
			'name'        => __( 'E-commerce Retailer', 'smart-leading-net' ),
			'tag'         => __( 'SEARCH + SHOPPING', 'smart-leading-net' ),
			'metrics'     => array(
				array( 'prefix' => '+', 'value' => '312', 'decimals' => '0', 'suffix' => '%', 'display_value' => '+312%', 'label' => __( 'ONLINE REVENUE', 'smart-leading-net' ), 'visual_style' => 'blue' ),
				array( 'prefix' => '−', 'value' => '38', 'decimals' => '0', 'suffix' => '%', 'display_value' => '−38%', 'label' => __( 'COST / SALE', 'smart-leading-net' ), 'visual_style' => 'green' ),
				array( 'prefix' => '', 'value' => '5.1', 'decimals' => '1', 'suffix' => 'x', 'display_value' => '5.1x', 'label' => __( 'ROAS', 'smart-leading-net' ), 'visual_style' => 'orange' ),
				array( 'prefix' => '$', 'value' => '6.2', 'decimals' => '1', 'suffix' => 'M', 'display_value' => '$6.2M', 'label' => __( 'SALES DRIVEN', 'smart-leading-net' ), 'visual_style' => 'default' ),
			),
			'progress'    => array( 'label' => __( 'Return on ad spend', 'smart-leading-net' ), 'value' => '5.1x', 'width' => 90 ),
			'quote'       => __( 'Our Shopping campaigns went from a money pit to our best-performing sales channel in under a quarter.', 'smart-leading-net' ),
			'attribution' => __( '— Founder, E-commerce Retailer', 'smart-leading-net' ),
			'active'      => true,
		),
		array(
			'name'        => __( 'Dental Practice', 'smart-leading-net' ),
			'tag'         => __( 'SEARCH + CALL ADS', 'smart-leading-net' ),
			'metrics'     => array(
				array( 'prefix' => '+', 'value' => '265', 'decimals' => '0', 'suffix' => '%', 'display_value' => '+265%', 'label' => __( 'NEW PATIENTS', 'smart-leading-net' ), 'visual_style' => 'blue' ),
				array( 'prefix' => '−', 'value' => '41', 'decimals' => '0', 'suffix' => '%', 'display_value' => '−41%', 'label' => __( 'COST / PATIENT', 'smart-leading-net' ), 'visual_style' => 'green' ),
				array( 'prefix' => '', 'value' => '6.3', 'decimals' => '1', 'suffix' => 'x', 'display_value' => '6.3x', 'label' => __( 'ROAS', 'smart-leading-net' ), 'visual_style' => 'orange' ),
				array( 'prefix' => '$', 'value' => '2.4', 'decimals' => '1', 'suffix' => 'M', 'display_value' => '$2.4M', 'label' => __( 'REVENUE DRIVEN', 'smart-leading-net' ), 'visual_style' => 'default' ),
			),
			'progress'    => array( 'label' => __( 'New patient volume vs. before', 'smart-leading-net' ), 'value' => '+265%', 'width' => 86 ),
			'quote'       => __( 'New-patient calls more than doubled within weeks — and we finally know exactly what each one costs us.', 'smart-leading-net' ),
			'attribution' => __( '— Practice Owner, Dental Clinic', 'smart-leading-net' ),
			'active'      => true,
		),
	);
}

/**
 * Active case studies.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_ppc_case_studies( $post_id = null ) {
	return sln_ppc_get_rows_data( $post_id, SLN_PPC_CASE_STUDIES_META, sln_ppc_default_case_studies() );
}

/**
 * Default pricing section.
 *
 * @return array<string, mixed>
 */
function sln_ppc_default_pricing_section() {
	return array(
		'small_heading'    => __( 'Investment', 'smart-leading-net' ),
		'main_heading'     => __( 'Simple,', 'smart-leading-net' ),
		'highlighted_text' => __( 'Transparent Management.', 'smart-leading-net' ),
		'description'      => __( 'No hidden fees. No long contracts. You own the account — we make it profitable.', 'smart-leading-net' ),
		'bottom_note'      => __( '<b>Management fee only</b> — your ad spend goes straight to Google, and you keep 100% ownership of your account. Custom plans available on request.', 'smart-leading-net' ),
		'active'           => true,
	);
}

/**
 * Pricing section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_ppc_pricing_section( $post_id = null ) {
	return sln_ppc_get_section_data( $post_id, SLN_PPC_PRICING_SECTION_META, sln_ppc_default_pricing_section() );
}

/**
 * Default pricing plans.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_ppc_default_pricing_plans() {
	return array(
		array(
			'name'          => __( 'LAUNCH', 'smart-leading-net' ),
			'tagline'       => __( 'For getting paid search live and profitable.', 'smart-leading-net' ),
			'price'         => '$500',
			'price_suffix'  => __( ' /mo mgmt', 'smart-leading-net' ),
			'spend'         => __( 'Ad spend managed up to $3k/mo', 'smart-leading-net' ),
			'is_popular'    => false,
			'popular_badge' => '',
			'features'      => array(
				array( 'text' => __( 'Get Google Ads live & profitable', 'smart-leading-net' ), 'highlight' => true, 'active' => true ),
				array( 'text' => __( '1–2 Search campaigns', 'smart-leading-net' ), 'highlight' => false, 'active' => true ),
				array( 'text' => __( 'Conversion tracking setup', 'smart-leading-net' ), 'highlight' => false, 'active' => true ),
				array( 'text' => __( 'Monthly performance report', 'smart-leading-net' ), 'highlight' => false, 'active' => true ),
				array( 'text' => __( 'Dedicated account manager', 'smart-leading-net' ), 'highlight' => false, 'active' => true ),
			),
			'button_text'   => __( 'Get Started', 'smart-leading-net' ),
			'button_url'    => 'mailto:hello@smartleading.net?subject=Launch%20Plan%20Enquiry',
			'button_style'  => 'ghost',
			'active'        => true,
		),
		array(
			'name'          => __( 'SCALE', 'smart-leading-net' ),
			'tagline'       => __( 'For businesses ready to grow paid search.', 'smart-leading-net' ),
			'price'         => '$1,200',
			'price_suffix'  => __( ' /mo mgmt', 'smart-leading-net' ),
			'spend'         => __( 'Ad spend managed up to $15k/mo', 'smart-leading-net' ),
			'is_popular'    => true,
			'popular_badge' => __( '★ MOST POPULAR', 'smart-leading-net' ),
			'features'      => array(
				array( 'text' => __( 'Everything in Launch, plus:', 'smart-leading-net' ), 'highlight' => true, 'active' => true ),
				array( 'text' => __( 'Search + PMax + Retargeting', 'smart-leading-net' ), 'highlight' => false, 'active' => true ),
				array( 'text' => __( 'Landing page optimisation', 'smart-leading-net' ), 'highlight' => false, 'active' => true ),
				array( 'text' => __( 'Bi-weekly strategy calls', 'smart-leading-net' ), 'highlight' => false, 'active' => true ),
				array( 'text' => __( 'Call & lead tracking', 'smart-leading-net' ), 'highlight' => false, 'active' => true ),
			),
			'button_text'   => __( 'Get Started', 'smart-leading-net' ),
			'button_url'    => 'mailto:hello@smartleading.net?subject=Scale%20Plan%20Enquiry',
			'button_style'  => 'primary',
			'active'        => true,
		),
		array(
			'name'          => __( 'DOMINATE', 'smart-leading-net' ),
			'tagline'       => __( 'For capturing maximum market share.', 'smart-leading-net' ),
			'price'         => '$2,500',
			'price_suffix'  => __( ' /mo mgmt', 'smart-leading-net' ),
			'spend'         => __( 'Ad spend $15k+/mo', 'smart-leading-net' ),
			'is_popular'    => false,
			'popular_badge' => '',
			'features'      => array(
				array( 'text' => __( 'Everything in Scale, plus:', 'smart-leading-net' ), 'highlight' => true, 'active' => true ),
				array( 'text' => __( 'Full-funnel: YouTube + Display', 'smart-leading-net' ), 'highlight' => false, 'active' => true ),
				array( 'text' => __( 'Advanced CRO & funnel build', 'smart-leading-net' ), 'highlight' => false, 'active' => true ),
				array( 'text' => __( 'Weekly reporting dashboard', 'smart-leading-net' ), 'highlight' => false, 'active' => true ),
				array( 'text' => __( 'Priority support + quarterly planning', 'smart-leading-net' ), 'highlight' => false, 'active' => true ),
			),
			'button_text'   => __( 'Get Started', 'smart-leading-net' ),
			'button_url'    => 'mailto:hello@smartleading.net?subject=Dominate%20Plan%20Enquiry',
			'button_style'  => 'ghost',
			'active'        => true,
		),
	);
}

/**
 * Active pricing plans.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_ppc_pricing_plans( $post_id = null ) {
	return sln_ppc_get_rows_data( $post_id, SLN_PPC_PRICING_PLANS_META, sln_ppc_default_pricing_plans() );
}

/**
 * Default FAQ section.
 *
 * @return array<string, mixed>
 */
function sln_ppc_default_faq_section() {
	return array(
		'small_heading'    => __( 'Common Questions', 'smart-leading-net' ),
		'main_heading'     => __( 'Questions?', 'smart-leading-net' ),
		'highlighted_text' => __( 'We\'ve Got Answers.', 'smart-leading-net' ),
		'description'      => __( 'Everything you want to know before we touch your ad account — answered straight.', 'smart-leading-net' ),
		'active'           => true,
	);
}

/**
 * FAQ section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_ppc_faq_section( $post_id = null ) {
	return sln_ppc_get_section_data( $post_id, SLN_PPC_FAQ_SECTION_META, sln_ppc_default_faq_section() );
}

/**
 * Default FAQ items.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_ppc_default_faq_items() {
	return array(
		array( 'question' => __( 'How soon will I see results?', 'smart-leading-net' ), 'answer' => __( 'Google Ads can drive qualified traffic from day one. Most clients see meaningful lead flow within the first 2–4 weeks, then compounding gains as we optimise bids, keywords and landing pages.', 'smart-leading-net' ), 'active' => true ),
		array( 'question' => __( 'Who owns the ad account?', 'smart-leading-net' ), 'answer' => __( 'You do — 100%. We build inside your own Google Ads account, so you keep every campaign, all conversion history and every dollar of data, even if we ever part ways.', 'smart-leading-net' ), 'active' => true ),
		array( 'question' => __( 'Is ad spend included in the fee?', 'smart-leading-net' ), 'answer' => __( 'No. The management fee is separate — your ad budget goes directly to Google. That keeps spending fully transparent and completely in your control.', 'smart-leading-net' ), 'active' => true ),
		array( 'question' => __( 'How much should I spend on ads?', 'smart-leading-net' ), 'answer' => __( 'It depends on your market and goals. We\'ll recommend a realistic starting budget on your free audit — and only scale it as the numbers justify the return.', 'smart-leading-net' ), 'active' => true ),
		array( 'question' => __( 'Which campaigns will you run?', 'smart-leading-net' ), 'answer' => __( 'Only the ones that fit your buyers — usually high-intent Search first, then PMax, Retargeting and more as we prove ROI. No spray-and-pray, no wasted budget.', 'smart-leading-net' ), 'active' => true ),
		array( 'question' => __( 'What if I\'ve been burned before?', 'smart-leading-net' ), 'answer' => __( 'Most clients come to us after exactly that. Transparent reporting, full account ownership and a dedicated manager mean you always know what\'s running and what it\'s returning.', 'smart-leading-net' ), 'active' => true ),
	);
}

/**
 * Active FAQ items.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_ppc_faq_items( $post_id = null ) {
	return sln_ppc_get_rows_data( $post_id, SLN_PPC_FAQ_ITEMS_META, sln_ppc_default_faq_items() );
}

/**
 * Default final CTA section.
 *
 * @return array<string, mixed>
 */
function sln_ppc_default_final_cta() {
	return array(
		'small_heading'          => __( 'Your Next Step', 'smart-leading-net' ),
		'main_heading'           => __( 'Ready to Own the', 'smart-leading-net' ),
		'highlighted_text'       => __( 'Top of Search?', 'smart-leading-net' ),
		'description'            => __( 'Get a free, no-obligation audit of your Google Ads account. We\'ll show you exactly where budget is leaking — and precisely what we\'d do about it.', 'smart-leading-net' ),
		'primary_button_text'    => __( 'Get My Free PPC Audit', 'smart-leading-net' ),
		'primary_button_url'     => 'mailto:hello@smartleading.net?subject=Free%20PPC%20Audit%20Request',
		'secondary_button_text'  => __( 'See the process', 'smart-leading-net' ),
		'secondary_button_url'   => '#process',
		'website_label'          => __( 'or visit', 'smart-leading-net' ),
		'website_text'           => __( 'www.smartleading.net', 'smart-leading-net' ),
		'website_url'            => 'https://www.smartleading.net',
		'bottom_note'            => __( 'No commitment. No pressure. Just a clear look at your numbers.', 'smart-leading-net' ),
		'active'                 => true,
	);
}

/**
 * Final CTA section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_ppc_final_cta( $post_id = null ) {
	return sln_ppc_get_section_data( $post_id, SLN_PPC_FINAL_CTA_META, sln_ppc_default_final_cta() );
}
