<?php
/**
 * Referral Partners page — terms, assets and the application form handler.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_REFERRAL_FORM_NONCE_ACTION', 'sln_referral_form' );
define( 'SLN_REFERRAL_FORM_AJAX_ACTION', 'sln_referral_submit_lead' );

/**
 * Programme terms. Owner confirmed (25 Nov 2026): recurring monthly
 * commission, paid after the referred client's first 30–60 days.
 * The rate and how many months it runs are not set yet — while they are
 * empty, the page says the terms are shared when a partner joins.
 *
 * @return array{rate:string,months:string,payout_days:string}
 */
function sln_referral_terms() {
	return array(
		'rate'        => '', // e.g. '10%'.
		'months'      => '', // e.g. '12' — leave empty for "as long as the client stays".
		'payout_days' => '30–60',
	);
}

/**
 * One-line description of the commission, built only from confirmed terms.
 *
 * @return string
 */
function sln_referral_commission_line() {
	$terms = sln_referral_terms();

	if ( '' !== $terms['rate'] && '' !== $terms['months'] ) {
		/* translators: 1: commission rate, 2: number of months */
		return sprintf( __( '%1$s of the client’s monthly fee, every month for %2$s months.', 'smart-leading-net' ), $terms['rate'], $terms['months'] );
	}

	if ( '' !== $terms['rate'] ) {
		/* translators: %s: commission rate */
		return sprintf( __( '%s of the client’s monthly fee, every month they stay with us.', 'smart-leading-net' ), $terms['rate'] );
	}

	return __( 'A recurring monthly commission on the client’s fee. We share the exact rate when you join.', 'smart-leading-net' );
}

/**
 * Enqueue page assets.
 */
function sln_enqueue_referral_assets() {
	if ( ! is_page_template( 'referral-partners-template.php' ) ) {
		return;
	}

	$css_path = SLN_THEME_DIR . '/assets/css/referral.css';
	$js_path  = SLN_THEME_DIR . '/assets/js/referral.js';

	wp_enqueue_style(
		'intl-tel-input',
		'https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.0/build/css/intlTelInput.min.css',
		array(),
		'23.8.0'
	);

	wp_enqueue_style(
		'sln-referral',
		SLN_THEME_URI . '/assets/css/referral.css',
		array( 'sln-main' ),
		file_exists( $css_path ) ? (string) filemtime( $css_path ) : SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'intl-tel-input',
		'https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.0/build/js/intlTelInput.min.js',
		array(),
		'23.8.0',
		true
	);

	wp_enqueue_script(
		'sln-referral',
		SLN_THEME_URI . '/assets/js/referral.js',
		array( 'intl-tel-input' ),
		file_exists( $js_path ) ? (string) filemtime( $js_path ) : SLN_THEME_VERSION,
		true
	);

	wp_localize_script(
		'sln-referral',
		'slnReferralForm',
		array(
			'ajaxUrl'         => admin_url( 'admin-ajax.php' ),
			'action'          => SLN_REFERRAL_FORM_AJAX_ACTION,
			'nonce'           => wp_create_nonce( SLN_REFERRAL_FORM_NONCE_ACTION ),
			'thankYouUrl'     => sln_get_thank_you_page_url(),
			'submittingLabel' => __( 'Sending...', 'smart-leading-net' ),
			'errorMessage'    => __( 'Sorry, something went wrong. Please try again.', 'smart-leading-net' ),
			'phoneMessage'    => __( 'Please enter a valid phone number.', 'smart-leading-net' ),
			'utilsScript'     => 'https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.0/build/js/utils.js',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_referral_assets' );

/**
 * Allowed answers for "I am a…".
 *
 * @return array<string,string>
 */
function sln_referral_partner_types() {
	return array(
		'freelancer' => __( 'Freelancer or consultant', 'smart-leading-net' ),
		'client'     => __( 'Smart Leading client', 'smart-leading-net' ),
		'other'      => __( 'Someone with a business network', 'smart-leading-net' ),
	);
}

/**
 * AJAX: referral partner application → GoHighLevel contact.
 */
function sln_referral_form_submit_handler() {
	if ( ! check_ajax_referer( SLN_REFERRAL_FORM_NONCE_ACTION, 'nonce', false ) ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Sorry, something went wrong. Please try again.', 'smart-leading-net' ),
			),
			403
		);
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$website = isset( $_POST['website'] ) ? sanitize_text_field( wp_unslash( $_POST['website'] ) ) : '';
	$type    = isset( $_POST['partner_type'] ) ? sanitize_key( wp_unslash( $_POST['partner_type'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
	$types   = sln_referral_partner_types();

	$fail = static function ( $text ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => $text,
			),
			400
		);
	};

	if ( '' === trim( $name ) ) {
		$fail( __( 'Please enter your full name.', 'smart-leading-net' ) );
	}

	if ( ! is_email( $email ) ) {
		$fail( __( 'Please enter a valid email address.', 'smart-leading-net' ) );
	}

	if ( ! sln_ghl_validate_international_phone( $phone ) ) {
		$fail( __( 'Please enter a valid phone number.', 'smart-leading-net' ) );
	}

	if ( ! isset( $types[ $type ] ) ) {
		$fail( __( 'Please tell us who you are.', 'smart-leading-net' ) );
	}

	$note_lines = array(
		__( 'Referral partner application via the Referral Partners page.', 'smart-leading-net' ),
		/* translators: %s: partner type */
		sprintf( __( 'Partner type: %s', 'smart-leading-net' ), $types[ $type ] ),
	);

	if ( '' !== trim( $message ) ) {
		/* translators: %s: applicant message */
		$note_lines[] = sprintf( __( 'Message: %s', 'smart-leading-net' ), $message );
	}

	$result = sln_ghl_upsert_contact(
		array(
			'name'    => $name,
			'email'   => $email,
			'phone'   => sln_ghl_normalize_international_phone( $phone ),
			'website' => sln_ghl_normalize_website( $website ),
			'source'  => 'Smart Leading Referral Partner Form',
			'tags'    => array( 'Referral Partner Application' ),
			'note'    => implode( "\n", $note_lines ),
		)
	);

	if ( is_wp_error( $result ) ) {
		sln_ghl_log(
			'Referral partner submission failed',
			array(
				'event'         => 'referral_form_failure',
				'email'         => $email,
				'error_code'    => $result->get_error_code(),
				'error_message' => $result->get_error_message(),
			)
		);
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Sorry, something went wrong. Please try again.', 'smart-leading-net' ),
			),
			500
		);
	}

	wp_send_json(
		array(
			'success'      => true,
			'redirect_url' => sln_get_thank_you_page_url(),
		)
	);
}
add_action( 'wp_ajax_' . SLN_REFERRAL_FORM_AJAX_ACTION, 'sln_referral_form_submit_handler' );
add_action( 'wp_ajax_nopriv_' . SLN_REFERRAL_FORM_AJAX_ACTION, 'sln_referral_form_submit_handler' );
