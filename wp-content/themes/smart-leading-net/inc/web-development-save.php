<?php
/**
 * Web Development Services page — save handlers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register save hook for Web Development pages.
 */
function sln_wd_register_save_hooks() {
	add_action( 'save_post_page', 'sln_wd_save_meta', 10, 2 );
}
add_action( 'init', 'sln_wd_register_save_hooks', 20 );

/**
 * Output master save nonce after title.
 *
 * @param WP_Post $post Current post.
 */
function sln_wd_output_save_nonce( $post ) {
	if ( ! $post instanceof WP_Post || 'page' !== $post->post_type ) {
		return;
	}

	wp_nonce_field( 'sln_wd_save_meta', 'sln_wd_master_nonce', false );
}
add_action( 'edit_form_after_title', 'sln_wd_output_save_nonce' );

/**
 * Whether save should proceed.
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function sln_wd_should_save_meta( $post_id ) {
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

	if ( ! isset( $_POST['sln_wd_master_nonce'] ) ) {
		return false;
	}

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sln_wd_master_nonce'] ) ), 'sln_wd_save_meta' ) ) {
		return false;
	}

	$template = isset( $_POST['page_template'] )
		? sanitize_text_field( wp_unslash( $_POST['page_template'] ) )
		: get_page_template_slug( $post_id );

	return SLN_WD_TEMPLATE === $template;
}

/**
 * Persist associative section when posted.
 *
 * @param int      $post_id  Post ID.
 * @param string   $meta_key Meta key.
 * @param string   $post_key POST key.
 * @param callable $sanitize Sanitizer callback.
 */
function sln_wd_save_section_meta( $post_id, $meta_key, $post_key, $sanitize ) {
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
function sln_wd_save_repeater_meta( $post_id, $meta_key, $post_key, $sanitize_row ) {
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
function sln_wd_sanitize_string_list( $raw ) {
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
 * Sanitize newline textarea or posted array into string list.
 *
 * @param mixed $raw Raw value.
 * @return array<int, string>
 */
function sln_wd_sanitize_textarea_list( $raw ) {
	if ( is_array( $raw ) ) {
		return sln_wd_sanitize_string_list( $raw );
	}

	$list  = array();
	$lines = preg_split( '/\r\n|\r|\n/', (string) $raw );

	if ( ! is_array( $lines ) ) {
		return $list;
	}

	foreach ( $lines as $line ) {
		$line = sanitize_text_field( trim( $line ) );

		if ( '' !== $line ) {
			$list[] = $line;
		}
	}

	return $list;
}

/**
 * Save all Web Development meta.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 */
function sln_wd_save_meta( $post_id, $post ) {
	unset( $post );

	if ( ! sln_wd_should_save_meta( $post_id ) ) {
		return;
	}

	sln_wd_save_hero_meta( $post_id );
	sln_wd_save_stats_meta( $post_id );
	sln_wd_save_platforms_meta( $post_id );
	sln_wd_save_extras_meta( $post_id );
	sln_wd_save_matters_meta( $post_id );
	sln_wd_save_process_meta( $post_id );
	sln_wd_save_promises_meta( $post_id );
	sln_wd_save_services_meta( $post_id );
	sln_wd_save_compare_meta( $post_id );
	sln_wd_save_benefits_meta( $post_id );
	sln_wd_save_cases_meta( $post_id );
	sln_wd_save_pricing_meta( $post_id );
	sln_wd_save_care_meta( $post_id );
	sln_wd_save_faq_meta( $post_id );
	sln_wd_save_final_meta( $post_id );
}

/**
 * Sanitize hero section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_hero( $raw ) {
	return array(
		'trust_badge'           => sanitize_text_field( $raw['trust_badge'] ?? '' ),
		'certified_text'        => sanitize_text_field( $raw['certified_text'] ?? '' ),
		'main_heading'          => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_text'      => sanitize_text_field( $raw['highlighted_text'] ?? '' ),
		'description'           => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'primary_button_text'   => sanitize_text_field( $raw['primary_button_text'] ?? '' ),
		'primary_button_url'    => sln_wd_sanitize_url( $raw['primary_button_url'] ?? '' ),
		'secondary_button_text' => sanitize_text_field( $raw['secondary_button_text'] ?? '' ),
		'secondary_button_url'  => sln_wd_sanitize_url( $raw['secondary_button_url'] ?? '' ),
		'hero_image_id'         => sln_sanitize_media_attachment_id( $raw['hero_image_id'] ?? 0 ),
		'float_stat_1_label'    => sanitize_text_field( $raw['float_stat_1_label'] ?? '' ),
		'float_stat_1_value'    => sanitize_text_field( $raw['float_stat_1_value'] ?? '' ),
		'float_stat_2_label'    => sanitize_text_field( $raw['float_stat_2_label'] ?? '' ),
		'float_stat_2_value'    => sanitize_text_field( $raw['float_stat_2_value'] ?? '' ),
		'float_stat_3_label'    => sanitize_text_field( $raw['float_stat_3_label'] ?? '' ),
		'float_stat_3_value'    => sanitize_text_field( $raw['float_stat_3_value'] ?? '' ),
		'float_stat_4_label'    => sanitize_text_field( $raw['float_stat_4_label'] ?? '' ),
		'float_stat_4_value'    => sanitize_text_field( $raw['float_stat_4_value'] ?? '' ),
		'float_stat_5_label'    => sanitize_text_field( $raw['float_stat_5_label'] ?? '' ),
		'float_stat_5_value'    => sanitize_text_field( $raw['float_stat_5_value'] ?? '' ),
		'rating_line'           => sanitize_text_field( $raw['rating_line'] ?? '' ),
		'active'                => ! empty( $raw['active'] ),
	);
}

/**
 * Save hero meta.
 *
 * @param int $post_id Post ID.
 */
function sln_wd_save_hero_meta( $post_id ) {
	sln_wd_save_section_meta( $post_id, SLN_WD_HERO_META, 'sln_wd_hero', 'sln_wd_sanitize_hero' );
}

/**
 * Sanitize stat row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_stat_row( $raw ) {
	$row = array(
		'prefix'  => sanitize_text_field( $raw['prefix'] ?? '' ),
		'value'   => sanitize_text_field( $raw['value'] ?? '' ),
		'suffix'  => sanitize_text_field( $raw['suffix'] ?? '' ),
		'label'   => sanitize_text_field( $raw['label'] ?? '' ),
		'icon_id' => sln_sanitize_media_attachment_id( $raw['icon_id'] ?? 0 ),
		'active'  => ! empty( $raw['active'] ),
	);

	if ( '' === $row['label'] ) {
		return array();
	}

	return $row;
}

/**
 * Save stats meta.
 *
 * @param int $post_id Post ID.
 */
function sln_wd_save_stats_meta( $post_id ) {
	sln_wd_save_repeater_meta( $post_id, SLN_WD_STATS_META, 'sln_wd_stats', 'sln_wd_sanitize_stat_row' );
}

/**
 * Sanitize platforms section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_platforms_section( $raw ) {
	return array(
		'small_heading'    => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'     => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_text' => sanitize_text_field( $raw['highlighted_text'] ?? '' ),
		'description'      => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'bottom_note'      => sanitize_text_field( $raw['bottom_note'] ?? '' ),
		'active'           => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize platform card row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_platform_row( $raw ) {
	$row = array(
		'title'       => sanitize_text_field( $raw['title'] ?? '' ),
		'description' => sanitize_textarea_field( $raw['description'] ?? '' ),
		'icon_id'     => sln_sanitize_media_attachment_id( $raw['icon_id'] ?? 0 ),
		'url'         => sln_wd_sanitize_url( $raw['url'] ?? '' ),
		'active'      => ! empty( $raw['active'] ),
	);

	if ( '' === $row['title'] ) {
		return array();
	}

	return $row;
}

/**
 * Save platforms meta.
 *
 * @param int $post_id Post ID.
 */
function sln_wd_save_platforms_meta( $post_id ) {
	sln_wd_save_section_meta( $post_id, SLN_WD_PLATFORMS_SECTION_META, 'sln_wd_platforms_section', 'sln_wd_sanitize_platforms_section' );
	sln_wd_save_repeater_meta( $post_id, SLN_WD_PLATFORMS_META, 'sln_wd_platforms', 'sln_wd_sanitize_platform_row' );
}

/**
 * Sanitize extras section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_extras_section( $raw ) {
	return array(
		'heading'     => sanitize_text_field( $raw['heading'] ?? '' ),
		'description' => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'active'      => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize extras item row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_extra_row( $raw ) {
	$row = array(
		'title'       => sanitize_text_field( $raw['title'] ?? '' ),
		'description' => sanitize_textarea_field( $raw['description'] ?? '' ),
		'icon_id'     => sln_sanitize_media_attachment_id( $raw['icon_id'] ?? 0 ),
		'url'         => sln_wd_sanitize_url( $raw['url'] ?? '' ),
		'active'      => ! empty( $raw['active'] ),
	);

	if ( '' === $row['title'] ) {
		return array();
	}

	return $row;
}

/**
 * Save extras meta.
 *
 * @param int $post_id Post ID.
 */
function sln_wd_save_extras_meta( $post_id ) {
	sln_wd_save_section_meta( $post_id, SLN_WD_EXTRAS_SECTION_META, 'sln_wd_extras_section', 'sln_wd_sanitize_extras_section' );
	sln_wd_save_repeater_meta( $post_id, SLN_WD_EXTRAS_META, 'sln_wd_extras', 'sln_wd_sanitize_extra_row' );
}

/**
 * Sanitize matters section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_matters_section( $raw ) {
	return array(
		'small_heading'    => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'     => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_text' => sanitize_text_field( $raw['highlighted_text'] ?? '' ),
		'description'      => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'image_id'         => sln_sanitize_media_attachment_id( $raw['image_id'] ?? 0 ),
		'active'           => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize matters card row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_matters_card_row( $raw ) {
	$row = array(
		'title'       => sanitize_text_field( $raw['title'] ?? '' ),
		'description' => sanitize_textarea_field( $raw['description'] ?? '' ),
		'icon_id'     => sln_sanitize_media_attachment_id( $raw['icon_id'] ?? 0 ),
		'active'      => ! empty( $raw['active'] ),
	);

	if ( '' === $row['title'] ) {
		return array();
	}

	return $row;
}

/**
 * Save matters meta.
 *
 * @param int $post_id Post ID.
 */
function sln_wd_save_matters_meta( $post_id ) {
	sln_wd_save_section_meta( $post_id, SLN_WD_MATTERS_SECTION_META, 'sln_wd_matters_section', 'sln_wd_sanitize_matters_section' );
	sln_wd_save_repeater_meta( $post_id, SLN_WD_MATTERS_CARDS_META, 'sln_wd_matters_cards', 'sln_wd_sanitize_matters_card_row' );
}

/**
 * Sanitize process section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_process_section( $raw ) {
	return array(
		'small_heading'    => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'     => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_text' => sanitize_text_field( $raw['highlighted_text'] ?? '' ),
		'description'      => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'active'           => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize nested process step row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_process_step( $raw ) {
	$row = array(
		'number'      => sanitize_text_field( $raw['number'] ?? '' ),
		'title'       => sanitize_text_field( $raw['title'] ?? '' ),
		'description' => sanitize_textarea_field( $raw['description'] ?? '' ),
		'active'      => ! empty( $raw['active'] ),
	);

	if ( '' === $row['title'] ) {
		return array();
	}

	return $row;
}

/**
 * Sanitize process steps list.
 *
 * @param mixed $raw Raw steps list.
 * @return array<int, array<string, mixed>>
 */
function sln_wd_sanitize_process_steps( $raw ) {
	if ( ! is_array( $raw ) ) {
		return array();
	}

	$steps = array();

	foreach ( $raw as $step_raw ) {
		if ( ! is_array( $step_raw ) ) {
			continue;
		}

		$step = sln_wd_sanitize_process_step( $step_raw );

		if ( ! empty( $step ) ) {
			$steps[] = $step;
		}
	}

	return $steps;
}

/**
 * Sanitize process stage row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_process_stage( $raw ) {
	$row = array(
		'number' => sanitize_text_field( $raw['number'] ?? '' ),
		'title'  => sanitize_text_field( $raw['title'] ?? '' ),
		'steps'  => sln_wd_sanitize_process_steps( $raw['steps'] ?? array() ),
		'active' => ! empty( $raw['active'] ),
	);

	if ( '' === $row['title'] ) {
		return array();
	}

	return $row;
}

/**
 * Save process meta.
 *
 * @param int $post_id Post ID.
 */
function sln_wd_save_process_meta( $post_id ) {
	sln_wd_save_section_meta( $post_id, SLN_WD_PROCESS_SECTION_META, 'sln_wd_process_section', 'sln_wd_sanitize_process_section' );
	sln_wd_save_repeater_meta( $post_id, SLN_WD_PROCESS_STAGES_META, 'sln_wd_process_stages', 'sln_wd_sanitize_process_stage' );
}

/**
 * Sanitize promises section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_promises_section( $raw ) {
	return array(
		'heading' => sanitize_text_field( $raw['heading'] ?? '' ),
		'active'  => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize promise card row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_promise_row( $raw ) {
	$row = array(
		'icon_id'         => sln_sanitize_media_attachment_id( $raw['icon_id'] ?? 0 ),
		'main_text'       => sanitize_text_field( $raw['main_text'] ?? '' ),
		'supporting_text' => sanitize_text_field( $raw['supporting_text'] ?? '' ),
		'active'          => ! empty( $raw['active'] ),
	);

	if ( '' === $row['main_text'] ) {
		return array();
	}

	return $row;
}

/**
 * Save promises meta.
 *
 * @param int $post_id Post ID.
 */
function sln_wd_save_promises_meta( $post_id ) {
	sln_wd_save_section_meta( $post_id, SLN_WD_PROMISES_SECTION_META, 'sln_wd_promises_section', 'sln_wd_sanitize_promises_section' );
	sln_wd_save_repeater_meta( $post_id, SLN_WD_PROMISES_META, 'sln_wd_promises', 'sln_wd_sanitize_promise_row' );
}

/**
 * Sanitize services section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_services_section( $raw ) {
	return array(
		'small_heading' => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'  => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'description'   => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'active'        => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize service card row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_service_row( $raw ) {
	$row = array(
		'title'       => sanitize_text_field( $raw['title'] ?? '' ),
		'description' => sanitize_textarea_field( $raw['description'] ?? '' ),
		'icon_id'     => sln_sanitize_media_attachment_id( $raw['icon_id'] ?? 0 ),
		'url'         => sln_wd_sanitize_url( $raw['url'] ?? '' ),
		'active'      => ! empty( $raw['active'] ),
	);

	if ( '' === $row['title'] ) {
		return array();
	}

	return $row;
}

/**
 * Save services meta.
 *
 * @param int $post_id Post ID.
 */
function sln_wd_save_services_meta( $post_id ) {
	sln_wd_save_section_meta( $post_id, SLN_WD_SERVICES_SECTION_META, 'sln_wd_services_section', 'sln_wd_sanitize_services_section' );
	sln_wd_save_repeater_meta( $post_id, SLN_WD_SERVICES_META, 'sln_wd_services', 'sln_wd_sanitize_service_row' );
}

/**
 * Sanitize compare section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_compare_section( $raw ) {
	return array(
		'small_heading'    => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'     => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_text' => sanitize_text_field( $raw['highlighted_text'] ?? '' ),
		'description'      => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'col_features'     => sanitize_text_field( $raw['col_features'] ?? '' ),
		'col_sl'           => sanitize_text_field( $raw['col_sl'] ?? '' ),
		'col_inhouse'      => sanitize_text_field( $raw['col_inhouse'] ?? '' ),
		'col_agency'       => sanitize_text_field( $raw['col_agency'] ?? '' ),
		'active'           => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize compare table row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_compare_row( $raw ) {
	$row = array(
		'feature'       => sanitize_text_field( $raw['feature'] ?? '' ),
		'smart_leading' => sanitize_text_field( $raw['smart_leading'] ?? '' ),
		'in_house'      => sanitize_text_field( $raw['in_house'] ?? '' ),
		'agency'        => sanitize_text_field( $raw['agency'] ?? '' ),
		'active'        => ! empty( $raw['active'] ),
	);

	if ( '' === $row['feature'] ) {
		return array();
	}

	return $row;
}

/**
 * Save compare meta.
 *
 * @param int $post_id Post ID.
 */
function sln_wd_save_compare_meta( $post_id ) {
	sln_wd_save_section_meta( $post_id, SLN_WD_COMPARE_SECTION_META, 'sln_wd_compare_section', 'sln_wd_sanitize_compare_section' );
	sln_wd_save_repeater_meta( $post_id, SLN_WD_COMPARE_ROWS_META, 'sln_wd_compare_rows', 'sln_wd_sanitize_compare_row' );
}

/**
 * Sanitize benefits section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_benefits_section( $raw ) {
	return array(
		'active' => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize summary benefit card row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_benefit_card_row( $raw ) {
	$row = array(
		'main_text'       => sanitize_text_field( $raw['main_text'] ?? '' ),
		'supporting_text' => sanitize_text_field( $raw['supporting_text'] ?? '' ),
		'icon_id'         => sln_sanitize_media_attachment_id( $raw['icon_id'] ?? 0 ),
		'active'          => ! empty( $raw['active'] ),
	);

	if ( '' === $row['main_text'] ) {
		return array();
	}

	return $row;
}

/**
 * Save benefits meta.
 *
 * @param int $post_id Post ID.
 */
function sln_wd_save_benefits_meta( $post_id ) {
	sln_wd_save_section_meta( $post_id, SLN_WD_BENEFITS_SECTION_META, 'sln_wd_benefits_section', 'sln_wd_sanitize_benefits_section' );
	sln_wd_save_repeater_meta( $post_id, SLN_WD_BENEFITS_META, 'sln_wd_benefits', 'sln_wd_sanitize_benefit_card_row' );
}

/**
 * Sanitize cases section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_cases_section( $raw ) {
	return array(
		'small_heading' => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'  => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'description'   => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'active'        => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize case study row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_case_row( $raw ) {
	$row = array(
		'client_name'        => sanitize_text_field( $raw['client_name'] ?? '' ),
		'industry'           => sanitize_text_field( $raw['industry'] ?? '' ),
		'metric_value'       => sanitize_text_field( $raw['metric_value'] ?? '' ),
		'metric_description' => sanitize_textarea_field( $raw['metric_description'] ?? '' ),
		'image_id'           => sln_sanitize_media_attachment_id( $raw['image_id'] ?? 0 ),
		'project_url'        => sln_wd_sanitize_url( $raw['project_url'] ?? '' ),
		'active'             => ! empty( $raw['active'] ),
	);

	if ( '' === $row['client_name'] ) {
		return array();
	}

	return $row;
}

/**
 * Save cases meta.
 *
 * @param int $post_id Post ID.
 */
function sln_wd_save_cases_meta( $post_id ) {
	sln_wd_save_section_meta( $post_id, SLN_WD_CASES_SECTION_META, 'sln_wd_cases_section', 'sln_wd_sanitize_cases_section' );
	sln_wd_save_repeater_meta( $post_id, SLN_WD_CASES_META, 'sln_wd_cases', 'sln_wd_sanitize_case_row' );
}

/**
 * Sanitize pricing section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_pricing_section( $raw ) {
	return array(
		'small_heading'    => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'     => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_text' => sanitize_text_field( $raw['highlighted_text'] ?? '' ),
		'description'      => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'active'           => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize pricing plan row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_pricing_plan_row( $raw ) {
	$row = array(
		'name'        => sanitize_text_field( $raw['name'] ?? '' ),
		'price'       => sanitize_text_field( $raw['price'] ?? '' ),
		'timeline'    => sanitize_text_field( $raw['timeline'] ?? '' ),
		'description' => sanitize_textarea_field( $raw['description'] ?? '' ),
		'features'    => sln_wd_sanitize_string_list( $raw['features'] ?? array() ),
		'button_text' => sanitize_text_field( $raw['button_text'] ?? '' ),
		'button_url'  => sln_wd_sanitize_url( $raw['button_url'] ?? '' ),
		'is_popular'  => ! empty( $raw['is_popular'] ),
		'active'      => ! empty( $raw['active'] ),
	);

	if ( '' === $row['name'] ) {
		return array();
	}

	return $row;
}

/**
 * Save pricing meta.
 *
 * @param int $post_id Post ID.
 */
function sln_wd_save_pricing_meta( $post_id ) {
	sln_wd_save_section_meta( $post_id, SLN_WD_PRICING_SECTION_META, 'sln_wd_pricing_section', 'sln_wd_sanitize_pricing_section' );
	sln_wd_save_repeater_meta( $post_id, SLN_WD_PRICING_PLANS_META, 'sln_wd_pricing_plans', 'sln_wd_sanitize_pricing_plan_row' );
}

/**
 * Sanitize care section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_care_section( $raw ) {
	return array(
		'small_heading' => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'  => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'description'   => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'active'        => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize care plan row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_care_plan_row( $raw ) {
	$row = array(
		'name'        => sanitize_text_field( $raw['name'] ?? '' ),
		'price'       => sanitize_text_field( $raw['price'] ?? '' ),
		'description' => sanitize_textarea_field( $raw['description'] ?? '' ),
		'features'    => sln_wd_sanitize_string_list( $raw['features'] ?? array() ),
		'button_text' => sanitize_text_field( $raw['button_text'] ?? '' ),
		'button_url'  => sln_wd_sanitize_url( $raw['button_url'] ?? '' ),
		'is_popular'  => ! empty( $raw['is_popular'] ),
		'active'      => ! empty( $raw['active'] ),
	);

	if ( '' === $row['name'] ) {
		return array();
	}

	return $row;
}

/**
 * Save care meta.
 *
 * @param int $post_id Post ID.
 */
function sln_wd_save_care_meta( $post_id ) {
	sln_wd_save_section_meta( $post_id, SLN_WD_CARE_SECTION_META, 'sln_wd_care_section', 'sln_wd_sanitize_care_section' );
	sln_wd_save_repeater_meta( $post_id, SLN_WD_CARE_PLANS_META, 'sln_wd_care_plans', 'sln_wd_sanitize_care_plan_row' );
}

/**
 * Sanitize FAQ section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_faq_section( $raw ) {
	return array(
		'small_heading'    => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'     => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_text' => sanitize_text_field( $raw['highlighted_text'] ?? '' ),
		'description'      => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'active'           => ! empty( $raw['active'] ),
	);
}

/**
 * Sanitize FAQ item row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_faq_item_row( $raw ) {
	$row = array(
		'question' => sanitize_text_field( $raw['question'] ?? '' ),
		'answer'   => sln_growth_page_sanitize_wysiwyg_content( $raw['answer'] ?? '' ),
		'active'   => ! empty( $raw['active'] ),
	);

	if ( '' === $row['question'] ) {
		return array();
	}

	return $row;
}

/**
 * Save FAQ meta.
 *
 * @param int $post_id Post ID.
 */
function sln_wd_save_faq_meta( $post_id ) {
	sln_wd_save_section_meta( $post_id, SLN_WD_FAQ_SECTION_META, 'sln_wd_faq_section', 'sln_wd_sanitize_faq_section' );
	sln_wd_save_repeater_meta( $post_id, SLN_WD_FAQ_ITEMS_META, 'sln_wd_faq_items', 'sln_wd_sanitize_faq_item_row' );
}

/**
 * Sanitize final CTA benefit row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_wd_sanitize_final_benefit_row( $raw ) {
	$row = array(
		'text'   => sanitize_text_field( $raw['text'] ?? '' ),
		'active' => ! empty( $raw['active'] ),
	);

	if ( '' === $row['text'] ) {
		return array();
	}

	return $row;
}

/**
 * Sanitize final CTA benefits list.
 *
 * @param mixed $raw Raw benefits list.
 * @return array<int, array<string, mixed>>
 */
function sln_wd_sanitize_final_benefits( $raw ) {
	if ( ! is_array( $raw ) ) {
		return array();
	}

	$benefits = array();

	foreach ( $raw as $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}

		$benefit = sln_wd_sanitize_final_benefit_row( $item );

		if ( ! empty( $benefit ) ) {
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
function sln_wd_sanitize_final_section( $raw ) {
	return array(
		'small_heading'       => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'        => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_text'    => sanitize_text_field( $raw['highlighted_text'] ?? '' ),
		'description'         => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'benefits'            => sln_wd_sanitize_final_benefits( $raw['benefits'] ?? array() ),
		'form_heading'        => sanitize_text_field( $raw['form_heading'] ?? '' ),
		'name_label'          => sanitize_text_field( $raw['name_label'] ?? '' ),
		'name_placeholder'    => sanitize_text_field( $raw['name_placeholder'] ?? '' ),
		'email_label'         => sanitize_text_field( $raw['email_label'] ?? '' ),
		'email_placeholder'   => sanitize_text_field( $raw['email_placeholder'] ?? '' ),
		'website_label'       => sanitize_text_field( $raw['website_label'] ?? '' ),
		'website_placeholder' => sanitize_text_field( $raw['website_placeholder'] ?? '' ),
		'country_label'       => sanitize_text_field( $raw['country_label'] ?? '' ),
		'country_placeholder' => sanitize_text_field( $raw['country_placeholder'] ?? '' ),
		'need_label'          => sanitize_text_field( $raw['need_label'] ?? '' ),
		'need_options'        => sln_wd_sanitize_textarea_list( $raw['need_options'] ?? '' ),
		'budget_label'        => sanitize_text_field( $raw['budget_label'] ?? '' ),
		'budget_options'      => sln_wd_sanitize_textarea_list( $raw['budget_options'] ?? '' ),
		'message_label'       => sanitize_text_field( $raw['message_label'] ?? '' ),
		'message_placeholder' => sanitize_text_field( $raw['message_placeholder'] ?? '' ),
		'submit_text'         => sanitize_text_field( $raw['submit_text'] ?? '' ),
		'form_note'           => sanitize_text_field( $raw['form_note'] ?? '' ),
		'thank_you_url'       => sln_wd_sanitize_url( $raw['thank_you_url'] ?? '' ),
		'countries'           => sln_wd_sanitize_textarea_list( $raw['countries'] ?? '' ),
		'active'              => ! empty( $raw['active'] ),
	);
}

/**
 * Save final CTA meta.
 *
 * @param int $post_id Post ID.
 */
function sln_wd_save_final_meta( $post_id ) {
	sln_wd_save_section_meta( $post_id, SLN_WD_FINAL_SECTION_META, 'sln_wd_final_section', 'sln_wd_sanitize_final_section' );
}
