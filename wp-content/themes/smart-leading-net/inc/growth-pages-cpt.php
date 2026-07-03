<?php
/**
 * Growth Pages — custom post type and permalinks.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_GROWTH_PAGE_POST_TYPE', 'growth_page' );

/**
 * Bump when CPT args change so rewrite rules flush once on next load.
 */
define( 'SLN_GP_CPT_VERSION', '1.3.1' );

/**
 * Register Growth Pages post type.
 *
 * Uses capability_type "post" (not "page") so standard editor/admin roles that can
 * publish posts can publish Growth Pages without needing page-specific caps.
 */
function sln_register_growth_page_post_type() {
	$labels = array(
		'name'               => __( 'Growth Pages', 'smart-leading-net' ),
		'singular_name'      => __( 'Growth Page', 'smart-leading-net' ),
		'menu_name'          => __( 'Growth Pages', 'smart-leading-net' ),
		'name_admin_bar'     => __( 'Growth Page', 'smart-leading-net' ),
		'add_new'            => __( 'Add New', 'smart-leading-net' ),
		'add_new_item'       => __( 'Add New Growth Page', 'smart-leading-net' ),
		'new_item'           => __( 'New Growth Page', 'smart-leading-net' ),
		'edit_item'          => __( 'Edit Growth Page', 'smart-leading-net' ),
		'view_item'          => __( 'View Growth Page', 'smart-leading-net' ),
		'all_items'          => __( 'Growth Pages', 'smart-leading-net' ),
		'search_items'       => __( 'Search Growth Pages', 'smart-leading-net' ),
		'not_found'          => __( 'No growth pages found.', 'smart-leading-net' ),
		'not_found_in_trash' => __( 'No growth pages found in Trash.', 'smart-leading-net' ),
	);

	register_post_type(
		SLN_GROWTH_PAGE_POST_TYPE,
		array(
			'labels'              => $labels,
			'public'              => true,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'menu_icon'           => 'dashicons-chart-area',
			'menu_position'       => 21,
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'hierarchical'        => false,
			// No "editor" — content lives in metabox wp_editor() fields.
			'supports'            => array( 'title', 'thumbnail', 'revisions' ),
			'has_archive'         => false,
			'rewrite'             => array(
				'slug'       => '',
				'with_front' => false,
			),
			'query_var'           => true,
			'show_in_rest'        => false,
			'can_export'          => true,
			'delete_with_user'    => false,
		)
	);
}
add_action( 'init', 'sln_register_growth_page_post_type', 0 );

/**
 * Output Growth Page permalinks without the /growth/ prefix.
 *
 * @param string  $post_link Generated permalink.
 * @param WP_Post $post      Post object.
 * @return string
 */
function sln_growth_page_post_type_link( $post_link, $post ) {
	if ( SLN_GROWTH_PAGE_POST_TYPE !== $post->post_type ) {
		return $post_link;
	}

	if ( 'publish' !== $post->post_status && ! is_admin() ) {
		return $post_link;
	}

	return home_url( user_trailingslashit( $post->post_name ) );
}
add_filter( 'post_type_link', 'sln_growth_page_post_type_link', 10, 2 );

/**
 * Find a published Growth Page by slug.
 *
 * @param string $slug Post slug.
 * @return WP_Post|null
 */
function sln_get_growth_page_by_slug( $slug ) {
	$slug = sanitize_title( $slug );

	if ( '' === $slug ) {
		return null;
	}

	$posts = get_posts(
		array(
			'name'                   => $slug,
			'post_type'              => SLN_GROWTH_PAGE_POST_TYPE,
			'post_status'            => 'publish',
			'posts_per_page'         => 1,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	return ! empty( $posts[0] ) ? $posts[0] : null;
}

/**
 * Resolve root-level URLs to WordPress pages or Growth Pages.
 *
 * WordPress page rules (pagename) must win when a published page exists.
 * Growth Pages use the same root path only when no matching page is found.
 * Do not register a catch-all rewrite at priority "top" — it breaks /about-us/ and /contact-us/.
 *
 * @param array<string, mixed> $query_vars Parsed query vars.
 * @return array<string, mixed>
 */
function sln_growth_page_prefer_page_request( $query_vars ) {
	$slug = '';

	if ( ! empty( $query_vars['pagename'] ) ) {
		$slug = sanitize_title( (string) $query_vars['pagename'] );
	} elseif ( ! empty( $query_vars[ SLN_GROWTH_PAGE_POST_TYPE ] ) ) {
		$slug = sanitize_title( (string) $query_vars[ SLN_GROWTH_PAGE_POST_TYPE ] );
	} elseif ( ! empty( $query_vars['name'] ) && ( empty( $query_vars['post_type'] ) || SLN_GROWTH_PAGE_POST_TYPE === $query_vars['post_type'] ) ) {
		$slug = sanitize_title( (string) $query_vars['name'] );
	}

	if ( '' === $slug ) {
		return $query_vars;
	}

	$page = get_page_by_path( $slug );

	if ( $page instanceof WP_Post && 'publish' === $page->post_status ) {
		unset( $query_vars[ SLN_GROWTH_PAGE_POST_TYPE ], $query_vars['name'], $query_vars['page'] );
		$query_vars['pagename'] = $slug;

		return $query_vars;
	}

	if ( sln_get_growth_page_by_slug( $slug ) instanceof WP_Post ) {
		unset( $query_vars['pagename'], $query_vars['page'], $query_vars[ SLN_GROWTH_PAGE_POST_TYPE ] );
		$query_vars['post_type'] = SLN_GROWTH_PAGE_POST_TYPE;
		$query_vars['name']      = $slug;

		return $query_vars;
	}

	return $query_vars;
}
add_filter( 'request', 'sln_growth_page_prefer_page_request' );

/**
 * Flag rewrite rules for flush after CPT registration changes.
 */
function sln_growth_page_flag_rewrite_flush() {
	update_option( 'sln_growth_page_flush_rewrite', 1 );
}

/**
 * Flush rewrite rules once when CPT version changes (not on every save).
 */
function sln_growth_page_maybe_upgrade_cpt() {
	$stored_version = get_option( 'sln_gp_cpt_version', '' );

	if ( SLN_GP_CPT_VERSION === $stored_version ) {
		return;
	}

	update_option( 'sln_gp_cpt_version', SLN_GP_CPT_VERSION );
	sln_growth_page_flag_rewrite_flush();
}
add_action( 'init', 'sln_growth_page_maybe_upgrade_cpt', 15 );

add_action( 'after_switch_theme', 'sln_growth_page_flag_rewrite_flush' );

/**
 * Flush rewrite rules once when flagged.
 */
function sln_growth_page_maybe_flush_rewrite_rules() {
	if ( ! get_option( 'sln_growth_page_flush_rewrite' ) ) {
		return;
	}

	flush_rewrite_rules( false );
	delete_option( 'sln_growth_page_flush_rewrite' );
}
add_action( 'init', 'sln_growth_page_maybe_flush_rewrite_rules', 99 );

/**
 * Warn admins when PHP max_input_vars is too low for the large Growth Page edit form.
 *
 * Growth Pages submit hundreds of metabox fields; when max_input_vars is exceeded PHP
 * silently drops POST keys (often nonces or meta), which looks like "Update does nothing".
 */
function sln_growth_page_admin_input_vars_notice() {
	if ( ! is_admin() ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	if ( ! $screen || SLN_GROWTH_PAGE_POST_TYPE !== $screen->post_type ) {
		return;
	}

	$max_input_vars = (int) ini_get( 'max_input_vars' );

	if ( $max_input_vars >= 3000 ) {
		return;
	}

	printf(
		'<div class="notice notice-warning"><p><strong>%s</strong> %s</p></div>',
		esc_html__( 'Growth Pages:', 'smart-leading-net' ),
		esc_html(
			sprintf(
				/* translators: %d: current max_input_vars value */
				__( 'PHP max_input_vars is %d. This form may exceed that limit and fail to save all fields. Increase max_input_vars to at least 3000 in php.ini or .user.ini, then restart the web server.', 'smart-leading-net' ),
				$max_input_vars
			)
		)
	);
}
add_action( 'admin_notices', 'sln_growth_page_admin_input_vars_notice' );

/**
 * Add growth-page body class for scoped responsive CSS.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function sln_growth_page_body_class( $classes ) {
	if ( is_singular( SLN_GROWTH_PAGE_POST_TYPE ) ) {
		$classes[] = 'growth-page';
	}

	return $classes;
}
add_filter( 'body_class', 'sln_growth_page_body_class' );
