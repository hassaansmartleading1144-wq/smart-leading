<?php
/**
 * PPC & Google Ads page — save handlers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register save hook for PPC & Google Ads pages.
 */
function sln_ppc_register_save_hooks() {
	add_action( 'save_post_page', 'sln_ppc_save_meta', 10, 2 );
}
add_action( 'init', 'sln_ppc_register_save_hooks', 20 );

/**
 * Output master save nonce after title.
 *
 * @param WP_Post $post Current post.
 */
function sln_ppc_output_save_nonce( $post ) {
	if ( ! $post instanceof WP_Post || 'page' !== $post->post_type ) {
		return;
	}

	// Always output on page screens so the first save after selecting
	// the PPC & Google Ads template can persist fields.
	wp_nonce_field( 'sln_ppc_save_meta', 'sln_ppc_master_nonce', false );
}
add_action( 'edit_form_after_title', 'sln_ppc_output_save_nonce' );

/**
 * Whether save should proceed.
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function sln_ppc_should_save_meta( $post_id ) {
	$post_id = absint( $post_id );

	if ( ! $post_id || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
		return false;
	}

	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return false;
	}

	if ( 'page' !== get_post_type( $post_id ) ) {
		return false;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return false;
	}

	if ( ! isset( $_POST['sln_ppc_master_nonce'] ) ) {
		return false;
	}

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sln_ppc_master_nonce'] ) ), 'sln_ppc_save_meta' ) ) {
		return false;
	}

	$template = isset( $_POST['page_template'] )
		? sanitize_text_field( wp_unslash( $_POST['page_template'] ) )
		: get_page_template_slug( $post_id );

	return SLN_PPC_TEMPLATE === $template;
}

/**
 * Save all PPC & Google Ads meta.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 */
function sln_ppc_save_meta( $post_id, $post ) {
	unset( $post );

	if ( ! sln_ppc_should_save_meta( $post_id ) ) {
		return;
	}

	sln_ppc_save_hero_meta( $post_id );
	sln_ppc_save_keyword_marquee_meta( $post_id );
	sln_ppc_save_stats_meta( $post_id );
	sln_ppc_save_trust_meta( $post_id );
	sln_ppc_save_reality_meta( $post_id );
	sln_ppc_save_approach_meta( $post_id );
	sln_ppc_save_truth_meta( $post_id );
	sln_ppc_save_why_meta( $post_id );
	sln_ppc_save_services_meta( $post_id );
	sln_ppc_save_industries_meta( $post_id );
	sln_ppc_save_roi_meta( $post_id );
	sln_ppc_save_process_meta( $post_id );
	sln_ppc_save_mid_cta_meta( $post_id );
	sln_ppc_save_proof_meta( $post_id );
	sln_ppc_save_pricing_meta( $post_id );
	sln_ppc_save_faq_meta( $post_id );
	sln_ppc_save_final_cta_meta( $post_id );
}

/**
 * Persist associative section when posted.
 *
 * @param int      $post_id  Post ID.
 * @param string   $meta_key Meta key.
 * @param string   $post_key POST key.
 * @param callable $sanitize Sanitizer callback.
 */
function sln_ppc_save_section_meta( $post_id, $meta_key, $post_key, $sanitize ) {
	if ( ! isset( $_POST[ $post_key ] ) || ! is_array( $_POST[ $post_key ] ) ) {
		return;
	}

	$raw      = wp_unslash( $_POST[ $post_key ] );
	$defaults = call_user_func( $sanitize, array() );
	$clean    = call_user_func( $sanitize, $raw );

	update_post_meta( $post_id, $meta_key, array_merge( $defaults, $clean ) );
}

/**
 * Persist repeater rows.
 *
 * @param int      $post_id      Post ID.
 * @param string   $meta_key     Meta key.
 * @param string   $post_key     POST key.
 * @param callable $sanitize_row Row sanitizer.
 */
function sln_ppc_save_repeater_meta( $post_id, $meta_key, $post_key, $sanitize_row ) {
	if ( ! isset( $_POST[ $post_key ] ) || ! is_array( $_POST[ $post_key ] ) ) {
		return;
	}

	$items = array();

	foreach ( wp_unslash( $_POST[ $post_key ] ) as $raw_row ) {
		if ( ! is_array( $raw_row ) ) {
			continue;
		}

		$row = call_user_func( $sanitize_row, $raw_row );

		if ( ! empty( $row ) ) {
			$items[] = $row;
		}
	}

	update_post_meta( $post_id, $meta_key, $items );
}

/**
 * Sanitize WYSIWYG content.
 *
 * @param mixed $content Raw content.
 * @return string
 */
function sln_ppc_sanitize_wysiwyg_content( $content ) {
	if ( function_exists( 'sln_growth_page_sanitize_wysiwyg_content' ) ) {
		return sln_growth_page_sanitize_wysiwyg_content( $content );
	}

	return wp_kses_post( $content );
}

/**
 * Sanitize a numeric value.
 *
 * @param mixed $value Raw value.
 * @return float
 */
function sln_ppc_sanitize_float_value( $value ) {
	return floatval( $value );
}

/**
 * Sanitize visual style token.
 *
 * @param string $style Raw style.
 * @return string
 */
function sln_ppc_sanitize_visual_style( $style ) {
	$style = sanitize_key( $style );

	return in_array( $style, array( 'orange', 'blue', 'green', 'default' ), true ) ? $style : 'default';
}

/**
 * Sanitize button style token.
 *
 * @param string $style Raw style.
 * @return string
 */
function sln_ppc_sanitize_button_style( $style ) {
	$style = sanitize_key( $style );

	return in_array( $style, array( 'primary', 'ghost' ), true ) ? $style : 'primary';
}

/**
 * Sanitize string list.
 *
 * @param mixed $raw Raw list.
 * @return array<int, string>
 */
function sln_ppc_sanitize_string_list( $raw ) {
	if ( ! is_array( $raw ) ) {
		return array();
	}

	$list = array();

	foreach ( $raw as $item ) {
		$item = sanitize_text_field( $item );

		if ( '' !== $item ) {
			$list[] = $item;
		}
	}

	return $list;
}

/**
 * Sanitize hero section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_hero( $raw ) {
	return array(
		'small_heading'             => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'              => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_text'          => sanitize_text_field( $raw['highlighted_text'] ?? '' ),
		'description'               => sln_ppc_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'primary_button_text'       => sanitize_text_field( $raw['primary_button_text'] ?? '' ),
		'primary_button_url'        => sln_ppc_sanitize_url( $raw['primary_button_url'] ?? '' ),
		'secondary_button_text'     => sanitize_text_field( $raw['secondary_button_text'] ?? '' ),
		'secondary_button_url'      => sln_ppc_sanitize_url( $raw['secondary_button_url'] ?? '' ),
		'search_label'              => sanitize_text_field( $raw['search_label'] ?? '' ),
		'search_result_ad_label'    => sanitize_text_field( $raw['search_result_ad_label'] ?? '' ),
		'search_result_url'         => sanitize_text_field( $raw['search_result_url'] ?? '' ),
		'search_result_title'       => sanitize_text_field( $raw['search_result_title'] ?? '' ),
		'search_result_description' => sanitize_textarea_field( $raw['search_result_description'] ?? '' ),
		'dashboard_title'           => sanitize_text_field( $raw['dashboard_title'] ?? '' ),
		'chart_label'               => sanitize_text_field( $raw['chart_label'] ?? '' ),
		'live_label'                => sanitize_text_field( $raw['live_label'] ?? '' ),
		'active'                    => ! empty( $raw['active'] ),
	);
}

/**
 * Save hero meta.
 *
 * @param int $post_id Post ID.
 */
function sln_ppc_save_hero_meta( $post_id ) {
	sln_ppc_save_section_meta( $post_id, SLN_PPC_HERO_META, 'sln_ppc_hero', 'sln_ppc_sanitize_hero' );
	sln_ppc_save_repeater_meta( $post_id, SLN_PPC_HERO_SEARCH_QUERIES_META, 'sln_ppc_hero_search_queries', 'sln_ppc_sanitize_hero_search_query' );
	sln_ppc_save_repeater_meta( $post_id, SLN_PPC_HERO_METRICS_META, 'sln_ppc_hero_metrics', 'sln_ppc_sanitize_metric_row' );
	sln_ppc_save_repeater_meta( $post_id, SLN_PPC_HERO_CHART_META, 'sln_ppc_hero_chart', 'sln_ppc_sanitize_hero_chart_bar' );
}

/**
 * Sanitize hero search query row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_hero_search_query( $raw ) {
	return array(
		'query'  => sanitize_text_field( $raw['query'] ?? '' ),
		'active' => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize metric row using value key.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_metric_row( $raw ) {
	return array(
		'label'         => sanitize_text_field( $raw['label'] ?? '' ),
		'prefix'        => sanitize_text_field( $raw['prefix'] ?? '' ),
		'value'         => sln_ppc_sanitize_float_value( $raw['value'] ?? 0 ),
		'decimals'      => absint( $raw['decimals'] ?? 0 ),
		'suffix'        => sanitize_text_field( $raw['suffix'] ?? '' ),
		'display_value' => sanitize_text_field( $raw['display_value'] ?? '' ),
		'visual_style'  => sln_ppc_sanitize_visual_style( $raw['visual_style'] ?? 'default' ),
		'active'        => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize hero chart bar row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_hero_chart_bar( $raw ) {
	return array(
		'height' => absint( $raw['height'] ?? 0 ),
		'label'  => sanitize_text_field( $raw['label'] ?? '' ),
		'active' => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize keyword marquee row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_keyword_marquee_row( $raw ) {
	return array(
		'keyword'   => sanitize_text_field( $raw['keyword'] ?? '' ),
		'icon_text' => sanitize_text_field( $raw['icon_text'] ?? '' ),
		'active'    => ! empty( $raw['active'] ),
	);
}

/**
 * Save keyword marquee meta.
 *
 * @param int $post_id Post ID.
 */
function sln_ppc_save_keyword_marquee_meta( $post_id ) {
	sln_ppc_save_repeater_meta( $post_id, SLN_PPC_KEYWORD_MARQUEE_META, 'sln_ppc_keyword_marquee', 'sln_ppc_sanitize_keyword_marquee_row' );
}

/**
 * Sanitize stat row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_stat_row( $raw ) {
	return array(
		'prefix'        => sanitize_text_field( $raw['prefix'] ?? '' ),
		'number'        => sln_ppc_sanitize_float_value( $raw['number'] ?? 0 ),
		'decimals'      => absint( $raw['decimals'] ?? 0 ),
		'suffix'        => sanitize_text_field( $raw['suffix'] ?? '' ),
		'unit'          => sanitize_text_field( $raw['unit'] ?? '' ),
		'display_value' => sanitize_text_field( $raw['display_value'] ?? '' ),
		'label'         => sanitize_text_field( $raw['label'] ?? '' ),
		'active'        => ! empty( $raw['active'] ),
	);
}

/**
 * Save stats meta.
 *
 * @param int $post_id Post ID.
 */
function sln_ppc_save_stats_meta( $post_id ) {
	sln_ppc_save_repeater_meta( $post_id, SLN_PPC_STATS_META, 'sln_ppc_stats', 'sln_ppc_sanitize_stat_row' );
}

/**
 * Sanitize trust section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_trust_section( $raw ) {
	return array(
		'label'  => sanitize_text_field( $raw['label'] ?? '' ),
		'active' => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize trust platform row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_trust_platform( $raw ) {
	return array(
		'name'   => sanitize_text_field( $raw['name'] ?? '' ),
		'active' => ! empty( $raw['active'] ),
	);
}

/**
 * Save trust meta.
 *
 * @param int $post_id Post ID.
 */
function sln_ppc_save_trust_meta( $post_id ) {
	sln_ppc_save_section_meta( $post_id, SLN_PPC_TRUST_SECTION_META, 'sln_ppc_trust_section', 'sln_ppc_sanitize_trust_section' );
	sln_ppc_save_repeater_meta( $post_id, SLN_PPC_TRUST_PLATFORMS_META, 'sln_ppc_trust_platforms', 'sln_ppc_sanitize_trust_platform' );
}

/**
 * Sanitize common heading section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_heading_section( $raw ) {
	return array(
		'small_heading'    => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'     => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_text' => sanitize_text_field( $raw['highlighted_text'] ?? '' ),
		'description'      => sln_ppc_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'active'           => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize reality section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_reality_section( $raw ) {
	return array_merge(
		sln_ppc_sanitize_heading_section( $raw ),
		array(
			'bottom_note' => sln_ppc_sanitize_wysiwyg_content( $raw['bottom_note'] ?? '' ),
		)
	);
}

/**
 * Sanitize reality budget panel.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_reality_budget( $raw ) {
	return array(
		'lead_text'       => sanitize_text_field( $raw['lead_text'] ?? '' ),
		'waste_percent'   => absint( $raw['waste_percent'] ?? 0 ),
		'working_percent' => absint( $raw['working_percent'] ?? 0 ),
		'waste_big_text'  => sanitize_text_field( $raw['waste_big_text'] ?? '' ),
		'flip_text'       => sanitize_text_field( $raw['flip_text'] ?? '' ),
		'flip_highlight'  => sanitize_text_field( $raw['flip_highlight'] ?? '' ),
		'wasted_label'    => sanitize_text_field( $raw['wasted_label'] ?? '' ),
		'working_label'   => sanitize_text_field( $raw['working_label'] ?? '' ),
		'caption'         => sanitize_textarea_field( $raw['caption'] ?? '' ),
		'active'          => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize reality challenge row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_reality_challenge( $raw ) {
	return array(
		'icon_text'   => sanitize_text_field( $raw['icon_text'] ?? '' ),
		'title'       => sanitize_text_field( $raw['title'] ?? '' ),
		'description' => sanitize_textarea_field( $raw['description'] ?? '' ),
		'impact'      => sanitize_text_field( $raw['impact'] ?? '' ),
		'active'      => ! empty( $raw['active'] ),
	);
}

/**
 * Save reality meta.
 *
 * @param int $post_id Post ID.
 */
function sln_ppc_save_reality_meta( $post_id ) {
	sln_ppc_save_section_meta( $post_id, SLN_PPC_REALITY_SECTION_META, 'sln_ppc_reality_section', 'sln_ppc_sanitize_reality_section' );
	sln_ppc_save_section_meta( $post_id, SLN_PPC_REALITY_BUDGET_META, 'sln_ppc_reality_budget', 'sln_ppc_sanitize_reality_budget' );
	sln_ppc_save_repeater_meta( $post_id, SLN_PPC_REALITY_CHALLENGES_META, 'sln_ppc_reality_challenges', 'sln_ppc_sanitize_reality_challenge' );
}

/**
 * Sanitize approach item row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_approach_item( $raw ) {
	return array(
		'problem'  => sanitize_text_field( $raw['problem'] ?? '' ),
		'solution' => sln_ppc_sanitize_wysiwyg_content( $raw['solution'] ?? '' ),
		'active'   => ! empty( $raw['active'] ),
	);
}

/**
 * Save approach meta.
 *
 * @param int $post_id Post ID.
 */
function sln_ppc_save_approach_meta( $post_id ) {
	sln_ppc_save_section_meta( $post_id, SLN_PPC_APPROACH_SECTION_META, 'sln_ppc_approach_section', 'sln_ppc_sanitize_heading_section' );
	sln_ppc_save_repeater_meta( $post_id, SLN_PPC_APPROACH_ITEMS_META, 'sln_ppc_approach_items', 'sln_ppc_sanitize_approach_item' );
}

/**
 * Sanitize truth section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_truth_section( $raw ) {
	return array(
		'statement'        => sanitize_text_field( $raw['statement'] ?? '' ),
		'highlighted_text' => sanitize_text_field( $raw['highlighted_text'] ?? '' ),
		'body'             => sln_ppc_sanitize_wysiwyg_content( $raw['body'] ?? '' ),
		'quote'            => sanitize_text_field( $raw['quote'] ?? '' ),
		'quote_highlight'  => sanitize_text_field( $raw['quote_highlight'] ?? '' ),
		'attribution'      => sanitize_text_field( $raw['attribution'] ?? '' ),
		'button_text'      => sanitize_text_field( $raw['button_text'] ?? '' ),
		'button_url'       => sln_ppc_sanitize_url( $raw['button_url'] ?? '' ),
		'active'           => ! empty( $raw['active'] ),
	);
}

/**
 * Save truth meta.
 *
 * @param int $post_id Post ID.
 */
function sln_ppc_save_truth_meta( $post_id ) {
	sln_ppc_save_section_meta( $post_id, SLN_PPC_TRUTH_SECTION_META, 'sln_ppc_truth_section', 'sln_ppc_sanitize_truth_section' );
}

/**
 * Sanitize why section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_why_section( $raw ) {
	return array_merge(
		sln_ppc_sanitize_heading_section( $raw ),
		array(
			'left_heading'  => sanitize_text_field( $raw['left_heading'] ?? '' ),
			'right_heading' => sanitize_text_field( $raw['right_heading'] ?? '' ),
		)
	);
}

/**
 * Sanitize why comparison row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_why_comparison( $raw ) {
	return array(
		'typical' => sln_ppc_sanitize_wysiwyg_content( $raw['typical'] ?? '' ),
		'sls'     => sln_ppc_sanitize_wysiwyg_content( $raw['sls'] ?? '' ),
		'active'  => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize why badge row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_why_badge( $raw ) {
	return array(
		'text'   => sanitize_text_field( $raw['text'] ?? '' ),
		'active' => ! empty( $raw['active'] ),
	);
}

/**
 * Save why meta.
 *
 * @param int $post_id Post ID.
 */
function sln_ppc_save_why_meta( $post_id ) {
	sln_ppc_save_section_meta( $post_id, SLN_PPC_WHY_SECTION_META, 'sln_ppc_why_section', 'sln_ppc_sanitize_why_section' );
	sln_ppc_save_repeater_meta( $post_id, SLN_PPC_WHY_COMPARISON_META, 'sln_ppc_why_comparison', 'sln_ppc_sanitize_why_comparison' );
	sln_ppc_save_repeater_meta( $post_id, SLN_PPC_WHY_BADGES_META, 'sln_ppc_why_badges', 'sln_ppc_sanitize_why_badge' );
}

/**
 * Sanitize service item row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_service_item( $raw ) {
	return array(
		'icon_key'    => sanitize_key( $raw['icon_key'] ?? '' ),
		'icon_text'   => sanitize_text_field( $raw['icon_text'] ?? '' ),
		'icon_style'  => sln_ppc_sanitize_visual_style( $raw['icon_style'] ?? 'default' ),
		'tag'         => sanitize_text_field( $raw['tag'] ?? '' ),
		'title'       => sanitize_text_field( $raw['title'] ?? '' ),
		'description' => sanitize_textarea_field( $raw['description'] ?? '' ),
		'active'      => ! empty( $raw['active'] ),
	);
}

/**
 * Save services meta.
 *
 * @param int $post_id Post ID.
 */
function sln_ppc_save_services_meta( $post_id ) {
	sln_ppc_save_section_meta( $post_id, SLN_PPC_SERVICES_SECTION_META, 'sln_ppc_services_section', 'sln_ppc_sanitize_heading_section' );
	sln_ppc_save_repeater_meta( $post_id, SLN_PPC_SERVICES_ITEMS_META, 'sln_ppc_services_items', 'sln_ppc_sanitize_service_item' );
}

/**
 * Sanitize industry item row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_industry_item( $raw ) {
	return array(
		'icon_text'   => sanitize_text_field( $raw['icon_text'] ?? '' ),
		'title'       => sanitize_text_field( $raw['title'] ?? '' ),
		'description' => sanitize_textarea_field( $raw['description'] ?? '' ),
		'active'      => ! empty( $raw['active'] ),
	);
}

/**
 * Save industries meta.
 *
 * @param int $post_id Post ID.
 */
function sln_ppc_save_industries_meta( $post_id ) {
	sln_ppc_save_section_meta( $post_id, SLN_PPC_INDUSTRIES_SECTION_META, 'sln_ppc_industries_section', 'sln_ppc_sanitize_heading_section' );
	sln_ppc_save_repeater_meta( $post_id, SLN_PPC_INDUSTRIES_ITEMS_META, 'sln_ppc_industries_items', 'sln_ppc_sanitize_industry_item' );
}

/**
 * Sanitize ROI section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_roi_section( $raw ) {
	return array_merge(
		sln_ppc_sanitize_heading_section( $raw ),
		array(
			'assumption_text' => sanitize_textarea_field( $raw['assumption_text'] ?? '' ),
			'cpc'             => sln_ppc_sanitize_float_value( $raw['cpc'] ?? 0 ),
			'cvr'             => sln_ppc_sanitize_float_value( $raw['cvr'] ?? 0 ),
			'disclaimer'      => sanitize_textarea_field( $raw['disclaimer'] ?? '' ),
		)
	);
}

/**
 * Sanitize ROI control row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_roi_control( $raw ) {
	return array(
		'key'           => sanitize_key( $raw['key'] ?? '' ),
		'label'         => sanitize_text_field( $raw['label'] ?? '' ),
		'min'           => sln_ppc_sanitize_float_value( $raw['min'] ?? 0 ),
		'max'           => sln_ppc_sanitize_float_value( $raw['max'] ?? 0 ),
		'step'          => sln_ppc_sanitize_float_value( $raw['step'] ?? 0 ),
		'default'       => sln_ppc_sanitize_float_value( $raw['default'] ?? 0 ),
		'display_value' => sanitize_text_field( $raw['display_value'] ?? '' ),
		'prefix'        => sanitize_text_field( $raw['prefix'] ?? '' ),
		'suffix'        => sanitize_text_field( $raw['suffix'] ?? '' ),
		'active'        => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize ROI output row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_roi_output( $raw ) {
	return array(
		'key'           => sanitize_key( $raw['key'] ?? '' ),
		'label'         => sanitize_text_field( $raw['label'] ?? '' ),
		'calc_type'     => sanitize_text_field( $raw['calc_type'] ?? '' ),
		'display_value' => sanitize_text_field( $raw['display_value'] ?? '' ),
		'prefix'        => sanitize_text_field( $raw['prefix'] ?? '' ),
		'suffix'        => sanitize_text_field( $raw['suffix'] ?? '' ),
		'visual_style'  => sln_ppc_sanitize_visual_style( $raw['visual_style'] ?? 'default' ),
		'highlight'     => ! empty( $raw['highlight'] ),
		'active'        => ! empty( $raw['active'] ),
	);
}

/**
 * Save ROI meta.
 *
 * @param int $post_id Post ID.
 */
function sln_ppc_save_roi_meta( $post_id ) {
	sln_ppc_save_section_meta( $post_id, SLN_PPC_ROI_SECTION_META, 'sln_ppc_roi_section', 'sln_ppc_sanitize_roi_section' );
	sln_ppc_save_repeater_meta( $post_id, SLN_PPC_ROI_CONTROLS_META, 'sln_ppc_roi_controls', 'sln_ppc_sanitize_roi_control' );
	sln_ppc_save_repeater_meta( $post_id, SLN_PPC_ROI_OUTPUTS_META, 'sln_ppc_roi_outputs', 'sln_ppc_sanitize_roi_output' );
}

/**
 * Sanitize process section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_process_section( $raw ) {
	return array_merge(
		sln_ppc_sanitize_heading_section( $raw ),
		array(
			'bottom_note' => sanitize_textarea_field( $raw['bottom_note'] ?? '' ),
		)
	);
}

/**
 * Sanitize process step row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_process_step( $raw ) {
	return array(
		'number'  => sanitize_text_field( $raw['number'] ?? '' ),
		'title'   => sanitize_text_field( $raw['title'] ?? '' ),
		'bullets' => sln_ppc_sanitize_string_list( $raw['bullets'] ?? array() ),
		'active'  => ! empty( $raw['active'] ),
	);
}

/**
 * Save process meta.
 *
 * @param int $post_id Post ID.
 */
function sln_ppc_save_process_meta( $post_id ) {
	sln_ppc_save_section_meta( $post_id, SLN_PPC_PROCESS_SECTION_META, 'sln_ppc_process_section', 'sln_ppc_sanitize_process_section' );
	sln_ppc_save_repeater_meta( $post_id, SLN_PPC_PROCESS_STEPS_META, 'sln_ppc_process_steps', 'sln_ppc_sanitize_process_step' );
}

/**
 * Sanitize mid CTA section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_mid_cta( $raw ) {
	return array(
		'heading'     => sanitize_text_field( $raw['heading'] ?? '' ),
		'description' => sln_ppc_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'button_text' => sanitize_text_field( $raw['button_text'] ?? '' ),
		'button_url'  => sln_ppc_sanitize_url( $raw['button_url'] ?? '' ),
		'active'      => ! empty( $raw['active'] ),
	);
}

/**
 * Save mid CTA meta.
 *
 * @param int $post_id Post ID.
 */
function sln_ppc_save_mid_cta_meta( $post_id ) {
	sln_ppc_save_section_meta( $post_id, SLN_PPC_MID_CTA_META, 'sln_ppc_mid_cta', 'sln_ppc_sanitize_mid_cta' );
}

/**
 * Sanitize proof section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_proof_section( $raw ) {
	return array_merge(
		sln_ppc_sanitize_heading_section( $raw ),
		array(
			'disclaimer' => sanitize_textarea_field( $raw['disclaimer'] ?? '' ),
		)
	);
}

/**
 * Sanitize case-study metric row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_case_metric( $raw ) {
	if ( ! is_array( $raw ) ) {
		return array();
	}

	return array(
		'prefix'        => sanitize_text_field( $raw['prefix'] ?? '' ),
		'value'         => sln_ppc_sanitize_float_value( $raw['value'] ?? 0 ),
		'decimals'      => absint( $raw['decimals'] ?? 0 ),
		'suffix'        => sanitize_text_field( $raw['suffix'] ?? '' ),
		'display_value' => sanitize_text_field( $raw['display_value'] ?? '' ),
		'label'         => sanitize_text_field( $raw['label'] ?? '' ),
		'visual_style'  => sln_ppc_sanitize_visual_style( $raw['visual_style'] ?? 'default' ),
	);
}

/**
 * Sanitize case-study metrics list.
 *
 * @param mixed $raw Raw metrics list.
 * @return array<int, array<string, mixed>>
 */
function sln_ppc_sanitize_case_metrics( $raw ) {
	if ( ! is_array( $raw ) ) {
		return array();
	}

	$metrics = array();

	foreach ( $raw as $item ) {
		$metric = sln_ppc_sanitize_case_metric( $item );

		if ( ! empty( $metric['display_value'] ) || ! empty( $metric['label'] ) || 0.0 !== (float) $metric['value'] ) {
			$metrics[] = $metric;
		}
	}

	return $metrics;
}

/**
 * Sanitize case-study progress block.
 *
 * @param mixed $raw Raw progress block.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_case_progress( $raw ) {
	if ( ! is_array( $raw ) ) {
		$raw = array();
	}

	return array(
		'label' => sanitize_text_field( $raw['label'] ?? '' ),
		'value' => sanitize_text_field( $raw['value'] ?? '' ),
		'width' => absint( $raw['width'] ?? 0 ),
	);
}

/**
 * Sanitize case study row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_case_study( $raw ) {
	return array(
		'name'        => sanitize_text_field( $raw['name'] ?? '' ),
		'tag'         => sanitize_text_field( $raw['tag'] ?? '' ),
		'metrics'     => sln_ppc_sanitize_case_metrics( $raw['metrics'] ?? array() ),
		'progress'    => sln_ppc_sanitize_case_progress( $raw['progress'] ?? array() ),
		'quote'       => sanitize_textarea_field( $raw['quote'] ?? '' ),
		'attribution' => sanitize_text_field( $raw['attribution'] ?? '' ),
		'active'      => ! empty( $raw['active'] ),
	);
}

/**
 * Save proof meta.
 *
 * @param int $post_id Post ID.
 */
function sln_ppc_save_proof_meta( $post_id ) {
	sln_ppc_save_section_meta( $post_id, SLN_PPC_PROOF_SECTION_META, 'sln_ppc_proof_section', 'sln_ppc_sanitize_proof_section' );
	sln_ppc_save_repeater_meta( $post_id, SLN_PPC_CASE_STUDIES_META, 'sln_ppc_case_studies', 'sln_ppc_sanitize_case_study' );
}

/**
 * Sanitize pricing section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_pricing_section( $raw ) {
	return array_merge(
		sln_ppc_sanitize_heading_section( $raw ),
		array(
			'bottom_note' => sln_ppc_sanitize_wysiwyg_content( $raw['bottom_note'] ?? '' ),
		)
	);
}

/**
 * Sanitize pricing feature row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_pricing_feature( $raw ) {
	return array(
		'text'      => sanitize_text_field( $raw['text'] ?? '' ),
		'highlight' => ! empty( $raw['highlight'] ),
		'active'    => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize pricing features list.
 *
 * @param mixed $raw Raw features list.
 * @return array<int, array<string, mixed>>
 */
function sln_ppc_sanitize_pricing_features( $raw ) {
	if ( ! is_array( $raw ) ) {
		return array();
	}

	$features = array();

	foreach ( $raw as $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}

		$feature = sln_ppc_sanitize_pricing_feature( $item );

		if ( '' !== $feature['text'] ) {
			$features[] = $feature;
		}
	}

	return $features;
}

/**
 * Sanitize pricing plan row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_pricing_plan( $raw ) {
	return array(
		'name'          => sanitize_text_field( $raw['name'] ?? '' ),
		'tagline'       => sanitize_text_field( $raw['tagline'] ?? '' ),
		'price'         => sanitize_text_field( $raw['price'] ?? '' ),
		'price_suffix'  => sanitize_text_field( $raw['price_suffix'] ?? '' ),
		'spend'         => sanitize_text_field( $raw['spend'] ?? '' ),
		'is_popular'    => ! empty( $raw['is_popular'] ),
		'popular_badge' => sanitize_text_field( $raw['popular_badge'] ?? '' ),
		'features'      => sln_ppc_sanitize_pricing_features( $raw['features'] ?? array() ),
		'button_text'   => sanitize_text_field( $raw['button_text'] ?? '' ),
		'button_url'    => sln_ppc_sanitize_url( $raw['button_url'] ?? '' ),
		'button_style'  => sln_ppc_sanitize_button_style( $raw['button_style'] ?? 'primary' ),
		'active'        => ! empty( $raw['active'] ),
	);
}

/**
 * Save pricing meta.
 *
 * @param int $post_id Post ID.
 */
function sln_ppc_save_pricing_meta( $post_id ) {
	sln_ppc_save_section_meta( $post_id, SLN_PPC_PRICING_SECTION_META, 'sln_ppc_pricing_section', 'sln_ppc_sanitize_pricing_section' );
	sln_ppc_save_repeater_meta( $post_id, SLN_PPC_PRICING_PLANS_META, 'sln_ppc_pricing_plans', 'sln_ppc_sanitize_pricing_plan' );
}

/**
 * Sanitize FAQ item row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_faq_item( $raw ) {
	return array(
		'question' => sanitize_text_field( $raw['question'] ?? '' ),
		'answer'   => sln_ppc_sanitize_wysiwyg_content( $raw['answer'] ?? '' ),
		'active'   => ! empty( $raw['active'] ),
	);
}

/**
 * Save FAQ meta.
 *
 * @param int $post_id Post ID.
 */
function sln_ppc_save_faq_meta( $post_id ) {
	sln_ppc_save_section_meta( $post_id, SLN_PPC_FAQ_SECTION_META, 'sln_ppc_faq_section', 'sln_ppc_sanitize_heading_section' );
	sln_ppc_save_repeater_meta( $post_id, SLN_PPC_FAQ_ITEMS_META, 'sln_ppc_faq_items', 'sln_ppc_sanitize_faq_item' );
}

/**
 * Sanitize final CTA section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_ppc_sanitize_final_cta( $raw ) {
	return array(
		'small_heading'          => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'           => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_text'       => sanitize_text_field( $raw['highlighted_text'] ?? '' ),
		'description'            => sln_ppc_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'primary_button_text'    => sanitize_text_field( $raw['primary_button_text'] ?? '' ),
		'primary_button_url'     => sln_ppc_sanitize_url( $raw['primary_button_url'] ?? '' ),
		'secondary_button_text'  => sanitize_text_field( $raw['secondary_button_text'] ?? '' ),
		'secondary_button_url'   => sln_ppc_sanitize_url( $raw['secondary_button_url'] ?? '' ),
		'website_label'          => sanitize_text_field( $raw['website_label'] ?? '' ),
		'website_text'           => sanitize_text_field( $raw['website_text'] ?? '' ),
		'website_url'            => sln_ppc_sanitize_url( $raw['website_url'] ?? '' ),
		'bottom_note'            => sanitize_text_field( $raw['bottom_note'] ?? '' ),
		'active'                 => ! empty( $raw['active'] ),
	);
}

/**
 * Save final CTA meta.
 *
 * @param int $post_id Post ID.
 */
function sln_ppc_save_final_cta_meta( $post_id ) {
	sln_ppc_save_section_meta( $post_id, SLN_PPC_FINAL_CTA_META, 'sln_ppc_final_cta', 'sln_ppc_sanitize_final_cta' );
}
