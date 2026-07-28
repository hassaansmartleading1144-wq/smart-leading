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
define( 'SLN_CAREERS_FORM_NONCE_ACTION', 'sln_careers_form' );
define( 'SLN_CAREERS_FORM_AJAX_ACTION', 'sln_careers_submit_application' );
define( 'SLN_STARTS_CTA_FORM_NONCE_ACTION', 'sln_starts_cta_form' );
define( 'SLN_STARTS_CTA_FORM_AJAX_ACTION', 'sln_starts_cta_submit_lead' );

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
	add_action( 'wp_ajax_' . SLN_CAREERS_FORM_AJAX_ACTION, 'sln_careers_form_submit_handler' );
	add_action( 'wp_ajax_nopriv_' . SLN_CAREERS_FORM_AJAX_ACTION, 'sln_careers_form_submit_handler' );
	add_action( 'wp_ajax_' . SLN_STARTS_CTA_FORM_AJAX_ACTION, 'sln_starts_cta_form_submit_handler' );
	add_action( 'wp_ajax_nopriv_' . SLN_STARTS_CTA_FORM_AJAX_ACTION, 'sln_starts_cta_form_submit_handler' );
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

/**
 * Allowed MIME types for careers resume uploads.
 *
 * @return array<string, string>
 */
function sln_careers_resume_allowed_mimes() {
	return array(
		'pdf'  => 'application/pdf',
		'doc'  => 'application/msword',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
	);
}

/**
 * Handle Careers page application form submission.
 */
function sln_careers_form_submit_handler() {
	sln_ghl_log( 'Form received: careers application AJAX endpoint reached.' );
	sln_ghl_log_environment_checks( 'careers_form_submit' );

	if ( ! check_ajax_referer( SLN_CAREERS_FORM_NONCE_ACTION, 'nonce', false ) ) {
		sln_ghl_log(
			'Submission failed — invalid nonce',
			array(
				'event' => 'careers_form_failure',
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

	$name      = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email     = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone     = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$position  = isset( $_POST['position'] ) ? sanitize_text_field( wp_unslash( $_POST['position'] ) ) : '';
	$linkedin  = isset( $_POST['linkedin'] ) ? esc_url_raw( wp_unslash( $_POST['linkedin'] ) ) : '';
	$portfolio = isset( $_POST['portfolio'] ) ? esc_url_raw( wp_unslash( $_POST['portfolio'] ) ) : '';
	$message   = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	sln_ghl_log(
		'Data received from careers form',
		array(
			'name'     => $name,
			'email'    => $email,
			'phone'    => $phone,
			'position' => $position,
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

	if ( '' === trim( $position ) ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Please select a position.', 'smart-leading-net' ),
			),
			400
		);
	}

	if ( empty( $_FILES['resume'] ) || empty( $_FILES['resume']['name'] ) ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Please upload your resume.', 'smart-leading-net' ),
			),
			400
		);
	}

	$file = $_FILES['resume']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

	if ( ! empty( $file['error'] ) ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Resume upload failed. Please try again.', 'smart-leading-net' ),
			),
			400
		);
	}

	$max_bytes = 5 * MB_IN_BYTES;

	if ( ! empty( $file['size'] ) && (int) $file['size'] > $max_bytes ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Resume must be 5MB or smaller.', 'smart-leading-net' ),
			),
			400
		);
	}

	$allowed_mimes = sln_careers_resume_allowed_mimes();
	$filetype      = wp_check_filetype( $file['name'], $allowed_mimes );

	if ( empty( $filetype['ext'] ) || empty( $filetype['type'] ) ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Resume must be a PDF or DOC/DOCX file.', 'smart-leading-net' ),
			),
			400
		);
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';

	$mime_filter = static function ( $mimes ) use ( $allowed_mimes ) {
		return array_merge( is_array( $mimes ) ? $mimes : array(), $allowed_mimes );
	};
	add_filter( 'upload_mimes', $mime_filter );

	$upload = wp_handle_upload(
		$file,
		array(
			'test_form' => false,
			'test_type' => true,
			'mimes'     => $allowed_mimes,
		)
	);

	remove_filter( 'upload_mimes', $mime_filter );

	if ( isset( $upload['error'] ) ) {
		sln_ghl_log(
			'Submission failed — resume upload error',
			array(
				'event'  => 'careers_form_failure',
				'reason' => $upload['error'],
			)
		);
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Resume upload failed. Please try again.', 'smart-leading-net' ),
			),
			500
		);
	}

	$resume_url = isset( $upload['url'] ) ? esc_url_raw( $upload['url'] ) : '';
	$phone_e164 = sln_ghl_normalize_international_phone( $phone );

	$note_lines = array(
		__( 'Application submitted via Careers page.', 'smart-leading-net' ),
		/* translators: %s: position title */
		sprintf( __( 'Position: %s', 'smart-leading-net' ), $position ),
	);

	if ( $resume_url ) {
		/* translators: %s: resume URL */
		$note_lines[] = sprintf( __( 'Resume: %s', 'smart-leading-net' ), $resume_url );
	}

	if ( $linkedin ) {
		/* translators: %s: LinkedIn URL */
		$note_lines[] = sprintf( __( 'LinkedIn: %s', 'smart-leading-net' ), $linkedin );
	}

	if ( $portfolio ) {
		/* translators: %s: portfolio URL */
		$note_lines[] = sprintf( __( 'Portfolio: %s', 'smart-leading-net' ), $portfolio );
	}

	if ( '' !== trim( $message ) ) {
		/* translators: %s: applicant message */
		$note_lines[] = sprintf( __( 'Message: %s', 'smart-leading-net' ), $message );
	}

	$result = sln_ghl_upsert_contact(
		array(
			'name'    => $name,
			'email'   => $email,
			'phone'   => $phone_e164,
			'source'  => 'Smart Leading Careers Application',
			'tags'    => array( 'Careers Application', 'Website Lead', $position ),
			'note'    => implode( "\n", $note_lines ),
			'website' => $portfolio ? $portfolio : $linkedin,
		)
	);

	if ( is_wp_error( $result ) ) {
		sln_ghl_log(
			'Submission failed — GHL contact was not created',
			array(
				'event'         => 'careers_form_failure',
				'email'         => $email,
				'error_code'    => $result->get_error_code(),
				'error_message' => $result->get_error_message(),
			)
		);

		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Something went wrong. Please try again or contact HR directly.', 'smart-leading-net' ),
			),
			500
		);
	}

	sln_ghl_log(
		'Submission succeeded — careers application stored in GHL',
		array(
			'event'      => 'careers_form_success',
			'email'      => $email,
			'contact_id' => $result['contact_id'] ?? '',
			'resume_url' => $resume_url,
		)
	);

	wp_send_json(
		array(
			'success'    => true,
			'title'      => __( 'Thank You!', 'smart-leading-net' ),
			'message'    => __( 'Your application has been received.', 'smart-leading-net' ),
			'message_2'  => __( 'Our talent team will review your profile and contact you if there is a fit.', 'smart-leading-net' ),
			'contact_id' => $result['contact_id'] ?? '',
		)
	);
}

/**
 * Handle homepage Starts CTA email form submission.
 */
function sln_starts_cta_form_submit_handler() {
	sln_ghl_log( 'Form received: starts CTA AJAX endpoint reached.' );
	sln_ghl_log_environment_checks( 'starts_cta_form_submit' );

	if ( ! check_ajax_referer( SLN_STARTS_CTA_FORM_NONCE_ACTION, 'nonce', false ) ) {
		sln_ghl_log(
			'Submission failed — invalid nonce',
			array(
				'event' => 'starts_cta_form_failure',
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

	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

	if ( ! is_email( $email ) ) {
		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Please enter a valid email address.', 'smart-leading-net' ),
			),
			400
		);
	}

	$result = sln_ghl_upsert_contact(
		array(
			'name'   => $email,
			'email'  => $email,
			'source' => 'Smart Leading Starts CTA',
			'tags'   => array( 'Website Lead', 'Starts CTA', 'Free Proposal' ),
			'note'   => __( 'Lead submitted via homepage Starts CTA email form.', 'smart-leading-net' ),
		)
	);

	if ( is_wp_error( $result ) ) {
		sln_ghl_log(
			'Submission failed — GHL contact was not created',
			array(
				'event'         => 'starts_cta_form_failure',
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
		'Submission succeeded — starts CTA lead created',
		array(
			'event'      => 'starts_cta_form_success',
			'email'      => $email,
			'contact_id' => $result['contact_id'] ?? '',
		)
	);

	wp_send_json(
		array(
			'success'    => true,
			'title'      => __( 'Thank You!', 'smart-leading-net' ),
			'message'    => __( 'Your request has been submitted successfully.', 'smart-leading-net' ),
			'message_2'  => __( 'A Smart Leading team member will contact you shortly with your free proposal.', 'smart-leading-net' ),
			'contact_id' => $result['contact_id'] ?? '',
		)
	);
}
