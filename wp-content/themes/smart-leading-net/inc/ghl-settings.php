<?php
/**
 * GoHighLevel integration — theme settings.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_GHL_OPTION', 'sln_ghl_settings' );
define( 'SLN_GHL_DEFAULT_LOCATION_ID', 'z7iJxT0penWjQEwjsz92' );

/**
 * Default GHL settings.
 *
 * @return array<string, string>
 */
function sln_ghl_get_default_settings() {
	return array(
		'private_token' => '',
		'location_id'   => SLN_GHL_DEFAULT_LOCATION_ID,
	);
}

/**
 * Get saved GHL settings merged with defaults.
 *
 * @return array<string, string>
 */
function sln_ghl_get_settings() {
	$saved = get_option( SLN_GHL_OPTION, array() );

	if ( ! is_array( $saved ) ) {
		$saved = array();
	}

	return wp_parse_args( $saved, sln_ghl_get_default_settings() );
}

/**
 * Bearer token — theme settings first, wp-config constant fallback.
 *
 * @return string
 */
function sln_ghl_get_private_token() {
	$settings = sln_ghl_get_settings();
	$token    = trim( (string) ( $settings['private_token'] ?? '' ) );

	if ( '' !== $token ) {
		return $token;
	}

	if ( defined( 'SLS_GHL_PRIVATE_TOKEN' ) && '' !== SLS_GHL_PRIVATE_TOKEN ) {
		return (string) SLS_GHL_PRIVATE_TOKEN;
	}

	return '';
}

/**
 * Location ID — theme settings first, wp-config constant fallback.
 *
 * @return string
 */
function sln_ghl_get_location_id() {
	$settings = sln_ghl_get_settings();
	$location = trim( (string) ( $settings['location_id'] ?? '' ) );

	if ( '' !== $location ) {
		return $location;
	}

	if ( defined( 'SLS_GHL_LOCATION_ID' ) && '' !== SLS_GHL_LOCATION_ID ) {
		return (string) SLS_GHL_LOCATION_ID;
	}

	return SLN_GHL_DEFAULT_LOCATION_ID;
}

/**
 * Register GHL settings admin page.
 */
function sln_ghl_admin_menu() {
	add_theme_page(
		__( 'GHL Integration', 'smart-leading-net' ),
		__( 'GHL Integration', 'smart-leading-net' ),
		'manage_options',
		'sln-ghl-settings',
		'sln_render_ghl_settings_page'
	);
}
add_action( 'admin_menu', 'sln_ghl_admin_menu' );

/**
 * Register settings.
 */
function sln_ghl_register_settings() {
	register_setting(
		'sln_ghl_settings_group',
		SLN_GHL_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'sln_ghl_sanitize_settings',
			'default'           => sln_ghl_get_default_settings(),
		)
	);
}
add_action( 'admin_init', 'sln_ghl_register_settings' );

/**
 * Sanitize GHL settings.
 *
 * @param array<string, mixed> $input Raw input.
 * @return array<string, string>
 */
function sln_ghl_sanitize_settings( $input ) {
	$output = sln_ghl_get_default_settings();

	if ( ! is_array( $input ) ) {
		return $output;
	}

	$output['private_token'] = isset( $input['private_token'] ) ? sanitize_text_field( $input['private_token'] ) : '';
	$output['location_id']   = isset( $input['location_id'] ) ? sanitize_text_field( $input['location_id'] ) : SLN_GHL_DEFAULT_LOCATION_ID;

	return $output;
}

/**
 * Render GHL settings page.
 */
function sln_render_ghl_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$settings = sln_ghl_get_settings();
	$log_path = sln_ghl_get_log_file_path();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'GoHighLevel Integration', 'smart-leading-net' ); ?></h1>
		<p><?php esc_html_e( 'Credentials for the Smart Leading lead form and AI chat CRM sync.', 'smart-leading-net' ); ?></p>

		<form method="post" action="options.php">
			<?php settings_fields( 'sln_ghl_settings_group' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="sln-ghl-private-token"><?php esc_html_e( 'Private API Token (Bearer)', 'smart-leading-net' ); ?></label></th>
					<td>
						<input
							id="sln-ghl-private-token"
							type="password"
							class="regular-text"
							name="<?php echo esc_attr( SLN_GHL_OPTION ); ?>[private_token]"
							value="<?php echo esc_attr( $settings['private_token'] ); ?>"
							autocomplete="off"
						/>
						<p class="description"><?php esc_html_e( 'Stored in WordPress options. You can also define SLS_GHL_PRIVATE_TOKEN in wp-config.php as a fallback.', 'smart-leading-net' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln-ghl-location-id"><?php esc_html_e( 'Location ID', 'smart-leading-net' ); ?></label></th>
					<td>
						<input
							id="sln-ghl-location-id"
							type="text"
							class="regular-text"
							name="<?php echo esc_attr( SLN_GHL_OPTION ); ?>[location_id]"
							value="<?php echo esc_attr( $settings['location_id'] ); ?>"
						/>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>

		<hr>
		<h2><?php esc_html_e( 'Debug Log', 'smart-leading-net' ); ?></h2>
		<p>
			<?php
			printf(
				/* translators: %s: log file path */
				esc_html__( 'Lead submission debug output is written to: %s', 'smart-leading-net' ),
				'<code>' . esc_html( $log_path ) . '</code>'
			);
			?>
		</p>
	</div>
	<?php
}
