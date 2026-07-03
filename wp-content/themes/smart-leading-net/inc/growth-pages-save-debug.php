<?php
/**
 * Temporary Growth Page save diagnostics — remove after confirming fix.
 *
 * Enable in wp-config.php:
 *   define( 'SLN_GP_SAVE_DEBUG', true );
 *   define( 'WP_DEBUG_LOG', true );
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'SLN_GP_SAVE_DEBUG' ) || ! SLN_GP_SAVE_DEBUG ) {
	return;
}

/**
 * @param string $message Log line.
 */
function sln_gp_save_debug_log( $message ) {
	if ( ! function_exists( 'error_log' ) ) {
		return;
	}

	error_log( '[SLN GP Save] ' . $message );
}

/**
 * Log generic save_post for growth_page.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 * @param bool    $update  Whether this is an existing post being updated.
 */
function sln_gp_save_debug_on_save_post( $post_id, $post, $update ) {
	if ( ! $post instanceof WP_Post || SLN_GROWTH_PAGE_POST_TYPE !== $post->post_type ) {
		return;
	}

	sln_gp_save_debug_log(
		sprintf(
			'save_post fired | post_id=%d | update=%s | DOING_AUTOSAVE=%s | DOING_AJAX=%s | post_title_POST=%s',
			$post_id,
			$update ? 'yes' : 'no',
			( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ? 'yes' : 'no',
			( defined( 'DOING_AJAX' ) && DOING_AJAX ) ? 'yes' : 'no',
			isset( $_POST['post_title'] ) ? sanitize_text_field( wp_unslash( $_POST['post_title'] ) ) : '(missing)'
		)
	);
}
add_action( 'save_post', 'sln_gp_save_debug_on_save_post', 1, 3 );

/**
 * Log typed hook.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 */
function sln_gp_save_debug_on_save_post_growth_page( $post_id, $post ) {
	sln_gp_save_debug_log( 'save_post_growth_page fired | post_id=' . absint( $post_id ) );
}
add_action( 'save_post_' . SLN_GROWTH_PAGE_POST_TYPE, 'sln_gp_save_debug_on_save_post_growth_page', 1, 2 );

/**
 * Log POST snapshot once per request before meta handlers run.
 */
function sln_gp_save_debug_dump_post() {
	if ( ! is_admin() || empty( $_POST['post_ID'] ) ) {
		return;
	}

	$post_id = absint( $_POST['post_ID'] );

	if ( ! $post_id || SLN_GROWTH_PAGE_POST_TYPE !== get_post_type( $post_id ) ) {
		return;
	}

	static $logged = false;

	if ( $logged ) {
		return;
	}

	$logged = true;

	$count = 0;
	array_walk_recursive(
		$_POST,
		static function () use ( &$count ) {
			++$count;
		}
	);

	sln_gp_save_debug_log(
		sprintf(
			'$_POST snapshot | keys=%d | nested_count=%d | max_input_vars=%s | post_ID=%s | post_type=%s | _wpnonce=%s',
			count( array_keys( $_POST ) ),
			$count,
			ini_get( 'max_input_vars' ),
			isset( $_POST['post_ID'] ) ? sanitize_text_field( wp_unslash( $_POST['post_ID'] ) ) : '(missing)',
			isset( $_POST['post_type'] ) ? sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) : '(missing)',
			isset( $_POST['_wpnonce'] ) ? 'present' : 'MISSING'
		)
	);

	$nonce_keys = array(
		'sln_growth_page_master_nonce',
		'sln_growth_page_banner_nonce',
		'sln_growth_page_growth_metrics_nonce',
		'sln_growth_page_section_order_nonce',
		'sln_growth_page_services_nonce',
		'sln_growth_page_client_story_nonce',
		'sln_growth_page_how_work_nonce',
		'sln_growth_page_growth_services_nonce',
		'sln_growth_page_case_studies_nonce',
		'sln_growth_page_why_choose_nonce',
		'sln_growth_page_price_plan_nonce',
		'sln_growth_page_testimonials_nonce',
		'sln_growth_page_cta_banner_nonce',
	);

	foreach ( $nonce_keys as $key ) {
		if ( ! isset( $_POST[ $key ] ) ) {
			sln_gp_save_debug_log( "nonce field missing in POST: {$key}" );
		}
	}
}
add_action( 'save_post_' . SLN_GROWTH_PAGE_POST_TYPE, 'sln_gp_save_debug_dump_post', 0, 2 );

/**
 * Wrap should_save_meta with detailed guard logging.
 *
 * @param int    $post_id      Post ID.
 * @param string $nonce_field  Nonce field name.
 * @param string $nonce_action Nonce action.
 * @return bool
 */
function sln_gp_save_debug_should_save_meta( $post_id, $nonce_field, $nonce_action ) {
	$checks = array(
		'post_id'          => (bool) absint( $post_id ),
		'doing_autosave'   => ! ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ),
		'post_type'        => SLN_GROWTH_PAGE_POST_TYPE === get_post_type( $post_id ),
		'not_autosave_rev' => ! wp_is_post_autosave( $post_id ),
		'not_revision'     => ! wp_is_post_revision( $post_id ),
		'capability'       => current_user_can( 'edit_post', $post_id ),
		'nonce_valid'      => sln_growth_page_is_save_nonce_valid( $nonce_field, $nonce_action ),
	);

	$allowed = ! in_array( false, $checks, true );

	sln_gp_save_debug_log(
		sprintf(
			'should_save_meta(%s) => %s | %s',
			$nonce_field,
			$allowed ? 'ALLOW' : 'BLOCK',
			wp_json_encode( $checks )
		)
	);

	return $allowed;
}
