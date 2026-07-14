<?php
/**
 * Shared helpers for template-specific page admin meta boxes.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether template-specific meta boxes should register on the current edit screen.
 *
 * Existing pages register boxes only when the saved template matches.
 * Unsaved / new pages register boxes so admin JS can toggle visibility.
 *
 * @param callable $is_target_page Callback receiving WP_Post, returns bool.
 * @return bool
 */
function sln_page_admin_should_register_template_boxes( $is_target_page ) {
	global $post;

	if ( ! is_callable( $is_target_page ) ) {
		return false;
	}

	if ( $post instanceof WP_Post && $post->ID ) {
		return (bool) call_user_func( $is_target_page, $post );
	}

	return true;
}
