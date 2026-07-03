<?php
/**
 * Theme settings save helpers — attachment sanitization and debug logging.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Normalize a media attachment ID from scalar or media-picker array input.
 *
 * @param mixed $value         Raw POST / option value.
 * @param array $allowed_mimes Optional list of allowed MIME types (empty = any attachment).
 * @return int
 */
function sln_sanitize_media_attachment_id( $value, $allowed_mimes = array() ) {
	if ( is_array( $value ) ) {
		if ( isset( $value['id'] ) ) {
			$value = $value['id'];
		} elseif ( isset( $value[0] ) ) {
			$value = $value[0];
		} else {
			return 0;
		}
	}

	if ( is_object( $value ) && isset( $value->id ) ) {
		$value = $value->id;
	}

	$attachment_id = absint( $value );

	if ( ! $attachment_id ) {
		return 0;
	}

	$post = get_post( $attachment_id );

	if ( ! $post instanceof WP_Post || 'attachment' !== $post->post_type ) {
		return 0;
	}

	if ( ! empty( $allowed_mimes ) ) {
		$mime = get_post_mime_type( $attachment_id );

		if ( ! $mime || ! in_array( $mime, $allowed_mimes, true ) ) {
			return 0;
		}
	}

	return $attachment_id;
}

/**
 * Normalize a legacy uploads basename from POST.
 *
 * @param mixed $value Raw value.
 * @return string
 */
function sln_sanitize_legacy_upload_basename( $value ) {
	if ( is_array( $value ) || is_object( $value ) ) {
		return '';
	}

	return sanitize_file_name( (string) $value );
}

/**
 * Summarize a nested POST branch without logging the full request body.
 *
 * @param mixed $value Value to summarize.
 * @return mixed
 */
function sln_settings_summarize_post_value( $value ) {
	if ( ! is_array( $value ) ) {
		if ( is_string( $value ) && strlen( $value ) > 200 ) {
			return substr( $value, 0, 200 ) . '...';
		}

		return $value;
	}

	$summary = array();

	foreach ( $value as $key => $item ) {
		if ( is_array( $item ) ) {
			$summary[ $key ] = array(
				'_type'  => 'array',
				'_count' => count( $item ),
				'_keys'  => array_slice( array_keys( $item ), 0, 12 ),
			);
			continue;
		}

		$summary[ $key ] = $item;
	}

	return $summary;
}

/**
 * Log theme settings POST data safely before option updates.
 *
 * @param string $option_name Option being updated.
 */
function sln_settings_log_post_debug( $option_name ) {
	if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG || ! defined( 'WP_DEBUG_LOG' ) || ! WP_DEBUG_LOG ) {
		return;
	}

	$payload = array(
		'option'      => $option_name,
		'option_page' => isset( $_POST['option_page'] ) ? sanitize_key( wp_unslash( $_POST['option_page'] ) ) : '',
		'action'      => isset( $_POST['action'] ) ? sanitize_key( wp_unslash( $_POST['action'] ) ) : '',
	);

	if ( isset( $_POST[ $option_name ] ) ) {
		$payload['data'] = sln_settings_summarize_post_value( wp_unslash( $_POST[ $option_name ] ) );
	} else {
		$payload['data'] = '(missing from POST — check max_input_vars)';
	}

	$payload['post_keys'] = array_slice( array_keys( $_POST ), 0, 40 );
	$payload['post_count'] = count( $_POST );

	error_log( '[SLN Settings Save] ' . print_r( $payload, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
}

/**
 * Register debug logging for theme settings options.
 */
function sln_register_settings_save_debug_hooks() {
	$options = array(
		SLN_OUR_SERVICES_OPTION,
		SLN_OUR_PROJECTS_OPTION,
		SLN_CREDIBILITY_OPTION,
	);

	foreach ( $options as $option_name ) {
		add_filter(
			'pre_update_option_' . $option_name,
			static function ( $value ) use ( $option_name ) {
				sln_settings_log_post_debug( $option_name );
				return $value;
			},
			1,
			1
		);
	}
}
add_action( 'admin_init', 'sln_register_settings_save_debug_hooks', 1 );

add_action( 'admin_notices', 'sln_settings_admin_max_input_vars_notice' );

/**
 * Show max_input_vars warning on theme settings screens.
 */
function sln_settings_admin_max_input_vars_notice() {
	if ( ! is_admin() ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	if ( ! $screen || empty( $screen->id ) ) {
		return;
	}

	sln_settings_render_max_input_vars_notice( $screen->id );
}

/**
 * Warn when PHP max_input_vars is too low for large theme settings forms.
 *
 * @param string $hook Current admin page hook / screen id.
 */
function sln_settings_render_max_input_vars_notice( $hook ) {
	$settings_hooks = array(
		'appearance_page_sln-our-services',
		'appearance_page_sln-our-projects',
		'appearance_page_sln-credibility',
	);

	if ( ! in_array( $hook, $settings_hooks, true ) ) {
		return;
	}

	$max_input_vars = (int) ini_get( 'max_input_vars' );

	if ( $max_input_vars >= 3000 ) {
		return;
	}

	printf(
		'<div class="notice notice-warning"><p><strong>%s</strong> %s</p></div>',
		esc_html__( 'Theme settings:', 'smart-leading-net' ),
		esc_html(
			sprintf(
				/* translators: %d: current max_input_vars value */
				__( 'PHP max_input_vars is %d. Large forms may fail to save icons and repeater rows. Increase max_input_vars to at least 3000 in php.ini, then restart the web server.', 'smart-leading-net' ),
				$max_input_vars
			)
		)
	);
}
