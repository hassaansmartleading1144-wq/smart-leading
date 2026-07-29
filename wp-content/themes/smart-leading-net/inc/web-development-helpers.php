<?php
/**
 * Web Development Services page — defaults, meta keys, and frontend data helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_WD_TEMPLATE', 'web-development-page-template.php' );
define( 'SLN_WD_HERO_META', '_sln_wd_hero' );
define( 'SLN_WD_STATS_META', '_sln_wd_stats' );
define( 'SLN_WD_PLATFORMS_SECTION_META', '_sln_wd_platforms_section' );
define( 'SLN_WD_PLATFORMS_META', '_sln_wd_platforms' );
define( 'SLN_WD_EXTRAS_SECTION_META', '_sln_wd_extras_section' );
define( 'SLN_WD_EXTRAS_META', '_sln_wd_extras' );
define( 'SLN_WD_MATTERS_SECTION_META', '_sln_wd_matters_section' );
define( 'SLN_WD_MATTERS_CARDS_META', '_sln_wd_matters_cards' );
define( 'SLN_WD_PROCESS_SECTION_META', '_sln_wd_process_section' );
define( 'SLN_WD_PROCESS_STAGES_META', '_sln_wd_process_stages' );
define( 'SLN_WD_PROMISES_SECTION_META', '_sln_wd_promises_section' );
define( 'SLN_WD_PROMISES_META', '_sln_wd_promises' );
define( 'SLN_WD_SERVICES_SECTION_META', '_sln_wd_services_section' );
define( 'SLN_WD_SERVICES_META', '_sln_wd_services' );
define( 'SLN_WD_COMPARE_SECTION_META', '_sln_wd_compare_section' );
define( 'SLN_WD_COMPARE_ROWS_META', '_sln_wd_compare_rows' );
define( 'SLN_WD_BENEFITS_SECTION_META', '_sln_wd_benefits_section' );
define( 'SLN_WD_BENEFITS_META', '_sln_wd_benefits' );
define( 'SLN_WD_CASES_SECTION_META', '_sln_wd_cases_section' );
define( 'SLN_WD_CASES_META', '_sln_wd_cases' );
define( 'SLN_WD_PRICING_SECTION_META', '_sln_wd_pricing_section' );
define( 'SLN_WD_PRICING_PLANS_META', '_sln_wd_pricing_plans' );
define( 'SLN_WD_CARE_SECTION_META', '_sln_wd_care_section' );
define( 'SLN_WD_CARE_PLANS_META', '_sln_wd_care_plans' );
define( 'SLN_WD_FAQ_SECTION_META', '_sln_wd_faq_section' );
define( 'SLN_WD_FAQ_ITEMS_META', '_sln_wd_faq_items' );
define( 'SLN_WD_FINAL_SECTION_META', '_sln_wd_final_section' );

/**
 * Resolve post ID for Web Development data.
 *
 * @param int|null $post_id Optional post ID.
 * @return int
 */
function sln_wd_resolve_post_id( $post_id = null ) {
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
 * Whether a page uses the Web Development template.
 *
 * @param int|null $post_id Optional post ID.
 * @return bool
 */
function sln_wd_uses_template( $post_id = null ) {
	$post_id = sln_wd_resolve_post_id( $post_id );

	if ( ! $post_id ) {
		return function_exists( 'sln_is_web_development_page' ) && sln_is_web_development_page();
	}

	return SLN_WD_TEMPLATE === get_page_template_slug( $post_id );
}

/**
 * Read stored meta or defaults when never saved.
 *
 * @param int    $post_id  Post ID.
 * @param string $meta_key Meta key.
 * @param mixed  $default  Default value.
 * @return mixed
 */
function sln_wd_get_meta_or_default( $post_id, $meta_key, $default ) {
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
function sln_wd_merge_section( $defaults, $stored ) {
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
function sln_wd_plain_text( $content ) {
	return trim( wp_strip_all_tags( (string) $content ) );
}

/**
 * Output sanitized rich text.
 *
 * @param string $content HTML content.
 * @return string
 */
function sln_wd_format_content( $content ) {
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
function sln_wd_row_is_active( $row ) {
	return ! array_key_exists( 'active', $row ) || ! empty( $row['active'] );
}

/**
 * Sanitize URL field — pages, growth pages, anchors, external.
 *
 * @param string $url Raw URL.
 * @return string
 */
function sln_wd_sanitize_url( $url ) {
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
function sln_wd_filter_active_rows( $rows, $defaults = array() ) {
	if ( ! is_array( $rows ) || empty( $rows ) ) {
		$rows = $defaults;
	}

	$active = array();

	foreach ( $rows as $row ) {
		if ( ! is_array( $row ) || ! sln_wd_row_is_active( $row ) ) {
			continue;
		}

		$active[] = $row;
	}

	return $active;
}

/**
 * Filter active process stages and nested steps.
 *
 * @param mixed             $stages   Stored or default stages.
 * @param array<int, mixed> $defaults Default stages when stored is empty.
 * @return array<int, array<string, mixed>>
 */
function sln_wd_filter_active_process_stages( $stages, $defaults = array() ) {
	if ( ! is_array( $stages ) || empty( $stages ) ) {
		$stages = $defaults;
	}

	$active_stages = array();

	foreach ( $stages as $stage ) {
		if ( ! is_array( $stage ) || ! sln_wd_row_is_active( $stage ) ) {
			continue;
		}

		$steps = $stage['steps'] ?? array();
		if ( is_array( $steps ) ) {
			$stage['steps'] = sln_wd_filter_active_rows( $steps, $steps );
		}

		$active_stages[] = $stage;
	}

	return $active_stages;
}

/**
 * Default project need options for the contact form.
 *
 * @return array<int, string>
 */
function sln_wd_default_need_options() {
	return array(
		__( 'new website', 'smart-leading-net' ),
		__( 'online store', 'smart-leading-net' ),
		__( 'redesign', 'smart-leading-net' ),
		__( 'ongoing care', 'smart-leading-net' ),
		__( 'advice', 'smart-leading-net' ),
	);
}

/**
 * Default budget range options for the contact form.
 *
 * @return array<int, string>
 */
function sln_wd_default_budget_options() {
	return array(
		__( 'Under $1,500', 'smart-leading-net' ),
		__( '$1,500–$3,000', 'smart-leading-net' ),
		__( '$3,000–$5,000', 'smart-leading-net' ),
		__( '$5,000+', 'smart-leading-net' ),
		__( 'Not sure yet', 'smart-leading-net' ),
	);
}

/**
 * Default country list for the contact form.
 *
 * @return array<int, string>
 */
function sln_wd_default_countries() {
	return array(
		__( 'United States', 'smart-leading-net' ),
		__( 'United Kingdom', 'smart-leading-net' ),
		__( 'Australia', 'smart-leading-net' ),
		__( 'Canada', 'smart-leading-net' ),
		__( 'Pakistan', 'smart-leading-net' ),
		__( 'Other', 'smart-leading-net' ),
	);
}

/**
 * Default hero section.
 *
 * @return array<string, mixed>
 */
function sln_wd_default_hero() {
	return array(
		'trust_badge'           => __( 'Trusted Agency', 'smart-leading-net' ),
		'certified_text'        => __( 'Certified Team', 'smart-leading-net' ),
		'main_heading'          => __( 'A website that', 'smart-leading-net' ),
		'highlighted_text'      => __( 'works as hard as you do', 'smart-leading-net' ),
		'description'           => __( 'We design, build, and look after websites that bring you customers — clear to use, quick to load, and easy on your budget. Whether you need a brand-new site, a fresh new look for an old one, or a steady hand keeping your current site in great shape, we handle every part for you.', 'smart-leading-net' ),
		'primary_button_text'   => __( 'Get my free website quote', 'smart-leading-net' ),
		'primary_button_url'    => '#wd-contact',
		'secondary_button_text' => __( 'See case studies', 'smart-leading-net' ),
		'secondary_button_url'  => '#wd-cases',
		'hero_image_id'         => 0,
		'float_stat_1_label'    => __( 'Websites built', 'smart-leading-net' ),
		'float_stat_1_value'    => '120+',
		'float_stat_2_label'    => __( 'To go live', 'smart-leading-net' ),
		'float_stat_2_value'    => __( '~4 wk', 'smart-leading-net' ),
		'float_stat_3_label'    => __( 'Happy clients', 'smart-leading-net' ),
		'float_stat_3_value'    => '98%',
		'float_stat_4_label'    => __( 'Website performance loads in', 'smart-leading-net' ),
		'float_stat_4_value'    => '1.8s',
		'float_stat_5_label'    => __( 'Performance score', 'smart-leading-net' ),
		'float_stat_5_value'    => '98 / 100',
		'rating_line'           => __( '4.9 average client rating · 120+ websites launched · Fixed quotes, no surprises · You own everything we build', 'smart-leading-net' ),
		'active'                => true,
	);
}

/**
 * Default hero dashboard project cards (PDF visual).
 *
 * @return array<int, array<string, string>>
 */
function sln_wd_default_hero_projects() {
	return array(
		array(
			'name'   => 'Nexis Holdings',
			'result' => '↑ 34%',
			'type'   => __( 'Corporate Website', 'smart-leading-net' ),
		),
		array(
			'name'   => 'Pinnacle',
			'result' => '↑ 18%',
			'type'   => __( 'Industrial Equipment Site', 'smart-leading-net' ),
		),
		array(
			'name'   => 'BlueOak Realty',
			'result' => '↑ 22%',
			'type'   => __( 'Corporate Website', 'smart-leading-net' ),
		),
	);
}

/**
 * Default hero dashboard metrics (PDF visual).
 *
 * @return array<int, array<string, string>>
 */
function sln_wd_default_hero_metrics() {
	return array(
		array(
			'value' => '1.2K',
			'label' => __( 'Leads Generated', 'smart-leading-net' ),
			'note'  => __( '↑ 28% vs last month', 'smart-leading-net' ),
		),
		array(
			'value' => '5.6%',
			'label' => __( 'Conversion Rate', 'smart-leading-net' ),
			'note'  => '',
		),
		array(
			'value' => '$550K+',
			'label' => __( 'Revenue Impact', 'smart-leading-net' ),
			'note'  => '',
		),
	);
}

/**
 * Hero section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_wd_hero( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_hero();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_wd_merge_section(
		$defaults,
		sln_wd_get_meta_or_default( $post_id, SLN_WD_HERO_META, $defaults )
	);
}

/**
 * Default stats repeater.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_wd_default_stats() {
	return array(
		array(
			'prefix'  => '',
			'value'   => '120',
			'suffix'  => '+',
			'label'   => __( 'Websites built and launched', 'smart-leading-net' ),
			'icon_id' => 0,
			'active'  => true,
		),
		array(
			'prefix'  => '',
			'value'   => '92',
			'suffix'  => '%',
			'label'   => __( 'Of clients stay with us year after year', 'smart-leading-net' ),
			'icon_id' => 0,
			'active'  => true,
		),
		array(
			'prefix'  => '~',
			'value'   => '4',
			'suffix'  => '',
			'label'   => __( 'Weeks from start to a live website', 'smart-leading-net' ),
			'icon_id' => 0,
			'active'  => true,
		),
		array(
			'prefix'  => '',
			'value'   => '1',
			'suffix'  => '',
			'label'   => __( 'Dedicated person looking after your project', 'smart-leading-net' ),
			'icon_id' => 0,
			'active'  => true,
		),
	);
}

/**
 * Active stats for frontend.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_wd_stats( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_stats();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return sln_wd_filter_active_rows( $defaults, $defaults );
	}

	$stored = sln_wd_get_meta_or_default( $post_id, SLN_WD_STATS_META, $defaults );

	return sln_wd_filter_active_rows( $stored, $defaults );
}

/**
 * Default platforms section header.
 *
 * @return array<string, mixed>
 */
function sln_wd_default_platforms_section() {
	return array(
		'small_heading'    => __( 'Built on', 'smart-leading-net' ),
		'main_heading'     => __( 'Whichever Platform', 'smart-leading-net' ),
		'highlighted_text' => __( 'Fits your Business', 'smart-leading-net' ),
		'description'      => __( 'Not every business needs the same kind of website. We pick — or you choose — the right platform for your goals, then build and look after it properly.', 'smart-leading-net' ),
		'bottom_note'      => __( 'The right platform. Chosen for your goals. Built for results.', 'smart-leading-net' ),
		'active'           => true,
	);
}

/**
 * Default platform cards.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_wd_default_platforms() {
	return array(
		array(
			'title'       => __( 'WordPress', 'smart-leading-net' ),
			'description' => __( 'Flexible, content-friendly sites you can easily update yourself.', 'smart-leading-net' ),
			'icon_id'     => 0,
			'url'         => '',
			'active'      => true,
		),
		array(
			'title'       => __( 'Webflow', 'smart-leading-net' ),
			'description' => __( 'Fast, highly-designed marketing sites with pixel-perfect visuals.', 'smart-leading-net' ),
			'icon_id'     => 0,
			'url'         => '',
			'active'      => true,
		),
		array(
			'title'       => __( 'Shopify', 'smart-leading-net' ),
			'description' => __( 'Full-featured online stores built to sell, scale, and convert.', 'smart-leading-net' ),
			'icon_id'     => 0,
			'url'         => '',
			'active'      => true,
		),
		array(
			'title'       => __( 'Squarespace & Wix', 'smart-leading-net' ),
			'description' => __( 'Simple, affordable sites for smaller budgets and quick launches.', 'smart-leading-net' ),
			'icon_id'     => 0,
			'url'         => '',
			'active'      => true,
		),
		array(
			'title'       => __( 'Custom coded (HTML/React)', 'smart-leading-net' ),
			'description' => __( 'Fully bespoke, no platform limits, built for speed.', 'smart-leading-net' ),
			'icon_id'     => 0,
			'url'         => '',
			'active'      => true,
		),
		array(
			'title'       => __( 'Website & eCommerce migrations', 'smart-leading-net' ),
			'description' => __( 'Moving an existing store or site over without losing your Google ranking.', 'smart-leading-net' ),
			'icon_id'     => 0,
			'url'         => '',
			'active'      => true,
		),
	);
}

/**
 * Platforms section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_wd_platforms_section( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_platforms_section();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_wd_merge_section(
		$defaults,
		sln_wd_get_meta_or_default( $post_id, SLN_WD_PLATFORMS_SECTION_META, $defaults )
	);
}

/**
 * Active platform cards.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_wd_platforms( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_platforms();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return sln_wd_filter_active_rows( $defaults, $defaults );
	}

	$stored = sln_wd_get_meta_or_default( $post_id, SLN_WD_PLATFORMS_META, $defaults );

	return sln_wd_filter_active_rows( $stored, $defaults );
}

/**
 * Default helpful extras section.
 *
 * @return array<string, mixed>
 */
function sln_wd_default_extras_section() {
	return array(
		'heading'     => __( 'Helpful extras, whenever you need them', 'smart-leading-net' ),
		'description' => '',
		'active'      => true,
	);
}

/**
 * Default helpful extras items.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_wd_default_extras() {
	return array(
		array(
			'title'       => __( 'Tracking and analytics', 'smart-leading-net' ),
			'description' => __( 'See which pages and ads actually bring in calls and enquiries, so you spend your budget where it works.', 'smart-leading-net' ),
			'icon_id'     => 0,
			'url'         => '',
			'active'      => true,
		),
		array(
			'title'       => __( 'Mobile app support', 'smart-leading-net' ),
			'description' => __( 'Build a simple mobile app for your customers.', 'smart-leading-net' ),
			'icon_id'     => 0,
			'url'         => '',
			'active'      => true,
		),
		array(
			'title'       => __( 'Reporting', 'smart-leading-net' ),
			'description' => __( 'Clear, plain-English reports so you always know what\'s working on your site.', 'smart-leading-net' ),
			'icon_id'     => 0,
			'url'         => '',
			'active'      => true,
		),
		array(
			'title'       => __( 'Personalised website content', 'smart-leading-net' ),
			'description' => __( 'Show different visitors different messages — tailored to who\'s viewing your site.', 'smart-leading-net' ),
			'icon_id'     => 0,
			'url'         => '',
			'active'      => true,
		),
		array(
			'title'       => __( 'Integrations and automation', 'smart-leading-net' ),
			'description' => __( 'Connect your website to the other tools you already use, so information flows automatically.', 'smart-leading-net' ),
			'icon_id'     => 0,
			'url'         => '',
			'active'      => true,
		),
	);
}

/**
 * Helpful extras section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_wd_extras_section( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_extras_section();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_wd_merge_section(
		$defaults,
		sln_wd_get_meta_or_default( $post_id, SLN_WD_EXTRAS_SECTION_META, $defaults )
	);
}

/**
 * Active helpful extras items.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_wd_extras( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_extras();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return sln_wd_filter_active_rows( $defaults, $defaults );
	}

	$stored = sln_wd_get_meta_or_default( $post_id, SLN_WD_EXTRAS_META, $defaults );

	return sln_wd_filter_active_rows( $stored, $defaults );
}

/**
 * Default why-it-matters section.
 *
 * @return array<string, mixed>
 */
function sln_wd_default_matters_section() {
	return array(
		'small_heading'    => __( 'Why It Matters', 'smart-leading-net' ),
		'main_heading'     => __( 'Your website is your', 'smart-leading-net' ),
		'highlighted_text' => __( 'hardest-working employee', 'smart-leading-net' ),
		'description'      => __( 'For most customers, your website is the first impression they get of your business — and it never takes a day off. A good website is easy to find, loads quickly, looks great on a phone, and makes it simple for visitors to get in touch or buy. A tired, slow, or confusing website does the opposite: it quietly turns customers away before you ever hear from them.', 'smart-leading-net' ) . "\n\n" . __( 'We build websites that earn their keep — sites that look professional, build trust, and turn visitors into real enquiries and sales.', 'smart-leading-net' ),
		'image_id'         => 0,
		'active'           => true,
	);
}

/**
 * Default why-it-matters cards.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_wd_default_matters_cards() {
	return array(
		array(
			'title'       => __( 'Easy to Find', 'smart-leading-net' ),
			'description' => __( 'Optimised so customers can find you first.', 'smart-leading-net' ),
			'icon_id'     => 0,
			'active'      => true,
		),
		array(
			'title'       => __( 'Fast Loading', 'smart-leading-net' ),
			'description' => __( 'Speed-optimised for a better experience.', 'smart-leading-net' ),
			'icon_id'     => 0,
			'active'      => true,
		),
		array(
			'title'       => __( 'Mobile Friendly', 'smart-leading-net' ),
			'description' => __( 'Looks and works perfectly on any device.', 'smart-leading-net' ),
			'icon_id'     => 0,
			'active'      => true,
		),
		array(
			'title'       => __( 'Built to Convert', 'smart-leading-net' ),
			'description' => __( 'Designed to turn visitors into enquiries and sales.', 'smart-leading-net' ),
			'icon_id'     => 0,
			'active'      => true,
		),
	);
}

/**
 * Why-it-matters section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_wd_matters_section( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_matters_section();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_wd_merge_section(
		$defaults,
		sln_wd_get_meta_or_default( $post_id, SLN_WD_MATTERS_SECTION_META, $defaults )
	);
}

/**
 * Active why-it-matters cards.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_wd_matters_cards( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_matters_cards();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return sln_wd_filter_active_rows( $defaults, $defaults );
	}

	$stored = sln_wd_get_meta_or_default( $post_id, SLN_WD_MATTERS_CARDS_META, $defaults );

	return sln_wd_filter_active_rows( $stored, $defaults );
}

/**
 * Default process section header.
 *
 * @return array<string, mixed>
 */
function sln_wd_default_process_section() {
	return array(
		'small_heading'    => __( 'How It Works', 'smart-leading-net' ),
		'main_heading'     => __( 'From first chat to live website', 'smart-leading-net' ),
		'highlighted_text' => __( '— no surprises', 'smart-leading-net' ),
		'description'      => __( 'You always know what\'s happening, what we need from you, and when. Here\'s the journey:', 'smart-leading-net' ),
		'active'           => true,
	);
}

/**
 * Default process stages with nested steps.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_wd_default_process_stages() {
	return array(
		array(
			'number' => '01',
			'title'  => __( 'Discover', 'smart-leading-net' ),
			'steps'  => array(
				array(
					'number'      => '1',
					'title'       => __( 'A free, no-pressure chat', 'smart-leading-net' ),
					'description' => __( 'We talk through your goals, customers, and budget, and give honest advice.', 'smart-leading-net' ),
					'active'      => true,
				),
				array(
					'number'      => '2',
					'title'       => __( 'A clear quote within 24 hours', 'smart-leading-net' ),
					'description' => __( 'You get a fixed price, timeline, and no hidden fees.', 'smart-leading-net' ),
					'active'      => true,
				),
				array(
					'number'      => '3',
					'title'       => __( 'You meet your project manager', 'smart-leading-net' ),
					'description' => __( 'One friendly point of contact guides you throughout.', 'smart-leading-net' ),
					'active'      => true,
				),
			),
			'active' => true,
		),
		array(
			'number' => '02',
			'title'  => __( 'Design & Build', 'smart-leading-net' ),
			'steps'  => array(
				array(
					'number'      => '4',
					'title'       => __( 'We design it first', 'smart-leading-net' ),
					'description' => __( 'You approve the look before any build begins.', 'smart-leading-net' ),
					'active'      => true,
				),
				array(
					'number'      => '5',
					'title'       => __( 'We build it', 'smart-leading-net' ),
					'description' => __( 'We build on a private preview version and keep you updated.', 'smart-leading-net' ),
					'active'      => true,
				),
				array(
					'number'      => '6',
					'title'       => __( 'You test everything', 'smart-leading-net' ),
					'description' => __( 'You check every page, form, and device before launch.', 'smart-leading-net' ),
					'active'      => true,
				),
			),
			'active' => true,
		),
		array(
			'number' => '03',
			'title'  => __( 'Launch & Support', 'smart-leading-net' ),
			'steps'  => array(
				array(
					'number'      => '7',
					'title'       => __( 'We make your changes', 'smart-leading-net' ),
					'description' => __( 'Revisions are included, so we get every detail right.', 'smart-leading-net' ),
					'active'      => true,
				),
				array(
					'number'      => '8',
					'title'       => __( 'We launch it for you', 'smart-leading-net' ),
					'description' => __( 'We handle hosting, domain, security, and keep old links working.', 'smart-leading-net' ),
					'active'      => true,
				),
				array(
					'number'      => '9',
					'title'       => __( '30 days of free support', 'smart-leading-net' ),
					'description' => __( 'We stay close after launch while you settle into your new site.', 'smart-leading-net' ),
					'active'      => true,
				),
			),
			'active' => true,
		),
	);
}

/**
 * Process section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_wd_process_section( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_process_section();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_wd_merge_section(
		$defaults,
		sln_wd_get_meta_or_default( $post_id, SLN_WD_PROCESS_SECTION_META, $defaults )
	);
}

/**
 * Active process stages with active nested steps.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_wd_process_stages( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_process_stages();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return sln_wd_filter_active_process_stages( $defaults, $defaults );
	}

	$stored = sln_wd_get_meta_or_default( $post_id, SLN_WD_PROCESS_STAGES_META, $defaults );

	return sln_wd_filter_active_process_stages( $stored, $defaults );
}

/**
 * Default promises section header.
 *
 * @return array<string, mixed>
 */
function sln_wd_default_promises_section() {
	return array(
		'heading' => __( 'Our promise', 'smart-leading-net' ),
		'active'  => true,
	);
}

/**
 * Default promise cards.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_wd_default_promises() {
	return array(
		array(
			'icon_id'          => 0,
			'main_text'        => __( 'Most sites live in 3–6 weeks', 'smart-leading-net' ),
			'supporting_text'  => '',
			'active'           => true,
		),
		array(
			'icon_id'          => 0,
			'main_text'        => __( 'Pay in stages, never one lump sum', 'smart-leading-net' ),
			'supporting_text'  => '',
			'active'           => true,
		),
		array(
			'icon_id'          => 0,
			'main_text'        => __( 'You own all files & content', 'smart-leading-net' ),
			'supporting_text'  => '',
			'active'           => true,
		),
		array(
			'icon_id'          => 0,
			'main_text'        => __( 'We work around your hours', 'smart-leading-net' ),
			'supporting_text'  => '',
			'active'           => true,
		),
	);
}

/**
 * Promises section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_wd_promises_section( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_promises_section();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_wd_merge_section(
		$defaults,
		sln_wd_get_meta_or_default( $post_id, SLN_WD_PROMISES_SECTION_META, $defaults )
	);
}

/**
 * Active promise cards.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_wd_promises( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_promises();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return sln_wd_filter_active_rows( $defaults, $defaults );
	}

	$stored = sln_wd_get_meta_or_default( $post_id, SLN_WD_PROMISES_META, $defaults );

	return sln_wd_filter_active_rows( $stored, $defaults );
}

/**
 * Default services section header.
 *
 * @return array<string, mixed>
 */
function sln_wd_default_services_section() {
	return array(
		'small_heading' => __( 'What We Do', 'smart-leading-net' ),
		'main_heading'  => __( 'One team for the whole journey', 'smart-leading-net' ),
		'description'   => __( 'From the first idea to launch day and every day after, we cover every part of your website so you don\'t have to juggle different providers. Here\'s how we help:', 'smart-leading-net' ),
		'active'        => true,
	);
}

/**
 * Default service cards.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_wd_default_services() {
	return array(
		array(
			'title'       => __( 'Brand-new websites', 'smart-leading-net' ),
			'description' => __( 'Custom websites built from scratch around your brand, customers, and goals.', 'smart-leading-net' ),
			'icon_id'     => 0,
			'url'         => '',
			'active'      => true,
		),
		array(
			'title'       => __( 'Online stores', 'smart-leading-net' ),
			'description' => __( 'Smooth shopping experiences with clear product pages, secure checkout, and recovery tools.', 'smart-leading-net' ),
			'icon_id'     => 0,
			'url'         => '',
			'active'      => true,
		),
		array(
			'title'       => __( 'Easy to update', 'smart-leading-net' ),
			'description' => __( 'We set up simple editing tools so you can change text, images, and prices yourself.', 'smart-leading-net' ),
			'icon_id'     => 0,
			'url'         => '',
			'active'      => true,
		),
		array(
			'title'       => __( 'Refresh & redesign', 'smart-leading-net' ),
			'description' => __( 'We modernise dated websites and improve speed, usability, and conversions while protecting SEO value.', 'smart-leading-net' ),
			'icon_id'     => 0,
			'url'         => '',
			'active'      => true,
		),
		array(
			'title'       => __( 'Campaign pages', 'smart-leading-net' ),
			'description' => __( 'Focused landing pages built to turn ad traffic into enquiries and sales.', 'smart-leading-net' ),
			'icon_id'     => 0,
			'url'         => '',
			'active'      => true,
		),
		array(
			'title'       => __( 'Works on every device', 'smart-leading-net' ),
			'description' => __( 'Responsive layouts that look right on phones, tablets, laptops, and desktops.', 'smart-leading-net' ),
			'icon_id'     => 0,
			'url'         => '',
			'active'      => true,
		),
		array(
			'title'       => __( 'Ongoing website care', 'smart-leading-net' ),
			'description' => __( 'Backups, updates, quick fixes, and regular checks to keep your site fast and online.', 'smart-leading-net' ),
			'icon_id'     => 0,
			'url'         => '',
			'active'      => true,
		),
		array(
			'title'       => __( 'Security & protection', 'smart-leading-net' ),
			'description' => __( 'Protection for your website and customer data, with trust-building security signals.', 'smart-leading-net' ),
			'icon_id'     => 0,
			'url'         => '',
			'active'      => true,
		),
	);
}

/**
 * Services section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_wd_services_section( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_services_section();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_wd_merge_section(
		$defaults,
		sln_wd_get_meta_or_default( $post_id, SLN_WD_SERVICES_SECTION_META, $defaults )
	);
}

/**
 * Active service cards.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_wd_services( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_services();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return sln_wd_filter_active_rows( $defaults, $defaults );
	}

	$stored = sln_wd_get_meta_or_default( $post_id, SLN_WD_SERVICES_META, $defaults );

	return sln_wd_filter_active_rows( $stored, $defaults );
}

/**
 * Default comparison section header.
 *
 * @return array<string, mixed>
 */
function sln_wd_default_compare_section() {
	return array(
		'small_heading'    => __( 'Why Choose Us', 'smart-leading-net' ),
		'main_heading'     => __( 'The same quality — a better', 'smart-leading-net' ),
		'highlighted_text' => __( 'experience and a fairer price', 'smart-leading-net' ),
		'description'      => __( 'Here\'s how working with us compares to building a website in-house or hiring a typical agency or freelancer.', 'smart-leading-net' ),
		'col_features'     => __( 'Features', 'smart-leading-net' ),
		'col_sl'           => __( 'Smart Leading', 'smart-leading-net' ),
		'col_inhouse'      => __( 'Doing it in-house', 'smart-leading-net' ),
		'col_agency'       => __( 'Typical agency', 'smart-leading-net' ),
		'active'           => true,
	);
}

/**
 * Default comparison table rows.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_wd_default_compare_rows() {
	return array(
		array(
			'feature'       => __( 'A clear, fixed price up front', 'smart-leading-net' ),
			'smart_leading' => __( 'Always', 'smart-leading-net' ),
			'in_house'      => '— —',
			'agency'        => '— ' . __( 'Often vague', 'smart-leading-net' ),
			'active'        => true,
		),
		array(
			'feature'       => __( 'Finished in weeks, not months', 'smart-leading-net' ),
			'smart_leading' => __( 'Yes', 'smart-leading-net' ),
			'in_house'      => '− ' . __( 'Varies', 'smart-leading-net' ),
			'agency'        => '— ' . __( 'Unpredictable', 'smart-leading-net' ),
			'active'        => true,
		),
		array(
			'feature'       => __( 'You see the design before we build', 'smart-leading-net' ),
			'smart_leading' => __( 'Always', 'smart-leading-net' ),
			'in_house'      => '− ' . __( 'Sometimes', 'smart-leading-net' ),
			'agency'        => '− ' . __( 'Sometimes', 'smart-leading-net' ),
			'active'        => true,
		),
		array(
			'feature'       => __( 'You own everything we create', 'smart-leading-net' ),
			'smart_leading' => __( 'On final payment', 'smart-leading-net' ),
			'in_house'      => __( 'Yes', 'smart-leading-net' ),
			'agency'        => '− ' . __( 'Often unclear', 'smart-leading-net' ),
			'active'        => true,
		),
		array(
			'feature'       => __( 'Pay in stages, not all upfront', 'smart-leading-net' ),
			'smart_leading' => __( 'Always', 'smart-leading-net' ),
			'in_house'      => 'n/a',
			'agency'        => '− ' . __( 'Varies', 'smart-leading-net' ),
			'active'        => true,
		),
		array(
			'feature'       => __( 'One person looking after your project', 'smart-leading-net' ),
			'smart_leading' => __( 'Yes', 'smart-leading-net' ),
			'in_house'      => '− ' . __( 'Stretched thin', 'smart-leading-net' ),
			'agency'        => '− ' . __( 'Often none', 'smart-leading-net' ),
			'active'        => true,
		),
		array(
			'feature'       => __( 'We work around your hours', 'smart-leading-net' ),
			'smart_leading' => __( 'Yes', 'smart-leading-net' ),
			'in_house'      => __( 'Yes', 'smart-leading-net' ),
			'agency'        => '— ' . __( 'No guarantee', 'smart-leading-net' ),
			'active'        => true,
		),
		array(
			'feature'       => __( 'Security set up from day one', 'smart-leading-net' ),
			'smart_leading' => __( 'Always', 'smart-leading-net' ),
			'in_house'      => '— ' . __( 'Sometimes', 'smart-leading-net' ),
			'agency'        => '— ' . __( 'Extra cost', 'smart-leading-net' ),
			'active'        => true,
		),
		array(
			'feature'       => __( 'Built to be found on Google', 'smart-leading-net' ),
			'smart_leading' => __( 'Always', 'smart-leading-net' ),
			'in_house'      => '— ' . __( 'Sometimes', 'smart-leading-net' ),
			'agency'        => '— ' . __( 'Extra cost', 'smart-leading-net' ),
			'active'        => true,
		),
		array(
			'feature'       => __( 'Free support after launch', 'smart-leading-net' ),
			'smart_leading' => __( '30 days', 'smart-leading-net' ),
			'in_house'      => 'n/a',
			'agency'        => '— ' . __( 'Rarely', 'smart-leading-net' ),
			'active'        => true,
		),
	);
}

/**
 * Comparison section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_wd_compare_section( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_compare_section();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_wd_merge_section(
		$defaults,
		sln_wd_get_meta_or_default( $post_id, SLN_WD_COMPARE_SECTION_META, $defaults )
	);
}

/**
 * Active comparison table rows.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_wd_compare_rows( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_compare_rows();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return sln_wd_filter_active_rows( $defaults, $defaults );
	}

	$stored = sln_wd_get_meta_or_default( $post_id, SLN_WD_COMPARE_ROWS_META, $defaults );

	return sln_wd_filter_active_rows( $stored, $defaults );
}

/**
 * Default summary benefits section header.
 *
 * @return array<string, mixed>
 */
function sln_wd_default_benefits_section() {
	return array(
		'active' => true,
	);
}

/**
 * Default summary benefit cards.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_wd_default_benefits() {
	return array(
		array(
			'main_text'       => __( '3–6 weeks', 'smart-leading-net' ),
			'supporting_text' => __( 'Most sites live in just 3–6 weeks', 'smart-leading-net' ),
			'icon_id'         => 0,
			'active'          => true,
		),
		array(
			'main_text'       => __( 'Pay in stages', 'smart-leading-net' ),
			'supporting_text' => __( 'Never one lump sum', 'smart-leading-net' ),
			'icon_id'         => 0,
			'active'          => true,
		),
		array(
			'main_text'       => __( 'You own everything', 'smart-leading-net' ),
			'supporting_text' => __( 'All files & content are 100% yours', 'smart-leading-net' ),
			'icon_id'         => 0,
			'active'          => true,
		),
		array(
			'main_text'       => __( 'We work around you', 'smart-leading-net' ),
			'supporting_text' => __( 'Flexible support that fits your hours', 'smart-leading-net' ),
			'icon_id'         => 0,
			'active'          => true,
		),
	);
}

/**
 * Summary benefits section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_wd_benefits_section( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_benefits_section();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_wd_merge_section(
		$defaults,
		sln_wd_get_meta_or_default( $post_id, SLN_WD_BENEFITS_SECTION_META, $defaults )
	);
}

/**
 * Active summary benefit cards.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_wd_benefits( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_benefits();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return sln_wd_filter_active_rows( $defaults, $defaults );
	}

	$stored = sln_wd_get_meta_or_default( $post_id, SLN_WD_BENEFITS_META, $defaults );

	return sln_wd_filter_active_rows( $stored, $defaults );
}

/**
 * Default case studies section header.
 *
 * @return array<string, mixed>
 */
function sln_wd_default_cases_section() {
	return array(
		'small_heading' => __( 'Our Work — Case Studies', 'smart-leading-net' ),
		'main_heading'  => __( 'A few of the businesses we\'ve helped.', 'smart-leading-net' ),
		'description'   => '',
		'active'        => true,
	);
}

/**
 * Default case study cards.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_wd_default_cases() {
	return array(
		array(
			'client_name'        => __( 'USA Marble & Granite', 'smart-leading-net' ),
			'industry'           => __( 'Home remodeling', 'smart-leading-net' ),
			'metric_value'       => '64%',
			'metric_description' => __( 'more enquiry requests after an outdated, slow site became their best source of new leads.', 'smart-leading-net' ),
			'image_id'           => 0,
			'project_url'        => '',
			'active'             => true,
		),
		array(
			'client_name'        => __( 'Dän Bakery & Cafe', 'smart-leading-net' ),
			'industry'           => __( 'Local bakery & café', 'smart-leading-net' ),
			'metric_value'       => '47%',
			'metric_description' => __( 'more online visibility and bookings after we gave a much-loved local brand a website to match, ready for new locations.', 'smart-leading-net' ),
			'image_id'           => 0,
			'project_url'        => '',
			'active'             => true,
		),
		array(
			'client_name'        => __( 'NovaMed Urgent Care', 'smart-leading-net' ),
			'industry'           => __( 'Urgent care clinic', 'smart-leading-net' ),
			'metric_value'       => '56%',
			'metric_description' => __( 'more appointments booked online after a booking-driven business stopped losing enquiries.', 'smart-leading-net' ),
			'image_id'           => 0,
			'project_url'        => '',
			'active'             => true,
		),
	);
}

/**
 * Case studies section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_wd_cases_section( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_cases_section();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_wd_merge_section(
		$defaults,
		sln_wd_get_meta_or_default( $post_id, SLN_WD_CASES_SECTION_META, $defaults )
	);
}

/**
 * Active case study cards.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_wd_cases( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_cases();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return sln_wd_filter_active_rows( $defaults, $defaults );
	}

	$stored = sln_wd_get_meta_or_default( $post_id, SLN_WD_CASES_META, $defaults );

	return sln_wd_filter_active_rows( $stored, $defaults );
}

/**
 * Default pricing section header.
 *
 * @return array<string, mixed>
 */
function sln_wd_default_pricing_section() {
	return array(
		'small_heading'    => __( 'Transparent Pricing', 'smart-leading-net' ),
		'main_heading'     => __( 'Clear prices.', 'smart-leading-net' ),
		'highlighted_text' => __( 'No pushy sales calls.', 'smart-leading-net' ),
		'description'      => __( 'We tell you what things cost up front, because we\'re confident in the value. Pick the starting point that fits — we\'ll tailor the final quote to you.', 'smart-leading-net' ),
		'active'           => true,
	);
}

/**
 * Default pricing plans.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_wd_default_pricing_plans() {
	return array(
		array(
			'name'        => __( 'Starter', 'smart-leading-net' ),
			'price'       => __( 'From $1,200+', 'smart-leading-net' ),
			'timeline'    => __( '2–3 week delivery', 'smart-leading-net' ),
			'description' => __( 'A simple 5–8 page website', 'smart-leading-net' ),
			'features'    => array(
				__( 'Looks great on every device', 'smart-leading-net' ),
				__( 'Contact form and clear buttons', 'smart-leading-net' ),
				__( 'Set up to be found on Google', 'smart-leading-net' ),
				__( '30 days of free support', 'smart-leading-net' ),
			),
			'button_text' => __( 'Get Started', 'smart-leading-net' ),
			'button_url'  => '#wd-contact',
			'is_popular'  => false,
			'active'      => true,
		),
		array(
			'name'        => __( 'Growth', 'smart-leading-net' ),
			'price'       => __( 'From $2,800+', 'smart-leading-net' ),
			'timeline'    => __( '3–5 week delivery', 'smart-leading-net' ),
			'description' => __( 'A larger custom-designed site (10–20 pages)', 'smart-leading-net' ),
			'features'    => array(
				__( 'You see the design before we build', 'smart-leading-net' ),
				__( 'Set up to be found on Google', 'smart-leading-net' ),
				__( 'Simple reports so you see what\'s working', 'smart-leading-net' ),
				__( 'Pay in stages · you own everything', 'smart-leading-net' ),
			),
			'button_text' => __( 'Get Started', 'smart-leading-net' ),
			'button_url'  => '#wd-contact',
			'is_popular'  => true,
			'active'      => true,
		),
		array(
			'name'        => __( 'Online Store', 'smart-leading-net' ),
			'price'       => __( 'From $3,500+', 'smart-leading-net' ),
			'timeline'    => __( '4–6 week delivery', 'smart-leading-net' ),
			'description' => __( 'A full online shop', 'smart-leading-net' ),
			'features'    => array(
				__( 'Easy-to-manage products', 'smart-leading-net' ),
				__( 'A safe, smooth checkout', 'smart-leading-net' ),
				__( 'Everything you need to start selling', 'smart-leading-net' ),
				__( 'We show you how to run it', 'smart-leading-net' ),
			),
			'button_text' => __( 'Get Started', 'smart-leading-net' ),
			'button_url'  => '#wd-contact',
			'is_popular'  => false,
			'active'      => true,
		),
		array(
			'name'        => __( 'Custom / Larger', 'smart-leading-net' ),
			'price'       => __( 'Let\'s talk — Custom', 'smart-leading-net' ),
			'timeline'    => __( 'Timeline by scope', 'smart-leading-net' ),
			'description' => __( 'Bigger or more complex websites', 'smart-leading-net' ),
			'features'    => array(
				__( 'A tailored plan and price', 'smart-leading-net' ),
				__( 'A dedicated project manager', 'smart-leading-net' ),
				__( 'Priority support', 'smart-leading-net' ),
				__( 'Scoped together with you', 'smart-leading-net' ),
			),
			'button_text' => __( 'Let\'s Talk', 'smart-leading-net' ),
			'button_url'  => '#wd-contact',
			'is_popular'  => false,
			'active'      => true,
		),
	);
}

/**
 * Pricing section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_wd_pricing_section( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_pricing_section();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_wd_merge_section(
		$defaults,
		sln_wd_get_meta_or_default( $post_id, SLN_WD_PRICING_SECTION_META, $defaults )
	);
}

/**
 * Active pricing plans.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_wd_pricing_plans( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_pricing_plans();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return sln_wd_filter_active_rows( $defaults, $defaults );
	}

	$stored = sln_wd_get_meta_or_default( $post_id, SLN_WD_PRICING_PLANS_META, $defaults );

	return sln_wd_filter_active_rows( $stored, $defaults );
}

/**
 * Default website care section header.
 *
 * @return array<string, mixed>
 */
function sln_wd_default_care_section() {
	return array(
		'small_heading' => __( 'Website Care Plans', 'smart-leading-net' ),
		'main_heading'  => __( 'We look after it, so you don\'t have to', 'smart-leading-net' ),
		'description'   => __( 'A website needs a little regular attention to stay fast, safe, and working — much like a car needs servicing. Choose the level of care that suits you.', 'smart-leading-net' ),
		'active'        => true,
	);
}

/**
 * Default website care plans.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_wd_default_care_plans() {
	return array(
		array(
			'name'        => __( 'Essential Care', 'smart-leading-net' ),
			'price'       => __( '$79/month', 'smart-leading-net' ),
			'description' => __( 'Regular backups, security checks, and software updates, plus quick help if something goes wrong. Peace of mind that your site is protected.', 'smart-leading-net' ),
			'features'    => array(
				__( 'Regular backups', 'smart-leading-net' ),
				__( 'Security checks and software updates', 'smart-leading-net' ),
				__( 'Quick help if something goes wrong', 'smart-leading-net' ),
				__( 'Peace of mind that your site is protected', 'smart-leading-net' ),
			),
			'button_text' => __( 'Book Care', 'smart-leading-net' ),
			'button_url'  => '#wd-contact',
			'is_popular'  => false,
			'active'      => true,
		),
		array(
			'name'        => __( 'Plus Care', 'smart-leading-net' ),
			'price'       => __( '$149/month', 'smart-leading-net' ),
			'description' => __( 'Everything in Essential, plus a set number of hours each month for changes and improvements, and priority help if your site ever goes down.', 'smart-leading-net' ),
			'features'    => array(
				__( 'Everything in Essential Care', 'smart-leading-net' ),
				__( 'Monthly hours for changes and improvements', 'smart-leading-net' ),
				__( 'Priority help if your site goes down', 'smart-leading-net' ),
			),
			'button_text' => __( 'Book Care', 'smart-leading-net' ),
			'button_url'  => '#wd-contact',
			'is_popular'  => true,
			'active'      => true,
		),
		array(
			'name'        => __( 'Complete Care', 'smart-leading-net' ),
			'price'       => __( '$249/month', 'smart-leading-net' ),
			'description' => __( 'Everything in Plus, plus round-the-clock monitoring, regular check-ups, a monthly report on how your site is performing, and a monthly call with your project manager.', 'smart-leading-net' ),
			'features'    => array(
				__( 'Everything in Plus Care', 'smart-leading-net' ),
				__( 'Round-the-clock monitoring', 'smart-leading-net' ),
				__( 'Regular check-ups', 'smart-leading-net' ),
				__( 'Monthly performance report', 'smart-leading-net' ),
				__( 'Monthly call with your project manager', 'smart-leading-net' ),
			),
			'button_text' => __( 'Book Care', 'smart-leading-net' ),
			'button_url'  => '#wd-contact',
			'is_popular'  => false,
			'active'      => true,
		),
	);
}

/**
 * Website care section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_wd_care_section( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_care_section();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_wd_merge_section(
		$defaults,
		sln_wd_get_meta_or_default( $post_id, SLN_WD_CARE_SECTION_META, $defaults )
	);
}

/**
 * Active website care plans.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_wd_care_plans( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_care_plans();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return sln_wd_filter_active_rows( $defaults, $defaults );
	}

	$stored = sln_wd_get_meta_or_default( $post_id, SLN_WD_CARE_PLANS_META, $defaults );

	return sln_wd_filter_active_rows( $stored, $defaults );
}

/**
 * Default FAQ section header.
 *
 * @return array<string, mixed>
 */
function sln_wd_default_faq_section() {
	return array(
		'small_heading'    => __( 'Common Questions', 'smart-leading-net' ),
		'main_heading'     => __( 'We\'ve answered the questions', 'smart-leading-net' ),
		'highlighted_text' => __( 'we hear most', 'smart-leading-net' ),
		'description'      => __( 'Straight answers to common questions about websites, pricing, timelines, and how we work.', 'smart-leading-net' ),
		'active'           => true,
	);
}

/**
 * Default FAQ items.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_wd_default_faq_items() {
	return array(
		array(
			'question' => __( 'What\'s the difference between web design and web development?', 'smart-leading-net' ),
			'answer'   => __( 'Think of it like building a house. Design is what it looks like — the colours, layout, and style. Development is what makes it actually work — the buttons, forms, and pages all doing their job. We handle both, so you get a site that looks great and works perfectly.', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'question' => __( 'How much does a website cost?', 'smart-leading-net' ),
			'answer'   => __( 'Our starter websites begin from $1,200+ for a simple 5–8 page site, with larger custom sites from $2,800+ and online stores from $3,500+. Every project gets a fixed quote within 24 hours — no vague estimates or surprise add-ons at the end.', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'question' => __( 'How long does it take?', 'smart-leading-net' ),
			'answer'   => __( 'Most websites go live in 3–6 weeks depending on size and complexity. A starter site typically takes 2–3 weeks, larger custom sites 3–5 weeks, and online stores 4–6 weeks. You always get a clear timeline in your quote before any work begins.', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'question' => __( 'Will I own my website?', 'smart-leading-net' ),
			'answer'   => __( 'Yes. Once your final payment is made, you own all files, content, and assets we create for you. We never hold your site hostage — you can take it anywhere, anytime.', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'question' => __( 'Do I really need ongoing "website care"?', 'smart-leading-net' ),
			'answer'   => __( 'We strongly recommend it. Websites need regular updates, backups, and security checks to stay fast and safe — much like a car needs servicing. Our care plans start at $79/month and handle all of that for you, so you never worry about downtime or missed updates.', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'question' => __( 'Will my site work on phones?', 'smart-leading-net' ),
			'answer'   => __( 'Absolutely. Every site we build is fully responsive — it looks and works perfectly on phones, tablets, laptops, and desktops. Mobile-friendly design is built in from day one, not added as an afterthought.', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'question' => __( 'What does "website security" actually mean for me?', 'smart-leading-net' ),
			'answer'   => __( 'It means your site and your customers\' data are protected from hackers, malware, and downtime. We set up SSL certificates, security plugins, regular backups, and monitoring from launch day — so you and your visitors can trust your site.', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'question' => __( 'What is an "accessible" website, and do I need one?', 'smart-leading-net' ),
			'answer'   => __( 'An accessible website is one that people with disabilities can use easily — clear text, proper contrast, keyboard navigation, and screen-reader support. It\'s good practice, often a legal requirement in many industries, and it means you don\'t turn away potential customers.', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'question' => __( 'Can you redesign my current site without hurting my Google ranking?', 'smart-leading-net' ),
			'answer'   => __( 'Yes. We follow SEO-safe redesign practices — preserving your existing URLs, redirects, meta data, and content structure so your Google rankings are protected throughout the rebuild. Many clients see improved rankings after launch because the new site is faster and better structured.', 'smart-leading-net' ),
			'active'   => true,
		),
		array(
			'question' => __( 'What happens if my website goes down or gets hacked?', 'smart-leading-net' ),
			'answer'   => __( 'If you\'re on a care plan, we respond quickly — restoring from backup, fixing the issue, and getting you back online. Complete Care clients get round-the-clock monitoring so we often catch problems before you notice. Even without a care plan, we\'re available to help on an hourly basis.', 'smart-leading-net' ),
			'active'   => true,
		),
	);
}

/**
 * FAQ section settings.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_wd_faq_section( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_faq_section();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return $defaults;
	}

	return sln_wd_merge_section(
		$defaults,
		sln_wd_get_meta_or_default( $post_id, SLN_WD_FAQ_SECTION_META, $defaults )
	);
}

/**
 * Active FAQ items.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_wd_faq_items( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_faq_items();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		return sln_wd_filter_active_rows( $defaults, $defaults );
	}

	$stored = sln_wd_get_meta_or_default( $post_id, SLN_WD_FAQ_ITEMS_META, $defaults );

	return sln_wd_filter_active_rows( $stored, $defaults );
}

/**
 * Default final CTA and contact form section.
 *
 * @return array<string, mixed>
 */
function sln_wd_default_final() {
	return array(
		'small_heading'       => __( 'Contact Us', 'smart-leading-net' ),
		'main_heading'        => __( 'Ready for a website', 'smart-leading-net' ),
		'highlighted_text'    => __( 'that works?', 'smart-leading-net' ),
		'description'         => __( 'Tell us about your project. No commitment and no pressure — just honest advice and a clear, tailored quote within one business day.', 'smart-leading-net' ),
		'benefits'            => array(
			array(
				'text'   => __( 'A clear quote within 24 hours', 'smart-leading-net' ),
				'active' => true,
			),
			array(
				'text'   => __( 'A written agreement before any work begins', 'smart-leading-net' ),
				'active' => true,
			),
			array(
				'text'   => __( 'Pay in stages — never one big lump sum upfront', 'smart-leading-net' ),
				'active' => true,
			),
			array(
				'text'   => __( 'We reply during your business hours', 'smart-leading-net' ),
				'active' => true,
			),
			array(
				'text'   => __( 'You own your website and files once it\'s paid for', 'smart-leading-net' ),
				'active' => true,
			),
		),
		'form_heading'        => __( 'Send us your project', 'smart-leading-net' ),
		'name_label'          => __( 'Your name', 'smart-leading-net' ),
		'name_placeholder'    => __( 'Enter your name', 'smart-leading-net' ),
		'email_label'         => __( 'Email', 'smart-leading-net' ),
		'email_placeholder'   => __( 'Enter your email', 'smart-leading-net' ),
		'website_label'       => __( 'Your website (if you have one)', 'smart-leading-net' ),
		'website_placeholder' => __( 'https://yourwebsite.com', 'smart-leading-net' ),
		'country_label'       => __( 'Country', 'smart-leading-net' ),
		'country_placeholder' => __( 'Select your country', 'smart-leading-net' ),
		'need_label'          => __( 'What you need', 'smart-leading-net' ),
		'need_options'        => sln_wd_default_need_options(),
		'budget_label'        => __( 'Budget range', 'smart-leading-net' ),
		'budget_options'      => sln_wd_default_budget_options(),
		'message_label'       => __( 'A few words about your project', 'smart-leading-net' ),
		'message_placeholder' => __( 'Tell us about your goals, features, and any other details...', 'smart-leading-net' ),
		'submit_text'         => __( 'Send my free quote', 'smart-leading-net' ),
		'form_note'           => __( 'No spam. No commitment. We reply within 24 hours.', 'smart-leading-net' ),
		'thank_you_url'       => '/thank-you/',
		'countries'           => sln_wd_default_countries(),
		'active'              => true,
	);
}

/**
 * Final CTA and contact form section data.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<string, mixed>
 */
function sln_get_wd_final( $post_id = null ) {
	$post_id  = sln_wd_resolve_post_id( $post_id );
	$defaults = sln_wd_default_final();

	if ( ! $post_id || ! sln_wd_uses_template( $post_id ) ) {
		$data = $defaults;
	} else {
		$data = sln_wd_merge_section(
			$defaults,
			sln_wd_get_meta_or_default( $post_id, SLN_WD_FINAL_SECTION_META, $defaults )
		);
	}

	$data['benefits'] = sln_wd_filter_active_rows( $data['benefits'] ?? array(), $defaults['benefits'] );

	return $data;
}
