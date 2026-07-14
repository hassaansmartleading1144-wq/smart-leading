<?php
/**
 * Portfolio page — admin field render helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether the edit screen is for a Portfolio page.
 *
 * @param WP_Post|null $post Post object.
 * @return bool
 */
function sln_portfolio_admin_is_target_page( $post = null ) {
	if ( ! $post instanceof WP_Post ) {
		global $post;
	}

	if ( ! $post instanceof WP_Post || 'page' !== $post->post_type ) {
		return false;
	}

	return SLN_PORTFOLIO_TEMPLATE === get_page_template_slug( $post->ID );
}

/**
 * Get stored section for admin.
 *
 * @param int $post_id Post ID.
 * @return array<string, string>
 */
function sln_portfolio_admin_get_section( $post_id ) {
	$defaults = sln_portfolio_default_section();
	$stored   = sln_portfolio_get_meta_or_default( $post_id, SLN_PORTFOLIO_SECTION_META, $defaults );

	return is_array( $stored ) ? array_merge( $defaults, $stored ) : $defaults;
}

/**
 * Get stored projects for admin.
 *
 * @param int $post_id Post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_portfolio_admin_get_projects( $post_id ) {
	$defaults = sln_portfolio_default_projects();
	$stored   = sln_portfolio_get_meta_or_default( $post_id, SLN_PORTFOLIO_PROJECTS_META, $defaults );

	return is_array( $stored ) && ! empty( $stored ) ? $stored : $defaults;
}
