<?php
/**
 * AEO Services page — save handlers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register save hook for AEO Services pages.
 */
function sln_aeo_register_save_hooks() {
	add_action( 'save_post_page', 'sln_aeo_save_meta', 10, 2 );
}
add_action( 'init', 'sln_aeo_register_save_hooks', 20 );

/**
 * Print the AEO save nonce once per request.
 */
function sln_aeo_print_save_nonce() {
	static $printed = false;

	if ( $printed ) {
		return;
	}

	wp_nonce_field( 'sln_aeo_save_meta', 'sln_aeo_master_nonce', false );
	$printed = true;
}

/**
 * Output master save nonce after title (classic editor).
 *
 * @param WP_Post $post Current post.
 */
function sln_aeo_output_save_nonce( $post ) {
	if ( ! $post instanceof WP_Post || 'page' !== $post->post_type ) {
		return;
	}

	sln_aeo_print_save_nonce();
}
add_action( 'edit_form_after_title', 'sln_aeo_output_save_nonce' );

/**
 * Output save nonce in the block editor metabox form.
 *
 * @param WP_Post $post Current post.
 */
function sln_aeo_output_block_editor_save_nonce( $post ) {
	if ( ! $post instanceof WP_Post || 'page' !== $post->post_type ) {
		return;
	}

	if ( function_exists( 'sln_aeo_admin_is_target_page' ) && ! sln_aeo_admin_is_target_page( $post ) ) {
		return;
	}

	sln_aeo_print_save_nonce();
}
add_action( 'block_editor_meta_box_hidden_fields', 'sln_aeo_output_block_editor_save_nonce' );

/**
 * Whether save should proceed.
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function sln_aeo_should_save_meta( $post_id ) {
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

	if ( ! isset( $_POST['sln_aeo_master_nonce'] ) ) {
		return false;
	}

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sln_aeo_master_nonce'] ) ), 'sln_aeo_save_meta' ) ) {
		return false;
	}

	$template = isset( $_POST['page_template'] )
		? sanitize_text_field( wp_unslash( $_POST['page_template'] ) )
		: get_page_template_slug( $post_id );

	$expected = defined( 'SLN_AEO_TEMPLATE' ) ? SLN_AEO_TEMPLATE : 'aeo-page-template.php';

	if ( $expected === $template || basename( (string) $template ) === $expected ) {
		return true;
	}

	return function_exists( 'sln_aeo_admin_is_target_page' ) && sln_aeo_admin_is_target_page( get_post( $post_id ) );
}

/**
 * Save all AEO Services meta.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 */
function sln_aeo_save_meta( $post_id, $post ) {
	unset( $post );

	if ( ! sln_aeo_should_save_meta( $post_id ) ) {
		return;
	}

	sln_aeo_save_section_meta( $post_id, SLN_AEO_HERO_META, 'sln_aeo_hero', 'sln_aeo_sanitize_hero' );
	sln_aeo_save_repeater_meta( $post_id, SLN_AEO_HERO_PROOF_META, 'sln_aeo_hero_proof', 'sln_aeo_sanitize_proof_item', array( 'label' ) );
	sln_aeo_save_section_meta( $post_id, SLN_AEO_EVOLUTION_SECTION_META, 'sln_aeo_evolution_section', 'sln_aeo_sanitize_intro_section' );
	sln_aeo_save_repeater_meta( $post_id, SLN_AEO_EVOLUTION_STAGES_META, 'sln_aeo_evolution_stages', 'sln_aeo_sanitize_evolution_stage', array( 'label', 'title', 'description' ) );
	sln_aeo_save_section_meta( $post_id, SLN_AEO_WHY_SECTION_META, 'sln_aeo_why_section', 'sln_aeo_sanitize_intro_section' );
	sln_aeo_save_repeater_meta( $post_id, SLN_AEO_WHY_CARDS_META, 'sln_aeo_why_cards', 'sln_aeo_sanitize_why_card', array( 'title', 'description' ) );
	sln_aeo_save_section_meta( $post_id, SLN_AEO_STRATEGY_SECTION_META, 'sln_aeo_strategy_section', 'sln_aeo_sanitize_intro_section' );
	sln_aeo_save_repeater_meta( $post_id, SLN_AEO_STRATEGY_STEPS_META, 'sln_aeo_strategy_steps', 'sln_aeo_sanitize_strategy_step', array( 'number', 'title', 'description' ) );
	sln_aeo_save_section_meta( $post_id, SLN_AEO_SERVICES_SECTION_META, 'sln_aeo_services_section', 'sln_aeo_sanitize_intro_section' );
	sln_aeo_save_repeater_meta( $post_id, SLN_AEO_SERVICES_ITEMS_META, 'sln_aeo_services_items', 'sln_aeo_sanitize_service_item', array( 'title', 'description' ) );
	sln_aeo_save_section_meta( $post_id, SLN_AEO_HOW_SECTION_META, 'sln_aeo_how_section', 'sln_aeo_sanitize_intro_section' );
	sln_aeo_save_repeater_meta( $post_id, SLN_AEO_HOW_STEPS_META, 'sln_aeo_how_steps', 'sln_aeo_sanitize_how_step', array( 'title', 'description' ) );
	sln_aeo_save_section_meta( $post_id, SLN_AEO_COMPARISON_SECTION_META, 'sln_aeo_comparison_section', 'sln_aeo_sanitize_comparison_section' );
	sln_aeo_save_repeater_meta( $post_id, SLN_AEO_COMPARISON_ROWS_META, 'sln_aeo_comparison_rows', 'sln_aeo_sanitize_comparison_row', array( 'seo', 'aeo' ) );
	sln_aeo_save_section_meta( $post_id, SLN_AEO_PLATFORMS_SECTION_META, 'sln_aeo_platforms_section', 'sln_aeo_sanitize_heading_section' );
	sln_aeo_save_repeater_meta( $post_id, SLN_AEO_PLATFORM_ITEMS_META, 'sln_aeo_platform_items', 'sln_aeo_sanitize_platform_item', array( 'name' ) );
	sln_aeo_save_section_meta( $post_id, SLN_AEO_INDUSTRIES_SECTION_META, 'sln_aeo_industries_section', 'sln_aeo_sanitize_heading_section' );
	sln_aeo_save_repeater_meta( $post_id, SLN_AEO_INDUSTRY_ITEMS_META, 'sln_aeo_industry_items', 'sln_aeo_sanitize_industry_item', array( 'title' ) );
	sln_aeo_save_section_meta( $post_id, SLN_AEO_RESULTS_SECTION_META, 'sln_aeo_results_section', 'sln_aeo_sanitize_intro_section' );
	sln_aeo_save_repeater_meta( $post_id, SLN_AEO_RESULT_ITEMS_META, 'sln_aeo_result_items', 'sln_aeo_sanitize_result_item', array( 'title', 'description' ) );
	sln_aeo_save_section_meta( $post_id, SLN_AEO_FAQ_SECTION_META, 'sln_aeo_faq_section', 'sln_aeo_sanitize_heading_section' );
	sln_aeo_save_repeater_meta( $post_id, SLN_AEO_FAQ_ITEMS_META, 'sln_aeo_faq_items', 'sln_aeo_sanitize_faq_item', array( 'question', 'answer' ) );
	sln_aeo_save_section_meta( $post_id, SLN_AEO_FINAL_CTA_META, 'sln_aeo_final_cta', 'sln_aeo_sanitize_final_cta' );
}

/**
 * Persist associative section when posted.
 *
 * @param int      $post_id  Post ID.
 * @param string   $meta_key Meta key.
 * @param string   $post_key POST key.
 * @param callable $sanitize Sanitizer callback.
 */
function sln_aeo_save_section_meta( $post_id, $meta_key, $post_key, $sanitize ) {
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
 * @param array    $content_keys Keys that indicate the row has content.
 */
function sln_aeo_save_repeater_meta( $post_id, $meta_key, $post_key, $sanitize_row, $content_keys = array() ) {
	$posted_flag = $post_key . '_posted';

	if ( ! isset( $_POST[ $post_key ] ) && ! isset( $_POST[ $posted_flag ] ) ) {
		return;
	}

	if ( ! isset( $_POST[ $post_key ] ) || ! is_array( $_POST[ $post_key ] ) ) {
		update_post_meta( $post_id, $meta_key, array() );
		return;
	}

	$items = array();

	foreach ( wp_unslash( $_POST[ $post_key ] ) as $raw_row ) {
		if ( ! is_array( $raw_row ) ) {
			continue;
		}

		$row = call_user_func( $sanitize_row, $raw_row );

		if ( empty( $row ) ) {
			continue;
		}

		if ( ! empty( $content_keys ) && ! sln_aeo_repeater_row_has_content( $row, $content_keys ) ) {
			continue;
		}

		$items[] = $row;
	}

	update_post_meta( $post_id, $meta_key, $items );
}

/**
 * Whether a sanitized repeater row has any content.
 *
 * @param array<string, mixed> $row  Row data.
 * @param array<int, string>   $keys Content keys.
 * @return bool
 */
function sln_aeo_repeater_row_has_content( $row, $keys ) {
	foreach ( $keys as $key ) {
		if ( '' !== trim( (string) ( $row[ $key ] ?? '' ) ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Sanitize heading-only section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, string>
 */
function sln_aeo_sanitize_heading_section( $raw ) {
	return array(
		'eyebrow' => sanitize_text_field( $raw['eyebrow'] ?? '' ),
		'heading' => sanitize_text_field( $raw['heading'] ?? '' ),
	);
}

/**
 * Sanitize intro section (eyebrow, heading, description).
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, string>
 */
function sln_aeo_sanitize_intro_section( $raw ) {
	return array(
		'eyebrow'     => sanitize_text_field( $raw['eyebrow'] ?? '' ),
		'heading'     => sanitize_text_field( $raw['heading'] ?? '' ),
		'description' => sanitize_textarea_field( $raw['description'] ?? '' ),
	);
}

/**
 * Sanitize hero section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, string>
 */
function sln_aeo_sanitize_hero( $raw ) {
	return array(
		'eyebrow'            => sanitize_text_field( $raw['eyebrow'] ?? '' ),
		'heading'            => sanitize_text_field( $raw['heading'] ?? '' ),
		'accent'             => sanitize_text_field( $raw['accent'] ?? '' ),
		'heading_suffix'     => sanitize_text_field( $raw['heading_suffix'] ?? '' ),
		'description'        => sanitize_textarea_field( $raw['description'] ?? '' ),
		'primary_cta_text'   => sanitize_text_field( $raw['primary_cta_text'] ?? '' ),
		'primary_cta_url'    => sln_aeo_sanitize_url( $raw['primary_cta_url'] ?? '' ),
		'secondary_cta_text' => sanitize_text_field( $raw['secondary_cta_text'] ?? '' ),
		'secondary_cta_url'  => sln_aeo_sanitize_url( $raw['secondary_cta_url'] ?? '' ),
	);
}

/**
 * Sanitize hero proof item.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_aeo_sanitize_proof_item( $raw ) {
	return array(
		'label'  => sanitize_text_field( $raw['label'] ?? '' ),
		'active' => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize evolution stage.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_aeo_sanitize_evolution_stage( $raw ) {
	return array(
		'icon'        => sln_aeo_sanitize_icon_key( $raw['icon'] ?? '' ),
		'label'       => sanitize_text_field( $raw['label'] ?? '' ),
		'title'       => sanitize_text_field( $raw['title'] ?? '' ),
		'description' => sanitize_textarea_field( $raw['description'] ?? '' ),
		'modifier'    => sln_aeo_sanitize_stage_modifier( $raw['modifier'] ?? '' ),
		'active'      => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize why-matters card.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_aeo_sanitize_why_card( $raw ) {
	return array(
		'icon'        => sln_aeo_sanitize_icon_key( $raw['icon'] ?? '' ),
		'title'       => sanitize_text_field( $raw['title'] ?? '' ),
		'description' => sanitize_textarea_field( $raw['description'] ?? '' ),
		'wide'        => ! empty( $raw['wide'] ),
		'active'      => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize strategy step.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_aeo_sanitize_strategy_step( $raw ) {
	return array(
		'number'      => sanitize_text_field( $raw['number'] ?? '' ),
		'title'       => sanitize_text_field( $raw['title'] ?? '' ),
		'description' => sanitize_textarea_field( $raw['description'] ?? '' ),
		'active'      => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize service item.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_aeo_sanitize_service_item( $raw ) {
	return array(
		'icon'        => sln_aeo_sanitize_icon_key( $raw['icon'] ?? '' ),
		'title'       => sanitize_text_field( $raw['title'] ?? '' ),
		'description' => sanitize_textarea_field( $raw['description'] ?? '' ),
		'active'      => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize how-it-works step.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_aeo_sanitize_how_step( $raw ) {
	return array(
		'icon'        => sln_aeo_sanitize_icon_key( $raw['icon'] ?? '' ),
		'title'       => sanitize_text_field( $raw['title'] ?? '' ),
		'description' => sanitize_textarea_field( $raw['description'] ?? '' ),
		'highlight'   => ! empty( $raw['highlight'] ),
		'active'      => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize comparison section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, string>
 */
function sln_aeo_sanitize_comparison_section( $raw ) {
	return array(
		'eyebrow'     => sanitize_text_field( $raw['eyebrow'] ?? '' ),
		'heading'     => sanitize_text_field( $raw['heading'] ?? '' ),
		'description' => sanitize_textarea_field( $raw['description'] ?? '' ),
		'seo_heading' => sanitize_text_field( $raw['seo_heading'] ?? '' ),
		'aeo_heading' => sanitize_text_field( $raw['aeo_heading'] ?? '' ),
	);
}

/**
 * Sanitize comparison row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_aeo_sanitize_comparison_row( $raw ) {
	return array(
		'seo'    => sanitize_text_field( $raw['seo'] ?? '' ),
		'aeo'    => sanitize_text_field( $raw['aeo'] ?? '' ),
		'active' => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize platform item.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_aeo_sanitize_platform_item( $raw ) {
	return array(
		'name'   => sanitize_text_field( $raw['name'] ?? '' ),
		'active' => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize industry item.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_aeo_sanitize_industry_item( $raw ) {
	return array(
		'icon'   => sln_aeo_sanitize_icon_key( $raw['icon'] ?? '' ),
		'title'  => sanitize_text_field( $raw['title'] ?? '' ),
		'active' => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize result item.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_aeo_sanitize_result_item( $raw ) {
	return array(
		'title'       => sanitize_text_field( $raw['title'] ?? '' ),
		'description' => sanitize_textarea_field( $raw['description'] ?? '' ),
		'active'      => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize FAQ item.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_aeo_sanitize_faq_item( $raw ) {
	return array(
		'question' => sanitize_text_field( $raw['question'] ?? '' ),
		'answer'   => sanitize_textarea_field( $raw['answer'] ?? '' ),
		'active'   => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize final CTA section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, string>
 */
function sln_aeo_sanitize_final_cta( $raw ) {
	return array(
		'eyebrow'            => sanitize_text_field( $raw['eyebrow'] ?? '' ),
		'heading'            => sanitize_text_field( $raw['heading'] ?? '' ),
		'description'        => sanitize_textarea_field( $raw['description'] ?? '' ),
		'primary_cta_text'   => sanitize_text_field( $raw['primary_cta_text'] ?? '' ),
		'primary_cta_url'    => sln_aeo_sanitize_url( $raw['primary_cta_url'] ?? '' ),
		'secondary_cta_text' => sanitize_text_field( $raw['secondary_cta_text'] ?? '' ),
		'secondary_cta_url'  => sln_aeo_sanitize_url( $raw['secondary_cta_url'] ?? '' ),
	);
}
