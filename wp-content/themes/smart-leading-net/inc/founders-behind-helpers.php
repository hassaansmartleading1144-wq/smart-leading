<?php
/**
 * Founders Behind page helpers — enqueue and page seed.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_FOUNDERS_TEMPLATE', 'founders-behind.php' );

/**
 * Whether the current request uses the Founders Behind template.
 *
 * @return bool
 */
function sln_is_founders_behind_page() {
	return is_page_template( SLN_FOUNDERS_TEMPLATE );
}

/**
 * Enqueue frontend assets only on the Founders Behind template.
 */
function sln_enqueue_founders_behind_assets() {
	if ( ! sln_is_founders_behind_page() ) {
		return;
	}

	$css_path = SLN_THEME_DIR . '/assets/css/founders-behind.css';
	wp_enqueue_style(
		'sln-founders-behind',
		SLN_THEME_URI . '/assets/css/founders-behind.css',
		array( 'sln-style', 'sln-buttons' ),
		file_exists( $css_path ) ? (string) filemtime( $css_path ) : SLN_THEME_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_founders_behind_assets' );

/**
 * Load Founders Behind styles in the block editor for this template.
 */
function sln_enqueue_founders_behind_editor_assets() {
	$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( ! $post_id ) {
		return;
	}
	if ( SLN_FOUNDERS_TEMPLATE !== get_page_template_slug( $post_id ) ) {
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
	wp_enqueue_style( 'sln-founders-behind' );
	wp_enqueue_style( 'smartleading-founders-editor-style' );
}
add_action( 'enqueue_block_editor_assets', 'sln_enqueue_founders_behind_editor_assets' );

/**
 * Ensure the Founders Behind page exists. Never overwrites existing post_content.
 *
 * @param bool $seed_pattern Seed empty pages with the full-page pattern.
 * @return int
 */
function sln_ensure_founders_behind_page( $seed_pattern = true ) {
	$slug  = 'founders-behind';
	$title = __( 'The Founders Behind Smart Leading', 'smart-leading-net' );
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

	update_post_meta( $page_id, '_wp_page_template', SLN_FOUNDERS_TEMPLATE );

	if ( $seed_pattern ) {
		$existing = get_post_field( 'post_content', $page_id );
		if ( is_string( $existing ) && '' === trim( $existing ) && function_exists( 'sln_founders_full_page_pattern_content' ) ) {
			kses_remove_filters();
			wp_update_post(
				array(
					'ID'           => $page_id,
					'post_content' => sln_founders_full_page_pattern_content(),
				)
			);
			kses_init_filters();
		}
	}

	return absint( $page_id );
}

/**
 * One-time ensure for the Founders Behind page.
 * Runs on front-end and admin so FTP/git deploys create the page without a theme switch.
 *
 * @return bool True when a stored page ID was written this request.
 */
function sln_maybe_ensure_founders_behind_page() {
	$stored = absint( get_option( 'sln_founders_behind_page_ensured' ) );
	if ( $stored && 'page' === get_post_type( $stored ) && 'trash' !== get_post_status( $stored ) ) {
		if ( SLN_FOUNDERS_TEMPLATE !== get_page_template_slug( $stored ) ) {
			update_post_meta( $stored, '_wp_page_template', SLN_FOUNDERS_TEMPLATE );
		}
		return false;
	}

	$page_id = sln_ensure_founders_behind_page( true );
	if ( $page_id ) {
		update_option( 'sln_founders_behind_page_ensured', (string) $page_id, false );
		return true;
	}

	return false;
}
add_action( 'admin_init', 'sln_maybe_ensure_founders_behind_page', 30 );
add_action( 'after_switch_theme', 'sln_ensure_founders_behind_page' );
