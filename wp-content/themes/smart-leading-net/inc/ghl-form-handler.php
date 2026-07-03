<?php
/**
 * Smart Leading lead form — AJAX handlers (Growing section).
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_GROWING_FORM_NONCE_ACTION', 'sln_growing_form' );
define( 'SLN_GROWING_FORM_AJAX_ACTION', 'sln_growing_submit_lead' );
define( 'SLN_CONTACT_FORM_NONCE_ACTION', 'sln_contact_form' );
define( 'SLN_CONTACT_FORM_AJAX_ACTION', 'sln_contact_submit_lead' );
define( 'SLN_SEO_FORM_NONCE_ACTION', 'sln_seo_form' );
define( 'SLN_SEO_FORM_AJAX_ACTION', 'sln_seo_submit_lead' );

/**
 * Register growing form AJAX handlers.
 */
function sln_growing_form_register_ajax() {
	add_action( 'wp_ajax_' . SLN_GROWING_FORM_AJAX_ACTION, 'sln_growing_form_submit_handler' );
	add_action( 'wp_ajax_nopriv_' . SLN_GROWING_FORM_AJAX_ACTION, 'sln_growing_form_submit_handler' );
	add_action( 'wp_ajax_' . SLN_CONTACT_FORM_AJAX_ACTION, 'sln_contact_form_submit_handler' );
	add_action( 'wp_ajax_nopriv_' . SLN_CONTACT_FORM_AJAX_ACTION, 'sln_contact_form_submit_handler' );
	add_action( 'wp_ajax_' . SLN_SEO_FORM_AJAX_ACTION, 'sln_seo_form_submit_handler' );
	add_action( 'wp_ajax_nopriv_' . SLN_SEO_FORM_AJAX_ACTION, 'sln_seo_form_submit_handler' );
}
add_action( 'init', 'sln_growing_form_register_ajax' );

/**
 * Handle Smart Leading growing form submission.
 */
function sln_growing_form_submit_handler() {
	sln_ghl_log( 'Form received: growing section AJAX endpoint reached.' );
	sln_ghl_log_environment_checks( 'growing_form_submit' );

	if ( ! check_ajax_referer( SLN_GROWING_FORM_NONCE_ACTION, 'nonce', false ) ) {
		sln_ghl_log(
			'Submission failed — invalid nonce',
			array(
				'event' => 'growing_form_failure',
			)
		);
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

	sln_ghl_log(
		'Data received from form',
		array(
			'name'    => $name,
			'email'   => $email,
			'website' => $website,
		)
	);

	if ( '' === trim( $name ) ) {
		sln_ghl_log(
			'Submission failed — validation',
			array(
				'event'  => 'growing_form_failure',
				'reason' => 'empty_name',
			)
		);
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Please enter your full name.', 'smart-leading-net' ),
			),
			400
		);
	}

	if ( ! is_email( $email ) ) {
		sln_ghl_log(
			'Submission failed — validation',
			array(
				'event'  => 'growing_form_failure',
				'reason' => 'invalid_email',
			)
		);
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Please enter a valid email address.', 'smart-leading-net' ),
			),
			400
		);
	}

	$website = sln_ghl_normalize_website( $website );

	$result = sln_ghl_upsert_contact(
		array(
			'name'    => $name,
			'email'   => $email,
			'website' => $website,
			'source'  => 'Smart Leading Website Form',
			'tags'    => array( 'Website Lead', 'Growing Form' ),
			'note'    => __( 'Lead submitted via homepage Growing section form.', 'smart-leading-net' ),
		)
	);

	if ( is_wp_error( $result ) ) {
		sln_ghl_log(
			'Submission failed — GHL contact was not created',
			array(
				'event'         => 'growing_form_failure',
				'email'         => $email,
				'error_code'    => $result->get_error_code(),
				'error_message' => $result->get_error_message(),
			)
		);

		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Something went wrong. Please try again or contact us directly.', 'smart-leading-net' ),
			),
			500
		);
	}

	sln_ghl_log(
		'Submission succeeded — GHL contact created',
		array(
			'event'      => 'growing_form_success',
			'email'      => $email,
			'contact_id' => $result['contact_id'] ?? '',
		)
	);

	wp_send_json(
		array(
			'success'    => true,
			'title'      => __( 'Thank You!', 'smart-leading-net' ),
			'message'    => __( 'Your request has been submitted successfully.', 'smart-leading-net' ),
			'message_2'  => __( 'A Smart Leading team member will review your information and contact you shortly to discuss your business goals and growth opportunities.', 'smart-leading-net' ),
			'contact_id' => $result['contact_id'] ?? '',
		)
	);
}

/**
 * Handle Contact Us page form submission.
 */
function sln_contact_form_submit_handler() {
	sln_ghl_log( 'Form received: contact page AJAX endpoint reached.' );
	sln_ghl_log_environment_checks( 'contact_form_submit' );

	if ( ! check_ajax_referer( SLN_CONTACT_FORM_NONCE_ACTION, 'nonce', false ) ) {
		sln_ghl_log(
			'Submission failed — invalid nonce',
			array(
				'event' => 'contact_form_failure',
			)
		);
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Sorry, something went wrong. Please try again.', 'smart-leading-net' ),
			),
			403
		);
	}

	$name         = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email        = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$country_code = isset( $_POST['country_code'] ) ? sanitize_text_field( wp_unslash( $_POST['country_code'] ) ) : '';
	$phone        = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$website      = isset( $_POST['website'] ) ? sanitize_text_field( wp_unslash( $_POST['website'] ) ) : '';
	$message      = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	sln_ghl_log(
		'Data received from contact form',
		array(
			'name'         => $name,
			'email'        => $email,
			'country_code' => $country_code,
			'phone'        => $phone,
			'website'      => $website,
			'message'      => $message,
		)
	);

	if ( '' === trim( $name ) ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Please enter your full name.', 'smart-leading-net' ),
			),
			400
		);
	}

	if ( ! is_email( $email ) ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Please enter a valid email address.', 'smart-leading-net' ),
			),
			400
		);
	}

	if ( ! sln_ghl_validate_international_phone( $phone ) ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Please enter a valid phone number.', 'smart-leading-net' ),
			),
			400
		);
	}

	if ( '' === trim( $message ) ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Please enter a message.', 'smart-leading-net' ),
			),
			400
		);
	}

	$website    = sln_ghl_normalize_website( $website );
	$phone_e164 = sln_ghl_normalize_international_phone( $phone );

	$note_lines = array( __( 'Lead submitted via Contact Us page.', 'smart-leading-net' ) );

	if ( '' !== trim( $message ) ) {
		/* translators: %s: visitor message */
		$note_lines[] = sprintf( __( 'Message: %s', 'smart-leading-net' ), $message );
	}

	$result = sln_ghl_upsert_contact(
		array(
			'name'    => $name,
			'email'   => $email,
			'phone'   => $phone_e164,
			'website' => $website,
			'source'  => 'Smart Leading Contact Form',
			'tags'    => array( 'Website Lead', 'Contact Form' ),
			'note'    => implode( "\n", $note_lines ),
		)
	);

	if ( is_wp_error( $result ) ) {
		sln_ghl_log(
			'Submission failed — GHL contact was not created',
			array(
				'event'         => 'contact_form_failure',
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

	sln_ghl_log(
		'Submission succeeded — GHL contact created',
		array(
			'event'      => 'contact_form_success',
			'email'      => $email,
			'contact_id' => $result['contact_id'] ?? '',
		)
	);

	wp_send_json(
		array(
			'success'      => true,
			'redirect_url' => sln_get_thank_you_page_url(),
			'contact_id'   => $result['contact_id'] ?? '',
		)
	);
}

/**
 * Handle SEO Services page form submission.
 */
function sln_seo_form_submit_handler() {
	sln_ghl_log( 'Form received: SEO page AJAX endpoint reached.' );
	sln_ghl_log_environment_checks( 'seo_form_submit' );

	if ( ! check_ajax_referer( SLN_SEO_FORM_NONCE_ACTION, 'nonce', false ) ) {
		sln_ghl_log(
			'Submission failed — invalid nonce',
			array(
				'event' => 'seo_form_failure',
			)
		);
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

	sln_ghl_log(
		'Data received from SEO form',
		array(
			'name'    => $name,
			'email'   => $email,
			'website' => $website,
		)
	);

	if ( '' === trim( $name ) ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Please enter your name.', 'smart-leading-net' ),
			),
			400
		);
	}

	if ( ! is_email( $email ) ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Please enter a valid email address.', 'smart-leading-net' ),
			),
			400
		);
	}

	$website = sln_ghl_normalize_website( $website );

	$result = sln_ghl_upsert_contact(
		array(
			'name'    => $name,
			'email'   => $email,
			'website' => $website,
			'source'  => 'Smart Leading SEO Page',
			'tags'    => array( 'Website Lead', 'SEO Page', 'SEO Proposal' ),
			'note'    => __( 'Lead submitted via SEO Services page proposal form.', 'smart-leading-net' ),
		)
	);

	if ( is_wp_error( $result ) ) {
		sln_ghl_log(
			'Submission failed — GHL contact was not created',
			array(
				'event'         => 'seo_form_failure',
				'email'         => $email,
				'error_code'    => $result->get_error_code(),
				'error_message' => $result->get_error_message(),
			)
		);

		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Something went wrong. Please try again or contact us directly.', 'smart-leading-net' ),
			),
			500
		);
	}

	sln_ghl_log(
		'Submission succeeded — GHL contact created',
		array(
			'event'      => 'seo_form_success',
			'email'      => $email,
			'contact_id' => $result['contact_id'] ?? '',
		)
	);

	wp_send_json(
		array(
			'success'   => true,
			'title'     => __( 'Thank You!', 'smart-leading-net' ),
			'message'   => __( 'Your SEO proposal request has been submitted successfully.', 'smart-leading-net' ),
			'message_2' => __( 'A Smart Leading strategist will review your site and contact you within one business day.', 'smart-leading-net' ),
			'contact_id' => $result['contact_id'] ?? '',
		)
	);
}
