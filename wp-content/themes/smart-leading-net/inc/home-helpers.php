<?php
/**
 * Homepage (front page) helpers — icons, content and assets for the
 * template-parts/home/ sections. Copy source: work/homepage/01-merge-plan.md.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Inline stroke icon (24×24) for homepage sections.
 *
 * @param string $name  Icon key.
 * @param string $class Extra class.
 * @return string SVG markup.
 */
function sln_home_icon( $name, $class = '' ) {
	$paths = array(
		'award'    => '<circle cx="12" cy="8" r="6"/><path d="M15.48 12.89 17 22l-5-3-5 3 1.52-9.11"/>',
		'badge'    => '<path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m9 12 2 2 4-4"/>',
		'cpu'      => '<rect x="5" y="5" width="14" height="14" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M9 1v4M15 1v4M9 19v4M15 19v4M1 9h4M1 15h4M19 9h4M19 15h4"/>',
		'bag'      => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>',
		'code'     => '<polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>',
		'users'    => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
		'target'   => '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>',
		'wallet'   => '<path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/>',
		'key'      => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><circle cx="12" cy="10" r="2"/><path d="M12 12v4"/>',
		'search'   => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/><path d="M11 8v6M8 11h6"/>',
		'click'    => '<path d="m9 9 5 12 1.8-5.2L21 14Z"/><path d="M7.2 2.2 8 5.1M5.1 8l-2.9-.8M14 4.1 12 6M6 12l-1.9 2"/>',
		'social'   => '<path d="M7 10v12"/><path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2a3.13 3.13 0 0 1 3 3.88Z"/>',
		'layout'   => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>',
		'mail'     => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>',
		'chart'    => '<path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/>',
		'pipeline' => '<rect x="3" y="3" width="5" height="18" rx="1"/><rect x="10" y="3" width="5" height="12" rx="1"/><rect x="17" y="3" width="4" height="8" rx="1"/>',
		'bot'      => '<path d="M12 8V4H8"/><rect x="4" y="8" width="16" height="12" rx="2"/><path d="M2 14h2M20 14h2M15 13v2M9 13v2"/>',
		'pin'      => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
		'reply'    => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/><path d="M8 10h.01M12 10h.01M16 10h.01"/>',
		'revenue'  => '<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',
		'shield'   => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/>',
		'arrow'    => '<path d="M5 12h14M12 5l7 7-7 7"/>',
		'external' => '<path d="M15 3h6v6M10 14 21 3M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>',
		'check'    => '<path d="M20 6 9 17l-5-5"/>',
		'funnel'   => '<path d="M22 3H2l8 9.46V19l4 2v-8.54Z"/>',
		'phone'    => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92Z"/>',
		'form'     => '<rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 8h8M8 12h8M8 16h5"/>',
		'chat'     => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"/>',
		'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/><path d="m9 16 2 2 4-4"/>',
		'star'     => '<path d="m12 2 3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01Z"/>',
		'sparkle'  => '<path d="M12 3l1.9 5.8L20 11l-6.1 2.2L12 19l-1.9-5.8L4 11l6.1-2.2Z"/><path d="M19 3v4M17 5h4"/>',
		'trend'    => '<path d="m22 7-8.5 8.5-5-5L2 17"/><path d="M16 7h6v6"/>',
	);

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	return sprintf(
		'<svg class="sls-home-icon %1$s" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%2$s</svg>',
		esc_attr( $class ),
		$paths[ $name ]
	);
}

/**
 * Contact page URL used by homepage CTAs.
 *
 * @return string
 */
function sln_home_contact_url() {
	$contact_page = get_page_by_path( 'contact-us' );
	return $contact_page ? get_permalink( $contact_page ) : home_url( '/contact-us/' );
}

/**
 * Homepage services (copy from the owner's homepage copy, 25 Nov 2026).
 *
 * @return array<int, array<string, mixed>>
 */
function sln_home_services() {
	return array(
		array(
			'slug'    => 'seo',
			'logo'    => array( 'file' => 'google.svg', 'w' => 48, 'h' => 48, 'square' => true ),
			'icon'    => 'search',
			'title'   => 'SEO & AI Search Visibility',
			'benefit' => 'Be found when customers are ready to buy.',
			'details' => array( 'Technical and local SEO', 'buyer research', 'commercial content', 'entity SEO', 'relevant backlinks', 'digital PR', 'visibility in Google, ChatGPT and Gemini' ),
			'url'     => home_url( '/seo-services/' ),
		),
		array(
			'slug'    => 'google-ads',
			'logo'    => array( 'file' => 'google-ads.svg', 'w' => 30, 'h' => 27, 'square' => true ),
			'icon'    => 'click',
			'title'   => 'Google Ads',
			'benefit' => 'Make more of your ad budget.',
			'details' => array( 'Search', 'Shopping', 'Performance Max', 'YouTube campaigns' ),
			'note'    => 'We track qualified leads, sales and return on ad spend.',
			'url'     => home_url( '/ppc/google-ads-management/' ),
		),
		array(
			'slug'    => 'meta',
			'logo'    => array( 'file' => 'meta.svg', 'w' => 85, 'h' => 17 ),
			'icon'    => 'social',
			'title'   => 'Meta & Paid Social',
			'benefit' => 'Reach people who are likely to buy.',
			'details' => array( 'Facebook and Instagram ads', 'audience research', 'creative design', 'retargeting', 'conversion optimization' ),
			'url'     => home_url( '/ppc/facebook-ads-management/' ),
		),
		array(
			'slug'    => 'web',
			'logo'    => array( 'file' => 'wordpress.png', 'w' => 480, 'h' => 163, 'padded' => true ),
			'icon'    => 'layout',
			'title'   => 'Web Design & Development',
			'benefit' => 'Give customers a reason to choose you.',
			'details' => array( 'Custom WordPress development', 'UI/UX', 'landing pages', 'plugins', 'code', 'speed', 'website integrations' ),
			'url'     => home_url( '/web-development-services/' ),
		),
		array(
			'slug'    => 'shopify',
			'logo'    => array( 'file' => 'shopify.svg', 'w' => 304, 'h' => 87 ),
			'icon'    => 'bag',
			'title'   => 'Shopify & Ecommerce',
			'benefit' => 'Sell more from your online store.',
			'details' => array( 'Custom Shopify themes', 'product SEO', 'checkout', 'payment', 'shipping', 'inventory', 'store optimization' ),
			'url'     => home_url( '/seo-services/ecommerce-seo/' ),
		),
		array(
			'slug'    => 'email-sms',
			'logo'    => array( 'file' => 'gmail.svg', 'w' => 88, 'h' => 66, 'square' => true ),
			'icon'    => 'mail',
			'title'   => 'Email & SMS Marketing',
			'benefit' => 'Turn more inquiries into repeat customers.',
			'details' => array( 'Email campaigns', 'customer segments', 'abandoned carts', 'quote follow ups', 'retention workflows' ),
			'url'     => home_url( '/digital-marketing-services/' ),
		),
		array(
			'slug'    => 'tracking',
			'logo'    => array( 'file' => 'lead-analytics.webp', 'w' => 640, 'h' => 158, 'padded' => true ),
			'icon'    => 'chart',
			'title'   => 'Conversion Tracking',
			'benefit' => 'Know which marketing brings in money.',
			'details' => array( 'GA4', 'Google Tag Manager', 'dynamic phone numbers', 'call tracking', 'server side events', 'offline sales attribution' ),
			'url'     => home_url( '/digital-marketing-services/' ),
		),
		array(
			'slug'    => 'crm',
			'logo'    => array( 'file' => 'highlevel.svg', 'w' => 172, 'h' => 38 ),
			'icon'    => 'pipeline',
			'title'   => 'GoHighLevel CRM',
			'benefit' => 'Keep every lead and sales opportunity in one place.',
			'details' => array( 'Sales funnels', 'pipelines', 'calendars', 'automated follow ups', 'missed call replies', 'reporting' ),
			'url'     => home_url( '/digital-marketing-services/' ),
		),
		array(
			'slug'    => 'ai-agents',
			'logo'    => array( 'file' => 'chatgpt.svg', 'w' => 24, 'h' => 24, 'square' => true ),
			'icon'    => 'bot',
			'title'   => 'AI Agents & Automation',
			'benefit' => 'Let your team spend more time with customers.',
			'details' => array( 'AI chat and voice agents', 'lead qualification', 'appointment booking', 'API integrations', 'custom workflows' ),
			'url'     => home_url( '/digital-marketing-services/' ),
		),
	);
}

/**
 * Enqueue homepage section assets and drop styles/scripts of sections
 * the homepage no longer renders.
 */
function sln_enqueue_home_assets() {
	if ( ! is_front_page() ) {
		return;
	}

	$css_path = SLN_THEME_DIR . '/assets/css/home.css';
	$js_path  = SLN_THEME_DIR . '/assets/js/home.js';

	wp_enqueue_style(
		'sln-home',
		SLN_THEME_URI . '/assets/css/home.css',
		array( 'sln-main', 'sln-hero-banner' ),
		file_exists( $css_path ) ? (string) filemtime( $css_path ) : SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-home',
		SLN_THEME_URI . '/assets/js/home.js',
		array(),
		file_exists( $js_path ) ? (string) filemtime( $js_path ) : SLN_THEME_VERSION,
		true
	);

	$unused = array(
		'sln-accomplishments',
		'sln-businesses-choose',
		'sln-why-choose-us',
		'sln-our-services',
		'sln-case-studies',
		'sln-expertise',
		'sln-growing',
		'sln-testimonials',
		'sln-credibility',
	);

	foreach ( $unused as $handle ) {
		wp_dequeue_style( $handle );
		wp_dequeue_script( $handle );
	}
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_home_assets', 100 );

/*
 * The homepage hero no longer shows banner_image.webp (it is now a dashboard
 * drawn in HTML/CSS), so don't preload that image.
 */
remove_action( 'wp_head', 'sln_preload_lcp_image', 1 );
