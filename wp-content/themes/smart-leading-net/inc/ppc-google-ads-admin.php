<?php
/**
 * PPC & Google Ads page — admin meta boxes.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build a field definition.
 *
 * @param string $key   Data key.
 * @param string $label Field label.
 * @param string $type  Field type.
 * @param array  $extra Extra definition data.
 * @return array<string, mixed>
 */
function sln_ppc_admin_field_def( $key, $label, $type = 'text', $extra = array() ) {
	return array_merge(
		array(
			'key'   => $key,
			'label' => $label,
			'type'  => $type,
		),
		$extra
	);
}

/**
 * Shared section heading fields.
 *
 * @param array<int, array> $extra Extra fields before status.
 * @return array<int, array>
 */
function sln_ppc_admin_section_heading_fields( $extra = array() ) {
	return array_merge(
		array(
			sln_ppc_admin_field_def( 'small_heading', __( 'Small Heading', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'main_heading', __( 'Main Heading', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'highlighted_text', __( 'Highlighted Text', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'description', __( 'Description', 'smart-leading-net' ), 'editor' ),
		),
		$extra,
		array(
			sln_ppc_admin_field_def( 'active', __( 'Status', 'smart-leading-net' ), 'checkbox' ),
		)
	);
}

/**
 * Register PPC & Google Ads meta boxes.
 */
function sln_ppc_register_meta_boxes() {
	$screen = get_current_screen();

	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}

	if ( ! sln_page_admin_should_register_template_boxes( 'sln_ppc_admin_is_target_page' ) ) {
		return;
	}

	$callbacks = array(
		'sln_ppc_hero'             => 'sln_ppc_render_hero_metabox',
		'sln_ppc_keyword_marquee'  => 'sln_ppc_render_keyword_marquee_metabox',
		'sln_ppc_stats'            => 'sln_ppc_render_stats_metabox',
		'sln_ppc_trust'            => 'sln_ppc_render_trust_metabox',
		'sln_ppc_reality'          => 'sln_ppc_render_reality_metabox',
		'sln_ppc_approach'         => 'sln_ppc_render_approach_metabox',
		'sln_ppc_truth'            => 'sln_ppc_render_truth_metabox',
		'sln_ppc_why'              => 'sln_ppc_render_why_metabox',
		'sln_ppc_services'         => 'sln_ppc_render_services_metabox',
		'sln_ppc_industries'       => 'sln_ppc_render_industries_metabox',
		'sln_ppc_roi'              => 'sln_ppc_render_roi_metabox',
		'sln_ppc_process'          => 'sln_ppc_render_process_metabox',
		'sln_ppc_mid_cta'          => 'sln_ppc_render_mid_cta_metabox',
		'sln_ppc_proof'            => 'sln_ppc_render_proof_metabox',
		'sln_ppc_pricing'          => 'sln_ppc_render_pricing_metabox',
		'sln_ppc_faq'              => 'sln_ppc_render_faq_metabox',
		'sln_ppc_final_cta'        => 'sln_ppc_render_final_cta_metabox',
	);

	$titles = array(
		'sln_ppc_hero'            => __( 'PPC Hero', 'smart-leading-net' ),
		'sln_ppc_keyword_marquee' => __( 'Keyword Search Marquee', 'smart-leading-net' ),
		'sln_ppc_stats'           => __( 'Performance Statistics', 'smart-leading-net' ),
		'sln_ppc_trust'           => __( 'Trust / Platforms', 'smart-leading-net' ),
		'sln_ppc_reality'         => __( 'The Reality / Budget Leakage', 'smart-leading-net' ),
		'sln_ppc_approach'        => __( 'Our Approach', 'smart-leading-net' ),
		'sln_ppc_truth'           => __( 'Quick Truth', 'smart-leading-net' ),
		'sln_ppc_why'             => __( 'Why SLS Comparison', 'smart-leading-net' ),
		'sln_ppc_services'        => __( 'PPC Services', 'smart-leading-net' ),
		'sln_ppc_industries'      => __( 'Industries', 'smart-leading-net' ),
		'sln_ppc_roi'             => __( 'ROI Estimator', 'smart-leading-net' ),
		'sln_ppc_process'         => __( 'Process', 'smart-leading-net' ),
		'sln_ppc_mid_cta'         => __( 'Mid CTA', 'smart-leading-net' ),
		'sln_ppc_proof'           => __( 'Proof of Work', 'smart-leading-net' ),
		'sln_ppc_pricing'         => __( 'Pricing', 'smart-leading-net' ),
		'sln_ppc_faq'             => __( 'FAQ', 'smart-leading-net' ),
		'sln_ppc_final_cta'       => __( 'Final CTA', 'smart-leading-net' ),
	);

	foreach ( $titles as $id => $title ) {
		add_meta_box(
			$id,
			$title,
			$callbacks[ $id ],
			'page',
			'normal',
			'default'
		);
	}
}
add_action( 'add_meta_boxes', 'sln_ppc_register_meta_boxes' );

/**
 * Enqueue admin assets on PPC & Google Ads page edit screens.
 *
 * @param string $hook Current admin hook.
 */
function sln_ppc_enqueue_admin_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	$screen = get_current_screen();

	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}

	$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$post    = $post_id ? get_post( $post_id ) : null;

	wp_enqueue_media();
	wp_enqueue_script( 'jquery-ui-sortable' );

	wp_enqueue_style(
		'sln-ppc-google-ads-admin',
		SLN_THEME_URI . '/assets/css/ppc-google-ads-admin.css',
		array(),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-ppc-google-ads-admin',
		SLN_THEME_URI . '/assets/js/ppc-google-ads-admin.js',
		array( 'jquery', 'jquery-ui-sortable' ),
		SLN_THEME_VERSION,
		true
	);

	wp_localize_script(
		'sln-ppc-google-ads-admin',
		'slnPpcGoogleAdsAdmin',
		array(
			'template'        => SLN_PPC_TEMPLATE,
			'currentTemplate' => ( $post instanceof WP_Post ) ? get_page_template_slug( $post->ID ) : '',
			'isTargetPage'    => ( $post instanceof WP_Post ) ? sln_ppc_admin_is_target_page( $post ) : false,
			'editorSettings'  => function_exists( 'sln_growth_page_get_js_editor_settings' ) ? sln_growth_page_get_js_editor_settings() : array(),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'sln_ppc_enqueue_admin_assets' );

/**
 * Render hero metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_ppc_render_hero_metabox( $post ) {
	$data    = sln_ppc_admin_get_section( $post->ID, SLN_PPC_HERO_META, sln_ppc_default_hero() );
	$queries = sln_ppc_admin_get_rows( $post->ID, SLN_PPC_HERO_SEARCH_QUERIES_META, sln_ppc_default_hero_search_queries() );
	$metrics = sln_ppc_admin_get_rows( $post->ID, SLN_PPC_HERO_METRICS_META, sln_ppc_default_hero_metrics() );
	$chart   = sln_ppc_admin_get_rows( $post->ID, SLN_PPC_HERO_CHART_META, sln_ppc_default_hero_chart() );

	sln_ppc_admin_render_fields_table(
		'sln_ppc_hero',
		$data,
		array(
			sln_ppc_admin_field_def( 'small_heading', __( 'Small Heading', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'main_heading', __( 'Main Heading', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'highlighted_text', __( 'Highlighted Text', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'description', __( 'Description', 'smart-leading-net' ), 'editor' ),
			sln_ppc_admin_field_def( 'primary_button_text', __( 'Primary Button Text', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'primary_button_url', __( 'Primary Button URL', 'smart-leading-net' ), 'url' ),
			sln_ppc_admin_field_def( 'secondary_button_text', __( 'Secondary Button Text', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'secondary_button_url', __( 'Secondary Button URL', 'smart-leading-net' ), 'url' ),
			sln_ppc_admin_field_def( 'search_label', __( 'Search Label', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'search_result_ad_label', __( 'Search Result Ad Label', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'search_result_url', __( 'Search Result URL Text', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'search_result_title', __( 'Search Result Title', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'search_result_description', __( 'Search Result Description', 'smart-leading-net' ), 'textarea' ),
			sln_ppc_admin_field_def( 'dashboard_title', __( 'Dashboard Title', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'chart_label', __( 'Chart Label', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'live_label', __( 'Live Label', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'active', __( 'Status', 'smart-leading-net' ), 'checkbox' ),
		),
		'sln-ppc-hero'
	);

	sln_ppc_admin_render_repeater(
		array(
			'title'              => __( 'Search Queries', 'smart-leading-net' ),
			'name_prefix'        => 'sln_ppc_hero_search_queries',
			'row_class'          => 'sln-ppc-admin__hero-query-row',
			'rows'               => $queries,
			'row_title_key'      => 'query',
			'row_title_fallback' => __( 'Search Query', 'smart-leading-net' ),
			'add_label'          => __( 'Add Search Query', 'smart-leading-net' ),
			'fields'             => array(
				sln_ppc_admin_field_def( 'query', __( 'Query', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'active', __( 'Status', 'smart-leading-net' ), 'checkbox' ),
			),
		)
	);

	sln_ppc_admin_render_repeater(
		array(
			'title'              => __( 'Hero Metrics', 'smart-leading-net' ),
			'name_prefix'        => 'sln_ppc_hero_metrics',
			'row_class'          => 'sln-ppc-admin__hero-metric-row',
			'rows'               => $metrics,
			'row_title_key'      => 'label',
			'row_title_fallback' => __( 'Hero Metric', 'smart-leading-net' ),
			'add_label'          => __( 'Add Hero Metric', 'smart-leading-net' ),
			'fields'             => array(
				sln_ppc_admin_field_def( 'label', __( 'Label', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'prefix', __( 'Prefix', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'value', __( 'Value', 'smart-leading-net' ), 'number', array( 'step' => '0.01' ) ),
				sln_ppc_admin_field_def( 'decimals', __( 'Decimals', 'smart-leading-net' ), 'number' ),
				sln_ppc_admin_field_def( 'suffix', __( 'Suffix', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'display_value', __( 'Display Value', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'visual_style', __( 'Visual Style', 'smart-leading-net' ), 'select', array( 'options' => sln_ppc_admin_visual_style_options() ) ),
				sln_ppc_admin_field_def( 'active', __( 'Status', 'smart-leading-net' ), 'checkbox' ),
			),
		)
	);

	sln_ppc_admin_render_repeater(
		array(
			'title'              => __( 'Chart Bars', 'smart-leading-net' ),
			'name_prefix'        => 'sln_ppc_hero_chart',
			'row_class'          => 'sln-ppc-admin__hero-chart-row',
			'rows'               => $chart,
			'row_title_key'      => 'label',
			'row_title_fallback' => __( 'Chart Bar', 'smart-leading-net' ),
			'add_label'          => __( 'Add Chart Bar', 'smart-leading-net' ),
			'fields'             => array(
				sln_ppc_admin_field_def( 'height', __( 'Height Percent', 'smart-leading-net' ), 'number' ),
				sln_ppc_admin_field_def( 'label', __( 'Label', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'active', __( 'Status', 'smart-leading-net' ), 'checkbox' ),
			),
		)
	);
}

/**
 * Render keyword marquee metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_ppc_render_keyword_marquee_metabox( $post ) {
	$rows = sln_ppc_admin_get_rows( $post->ID, SLN_PPC_KEYWORD_MARQUEE_META, sln_ppc_default_keyword_marquee() );

	sln_ppc_admin_render_repeater(
		array(
			'title'              => __( 'Keywords', 'smart-leading-net' ),
			'name_prefix'        => 'sln_ppc_keyword_marquee',
			'row_class'          => 'sln-ppc-admin__keyword-row',
			'rows'               => $rows,
			'row_title_key'      => 'keyword',
			'row_title_fallback' => __( 'Keyword', 'smart-leading-net' ),
			'add_label'          => __( 'Add Keyword', 'smart-leading-net' ),
			'fields'             => array(
				sln_ppc_admin_field_def( 'keyword', __( 'Keyword', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'icon_text', __( 'Icon Text', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'active', __( 'Status', 'smart-leading-net' ), 'checkbox' ),
			),
		)
	);
}

/**
 * Render statistics metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_ppc_render_stats_metabox( $post ) {
	$rows = sln_ppc_admin_get_rows( $post->ID, SLN_PPC_STATS_META, sln_ppc_default_stats() );

	sln_ppc_admin_render_repeater(
		array(
			'title'              => __( 'Statistics', 'smart-leading-net' ),
			'name_prefix'        => 'sln_ppc_stats',
			'row_class'          => 'sln-ppc-admin__stat-row',
			'rows'               => $rows,
			'row_title_key'      => 'label',
			'row_title_fallback' => __( 'Statistic', 'smart-leading-net' ),
			'add_label'          => __( 'Add Statistic', 'smart-leading-net' ),
			'fields'             => array(
				sln_ppc_admin_field_def( 'prefix', __( 'Prefix', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'number', __( 'Number', 'smart-leading-net' ), 'number', array( 'step' => '0.01' ) ),
				sln_ppc_admin_field_def( 'decimals', __( 'Decimals', 'smart-leading-net' ), 'number' ),
				sln_ppc_admin_field_def( 'suffix', __( 'Suffix', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'unit', __( 'Unit', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'display_value', __( 'Display Value', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'label', __( 'Label', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'active', __( 'Status', 'smart-leading-net' ), 'checkbox' ),
			),
		)
	);
}

/**
 * Render trust / platforms metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_ppc_render_trust_metabox( $post ) {
	$section   = sln_ppc_admin_get_section( $post->ID, SLN_PPC_TRUST_SECTION_META, sln_ppc_default_trust_section() );
	$platforms = sln_ppc_admin_get_rows( $post->ID, SLN_PPC_TRUST_PLATFORMS_META, sln_ppc_default_trust_platforms() );

	sln_ppc_admin_render_fields_table(
		'sln_ppc_trust_section',
		$section,
		array(
			sln_ppc_admin_field_def( 'label', __( 'Label', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'active', __( 'Status', 'smart-leading-net' ), 'checkbox' ),
		),
		'sln-ppc-trust'
	);

	sln_ppc_admin_render_repeater(
		array(
			'title'              => __( 'Platforms', 'smart-leading-net' ),
			'name_prefix'        => 'sln_ppc_trust_platforms',
			'row_class'          => 'sln-ppc-admin__trust-platform-row',
			'rows'               => $platforms,
			'row_title_key'      => 'name',
			'row_title_fallback' => __( 'Platform', 'smart-leading-net' ),
			'add_label'          => __( 'Add Platform', 'smart-leading-net' ),
			'fields'             => array(
				sln_ppc_admin_field_def( 'name', __( 'Name', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'active', __( 'Status', 'smart-leading-net' ), 'checkbox' ),
			),
		)
	);
}

/**
 * Render reality / budget leakage metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_ppc_render_reality_metabox( $post ) {
	$section    = sln_ppc_admin_get_section( $post->ID, SLN_PPC_REALITY_SECTION_META, sln_ppc_default_reality_section() );
	$budget     = sln_ppc_admin_get_section( $post->ID, SLN_PPC_REALITY_BUDGET_META, sln_ppc_default_reality_budget() );
	$challenges = sln_ppc_admin_get_rows( $post->ID, SLN_PPC_REALITY_CHALLENGES_META, sln_ppc_default_reality_challenges() );

	sln_ppc_admin_render_fields_table(
		'sln_ppc_reality_section',
		$section,
		sln_ppc_admin_section_heading_fields(
			array(
				sln_ppc_admin_field_def( 'bottom_note', __( 'Bottom Note', 'smart-leading-net' ), 'editor' ),
			)
		),
		'sln-ppc-reality'
	);

	echo '<h3>' . esc_html__( 'Budget Leakage Panel', 'smart-leading-net' ) . '</h3>';
	sln_ppc_admin_render_fields_table(
		'sln_ppc_reality_budget',
		$budget,
		array(
			sln_ppc_admin_field_def( 'lead_text', __( 'Lead Text', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'waste_percent', __( 'Waste Percent', 'smart-leading-net' ), 'number' ),
			sln_ppc_admin_field_def( 'working_percent', __( 'Working Percent', 'smart-leading-net' ), 'number' ),
			sln_ppc_admin_field_def( 'waste_big_text', __( 'Waste Big Text', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'flip_text', __( 'Flip Text', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'flip_highlight', __( 'Flip Highlight', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'wasted_label', __( 'Wasted Label', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'working_label', __( 'Working Label', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'caption', __( 'Caption', 'smart-leading-net' ), 'textarea' ),
			sln_ppc_admin_field_def( 'active', __( 'Status', 'smart-leading-net' ), 'checkbox' ),
		),
		'sln-ppc-reality-budget'
	);

	sln_ppc_admin_render_repeater(
		array(
			'title'              => __( 'Challenges', 'smart-leading-net' ),
			'name_prefix'        => 'sln_ppc_reality_challenges',
			'row_class'          => 'sln-ppc-admin__reality-challenge-row',
			'rows'               => $challenges,
			'row_title_key'      => 'title',
			'row_title_fallback' => __( 'Challenge', 'smart-leading-net' ),
			'add_label'          => __( 'Add Challenge', 'smart-leading-net' ),
			'fields'             => array(
				sln_ppc_admin_field_def( 'icon_text', __( 'Icon Text', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'title', __( 'Title', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'description', __( 'Description', 'smart-leading-net' ), 'textarea' ),
				sln_ppc_admin_field_def( 'impact', __( 'Impact', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'active', __( 'Status', 'smart-leading-net' ), 'checkbox' ),
			),
		)
	);
}

/**
 * Render approach metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_ppc_render_approach_metabox( $post ) {
	$section = sln_ppc_admin_get_section( $post->ID, SLN_PPC_APPROACH_SECTION_META, sln_ppc_default_approach_section() );
	$items   = sln_ppc_admin_get_rows( $post->ID, SLN_PPC_APPROACH_ITEMS_META, sln_ppc_default_approach_items() );

	sln_ppc_admin_render_fields_table( 'sln_ppc_approach_section', $section, sln_ppc_admin_section_heading_fields(), 'sln-ppc-approach' );

	sln_ppc_admin_render_repeater(
		array(
			'title'              => __( 'Problem / Solution Items', 'smart-leading-net' ),
			'name_prefix'        => 'sln_ppc_approach_items',
			'row_class'          => 'sln-ppc-admin__approach-row',
			'rows'               => $items,
			'row_title_key'      => 'problem',
			'row_title_fallback' => __( 'Approach Item', 'smart-leading-net' ),
			'add_label'          => __( 'Add Item', 'smart-leading-net' ),
			'fields'             => array(
				sln_ppc_admin_field_def( 'problem', __( 'Problem', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'solution', __( 'Solution', 'smart-leading-net' ), 'textarea' ),
				sln_ppc_admin_field_def( 'active', __( 'Status', 'smart-leading-net' ), 'checkbox' ),
			),
		)
	);
}

/**
 * Render quick truth metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_ppc_render_truth_metabox( $post ) {
	$section = sln_ppc_admin_get_section( $post->ID, SLN_PPC_TRUTH_SECTION_META, sln_ppc_default_truth_section() );

	sln_ppc_admin_render_fields_table(
		'sln_ppc_truth_section',
		$section,
		array(
			sln_ppc_admin_field_def( 'statement', __( 'Statement', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'highlighted_text', __( 'Highlighted Text', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'body', __( 'Body', 'smart-leading-net' ), 'editor' ),
			sln_ppc_admin_field_def( 'quote', __( 'Quote', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'quote_highlight', __( 'Quote Highlight', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'attribution', __( 'Attribution', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'button_text', __( 'Button Text', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'button_url', __( 'Button URL', 'smart-leading-net' ), 'url' ),
			sln_ppc_admin_field_def( 'active', __( 'Status', 'smart-leading-net' ), 'checkbox' ),
		),
		'sln-ppc-truth'
	);
}

/**
 * Render why SLS comparison metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_ppc_render_why_metabox( $post ) {
	$section    = sln_ppc_admin_get_section( $post->ID, SLN_PPC_WHY_SECTION_META, sln_ppc_default_why_section() );
	$comparison = sln_ppc_admin_get_rows( $post->ID, SLN_PPC_WHY_COMPARISON_META, sln_ppc_default_why_comparison() );
	$badges     = sln_ppc_admin_get_rows( $post->ID, SLN_PPC_WHY_BADGES_META, sln_ppc_default_why_badges() );

	sln_ppc_admin_render_fields_table(
		'sln_ppc_why_section',
		$section,
		sln_ppc_admin_section_heading_fields(
			array(
				sln_ppc_admin_field_def( 'left_heading', __( 'Left Heading', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'right_heading', __( 'Right Heading', 'smart-leading-net' ) ),
			)
		),
		'sln-ppc-why'
	);

	sln_ppc_admin_render_repeater(
		array(
			'title'              => __( 'Comparison Rows', 'smart-leading-net' ),
			'name_prefix'        => 'sln_ppc_why_comparison',
			'row_class'          => 'sln-ppc-admin__why-comparison-row',
			'rows'               => $comparison,
			'row_title_key'      => 'typical',
			'row_title_fallback' => __( 'Comparison Row', 'smart-leading-net' ),
			'add_label'          => __( 'Add Comparison Row', 'smart-leading-net' ),
			'fields'             => array(
				sln_ppc_admin_field_def( 'typical', __( 'Typical Agency', 'smart-leading-net' ), 'textarea' ),
				sln_ppc_admin_field_def( 'sls', __( 'Smart Leading Solutions', 'smart-leading-net' ), 'textarea' ),
				sln_ppc_admin_field_def( 'active', __( 'Status', 'smart-leading-net' ), 'checkbox' ),
			),
		)
	);

	sln_ppc_admin_render_repeater(
		array(
			'title'              => __( 'Badges', 'smart-leading-net' ),
			'name_prefix'        => 'sln_ppc_why_badges',
			'row_class'          => 'sln-ppc-admin__why-badge-row',
			'rows'               => $badges,
			'row_title_key'      => 'text',
			'row_title_fallback' => __( 'Badge', 'smart-leading-net' ),
			'add_label'          => __( 'Add Badge', 'smart-leading-net' ),
			'fields'             => array(
				sln_ppc_admin_field_def( 'text', __( 'Text', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'active', __( 'Status', 'smart-leading-net' ), 'checkbox' ),
			),
		)
	);
}

/**
 * Render PPC services metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_ppc_render_services_metabox( $post ) {
	$section = sln_ppc_admin_get_section( $post->ID, SLN_PPC_SERVICES_SECTION_META, sln_ppc_default_services_section() );
	$items   = sln_ppc_admin_get_rows( $post->ID, SLN_PPC_SERVICES_ITEMS_META, sln_ppc_default_services_items() );

	sln_ppc_admin_render_fields_table( 'sln_ppc_services_section', $section, sln_ppc_admin_section_heading_fields(), 'sln-ppc-services' );

	sln_ppc_admin_render_repeater(
		array(
			'title'              => __( 'Services', 'smart-leading-net' ),
			'name_prefix'        => 'sln_ppc_services_items',
			'row_class'          => 'sln-ppc-admin__service-row',
			'rows'               => $items,
			'row_title_key'      => 'title',
			'row_title_fallback' => __( 'Service', 'smart-leading-net' ),
			'add_label'          => __( 'Add Service', 'smart-leading-net' ),
			'fields'             => array(
				sln_ppc_admin_field_def( 'icon_key', __( 'Icon Key', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'icon_text', __( 'Icon Text', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'icon_style', __( 'Icon Style', 'smart-leading-net' ), 'select', array( 'options' => sln_ppc_admin_visual_style_options() ) ),
				sln_ppc_admin_field_def( 'tag', __( 'Tag', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'title', __( 'Title', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'description', __( 'Description', 'smart-leading-net' ), 'textarea' ),
				sln_ppc_admin_field_def( 'active', __( 'Status', 'smart-leading-net' ), 'checkbox' ),
			),
		)
	);
}

/**
 * Render industries metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_ppc_render_industries_metabox( $post ) {
	$section = sln_ppc_admin_get_section( $post->ID, SLN_PPC_INDUSTRIES_SECTION_META, sln_ppc_default_industries_section() );
	$items   = sln_ppc_admin_get_rows( $post->ID, SLN_PPC_INDUSTRIES_ITEMS_META, sln_ppc_default_industries_items() );

	sln_ppc_admin_render_fields_table( 'sln_ppc_industries_section', $section, sln_ppc_admin_section_heading_fields(), 'sln-ppc-industries' );

	sln_ppc_admin_render_repeater(
		array(
			'title'              => __( 'Industries', 'smart-leading-net' ),
			'name_prefix'        => 'sln_ppc_industries_items',
			'row_class'          => 'sln-ppc-admin__industry-row',
			'rows'               => $items,
			'row_title_key'      => 'title',
			'row_title_fallback' => __( 'Industry', 'smart-leading-net' ),
			'add_label'          => __( 'Add Industry', 'smart-leading-net' ),
			'fields'             => array(
				sln_ppc_admin_field_def( 'icon_text', __( 'Icon Text', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'title', __( 'Title', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'description', __( 'Description', 'smart-leading-net' ), 'textarea' ),
				sln_ppc_admin_field_def( 'active', __( 'Status', 'smart-leading-net' ), 'checkbox' ),
			),
		)
	);
}

/**
 * Render ROI estimator metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_ppc_render_roi_metabox( $post ) {
	$section  = sln_ppc_admin_get_section( $post->ID, SLN_PPC_ROI_SECTION_META, sln_ppc_default_roi_section() );
	$controls = sln_ppc_admin_get_rows( $post->ID, SLN_PPC_ROI_CONTROLS_META, sln_ppc_default_roi_controls() );
	$outputs  = sln_ppc_admin_get_rows( $post->ID, SLN_PPC_ROI_OUTPUTS_META, sln_ppc_default_roi_outputs() );

	sln_ppc_admin_render_fields_table(
		'sln_ppc_roi_section',
		$section,
		sln_ppc_admin_section_heading_fields(
			array(
				sln_ppc_admin_field_def( 'assumption_text', __( 'Assumption Text', 'smart-leading-net' ), 'textarea' ),
				sln_ppc_admin_field_def( 'cpc', __( 'Average CPC', 'smart-leading-net' ), 'number', array( 'step' => '0.01' ) ),
				sln_ppc_admin_field_def( 'cvr', __( 'Conversion Rate', 'smart-leading-net' ), 'number', array( 'step' => '0.001' ) ),
				sln_ppc_admin_field_def( 'disclaimer', __( 'Disclaimer', 'smart-leading-net' ), 'textarea' ),
			)
		),
		'sln-ppc-roi'
	);

	sln_ppc_admin_render_repeater(
		array(
			'title'              => __( 'Controls', 'smart-leading-net' ),
			'name_prefix'        => 'sln_ppc_roi_controls',
			'row_class'          => 'sln-ppc-admin__roi-control-row',
			'rows'               => $controls,
			'row_title_key'      => 'label',
			'row_title_fallback' => __( 'Control', 'smart-leading-net' ),
			'add_label'          => __( 'Add Control', 'smart-leading-net' ),
			'fields'             => array(
				sln_ppc_admin_field_def( 'key', __( 'Key', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'label', __( 'Label', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'min', __( 'Min', 'smart-leading-net' ), 'number', array( 'step' => '0.01' ) ),
				sln_ppc_admin_field_def( 'max', __( 'Max', 'smart-leading-net' ), 'number', array( 'step' => '0.01' ) ),
				sln_ppc_admin_field_def( 'step', __( 'Step', 'smart-leading-net' ), 'number', array( 'step' => '0.01' ) ),
				sln_ppc_admin_field_def( 'default', __( 'Default', 'smart-leading-net' ), 'number', array( 'step' => '0.01' ) ),
				sln_ppc_admin_field_def( 'display_value', __( 'Display Value', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'prefix', __( 'Prefix', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'suffix', __( 'Suffix', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'active', __( 'Status', 'smart-leading-net' ), 'checkbox' ),
			),
		)
	);

	sln_ppc_admin_render_repeater(
		array(
			'title'              => __( 'Outputs', 'smart-leading-net' ),
			'name_prefix'        => 'sln_ppc_roi_outputs',
			'row_class'          => 'sln-ppc-admin__roi-output-row',
			'rows'               => $outputs,
			'row_title_key'      => 'label',
			'row_title_fallback' => __( 'Output', 'smart-leading-net' ),
			'add_label'          => __( 'Add Output', 'smart-leading-net' ),
			'fields'             => array(
				sln_ppc_admin_field_def( 'key', __( 'Key', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'label', __( 'Label', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'calc_type', __( 'Calculation Type', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'display_value', __( 'Display Value', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'prefix', __( 'Prefix', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'suffix', __( 'Suffix', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'visual_style', __( 'Visual Style', 'smart-leading-net' ), 'select', array( 'options' => sln_ppc_admin_visual_style_options() ) ),
				sln_ppc_admin_field_def( 'highlight', __( 'Highlight', 'smart-leading-net' ), 'checkbox', array( 'text' => __( 'Highlight output', 'smart-leading-net' ) ) ),
				sln_ppc_admin_field_def( 'active', __( 'Status', 'smart-leading-net' ), 'checkbox' ),
			),
		)
	);
}

/**
 * Render process metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_ppc_render_process_metabox( $post ) {
	$section = sln_ppc_admin_get_section( $post->ID, SLN_PPC_PROCESS_SECTION_META, sln_ppc_default_process_section() );
	$steps   = sln_ppc_admin_get_rows( $post->ID, SLN_PPC_PROCESS_STEPS_META, sln_ppc_default_process_steps() );

	sln_ppc_admin_render_fields_table(
		'sln_ppc_process_section',
		$section,
		sln_ppc_admin_section_heading_fields(
			array(
				sln_ppc_admin_field_def( 'bottom_note', __( 'Bottom Note', 'smart-leading-net' ), 'textarea' ),
			)
		),
		'sln-ppc-process'
	);

	sln_ppc_admin_render_repeater(
		array(
			'title'              => __( 'Steps', 'smart-leading-net' ),
			'name_prefix'        => 'sln_ppc_process_steps',
			'row_class'          => 'sln-ppc-admin__process-step-row',
			'rows'               => $steps,
			'row_title_key'      => 'title',
			'row_title_fallback' => __( 'Process Step', 'smart-leading-net' ),
			'add_label'          => __( 'Add Step', 'smart-leading-net' ),
			'fields'             => array(
				sln_ppc_admin_field_def( 'number', __( 'Number', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'title', __( 'Title', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'bullets', __( 'Bullets', 'smart-leading-net' ), 'bullets' ),
				sln_ppc_admin_field_def( 'active', __( 'Status', 'smart-leading-net' ), 'checkbox' ),
			),
		)
	);
}

/**
 * Render mid CTA metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_ppc_render_mid_cta_metabox( $post ) {
	$data = sln_ppc_admin_get_section( $post->ID, SLN_PPC_MID_CTA_META, sln_ppc_default_mid_cta() );

	sln_ppc_admin_render_fields_table(
		'sln_ppc_mid_cta',
		$data,
		array(
			sln_ppc_admin_field_def( 'heading', __( 'Heading', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'description', __( 'Description', 'smart-leading-net' ), 'editor' ),
			sln_ppc_admin_field_def( 'button_text', __( 'Button Text', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'button_url', __( 'Button URL', 'smart-leading-net' ), 'url' ),
			sln_ppc_admin_field_def( 'active', __( 'Status', 'smart-leading-net' ), 'checkbox' ),
		),
		'sln-ppc-mid-cta'
	);
}

/**
 * Render proof of work metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_ppc_render_proof_metabox( $post ) {
	$section = sln_ppc_admin_get_section( $post->ID, SLN_PPC_PROOF_SECTION_META, sln_ppc_default_proof_section() );
	$studies = sln_ppc_admin_get_rows( $post->ID, SLN_PPC_CASE_STUDIES_META, sln_ppc_default_case_studies() );

	sln_ppc_admin_render_fields_table(
		'sln_ppc_proof_section',
		$section,
		sln_ppc_admin_section_heading_fields(
			array(
				sln_ppc_admin_field_def( 'disclaimer', __( 'Disclaimer', 'smart-leading-net' ), 'textarea' ),
			)
		),
		'sln-ppc-proof'
	);

	sln_ppc_admin_render_repeater(
		array(
			'title'              => __( 'Case Studies', 'smart-leading-net' ),
			'name_prefix'        => 'sln_ppc_case_studies',
			'row_class'          => 'sln-ppc-admin__case-study-row',
			'rows'               => $studies,
			'row_title_key'      => 'name',
			'row_title_fallback' => __( 'Case Study', 'smart-leading-net' ),
			'add_label'          => __( 'Add Case Study', 'smart-leading-net' ),
			'fields'             => array(
				sln_ppc_admin_field_def( 'name', __( 'Name', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'tag', __( 'Tag', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'metrics', __( 'Metrics', 'smart-leading-net' ), 'case_metrics' ),
				sln_ppc_admin_field_def( 'progress', __( 'Progress', 'smart-leading-net' ), 'progress' ),
				sln_ppc_admin_field_def( 'quote', __( 'Quote', 'smart-leading-net' ), 'textarea' ),
				sln_ppc_admin_field_def( 'attribution', __( 'Attribution', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'active', __( 'Status', 'smart-leading-net' ), 'checkbox' ),
			),
		)
	);
}

/**
 * Render pricing metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_ppc_render_pricing_metabox( $post ) {
	$section = sln_ppc_admin_get_section( $post->ID, SLN_PPC_PRICING_SECTION_META, sln_ppc_default_pricing_section() );
	$plans   = sln_ppc_admin_get_rows( $post->ID, SLN_PPC_PRICING_PLANS_META, sln_ppc_default_pricing_plans() );

	sln_ppc_admin_render_fields_table(
		'sln_ppc_pricing_section',
		$section,
		sln_ppc_admin_section_heading_fields(
			array(
				sln_ppc_admin_field_def( 'bottom_note', __( 'Bottom Note', 'smart-leading-net' ), 'editor' ),
			)
		),
		'sln-ppc-pricing'
	);

	sln_ppc_admin_render_repeater(
		array(
			'title'              => __( 'Pricing Plans', 'smart-leading-net' ),
			'name_prefix'        => 'sln_ppc_pricing_plans',
			'row_class'          => 'sln-ppc-admin__pricing-plan-row',
			'rows'               => $plans,
			'row_title_key'      => 'name',
			'row_title_fallback' => __( 'Pricing Plan', 'smart-leading-net' ),
			'add_label'          => __( 'Add Pricing Plan', 'smart-leading-net' ),
			'fields'             => array(
				sln_ppc_admin_field_def( 'name', __( 'Name', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'tagline', __( 'Tagline', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'price', __( 'Price', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'price_suffix', __( 'Price Suffix', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'spend', __( 'Spend', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'is_popular', __( 'Most Popular', 'smart-leading-net' ), 'checkbox', array( 'text' => __( 'Mark as most popular', 'smart-leading-net' ) ) ),
				sln_ppc_admin_field_def( 'popular_badge', __( 'Popular Badge', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'features', __( 'Features', 'smart-leading-net' ), 'features' ),
				sln_ppc_admin_field_def( 'button_text', __( 'Button Text', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'button_url', __( 'Button URL', 'smart-leading-net' ), 'url' ),
				sln_ppc_admin_field_def( 'button_style', __( 'Button Style', 'smart-leading-net' ), 'select', array( 'options' => sln_ppc_admin_button_style_options() ) ),
				sln_ppc_admin_field_def( 'active', __( 'Status', 'smart-leading-net' ), 'checkbox' ),
			),
		)
	);
}

/**
 * Render FAQ metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_ppc_render_faq_metabox( $post ) {
	$section = sln_ppc_admin_get_section( $post->ID, SLN_PPC_FAQ_SECTION_META, sln_ppc_default_faq_section() );
	$items   = sln_ppc_admin_get_rows( $post->ID, SLN_PPC_FAQ_ITEMS_META, sln_ppc_default_faq_items() );

	sln_ppc_admin_render_fields_table( 'sln_ppc_faq_section', $section, sln_ppc_admin_section_heading_fields(), 'sln-ppc-faq' );

	sln_ppc_admin_render_repeater(
		array(
			'title'              => __( 'FAQ Items', 'smart-leading-net' ),
			'name_prefix'        => 'sln_ppc_faq_items',
			'row_class'          => 'sln-ppc-admin__faq-row',
			'rows'               => $items,
			'row_title_key'      => 'question',
			'row_title_fallback' => __( 'FAQ Item', 'smart-leading-net' ),
			'add_label'          => __( 'Add FAQ Item', 'smart-leading-net' ),
			'fields'             => array(
				sln_ppc_admin_field_def( 'question', __( 'Question', 'smart-leading-net' ) ),
				sln_ppc_admin_field_def( 'answer', __( 'Answer', 'smart-leading-net' ), 'textarea' ),
				sln_ppc_admin_field_def( 'active', __( 'Status', 'smart-leading-net' ), 'checkbox' ),
			),
		)
	);
}

/**
 * Render final CTA metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_ppc_render_final_cta_metabox( $post ) {
	$data = sln_ppc_admin_get_section( $post->ID, SLN_PPC_FINAL_CTA_META, sln_ppc_default_final_cta() );

	sln_ppc_admin_render_fields_table(
		'sln_ppc_final_cta',
		$data,
		array(
			sln_ppc_admin_field_def( 'small_heading', __( 'Small Heading', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'main_heading', __( 'Main Heading', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'highlighted_text', __( 'Highlighted Text', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'description', __( 'Description', 'smart-leading-net' ), 'editor' ),
			sln_ppc_admin_field_def( 'primary_button_text', __( 'Primary Button Text', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'primary_button_url', __( 'Primary Button URL', 'smart-leading-net' ), 'url' ),
			sln_ppc_admin_field_def( 'secondary_button_text', __( 'Secondary Button Text', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'secondary_button_url', __( 'Secondary Button URL', 'smart-leading-net' ), 'url' ),
			sln_ppc_admin_field_def( 'website_label', __( 'Website Label', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'website_text', __( 'Website Text', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'website_url', __( 'Website URL', 'smart-leading-net' ), 'url' ),
			sln_ppc_admin_field_def( 'bottom_note', __( 'Bottom Note', 'smart-leading-net' ) ),
			sln_ppc_admin_field_def( 'active', __( 'Status', 'smart-leading-net' ), 'checkbox' ),
		),
		'sln-ppc-final-cta'
	);
}
