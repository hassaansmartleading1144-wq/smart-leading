<?php
/**
 * GoHighLevel API client and logging.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_GHL_API_BASE', 'https://services.leadconnectorhq.com' );
define( 'SLN_GHL_API_VERSION', '2021-07-28' );

/**
 * Absolute path to the GHL debug log file.
 *
 * @return string
 */
function sln_ghl_get_log_file_path() {
	$upload_dir = wp_upload_dir();

	if ( ! empty( $upload_dir['error'] ) ) {
		return WP_CONTENT_DIR . '/uploads/ghl-debug.log';
	}

	return trailingslashit( $upload_dir['basedir'] ) . 'ghl-debug.log';
}

/**
 * Write a line to wp-content/uploads/ghl-debug.log and WP debug.log.
 *
 * @param string               $message Log message.
 * @param array<string, mixed> $context Optional structured context.
 */
function sln_ghl_log( $message, $context = array() ) {
	$timestamp = gmdate( 'Y-m-d H:i:s' ) . ' UTC';
	$line      = '[' . $timestamp . '] ' . $message;

	if ( ! empty( $context ) ) {
		// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
		$line .= ' | ' . wp_json_encode( $context );
	}

	$log_file = sln_ghl_get_log_file_path();
	$dir      = dirname( $log_file );

	if ( ! is_dir( $dir ) ) {
		wp_mkdir_p( $dir );
	}

	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
	@file_put_contents( $log_file, $line . PHP_EOL, FILE_APPEND | LOCK_EX );

	// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	error_log( '[SLN GHL] ' . $message );
}

/**
 * Log server environment details relevant to outbound API calls.
 *
 * @param string $trigger What triggered the check.
 */
function sln_ghl_log_environment_checks( $trigger = 'request' ) {
	sln_ghl_log(
		'Environment check (' . $trigger . ')',
		array(
			'curl_available'   => function_exists( 'curl_init' ),
			'wp_remote_post'   => function_exists( 'wp_remote_post' ),
			'ssl_verify'       => true,
			'php_version'      => PHP_VERSION,
			'site_url'         => home_url( '/' ),
			'has_token'        => '' !== sln_ghl_get_private_token(),
			'location_id'      => sln_ghl_get_location_id(),
			'token_source'     => sln_ghl_get_token_source(),
		)
	);
}

/**
 * Describe where the bearer token is loaded from.
 *
 * @return string
 */
function sln_ghl_get_token_source() {
	$settings = sln_ghl_get_settings();

	if ( '' !== trim( (string) ( $settings['private_token'] ?? '' ) ) ) {
		return 'theme_settings';
	}

	if ( defined( 'SLS_GHL_PRIVATE_TOKEN' ) && '' !== SLS_GHL_PRIVATE_TOKEN ) {
		return 'wp_config_constant';
	}

	return 'missing';
}

/**
 * Split a full name into first and last name.
 *
 * @param string $full_name Full name.
 * @return array{0:string,1:string}
 */
function sln_ghl_split_name( $full_name ) {
	$full_name = trim( (string) $full_name );
	$parts     = preg_split( '/\s+/', $full_name, 2 );

	$first_name = isset( $parts[0] ) ? sanitize_text_field( $parts[0] ) : '';
	$last_name  = isset( $parts[1] ) ? sanitize_text_field( $parts[1] ) : '';

	return array( $first_name, $last_name );
}

/**
 * Normalize a phone number for GHL.
 *
 * @param string $phone Raw phone.
 * @return string
 */
function sln_ghl_normalize_phone( $phone ) {
	$digits = preg_replace( '/\D+/', '', (string) $phone );

	if ( 10 === strlen( $digits ) ) {
		return '+1' . $digits;
	}

	if ( 11 === strlen( $digits ) && '1' === $digits[0] ) {
		return '+' . $digits;
	}

	if ( strlen( $digits ) > 10 ) {
		return '+' . $digits;
	}

	return sanitize_text_field( $phone );
}

/**
 * Normalize a website URL.
 *
 * @param string $website Raw website input.
 * @return string
 */
function sln_ghl_normalize_website( $website ) {
	$website = trim( (string) $website );

	if ( '' === $website ) {
		return '';
	}

	if ( ! preg_match( '#^https?://#i', $website ) ) {
		$website = 'https://' . $website;
	}

	return esc_url_raw( $website );
}

/**
 * Allowed dial codes for contact phone validation.
 *
 * @return array<int, string>
 */
function sln_ghl_get_allowed_country_codes() {
	return array( '+1', '+44', '+61', '+47', '+358' );
}

/**
 * Validate contact form phone number (national digits + allowed country code).
 *
 * @param string $country_code Dial code e.g. +1.
 * @param string $phone        National number digits.
 * @return bool
 */
function sln_ghl_validate_contact_phone( $country_code, $phone ) {
	$country_code = sanitize_text_field( (string) $country_code );

	if ( ! in_array( $country_code, sln_ghl_get_allowed_country_codes(), true ) ) {
		return false;
	}

	$digits = preg_replace( '/\D+/', '', (string) $phone );

	return strlen( $digits ) >= 7 && strlen( $digits ) <= 14;
}

/**
 * Build E.164 phone from country code and national number.
 *
 * @param string $country_code Dial code.
 * @param string $phone        National number.
 * @return string
 */
function sln_ghl_build_phone_e164( $country_code, $phone ) {
	$code_digits = preg_replace( '/\D+/', '', (string) $country_code );
	$national    = preg_replace( '/\D+/', '', (string) $phone );

	return '+' . $code_digits . $national;
}

/**
 * Validate a full international phone number (from intl-tel-input).
 *
 * @param string $phone Full number, optionally E.164 with leading +.
 * @return bool
 */
function sln_ghl_validate_international_phone( $phone ) {
	$digits = preg_replace( '/\D+/', '', (string) $phone );

	return strlen( $digits ) >= 7 && strlen( $digits ) <= 15;
}

/**
 * Normalize a full international phone number to E.164.
 *
 * @param string $phone Full number, optionally with leading +.
 * @return string
 */
function sln_ghl_normalize_international_phone( $phone ) {
	$phone  = trim( (string) $phone );
	$digits = preg_replace( '/\D+/', '', $phone );

	if ( '' === $digits ) {
		return '';
	}

	return '+' . $digits;
}

/**
 * Validate GHL credentials are configured.
 *
 * @return true|WP_Error
 */
function sln_ghl_validate_config() {
	if ( '' === sln_ghl_get_private_token() ) {
		sln_ghl_log( 'Configuration error: Bearer token is missing.' );
		return new WP_Error(
			'sln_ghl_config',
			__( 'GoHighLevel API token is not configured. Add it under Appearance → GHL Integration.', 'smart-leading-net' )
		);
	}

	if ( '' === sln_ghl_get_location_id() ) {
		sln_ghl_log( 'Configuration error: Location ID is missing.' );
		return new WP_Error(
			'sln_ghl_config',
			__( 'GoHighLevel Location ID is not configured.', 'smart-leading-net' )
		);
	}

	return true;
}

/**
 * Create or update a contact in GoHighLevel.
 *
 * @param array<string, mixed> $args Contact args: name, email, phone, website, source, tags, note.
 * @return array{contact_id:string}|WP_Error
 */
function sln_ghl_upsert_contact( $args ) {
	sln_ghl_log( 'GHL upsert requested', array( 'args' => sln_ghl_redact_sensitive( $args ) ) );

	$config = sln_ghl_validate_config();

	if ( is_wp_error( $config ) ) {
		return $config;
	}

	$name    = isset( $args['name'] ) ? sanitize_text_field( $args['name'] ) : '';
	$email   = isset( $args['email'] ) ? sanitize_email( $args['email'] ) : '';
	$phone   = isset( $args['phone'] ) ? sanitize_text_field( $args['phone'] ) : '';
	$website = isset( $args['website'] ) ? sln_ghl_normalize_website( $args['website'] ) : '';
	$source  = isset( $args['source'] ) ? sanitize_text_field( $args['source'] ) : 'Smart Leading Website';
	$tags    = isset( $args['tags'] ) && is_array( $args['tags'] ) ? array_map( 'sanitize_text_field', $args['tags'] ) : array();
	$note    = isset( $args['note'] ) ? sanitize_textarea_field( $args['note'] ) : '';

	if ( '' === $name || ! is_email( $email ) ) {
		sln_ghl_log( 'Validation failed: invalid name or email.', array( 'name' => $name, 'email' => $email ) );
		return new WP_Error(
			'sln_ghl_validation',
			__( 'Please enter a valid full name and email address.', 'smart-leading-net' )
		);
	}

	list( $first_name, $last_name ) = sln_ghl_split_name( $name );

	$payload = array(
		'locationId' => sln_ghl_get_location_id(),
		'firstName'  => $first_name,
		'email'      => $email,
		'source'     => $source,
	);

	if ( '' !== $last_name ) {
		$payload['lastName'] = $last_name;
	}

	if ( '' !== $phone ) {
		$payload['phone'] = sln_ghl_normalize_phone( $phone );
	}

	if ( '' !== $website ) {
		$payload['website'] = $website;
	}

	if ( ! empty( $tags ) ) {
		$payload['tags'] = array_values( $tags );
	}

	sln_ghl_log( 'API request payload prepared for POST /contacts/upsert', array( 'payload' => $payload ) );

	$response = sln_ghl_api_request( 'POST', '/contacts/upsert', $payload );

	if ( is_wp_error( $response ) ) {
		return $response;
	}

	$contact_id = sln_ghl_extract_contact_id( $response );

	if ( '' === $contact_id ) {
		sln_ghl_log( 'Upsert succeeded but contact ID missing in response.', array( 'response' => $response ) );
		return new WP_Error(
			'sln_ghl_contact',
			__( 'GoHighLevel accepted the request but did not return a contact ID.', 'smart-leading-net' )
		);
	}

	sln_ghl_log( 'Contact upsert successful', array( 'contact_id' => $contact_id ) );

	$note_lines = array();

	if ( '' !== trim( $note ) ) {
		$note_lines[] = trim( $note );
	}

	if ( '' !== $website ) {
		$note_lines[] = 'Website: ' . $website;
	}

	if ( ! empty( $note_lines ) ) {
		$note_body = implode( "\n\n", $note_lines );
		$note_result = sln_ghl_add_contact_note( $contact_id, $note_body );

		if ( is_wp_error( $note_result ) ) {
			sln_ghl_log( 'Note creation failed', array( 'error' => $note_result->get_error_message() ) );
			return $note_result;
		}

		sln_ghl_log( 'Contact note saved', array( 'contact_id' => $contact_id ) );
	}

	return array(
		'contact_id' => $contact_id,
	);
}

/**
 * Perform an authenticated GHL API request.
 *
 * @param string               $method HTTP method.
 * @param string               $path   API path beginning with /.
 * @param array<string, mixed> $body   Request body.
 * @return array<string, mixed>|WP_Error
 */
function sln_ghl_api_request( $method, $path, $body = array() ) {
	$token = sln_ghl_get_private_token();
	$url   = SLN_GHL_API_BASE . $path;

	$args = array(
		'method'    => strtoupper( $method ),
		'timeout'   => 30,
		'sslverify' => true,
		'headers'   => array(
			'Authorization' => 'Bearer ' . $token,
			'Version'       => SLN_GHL_API_VERSION,
			'Content-Type'  => 'application/json',
			'Accept'        => 'application/json',
		),
	);

	if ( ! empty( $body ) ) {
		$args['body'] = wp_json_encode( $body );
	}

	sln_ghl_log(
		'Sending GHL API request',
		array(
			'method' => $args['method'],
			'url'    => $url,
			'body'   => $body,
		)
	);

	$response = wp_remote_request( $url, $args );

	if ( is_wp_error( $response ) ) {
		sln_ghl_log(
			'wp_remote_request() failed',
			array(
				'error_code'    => $response->get_error_code(),
				'error_message' => $response->get_error_message(),
			)
		);
		return new WP_Error(
			'sln_ghl_network',
			sprintf(
				/* translators: %s: network error message */
				__( 'Could not reach GoHighLevel: %s', 'smart-leading-net' ),
				$response->get_error_message()
			)
		);
	}

	$status_code = (int) wp_remote_retrieve_response_code( $response );
	$raw_body    = (string) wp_remote_retrieve_body( $response );
	$data        = json_decode( $raw_body, true );

	sln_ghl_log(
		'GHL API response received',
		array(
			'status_code' => $status_code,
			'body'        => is_array( $data ) ? $data : $raw_body,
		)
	);

	if ( $status_code < 200 || $status_code >= 300 ) {
		$message = sln_ghl_extract_error_message( $data, $raw_body, $status_code );
		sln_ghl_log( 'GHL API error', array( 'message' => $message ) );
		return new WP_Error( 'sln_ghl_api', $message );
	}

	return is_array( $data ) ? $data : array();
}

/**
 * Extract a human-readable error from a GHL response.
 *
 * @param mixed  $data        Decoded JSON.
 * @param string $raw_body    Raw response body.
 * @param int    $status_code HTTP status.
 * @return string
 */
function sln_ghl_extract_error_message( $data, $raw_body, $status_code ) {
	if ( is_array( $data ) ) {
		if ( ! empty( $data['message'] ) ) {
			return (string) $data['message'];
		}

		if ( ! empty( $data['error'] ) && is_string( $data['error'] ) ) {
			return (string) $data['error'];
		}

		if ( ! empty( $data['msg'] ) ) {
			return (string) $data['msg'];
		}
	}

	if ( '' !== trim( $raw_body ) ) {
		return sprintf(
			/* translators: 1: HTTP status code, 2: response body */
			__( 'GoHighLevel API error (HTTP %1$d): %2$s', 'smart-leading-net' ),
			$status_code,
			wp_strip_all_tags( $raw_body )
		);
	}

	return sprintf(
		/* translators: %d: HTTP status code */
		__( 'GoHighLevel API request failed with HTTP %d.', 'smart-leading-net' ),
		$status_code
	);
}

/**
 * Extract contact ID from a GHL upsert/create response.
 *
 * @param array<string, mixed> $response API response.
 * @return string
 */
function sln_ghl_extract_contact_id( $response ) {
	if ( ! empty( $response['contact']['id'] ) ) {
		return (string) $response['contact']['id'];
	}

	if ( ! empty( $response['id'] ) ) {
		return (string) $response['id'];
	}

	return '';
}

/**
 * Add a note to a GHL contact.
 *
 * @param string $contact_id Contact ID.
 * @param string $body       Note body.
 * @return true|WP_Error
 */
function sln_ghl_add_contact_note( $contact_id, $body ) {
	$path = '/contacts/' . rawurlencode( $contact_id ) . '/notes';

	$response = sln_ghl_api_request(
		'POST',
		$path,
		array(
			'body' => $body,
		)
	);

	if ( is_wp_error( $response ) ) {
		return $response;
	}

	return true;
}

/**
 * Redact sensitive values before logging.
 *
 * @param array<string, mixed> $args Args array.
 * @return array<string, mixed>
 */
function sln_ghl_redact_sensitive( $args ) {
	$safe = $args;

	if ( isset( $safe['private_token'] ) ) {
		$safe['private_token'] = '[redacted]';
	}

	return $safe;
}

/**
 * Backward-compatible alias used by AI chat integration.
 *
 * @param string $message Log message.
 */
function sls_ghl_log( $message ) {
	sln_ghl_log( $message );
}

/**
 * Backward-compatible AI chat lead sync.
 *
 * @param string $name         Full name.
 * @param string $email        Email address.
 * @param string $phone        Phone number.
 * @param string $conversation Optional chat transcript.
 * @return true|WP_Error
 */
function sls_send_lead_to_ghl( $name, $email, $phone, $conversation = '' ) {
	$note = '';

	if ( '' !== trim( $conversation ) ) {
		$note = "Smart Leading AI Chat Conversation\n\n" . trim( $conversation );
	}

	$result = sln_ghl_upsert_contact(
		array(
			'name'   => $name,
			'email'  => $email,
			'phone'  => $phone,
			'source' => 'Smart Leading AI Chat',
			'tags'   => array( 'AI Chat Lead' ),
			'note'   => $note,
		)
	);

	if ( is_wp_error( $result ) ) {
		return $result;
	}

	return true;
}
