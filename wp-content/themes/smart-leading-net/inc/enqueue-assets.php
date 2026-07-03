<?php
/**
 * Enqueue scripts and styles.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue theme styles and scripts.
 */
function sln_enqueue_assets() {
	wp_enqueue_style(
		'google-font-inter',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'bootstrap',
		'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
		array(),
		'5.3.3'
	);

	wp_enqueue_style(
		'sln-variables',
		SLN_THEME_URI . '/assets/css/variables.css',
		array( 'bootstrap' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_style(
		'sln-typography',
		SLN_THEME_URI . '/assets/css/typography.css',
		array( 'sln-variables', 'google-font-inter' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_style(
		'sln-buttons',
		SLN_THEME_URI . '/assets/css/buttons.css',
		array( 'sln-typography' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_style(
		'sln-global',
		SLN_THEME_URI . '/assets/css/global.css',
		array( 'sln-buttons' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_style(
		'sln-header',
		SLN_THEME_URI . '/assets/css/header.css',
		array( 'sln-global' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_style(
		'sln-footer',
		SLN_THEME_URI . '/assets/css/footer.css',
		array( 'sln-header' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_style(
		'sln-responsive',
		SLN_THEME_URI . '/assets/css/responsive.css',
		array( 'sln-footer' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_style(
		'sln-main',
		SLN_THEME_URI . '/assets/css/main.css',
		array( 'sln-responsive' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_style(
		'sln-style',
		get_stylesheet_uri(),
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'bootstrap',
		'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
		array(),
		'5.3.3',
		true
	);

	wp_enqueue_script(
		'sln-main',
		SLN_THEME_URI . '/assets/js/main.js',
		array( 'bootstrap' ),
		SLN_THEME_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_assets' );

/**
 * Preload below-the-fold background images for inner pages (not homepage LCP).
 */
function sln_preload_critical_backgrounds() {
	if ( is_front_page() ) {
		return;
	}

	$preloads = array();

	if ( is_page_template( array( 'about-us-template.php', 'about-template.php' ) ) ) {
		$workflow_bg = sln_get_workflow_background_url();

		if ( $workflow_bg ) {
			$preloads[] = $workflow_bg;
		}

		$preloads[] = content_url( '/uploads/2026/05/bg-businesses.webp' );
	}

	foreach ( array_unique( $preloads ) as $image_url ) {
		printf(
			'<link rel="preload" as="image" href="%s">' . "\n",
			esc_url( $image_url )
		);
	}
}
add_action( 'wp_head', 'sln_preload_critical_backgrounds', 3 );
