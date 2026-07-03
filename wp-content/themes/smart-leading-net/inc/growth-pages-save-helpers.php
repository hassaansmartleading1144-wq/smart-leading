<?php
/**
 * Growth Pages — shared save_post guards and persistence helpers.
 *
 * Every Growth Page metabox save callback should use sln_growth_page_should_save_meta()
 * so autosaves, revisions, nonce failures, and wrong post types never write meta.
 *
 * Repeater fields must use sln_growth_page_update_repeater_meta() so a missing POST key
 * (truncated request, hidden metabox, failed editor sync on another box) does not wipe
 * existing rows with an empty array.
 *
 * Section order is saved once from the sidebar metabox (priority 99) — not per section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Attach all Growth Page metabox save callbacks to the typed hook for this CPT only.
 *
 * Using save_post_growth_page avoids running save logic on every post type and ensures
 * hooks fire after WordPress core has finished wp_insert_post() / wp_update_post().
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 */
function sln_growth_page_register_save_hooks() {
	$handlers = array(
		'sln_growth_page_save_banner_meta',
		'sln_growth_page_save_growth_metrics_meta',
		'sln_growth_page_save_services_meta',
		'sln_growth_page_save_client_story_meta',
		'sln_growth_page_save_how_work_meta',
		'sln_growth_page_save_growth_services_meta',
		'sln_growth_page_save_case_studies_meta',
		'sln_growth_page_save_why_choose_meta',
		'sln_growth_page_save_price_plan_meta',
		'sln_growth_page_save_testimonials_meta',
		'sln_growth_page_save_cta_banner_meta',
	);

	foreach ( $handlers as $handler ) {
		if ( is_callable( $handler ) ) {
			add_action( 'save_post_' . SLN_GROWTH_PAGE_POST_TYPE, $handler, 10, 2 );
		}
	}

	add_action( 'save_post_' . SLN_GROWTH_PAGE_POST_TYPE, 'sln_growth_page_save_section_order_meta', 99, 2 );
}
add_action( 'init', 'sln_growth_page_register_save_hooks', 20 );

/**
 * Output one shared save nonce immediately after the title (early in POST order).
 *
 * Growth Page forms submit hundreds of fields. Per-metabox nonces at the end of the
 * request are the first keys PHP drops when max_input_vars is exceeded, which makes
 * every metabox save silently skip in sln_growth_page_should_save_meta().
 *
 * @param WP_Post $post Current post.
 */
function sln_growth_page_output_master_save_nonce( $post ) {
	if ( ! $post instanceof WP_Post || SLN_GROWTH_PAGE_POST_TYPE !== $post->post_type ) {
		return;
	}

	wp_nonce_field( 'sln_growth_page_save_meta', 'sln_growth_page_master_nonce', false );
}
add_action( 'edit_form_after_title', 'sln_growth_page_output_master_save_nonce' );

/**
 * Verify the master save nonce or a legacy per-metabox nonce.
 *
 * @param string $nonce_field  Metabox nonce field name.
 * @param string $nonce_action Metabox nonce action.
 * @return bool
 */
function sln_growth_page_is_save_nonce_valid( $nonce_field, $nonce_action ) {
	if ( isset( $_POST['sln_growth_page_master_nonce'] ) ) {
		return (bool) wp_verify_nonce(
			sanitize_text_field( wp_unslash( $_POST['sln_growth_page_master_nonce'] ) ),
			'sln_growth_page_save_meta'
		);
	}

	if ( ! isset( $_POST[ $nonce_field ] ) ) {
		return false;
	}

	return (bool) wp_verify_nonce(
		sanitize_text_field( wp_unslash( $_POST[ $nonce_field ] ) ),
		$nonce_action
	);
}

/**
 * Determine whether a Growth Page metabox save should proceed.
 *
 * @param int    $post_id      Post ID passed to save_post.
 * @param string $nonce_field  POST key for wp_nonce_field().
 * @param string $nonce_action Nonce action string.
 * @return bool
 */
function sln_growth_page_should_save_meta( $post_id, $nonce_field, $nonce_action ) {
	$post_id = absint( $post_id );

	if ( ! $post_id ) {
		return false;
	}

	// Skip Heartbeat/autosave requests — they do not include full metabox POST data.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return false;
	}

	// Typed save_post_growth_page hook already scopes to this CPT; keep as defense in depth.
	if ( SLN_GROWTH_PAGE_POST_TYPE !== get_post_type( $post_id ) ) {
		return false;
	}

	// Do not block when DOING_AJAX is set unless this is an autosave — normal Update uses POST.
	if ( wp_is_post_autosave( $post_id ) ) {
		return false;
	}

	// Revisions store a snapshot — never attach Growth Page meta to revision posts.
	if ( wp_is_post_revision( $post_id ) ) {
		return false;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return false;
	}

	if ( defined( 'SLN_GP_SAVE_DEBUG' ) && SLN_GP_SAVE_DEBUG && function_exists( 'sln_gp_save_debug_should_save_meta' ) ) {
		return sln_gp_save_debug_should_save_meta( $post_id, $nonce_field, $nonce_action );
	}

	return sln_growth_page_is_save_nonce_valid( $nonce_field, $nonce_action );
}

/**
 * Persist repeater rows from POST without wiping data when the key is absent.
 *
 * @param int      $post_id            Post ID.
 * @param string   $meta_key           Meta key to update.
 * @param string   $post_key           $_POST array key for repeater rows.
 * @param callable $sanitize_row       Callback: (array $row): array sanitized row.
 * @param callable $should_persist_row Callback: (array $row): bool keep row in storage.
 */
function sln_growth_page_update_repeater_meta( $post_id, $meta_key, $post_key, $sanitize_row, $should_persist_row ) {
	if ( ! isset( $_POST[ $post_key ] ) || ! is_array( $_POST[ $post_key ] ) ) {
		// Repeater was not posted — leave existing meta untouched.
		return;
	}

	$items = array();

	foreach ( wp_unslash( $_POST[ $post_key ] ) as $raw_row ) {
		if ( ! is_array( $raw_row ) ) {
			continue;
		}

		$sanitized = call_user_func( $sanitize_row, $raw_row );

		if ( ! is_array( $sanitized ) || empty( $sanitized ) ) {
			continue;
		}

		if ( call_user_func( $should_persist_row, $sanitized ) ) {
			$items[] = $sanitized;
		}
	}

	update_post_meta( $post_id, $meta_key, $items );
}

/**
 * Save all section order fields from the sidebar metabox (single source of truth).
 *
 * Individual section metaboxes may still display an order input for convenience, but
 * only this handler writes SLN_GP_SECTION_ORDERS_META to avoid last-writer conflicts.
 *
 * @param int $post_id Post ID.
 */
function sln_growth_page_save_section_orders_from_post( $post_id ) {
	if ( ! sln_growth_page_should_save_meta( $post_id, 'sln_growth_page_section_order_nonce', 'sln_growth_page_save_section_order' ) ) {
		return;
	}

	if ( ! isset( $_POST['sln_gp_section_orders'] ) || ! is_array( $_POST['sln_gp_section_orders'] ) ) {
		return;
	}

	$defaults = sln_get_growth_page_default_section_orders();
	$orders   = array();
	$raw      = wp_unslash( $_POST['sln_gp_section_orders'] );

	foreach ( $defaults as $section_key => $default_order ) {
		$orders[ $section_key ] = isset( $raw[ $section_key ] )
			? max( 1, absint( $raw[ $section_key ] ) )
			: $default_order;
	}

	update_post_meta( $post_id, SLN_GP_SECTION_ORDERS_META, $orders );
}

/**
 * Read stored meta or defaults only when the meta key has never been saved.
 *
 * @param int    $post_id     Post ID.
 * @param string $meta_key    Meta key.
 * @param mixed  $default     Default when meta has never existed.
 * @return mixed
 */
function sln_growth_page_get_meta_or_default( $post_id, $meta_key, $default ) {
	if ( ! metadata_exists( 'post', $post_id, $meta_key ) ) {
		return $default;
	}

	$value = get_post_meta( $post_id, $meta_key, true );

	return $value;
}

/**
 * Normalize section settings array from storage (never revert saved empty arrays to defaults).
 *
 * @param int                  $post_id  Post ID.
 * @param string               $meta_key Section meta key.
 * @param array<string, mixed> $defaults Default field map.
 * @return array<string, mixed>
 */
function sln_growth_page_get_section_settings( $post_id, $meta_key, $defaults ) {
	if ( ! metadata_exists( 'post', $post_id, $meta_key ) ) {
		return $defaults;
	}

	$stored = get_post_meta( $post_id, $meta_key, true );

	if ( ! is_array( $stored ) ) {
		return $defaults;
	}

	return array_intersect_key( wp_parse_args( $stored, $defaults ), $defaults );
}

/**
 * Normalize repeater rows from storage (never revert saved empty lists to defaults).
 *
 * @param int    $post_id       Post ID.
 * @param string $meta_key      Repeater meta key.
 * @param mixed  $default_rows  Default rows when meta has never existed.
 * @return array<int, mixed>
 */
function sln_growth_page_get_repeater_rows( $post_id, $meta_key, $default_rows ) {
	if ( ! metadata_exists( 'post', $post_id, $meta_key ) ) {
		return is_array( $default_rows ) ? $default_rows : array();
	}

	$rows = get_post_meta( $post_id, $meta_key, true );

	return is_array( $rows ) ? $rows : array();
}
