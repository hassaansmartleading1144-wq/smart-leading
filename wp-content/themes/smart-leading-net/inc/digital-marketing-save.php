<?php
/**
 * Digital Marketing page — save handlers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether the page edit screen should show Digital Marketing fields.
 *
 * @param WP_Post|null $post Post object.
 * @return bool
 */
function sln_dm_admin_is_target_page_fallback( $post ) {
	if ( ! $post instanceof WP_Post || 'page' !== $post->post_type ) {
		return false;
	}

	return SLN_DM_TEMPLATE === get_page_template_slug( $post->ID );
}

/**
 * Register save hook for Digital Marketing pages.
 */
function sln_dm_register_save_hooks() {
	add_action( 'save_post_page', 'sln_dm_save_meta', 10, 2 );
}
add_action( 'init', 'sln_dm_register_save_hooks', 20 );

/**
 * Output master save nonce after title.
 *
 * @param WP_Post $post Current post.
 */
function sln_dm_output_save_nonce( $post ) {
	if ( ! $post instanceof WP_Post || 'page' !== $post->post_type ) {
		return;
	}

	// Always output on page screens so the first save after selecting
	// the Digital Marketing template can persist fields.
	wp_nonce_field( 'sln_dm_save_meta', 'sln_dm_master_nonce', false );
}
add_action( 'edit_form_after_title', 'sln_dm_output_save_nonce' );

/**
 * Whether save should proceed.
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function sln_dm_should_save_meta( $post_id ) {
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

	if ( ! isset( $_POST['sln_dm_master_nonce'] ) ) {
		return false;
	}

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sln_dm_master_nonce'] ) ), 'sln_dm_save_meta' ) ) {
		return false;
	}

	$template = isset( $_POST['page_template'] )
		? sanitize_text_field( wp_unslash( $_POST['page_template'] ) )
		: get_page_template_slug( $post_id );

	return SLN_DM_TEMPLATE === $template;
}

/**
 * Save all Digital Marketing meta.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 */
function sln_dm_save_meta( $post_id, $post ) {
	unset( $post );

	if ( ! sln_dm_should_save_meta( $post_id ) ) {
		return;
	}

	sln_dm_save_hero_meta( $post_id );
	sln_dm_save_hero_stats_meta( $post_id );
	sln_dm_save_dashboard_metrics_meta( $post_id );
	sln_dm_save_reality_meta( $post_id );
	sln_dm_save_approach_meta( $post_id );
	sln_dm_save_truth_meta( $post_id );
	sln_dm_save_services_meta( $post_id );
	sln_dm_save_ads_meta( $post_id );
	sln_dm_save_process_meta( $post_id );
	sln_dm_save_proof_meta( $post_id );
	sln_dm_save_pricing_meta( $post_id );
	sln_dm_save_faq_meta( $post_id );
	sln_dm_save_final_cta_meta( $post_id );
}

/**
 * Persist associative section when posted.
 *
 * @param int      $post_id  Post ID.
 * @param string   $meta_key Meta key.
 * @param string   $post_key POST key.
 * @param callable $sanitize Sanitizer callback.
 */
function sln_dm_save_section_meta( $post_id, $meta_key, $post_key, $sanitize ) {
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
function sln_dm_save_repeater_meta( $post_id, $meta_key, $post_key, $sanitize_row ) {
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
 * Sanitize string list.
 *
 * @param mixed $raw Raw list.
 * @return array<int, string>
 */
function sln_dm_sanitize_string_list( $raw ) {
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
 * Sanitize icon style token.
 *
 * @param string $style Raw style.
 * @return string
 */
function sln_dm_sanitize_icon_style( $style ) {
	$style = sanitize_key( $style );

	return in_array( $style, array( 'orange', 'blue' ), true ) ? $style : 'orange';
}

/**
 * Sanitize button style token.
 *
 * @param string $style Raw style.
 * @return string
 */
function sln_dm_sanitize_button_style( $style ) {
	$style = sanitize_key( $style );

	return in_array( $style, array( 'primary', 'ghost' ), true ) ? $style : 'primary';
}

/**
 * Sanitize hero section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_dm_sanitize_hero( $raw ) {
	return array(
		'small_heading'       => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'        => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_text'    => sanitize_text_field( $raw['highlighted_text'] ?? '' ),
		'description'         => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'primary_button_text' => sanitize_text_field( $raw['primary_button_text'] ?? '' ),
		'primary_button_url'  => sln_dm_sanitize_url( $raw['primary_button_url'] ?? '' ),
		'dashboard_title'     => sanitize_text_field( $raw['dashboard_title'] ?? '' ),
		'chip_1_text'         => sanitize_text_field( $raw['chip_1_text'] ?? '' ),
		'chip_2_text'         => sanitize_text_field( $raw['chip_2_text'] ?? '' ),
		'active'              => ! empty( $raw['active'] ),
	);
}

/**
 * Save hero meta.
 *
 * @param int $post_id Post ID.
 */
function sln_dm_save_hero_meta( $post_id ) {
	sln_dm_save_section_meta( $post_id, SLN_DM_HERO_META, 'sln_dm_hero', 'sln_dm_sanitize_hero' );
}

/**
 * Sanitize hero stat row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_dm_sanitize_hero_stat( $raw ) {
	return array(
		'prefix'   => sanitize_text_field( $raw['prefix'] ?? '' ),
		'number'   => sanitize_text_field( $raw['number'] ?? '' ),
		'decimals' => sanitize_text_field( $raw['decimals'] ?? '0' ),
		'suffix'   => sanitize_text_field( $raw['suffix'] ?? '' ),
		'unit'     => sanitize_text_field( $raw['unit'] ?? '' ),
		'label'    => sanitize_text_field( $raw['label'] ?? '' ),
		'active'   => ! empty( $raw['active'] ),
	);
}

/**
 * Save hero stats meta.
 *
 * @param int $post_id Post ID.
 */
function sln_dm_save_hero_stats_meta( $post_id ) {
	sln_dm_save_repeater_meta( $post_id, SLN_DM_HERO_STATS_META, 'sln_dm_hero_stats', 'sln_dm_sanitize_hero_stat' );
}

/**
 * Sanitize dashboard metric row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_dm_sanitize_dashboard_metric( $raw ) {
	return array(
		'prefix'   => sanitize_text_field( $raw['prefix'] ?? '' ),
		'value'    => sanitize_text_field( $raw['value'] ?? '' ),
		'suffix'   => sanitize_text_field( $raw['suffix'] ?? '' ),
		'label'    => sanitize_text_field( $raw['label'] ?? '' ),
		'decimals' => sanitize_text_field( $raw['decimals'] ?? '0' ),
	);
}

/**
 * Save dashboard metrics meta.
 *
 * @param int $post_id Post ID.
 */
function sln_dm_save_dashboard_metrics_meta( $post_id ) {
	sln_dm_save_repeater_meta( $post_id, SLN_DM_DASHBOARD_METRICS_META, 'sln_dm_dashboard_metrics', 'sln_dm_sanitize_dashboard_metric' );
}

/**
 * Sanitize reality section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_dm_sanitize_reality_section( $raw ) {
	return array(
		'small_heading'    => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'     => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_text' => sanitize_text_field( $raw['highlighted_text'] ?? '' ),
		'description'      => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'note_text'        => sanitize_text_field( $raw['note_text'] ?? '' ),
		'note_highlight'   => sanitize_text_field( $raw['note_highlight'] ?? '' ),
		'note_active'      => ! empty( $raw['note_active'] ),
	);
}

/**
 * Sanitize reality card row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_dm_sanitize_reality_card( $raw ) {
	return array(
		'icon_id'     => sln_sanitize_media_attachment_id( $raw['icon_id'] ?? 0 ),
		'icon_text'   => sanitize_text_field( $raw['icon_text'] ?? '' ),
		'icon_style'  => sln_dm_sanitize_icon_style( $raw['icon_style'] ?? 'orange' ),
		'title'       => sanitize_text_field( $raw['title'] ?? '' ),
		'description' => sanitize_textarea_field( $raw['description'] ?? '' ),
		'url'         => sln_dm_sanitize_url( $raw['url'] ?? '' ),
		'active'      => ! empty( $raw['active'] ),
	);
}

/**
 * Save reality meta.
 *
 * @param int $post_id Post ID.
 */
function sln_dm_save_reality_meta( $post_id ) {
	sln_dm_save_section_meta( $post_id, SLN_DM_REALITY_SECTION_META, 'sln_dm_reality_section', 'sln_dm_sanitize_reality_section' );
	sln_dm_save_repeater_meta( $post_id, SLN_DM_REALITY_CARDS_META, 'sln_dm_reality_cards', 'sln_dm_sanitize_reality_card' );
}

/**
 * Sanitize approach section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, string>
 */
function sln_dm_sanitize_approach_section( $raw ) {
	return array(
		'small_heading'    => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'     => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_text' => sanitize_text_field( $raw['highlighted_text'] ?? '' ),
		'description'      => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
	);
}

/**
 * Sanitize approach item row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_dm_sanitize_approach_item( $raw ) {
	return array(
		'problem'  => sanitize_text_field( $raw['problem'] ?? '' ),
		'solution' => sln_growth_page_sanitize_wysiwyg_content( $raw['solution'] ?? '' ),
		'url'      => sln_dm_sanitize_url( $raw['url'] ?? '' ),
		'active'   => ! empty( $raw['active'] ),
	);
}

/**
 * Save approach meta.
 *
 * @param int $post_id Post ID.
 */
function sln_dm_save_approach_meta( $post_id ) {
	sln_dm_save_section_meta( $post_id, SLN_DM_APPROACH_SECTION_META, 'sln_dm_approach_section', 'sln_dm_sanitize_approach_section' );
	sln_dm_save_repeater_meta( $post_id, SLN_DM_APPROACH_ITEMS_META, 'sln_dm_approach_items', 'sln_dm_sanitize_approach_item' );
}

/**
 * Sanitize truth section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_dm_sanitize_truth_section( $raw ) {
	return array(
		'small_heading'    => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'     => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_text' => sanitize_text_field( $raw['highlighted_text'] ?? '' ),
		'button_text'      => sanitize_text_field( $raw['button_text'] ?? '' ),
		'button_url'       => sln_dm_sanitize_url( $raw['button_url'] ?? '' ),
		'active'           => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize truth paragraph row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_dm_sanitize_truth_paragraph( $raw ) {
	return array(
		'text'   => sanitize_textarea_field( $raw['text'] ?? '' ),
		'active' => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize truth quote block.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_dm_sanitize_truth_quote( $raw ) {
	return array(
		'quote_text'       => sanitize_text_field( $raw['quote_text'] ?? '' ),
		'highlighted_text' => sanitize_text_field( $raw['highlighted_text'] ?? '' ),
		'attribution'      => sanitize_text_field( $raw['attribution'] ?? '' ),
		'graph_label'      => sanitize_text_field( $raw['graph_label'] ?? '' ),
		'graph_growth'     => sanitize_text_field( $raw['graph_growth'] ?? '' ),
		'active'           => ! empty( $raw['active'] ),
	);
}

/**
 * Save truth meta.
 *
 * @param int $post_id Post ID.
 */
function sln_dm_save_truth_meta( $post_id ) {
	sln_dm_save_section_meta( $post_id, SLN_DM_TRUTH_SECTION_META, 'sln_dm_truth_section', 'sln_dm_sanitize_truth_section' );
	sln_dm_save_repeater_meta( $post_id, SLN_DM_TRUTH_PARAGRAPHS_META, 'sln_dm_truth_paragraphs', 'sln_dm_sanitize_truth_paragraph' );
	sln_dm_save_section_meta( $post_id, SLN_DM_TRUTH_QUOTE_META, 'sln_dm_truth_quote', 'sln_dm_sanitize_truth_quote' );
}

/**
 * Sanitize services section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, string>
 */
function sln_dm_sanitize_services_section( $raw ) {
	return array(
		'small_heading'    => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'     => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_text' => sanitize_text_field( $raw['highlighted_text'] ?? '' ),
		'description'      => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
	);
}

/**
 * Sanitize services item row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_dm_sanitize_services_item( $raw ) {
	return array(
		'icon_text'   => sanitize_text_field( $raw['icon_text'] ?? '' ),
		'icon_style'  => sln_dm_sanitize_icon_style( $raw['icon_style'] ?? 'orange' ),
		'title'       => sanitize_text_field( $raw['title'] ?? '' ),
		'description' => sanitize_textarea_field( $raw['description'] ?? '' ),
		'url'         => sln_dm_sanitize_url( $raw['url'] ?? '' ),
		'new_tab'     => ! empty( $raw['new_tab'] ),
		'icon_id'     => sln_sanitize_media_attachment_id( $raw['icon_id'] ?? 0 ),
		'active'      => ! empty( $raw['active'] ),
	);
}

/**
 * Save services meta.
 *
 * @param int $post_id Post ID.
 */
function sln_dm_save_services_meta( $post_id ) {
	sln_dm_save_section_meta( $post_id, SLN_DM_SERVICES_SECTION_META, 'sln_dm_services_section', 'sln_dm_sanitize_services_section' );
	sln_dm_save_repeater_meta( $post_id, SLN_DM_SERVICES_ITEMS_META, 'sln_dm_services_items', 'sln_dm_sanitize_services_item' );
}

/**
 * Sanitize ads section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, string>
 */
function sln_dm_sanitize_ads_section( $raw ) {
	return array(
		'small_heading'    => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'     => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_text' => sanitize_text_field( $raw['highlighted_text'] ?? '' ),
		'description'      => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
	);
}

/**
 * Sanitize ads channel row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_dm_sanitize_ads_channel( $raw ) {
	return array(
		'icon_text'   => sanitize_text_field( $raw['icon_text'] ?? '' ),
		'name'        => sanitize_text_field( $raw['name'] ?? '' ),
		'description' => sanitize_textarea_field( $raw['description'] ?? '' ),
		'url'         => sln_dm_sanitize_url( $raw['url'] ?? '' ),
		'icon_id'     => sln_sanitize_media_attachment_id( $raw['icon_id'] ?? 0 ),
		'active'      => ! empty( $raw['active'] ),
	);
}

/**
 * Save ads meta.
 *
 * @param int $post_id Post ID.
 */
function sln_dm_save_ads_meta( $post_id ) {
	sln_dm_save_section_meta( $post_id, SLN_DM_ADS_SECTION_META, 'sln_dm_ads_section', 'sln_dm_sanitize_ads_section' );
	sln_dm_save_repeater_meta( $post_id, SLN_DM_ADS_CHANNELS_META, 'sln_dm_ads_channels', 'sln_dm_sanitize_ads_channel' );
}

/**
 * Sanitize process section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, string>
 */
function sln_dm_sanitize_process_section( $raw ) {
	return array(
		'small_heading'    => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'     => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_text' => sanitize_text_field( $raw['highlighted_text'] ?? '' ),
		'description'      => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'bottom_note'      => sanitize_text_field( $raw['bottom_note'] ?? '' ),
	);
}

/**
 * Sanitize process step row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_dm_sanitize_process_step( $raw ) {
	return array(
		'number'  => sanitize_text_field( $raw['number'] ?? '' ),
		'title'   => sanitize_text_field( $raw['title'] ?? '' ),
		'bullets' => sln_dm_sanitize_string_list( $raw['bullets'] ?? array() ),
		'url'     => sln_dm_sanitize_url( $raw['url'] ?? '' ),
		'active'  => ! empty( $raw['active'] ),
	);
}

/**
 * Save process meta.
 *
 * @param int $post_id Post ID.
 */
function sln_dm_save_process_meta( $post_id ) {
	sln_dm_save_section_meta( $post_id, SLN_DM_PROCESS_SECTION_META, 'sln_dm_process_section', 'sln_dm_sanitize_process_section' );
	sln_dm_save_repeater_meta( $post_id, SLN_DM_PROCESS_STEPS_META, 'sln_dm_process_steps', 'sln_dm_sanitize_process_step' );
}

/**
 * Sanitize proof section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, string>
 */
function sln_dm_sanitize_proof_section( $raw ) {
	return array(
		'small_heading'    => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'     => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_text' => sanitize_text_field( $raw['highlighted_text'] ?? '' ),
		'description'      => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'disclaimer'       => sanitize_text_field( $raw['disclaimer'] ?? '' ),
	);
}

/**
 * Sanitize case study metric row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, string>
 */
function sln_dm_sanitize_case_study_metric( $raw ) {
	if ( ! is_array( $raw ) ) {
		return array();
	}

	return array(
		'value' => sanitize_text_field( $raw['value'] ?? '' ),
		'label' => sanitize_text_field( $raw['label'] ?? '' ),
	);
}

/**
 * Sanitize case study metrics list.
 *
 * @param mixed $raw Raw metrics list.
 * @return array<int, array<string, string>>
 */
function sln_dm_sanitize_case_study_metrics( $raw ) {
	if ( ! is_array( $raw ) ) {
		return array();
	}

	$metrics = array();

	foreach ( $raw as $item ) {
		$metric = sln_dm_sanitize_case_study_metric( $item );

		if ( ! empty( $metric['value'] ) || ! empty( $metric['label'] ) ) {
			$metrics[] = $metric;
		}
	}

	return $metrics;
}

/**
 * Sanitize case study row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_dm_sanitize_case_study( $raw ) {
	return array(
		'name'        => sanitize_text_field( $raw['name'] ?? '' ),
		'tag'         => sanitize_text_field( $raw['tag'] ?? '' ),
		'metrics'     => sln_dm_sanitize_case_study_metrics( $raw['metrics'] ?? array() ),
		'quote'       => sanitize_textarea_field( $raw['quote'] ?? '' ),
		'attribution' => sanitize_text_field( $raw['attribution'] ?? '' ),
		'url'         => sln_dm_sanitize_url( $raw['url'] ?? '' ),
		'active'      => ! empty( $raw['active'] ),
	);
}

/**
 * Save proof meta.
 *
 * @param int $post_id Post ID.
 */
function sln_dm_save_proof_meta( $post_id ) {
	sln_dm_save_section_meta( $post_id, SLN_DM_PROOF_SECTION_META, 'sln_dm_proof_section', 'sln_dm_sanitize_proof_section' );
	sln_dm_save_repeater_meta( $post_id, SLN_DM_CASE_STUDIES_META, 'sln_dm_case_studies', 'sln_dm_sanitize_case_study' );
}

/**
 * Sanitize pricing section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, string>
 */
function sln_dm_sanitize_pricing_section( $raw ) {
	return array(
		'small_heading'    => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'     => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_text' => sanitize_text_field( $raw['highlighted_text'] ?? '' ),
		'description'      => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'bottom_note'      => sanitize_text_field( $raw['bottom_note'] ?? '' ),
	);
}

/**
 * Sanitize pricing plan row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_dm_sanitize_pricing_plan( $raw ) {
	return array(
		'name'          => sanitize_text_field( $raw['name'] ?? '' ),
		'tagline'       => sanitize_text_field( $raw['tagline'] ?? '' ),
		'price'         => sanitize_text_field( $raw['price'] ?? '' ),
		'price_prefix'  => sanitize_text_field( $raw['price_prefix'] ?? '' ),
		'price_suffix'  => sanitize_text_field( $raw['price_suffix'] ?? '' ),
		'is_popular'    => ! empty( $raw['is_popular'] ),
		'popular_badge' => sanitize_text_field( $raw['popular_badge'] ?? '' ),
		'features'      => sln_dm_sanitize_string_list( $raw['features'] ?? array() ),
		'button_text'   => sanitize_text_field( $raw['button_text'] ?? '' ),
		'button_url'    => sln_dm_sanitize_url( $raw['button_url'] ?? '' ),
		'button_style'  => sln_dm_sanitize_button_style( $raw['button_style'] ?? 'primary' ),
		'active'        => ! empty( $raw['active'] ),
	);
}

/**
 * Save pricing meta.
 *
 * @param int $post_id Post ID.
 */
function sln_dm_save_pricing_meta( $post_id ) {
	sln_dm_save_section_meta( $post_id, SLN_DM_PRICING_SECTION_META, 'sln_dm_pricing_section', 'sln_dm_sanitize_pricing_section' );
	sln_dm_save_repeater_meta( $post_id, SLN_DM_PRICING_PLANS_META, 'sln_dm_pricing_plans', 'sln_dm_sanitize_pricing_plan' );
}

/**
 * Sanitize FAQ section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, string>
 */
function sln_dm_sanitize_faq_section( $raw ) {
	return array(
		'small_heading'    => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'     => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_text' => sanitize_text_field( $raw['highlighted_text'] ?? '' ),
		'description'      => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
	);
}

/**
 * Sanitize FAQ item row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_dm_sanitize_faq_item( $raw ) {
	return array(
		'question' => sanitize_text_field( $raw['question'] ?? '' ),
		'answer'   => sln_growth_page_sanitize_wysiwyg_content( $raw['answer'] ?? '' ),
		'active'   => ! empty( $raw['active'] ),
	);
}

/**
 * Save FAQ meta.
 *
 * @param int $post_id Post ID.
 */
function sln_dm_save_faq_meta( $post_id ) {
	sln_dm_save_section_meta( $post_id, SLN_DM_FAQ_SECTION_META, 'sln_dm_faq_section', 'sln_dm_sanitize_faq_section' );
	sln_dm_save_repeater_meta( $post_id, SLN_DM_FAQ_ITEMS_META, 'sln_dm_faq_items', 'sln_dm_sanitize_faq_item' );
}

/**
 * Sanitize final CTA benefit row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_dm_sanitize_final_cta_benefit( $raw ) {
	return array(
		'text'   => sanitize_text_field( $raw['text'] ?? '' ),
		'active' => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize final CTA benefits list.
 *
 * @param mixed $raw Raw benefits list.
 * @return array<int, array<string, mixed>>
 */
function sln_dm_sanitize_final_cta_benefits( $raw ) {
	if ( ! is_array( $raw ) ) {
		return array();
	}

	$benefits = array();

	foreach ( $raw as $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}

		$benefit = sln_dm_sanitize_final_cta_benefit( $item );

		if ( '' !== $benefit['text'] ) {
			$benefits[] = $benefit;
		}
	}

	return $benefits;
}

/**
 * Sanitize final CTA section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_dm_sanitize_final_cta( $raw ) {
	return array(
		'small_heading'    => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'     => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_text' => sanitize_text_field( $raw['highlighted_text'] ?? '' ),
		'description'      => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'benefits'         => sln_dm_sanitize_final_cta_benefits( $raw['benefits'] ?? array() ),
		'button_text'      => sanitize_text_field( $raw['button_text'] ?? '' ),
		'button_url'       => sln_dm_sanitize_url( $raw['button_url'] ?? '' ),
		'website_text'     => sanitize_text_field( $raw['website_text'] ?? '' ),
		'website_url'      => sln_dm_sanitize_url( $raw['website_url'] ?? '' ),
		'bottom_note'      => sanitize_text_field( $raw['bottom_note'] ?? '' ),
		'active'           => ! empty( $raw['active'] ),
	);
}

/**
 * Save final CTA meta.
 *
 * @param int $post_id Post ID.
 */
function sln_dm_save_final_cta_meta( $post_id ) {
	sln_dm_save_section_meta( $post_id, SLN_DM_FINAL_CTA_META, 'sln_dm_final_cta', 'sln_dm_sanitize_final_cta' );
}
