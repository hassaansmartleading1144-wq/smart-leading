<?php
/**
 * SEO Services template — per-page section visibility, breadcrumbs and
 * related SEO service links. Used by the main SEO page and its sub-pages.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_SEO_SVC_HIDDEN_SECTIONS_META', '_sln_seo_svc_hidden_sections' );

/**
 * Sections an editor can switch off per page.
 *
 * @return array<string, string> Section key => label.
 */
function sln_seo_services_optional_sections() {
	return array(
		'logos'        => __( 'Client logos strip', 'smart-leading-net' ),
		'case_studies' => __( 'Case Studies', 'smart-leading-net' ),
		'tracking'     => __( 'SEO Tracking (Lead Analytics)', 'smart-leading-net' ),
		'pricing'      => __( 'Pricing', 'smart-leading-net' ),
		'testimonials' => __( 'Testimonials', 'smart-leading-net' ),
		'related'      => __( 'Related SEO Services', 'smart-leading-net' ),
	);
}

/**
 * Hidden section keys for a page.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, string>
 */
function sln_seo_services_hidden_sections( $post_id = null ) {
	$post_id = sln_seo_services_resolve_post_id( $post_id );
	$hidden  = $post_id ? get_post_meta( $post_id, SLN_SEO_SVC_HIDDEN_SECTIONS_META, true ) : array();

	return is_array( $hidden ) ? array_values( array_intersect( $hidden, array_keys( sln_seo_services_optional_sections() ) ) ) : array();
}

/**
 * Whether a section should render on the current SEO page.
 *
 * @param string   $key     Section key.
 * @param int|null $post_id Optional post ID.
 * @return bool
 */
function sln_seo_services_section_visible( $key, $post_id = null ) {
	return ! in_array( $key, sln_seo_services_hidden_sections( $post_id ), true );
}

/**
 * Register the "Section Visibility" sidebar box.
 */
function sln_seo_services_register_visibility_box() {
	$screen = get_current_screen();

	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}

	if ( ! sln_page_admin_should_register_template_boxes( 'sln_seo_services_admin_is_target_page' ) ) {
		return;
	}

	add_meta_box(
		'sln_seo_svc_visibility',
		__( 'SEO Page — Section Visibility', 'smart-leading-net' ),
		'sln_seo_services_render_visibility_box',
		'page',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'sln_seo_services_register_visibility_box' );

/**
 * Render the visibility checkboxes.
 *
 * @param WP_Post $post Current post.
 */
function sln_seo_services_render_visibility_box( $post ) {
	$hidden = sln_seo_services_hidden_sections( $post->ID );
	?>
	<p><?php esc_html_e( 'Tick a section to hide it on this page.', 'smart-leading-net' ); ?></p>
	<input type="hidden" name="sln_seo_svc_hidden_sections_present" value="1" />
	<?php foreach ( sln_seo_services_optional_sections() as $key => $label ) : ?>
		<p>
			<label>
				<input type="checkbox" name="sln_seo_svc_hidden_sections[]" value="<?php echo esc_attr( $key ); ?>" <?php checked( in_array( $key, $hidden, true ) ); ?> />
				<?php
				/* translators: %s: section name. */
				echo esc_html( sprintf( __( 'Hide %s', 'smart-leading-net' ), $label ) );
				?>
			</label>
		</p>
	<?php endforeach; ?>
	<?php
}

/**
 * Save hidden sections.
 *
 * @param int $post_id Post ID.
 */
function sln_seo_services_save_visibility( $post_id ) {
	if ( ! sln_seo_services_should_save_meta( $post_id ) || empty( $_POST['sln_seo_svc_hidden_sections_present'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified in should_save_meta.
		return;
	}

	$raw    = isset( $_POST['sln_seo_svc_hidden_sections'] ) ? (array) wp_unslash( $_POST['sln_seo_svc_hidden_sections'] ) : array(); // phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$hidden = array_values( array_intersect( array_map( 'sanitize_key', $raw ), array_keys( sln_seo_services_optional_sections() ) ) );

	update_post_meta( $post_id, SLN_SEO_SVC_HIDDEN_SECTIONS_META, $hidden );
}
add_action( 'save_post_page', 'sln_seo_services_save_visibility', 11 );

/**
 * Parent SEO page for the current page, or the page itself when it is the main SEO page.
 *
 * @param int|null $post_id Optional post ID.
 * @return WP_Post|null
 */
function sln_seo_services_hub_page( $post_id = null ) {
	$post = get_post( sln_seo_services_resolve_post_id( $post_id ) );

	if ( ! $post instanceof WP_Post ) {
		return null;
	}

	if ( $post->post_parent ) {
		$parent = get_post( $post->post_parent );
		return $parent instanceof WP_Post ? $parent : null;
	}

	return $post;
}

/**
 * Breadcrumb trail for SEO sub-pages (Home › SEO › Current page).
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, array{label: string, url: string}>
 */
function sln_seo_services_breadcrumbs( $post_id = null ) {
	$post = get_post( sln_seo_services_resolve_post_id( $post_id ) );

	if ( ! $post instanceof WP_Post || ! $post->post_parent ) {
		return array();
	}

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
 * Related SEO service pages: other published children of the main SEO page,
 * plus the Local SEO and AI SEO pages, excluding the current page.
 *
 * @param int|null $post_id Optional post ID.
 * @return array<int, WP_Post>
 */
function sln_seo_services_related_pages( $post_id = null ) {
	$current_id = sln_seo_services_resolve_post_id( $post_id );
	$hub        = sln_seo_services_hub_page( $current_id );

	if ( ! $hub instanceof WP_Post ) {
		return array();
	}

	$children = get_pages(
		array(
			'parent'      => $hub->ID,
			'post_status' => 'publish',
			'sort_column' => 'menu_order,post_title',
		)
	);

	$siblings = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 4,
			'orderby'        => 'menu_order title',
			'order'          => 'ASC',
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => '_wp_page_template',
					'value'   => array( 'locel-seo.php', 'ai-seo.php' ),
					'compare' => 'IN',
				),
			),
		)
	);

	$pages = array();

	foreach ( array_merge( $children, $siblings ) as $page ) {
		if ( $page instanceof WP_Post && (int) $page->ID !== (int) $current_id && (int) $page->ID !== (int) $hub->ID ) {
			$pages[ $page->ID ] = $page;
		}
	}

	return array_values( $pages );
}

/**
 * Short summary for a related page card.
 *
 * @param WP_Post $page Page.
 * @return string
 */
function sln_seo_services_related_summary( $page ) {
	if ( SLN_SEO_SVC_TEMPLATE === get_page_template_slug( $page->ID ) ) {
		$hero = sln_get_seo_services_hero( $page->ID );
		$text = sln_seo_services_plain_text( $hero['description'] ?? '' );
	} else {
		$text = has_excerpt( $page ) ? get_the_excerpt( $page ) : trim( wp_strip_all_tags( strip_shortcodes( excerpt_remove_blocks( $page->post_content ) ) ) );
	}

	if ( '' === $text ) {
		$fallbacks = array(
			'locel-seo.php' => __( 'Show up in Google Maps and "near me" searches in the areas you serve, and turn local searches into calls and enquiries.', 'smart-leading-net' ),
			'ai-seo.php'    => __( 'Help AI Overviews, ChatGPT and other answer engines understand, trust and mention your business.', 'smart-leading-net' ),
		);
		$text      = $fallbacks[ get_page_template_slug( $page->ID ) ] ?? '';
	}

	return wp_trim_words( $text, 20, '…' );
}
