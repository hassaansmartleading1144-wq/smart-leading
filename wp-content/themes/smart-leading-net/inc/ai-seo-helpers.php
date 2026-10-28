<?php
/**
 * AI SEO Services page helpers — enqueue, form AJAX, editor assets.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_AI_SEO_TEMPLATE', 'ai-seo.php' );
define( 'SLN_AI_SEO_FORM_NONCE_ACTION', 'sln_ai_seo_form' );
define( 'SLN_AI_SEO_FORM_AJAX_ACTION', 'sln_ai_seo_submit_lead' );

/**
 * Whether the current request is the AI SEO Services page template.
 *
 * @return bool
 */
function sln_is_ai_seo_page() {
	return is_page_template( SLN_AI_SEO_TEMPLATE );
}

/**
 * Enqueue frontend assets only on the AI SEO template.
 */
function sln_enqueue_ai_seo_assets() {
	if ( ! sln_is_ai_seo_page() ) {
		return;
	}

	wp_enqueue_style(
		'sln-ai-seo',
		SLN_THEME_URI . '/assets/css/ai-seo.css',
		array( 'sln-style' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-ai-seo',
		SLN_THEME_URI . '/assets/js/ai-seo.js',
		array(),
		SLN_THEME_VERSION,
		true
	);

	$thank_you = get_page_by_path( 'thank-you' );
	wp_localize_script(
		'sln-ai-seo',
		'slnAiSeoForm',
		array(
			'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
			'action'         => SLN_AI_SEO_FORM_AJAX_ACTION,
			'nonce'          => wp_create_nonce( SLN_AI_SEO_FORM_NONCE_ACTION ),
			'sendingLabel'   => __( 'Sending…', 'smart-leading-net' ),
			'successMessage' => __( 'Thank you — your AI search enquiry has been sent. A Smart Leading team member will follow up shortly.', 'smart-leading-net' ),
			'errorMessage'   => __( 'Something went wrong. Please try again or contact us directly.', 'smart-leading-net' ),
			'nameRequired'   => __( 'Enter your name.', 'smart-leading-net' ),
			'emailRequired'  => __( 'Enter your business email address.', 'smart-leading-net' ),
			'emailInvalid'   => __( 'Enter an email address in the format name@company.com.', 'smart-leading-net' ),
			'websiteInvalid' => __( 'Enter a website address such as yourcompany.com, or leave this field blank.', 'smart-leading-net' ),
			'goalRequired'   => __( 'Tell us what you would like to understand about AI search.', 'smart-leading-net' ),
			'redirect'       => $thank_you ? get_permalink( $thank_you ) : '',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_ai_seo_assets' );

/**
 * Load AI SEO styles in the block editor when editing a page that uses the template.
 */
function sln_enqueue_ai_seo_editor_assets() {
	$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( ! $post_id ) {
		return;
	}

	$template = get_page_template_slug( $post_id );
	if ( SLN_AI_SEO_TEMPLATE !== $template ) {
		return;
	}

	wp_enqueue_style(
		'sln-ai-seo-editor',
		SLN_THEME_URI . '/assets/css/ai-seo.css',
		array(),
		SLN_THEME_VERSION
	);

	wp_enqueue_style(
		'sln-ai-seo-editor-chrome',
		SLN_THEME_URI . '/assets/css/ai-seo-editor.css',
		array( 'sln-ai-seo-editor' ),
		SLN_THEME_VERSION
	);
}
add_action( 'enqueue_block_editor_assets', 'sln_enqueue_ai_seo_editor_assets' );

/**
 * Register AI SEO form AJAX handlers.
 */
function sln_ai_seo_register_ajax() {
	add_action( 'wp_ajax_' . SLN_AI_SEO_FORM_AJAX_ACTION, 'sln_ai_seo_form_submit_handler' );
	add_action( 'wp_ajax_nopriv_' . SLN_AI_SEO_FORM_AJAX_ACTION, 'sln_ai_seo_form_submit_handler' );
}
add_action( 'init', 'sln_ai_seo_register_ajax' );

/**
 * Handle AI SEO enquiry form submission via existing GHL pipeline.
 */
function sln_ai_seo_form_submit_handler() {
	if ( ! check_ajax_referer( SLN_AI_SEO_FORM_NONCE_ACTION, 'nonce', false ) ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Security check failed. Please refresh the page and try again.', 'smart-leading-net' ),
			),
			403
		);
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$website = isset( $_POST['website'] ) ? sanitize_text_field( wp_unslash( $_POST['website'] ) ) : '';
	$goal    = isset( $_POST['goal'] ) ? sanitize_textarea_field( wp_unslash( $_POST['goal'] ) ) : '';

	if ( '' === trim( $name ) ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Enter your name.', 'smart-leading-net' ),
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

	if ( '' === trim( $goal ) ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Tell us what you would like to understand about AI search.', 'smart-leading-net' ),
			),
			400
		);
	}

	if ( function_exists( 'sln_ghl_normalize_website' ) ) {
		$website = sln_ghl_normalize_website( $website );
	}

	$note = sprintf(
		/* translators: %s: visitor goal text */
		__( 'AI SEO enquiry goal: %s', 'smart-leading-net' ),
		$goal
	);

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
			'source'  => 'Smart Leading AI SEO Page',
			'tags'    => array( 'Website Lead', 'AI SEO Page', 'AI Search Enquiry' ),
			'note'    => $note,
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
			'message'  => __( 'Thank you — your AI search enquiry has been sent. A Smart Leading team member will follow up shortly.', 'smart-leading-net' ),
			'redirect' => $thank_you ? get_permalink( $thank_you ) : '',
		)
	);
}

/**
 * Ensure the AI SEO Services page exists with the correct template.
 * Never overwrites existing post_content.
 *
 * @param bool $seed_pattern When true and the page content is empty, insert the full-page pattern.
 * @return int Page ID or 0.
 */
function sln_ensure_ai_seo_page( $seed_pattern = true ) {
	$slug  = 'ai-seo-services';
	$title = __( 'AI SEO Services', 'smart-leading-net' );
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

	update_post_meta( $page_id, '_wp_page_template', SLN_AI_SEO_TEMPLATE );

	if ( $seed_pattern ) {
		$existing = get_post_field( 'post_content', $page_id );
		if ( is_string( $existing ) && '' === trim( $existing ) ) {
			$patterns = WP_Block_Patterns_Registry::get_instance();
			if ( $patterns->is_registered( 'sln-ai-seo/full-page' ) ) {
				$pattern = $patterns->get_registered( 'sln-ai-seo/full-page' );
				if ( ! empty( $pattern['content'] ) ) {
					// Keep SVG markup from design HTML blocks when seeding as empty page.
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
 * One-time ensure for the AI SEO Services page (no content overwrite).
 */
function sln_maybe_ensure_ai_seo_page() {
	if ( get_option( 'sln_ai_seo_page_ensured' ) ) {
		return;
	}

	$page_id = sln_ensure_ai_seo_page( true );

	if ( $page_id ) {
		update_option( 'sln_ai_seo_page_ensured', (string) $page_id, false );
	}
}
add_action( 'admin_init', 'sln_maybe_ensure_ai_seo_page', 30 );
add_action( 'after_switch_theme', 'sln_ensure_ai_seo_page' );
