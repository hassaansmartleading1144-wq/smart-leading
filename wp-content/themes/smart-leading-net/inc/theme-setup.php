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
