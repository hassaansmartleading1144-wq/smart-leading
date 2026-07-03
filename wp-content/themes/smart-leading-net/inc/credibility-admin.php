<?php
/**
 * Credibility section — admin settings page.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register admin menu page.
 */
function sln_credibility_admin_menu() {
	add_theme_page(
		__( 'Credibility', 'smart-leading-net' ),
		__( 'Credibility', 'smart-leading-net' ),
		'manage_options',
		'sln-credibility',
		'sln_render_credibility_settings_page'
	);
}
add_action( 'admin_menu', 'sln_credibility_admin_menu' );

/**
 * Enqueue admin assets.
 *
 * @param string $hook Current admin page hook.
 */
function sln_enqueue_credibility_admin_assets( $hook ) {
	if ( 'appearance_page_sln-credibility' !== $hook ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_script( 'jquery-ui-sortable' );

	wp_enqueue_style(
		'sln-our-services-admin',
		SLN_THEME_URI . '/assets/css/our-services-admin.css',
		array(),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-credibility-admin',
		SLN_THEME_URI . '/assets/js/credibility-admin.js',
		array( 'jquery', 'jquery-ui-sortable' ),
		SLN_THEME_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'sln_enqueue_credibility_admin_assets' );

/**
 * Render client logos repeater rows.
 *
 * @param array $logos Logo rows.
 */
function sln_credibility_render_logos_repeater( $logos ) {
	$option = SLN_CREDIBILITY_OPTION;
	?>
	<div class="sln-os-admin__subsection">
		<h2><?php esc_html_e( 'Client Logos', 'smart-leading-net' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Add, remove, and reorder client logos shown in the Credibility slider. The first 14 logos appear on slide 1; remaining logos appear on slide 2 in this order.', 'smart-leading-net' ); ?></p>

		<div class="sln-os-admin__repeatable sln-cr-admin__logos-list">
			<?php foreach ( $logos as $index => $logo ) : ?>
				<div class="sln-os-admin__repeatable-row sln-cr-admin__logo-row">
					<span class="sln-os-admin__drag-handle dashicons dashicons-menu" aria-hidden="true"></span>
					<div class="sln-os-admin__repeatable-fields">
						<label>
							<span class="sln-os-admin__field-label"><?php esc_html_e( 'Logo Image', 'smart-leading-net' ); ?></span>
							<?php sln_our_services_render_media_field( $option . '[logos][' . $index . '][image_id]', $logo['image_id'], 'PNG, JPG, WEBP, or SVG' ); ?>
						</label>
						<input type="hidden" name="<?php echo esc_attr( $option ); ?>[logos][<?php echo esc_attr( $index ); ?>][image]" value="<?php echo esc_attr( $logo['image'] ?? '' ); ?>" />
						<label>
							<span class="sln-os-admin__field-label"><?php esc_html_e( 'Logo URL', 'smart-leading-net' ); ?></span>
							<input type="url" class="regular-text" name="<?php echo esc_attr( $option ); ?>[logos][<?php echo esc_attr( $index ); ?>][url]" value="<?php echo esc_attr( $logo['url'] ); ?>" placeholder="https://example.com" />
						</label>
					</div>
					<button type="button" class="button-link-delete sln-os-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
				</div>
			<?php endforeach; ?>
		</div>

		<p>
			<button type="button" class="button button-secondary sln-cr-admin__add-logo">
				<?php esc_html_e( 'Add Logo', 'smart-leading-net' ); ?>
			</button>
		</p>
	</div>
	<?php
}

/**
 * Render settings page.
 */
function sln_render_credibility_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$settings = sln_get_credibility_settings();
	$logos    = $settings['logos'];
	?>
	<div class="wrap sln-os-admin sln-cr-admin">
		<h1><?php esc_html_e( 'Credibility', 'smart-leading-net' ); ?></h1>
		<p class="description"><?php esc_html_e( 'Manage homepage client logos shown in the Credibility section slider.', 'smart-leading-net' ); ?></p>

		<?php settings_errors( SLN_CREDIBILITY_OPTION ); ?>

		<form method="post" action="options.php" class="sln-os-admin__form">
			<?php settings_fields( 'sln_credibility_settings_group' ); ?>

			<div class="sln-os-admin__panel">
				<?php sln_credibility_render_logos_repeater( $logos ); ?>
			</div>

			<script type="text/template" id="sln-cr-logo-row-template">
				<div class="sln-os-admin__repeatable-row sln-cr-admin__logo-row">
					<span class="sln-os-admin__drag-handle dashicons dashicons-menu" aria-hidden="true"></span>
					<div class="sln-os-admin__repeatable-fields">
						<label>
							<span class="sln-os-admin__field-label"><?php esc_html_e( 'Logo Image', 'smart-leading-net' ); ?></span>
							<div class="sln-os-admin__media-field" data-mime-hint="PNG, JPG, WEBP, or SVG">
								<input type="hidden" name="<?php echo esc_attr( SLN_CREDIBILITY_OPTION ); ?>[logos][{{index}}][image_id]" value="0" class="sln-os-admin__media-id" />
								<div class="sln-os-admin__media-preview"></div>
								<p class="sln-os-admin__media-actions">
									<button type="button" class="button sln-os-admin__media-select"><?php esc_html_e( 'Select File', 'smart-leading-net' ); ?></button>
									<button type="button" class="button-link-delete sln-os-admin__media-remove"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
								</p>
							</div>
						</label>
						<input type="hidden" name="<?php echo esc_attr( SLN_CREDIBILITY_OPTION ); ?>[logos][{{index}}][image]" value="" />
						<label>
							<span class="sln-os-admin__field-label"><?php esc_html_e( 'Logo URL', 'smart-leading-net' ); ?></span>
							<input type="url" class="regular-text" name="<?php echo esc_attr( SLN_CREDIBILITY_OPTION ); ?>[logos][{{index}}][url]" value="" placeholder="https://example.com" />
						</label>
					</div>
					<button type="button" class="button-link-delete sln-os-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
				</div>
			</script>

			<?php submit_button( __( 'Save Credibility', 'smart-leading-net' ) ); ?>
		</form>
	</div>
	<?php
}
