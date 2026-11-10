<?php
/**
 * SEO sub-pages — admin tool that creates the children of the main SEO page
 * (SEO Audit, Technical SEO, Ecommerce SEO, International SEO, Link Building)
 * from inc/seo-subpages-content.php.
 *
 * Pages → SEO Sub-pages. Existing pages are never overwritten, and menus,
 * options and other pages are not touched.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The main SEO page (uses the SEO Services template, slug seo-services).
 *
 * @return WP_Post|null
 */
function sln_seo_subpages_hub() {
	$hub = get_page_by_path( 'seo-services', OBJECT, 'page' );

	return $hub instanceof WP_Post && SLN_SEO_SVC_TEMPLATE === get_page_template_slug( $hub->ID ) ? $hub : null;
}

/**
 * Existing sub-page for a slug under the main SEO page.
 *
 * @param string $slug Page slug.
 * @return WP_Post|null
 */
function sln_seo_subpages_find( $slug ) {
	$page = get_page_by_path( 'seo-services/' . $slug, OBJECT, 'page' );

	return $page instanceof WP_Post ? $page : null;
}

/**
 * Create every missing sub-page.
 *
 * @param string $status Post status for new pages (draft or publish).
 * @return array{created: array<int, string>, skipped: array<int, string>, error: string}
 */
function sln_seo_subpages_create( $status = 'draft' ) {
	require_once SLN_THEME_DIR . '/inc/seo-subpages-content.php';

	$report = array(
		'created' => array(),
		'skipped' => array(),
		'error'   => '',
	);
	$hub    = sln_seo_subpages_hub();

	if ( ! $hub ) {
		$report['error'] = __( 'The main SEO page (/seo-services/, SEO Services template) was not found.', 'smart-leading-net' );
		return $report;
	}

	$status = in_array( $status, array( 'draft', 'publish' ), true ) ? $status : 'draft';

	foreach ( sln_seo_subpages_definitions() as $order => $definition ) {
		if ( sln_seo_subpages_find( $definition['slug'] ) ) {
			$report['skipped'][] = $definition['title'];
			continue;
		}

		$page_id = wp_insert_post(
			array(
				'post_type'   => 'page',
				'post_title'  => $definition['title'],
				'post_name'   => $definition['slug'],
				'post_parent' => $hub->ID,
				'post_status' => $status,
				'menu_order'  => $order + 1,
			),
			true
		);

		if ( is_wp_error( $page_id ) ) {
			$report['error'] = $page_id->get_error_message();
			break;
		}

		update_post_meta( $page_id, '_wp_page_template', SLN_SEO_SVC_TEMPLATE );

		foreach ( $definition['meta'] as $meta_key => $value ) {
			update_post_meta( $page_id, $meta_key, $value );
		}

		$report['created'][] = $definition['title'];
	}

	return $report;
}

/**
 * Register Pages → SEO Sub-pages.
 */
function sln_seo_subpages_admin_menu() {
	add_pages_page(
		__( 'SEO Sub-pages', 'smart-leading-net' ),
		__( 'SEO Sub-pages', 'smart-leading-net' ),
		'publish_pages',
		'sln-seo-subpages',
		'sln_seo_subpages_render_admin'
	);
}
add_action( 'admin_menu', 'sln_seo_subpages_admin_menu' );

/**
 * Render the admin screen.
 */
function sln_seo_subpages_render_admin() {
	require_once SLN_THEME_DIR . '/inc/seo-subpages-content.php';

	$hub    = sln_seo_subpages_hub();
	$report = get_transient( 'sln_seo_subpages_report_' . get_current_user_id() );

	if ( $report ) {
		delete_transient( 'sln_seo_subpages_report_' . get_current_user_id() );
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'SEO Sub-pages', 'smart-leading-net' ); ?></h1>
		<p><?php esc_html_e( 'Creates the SEO service pages under the main SEO page, using the SEO Services template and starter content. Pages that already exist are left untouched. Menus and other pages are not changed.', 'smart-leading-net' ); ?></p>

		<?php if ( is_array( $report ) ) : ?>
			<div class="notice <?php echo $report['error'] ? 'notice-error' : 'notice-success'; ?>">
				<?php if ( $report['created'] ) : ?>
					<p><?php echo esc_html( sprintf( /* translators: %s: page titles. */ __( 'Created: %s', 'smart-leading-net' ), implode( ', ', $report['created'] ) ) ); ?></p>
				<?php endif; ?>
				<?php if ( $report['skipped'] ) : ?>
					<p><?php echo esc_html( sprintf( /* translators: %s: page titles. */ __( 'Already existed, not changed: %s', 'smart-leading-net' ), implode( ', ', $report['skipped'] ) ) ); ?></p>
				<?php endif; ?>
				<?php if ( $report['error'] ) : ?>
					<p><?php echo esc_html( $report['error'] ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( ! $hub ) : ?>
			<div class="notice notice-error"><p><?php esc_html_e( 'The main SEO page (/seo-services/, SEO Services template) was not found, so sub-pages cannot be created yet.', 'smart-leading-net' ); ?></p></div>
		<?php else : ?>
			<table class="widefat striped" style="max-width:820px">
				<thead><tr><th><?php esc_html_e( 'Page', 'smart-leading-net' ); ?></th><th><?php esc_html_e( 'URL', 'smart-leading-net' ); ?></th><th><?php esc_html_e( 'Status', 'smart-leading-net' ); ?></th></tr></thead>
				<tbody>
				<?php foreach ( sln_seo_subpages_definitions() as $definition ) : ?>
					<?php $existing = sln_seo_subpages_find( $definition['slug'] ); ?>
					<tr>
						<td><?php echo esc_html( $definition['title'] ); ?></td>
						<td><code>/<?php echo esc_html( get_page_uri( $hub ) . '/' . $definition['slug'] ); ?>/</code></td>
						<td>
							<?php if ( $existing ) : ?>
								<a href="<?php echo esc_url( get_edit_post_link( $existing->ID ) ); ?>"><?php echo esc_html( get_post_status_object( $existing->post_status )->label ?? $existing->post_status ); ?></a>
							<?php else : ?>
								<?php esc_html_e( 'Not created', 'smart-leading-net' ); ?>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin-top:20px">
				<?php wp_nonce_field( 'sln_seo_subpages_create' ); ?>
				<input type="hidden" name="action" value="sln_seo_subpages_create" />
				<p>
					<label><input type="radio" name="sln_status" value="draft" checked /> <?php esc_html_e( 'Create as drafts (review, then publish each page)', 'smart-leading-net' ); ?></label><br />
					<label><input type="radio" name="sln_status" value="publish" /> <?php esc_html_e( 'Publish immediately', 'smart-leading-net' ); ?></label>
				</p>
				<?php submit_button( __( 'Create Missing Pages', 'smart-leading-net' ), 'primary', 'submit', false ); ?>
			</form>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Handle the create request.
 */
function sln_seo_subpages_handle_create() {
	if ( ! current_user_can( 'publish_pages' ) ) {
		wp_die( esc_html__( 'You do not have permission to create pages.', 'smart-leading-net' ) );
	}

	check_admin_referer( 'sln_seo_subpages_create' );

	$status = isset( $_POST['sln_status'] ) ? sanitize_key( wp_unslash( $_POST['sln_status'] ) ) : 'draft';

	set_transient( 'sln_seo_subpages_report_' . get_current_user_id(), sln_seo_subpages_create( $status ), MINUTE_IN_SECONDS );

	wp_safe_redirect( admin_url( 'edit.php?post_type=page&page=sln-seo-subpages' ) );
	exit;
}
add_action( 'admin_post_sln_seo_subpages_create', 'sln_seo_subpages_handle_create' );
