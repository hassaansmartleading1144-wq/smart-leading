<?php
/**
 * Theme performance — resource hints, deferrals, LCP, lazy chat loader.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Resource hints for fonts and CDN assets.
 */
function sln_performance_resource_hints() {
	echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
	echo '<link rel="dns-prefetch" href="https://cdn.jsdelivr.net">' . "\n";
}
add_action( 'wp_head', 'sln_performance_resource_hints', 0 );

/**
 * Preload the homepage LCP hero image only.
 */
function sln_preload_lcp_image() {
	if ( ! is_front_page() ) {
		return;
	}

	$hero_url  = sln_get_hero_lcp_image_url();
	$hero_set  = sln_get_hero_lcp_image_srcset();
	$hero_size = sln_get_hero_lcp_image_sizes();

	if ( ! $hero_url ) {
		return;
	}

	if ( $hero_set ) {
		printf(
			'<link rel="preload" as="image" href="%1$s" imagesrcset="%2$s" imagesizes="%3$s" fetchpriority="high" type="image/webp">' . "\n",
			esc_url( $hero_url ),
			esc_attr( $hero_set ),
			esc_attr( $hero_size )
		);
		return;
	}

	printf(
		'<link rel="preload" as="image" href="%s" fetchpriority="high" type="image/webp">' . "\n",
		esc_url( $hero_url )
	);
}
add_action( 'wp_head', 'sln_preload_lcp_image', 1 );

/**
 * Inline critical above-the-fold CSS on the homepage.
 */
function sln_inline_critical_css() {
	if ( ! is_front_page() ) {
		return;
	}

	$critical_path = SLN_THEME_DIR . '/assets/css/critical-front.css';

	if ( ! is_readable( $critical_path ) ) {
		return;
	}

	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	$css = file_get_contents( $critical_path );

	if ( false === $css || '' === trim( $css ) ) {
		return;
	}

	printf(
		'<style id="sln-critical-css">%s</style>' . "\n",
		wp_strip_all_tags( $css )
	);
}
add_action( 'wp_head', 'sln_inline_critical_css', 2 );

/**
 * Style handles loaded with non-blocking media swap.
 *
 * @return array<int, string>
 */
function sln_get_deferred_style_handles() {
	$handles = array(
		'sln-footer',
		'sln-responsive',
		'sln-ai-chat',
		'swiper',
		'sln-swiper-overrides',
	);

	if ( is_front_page() ) {
		$handles = array_merge(
			$handles,
			array(
				'sln-accomplishments',
				'sln-businesses-choose',
				'sln-our-services',
				'sln-case-studies',
				'sln-expertise',
				'sln-growing',
				'sln-our-project',
				'sln-testimonials',
				'sln-credibility',
				'sln-starts-cta',
				'sln-workflow',
			)
		);
	}

	if ( sln_is_about_page_template() ) {
		$handles[] = 'sln-about-page';
		$handles[] = 'sln-workflow';
		$handles[] = 'sln-page-banner';
	}

	if ( is_page_template( 'contact-template.php' ) ) {
		$handles[] = 'sln-contact-page';
		$handles[] = 'sln-page-banner';
	}

	if ( is_page_template( 'thank-you-template.php' ) ) {
		$handles[] = 'sln-contact-page';
		$handles[] = 'sln-page-banner';
	}

	if ( is_page_template( 'seo-page-template.php' ) ) {
		$handles[] = 'sln-seo-page';
		$handles[] = 'sln-case-studies';
		$handles[] = 'sln-price-plan';
		$handles[] = 'sln-testimonials';
	}

	if ( is_singular( defined( 'SLN_GROWTH_PAGE_POST_TYPE' ) ? SLN_GROWTH_PAGE_POST_TYPE : 'growth_page' ) ) {
		$handles = array_merge(
			$handles,
			array(
				'sln-growth-page-hero',
				'sln-convert-scale',
				'sln-client-story',
				'sln-our-services',
				'sln-how-work',
				'sln-growth-services',
				'sln-case-studies',
				'sln-why-choose',
				'sln-price-plan',
				'sln-testimonials',
				'sln-starts-cta',
			)
		);
	}

	return array_unique( $handles );
}

/**
 * Script handles that should use defer.
 *
 * @return array<int, string>
 */
function sln_get_deferred_script_handles() {
	$handles = array(
		'bootstrap',
		'sln-main',
		'sln-hero-banner',
		'sln-accomplishments',
		'sln-businesses-choose',
		'sln-our-services',
		'sln-case-studies',
		'sln-starts-cta',
		'sln-expertise',
		'sln-growing',
		'sln-our-project',
		'sln-testimonials',
		'sln-workflow',
		'swiper',
		'sln-how-work',
		'sln-growth-services',
		'sln-price-plan',
		'sln-contact-form',
		'sln-seo-page',
		'sln-ai-chat-loader',
	);

	return $handles;
}

/**
 * Defer non-critical stylesheets.
 *
 * @param string $html   Link tag HTML.
 * @param string $handle Style handle.
 * @param string $href   Style URL.
 * @param string $media  Media attribute.
 * @return string
 */
function sln_defer_style_loader_tag( $html, $handle, $href, $media ) {
	if ( ! in_array( $handle, sln_get_deferred_style_handles(), true ) ) {
		return $html;
	}

	if ( false === strpos( $html, "media='all'" ) ) {
		$html = str_replace( 'media="all"', "media='print' onload=\"this.media='all'\"", $html );
	} else {
		$html = str_replace( "media='all'", "media='print' onload=\"this.media='all'\"", $html );
	}

	$html .= '<noscript><link rel="stylesheet" href="' . esc_url( $href ) . '" media="all"></noscript>';

	return $html;
}
add_filter( 'style_loader_tag', 'sln_defer_style_loader_tag', 10, 4 );

/**
 * Add defer to non-blocking scripts.
 *
 * @param string $tag    Script tag HTML.
 * @param string $handle Script handle.
 * @param string $src    Script URL.
 * @return string
 */
function sln_defer_script_loader_tag( $tag, $handle, $src ) {
	if ( ! in_array( $handle, sln_get_deferred_script_handles(), true ) ) {
		return $tag;
	}

	if ( false !== strpos( $tag, ' defer' ) ) {
		return $tag;
	}

	return str_replace( ' src', ' defer src', $tag );
}
add_filter( 'script_loader_tag', 'sln_defer_script_loader_tag', 10, 3 );

/**
 * Enqueue the lightweight AI chat loader on the homepage.
 */
function sln_enqueue_ai_chat_loader() {
	if ( ! is_front_page() || ! function_exists( 'sln_get_ai_chat_loader_config' ) ) {
		return;
	}

	wp_enqueue_script(
		'sln-ai-chat-loader',
		SLN_THEME_URI . '/assets/js/ai-chat-loader.js',
		array(),
		SLN_THEME_VERSION,
		true
	);

	wp_localize_script(
		'sln-ai-chat-loader',
		'slsAiChatLoader',
		sln_get_ai_chat_loader_config()
	);
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_ai_chat_loader', 25 );

/**
 * Long-cache headers for theme static assets (Apache/Laragon).
 */
function sln_send_static_cache_headers() {
	if ( is_admin() || wp_doing_ajax() ) {
		return;
	}

	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';

	if ( '' === $request_uri || false === strpos( $request_uri, '/wp-content/themes/' . get_template() . '/assets/' ) ) {
		return;
	}

	$extension = strtolower( pathinfo( parse_url( $request_uri, PHP_URL_PATH ), PATHINFO_EXTENSION ) );
	$cacheable = array( 'css', 'js', 'webp', 'svg', 'png', 'jpg', 'jpeg', 'woff', 'woff2' );

	if ( ! in_array( $extension, $cacheable, true ) ) {
		return;
	}

	header( 'Cache-Control: public, max-age=31536000, immutable' );
}
add_action( 'send_headers', 'sln_send_static_cache_headers' );
