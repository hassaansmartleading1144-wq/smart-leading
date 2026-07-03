<?php
/**
 * Our Projects section — admin settings page.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register admin menu page.
 */
function sln_our_projects_admin_menu() {
	add_theme_page(
		__( 'Our Projects', 'smart-leading-net' ),
		__( 'Our Projects', 'smart-leading-net' ),
		'manage_options',
		'sln-our-projects',
		'sln_render_our_projects_settings_page'
	);
}
add_action( 'admin_menu', 'sln_our_projects_admin_menu' );

/**
 * Enqueue admin assets.
 *
 * @param string $hook Current admin page hook.
 */
function sln_enqueue_our_projects_admin_assets( $hook ) {
	if ( 'appearance_page_sln-our-projects' !== $hook ) {
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
		'sln-our-projects-admin',
		SLN_THEME_URI . '/assets/js/our-projects-admin.js',
		array( 'jquery', 'jquery-ui-sortable' ),
		SLN_THEME_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'sln_enqueue_our_projects_admin_assets' );

/**
 * Render projects repeater rows.
 *
 * @param array $projects Project rows.
 */
function sln_our_projects_render_projects_repeater( $projects ) {
	$option = SLN_OUR_PROJECTS_OPTION;
	?>
	<div class="sln-os-admin__subsection">
		<h2><?php esc_html_e( 'Projects Repeater', 'smart-leading-net' ); ?></h2>
		<p class="description"><?php esc_html_e( 'Add and reorder homepage project cards. Each item needs an image, title, and URL.', 'smart-leading-net' ); ?></p>

		<div class="sln-os-admin__repeatable sln-op-admin__projects-list">
			<?php foreach ( $projects as $index => $project ) : ?>
				<div class="sln-os-admin__repeatable-row sln-op-admin__project-row">
					<span class="sln-os-admin__drag-handle dashicons dashicons-menu" aria-hidden="true"></span>
					<div class="sln-os-admin__repeatable-fields">
						<label>
							<span class="sln-os-admin__field-label"><?php esc_html_e( 'Project Image', 'smart-leading-net' ); ?></span>
							<?php sln_our_services_render_media_field( $option . '[projects][' . $index . '][image_id]', $project['image_id'], 'WEBP' ); ?>
						</label>
						<input type="hidden" name="<?php echo esc_attr( $option ); ?>[projects][<?php echo esc_attr( $index ); ?>][image]" value="<?php echo esc_attr( $project['image'] ?? '' ); ?>" />
						<label>
							<span class="sln-os-admin__field-label"><?php esc_html_e( 'Project Title', 'smart-leading-net' ); ?></span>
							<input type="text" class="regular-text" name="<?php echo esc_attr( $option ); ?>[projects][<?php echo esc_attr( $index ); ?>][title]" value="<?php echo esc_attr( $project['title'] ); ?>" />
						</label>
						<label>
							<span class="sln-os-admin__field-label"><?php esc_html_e( 'Project URL', 'smart-leading-net' ); ?></span>
							<input type="url" class="regular-text" name="<?php echo esc_attr( $option ); ?>[projects][<?php echo esc_attr( $index ); ?>][url]" value="<?php echo esc_attr( $project['url'] ); ?>" placeholder="https://example.com" />
						</label>
					</div>
					<button type="button" class="button-link-delete sln-os-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
				</div>
			<?php endforeach; ?>
		</div>

		<p>
			<button type="button" class="button button-secondary sln-op-admin__add-project">
				<?php esc_html_e( 'Add Project', 'smart-leading-net' ); ?>
			</button>
		</p>
	</div>
	<?php
}

/**
 * Render settings page.
 */
function sln_render_our_projects_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$settings = sln_get_our_projects_settings();
	$projects = $settings['projects'];
	?>
	<div class="wrap sln-os-admin sln-op-admin">
		<h1><?php esc_html_e( 'Our Projects', 'smart-leading-net' ); ?></h1>
		<p class="description"><?php esc_html_e( 'Manage homepage project cards shown in the Our Projects slider.', 'smart-leading-net' ); ?></p>

		<?php settings_errors( SLN_OUR_PROJECTS_OPTION ); ?>

		<form method="post" action="options.php" class="sln-os-admin__form">
			<?php settings_fields( 'sln_our_projects_settings_group' ); ?>

			<div class="sln-os-admin__panel">
				<?php sln_our_projects_render_projects_repeater( $projects ); ?>
			</div>

			<script type="text/template" id="sln-op-project-row-template">
				<div class="sln-os-admin__repeatable-row sln-op-admin__project-row">
					<span class="sln-os-admin__drag-handle dashicons dashicons-menu" aria-hidden="true"></span>
					<div class="sln-os-admin__repeatable-fields">
						<label>
							<span class="sln-os-admin__field-label"><?php esc_html_e( 'Project Image', 'smart-leading-net' ); ?></span>
							<div class="sln-os-admin__media-field" data-mime-hint="WEBP">
								<input type="hidden" name="<?php echo esc_attr( SLN_OUR_PROJECTS_OPTION ); ?>[projects][{{index}}][image_id]" value="0" class="sln-os-admin__media-id" />
								<div class="sln-os-admin__media-preview"></div>
								<p class="sln-os-admin__media-actions">
									<button type="button" class="button sln-os-admin__media-select"><?php esc_html_e( 'Select File', 'smart-leading-net' ); ?></button>
									<button type="button" class="button-link-delete sln-os-admin__media-remove"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
								</p>
							</div>
						</label>
						<input type="hidden" name="<?php echo esc_attr( SLN_OUR_PROJECTS_OPTION ); ?>[projects][{{index}}][image]" value="" />
						<label>
							<span class="sln-os-admin__field-label"><?php esc_html_e( 'Project Title', 'smart-leading-net' ); ?></span>
							<input type="text" class="regular-text" name="<?php echo esc_attr( SLN_OUR_PROJECTS_OPTION ); ?>[projects][{{index}}][title]" value="" />
						</label>
						<label>
							<span class="sln-os-admin__field-label"><?php esc_html_e( 'Project URL', 'smart-leading-net' ); ?></span>
							<input type="url" class="regular-text" name="<?php echo esc_attr( SLN_OUR_PROJECTS_OPTION ); ?>[projects][{{index}}][url]" value="#" placeholder="https://example.com" />
						</label>
					</div>
					<button type="button" class="button-link-delete sln-os-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
				</div>
			</script>

			<?php submit_button( __( 'Save Our Projects', 'smart-leading-net' ) ); ?>
		</form>
	</div>
	<?php
}
