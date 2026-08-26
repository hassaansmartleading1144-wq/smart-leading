<?php
/**
 * AEO Services page — helpers, URLs, and icons.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_AEO_TEMPLATE', 'aeo-page-template.php' );
define( 'SLN_AEO_SLUG', 'answer-engine-optimization-aeo' );
define( 'SLN_AEO_PARENT_SLUG', 'digital-marketing-services' );
define( 'SLN_AEO_META_DESCRIPTION', 'Answer Engine Optimization services that help your brand get surfaced in AI Overviews, ChatGPT, Perplexity, and voice search — not just ranked in blue links.' );

/**
 * Whether the current request uses the AEO Services template.
 *
 * @return bool
 */
function sln_is_aeo_services_page() {
	return is_page_template( SLN_AEO_TEMPLATE );
}

/**
 * AEO page button — reuses the global sls-btn CTA.
 *
 * @param array $args Button args.
 */
function sln_render_aeo_page_button( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'text'    => '',
			'url'     => '#',
			'variant' => 'primary',
			'class'   => '',
			'arrow'   => true,
			'type'    => 'link',
		)
	);

	if ( '' === $args['text'] ) {
		return;
	}

	if ( ! function_exists( 'sln_render_cta_button' ) ) {
		return;
	}

	sln_render_cta_button(
		array(
			'text'       => $args['text'],
			'url'        => $args['url'],
			'type'       => $args['type'],
			'variant'    => $args['variant'],
			'show_arrow' => ! empty( $args['arrow'] ),
			'class'      => trim( 'sln-aeo-cta ' . $args['class'] ),
		)
	);
}

/**
 * Contact page URL.
 *
 * @return string
 */
function sln_aeo_get_contact_url() {
	$page = get_page_by_path( 'contact-us' );

	return $page instanceof WP_Post ? get_permalink( $page ) : home_url( '/contact-us/' );
}

/**
 * Digital Marketing Services parent URL.
 *
 * @return string
 */
function sln_aeo_get_digital_marketing_url() {
	$page = get_page_by_path( SLN_AEO_PARENT_SLUG );

	return $page instanceof WP_Post ? get_permalink( $page ) : home_url( '/' . SLN_AEO_PARENT_SLUG . '/' );
}

/**
 * SEO Services page URL.
 *
 * @return string
 */
function sln_aeo_get_seo_services_url() {
	$page = get_page_by_path( 'seo-services' );

	if ( $page instanceof WP_Post ) {
		return get_permalink( $page );
	}

	$pages = get_pages(
		array(
			'meta_key'   => '_wp_page_template',
			'meta_value' => 'seo-page-template.php',
			'number'     => 1,
		)
	);

	if ( ! empty( $pages[0] ) && $pages[0] instanceof WP_Post ) {
		return get_permalink( $pages[0] );
	}

	return home_url( '/seo-services/' );
}

/**
 * Inline SVG icon by key.
 *
 * @param string $icon Icon key.
 * @return string
 */
function sln_aeo_icon( $icon ) {
	$icon = sanitize_key( (string) $icon );

	$icons = array(
		'star'       => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3l2.2 5.8L20 11l-5.8 2.2L12 19l-2.2-5.8L4 11l5.8-2.2L12 3z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>',
		'list'       => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 6h16M4 12h10M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
		'check'      => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'arrow'      => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 12h16M14 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'help'       => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6"/><path d="M9 10c0-1.7 1.3-3 3-3s3 1.3 3 3c0 2-3 2-3 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
		'plus'       => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 4v16M4 12h16" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
		'mic'        => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="9" y="3" width="6" height="12" rx="3" stroke="currentColor" stroke-width="1.6"/><path d="M6 11a6 6 0 0012 0M12 19v2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
		'lines'      => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 12h18M3 6h18M3 18h11" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
		'doc'        => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="4" y="4" width="16" height="16" rx="3" stroke="currentColor" stroke-width="1.6"/><path d="M8 10h8M8 14h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
		'search'     => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8"/><path d="M21 21l-4.3-4.3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>',
		'square'     => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="4" y="4" width="16" height="16" rx="4" stroke="currentColor" stroke-width="1.8"/></svg>',
		'nodes'      => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="7" cy="7" r="2.5" stroke="currentColor" stroke-width="1.6"/><circle cx="17" cy="7" r="2.5" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="17" r="2.5" stroke="currentColor" stroke-width="1.6"/></svg>',
		'graph'      => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="7" cy="7" r="2.5" stroke="currentColor" stroke-width="1.6"/><circle cx="17" cy="7" r="2.5" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="17" r="2.5" stroke="currentColor" stroke-width="1.6"/><path d="M9 8.5L10.5 15M15 8.5L13.5 15M9.5 7h5" stroke="currentColor" stroke-width="1.4"/></svg>',
		'blocks'     => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 5h16v6H4zM4 15h10v4H4z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>',
		'crosshair'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3v18M3 12h18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6"/></svg>',
		'voice'      => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 2a4 4 0 014 4v5a4 4 0 01-8 0V6a4 4 0 014-4z" stroke="currentColor" stroke-width="1.6"/><path d="M6 11a6 6 0 0012 0M12 19v3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
		'bag'        => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 8l1-4h14l1 4M4 8h16M4 8l1 12h14l1-12" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>',
		'health'     => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3v18M5 8h14M5 16h14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
		'home'       => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 21V9l8-6 8 6v12M9 21v-6h6v6" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>',
		'pin'        => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 21c4-4 7-7.5 7-11a7 7 0 10-14 0c0 3.5 3 7 7 11z" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="10" r="2.4" stroke="currentColor" stroke-width="1.6"/></svg>',
		'card'       => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="6" width="18" height="12" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M8 10h3M8 14h6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
		'award'      => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 3h6l1 5-4 3-4-3 1-5zM6 21l3-6h6l3 6" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>',
		'trend'      => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 17l5-5 4 4 7-8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'tick'       => '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'faq'        => '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.5"/></svg>',
	);

	return isset( $icons[ $icon ] ) ? $icons[ $icon ] : '';
}

/**
 * Icon keys available in AEO admin selects.
 *
 * @return array<string, string>
 */
function sln_aeo_get_icon_choices() {
	return array(
		'star'      => __( 'Star', 'smart-leading-net' ),
		'list'      => __( 'List', 'smart-leading-net' ),
		'check'     => __( 'Check', 'smart-leading-net' ),
		'arrow'     => __( 'Arrow', 'smart-leading-net' ),
		'help'      => __( 'Help', 'smart-leading-net' ),
		'plus'      => __( 'Plus', 'smart-leading-net' ),
		'mic'       => __( 'Microphone', 'smart-leading-net' ),
		'lines'     => __( 'Lines', 'smart-leading-net' ),
		'doc'       => __( 'Document', 'smart-leading-net' ),
		'search'    => __( 'Search', 'smart-leading-net' ),
		'square'    => __( 'Square', 'smart-leading-net' ),
		'nodes'     => __( 'Nodes', 'smart-leading-net' ),
		'graph'     => __( 'Graph', 'smart-leading-net' ),
		'blocks'    => __( 'Blocks', 'smart-leading-net' ),
		'crosshair' => __( 'Crosshair', 'smart-leading-net' ),
		'voice'     => __( 'Voice', 'smart-leading-net' ),
		'bag'       => __( 'Bag', 'smart-leading-net' ),
		'health'    => __( 'Health', 'smart-leading-net' ),
		'home'      => __( 'Home', 'smart-leading-net' ),
		'pin'       => __( 'Pin', 'smart-leading-net' ),
		'card'      => __( 'Card', 'smart-leading-net' ),
		'award'     => __( 'Award', 'smart-leading-net' ),
		'trend'     => __( 'Trend', 'smart-leading-net' ),
		'tick'      => __( 'Tick', 'smart-leading-net' ),
		'faq'       => __( 'FAQ', 'smart-leading-net' ),
	);
}

/**
 * FAQ schema for JSON-LD.
 *
 * @return array<string, mixed>
 */
function sln_aeo_get_faq_schema() {
	$entities = array();

	foreach ( sln_aeo_get_faq_items() as $item ) {
		$entities[] = array(
			'@type'          => 'Question',
			'name'           => $item['question'],
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => $item['answer'],
			),
		);
	}

	return array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $entities,
	);
}

/**
 * Output page title for the AEO template.
 *
 * @param array<string, string> $title Title parts.
 * @return array<string, string>
 */
function sln_aeo_filter_document_title( $title ) {
	if ( ! sln_is_aeo_services_page() ) {
		return $title;
	}

	$title['title'] = __( 'Answer Engine Optimization (AEO) Services', 'smart-leading-net' );

	return $title;
}
add_filter( 'document_title_parts', 'sln_aeo_filter_document_title' );

/**
 * Output meta description for the AEO template.
 */
function sln_aeo_output_meta_description() {
	if ( ! sln_is_aeo_services_page() ) {
		return;
	}

	printf(
		'<meta name="description" content="%s">' . "\n",
		esc_attr( SLN_AEO_META_DESCRIPTION )
	);
}
add_action( 'wp_head', 'sln_aeo_output_meta_description', 4 );
