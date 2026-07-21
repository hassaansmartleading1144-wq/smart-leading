<?php
/**
 * Digital Marketing page — defaults, meta keys, and frontend data helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_DM_TEMPLATE', 'digital-marketing-page-template.php' );
define( 'SLN_DM_HERO_META', '_sln_dm_hero' );
define( 'SLN_DM_HERO_STATS_META', '_sln_dm_hero_stats' );
define( 'SLN_DM_DASHBOARD_METRICS_META', '_sln_dm_dashboard_metrics' );
define( 'SLN_DM_REALITY_SECTION_META', '_sln_dm_reality_section' );
define( 'SLN_DM_REALITY_CARDS_META', '_sln_dm_reality_cards' );
define( 'SLN_DM_APPROACH_SECTION_META', '_sln_dm_approach_section' );
define( 'SLN_DM_APPROACH_ITEMS_META', '_sln_dm_approach_items' );
define( 'SLN_DM_TRUTH_SECTION_META', '_sln_dm_truth_section' );
define( 'SLN_DM_TRUTH_PARAGRAPHS_META', '_sln_dm_truth_paragraphs' );
define( 'SLN_DM_TRUTH_QUOTE_META', '_sln_dm_truth_quote' );
define( 'SLN_DM_SERVICES_SECTION_META', '_sln_dm_services_section' );
define( 'SLN_DM_SERVICES_ITEMS_META', '_sln_dm_services_items' );
define( 'SLN_DM_ADS_SECTION_META', '_sln_dm_ads_section' );
define( 'SLN_DM_ADS_CHANNELS_META', '_sln_dm_ads_channels' );
define( 'SLN_DM_PROCESS_SECTION_META', '_sln_dm_process_section' );
define( 'SLN_DM_PROCESS_STEPS_META', '_sln_dm_process_steps' );
define( 'SLN_DM_PROOF_SECTION_META', '_sln_dm_proof_section' );
define( 'SLN_DM_CASE_STUDIES_META', '_sln_dm_case_studies' );
define( 'SLN_DM_PRICING_SECTION_META', '_sln_dm_pricing_section' );
define( 'SLN_DM_PRICING_PLANS_META', '_sln_dm_pricing_plans' );
define( 'SLN_DM_FAQ_SECTION_META', '_sln_dm_faq_section' );
define( 'SLN_DM_FAQ_ITEMS_META', '_sln_dm_faq_items' );
define( 'SLN_DM_FINAL_CTA_META', '_sln_dm_final_cta' );

/**
 * Resolve post ID for Digital Marketing data.
 *
 * @param int|null $post_id Optional post ID.
 * @return int
 */
function sln_dm_resolve_post_id( $post_id = null ) {
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
 * Whether a page uses the Digital Marketing template.
 *
 * @param int|null $post_id Optional post ID.
 * @return bool
 */
function sln_dm_uses_template( $post_id = null ) {
	$post_id = sln_dm_resolve_post_id( $post_id );

	if ( ! $post_id ) {
		return function_exists( 'sln_is_digital_marketing_page' ) && sln_is_digital_marketing_page();
	}

	return SLN_DM_TEMPLATE === get_page_template_slug( $post_id );
}

/**
 * Read stored meta or defaults when never saved.
 *
 * @param int    $post_id  Post ID.
 * @param string $meta_key Meta key.
 * @param mixed  $default  Default value.
 * @return mixed
 */
function sln_dm_get_meta_or_default( $post_id, $meta_key, $default ) {
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
function sln_dm_merge_section( $defaults, $stored ) {
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
function sln_dm_plain_text( $content ) {
	return trim( wp_strip_all_tags( (string) $content ) );
}

/**
 * Output sanitized rich text.
 *
 * @param string $content HTML content.
 * @return string
 */
function sln_dm_format_content( $content ) {
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
function sln_dm_row_is_active( $row ) {
	return ! array_key_exists( 'active', $row ) || ! empty( $row['active'] );
}

/**
 * Sanitize URL field — pages, growth pages, anchors, external.
 *
 * @param string $url Raw URL.
 * @return string
 */
function sln_dm_sanitize_url( $url ) {
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
 * @param mixed                $rows     Stored or posted rows.
 * @param array<int, mixed>    $defaults Default rows when stored is empty.
 * @return array<int, array<string, mixed>>
 */
function sln_dm_filter_active_rows( $rows, $defaults = array() ) {
	if ( ! is_array( $rows ) || empty( $rows ) ) {
		$rows = $defaults;
	}

	$active = array();

	foreach ( $rows as $row ) {
		if ( ! is_array( $row ) || ! sln_dm_row_is_active( $row ) ) {
			continue;
		}

		$active[] = $row;
	}

	return $active;
}

/**
 * Default hero section.
 *
 * @return array<string, mixed>
 */
function sln_dm_default_hero() {
	return array(
		'small_heading'       => __( 'Smart Leading Solutions', 'smart-leading-net' ),
		'main_heading'        => __( 'Turn Your Business Challenges Into', 'smart-leading-net' ),
		'highlighted_text'    => __( 'Growth Opportunities.', 'smart-leading-net' ),
		'description'         => __( 'A focused digital marketing roadmap — built to bring you more leads, stronger revenue, and measurable long-term growth.', 'smart-leading-net' ),
		'primary_button_text' => __( 'Book a Free Strategy Call', 'smart-leading-net' ),
		'primary_button_url'  => '#dm-contact',
		'dashboard_title'     => __( 'Live Growth Report', 'smart-leading-net' ),
		'chip_1_text'         => __( '▲ 190% ROAS', 'smart-leading-net' ),
		'chip_2_text'         => __( '● Live tracking', 'smart-leading-net' ),
		'active'              => true,
	);
}

/**
 * Hero section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_dm_hero( $post_id = null ) {
	$post_id  = sln_dm_resolve_post_id( $post_id );
	$defaults = sln_dm_default_hero();

	if ( ! $post_id || ! sln_dm_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_dm_merge_section(
		$defaults,
		sln_dm_get_meta_or_default( $post_id, SLN_DM_HERO_META, $defaults )
	);
}

/**
 * Default hero stat counters.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_dm_default_hero_stats() {
	return array(
		array(
			'prefix'   => '',
			'number'   => '3.2',
			'decimals' => '1',
			'suffix'   => 'x',
			'unit'     => '',
			'label'    => __( 'Average Ad ROAS', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'prefix'   => '+',
			'number'   => '300',
			'decimals' => '0',
			'suffix'   => '%',
			'unit'     => '',
			'label'    => __( 'Lead Volume Growth', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'prefix'   => '$',
			'number'   => '50',
			'decimals' => '0',
			'suffix'   => 'M+',
			'unit'     => '',
			'label'    => __( 'Sales Generated', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'prefix'   => '+',
			'number'   => '220',
			'decimals' => '0',
			'suffix'   => '%',
			'unit'     => '',
			'label'    => __( 'Avg. Revenue Growth', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'prefix'   => '',
			'number'   => '6',
			'decimals' => '0',
			'suffix'   => '',
			'unit'     => __( 'Weeks', 'smart-leading-net' ),
			'label'    => __( 'To Measurable Results', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'prefix'   => '',
			'number'   => '150',
			'decimals' => '0',
			'suffix'   => '+',
			'unit'     => '',
			'label'    => __( 'Businesses Scaled', 'smart-leading-net' ),
			'active'   => true,
		),
	);
}

/**
 * Active hero stat counters.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_dm_hero_stats( $post_id = null ) {
	$post_id  = sln_dm_resolve_post_id( $post_id );
	$defaults = sln_dm_default_hero_stats();

	if ( ! $post_id || ! sln_dm_uses_template( $post_id ) ) {
		return sln_dm_filter_active_rows( $defaults, $defaults );
	}

	$stored = sln_dm_get_meta_or_default( $post_id, SLN_DM_HERO_STATS_META, $defaults );

	return sln_dm_filter_active_rows( $stored, $defaults );
}

/**
 * Default dashboard metrics.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_dm_default_dashboard_metrics() {
	return array(
		array(
			'prefix'   => '',
			'value'    => '3.2',
			'suffix'   => 'x',
			'label'    => __( 'ROAS', 'smart-leading-net' ),
			'decimals' => '1',
		),
		array(
			'prefix'   => '+',
			'value'    => '220',
			'suffix'   => '%',
			'label'    => __( 'Revenue', 'smart-leading-net' ),
			'decimals' => '0',
		),
		array(
			'prefix'   => '',
			'value'    => '150',
			'suffix'   => '+',
			'label'    => __( 'Clients', 'smart-leading-net' ),
			'decimals' => '0',
		),
	);
}

/**
 * Dashboard metrics for hero mockup.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_dm_dashboard_metrics( $post_id = null ) {
	$post_id  = sln_dm_resolve_post_id( $post_id );
	$defaults = sln_dm_default_dashboard_metrics();

	if ( ! $post_id || ! sln_dm_uses_template( $post_id ) ) {
		return $defaults;
	}

	$stored = sln_dm_get_meta_or_default( $post_id, SLN_DM_DASHBOARD_METRICS_META, $defaults );

	return is_array( $stored ) && ! empty( $stored ) ? $stored : $defaults;
}

/**
 * Default reality section header.
 *
 * @return array<string, mixed>
 */
function sln_dm_default_reality_section() {
	return array(
		'small_heading'    => __( 'The Reality', 'smart-leading-net' ),
		'main_heading'     => __( 'We Understand the Challenges', 'smart-leading-net' ),
		'highlighted_text' => __( 'Holding You Back.', 'smart-leading-net' ),
		'description'      => __( 'Most owners come to us after years of frustration. If any of this feels familiar, you\'re in the right place.', 'smart-leading-net' ),
		'note_text'        => __( 'If even one of these feels familiar —', 'smart-leading-net' ),
		'note_highlight'   => __( 'you\'re exactly who we built this for.', 'smart-leading-net' ),
		'note_active'      => true,
	);
}

/**
 * Default reality cards.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_dm_default_reality_cards() {
	return array(
		array( 'icon_id' => 0, 'icon_text' => '!', 'icon_style' => 'orange', 'title' => __( 'Revenue Feels Stuck', 'smart-leading-net' ), 'description' => __( 'Your business works hard, but monthly revenue isn\'t growing the way it should.', 'smart-leading-net' ), 'url' => '', 'active' => true ),
		array( 'icon_id' => 0, 'icon_text' => '!', 'icon_style' => 'blue', 'title' => __( 'Sales Are Inconsistent', 'smart-leading-net' ), 'description' => __( 'Some months bring good inquiries; others feel slow and unpredictable.', 'smart-leading-net' ), 'url' => '', 'active' => true ),
		array( 'icon_id' => 0, 'icon_text' => '!', 'icon_style' => 'orange', 'title' => __( 'Poor Lead Quality', 'smart-leading-net' ), 'description' => __( 'You get inquiries, but many just ask prices and never become serious customers.', 'smart-leading-net' ), 'url' => '', 'active' => true ),
		array( 'icon_id' => 0, 'icon_text' => '!', 'icon_style' => 'orange', 'title' => __( 'Leads Aren\'t Converting', 'smart-leading-net' ), 'description' => __( 'People show interest but don\'t take the next step — call, book, visit or buy.', 'smart-leading-net' ), 'url' => '', 'active' => true ),
		array( 'icon_id' => 0, 'icon_text' => '!', 'icon_style' => 'blue', 'title' => __( 'Competitors Win First', 'smart-leading-net' ), 'description' => __( 'Even when your service is better, rivals with a stronger presence get noticed first.', 'smart-leading-net' ), 'url' => '', 'active' => true ),
		array( 'icon_id' => 0, 'icon_text' => '!', 'icon_style' => 'orange', 'title' => __( 'Weak Brand Trust', 'smart-leading-net' ), 'description' => __( 'People find you online but hesitate, because your brand doesn\'t build confidence fast.', 'smart-leading-net' ), 'url' => '', 'active' => true ),
		array( 'icon_id' => 0, 'icon_text' => '!', 'icon_style' => 'orange', 'title' => __( 'No Clear Offer', 'smart-leading-net' ), 'description' => __( 'Customers don\'t instantly understand why they should choose you over someone else.', 'smart-leading-net' ), 'url' => '', 'active' => true ),
		array( 'icon_id' => 0, 'icon_text' => '!', 'icon_style' => 'blue', 'title' => __( 'Missed Follow-Ups', 'smart-leading-net' ), 'description' => __( 'Interested leads slip away — there\'s no follow-up system to bring them back.', 'smart-leading-net' ), 'url' => '', 'active' => true ),
		array( 'icon_id' => 0, 'icon_text' => '!', 'icon_style' => 'orange', 'title' => __( 'Low Online Visibility', 'smart-leading-net' ), 'description' => __( 'Customers are searching, but you\'re not showing up strongly on Google or social.', 'smart-leading-net' ), 'url' => '', 'active' => true ),
	);
}

/**
 * Reality section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_dm_reality_section( $post_id = null ) {
	$post_id  = sln_dm_resolve_post_id( $post_id );
	$defaults = sln_dm_default_reality_section();

	if ( ! $post_id || ! sln_dm_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_dm_merge_section(
		$defaults,
		sln_dm_get_meta_or_default( $post_id, SLN_DM_REALITY_SECTION_META, $defaults )
	);
}

/**
 * Active reality cards.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_dm_reality_cards( $post_id = null ) {
	$post_id  = sln_dm_resolve_post_id( $post_id );
	$defaults = sln_dm_default_reality_cards();

	if ( ! $post_id || ! sln_dm_uses_template( $post_id ) ) {
		return sln_dm_filter_active_rows( $defaults, $defaults );
	}

	$stored = sln_dm_get_meta_or_default( $post_id, SLN_DM_REALITY_CARDS_META, $defaults );

	return sln_dm_filter_active_rows( $stored, $defaults );
}

/**
 * Default approach section.
 *
 * @return array<string, string>
 */
function sln_dm_default_approach_section() {
	return array(
		'small_heading'    => __( 'Our Approach', 'smart-leading-net' ),
		'main_heading'     => __( 'Your Problem.', 'smart-leading-net' ),
		'highlighted_text' => __( 'Our Solution.', 'smart-leading-net' ),
		'description'      => __( 'Every pain point has a direct, practical answer. Here\'s exactly how we solve each one.', 'smart-leading-net' ),
	);
}

/**
 * Default approach items.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_dm_default_approach_items() {
	return array(
		array(
			'problem'  => __( 'Wasting ad budget with zero leads', 'smart-leading-net' ),
			'solution' => __( 'We <b>audit, rebuild &amp; manage</b> every campaign — every dollar tracked and reported.', 'smart-leading-net' ),
			'url'      => '',
			'active'   => true,
		),
		array(
			'problem'  => __( 'Invisible when customers search', 'smart-leading-net' ),
			'solution' => __( '<b>Google Ads</b> puts you at the top of search the moment buyers are looking.', 'smart-leading-net' ),
			'url'      => '',
			'active'   => true,
		),
		array(
			'problem'  => __( 'Traffic that never converts', 'smart-leading-net' ),
			'solution' => __( 'We build <b>landing pages &amp; funnels</b> designed to turn clicks into real enquiries.', 'smart-leading-net' ),
			'url'      => '',
			'active'   => true,
		),
		array(
			'problem'  => __( 'Social media with no engagement', 'smart-leading-net' ),
			'solution' => __( 'A consistent, <b>on-brand content engine</b> that builds your audience and your DMs.', 'smart-leading-net' ),
			'url'      => '',
			'active'   => true,
		),
		array(
			'problem'  => __( 'Flying blind with no reporting', 'smart-leading-net' ),
			'solution' => __( 'Clear <b>monthly dashboards</b>: leads, cost-per-lead and ROAS in plain language.', 'smart-leading-net' ),
			'url'      => '',
			'active'   => true,
		),
	);
}

/**
 * Approach section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_get_dm_approach_section( $post_id = null ) {
	$post_id  = sln_dm_resolve_post_id( $post_id );
	$defaults = sln_dm_default_approach_section();

	if ( ! $post_id || ! sln_dm_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_dm_merge_section(
		$defaults,
		sln_dm_get_meta_or_default( $post_id, SLN_DM_APPROACH_SECTION_META, $defaults )
	);
}

/**
 * Active approach items.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_dm_approach_items( $post_id = null ) {
	$post_id  = sln_dm_resolve_post_id( $post_id );
	$defaults = sln_dm_default_approach_items();

	if ( ! $post_id || ! sln_dm_uses_template( $post_id ) ) {
		return sln_dm_filter_active_rows( $defaults, $defaults );
	}

	$stored = sln_dm_get_meta_or_default( $post_id, SLN_DM_APPROACH_ITEMS_META, $defaults );

	return sln_dm_filter_active_rows( $stored, $defaults );
}

/**
 * Default truth section.
 *
 * @return array<string, mixed>
 */
function sln_dm_default_truth_section() {
	return array(
		'small_heading'    => __( 'A Quick Truth', 'smart-leading-net' ),
		'main_heading'     => __( 'You Started With a Vision —', 'smart-leading-net' ),
		'highlighted_text' => __( 'Now You Need a System.', 'smart-leading-net' ),
		'button_text'      => __( 'Book a Free Strategy Call', 'smart-leading-net' ),
		'button_url'       => '#dm-contact',
		'active'           => true,
	);
}

/**
 * Default truth paragraphs.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_dm_default_truth_paragraphs() {
	return array(
		array(
			'text'   => __( 'It began with a vision — to do great work, serve more people, and build something that lasts.', 'smart-leading-net' ),
			'active' => true,
		),
		array(
			'text'   => __( 'Then marketing became a maze of dashboards, jargon and budgets that vanish with nothing to show. That\'s not your business failing — it\'s marketing done without a system.', 'smart-leading-net' ),
			'active' => true,
		),
		array(
			'text'   => __( 'You don\'t need more noise. You need a partner who turns ambition into a predictable engine for growth.', 'smart-leading-net' ),
			'active' => true,
		),
	);
}

/**
 * Default truth quote block.
 *
 * @return array<string, mixed>
 */
function sln_dm_default_truth_quote() {
	return array(
		'quote_text'       => __( 'You deserve a marketing partner that brings', 'smart-leading-net' ),
		'highlighted_text' => __( 'measurable results.', 'smart-leading-net' ),
		'attribution'      => __( '— What we promise every client on day one.', 'smart-leading-net' ),
		'graph_label'      => __( 'Client Revenue Trajectory', 'smart-leading-net' ),
		'graph_growth'     => __( '▲ 220%', 'smart-leading-net' ),
		'active'           => true,
	);
}

/**
 * Truth section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_dm_truth_section( $post_id = null ) {
	$post_id  = sln_dm_resolve_post_id( $post_id );
	$defaults = sln_dm_default_truth_section();

	if ( ! $post_id || ! sln_dm_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_dm_merge_section(
		$defaults,
		sln_dm_get_meta_or_default( $post_id, SLN_DM_TRUTH_SECTION_META, $defaults )
	);
}

/**
 * Active truth paragraphs.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_dm_truth_paragraphs( $post_id = null ) {
	$post_id  = sln_dm_resolve_post_id( $post_id );
	$defaults = sln_dm_default_truth_paragraphs();

	if ( ! $post_id || ! sln_dm_uses_template( $post_id ) ) {
		return sln_dm_filter_active_rows( $defaults, $defaults );
	}

	$stored = sln_dm_get_meta_or_default( $post_id, SLN_DM_TRUTH_PARAGRAPHS_META, $defaults );

	return sln_dm_filter_active_rows( $stored, $defaults );
}

/**
 * Truth quote block.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_dm_truth_quote( $post_id = null ) {
	$post_id  = sln_dm_resolve_post_id( $post_id );
	$defaults = sln_dm_default_truth_quote();

	if ( ! $post_id || ! sln_dm_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_dm_merge_section(
		$defaults,
		sln_dm_get_meta_or_default( $post_id, SLN_DM_TRUTH_QUOTE_META, $defaults )
	);
}

/**
 * Default services section.
 *
 * @return array<string, string>
 */
function sln_dm_default_services_section() {
	return array(
		'small_heading'    => __( 'What We Do', 'smart-leading-net' ),
		'main_heading'     => __( 'Everything You Need,', 'smart-leading-net' ),
		'highlighted_text' => __( 'Under One Roof.', 'smart-leading-net' ),
		'description'      => __( 'A complete growth system — not scattered tactics. One team, one strategy, one set of numbers.', 'smart-leading-net' ),
	);
}

/**
 * Default services items.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_dm_default_services_items() {
	return array(
		array( 'icon_text' => '◎', 'icon_style' => 'orange', 'title' => __( 'Paid Advertising', 'smart-leading-net' ), 'description' => __( 'Meta, Google, YouTube & TikTok — fully managed and optimised.', 'smart-leading-net' ), 'url' => '', 'new_tab' => false, 'icon_id' => 0, 'active' => true ),
		array( 'icon_text' => '◍', 'icon_style' => 'blue', 'title' => __( 'Social Media Management', 'smart-leading-net' ), 'description' => __( 'Strategy, content and community that build real trust.', 'smart-leading-net' ), 'url' => '', 'new_tab' => false, 'icon_id' => 0, 'active' => true ),
		array( 'icon_text' => '⌕', 'icon_style' => 'orange', 'title' => __( 'Search Marketing', 'smart-leading-net' ), 'description' => __( 'Show up first the moment buyers are looking for you.', 'smart-leading-net' ), 'url' => '', 'new_tab' => false, 'icon_id' => 0, 'active' => true ),
		array( 'icon_text' => '⮞', 'icon_style' => 'blue', 'title' => __( 'Landing Pages & Funnels', 'smart-leading-net' ), 'description' => __( 'Pages engineered to turn clicks into booked enquiries.', 'smart-leading-net' ), 'url' => '', 'new_tab' => false, 'icon_id' => 0, 'active' => true ),
		array( 'icon_text' => '✦', 'icon_style' => 'orange', 'title' => __( 'Creative & Content', 'smart-leading-net' ), 'description' => __( 'Scroll-stopping graphics, video and copy that convert.', 'smart-leading-net' ), 'url' => '', 'new_tab' => false, 'icon_id' => 0, 'active' => true ),
		array( 'icon_text' => '▤', 'icon_style' => 'blue', 'title' => __( 'Analytics & Reporting', 'smart-leading-net' ), 'description' => __( 'Clear numbers that show exactly what\'s driving growth.', 'smart-leading-net' ), 'url' => '', 'new_tab' => false, 'icon_id' => 0, 'active' => true ),
	);
}

/**
 * Services section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_get_dm_services_section( $post_id = null ) {
	$post_id  = sln_dm_resolve_post_id( $post_id );
	$defaults = sln_dm_default_services_section();

	if ( ! $post_id || ! sln_dm_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_dm_merge_section(
		$defaults,
		sln_dm_get_meta_or_default( $post_id, SLN_DM_SERVICES_SECTION_META, $defaults )
	);
}

/**
 * Active services items.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_dm_services_items( $post_id = null ) {
	$post_id  = sln_dm_resolve_post_id( $post_id );
	$defaults = sln_dm_default_services_items();

	if ( ! $post_id || ! sln_dm_uses_template( $post_id ) ) {
		return sln_dm_filter_active_rows( $defaults, $defaults );
	}

	$stored = sln_dm_get_meta_or_default( $post_id, SLN_DM_SERVICES_ITEMS_META, $defaults );

	return sln_dm_filter_active_rows( $stored, $defaults );
}

/**
 * Default ads section.
 *
 * @return array<string, string>
 */
function sln_dm_default_ads_section() {
	return array(
		'small_heading'    => __( 'Paid Advertising', 'smart-leading-net' ),
		'main_heading'     => __( 'Ads That', 'smart-leading-net' ),
		'highlighted_text' => __( 'Actually Pay You Back.', 'smart-leading-net' ),
		'description'      => __( 'Every major paid channel — managed under one strategy, one team, one transparent dashboard.', 'smart-leading-net' ),
	);
}

/**
 * Default ads channels.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_dm_default_ads_channels() {
	return array(
		array( 'icon_text' => 'f', 'name' => __( 'Meta Ads', 'smart-leading-net' ), 'description' => __( 'Facebook & Instagram', 'smart-leading-net' ), 'url' => '', 'icon_id' => 0, 'active' => true ),
		array( 'icon_text' => 'G', 'name' => __( 'Google Search', 'smart-leading-net' ), 'description' => __( 'High-intent buyers', 'smart-leading-net' ), 'url' => '', 'icon_id' => 0, 'active' => true ),
		array( 'icon_text' => '▷', 'name' => __( 'YouTube & Display', 'smart-leading-net' ), 'description' => __( 'Reach & awareness', 'smart-leading-net' ), 'url' => '', 'icon_id' => 0, 'active' => true ),
		array( 'icon_text' => '♪', 'name' => __( 'TikTok Ads', 'smart-leading-net' ), 'description' => __( 'Viral, younger reach', 'smart-leading-net' ), 'url' => '', 'icon_id' => 0, 'active' => true ),
		array( 'icon_text' => 'in', 'name' => __( 'LinkedIn Ads', 'smart-leading-net' ), 'description' => __( 'B2B decision-makers', 'smart-leading-net' ), 'url' => '', 'icon_id' => 0, 'active' => true ),
		array( 'icon_text' => '↺', 'name' => __( 'Retargeting', 'smart-leading-net' ), 'description' => __( 'Win back lost visitors', 'smart-leading-net' ), 'url' => '', 'icon_id' => 0, 'active' => true ),
		array( 'icon_text' => '◫', 'name' => __( 'Shopping & PMax', 'smart-leading-net' ), 'description' => __( 'Built for e-commerce', 'smart-leading-net' ), 'url' => '', 'icon_id' => 0, 'active' => true ),
		array( 'icon_text' => '✎', 'name' => __( 'Lead-Gen Ads', 'smart-leading-net' ), 'description' => __( 'Forms that fill your pipeline', 'smart-leading-net' ), 'url' => '', 'icon_id' => 0, 'active' => true ),
	);
}

/**
 * Ads section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_get_dm_ads_section( $post_id = null ) {
	$post_id  = sln_dm_resolve_post_id( $post_id );
	$defaults = sln_dm_default_ads_section();

	if ( ! $post_id || ! sln_dm_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_dm_merge_section(
		$defaults,
		sln_dm_get_meta_or_default( $post_id, SLN_DM_ADS_SECTION_META, $defaults )
	);
}

/**
 * Active ads channels.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_dm_ads_channels( $post_id = null ) {
	$post_id  = sln_dm_resolve_post_id( $post_id );
	$defaults = sln_dm_default_ads_channels();

	if ( ! $post_id || ! sln_dm_uses_template( $post_id ) ) {
		return sln_dm_filter_active_rows( $defaults, $defaults );
	}

	$stored = sln_dm_get_meta_or_default( $post_id, SLN_DM_ADS_CHANNELS_META, $defaults );

	return sln_dm_filter_active_rows( $stored, $defaults );
}

/**
 * Default process section.
 *
 * @return array<string, string>
 */
function sln_dm_default_process_section() {
	return array(
		'small_heading' => __( 'How We Work', 'smart-leading-net' ),
		'main_heading'  => __( 'A Clear Path To', 'smart-leading-net' ),
		'highlighted_text' => __( 'Predictable Growth.', 'smart-leading-net' ),
		'description'   => __( 'Four steps, clear ownership, and measurable results at every stage.', 'smart-leading-net' ),
		'bottom_note'   => __( 'Every step is communicated clearly — no jargon, just visible progress.', 'smart-leading-net' ),
	);
}

/**
 * Default process steps.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_dm_default_process_steps() {
	return array(
		array(
			'number'  => '01',
			'title'   => __( 'Discover', 'smart-leading-net' ),
			'bullets' => array(
				__( 'Free strategy call', 'smart-leading-net' ),
				__( 'Understand your goals', 'smart-leading-net' ),
				__( 'Audit your channels', 'smart-leading-net' ),
				__( 'Spot the quick wins', 'smart-leading-net' ),
			),
			'url'     => '',
			'active'  => true,
		),
		array(
			'number'  => '02',
			'title'   => __( 'Strategise', 'smart-leading-net' ),
			'bullets' => array(
				__( 'Custom 90-day plan', 'smart-leading-net' ),
				__( 'Right-fit channels', 'smart-leading-net' ),
				__( 'Smart budget split', 'smart-leading-net' ),
				__( 'Content & ad plan', 'smart-leading-net' ),
			),
			'url'     => '',
			'active'  => true,
		),
		array(
			'number'  => '03',
			'title'   => __( 'Launch', 'smart-leading-net' ),
			'bullets' => array(
				__( 'Accounts optimised', 'smart-leading-net' ),
				__( 'Campaigns go live', 'smart-leading-net' ),
				__( 'Content published', 'smart-leading-net' ),
				__( 'Tracking installed', 'smart-leading-net' ),
			),
			'url'     => '',
			'active'  => true,
		),
		array(
			'number'  => '04',
			'title'   => __( 'Optimise', 'smart-leading-net' ),
			'bullets' => array(
				__( 'Weekly reviews', 'smart-leading-net' ),
				__( 'Constant A/B testing', 'smart-leading-net' ),
				__( 'Budget reallocation', 'smart-leading-net' ),
				__( 'Monthly ROI report', 'smart-leading-net' ),
			),
			'url'     => '',
			'active'  => true,
		),
	);
}

/**
 * Process section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_get_dm_process_section( $post_id = null ) {
	$post_id  = sln_dm_resolve_post_id( $post_id );
	$defaults = sln_dm_default_process_section();

	if ( ! $post_id || ! sln_dm_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_dm_merge_section(
		$defaults,
		sln_dm_get_meta_or_default( $post_id, SLN_DM_PROCESS_SECTION_META, $defaults )
	);
}

/**
 * Active process steps.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_dm_process_steps( $post_id = null ) {
	$post_id  = sln_dm_resolve_post_id( $post_id );
	$defaults = sln_dm_default_process_steps();

	if ( ! $post_id || ! sln_dm_uses_template( $post_id ) ) {
		return sln_dm_filter_active_rows( $defaults, $defaults );
	}

	$stored = sln_dm_get_meta_or_default( $post_id, SLN_DM_PROCESS_STEPS_META, $defaults );

	return sln_dm_filter_active_rows( $stored, $defaults );
}

/**
 * Default proof section.
 *
 * @return array<string, string>
 */
function sln_dm_default_proof_section() {
	return array(
		'small_heading'    => __( 'Proof of Work', 'smart-leading-net' ),
		'main_heading'     => __( 'Real Clients.', 'smart-leading-net' ),
		'highlighted_text' => __( 'Real Numbers.', 'smart-leading-net' ),
		'description'      => __( 'These aren\'t projections. They\'re outcomes from businesses just like yours.', 'smart-leading-net' ),
		'disclaimer'       => __( 'Real client outcomes. Individual results vary by industry, budget and market.', 'smart-leading-net' ),
	);
}

/**
 * Default case studies.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_dm_default_case_studies() {
	return array(
		array(
			'name'        => __( 'Home Improvement Brand', 'smart-leading-net' ),
			'tag'         => __( 'Meta Ads', 'smart-leading-net' ),
			'metrics'     => array(
				array( 'value' => '+340%', 'label' => __( 'Inbound Leads / 4 Mo', 'smart-leading-net' ) ),
				array( 'value' => '−48%', 'label' => __( 'Cost Per Lead', 'smart-leading-net' ) ),
				array( 'value' => '+190%', 'label' => __( 'Revenue Growth', 'smart-leading-net' ) ),
				array( 'value' => '$4.8M', 'label' => __( 'Sales Driven', 'smart-leading-net' ) ),
			),
			'quote'       => __( 'Within two months our phone was ringing every single day — and we knew exactly where each call came from.', 'smart-leading-net' ),
			'attribution' => __( 'Owner, Cabinetry & Flooring Co.', 'smart-leading-net' ),
			'url'         => '',
			'active'      => true,
		),
		array(
			'name'        => __( 'Aesthetics Clinic', 'smart-leading-net' ),
			'tag'         => __( 'Meta + Google', 'smart-leading-net' ),
			'metrics'     => array(
				array( 'value' => '+280%', 'label' => __( 'Bookings / 60 Days', 'smart-leading-net' ) ),
				array( 'value' => '4.8x', 'label' => __( 'Return on Ad Spend', 'smart-leading-net' ) ),
				array( 'value' => '+240%', 'label' => __( 'Revenue Growth', 'smart-leading-net' ) ),
				array( 'value' => '$3.1M', 'label' => __( 'Sales Driven', 'smart-leading-net' ) ),
			),
			'quote'       => __( 'They rebuilt our entire digital presence — our calendar has been fully booked for three months straight.', 'smart-leading-net' ),
			'attribution' => __( 'Director, Aesthetics Practice', 'smart-leading-net' ),
			'url'         => '',
			'active'      => true,
		),
	);
}

/**
 * Proof section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_get_dm_proof_section( $post_id = null ) {
	$post_id  = sln_dm_resolve_post_id( $post_id );
	$defaults = sln_dm_default_proof_section();

	if ( ! $post_id || ! sln_dm_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_dm_merge_section(
		$defaults,
		sln_dm_get_meta_or_default( $post_id, SLN_DM_PROOF_SECTION_META, $defaults )
	);
}

/**
 * Active case studies.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_dm_case_studies( $post_id = null ) {
	$post_id  = sln_dm_resolve_post_id( $post_id );
	$defaults = sln_dm_default_case_studies();

	if ( ! $post_id || ! sln_dm_uses_template( $post_id ) ) {
		return sln_dm_filter_active_rows( $defaults, $defaults );
	}

	$stored = sln_dm_get_meta_or_default( $post_id, SLN_DM_CASE_STUDIES_META, $defaults );

	return sln_dm_filter_active_rows( $stored, $defaults );
}

/**
 * Default pricing section.
 *
 * @return array<string, string>
 */
function sln_dm_default_pricing_section() {
	return array(
		'small_heading'    => __( 'Investment', 'smart-leading-net' ),
		'main_heading'     => __( 'Simple,', 'smart-leading-net' ),
		'highlighted_text' => __( 'Transparent Pricing.', 'smart-leading-net' ),
		'description'      => __( 'No hidden fees. No long contracts. Just results you can measure.', 'smart-leading-net' ),
		'bottom_note'      => __( 'All plans include onboarding, a strategy session and a dedicated account manager. Custom plans available on request.', 'smart-leading-net' ),
	);
}

/**
 * Default pricing plans.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_dm_default_pricing_plans() {
	return array(
		array(
			'name'          => __( 'STARTER', 'smart-leading-net' ),
			'tagline'       => __( 'Launch & Grow', 'smart-leading-net' ),
			'price'         => '$500',
			'price_prefix'  => '',
			'price_suffix'  => __( ' /mo · starting at', 'smart-leading-net' ),
			'is_popular'    => false,
			'popular_badge' => '',
			'features'      => array(
				__( 'Social media management (2 platforms)', 'smart-leading-net' ),
				__( '20 posts/month + captions', 'smart-leading-net' ),
				__( 'Meta Ads setup & management', 'smart-leading-net' ),
				__( 'Monthly analytics report', 'smart-leading-net' ),
				__( 'Dedicated account manager', 'smart-leading-net' ),
			),
			'button_text'   => __( 'Get Started', 'smart-leading-net' ),
			'button_url'    => '#dm-contact',
			'button_style'  => 'ghost',
			'active'        => true,
		),
		array(
			'name'          => __( 'GROWTH', 'smart-leading-net' ),
			'tagline'       => __( 'Accelerate Sales', 'smart-leading-net' ),
			'price'         => '$1,200',
			'price_prefix'  => '',
			'price_suffix'  => __( ' /mo · starting at', 'smart-leading-net' ),
			'is_popular'    => true,
			'popular_badge' => __( 'MOST POPULAR', 'smart-leading-net' ),
			'features'      => array(
				__( 'Everything in Starter, plus:', 'smart-leading-net' ),
				__( 'Google Ads management', 'smart-leading-net' ),
				__( 'Retargeting campaigns', 'smart-leading-net' ),
				__( 'Landing page optimisation', 'smart-leading-net' ),
				__( 'Bi-weekly strategy calls', 'smart-leading-net' ),
				__( 'Lead tracking & CRM setup', 'smart-leading-net' ),
			),
			'button_text'   => __( 'Get Started', 'smart-leading-net' ),
			'button_url'    => '#dm-contact',
			'button_style'  => 'primary',
			'active'        => true,
		),
		array(
			'name'          => __( 'SCALE', 'smart-leading-net' ),
			'tagline'       => __( 'Dominate Market', 'smart-leading-net' ),
			'price'         => '$2,500',
			'price_prefix'  => '',
			'price_suffix'  => __( ' /mo · starting at', 'smart-leading-net' ),
			'is_popular'    => false,
			'popular_badge' => '',
			'features'      => array(
				__( 'Everything in Growth, plus:', 'smart-leading-net' ),
				__( 'Full content production', 'smart-leading-net' ),
				__( 'Advanced funnel build', 'smart-leading-net' ),
				__( 'Weekly reporting dashboard', 'smart-leading-net' ),
				__( 'Priority support', 'smart-leading-net' ),
				__( 'Quarterly strategy & planning', 'smart-leading-net' ),
			),
			'button_text'   => __( 'Get Started', 'smart-leading-net' ),
			'button_url'    => '#dm-contact',
			'button_style'  => 'ghost',
			'active'        => true,
		),
	);
}

/**
 * Pricing section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_get_dm_pricing_section( $post_id = null ) {
	$post_id  = sln_dm_resolve_post_id( $post_id );
	$defaults = sln_dm_default_pricing_section();

	if ( ! $post_id || ! sln_dm_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_dm_merge_section(
		$defaults,
		sln_dm_get_meta_or_default( $post_id, SLN_DM_PRICING_SECTION_META, $defaults )
	);
}

/**
 * Active pricing plans.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_dm_pricing_plans( $post_id = null ) {
	$post_id  = sln_dm_resolve_post_id( $post_id );
	$defaults = sln_dm_default_pricing_plans();

	if ( ! $post_id || ! sln_dm_uses_template( $post_id ) ) {
		return sln_dm_filter_active_rows( $defaults, $defaults );
	}

	$stored = sln_dm_get_meta_or_default( $post_id, SLN_DM_PRICING_PLANS_META, $defaults );

	return sln_dm_filter_active_rows( $stored, $defaults );
}

/**
 * Default FAQ section.
 *
 * @return array<string, string>
 */
function sln_dm_default_faq_section() {
	return array(
		'small_heading'    => __( 'Common Questions', 'smart-leading-net' ),
		'main_heading'     => __( 'Questions?', 'smart-leading-net' ),
		'highlighted_text' => __( 'We\'ve Got Answers.', 'smart-leading-net' ),
		'description'      => __( 'Everything you want to know before we start working together — answered straight.', 'smart-leading-net' ),
	);
}

/**
 * Default FAQ items.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_dm_default_faq_items() {
	return array(
		array(
			'question' => __( 'How soon will I see results?', 'smart-leading-net' ),
			'answer'   => __( 'Most clients see early traction within the first 4–6 weeks, with growth compounding as campaigns are optimised each month.', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'question' => __( 'Do I need a long-term contract?', 'smart-leading-net' ),
			'answer'   => __( 'No. We work month-to-month — we earn your business with results, not lock-in clauses or hidden penalties.', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'question' => __( 'How much should I budget for ads?', 'smart-leading-net' ),
			'answer'   => __( 'It depends on your goals and market. We\'ll recommend a realistic spend on your free call — and every dollar is tracked.', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'question' => __( 'Which platforms do you advertise on?', 'smart-leading-net' ),
			'answer'   => __( 'Meta, Google, YouTube, TikTok and LinkedIn — we pick the channels where your buyers are, not all of them at once.', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'question' => __( 'How will I know it\'s working?', 'smart-leading-net' ),
			'answer'   => __( 'You get clear monthly dashboards showing leads, cost-per-lead and ROAS — in plain language, no vanity metrics.', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'question' => __( 'What if I\'ve been burned before?', 'smart-leading-net' ),
			'answer'   => __( 'Most clients come to us after exactly that. Transparent reporting and a dedicated manager mean you always know what\'s happening.', 'smart-leading-net' ),
			'active'   => true,
		),
	);
}

/**
 * FAQ section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, string>
 */
function sln_get_dm_faq_section( $post_id = null ) {
	$post_id  = sln_dm_resolve_post_id( $post_id );
	$defaults = sln_dm_default_faq_section();

	if ( ! $post_id || ! sln_dm_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_dm_merge_section(
		$defaults,
		sln_dm_get_meta_or_default( $post_id, SLN_DM_FAQ_SECTION_META, $defaults )
	);
}

/**
 * Active FAQ items.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_dm_faq_items( $post_id = null ) {
	$post_id  = sln_dm_resolve_post_id( $post_id );
	$defaults = sln_dm_default_faq_items();

	if ( ! $post_id || ! sln_dm_uses_template( $post_id ) ) {
		return sln_dm_filter_active_rows( $defaults, $defaults );
	}

	$stored = sln_dm_get_meta_or_default( $post_id, SLN_DM_FAQ_ITEMS_META, $defaults );

	return sln_dm_filter_active_rows( $stored, $defaults );
}

/**
 * Default final CTA section.
 *
 * @return array<string, mixed>
 */
function sln_dm_default_final_cta() {
	return array(
		'small_heading'    => __( 'Your Next Step', 'smart-leading-net' ),
		'main_heading'     => __( 'Let\'s Build a Clear Path to', 'smart-leading-net' ),
		'highlighted_text' => __( 'More Leads & Revenue.', 'smart-leading-net' ),
		'description'      => __( 'Your next client is already online — searching for what you offer. The only question is whether they find you, or your competitor.', 'smart-leading-net' ),
		'benefits'         => array(
			array(
				'text'   => __( 'Revenue-focused strategy', 'smart-leading-net' ),
				'active' => true,
			),
			array(
				'text'   => __( 'Transparent reporting', 'smart-leading-net' ),
				'active' => true,
			),
			array(
				'text'   => __( 'Results from Month 1', 'smart-leading-net' ),
				'active' => true,
			),
		),
		'button_text'      => __( 'Book a Free Strategy Call', 'smart-leading-net' ),
		'button_url'       => '#dm-contact',
		'website_text'     => __( 'www.smartleading.net', 'smart-leading-net' ),
		'website_url'      => 'https://www.smartleading.net',
		'bottom_note'      => __( 'No commitment. No pressure. Just an honest conversation about your growth.', 'smart-leading-net' ),
		'active'           => true,
	);
}

/**
 * Final CTA section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_dm_final_cta( $post_id = null ) {
	$post_id  = sln_dm_resolve_post_id( $post_id );
	$defaults = sln_dm_default_final_cta();

	if ( ! $post_id || ! sln_dm_uses_template( $post_id ) ) {
		$data = $defaults;
	} else {
		$data = sln_dm_merge_section(
			$defaults,
			sln_dm_get_meta_or_default( $post_id, SLN_DM_FINAL_CTA_META, $defaults )
		);
	}

	$data['benefits'] = sln_dm_filter_active_rows( $data['benefits'] ?? array(), $defaults['benefits'] );

	return $data;
}
