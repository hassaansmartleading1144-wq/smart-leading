<?php
/**
 * PPC sub-pages — children of the main PPC page (PPC & Google Ads template).
 *
 * - Pages → PPC Sub-pages admin tool creates missing pages from
 *   inc/ppc-subpages-content.php (never overwrites existing pages).
 * - Breadcrumbs for sub-pages.
 * - Document title, meta description and JSON-LD (Service, FAQPage,
 *   BreadcrumbList) for sub-pages, only when no SEO plugin is active.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The main PPC page: the top-level page using the PPC & Google Ads template.
 *
 * Found by template rather than slug, so it works whether the live URL is
 * /ppc-google-ads-management/ or /ppc-management/.
 *
 * @return WP_Post|null
 */
function sln_ppc_subpages_hub() {
	// The redesigned hub: /ppc/ once switched over, or the /ppc-new/ draft
	// while the old live page still holds /ppc/.
	foreach ( array( 'ppc', 'ppc-new' ) as $sln_slug ) {
		$ppc = get_page_by_path( $sln_slug, OBJECT, 'page' );

		if ( $ppc instanceof WP_Post && SLN_PPC_TEMPLATE === get_page_template_slug( $ppc->ID ) && '1' === (string) get_post_meta( $ppc->ID, SLN_PPC_V2_META, true ) ) {
			return $ppc;
		}
	}

	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => array( 'publish', 'draft', 'private' ),
			'post_parent'    => 0,
			'posts_per_page' => 1,
			'orderby'        => 'ID',
			'order'          => 'ASC',
			'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => SLN_PPC_TEMPLATE, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	);

	return $pages ? $pages[0] : null;
}

/**
 * Whether the current (or given) page is a PPC sub-page.
 *
 * @param int|null $post_id Optional post ID.
 * @return bool
 */
function sln_ppc_is_subpage( $post_id = null ) {
	$post_id = sln_ppc_resolve_post_id( $post_id );
	$post    = $post_id ? get_post( $post_id ) : null;

	return $post instanceof WP_Post && $post->post_parent && sln_ppc_uses_template( $post_id );
}

define( 'SLN_PPC_V2_META', '_sln_ppc_v2' );

/**
 * Whether the page uses the redesigned PPC layout: the PPC hub and every
 * PPC sub-page (any page on the PPC & Google Ads template).
 *
 * @param int|null $post_id Optional post ID.
 * @return bool
 */
function sln_ppc_new_design( $post_id = null ) {
	$post_id = sln_ppc_resolve_post_id( $post_id );

	return $post_id && sln_ppc_uses_template( $post_id ) && '1' === (string) get_post_meta( $post_id, SLN_PPC_V2_META, true );
}

/**
 * Existing sub-page for a slug under the main PPC page.
 *
 * @param WP_Post $hub  Main PPC page.
 * @param string  $slug Page slug.
 * @return WP_Post|null
 */
function sln_ppc_subpages_find( $hub, $slug ) {
	$page = get_page_by_path( get_page_uri( $hub ) . '/' . $slug, OBJECT, 'page' );

	return $page instanceof WP_Post ? $page : null;
}

/**
 * Create every missing sub-page.
 *
 * @param string $status Post status for new pages (draft or publish).
 * @return array{created: array<int, string>, skipped: array<int, string>, error: string}
 */
function sln_ppc_subpages_create( $status = 'draft' ) {
	require_once SLN_THEME_DIR . '/inc/ppc-subpages-content.php';

	$report = array(
		'created' => array(),
		'skipped' => array(),
		'error'   => '',
	);
	$hub    = sln_ppc_subpages_hub();

	if ( ! $hub ) {
		$report['error'] = __( 'The main PPC page (PPC & Google Ads template) was not found.', 'smart-leading-net' );
		return $report;
	}

	$status = in_array( $status, array( 'draft', 'publish' ), true ) ? $status : 'draft';

	foreach ( sln_ppc_subpages_definitions() as $order => $definition ) {
		if ( sln_ppc_subpages_find( $hub, $definition['slug'] ) ) {
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

		update_post_meta( $page_id, '_wp_page_template', SLN_PPC_TEMPLATE );
		update_post_meta( $page_id, SLN_PPC_V2_META, '1' );

		foreach ( $definition['meta'] as $meta_key => $value ) {
			update_post_meta( $page_id, $meta_key, wp_slash( $value ) );
		}

		sln_ppc_write_seo_plugin_meta( $page_id, $definition['meta'] );

		$report['created'][] = $definition['title'];
	}

	return $report;
}

/**
 * Breadcrumb trail for PPC sub-pages (Home › PPC page › Current page).
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array{label: string, url: string}>
 */
function sln_ppc_subpage_breadcrumbs( $post_id = null ) {
	if ( ! sln_ppc_new_design( $post_id ) ) {
		return array();
	}

	$post  = get_post( sln_ppc_resolve_post_id( $post_id ) );
	$trail = array(
		array(
			'label' => __( 'Home', 'smart-leading-net' ),
			'url'   => home_url( '/' ),
		),
	);

	foreach ( array_reverse( get_post_ancestors( $post ) ) as $ancestor_id ) {
		$trail[] = array(
			'label' => get_the_title( $ancestor_id ),
			'url'   => get_permalink( $ancestor_id ),
		);
	}

	$trail[] = array(
		'label' => get_the_title( $post ),
		'url'   => '',
	);

	return $trail;
}

/**
 * Whether a dedicated SEO plugin handles titles and meta.
 *
 * @return bool
 */
function sln_ppc_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' );
}

/**
 * Sub-page document title from _sln_seo_title.
 *
 * @param string $title Title.
 * @return string
 */
function sln_ppc_subpage_document_title( $title ) {
	if ( ! is_singular( 'page' ) || sln_ppc_seo_plugin_active() || ! sln_ppc_new_design() ) {
		return $title;
	}

	$custom = (string) get_post_meta( get_queried_object_id(), '_sln_seo_title', true );

	return '' !== $custom ? $custom : $title;
}
add_filter( 'pre_get_document_title', 'sln_ppc_subpage_document_title', 20 );

/**
 * Meta description and JSON-LD for PPC sub-pages.
 */
function sln_ppc_subpage_head() {
	if ( ! is_singular( 'page' ) || ! sln_ppc_new_design() ) {
		return;
	}

	$post_id = get_queried_object_id();

	if ( ! sln_ppc_seo_plugin_active() ) {
		$description = (string) get_post_meta( $post_id, '_sln_seo_description', true );

		if ( '' !== $description ) {
			printf( '<meta name="description" content="%s" />' . "\n", esc_attr( $description ) );
		}
	}

	$url   = get_permalink( $post_id );
	$graph = array();

	$service_type = (string) get_post_meta( $post_id, '_sln_service_type', true );
	$hero         = sln_get_ppc_hero( $post_id );

	$graph[] = array(
		'@type'       => 'Service',
		'@id'         => $url . '#service',
		'name'        => get_the_title( $post_id ),
		'serviceType' => '' !== $service_type ? $service_type : get_the_title( $post_id ),
		'description' => sln_ppc_plain_text( $hero['description'] ?? '' ),
		'url'         => $url,
		'areaServed'  => array( 'United States', 'Australia' ),
		'provider'    => array(
			'@type' => 'Organization',
			'name'  => 'Smart Leading Solutions LLC',
			'url'   => home_url( '/' ),
		),
	);

	$faq_section = sln_get_ppc_faq_section( $post_id );
	$faqs        = sln_get_ppc_faq_items( $post_id );

	if ( sln_ppc_row_is_active( $faq_section ) && $faqs ) {
		$questions = array();

		foreach ( $faqs as $faq ) {
			if ( empty( $faq['question'] ) || empty( $faq['answer'] ) ) {
				continue;
			}

			$questions[] = array(
				'@type'          => 'Question',
				'name'           => sln_ppc_plain_text( $faq['question'] ),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => sln_ppc_plain_text( $faq['answer'] ),
				),
			);
		}

		if ( $questions ) {
			$graph[] = array(
				'@type'      => 'FAQPage',
				'@id'        => $url . '#faq',
				'mainEntity' => $questions,
			);
		}
	}

	$items = array();

	foreach ( sln_ppc_subpage_breadcrumbs( $post_id ) as $index => $crumb ) {
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => $index + 1,
			'name'     => $crumb['label'],
			'item'     => '' !== $crumb['url'] ? $crumb['url'] : $url,
		);
	}

	if ( $items ) {
		$graph[] = array(
			'@type'           => 'BreadcrumbList',
			'@id'             => $url . '#breadcrumb',
			'itemListElement' => $items,
		);
	}

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode(
			array(
				'@context' => 'https://schema.org',
				'@graph'   => $graph,
			),
			JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
		)
	);
}
add_action( 'wp_head', 'sln_ppc_subpage_head', 5 );

/**
 * Register Pages → PPC Sub-pages.
 */
function sln_ppc_subpages_admin_menu() {
	add_pages_page(
		__( 'PPC Sub-pages', 'smart-leading-net' ),
		__( 'PPC Sub-pages', 'smart-leading-net' ),
		'publish_pages',
		'sln-ppc-subpages',
		'sln_ppc_subpages_render_admin'
	);
}
// Admin screen removed (owner request, 18 Nov 2026): pages are created automatically as drafts.
// add_action( 'admin_menu', 'sln_ppc_subpages_admin_menu' );

/**
 * Render the admin screen.
 */
function sln_ppc_subpages_render_admin() {
	require_once SLN_THEME_DIR . '/inc/ppc-subpages-content.php';

	$hub    = sln_ppc_subpages_hub();
	$report = get_transient( 'sln_ppc_subpages_report_' . get_current_user_id() );

	if ( $report ) {
		delete_transient( 'sln_ppc_subpages_report_' . get_current_user_id() );
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'PPC Sub-pages', 'smart-leading-net' ); ?></h1>
		<p><?php esc_html_e( 'Creates the PPC service pages under the main PPC page, using the PPC & Google Ads template and starter content. Pages that already exist are left untouched. Menus and other pages are not changed.', 'smart-leading-net' ); ?></p>

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
			<div class="notice notice-error"><p><?php esc_html_e( 'The main PPC page (PPC & Google Ads template) was not found, so sub-pages cannot be created yet.', 'smart-leading-net' ); ?></p></div>
		<?php else : ?>
			<table class="widefat striped" style="max-width:820px">
				<thead><tr><th><?php esc_html_e( 'Page', 'smart-leading-net' ); ?></th><th><?php esc_html_e( 'URL', 'smart-leading-net' ); ?></th><th><?php esc_html_e( 'Status', 'smart-leading-net' ); ?></th></tr></thead>
				<tbody>
				<?php foreach ( sln_ppc_subpages_definitions() as $definition ) : ?>
					<?php $existing = sln_ppc_subpages_find( $hub, $definition['slug'] ); ?>
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
				<?php wp_nonce_field( 'sln_ppc_subpages_create' ); ?>
				<input type="hidden" name="action" value="sln_ppc_subpages_create" />
				<p>
					<label><input type="radio" name="sln_status" value="draft" checked /> <?php esc_html_e( 'Create as drafts (review, then publish each page)', 'smart-leading-net' ); ?></label><br />
					<label><input type="radio" name="sln_status" value="publish" /> <?php esc_html_e( 'Publish immediately', 'smart-leading-net' ); ?></label>
				</p>
				<?php submit_button( __( 'Create Missing Pages', 'smart-leading-net' ), 'primary', 'submit', false ); ?>
			</form>

			<h2 style="margin-top:32px"><?php esc_html_e( 'Main PPC page content', 'smart-leading-net' ); ?></h2>
			<p><?php esc_html_e( 'Adds the redesigned hub content to the main PPC page. Fields the page already has are kept; only missing fields are added.', 'smart-leading-net' ); ?></p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'sln_ppc_hub_apply' ); ?>
				<input type="hidden" name="action" value="sln_ppc_hub_apply" />
				<?php submit_button( __( 'Add Hub Content', 'smart-leading-net' ), 'secondary', 'submit', false ); ?>
			</form>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Handle the create request.
 */
function sln_ppc_subpages_handle_create() {
	if ( ! current_user_can( 'publish_pages' ) ) {
		wp_die( esc_html__( 'You do not have permission to create pages.', 'smart-leading-net' ) );
	}

	check_admin_referer( 'sln_ppc_subpages_create' );

	$status = isset( $_POST['sln_status'] ) ? sanitize_key( wp_unslash( $_POST['sln_status'] ) ) : 'draft';

	set_transient( 'sln_ppc_subpages_report_' . get_current_user_id(), sln_ppc_subpages_create( $status ), MINUTE_IN_SECONDS );

	wp_safe_redirect( admin_url( 'edit.php?post_type=page&page=sln-ppc-subpages' ) );
	exit;
}
// Admin screen removed (owner request, 18 Nov 2026): pages are created automatically as drafts.
// add_action( 'admin_post_sln_ppc_subpages_create', 'sln_ppc_subpages_handle_create' );

/**
 * 301 from the hub's old URL (/ppc-google-ads-management/…) to the new one.
 *
 * The hub moved to /ppc/ on 17 Nov 2026 (owner-approved silo structure).
 * WordPress does not keep old slugs for pages, so this keeps old links and
 * rankings working, including sub-page paths under the old URL.
 */
function sln_ppc_old_hub_redirect() {
	if ( ! is_404() ) {
		return;
	}

	$path = wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

	if ( ! is_string( $path ) || ! preg_match( '#^/ppc-google-ads-management(/.*)?$#', $path, $match ) ) {
		return;
	}

	$hub = sln_ppc_subpages_hub();

	if ( ! $hub || 'ppc-google-ads-management' === $hub->post_name ) {
		return;
	}

	$rest = isset( $match[1] ) ? ltrim( $match[1], '/' ) : '';

	wp_safe_redirect( trailingslashit( get_permalink( $hub ) ) . $rest, 301 );
	exit;
}
add_action( 'template_redirect', 'sln_ppc_old_hub_redirect', 1 );

/**
 * Handle "Add Hub Content".
 */
function sln_ppc_hub_handle_apply() {
	if ( ! current_user_can( 'publish_pages' ) ) {
		wp_die( esc_html__( 'You do not have permission to edit pages.', 'smart-leading-net' ) );
	}

	check_admin_referer( 'sln_ppc_hub_apply' );
	require_once SLN_THEME_DIR . '/inc/ppc-subpages-content.php';

	$written = sln_ppc_hub_apply( false );

	set_transient(
		'sln_ppc_subpages_report_' . get_current_user_id(),
		array(
			'created' => false === $written ? array() : array( sprintf( /* translators: %d: number of fields. */ __( 'Main PPC page: %d fields added', 'smart-leading-net' ), (int) $written ) ),
			'skipped' => array(),
			'error'   => false === $written ? __( 'The main PPC page was not found.', 'smart-leading-net' ) : '',
		),
		MINUTE_IN_SECONDS
	);

	wp_safe_redirect( admin_url( 'edit.php?post_type=page&page=sln-ppc-subpages' ) );
	exit;
}
// Admin screen removed (owner request, 18 Nov 2026): pages are created automatically as drafts.
// add_action( 'admin_post_sln_ppc_hub_apply', 'sln_ppc_hub_handle_apply' );

/**
 * Free PPC Audit form → GoHighLevel contact (reuses sln_ghl_upsert_contact()).
 */
define( 'SLN_PPC_AUDIT_NONCE_ACTION', 'sln_ppc_audit_form' );
define( 'SLN_PPC_AUDIT_AJAX_ACTION', 'sln_ppc_audit_submit_lead' );

/**
 * Handle the audit request.
 */
function sln_ppc_audit_form_handler() {
	if ( ! check_ajax_referer( SLN_PPC_AUDIT_NONCE_ACTION, 'nonce', false ) ) {
		wp_send_json( array( 'success' => false, 'message' => __( 'Security check failed. Please refresh the page and try again.', 'smart-leading-net' ) ), 403 );
	}

	// Honeypot.
	if ( ! empty( $_POST['company_fax'] ) ) {
		wp_send_json( array( 'success' => true, 'title' => __( 'Thank You!', 'smart-leading-net' ), 'message' => '' ) );
	}

	$name      = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email     = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone     = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$website   = isset( $_POST['website'] ) ? sanitize_text_field( wp_unslash( $_POST['website'] ) ) : '';
	$spend     = isset( $_POST['spend'] ) ? sanitize_text_field( wp_unslash( $_POST['spend'] ) ) : '';
	$message   = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
	$allowed   = array( 'Google Ads', 'Local Service Ads', 'Meta Ads' );
	$platforms = isset( $_POST['platforms'] ) ? array_values( array_intersect( $allowed, array_map( 'sanitize_text_field', (array) wp_unslash( $_POST['platforms'] ) ) ) ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

	if ( '' === trim( $name ) ) {
		wp_send_json( array( 'success' => false, 'message' => __( 'Please enter your name.', 'smart-leading-net' ) ), 400 );
	}

	if ( ! is_email( $email ) ) {
		wp_send_json( array( 'success' => false, 'message' => __( 'Please enter a valid email address.', 'smart-leading-net' ) ), 400 );
	}

	if ( ! $platforms ) {
		wp_send_json( array( 'success' => false, 'message' => __( 'Please choose at least one platform to audit.', 'smart-leading-net' ) ), 400 );
	}

	if ( ! function_exists( 'sln_ghl_upsert_contact' ) ) {
		wp_send_json( array( 'success' => false, 'message' => __( 'Something went wrong. Please try again or contact us directly.', 'smart-leading-net' ) ), 500 );
	}

	$note = sprintf(
		"Free PPC Audit request.\nPlatforms: %s\nMonthly ad spend: %s\nPhone: %s\nMessage: %s",
		implode( ', ', $platforms ),
		'' !== $spend ? $spend : 'not given',
		'' !== $phone ? $phone : 'not given',
		'' !== $message ? $message : '-'
	);

	$tags = array( 'Website Lead', 'PPC Audit', 'Free PPC Audit' );
	foreach ( $platforms as $platform ) {
		$tags[] = 'Audit: ' . $platform;
	}

	$result = sln_ghl_upsert_contact(
		array(
			'name'    => $name,
			'email'   => $email,
			'phone'   => $phone,
			'website' => function_exists( 'sln_ghl_normalize_website' ) ? sln_ghl_normalize_website( $website ) : $website,
			'source'  => 'Smart Leading Free PPC Audit Page',
			'tags'    => $tags,
			'note'    => $note,
		)
	);

	if ( is_wp_error( $result ) ) {
		if ( function_exists( 'sln_ghl_log' ) ) {
			sln_ghl_log( 'PPC audit submission failed', array( 'event' => 'ppc_audit_failure', 'email' => $email, 'error_code' => $result->get_error_code() ) );
		}
		wp_send_json( array( 'success' => false, 'message' => __( 'Something went wrong. Please try again or contact us directly.', 'smart-leading-net' ) ), 500 );
	}

	wp_send_json(
		array(
			'success'   => true,
			'title'     => __( 'Audit Requested!', 'smart-leading-net' ),
			'message'   => __( 'Thanks. We\'ll email you simple steps to give us read-only access.', 'smart-leading-net' ),
			'message_2' => __( 'Your PDF report, video walkthrough and call follow within 2–3 business days of getting access.', 'smart-leading-net' ),
		)
	);
}
add_action( 'wp_ajax_' . SLN_PPC_AUDIT_AJAX_ACTION, 'sln_ppc_audit_form_handler' );
add_action( 'wp_ajax_nopriv_' . SLN_PPC_AUDIT_AJAX_ACTION, 'sln_ppc_audit_form_handler' );

/**
 * Copy our meta title/description into Rank Math (and Yoast) fields, so the
 * SEO plugin on the live site shows them.
 *
 * @param int                  $page_id Page ID.
 * @param array<string, mixed> $meta    Page meta definition.
 */
function sln_ppc_write_seo_plugin_meta( $page_id, $meta ) {
	$title = (string) ( $meta['_sln_seo_title'] ?? '' );
	$desc  = (string) ( $meta['_sln_seo_description'] ?? '' );

	if ( '' !== $title ) {
		update_post_meta( $page_id, 'rank_math_title', $title );
		update_post_meta( $page_id, '_yoast_wpseo_title', $title );
	}

	if ( '' !== $desc ) {
		update_post_meta( $page_id, 'rank_math_description', $desc );
		update_post_meta( $page_id, '_yoast_wpseo_metadesc', $desc );
	}

	if ( ! empty( $meta['_sln_focus_keyword'] ) ) {
		update_post_meta( $page_id, 'rank_math_focus_keyword', (string) $meta['_sln_focus_keyword'] );
		update_post_meta( $page_id, '_yoast_wpseo_focuskw', (string) $meta['_sln_focus_keyword'] );
	}
}

/**
 * One-time setup, no admin menu: the first time an administrator opens
 * WP-admin after this deploy, create the PPC hub (/ppc/) and every PPC
 * sub-page as DRAFTS. Existing pages are never changed; the current live PPC
 * page keeps its URL, content and old design until the owner switches.
 */
function sln_ppc_seed_draft_pages() {
	if ( wp_doing_ajax() || ! current_user_can( 'publish_pages' ) || get_option( 'sln_ppc_v2_seeded_2' ) ) {
		return;
	}

	require_once SLN_THEME_DIR . '/inc/ppc-subpages-content.php';

	$hub = sln_ppc_subpages_hub();

	if ( ! $hub instanceof WP_Post || '1' !== (string) get_post_meta( $hub->ID, SLN_PPC_V2_META, true ) ) {
		// /ppc/ is taken by the current live page, so the redesign starts as a
		// draft at /ppc-new/. The owner switches it to /ppc/ when ready.
		$taken  = get_page_by_path( 'ppc', OBJECT, 'page' ) instanceof WP_Post;
		$hub_id = wp_insert_post(
			array(
				'post_type'   => 'page',
				'post_title'  => 'Performance Marketing (PPC)',
				'post_name'   => $taken ? 'ppc-new' : 'ppc',
				'post_status' => 'draft',
			),
			true
		);

		if ( is_wp_error( $hub_id ) ) {
			return;
		}

		update_post_meta( $hub_id, '_wp_page_template', SLN_PPC_TEMPLATE );
		update_post_meta( $hub_id, SLN_PPC_V2_META, '1' );

		$hub_meta = sln_ppc_hub_definition();
		foreach ( $hub_meta as $meta_key => $value ) {
			update_post_meta( $hub_id, $meta_key, wp_slash( $value ) );
		}
		sln_ppc_write_seo_plugin_meta( $hub_id, $hub_meta );

		$hub = get_post( $hub_id );
	}

	// If an earlier version created the service drafts under the old live
	// page, move them under the redesigned hub (drafts only).
	$old = get_page_by_path( 'ppc', OBJECT, 'page' );
	if ( $old instanceof WP_Post && (int) $old->ID !== (int) $hub->ID ) {
		foreach ( get_pages( array( 'parent' => $old->ID, 'post_status' => 'draft' ) ) as $child ) {
			if ( '1' === (string) get_post_meta( $child->ID, SLN_PPC_V2_META, true ) ) {
				wp_update_post( array( 'ID' => $child->ID, 'post_parent' => $hub->ID ) );
			}
		}
	}

	$report = sln_ppc_subpages_create( 'draft' );

	if ( '' === $report['error'] ) {
		update_option( 'sln_ppc_v2_seeded_2', gmdate( 'c' ), false );
	}
}
add_action( 'admin_init', 'sln_ppc_seed_draft_pages' );
