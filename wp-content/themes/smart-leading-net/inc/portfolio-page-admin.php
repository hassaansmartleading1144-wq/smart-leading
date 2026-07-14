<?php
/**
 * Portfolio page — admin meta boxes.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Portfolio meta boxes on page edit screens.
 */
function sln_portfolio_register_meta_boxes() {
	$screen = get_current_screen();

	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}

	if ( ! sln_page_admin_should_register_template_boxes( 'sln_portfolio_admin_is_target_page' ) ) {
		return;
	}

	add_meta_box(
		'sln_portfolio_hero',
		__( 'Portfolio Hero', 'smart-leading-net' ),
		'sln_portfolio_render_hero_meta_box',
		'page',
		'normal',
		'high'
	);

	add_meta_box(
		'sln_portfolio_projects',
		__( 'Projects', 'smart-leading-net' ),
		'sln_portfolio_render_projects_meta_box',
		'page',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'sln_portfolio_register_meta_boxes' );

/**
 * Enqueue Portfolio admin assets.
 *
 * @param string $hook Current admin hook.
 */
function sln_portfolio_enqueue_admin_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	$screen = get_current_screen();

	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}

	$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$post    = $post_id ? get_post( $post_id ) : null;

	wp_enqueue_media();
	wp_enqueue_script( 'jquery-ui-sortable' );

	wp_enqueue_style(
		'sln-portfolio-page-admin',
		SLN_THEME_URI . '/assets/css/portfolio-page-admin.css',
		array(),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-portfolio-page-admin',
		SLN_THEME_URI . '/assets/js/portfolio-page-admin.js',
		array( 'jquery', 'jquery-ui-sortable' ),
		SLN_THEME_VERSION,
		true
	);

	wp_localize_script(
		'sln-portfolio-page-admin',
		'slnPortfolioAdmin',
		array(
			'template'        => SLN_PORTFOLIO_TEMPLATE,
			'currentTemplate' => ( $post instanceof WP_Post ) ? get_page_template_slug( $post->ID ) : '',
			'isTargetPage'    => ( $post instanceof WP_Post ) ? sln_portfolio_admin_is_target_page( $post ) : false,
		)
	);
}
add_action( 'admin_enqueue_scripts', 'sln_portfolio_enqueue_admin_assets' );

/**
 * Render Portfolio Hero meta box.
 *
 * @param WP_Post $post Current post.
 */
function sln_portfolio_render_hero_meta_box( $post ) {
	$section = sln_portfolio_admin_get_section( $post->ID );
	?>
	<table class="form-table sln-portfolio-admin__table" role="presentation">
		<tr>
			<th scope="row"><label for="sln-portfolio-small-heading"><?php esc_html_e( 'Small Heading', 'smart-leading-net' ); ?></label></th>
			<td><input id="sln-portfolio-small-heading" type="text" class="large-text" name="sln_portfolio_section[small_heading]" value="<?php echo esc_attr( $section['small_heading'] ); ?>" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="sln-portfolio-main-heading"><?php esc_html_e( 'Main Heading', 'smart-leading-net' ); ?></label></th>
			<td><input id="sln-portfolio-main-heading" type="text" class="large-text" name="sln_portfolio_section[main_heading]" value="<?php echo esc_attr( $section['main_heading'] ); ?>" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="sln-portfolio-description"><?php esc_html_e( 'Description', 'smart-leading-net' ); ?></label></th>
			<td class="sln-portfolio-admin__editor-field">
				<?php
				if ( function_exists( 'sln_growth_page_render_wysiwyg_editor' ) ) {
					sln_growth_page_render_wysiwyg_editor( 'sln-portfolio-description', 'sln_portfolio_section[description]', $section['description'] );
				} else {
					wp_editor( $section['description'], 'sln-portfolio-description', array( 'textarea_name' => 'sln_portfolio_section[description]' ) );
				}
				?>
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Render Projects repeater meta box.
 *
 * @param WP_Post $post Current post.
 */
function sln_portfolio_render_projects_meta_box( $post ) {
	$projects = sln_portfolio_admin_get_projects( $post->ID );
	?>
	<div class="sln-portfolio-admin__repeatable" data-row-selector=".sln-portfolio-admin__project-row" data-name-prefix="sln_portfolio_projects">
		<div class="sln-portfolio-admin__repeatable-list">
			<?php foreach ( $projects as $index => $project ) : ?>
				<div class="sln-portfolio-admin__repeatable-row sln-portfolio-admin__project-row">
					<div class="sln-portfolio-admin__row-head">
						<span class="sln-portfolio-admin__drag-handle dashicons dashicons-menu" aria-hidden="true"></span>
						<strong><?php echo esc_html( $project['title'] ?: __( 'Project', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-portfolio-admin__row-actions">
							<button type="button" class="button sln-portfolio-admin__move-up" aria-label="<?php esc_attr_e( 'Move up', 'smart-leading-net' ); ?>">&#8593;</button>
							<button type="button" class="button sln-portfolio-admin__move-down" aria-label="<?php esc_attr_e( 'Move down', 'smart-leading-net' ); ?>">&#8595;</button>
							<button type="button" class="button-link-delete sln-portfolio-admin__remove-row"><?php esc_html_e( 'Remove Project', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<tr>
							<th scope="row"><?php esc_html_e( 'Image', 'smart-leading-net' ); ?></th>
							<td>
								<?php sln_our_services_render_media_field( 'sln_portfolio_projects[' . $index . '][image_id]', absint( $project['image_id'] ?? 0 ), 'WEBP, PNG, JPG' ); ?>
								<input type="hidden" name="sln_portfolio_projects[<?php echo esc_attr( (string) $index ); ?>][image]" value="<?php echo esc_attr( $project['image'] ?? '' ); ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row"><label><?php esc_html_e( 'Title', 'smart-leading-net' ); ?></label></th>
							<td><input type="text" class="large-text" name="sln_portfolio_projects[<?php echo esc_attr( (string) $index ); ?>][title]" value="<?php echo esc_attr( $project['title'] ?? '' ); ?>" /></td>
						</tr>
						<tr>
							<th scope="row"><label><?php esc_html_e( 'URL', 'smart-leading-net' ); ?></label></th>
							<td><input type="url" class="large-text code" name="sln_portfolio_projects[<?php echo esc_attr( (string) $index ); ?>][url]" value="<?php echo esc_attr( $project['url'] ?? '' ); ?>" placeholder="https://example.com" /></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Open in New Tab', 'smart-leading-net' ); ?></th>
							<td><label><input type="checkbox" name="sln_portfolio_projects[<?php echo esc_attr( (string) $index ); ?>][new_tab]" value="1" <?php checked( ! empty( $project['new_tab'] ) ); ?> /> <?php esc_html_e( 'Open link in new tab', 'smart-leading-net' ); ?></label></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Active', 'smart-leading-net' ); ?></th>
							<td><label><input type="checkbox" name="sln_portfolio_projects[<?php echo esc_attr( (string) $index ); ?>][active]" value="1" <?php checked( ! isset( $project['active'] ) || ! empty( $project['active'] ) ); ?> /> <?php esc_html_e( 'Show on portfolio page', 'smart-leading-net' ); ?></label></td>
						</tr>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<p><button type="button" class="button button-secondary sln-portfolio-admin__add-row"><?php esc_html_e( 'Add Project', 'smart-leading-net' ); ?></button></p>
	</div>
	<?php
}
