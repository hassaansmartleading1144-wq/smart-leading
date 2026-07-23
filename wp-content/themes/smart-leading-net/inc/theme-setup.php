<?php
/**
 * Theme setup and support.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme features and menus.
 */
function sln_theme_setup() {
	load_theme_textdomain( 'smart-leading-net', SLN_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 100,
			'width'       => 120,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	register_nav_menus(
		array(
			'primary'                  => esc_html__( 'Primary Menu', 'smart-leading-net' ),
			'footer_services_menu'     => esc_html__( 'Footer Services Menu', 'smart-leading-net' ),
			'footer_quick_links_menu'  => esc_html__( 'Footer Quick Links Menu', 'smart-leading-net' ),
		)
	);
}
add_action( 'after_setup_theme', 'sln_theme_setup' );

/**
 * Assign the "Home" page as the static front page when the theme is activated.
 */
function sln_assign_home_front_page() {
	$home_page = get_page_by_path( 'home' );

	if ( ! $home_page ) {
		$home_page = get_page_by_title( 'Home', OBJECT, 'page' );
	}

	if ( ! $home_page instanceof WP_Post ) {
		return;
	}

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $home_page->ID );
}
add_action( 'after_switch_theme', 'sln_assign_home_front_page' );

/**
 * Ensure the Digital Marketing Services page exists with the correct template.
 *
 * @return int Page ID or 0 on failure.
 */
function sln_ensure_digital_marketing_services_page() {
	$slug  = 'digital-marketing-services';
	$title = __( 'Digital Marketing Services', 'smart-leading-net' );
	$page  = get_page_by_path( $slug, OBJECT, 'page' );

	if ( ! $page ) {
		$page = get_page_by_title( $title, OBJECT, 'page' );
	}

	if ( $page instanceof WP_Post ) {
		$page_id = $page->ID;
	} else {
		$page_id = wp_insert_post(
			array(
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => '',
			),
			true
		);

		if ( is_wp_error( $page_id ) || ! $page_id ) {
			return 0;
		}
	}

	update_post_meta( $page_id, '_wp_page_template', 'digital-marketing-page-template.php' );

	return absint( $page_id );
}
add_action( 'after_switch_theme', 'sln_ensure_digital_marketing_services_page' );

/**
 * One-time ensure for the Digital Marketing Services page (no duplicates).
 */
function sln_maybe_ensure_digital_marketing_services_page() {
	if ( get_option( 'sln_dm_page_ensured' ) ) {
		return;
	}

	$page_id = sln_ensure_digital_marketing_services_page();

	if ( $page_id ) {
		update_option( 'sln_dm_page_ensured', (string) $page_id, false );
	}
}
add_action( 'admin_init', 'sln_maybe_ensure_digital_marketing_services_page', 30 );

/**
 * Ensure the PPC & Google Ads Management page exists with the correct template.
 *
 * @return int Page ID or 0 on failure.
 */
function sln_ensure_ppc_google_ads_page() {
	$slug  = 'ppc-google-ads-management';
	$title = __( 'PPC & Google Ads Management', 'smart-leading-net' );
	$page  = get_page_by_path( $slug, OBJECT, 'page' );

	if ( ! $page ) {
		$page = get_page_by_title( $title, OBJECT, 'page' );
	}

	if ( $page instanceof WP_Post ) {
		$page_id = $page->ID;
	} else {
		$page_id = wp_insert_post(
			array(
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => '',
			),
			true
		);

		if ( is_wp_error( $page_id ) || ! $page_id ) {
			return 0;
		}
	}

	$template = defined( 'SLN_PPC_TEMPLATE' ) ? SLN_PPC_TEMPLATE : 'ppc-google-ads-page-template.php';
	update_post_meta( $page_id, '_wp_page_template', $template );

	return absint( $page_id );
}
add_action( 'after_switch_theme', 'sln_ensure_ppc_google_ads_page' );

/**
 * One-time ensure for the PPC & Google Ads Management page (no duplicates).
 * Runs on admin and front-end so FTP deploys still create the page without a theme switch.
 */
function sln_maybe_ensure_ppc_google_ads_page() {
	if ( get_option( 'sln_ppc_page_ensured' ) ) {
		return;
	}

	$page_id = sln_ensure_ppc_google_ads_page();

	if ( $page_id ) {
		update_option( 'sln_ppc_page_ensured', (string) $page_id, false );
	}
}
add_action( 'admin_init', 'sln_maybe_ensure_ppc_google_ads_page', 30 );
add_action( 'init', 'sln_maybe_ensure_ppc_google_ads_page', 30 );

/**
 * Force front-page.php for the site homepage.
 *
 * @param string $template Current template path.
 * @return string
 */
function sln_force_front_page_template( $template ) {
	if ( is_front_page() && ! is_paged() ) {
		$front_page_template = SLN_THEME_DIR . '/front-page.php';

		if ( file_exists( $front_page_template ) ) {
			return $front_page_template;
		}
	}

	return $template;
}
add_filter( 'template_include', 'sln_force_front_page_template', 99 );
