<?php
/**
 * Smart Leading Virtual Sales Assistant — OpenAI AJAX handler.
 *
 * Requires in wp-config.php:
 * define( 'SLS_OPENAI_API_KEY', 'your-api-key-here' );
 *
 * GHL credentials: Appearance → GHL Integration (or wp-config constants as fallback).
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLS_AI_CHAT_NONCE_ACTION', 'sls_ai_chat' );
define( 'SLS_AI_CHAT_MAX_MESSAGE_LENGTH', 1000 );
define( 'SLS_AI_CHAT_MAX_HISTORY', 20 );
define( 'SLS_AI_CHAT_OPENAI_MODEL', 'gpt-4o-mini' );

/**
 * Register AJAX handlers (logged-in and guest).
 */
function sls_ai_chat_register_ajax() {
	add_action( 'wp_ajax_sls_ai_chat', 'sls_ai_chat_handle_request' );
	add_action( 'wp_ajax_nopriv_sls_ai_chat', 'sls_ai_chat_handle_request' );
	add_action( 'wp_ajax_sls_ai_chat_save_lead', 'sls_ai_chat_save_lead_handler' );
	add_action( 'wp_ajax_nopriv_sls_ai_chat_save_lead', 'sls_ai_chat_save_lead_handler' );
}
add_action( 'init', 'sls_ai_chat_register_ajax' );

/**
 * System prompt for the Smart Leading virtual sales assistant.
 *
 * @return string
 */
function sls_ai_chat_get_system_prompt() {
	return 'You are Smart Leading\'s virtual sales assistant. You represent Smart Leading.

COMPANY: Smart Leading

SERVICES (ONLY answer questions about these):
- SEO
- Google Ads (PPC)
- Meta Ads (Facebook & Instagram Ads)
- Social Media Marketing
- Lead Generation
- Web Development
- Mobile App Development
- CRM & Marketing Automation
- Conversion Rate Optimization
- Digital Marketing

PRIMARY GOAL: Convert website visitors into qualified leads for Smart Leading.

LEAD CAPTURE:
The website chat widget already collects Name, Email, and Phone before or during the conversation. Do NOT repeat the full welcome lead form if the visitor has already provided contact details. Focus on helpful service answers once they are engaged.

OFF-TOPIC QUESTIONS — DO NOT ANSWER:
Politics, religion, medical advice, legal advice, school homework, general knowledge, entertainment, or anything unrelated to Smart Leading services.

Reply exactly with this format (adapt line breaks only):

"I can only assist with Smart Leading services such as SEO, Google Ads, Meta Ads, Web Development, Lead Generation, and Digital Marketing.

For direct assistance please contact:

Phone: +1 (512) 764-7877
Email: admin@smartleading.net"

UNCERTAIN OR COMPLEX QUESTIONS:
If you cannot answer confidently about Smart Leading services, reply:

"For the most accurate answer, please contact the Smart Leading team directly.

Phone:
+1 (512) 764-7877

Email:
admin@smartleading.net

Website:
https://smartleading.net"

AFTER LEAD CAPTURE:
- Answer professionally and concisely (2–4 short paragraphs max unless more detail is requested).
- Recommend the most relevant Smart Leading service(s).
- Guide toward a free consultation, proposal, website audit, or strategy session when appropriate.

SALES CONVERSION:
When appropriate, offer a Free Consultation, Free Proposal, Free Website Audit, or Marketing Strategy Session.

TONE:
Professional, friendly, helpful, business-focused.

IDENTITY — NEVER SAY:
- "I am ChatGPT", "I am an AI model", "I am OpenAI"
- Never mention OpenAI, ChatGPT, or language models.
- Never use the name "Smart Leading Solutions" — always say "Smart Leading".

Instead say: "I\'m part of the Smart Leading team and I\'m here to help."

PRICING:
Do not invent prices or guarantees. Invite them to request a free proposal or consultation.';
}

/**
 * Handle incoming chat AJAX requests.
 */
function sls_ai_chat_handle_request() {
	check_ajax_referer( SLS_AI_CHAT_NONCE_ACTION, 'nonce' );

	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( '' === $message ) {
		sls_ai_chat_send_error( __( 'Please enter a message.', 'smart-leading-net' ), 400 );
	}

	if ( mb_strlen( $message ) > SLS_AI_CHAT_MAX_MESSAGE_LENGTH ) {
		sls_ai_chat_send_error(
			sprintf(
				/* translators: %d: maximum character count */
				__( 'Message is too long. Please keep it under %d characters.', 'smart-leading-net' ),
				SLS_AI_CHAT_MAX_MESSAGE_LENGTH
			),
			400
		);
	}

	if ( ! defined( 'SLS_OPENAI_API_KEY' ) || '' === SLS_OPENAI_API_KEY ) {
		sls_ai_chat_send_error( __( 'AI chat is not configured. Please contact the site administrator.', 'smart-leading-net' ), 503 );
	}

	$history = sls_ai_chat_parse_history( isset( $_POST['history'] ) ? wp_unslash( $_POST['history'] ) : '' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

	$messages = array(
		array(
			'role'    => 'system',
			'content' => sls_ai_chat_get_system_prompt(),
		),
	);

	foreach ( $history as $entry ) {
		$messages[] = $entry;
	}

	$messages[] = array(
		'role'    => 'user',
		'content' => $message,
	);

	$reply = sls_ai_chat_call_openai( $messages );

	if ( is_wp_error( $reply ) ) {
		sls_ai_chat_send_error( $reply->get_error_message(), 500 );
	}

	wp_send_json(
		array(
			'success' => true,
			'reply'   => $reply,
		)
	);
}

function sls_ai_chat_save_lead_handler() {
	sln_ghl_log( 'Form received: AI chat save lead AJAX endpoint reached.' );
	sln_ghl_log_environment_checks( 'ai_chat_save_lead' );

	check_ajax_referer( SLS_AI_CHAT_NONCE_ACTION, 'nonce' );

	$name  = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';

	$conversation = '';
	if ( isset( $_POST['conversation'] ) ) {
		$conversation = sanitize_textarea_field( wp_unslash( $_POST['conversation'] ) );
	} elseif ( isset( $_POST['transcript'] ) ) {
		$conversation = sanitize_textarea_field( wp_unslash( $_POST['transcript'] ) );
	}

	sln_ghl_log(
		'Data received from AI chat form',
		array(
			'name'  => $name,
			'email' => $email,
			'phone' => $phone,
		)
	);

	if ( '' === $name || ! is_email( $email ) || '' === $phone ) {
		sln_ghl_log( 'Lead validation failed: missing or invalid name, email, or phone.' );

		wp_send_json(
			array(
				'success' => false,
				'message' => __( 'Invalid lead details.', 'smart-leading-net' ),
			),
			400
		);
	}

	$result = sls_send_lead_to_ghl( $name, $email, $phone, $conversation );

	if ( is_wp_error( $result ) ) {
		sln_ghl_log(
			'Lead sync failed',
			array(
				'error_code'    => $result->get_error_code(),
				'error_message' => $result->get_error_message(),
			)
		);

		wp_send_json(
			array(
				'success' => false,
				'message' => $result->get_error_message(),
			),
			500
		);
	}

	sln_ghl_log( 'Contact created/updated successfully for email: ' . $email );

	wp_send_json(
		array(
			'success' => true,
			'message' => __( 'Thank you. Your information has been received successfully.', 'smart-leading-net' ),
		)
	);
}

/**
 * Parse conversation history from JSON POST data.
 *
 * @param string $raw_history JSON-encoded history array.
 * @return array<int, array{role:string, content:string}>
 */
function sls_ai_chat_parse_history( $raw_history ) {
	if ( '' === $raw_history ) {
		return array();
	}

	$decoded = json_decode( $raw_history, true );

	if ( ! is_array( $decoded ) ) {
		return array();
	}

	$parsed = array();

	foreach ( $decoded as $entry ) {
		if ( ! is_array( $entry ) ) {
			continue;
		}

		$role    = isset( $entry['role'] ) ? sanitize_key( $entry['role'] ) : '';
		$content = isset( $entry['content'] ) ? sanitize_textarea_field( $entry['content'] ) : '';

		if ( ! in_array( $role, array( 'user', 'assistant' ), true ) || '' === $content ) {
			continue;
		}

		$parsed[] = array(
			'role'    => $role,
			'content' => $content,
		);

		if ( count( $parsed ) >= SLS_AI_CHAT_MAX_HISTORY ) {
			break;
		}
	}

	return $parsed;
}

/**
 * Call OpenAI Chat Completions API.
 *
 * @param array<int, array{role:string, content:string}> $messages Messages payload.
 * @return string|WP_Error Assistant reply or error.
 */
function sls_ai_chat_call_openai( $messages ) {
	$response = wp_remote_post(
		'https://api.openai.com/v1/chat/completions',
		array(
			'timeout' => 45,
			'headers' => array(
				'Authorization' => 'Bearer ' . SLS_OPENAI_API_KEY,
				'Content-Type'  => 'application/json',
			),
			'body'    => wp_json_encode(
				array(
					'model'       => SLS_AI_CHAT_OPENAI_MODEL,
					'messages'    => $messages,
					'max_tokens'  => 500,
					'temperature' => 0.7,
				)
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		return new WP_Error(
			'sls_ai_chat_network',
			__( 'Unable to reach the assistant. Please try again in a moment.', 'smart-leading-net' )
		);
	}

	$status_code = (int) wp_remote_retrieve_response_code( $response );
	$body        = json_decode( (string) wp_remote_retrieve_body( $response ), true );

	if ( $status_code < 200 || $status_code >= 300 ) {
		$error_message = __( 'The assistant is temporarily unavailable. Please try again.', 'smart-leading-net' );

		if ( is_array( $body ) && ! empty( $body['error']['message'] ) ) {
			// Log for admins; show generic message to visitors.
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
				error_log( 'SLS AI Chat OpenAI error: ' . $body['error']['message'] );
			}
		}

		return new WP_Error( 'sls_ai_chat_api', $error_message );
	}

	$reply = '';

	if ( is_array( $body ) && ! empty( $body['choices'][0]['message']['content'] ) ) {
		$reply = trim( (string) $body['choices'][0]['message']['content'] );
	}

	if ( '' === $reply ) {
		return new WP_Error(
			'sls_ai_chat_empty',
			__( 'The assistant did not return a response. Please try again.', 'smart-leading-net' )
		);
	}

	return $reply;
}

/**
 * Send a JSON error response in the expected format.
 *
 * @param string $message Error message.
 * @param int    $status  HTTP status code hint.
 */
function sls_ai_chat_send_error( $message, $status = 400 ) {
	wp_send_json(
		array(
			'success' => false,
			'reply'   => '',
			'message' => $message,
		),
		$status
	);
}

/**
 * Enqueue AI chat assets on the front page (lazy-loaded by ai-chat-loader.js).
 */
function sls_ai_chat_enqueue_assets() {
	if ( ! is_front_page() ) {
		return;
	}

	wp_register_style(
		'sln-ai-chat',
		SLN_THEME_URI . '/assets/css/ai-chat.css',
		array( 'sln-hero-banner' ),
		SLN_THEME_VERSION
	);

	wp_register_script(
		'sln-ai-chat',
		SLN_THEME_URI . '/assets/js/ai-chat.js',
		array(),
		SLN_THEME_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'sls_ai_chat_enqueue_assets', 20 );

/**
 * Config passed to the lazy AI chat loader.
 *
 * @return array<string, mixed>
 */
function sln_get_ai_chat_loader_config() {
	return array(
		'css'    => SLN_THEME_URI . '/assets/css/ai-chat.css?ver=' . SLN_THEME_VERSION,
		'js'     => SLN_THEME_URI . '/assets/js/ai-chat.js?ver=' . SLN_THEME_VERSION,
		'config' => array(
			'ajaxUrl'            => admin_url( 'admin-ajax.php' ),
			'action'             => 'sls_ai_chat',
			'leadAction'         => 'sls_ai_chat_save_lead',
			'nonce'              => wp_create_nonce( SLS_AI_CHAT_NONCE_ACTION ),
			'historyStorageKey'  => 'smartleading_chat_history',
			'leadStorageKey'     => 'smartleading_lead_data',
			'typingLabel'        => __( 'A Smart Leading Team Member is typing...', 'smart-leading-net' ),
			'errorMessage'       => __( 'Sorry, something went wrong. Please try again.', 'smart-leading-net' ),
			'leadSuccessMessage' => __( 'Thank you. Your information has been received successfully.', 'smart-leading-net' ),
			'welcomeMessage'     => "Welcome to Smart Leading.\n\nPlease share:\n\n• Name\n• Email\n• Phone\n\nThen tell us how we can help your business grow.",
			'reminderOne'        => __( 'May I please have your Name, Email Address, and Phone Number so our team can assist you more effectively?', 'smart-leading-net' ),
			'reminderTwo'        => __( 'To help us provide accurate recommendations, please share your Name, Email Address, and Phone Number.', 'smart-leading-net' ),
			'clearChatLabel'     => __( 'Clear Chat', 'smart-leading-net' ),
			'continueChatLabel'  => __( 'Continue Chat', 'smart-leading-net' ),
			'leadFormIntro'      => __( 'Please share your contact details to continue.', 'smart-leading-net' ),
			'leadNameLabel'      => __( 'Full Name', 'smart-leading-net' ),
			'leadEmailLabel'     => __( 'Email', 'smart-leading-net' ),
			'leadPhoneLabel'     => __( 'Phone', 'smart-leading-net' ),
		),
	);
}
