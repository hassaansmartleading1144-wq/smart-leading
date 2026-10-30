<?php
/**
 * Locale SEO page helpers — enqueue, form AJAX, editor assets.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_LOCEL_SEO_TEMPLATE', 'locel-seo.php' );
define( 'SLN_LOCEL_SEO_FORM_NONCE_ACTION', 'sln_locel_seo_form' );
define( 'SLN_LOCEL_SEO_FORM_AJAX_ACTION', 'sln_locel_seo_submit_lead' );

/**
 * Whether the current request is the Locale SEO page template.
 *
 * @return bool
 */
function sln_is_locel_seo_page() {
	return is_page_template( SLN_LOCEL_SEO_TEMPLATE );
}

/**
 * Enqueue frontend assets only on the Locale SEO template.
 */
function sln_enqueue_locel_seo_assets() {
	if ( ! sln_is_locel_seo_page() ) {
		return;
	}

	$css_path = SLN_THEME_DIR . '/assets/css/locel-seo.css';
	$js_path  = SLN_THEME_DIR . '/assets/js/locel-seo.js';

	wp_enqueue_style(
		'sln-locel-seo',
		SLN_THEME_URI . '/assets/css/locel-seo.css',
		array( 'sln-style', 'sln-buttons' ),
		file_exists( $css_path ) ? (string) filemtime( $css_path ) : SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-locel-seo',
		SLN_THEME_URI . '/assets/js/locel-seo.js',
		array(),
		file_exists( $js_path ) ? (string) filemtime( $js_path ) : SLN_THEME_VERSION,
		true
	);

	$thank_you = get_page_by_path( 'thank-you' );
	wp_localize_script(
		'sln-locel-seo',
		'slnLocelSeoForm',
		array(
			'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
			'action'         => SLN_LOCEL_SEO_FORM_AJAX_ACTION,
			'nonce'          => wp_create_nonce( SLN_LOCEL_SEO_FORM_NONCE_ACTION ),
			'sendingLabel'   => __( 'Sending…', 'smart-leading-net' ),
			'successMessage' => __( 'Thank you — your Local SEO audit request has been sent. A Smart Leading team member will follow up shortly.', 'smart-leading-net' ),
			'errorMessage'   => __( 'Something went wrong. Please try again or contact us directly.', 'smart-leading-net' ),
			'nameRequired'   => __( 'Enter your full name.', 'smart-leading-net' ),
			'emailRequired'  => __( 'Enter your business email address.', 'smart-leading-net' ),
			'emailInvalid'   => __( 'Enter an email address in the format name@company.com.', 'smart-leading-net' ),
			'websiteInvalid' => __( 'Enter a website address such as yourcompany.com, or leave this field blank.', 'smart-leading-net' ),
			'areaRequired'   => __( 'Enter your city or service area.', 'smart-leading-net' ),
			'redirect'       => $thank_you ? get_permalink( $thank_you ) : '',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_locel_seo_assets' );

/**
 * Load Locale SEO styles in the block editor when editing a page that uses the template.
 */
function sln_enqueue_locel_seo_editor_assets() {
	$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( ! $post_id ) {
		return;
	}

	if ( SLN_LOCEL_SEO_TEMPLATE !== get_page_template_slug( $post_id ) ) {
		return;
	}

	wp_enqueue_style(
		'sln-variables',
		SLN_THEME_URI . '/assets/css/variables.css',
		array(),
		SLN_THEME_VERSION
	);
	wp_enqueue_style(
		'sln-buttons',
		SLN_THEME_URI . '/assets/css/buttons.css',
		array( 'sln-variables' ),
		SLN_THEME_VERSION
	);
	wp_enqueue_style( 'sln-locel-seo' );
	wp_enqueue_style( 'smartleading-locel-seo-editor-style' );
}
add_action( 'enqueue_block_editor_assets', 'sln_enqueue_locel_seo_editor_assets' );

/**
 * Register Locale SEO form AJAX handlers.
 */
function sln_locel_seo_register_ajax() {
	add_action( 'wp_ajax_' . SLN_LOCEL_SEO_FORM_AJAX_ACTION, 'sln_locel_seo_form_submit_handler' );
	add_action( 'wp_ajax_nopriv_' . SLN_LOCEL_SEO_FORM_AJAX_ACTION, 'sln_locel_seo_form_submit_handler' );
}
add_action( 'init', 'sln_locel_seo_register_ajax' );

/**
 * Handle Locale SEO audit form via the existing GHL contact pipeline.
 */
function sln_locel_seo_form_submit_handler() {
	if ( ! check_ajax_referer( SLN_LOCEL_SEO_FORM_NONCE_ACTION, 'nonce', false ) ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Security check failed. Please refresh the page and try again.', 'smart-leading-net' ),
			),
			403
		);
	}

	$honeypot = isset( $_POST['company_url'] ) ? sanitize_text_field( wp_unslash( $_POST['company_url'] ) ) : '';
	if ( '' !== trim( $honeypot ) ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Something went wrong. Please try again or contact us directly.', 'smart-leading-net' ),
			),
			400
		);
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$website = isset( $_POST['website'] ) ? sanitize_text_field( wp_unslash( $_POST['website'] ) ) : '';
	$area    = isset( $_POST['area'] ) ? sanitize_text_field( wp_unslash( $_POST['area'] ) ) : '';
	$extra   = isset( $_POST['extra'] ) ? sanitize_textarea_field( wp_unslash( $_POST['extra'] ) ) : '';

	if ( '' === trim( $name ) ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Enter your full name.', 'smart-leading-net' ),
			),
			400
		);
	}

	if ( ! is_email( $email ) ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Enter an email address in the format name@company.com.', 'smart-leading-net' ),
			),
			400
		);
	}

	if ( '' === trim( $area ) ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Enter your city or service area.', 'smart-leading-net' ),
			),
			400
		);
	}

	if ( function_exists( 'sln_ghl_normalize_website' ) ) {
		$website = sln_ghl_normalize_website( $website );
	}

	$note_parts = array(
		sprintf(
			/* translators: %s: city or service area */
			__( 'Local SEO audit — city/service area: %s', 'smart-leading-net' ),
			$area
		),
	);
	if ( '' !== trim( $extra ) ) {
		$note_parts[] = sprintf(
			/* translators: %s: extra visitor notes */
			__( 'Business Profile / extra: %s', 'smart-leading-net' ),
			$extra
		);
	}

	if ( ! function_exists( 'sln_ghl_upsert_contact' ) ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Something went wrong. Please try again or contact us directly.', 'smart-leading-net' ),
			),
			500
		);
	}

	$result = sln_ghl_upsert_contact(
		array(
			'name'    => $name,
			'email'   => $email,
			'website' => $website,
			'source'  => 'Smart Leading Locale SEO Page',
			'tags'    => array( 'Website Lead', 'Locale SEO Page', 'Local SEO Audit' ),
			'note'    => implode( "\n", $note_parts ),
		)
	);

	if ( is_wp_error( $result ) ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Something went wrong. Please try again or contact us directly.', 'smart-leading-net' ),
			),
			500
		);
	}

	$thank_you = get_page_by_path( 'thank-you' );

	wp_send_json(
		array(
			'success'  => true,
			'message'  => __( 'Thank you — your Local SEO audit request has been sent. A Smart Leading team member will follow up shortly.', 'smart-leading-net' ),
			'redirect' => $thank_you ? get_permalink( $thank_you ) : '',
		)
	);
}

/**
 * Ensure the Locale SEO page exists with the correct template.
 * Never overwrites existing post_content.
 *
 * @param bool $seed_pattern When true and the page content is empty, insert the full-page pattern.
 * @return int Page ID or 0.
 */
function sln_ensure_locel_seo_page( $seed_pattern = true ) {
	$slug  = 'locale-seo';
	$title = __( 'Locale SEO', 'smart-leading-net' );
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

	update_post_meta( $page_id, '_wp_page_template', SLN_LOCEL_SEO_TEMPLATE );

	if ( $seed_pattern ) {
		$existing = get_post_field( 'post_content', $page_id );
		if ( is_string( $existing ) && '' === trim( $existing ) ) {
			$patterns = WP_Block_Patterns_Registry::get_instance();
			if ( $patterns->is_registered( 'sln-locel-seo/full-page' ) ) {
				$pattern = $patterns->get_registered( 'sln-locel-seo/full-page' );
				if ( ! empty( $pattern['content'] ) ) {
					kses_remove_filters();
					wp_update_post(
						array(
							'ID'           => $page_id,
							'post_content' => $pattern['content'],
						)
					);
					kses_init_filters();
				}
			}
		}
	}

	return absint( $page_id );
}

/**
 * One-time ensure for the Locale SEO page (no content overwrite).
 * Runs on front-end and admin so FTP/git deploys create the page without a theme switch.
 *
 * @return bool True when a stored page ID was written this request.
 */
function sln_maybe_ensure_locel_seo_page() {
	$stored = absint( get_option( 'sln_locel_seo_page_ensured' ) );
	if ( $stored && 'page' === get_post_type( $stored ) && 'trash' !== get_post_status( $stored ) ) {
		if ( SLN_LOCEL_SEO_TEMPLATE !== get_page_template_slug( $stored ) ) {
			update_post_meta( $stored, '_wp_page_template', SLN_LOCEL_SEO_TEMPLATE );
		}
		return false;
	}

	$page_id = sln_ensure_locel_seo_page( true );

	if ( $page_id ) {
		update_option( 'sln_locel_seo_page_ensured', (string) $page_id, false );
		return true;
	}

	return false;
}
add_action( 'admin_init', 'sln_maybe_ensure_locel_seo_page', 30 );
add_action( 'after_switch_theme', 'sln_ensure_locel_seo_page' );

/**
 * One-time cleanup of saved Locale SEO markup for design-system fixes.
 */
function sln_locel_seo_migrate_design_fixes() {
	if ( get_option( 'sln_locel_seo_design_fix_20261029' ) ) {
		return;
	}

	$page_id = absint( get_option( 'sln_locel_seo_page_ensured' ) );
	if ( ! $page_id ) {
		$page    = get_page_by_path( 'locale-seo', OBJECT, 'page' );
		$page_id = $page instanceof WP_Post ? (int) $page->ID : 0;
	}
	if ( ! $page_id ) {
		update_option( 'sln_locel_seo_design_fix_20261029', '1', false );
		return;
	}

	$content = (string) get_post_field( 'post_content', $page_id );
	$updated = str_replace( array( ',"highlight":true', '"highlight":true,' ), '', $content );

	if ( $updated !== $content ) {
		kses_remove_filters();
		wp_update_post(
			array(
				'ID'           => $page_id,
				'post_content' => $updated,
			)
		);
		kses_init_filters();
	}

	update_option( 'sln_locel_seo_design_fix_20261029', '1', false );
}
add_action( 'init', 'sln_locel_seo_migrate_design_fixes', 40 );
