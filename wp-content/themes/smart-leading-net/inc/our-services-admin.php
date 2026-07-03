<?php
/**
 * Our Services section — admin settings page.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register admin menu page.
 */
function sln_our_services_admin_menu() {
	add_theme_page(
		__( 'Our Services', 'smart-leading-net' ),
		__( 'Our Services', 'smart-leading-net' ),
		'manage_options',
		'sln-our-services',
		'sln_render_our_services_settings_page'
	);
}
add_action( 'admin_menu', 'sln_our_services_admin_menu' );

/**
 * Enqueue admin assets.
 *
 * @param string $hook Current admin page hook.
 */
function sln_enqueue_our_services_admin_assets( $hook ) {
	if ( 'appearance_page_sln-our-services' !== $hook ) {
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
		'sln-our-services-admin',
		SLN_THEME_URI . '/assets/js/our-services-admin.js',
		array( 'jquery', 'jquery-ui-sortable' ),
		SLN_THEME_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'sln_enqueue_our_services_admin_assets' );

/**
 * Render media upload field.
 *
 * @param string $name          Input name.
 * @param int    $attachment_id Attachment ID.
 * @param string $mime_hint     Mime hint label.
 */
function sln_our_services_render_media_field( $name, $attachment_id, $mime_hint = 'SVG or WEBP' ) {
	$attachment_id = sln_sanitize_media_attachment_id( $attachment_id );
	$preview       = '';

	if ( $attachment_id ) {
		$mime = get_post_mime_type( $attachment_id );

		if ( $mime && 0 === strpos( $mime, 'image/' ) ) {
			if ( 'image/svg+xml' === $mime ) {
				$preview = sln_get_attachment_inline_svg( $attachment_id );
			} else {
				$preview = wp_get_attachment_image( $attachment_id, 'thumbnail', false, array( 'class' => 'sln-os-admin__media-thumb' ) );
			}
		}
	}
	?>
	<div class="sln-os-admin__media-field" data-mime-hint="<?php echo esc_attr( $mime_hint ); ?>">
		<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $attachment_id ); ?>" class="sln-os-admin__media-id" />
		<div class="sln-os-admin__media-preview"><?php echo $preview; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<p class="sln-os-admin__media-actions">
			<button type="button" class="button sln-os-admin__media-select"><?php esc_html_e( 'Select File', 'smart-leading-net' ); ?></button>
			<button type="button" class="button-link-delete sln-os-admin__media-remove"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
		</p>
	</div>
	<?php
}

/**
 * Render settings page.
 */
function sln_render_our_services_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$settings = sln_get_our_services_settings();
	$section  = $settings['section'];
	$counter  = $settings['counter'];
	$tabs     = $settings['tabs'];
	?>
	<div class="wrap sln-os-admin">
		<h1><?php esc_html_e( 'Our Services Section', 'smart-leading-net' ); ?></h1>
		<p class="description"><?php esc_html_e( 'Manage the homepage Our Services section. Each tab has its own content, services grid, and results grid.', 'smart-leading-net' ); ?></p>

		<?php settings_errors( SLN_OUR_SERVICES_OPTION ); ?>

		<form method="post" action="options.php" class="sln-os-admin__form">
			<?php settings_fields( 'sln_our_services_settings_group' ); ?>

			<div class="sln-os-admin__panel">
				<h2><?php esc_html_e( 'Section Settings', 'smart-leading-net' ); ?></h2>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><label for="sln-os-section-label"><?php esc_html_e( 'Small Heading', 'smart-leading-net' ); ?></label></th>
						<td><input id="sln-os-section-label" type="text" class="regular-text" name="<?php echo esc_attr( SLN_OUR_SERVICES_OPTION ); ?>[section][label]" value="<?php echo esc_attr( $section['label'] ); ?>" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="sln-os-heading-lead"><?php esc_html_e( 'Main Heading (Lead Text)', 'smart-leading-net' ); ?></label></th>
						<td><input id="sln-os-heading-lead" type="text" class="regular-text" name="<?php echo esc_attr( SLN_OUR_SERVICES_OPTION ); ?>[section][heading_lead]" value="<?php echo esc_attr( $section['heading_lead'] ); ?>" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="sln-os-heading-h1"><?php esc_html_e( 'Highlighted Word 1', 'smart-leading-net' ); ?></label></th>
						<td><input id="sln-os-heading-h1" type="text" class="regular-text" name="<?php echo esc_attr( SLN_OUR_SERVICES_OPTION ); ?>[section][heading_highlight_1]" value="<?php echo esc_attr( $section['heading_highlight_1'] ); ?>" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="sln-os-heading-h2"><?php esc_html_e( 'Highlighted Word 2', 'smart-leading-net' ); ?></label></th>
						<td><input id="sln-os-heading-h2" type="text" class="regular-text" name="<?php echo esc_attr( SLN_OUR_SERVICES_OPTION ); ?>[section][heading_highlight_2]" value="<?php echo esc_attr( $section['heading_highlight_2'] ); ?>" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="sln-os-section-description"><?php esc_html_e( 'Description', 'smart-leading-net' ); ?></label></th>
						<td><textarea id="sln-os-section-description" class="large-text" rows="4" name="<?php echo esc_attr( SLN_OUR_SERVICES_OPTION ); ?>[section][description]"><?php echo esc_textarea( $section['description'] ); ?></textarea></td>
					</tr>
				</table>
			</div>

			<div class="sln-os-admin__panel">
				<h2><?php esc_html_e( 'Counter Settings', 'smart-leading-net' ); ?></h2>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><?php esc_html_e( 'Counter Animation', 'smart-leading-net' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="<?php echo esc_attr( SLN_OUR_SERVICES_OPTION ); ?>[counter][enabled]" value="1" <?php checked( ! empty( $counter['enabled'] ) ); ?> />
								<?php esc_html_e( 'Enable counter animation', 'smart-leading-net' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="sln-os-counter-duration"><?php esc_html_e( 'Duration (ms)', 'smart-leading-net' ); ?></label></th>
						<td><input id="sln-os-counter-duration" type="number" min="500" max="10000" step="100" class="small-text" name="<?php echo esc_attr( SLN_OUR_SERVICES_OPTION ); ?>[counter][duration]" value="<?php echo esc_attr( $counter['duration'] ); ?>" /></td>
					</tr>
				</table>
			</div>

			<div class="sln-os-admin__panel">
				<h2><?php esc_html_e( 'Tab Content', 'smart-leading-net' ); ?></h2>
				<p class="description"><?php esc_html_e( 'Each tab stores its own featured content, services grid, and results grid.', 'smart-leading-net' ); ?></p>

				<?php foreach ( $tabs as $index => $tab ) : ?>
					<div class="sln-os-admin__tab-card">
						<h3><?php echo esc_html( sprintf( __( 'Tab %1$d Settings — %2$s', 'smart-leading-net' ), $index + 1, $tab['tab_title'] ) ); ?></h3>
						<input type="hidden" name="<?php echo esc_attr( SLN_OUR_SERVICES_OPTION ); ?>[tabs][<?php echo esc_attr( $index ); ?>][slug]" value="<?php echo esc_attr( $tab['slug'] ); ?>" />
						<table class="form-table" role="presentation">
							<tr>
								<th scope="row"><label><?php esc_html_e( 'Tab Title', 'smart-leading-net' ); ?></label></th>
								<td><input type="text" class="regular-text" name="<?php echo esc_attr( SLN_OUR_SERVICES_OPTION ); ?>[tabs][<?php echo esc_attr( $index ); ?>][tab_title]" value="<?php echo esc_attr( $tab['tab_title'] ); ?>" /></td>
							</tr>
							<tr>
								<th scope="row"><?php esc_html_e( 'Tab SVG Icon', 'smart-leading-net' ); ?></th>
								<td>
									<?php sln_our_services_render_media_field( SLN_OUR_SERVICES_OPTION . '[tabs][' . $index . '][tab_icon_id]', $tab['tab_icon_id'], 'SVG' ); ?>
									<label class="sln-os-admin__inline-check">
										<input type="checkbox" name="<?php echo esc_attr( SLN_OUR_SERVICES_OPTION ); ?>[tabs][<?php echo esc_attr( $index ); ?>][icon_flip]" value="1" <?php checked( ! empty( $tab['icon_flip'] ) ); ?> />
										<?php esc_html_e( 'Flip icon horizontally', 'smart-leading-net' ); ?>
									</label>
								</td>
							</tr>
							<tr>
								<th scope="row"><label><?php esc_html_e( 'Featured Label', 'smart-leading-net' ); ?></label></th>
								<td><input type="text" class="regular-text" name="<?php echo esc_attr( SLN_OUR_SERVICES_OPTION ); ?>[tabs][<?php echo esc_attr( $index ); ?>][featured_label]" value="<?php echo esc_attr( $tab['featured_label'] ); ?>" /></td>
							</tr>
							<tr>
								<th scope="row"><label><?php esc_html_e( 'Main Heading', 'smart-leading-net' ); ?></label></th>
								<td><input type="text" class="large-text" name="<?php echo esc_attr( SLN_OUR_SERVICES_OPTION ); ?>[tabs][<?php echo esc_attr( $index ); ?>][main_heading]" value="<?php echo esc_attr( $tab['main_heading'] ); ?>" /></td>
							</tr>
							<tr>
								<th scope="row"><label><?php esc_html_e( 'Description', 'smart-leading-net' ); ?></label></th>
								<td><textarea class="large-text" rows="4" name="<?php echo esc_attr( SLN_OUR_SERVICES_OPTION ); ?>[tabs][<?php echo esc_attr( $index ); ?>][description]"><?php echo esc_textarea( $tab['description'] ); ?></textarea></td>
							</tr>
							<tr>
								<th scope="row"><label><?php esc_html_e( 'Bullet Point 1', 'smart-leading-net' ); ?></label></th>
								<td><input type="text" class="large-text" name="<?php echo esc_attr( SLN_OUR_SERVICES_OPTION ); ?>[tabs][<?php echo esc_attr( $index ); ?>][bullet_1]" value="<?php echo esc_attr( $tab['bullet_1'] ); ?>" /></td>
							</tr>
							<tr>
								<th scope="row"><label><?php esc_html_e( 'Bullet Point 2', 'smart-leading-net' ); ?></label></th>
								<td><input type="text" class="large-text" name="<?php echo esc_attr( SLN_OUR_SERVICES_OPTION ); ?>[tabs][<?php echo esc_attr( $index ); ?>][bullet_2]" value="<?php echo esc_attr( $tab['bullet_2'] ); ?>" /></td>
							</tr>
							<tr>
								<th scope="row"><label><?php esc_html_e( 'Bullet Point 3', 'smart-leading-net' ); ?></label></th>
								<td><input type="text" class="large-text" name="<?php echo esc_attr( SLN_OUR_SERVICES_OPTION ); ?>[tabs][<?php echo esc_attr( $index ); ?>][bullet_3]" value="<?php echo esc_attr( $tab['bullet_3'] ); ?>" /></td>
							</tr>
						</table>

						<?php
						sln_our_services_render_tab_services( $index, $tab['services'] ?? array() );
						sln_our_services_render_tab_results( $index, $tab['results'] ?? array() );
						?>
					</div>
				<?php endforeach; ?>
			</div>

			<script type="text/template" id="sln-os-service-row-template">
				<div class="sln-os-admin__repeatable-row sln-os-admin__service-row">
					<span class="sln-os-admin__drag-handle dashicons dashicons-menu" aria-hidden="true"></span>
					<div class="sln-os-admin__repeatable-fields">
						<label>
							<span class="sln-os-admin__field-label"><?php esc_html_e( 'Service SVG Icon', 'smart-leading-net' ); ?></span>
							<div class="sln-os-admin__media-field" data-mime-hint="SVG">
								<input type="hidden" name="<?php echo esc_attr( SLN_OUR_SERVICES_OPTION ); ?>[tabs][{{tabIndex}}][services][{{index}}][icon_id]" value="0" class="sln-os-admin__media-id" />
								<div class="sln-os-admin__media-preview"></div>
								<p class="sln-os-admin__media-actions">
									<button type="button" class="button sln-os-admin__media-select"><?php esc_html_e( 'Select File', 'smart-leading-net' ); ?></button>
									<button type="button" class="button-link-delete sln-os-admin__media-remove"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
								</p>
							</div>
						</label>
						<label>
							<span class="sln-os-admin__field-label"><?php esc_html_e( 'Service Title', 'smart-leading-net' ); ?></span>
							<input type="text" class="regular-text" name="<?php echo esc_attr( SLN_OUR_SERVICES_OPTION ); ?>[tabs][{{tabIndex}}][services][{{index}}][title]" value="" />
						</label>
						<label>
							<span class="sln-os-admin__field-label"><?php esc_html_e( 'Service URL', 'smart-leading-net' ); ?></span>
							<input type="url" class="regular-text" name="<?php echo esc_attr( SLN_OUR_SERVICES_OPTION ); ?>[tabs][{{tabIndex}}][services][{{index}}][url]" value="#" placeholder="#" />
						</label>
					</div>
					<button type="button" class="button-link-delete sln-os-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
				</div>
			</script>

			<?php submit_button( __( 'Save Our Services Settings', 'smart-leading-net' ) ); ?>
		</form>
	</div>
	<?php
}
