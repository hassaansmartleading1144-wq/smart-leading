<?php
/**
 * Smart Leading Net theme functions and definitions.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	define( 'SLN_THEME_VERSION', '1.7.1' );
define( 'SLN_THEME_DIR', get_template_directory() );
define( 'SLN_THEME_URI', get_template_directory_uri() );

require SLN_THEME_DIR . '/inc/theme-setup.php';
require SLN_THEME_DIR . '/inc/template-tags.php';
require SLN_THEME_DIR . '/inc/enqueue-assets.php';
require SLN_THEME_DIR . '/inc/performance.php';
require SLN_THEME_DIR . '/inc/class-bootstrap-nav-walker.php';
require SLN_THEME_DIR . '/inc/upload-restrictions.php';
require SLN_THEME_DIR . '/inc/our-services-settings.php';
require SLN_THEME_DIR . '/inc/our-services-admin-fields.php';
require SLN_THEME_DIR . '/inc/our-services-admin.php';
require SLN_THEME_DIR . '/inc/our-projects-settings.php';
require SLN_THEME_DIR . '/inc/our-projects-admin.php';
require SLN_THEME_DIR . '/inc/credibility-settings.php';
require SLN_THEME_DIR . '/inc/credibility-admin.php';
require SLN_THEME_DIR . '/inc/settings-save-helpers.php';
require SLN_THEME_DIR . '/inc/growth-pages-cpt.php';
require SLN_THEME_DIR . '/inc/growth-pages-sections.php';
require SLN_THEME_DIR . '/inc/growth-pages-editor.php';
require SLN_THEME_DIR . '/inc/growth-pages-save-helpers.php';
// Debug-only — enable in wp-config: define( 'SLN_GP_SAVE_DEBUG', true );
if ( defined( 'SLN_GP_SAVE_DEBUG' ) && SLN_GP_SAVE_DEBUG ) {
	require SLN_THEME_DIR . '/inc/growth-pages-save-debug.php';
}
require SLN_THEME_DIR . '/inc/growth-pages-meta.php';
require SLN_THEME_DIR . '/inc/growth-pages-growth-metrics-meta.php';
require SLN_THEME_DIR . '/inc/growth-pages-services-meta.php';
require SLN_THEME_DIR . '/inc/growth-pages-client-story-meta.php';
require SLN_THEME_DIR . '/inc/growth-pages-how-work-meta.php';
require SLN_THEME_DIR . '/inc/growth-pages-growth-services-meta.php';
require SLN_THEME_DIR . '/inc/growth-pages-case-studies-meta.php';
require SLN_THEME_DIR . '/inc/growth-pages-why-choose-meta.php';
require SLN_THEME_DIR . '/inc/growth-pages-price-plan-meta.php';
require SLN_THEME_DIR . '/inc/growth-pages-testimonials-meta.php';
require SLN_THEME_DIR . '/inc/growth-pages-cta-banner-meta.php';
require SLN_THEME_DIR . '/inc/ghl-settings.php';
require SLN_THEME_DIR . '/inc/ghl-api.php';
require SLN_THEME_DIR . '/inc/ghl-form-handler.php';
require SLN_THEME_DIR . '/inc/seo-page-data.php';
require SLN_THEME_DIR . '/inc/page-template-admin-helpers.php';
require SLN_THEME_DIR . '/inc/seo-services-helpers.php';
require SLN_THEME_DIR . '/inc/seo-services-save.php';
require SLN_THEME_DIR . '/inc/seo-services-admin-fields.php';
require SLN_THEME_DIR . '/inc/seo-services-admin.php';
require SLN_THEME_DIR . '/inc/digital-marketing-page-data.php';
require SLN_THEME_DIR . '/inc/portfolio-page-helpers.php';
require SLN_THEME_DIR . '/inc/portfolio-page-save.php';
require SLN_THEME_DIR . '/inc/portfolio-page-admin-fields.php';
require SLN_THEME_DIR . '/inc/portfolio-page-admin.php';
require SLN_THEME_DIR . '/inc/demo-package.php';
require SLN_THEME_DIR . '/inc/demo-importer.php';
require SLN_THEME_DIR . '/inc/demo-exporter.php';
require SLN_THEME_DIR . '/inc/ai-chat-api.php';

/**
 * Enqueue hero banner assets on the front page only.
 */
function sln_enqueue_hero_banner_assets() {
	if ( ! is_front_page() ) {
		return;
	}

	wp_enqueue_style(
		'sln-hero-banner',
		SLN_THEME_URI . '/assets/css/hero-banner.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-hero-banner',
		SLN_THEME_URI . '/assets/js/hero-banner.js',
		array(),
		SLN_THEME_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_hero_banner_assets' );

/**
 * Enqueue accomplishments section assets on the front page only.
 */
function sln_enqueue_accomplishments_assets() {
	if ( ! is_front_page() ) {
		return;
	}

	wp_enqueue_style(
		'sln-accomplishments',
		SLN_THEME_URI . '/assets/css/accomplishments.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-accomplishments',
		SLN_THEME_URI . '/assets/js/accomplishments.js',
		array(),
		SLN_THEME_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_accomplishments_assets' );

/**
 * Enqueue businesses choose section assets on the front page only.
 */
function sln_enqueue_businesses_choose_assets() {
	if ( ! is_front_page() ) {
		return;
	}

	wp_enqueue_style(
		'sln-businesses-choose',
		SLN_THEME_URI . '/assets/css/businesses-choose.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-businesses-choose',
		SLN_THEME_URI . '/assets/js/businesses-choose.js',
		array(),
		SLN_THEME_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_businesses_choose_assets' );

/**
 * Enqueue our services section assets on the front page only.
 */
function sln_enqueue_our_services_assets() {
	if ( ! is_front_page() ) {
		return;
	}

	wp_enqueue_style(
		'sln-our-services',
		SLN_THEME_URI . '/assets/css/our-services.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-our-services',
		SLN_THEME_URI . '/assets/js/our-services.js',
		array(),
		SLN_THEME_VERSION,
		true
	);

	$our_services_settings = sln_get_our_services_settings();

	wp_localize_script(
		'sln-our-services',
		'slnOurServices',
		array(
			'counterEnabled'  => ! empty( $our_services_settings['counter']['enabled'] ),
			'counterDuration' => absint( $our_services_settings['counter']['duration'] ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_our_services_assets' );

/**
 * Enqueue case studies section assets on the front page only.
 */
function sln_enqueue_case_studies_assets() {
	if ( ! is_front_page() ) {
		return;
	}

	wp_enqueue_style(
		'sln-case-studies',
		SLN_THEME_URI . '/assets/css/case-studies.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_case_studies_assets' );

/**
 * Enqueue workflow section assets on the front page and About Us page.
 */
function sln_enqueue_workflow_assets() {
	if ( ! is_front_page() && ! is_page_template( array( 'about-us-template.php', 'about-template.php' ) ) ) {
		return;
	}

	wp_enqueue_style(
		'sln-workflow',
		SLN_THEME_URI . '/assets/css/workflow.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);

	// Workflow critical CSS only on About — homepage workflow CSS is deferred.
	if ( is_page_template( array( 'about-us-template.php', 'about-template.php' ) ) ) {
		wp_add_inline_style( 'sln-main', sln_get_workflow_critical_css() );
	}
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_workflow_assets' );

/**
 * Enqueue new section assets on the front page only.
 */
function sln_enqueue_new_section_assets() {
	if ( ! is_front_page() ) {
		return;
	}

	wp_enqueue_style(
		'sln-new-section',
		SLN_THEME_URI . '/assets/css/new-section.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_new_section_assets' );

/**
 * Enqueue team section assets on the front page only.
 */
function sln_enqueue_team_assets() {
	if ( ! is_front_page() ) {
		return;
	}

	wp_enqueue_style(
		'sln-team',
		SLN_THEME_URI . '/assets/css/team.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_team_assets' );

/**
 * Enqueue starts CTA section assets on the front page only.
 */
function sln_enqueue_starts_cta_assets() {
	if ( ! is_front_page() ) {
		return;
	}

	wp_enqueue_style(
		'sln-starts-cta',
		SLN_THEME_URI . '/assets/css/starts-cta.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-starts-cta',
		SLN_THEME_URI . '/assets/js/starts-cta.js',
		array(),
		SLN_THEME_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_starts_cta_assets' );

/**
 * Enqueue Swiper assets once for homepage sliders.
 */
function sln_enqueue_swiper_assets() {
	wp_enqueue_style(
		'swiper',
		'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
		array(),
		'11.2.10'
	);

	wp_enqueue_script(
		'swiper',
		'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
		array(),
		'11.2.10',
		true
	);

	wp_enqueue_style(
		'sln-swiper-overrides',
		SLN_THEME_URI . '/assets/css/swiper-overrides.css',
		array( 'swiper' ),
		SLN_THEME_VERSION
	);
}

/**
 * Enqueue expertise section assets on the front page only.
 */
function sln_enqueue_expertise_assets() {
	if ( ! is_front_page() ) {
		return;
	}

	wp_enqueue_style(
		'sln-expertise',
		SLN_THEME_URI . '/assets/css/expertise.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_expertise_assets' );

/**
 * Enqueue growing section assets on the front page only.
 */
function sln_enqueue_growing_assets() {
	if ( ! is_front_page() ) {
		return;
	}

	wp_enqueue_style(
		'sln-growing',
		SLN_THEME_URI . '/assets/css/growing.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-growing',
		SLN_THEME_URI . '/assets/js/growing.js',
		array(),
		SLN_THEME_VERSION,
		true
	);

	wp_localize_script(
		'sln-growing',
		'slnGrowingForm',
		array(
			'ajaxUrl'          => admin_url( 'admin-ajax.php' ),
			'action'           => SLN_GROWING_FORM_AJAX_ACTION,
			'nonce'            => wp_create_nonce( SLN_GROWING_FORM_NONCE_ACTION ),
			'sendingLabel'     => __( 'Sending…', 'smart-leading-net' ),
			'successTitle'     => __( 'Thank You!', 'smart-leading-net' ),
			'successLines'     => array(
				__( 'Your request has been submitted successfully.', 'smart-leading-net' ),
				__( 'A Smart Leading team member will review your information and contact you shortly to discuss your business goals and growth opportunities.', 'smart-leading-net' ),
			),
			'successVisibleMs' => 10000,
			'errorMessage'     => __( 'Something went wrong. Please try again or contact us directly.', 'smart-leading-net' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_growing_assets' );

/**
 * Enqueue our projects section assets on the front page only.
 */
function sln_enqueue_our_project_assets() {
	if ( ! is_front_page() ) {
		return;
	}

	sln_enqueue_swiper_assets();

	wp_enqueue_style(
		'sln-our-project',
		SLN_THEME_URI . '/assets/css/our-project.css',
		array( 'sln-main', 'swiper' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-our-project',
		SLN_THEME_URI . '/assets/js/our-project.js',
		array( 'swiper' ),
		SLN_THEME_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_our_project_assets' );

/**
 * Enqueue testimonials section assets on the front page only.
 */
function sln_enqueue_testimonials_assets() {
	if ( ! is_front_page() ) {
		return;
	}

	wp_enqueue_style(
		'sln-testimonials',
		SLN_THEME_URI . '/assets/css/testimonials.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_testimonials_assets' );

/**
 * Enqueue credibility section assets on the front page and Portfolio template.
 */
function sln_enqueue_credibility_assets() {
	if ( ! is_front_page() && ! is_page_template( SLN_PORTFOLIO_TEMPLATE ) ) {
		return;
	}

	wp_enqueue_style(
		'sln-credibility',
		SLN_THEME_URI . '/assets/css/credibility.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_credibility_assets' );

/**
 * Enqueue reusable inner page banner styles.
 */
function sln_enqueue_page_banner_assets() {
	wp_enqueue_style(
		'sln-page-banner',
		SLN_THEME_URI . '/assets/css/page-banner.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);
}

/**
 * Enqueue Contact page template assets.
 */
function sln_enqueue_contact_page_assets() {
	if ( ! is_page_template( array( 'contact-template.php', 'thank-you-template.php' ) ) ) {
		return;
	}

	sln_enqueue_page_banner_assets();

	wp_enqueue_style(
		'sln-contact-page',
		SLN_THEME_URI . '/assets/css/contact-page.css',
		array( 'sln-page-banner' ),
		SLN_THEME_VERSION
	);

	if ( ! is_page_template( 'contact-template.php' ) ) {
		return;
	}

	wp_enqueue_style(
		'intl-tel-input',
		'https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.0/build/css/intlTelInput.min.css',
		array(),
		'23.8.0'
	);

	wp_enqueue_script(
		'intl-tel-input',
		'https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.0/build/js/intlTelInput.min.js',
		array(),
		'23.8.0',
		true
	);

	wp_enqueue_script(
		'sln-contact-form',
		SLN_THEME_URI . '/assets/js/contact.js',
		array( 'intl-tel-input' ),
		SLN_THEME_VERSION,
		true
	);

	wp_localize_script(
		'sln-contact-form',
		'slnContactForm',
		array(
			'ajaxUrl'          => admin_url( 'admin-ajax.php' ),
			'action'           => SLN_CONTACT_FORM_AJAX_ACTION,
			'nonce'            => wp_create_nonce( SLN_CONTACT_FORM_NONCE_ACTION ),
			'thankYouUrl'      => sln_get_thank_you_page_url(),
			'submitLabel'      => __( 'Submit', 'smart-leading-net' ),
			'submittingLabel'  => __( 'Submitting...', 'smart-leading-net' ),
			'errorMessage'     => __( 'Sorry, something went wrong. Please try again.', 'smart-leading-net' ),
			'utilsScript'      => 'https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.0/build/js/utils.js',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_contact_page_assets' );

/**
 * Enqueue About Us page template assets.
 */
function sln_enqueue_about_page_assets() {
	if ( ! is_page_template( array( 'about-us-template.php', 'about-template.php' ) ) ) {
		return;
	}

	sln_enqueue_page_banner_assets();
	sln_enqueue_workflow_assets();

	wp_enqueue_style(
		'sln-about-page',
		SLN_THEME_URI . '/assets/css/about-page.css',
		array( 'sln-page-banner', 'sln-workflow' ),
		SLN_THEME_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_about_page_assets' );

/**
 * Enqueue SEO Services page template assets.
 */
function sln_enqueue_seo_page_assets() {
	if ( ! is_page_template( 'seo-page-template.php' ) ) {
		return;
	}

	wp_enqueue_style(
		'sln-seo-page',
		SLN_THEME_URI . '/assets/css/seo-page.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_style(
		'sln-case-studies',
		SLN_THEME_URI . '/assets/css/case-studies.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-case-studies',
		SLN_THEME_URI . '/assets/js/case-studies.js',
		array(),
		SLN_THEME_VERSION,
		true
	);

	wp_enqueue_style(
		'sln-price-plan',
		SLN_THEME_URI . '/assets/css/price-plan.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-price-plan',
		SLN_THEME_URI . '/assets/js/price-plan.js',
		array(),
		SLN_THEME_VERSION,
		true
	);

	wp_enqueue_style(
		'sln-testimonials',
		SLN_THEME_URI . '/assets/css/testimonials.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-testimonials',
		SLN_THEME_URI . '/assets/js/testimonials.js',
		array(),
		SLN_THEME_VERSION,
		true
	);

	wp_enqueue_script(
		'sln-seo-page',
		SLN_THEME_URI . '/assets/js/seo-page.js',
		array(),
		SLN_THEME_VERSION,
		true
	);

	wp_localize_script(
		'sln-seo-page',
		'slnSeoForm',
		array(
			'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
			'action'       => SLN_SEO_FORM_AJAX_ACTION,
			'nonce'        => wp_create_nonce( SLN_SEO_FORM_NONCE_ACTION ),
			'sendingLabel' => __( 'Sending…', 'smart-leading-net' ),
			'errorMessage' => __( 'Something went wrong. Please try again or contact us directly.', 'smart-leading-net' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_seo_page_assets' );

/**
 * Enqueue Digital Marketing Services page template assets.
 */
function sln_enqueue_digital_marketing_page_assets() {
	if ( ! sln_is_digital_marketing_services_page() ) {
		return;
	}

	wp_enqueue_style(
		'sln-digital-marketing-page',
		SLN_THEME_URI . '/assets/css/digital-marketing-page.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-digital-marketing-page',
		SLN_THEME_URI . '/assets/js/digital-marketing-page.js',
		array(),
		SLN_THEME_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_digital_marketing_page_assets' );

/**
 * Enqueue Portfolio page template assets.
 */
function sln_enqueue_portfolio_page_assets() {
	if ( ! is_page_template( SLN_PORTFOLIO_TEMPLATE ) ) {
		return;
	}

	wp_enqueue_style(
		'sln-page-banner',
		SLN_THEME_URI . '/assets/css/page-banner.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_style(
		'sln-our-project',
		SLN_THEME_URI . '/assets/css/our-project.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_style(
		'sln-portfolio-page',
		SLN_THEME_URI . '/assets/css/portfolio-page.css',
		array( 'sln-page-banner', 'sln-our-project' ),
		SLN_THEME_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_portfolio_page_assets' );
