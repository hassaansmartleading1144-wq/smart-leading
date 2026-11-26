<?php
/**
 * One-time page setup for the homepage rebuild (26 Nov 2026), no admin menu.
 *
 * The first time an administrator opens WP-admin after this deploy:
 * - "Referral Partners" (/referral-partners/) is created and published,
 *   because the header and footer link to it.
 * - "Previous Homepage" (/previous-homepage/) is created as a DRAFT with the
 *   old homepage sections, so the old design stays available for reference.
 * Existing pages with those slugs are never changed.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Create a page once if no page uses the slug yet.
 *
 * @param string $slug     Page slug.
 * @param string $title    Page title.
 * @param string $template Page template file.
 * @param string $status   Post status.
 */
function sln_home_seed_page( $slug, $title, $template, $status ) {
	$existing = get_page_by_path( $slug, OBJECT, 'page' );

	if ( $existing instanceof WP_Post ) {
		return;
	}

	$page_id = wp_insert_post(
		array(
			'post_type'   => 'page',
			'post_title'  => $title,
			'post_name'   => $slug,
			'post_status' => $status,
		),
		true
	);

	if ( ! is_wp_error( $page_id ) ) {
		update_post_meta( $page_id, '_wp_page_template', $template );
	}
}

/**
 * Run the seed once for an administrator in WP-admin.
 */
function sln_home_seed_pages() {
	if ( wp_doing_ajax() || ! current_user_can( 'publish_pages' ) || get_option( 'sln_home_pages_seeded_1' ) ) {
		return;
	}

	sln_home_seed_page( 'referral-partners', 'Referral Partners', 'referral-partners-template.php', 'publish' );
	sln_home_seed_page( 'previous-homepage', 'Previous Homepage', 'previous-homepage-template.php', 'draft' );

	update_option( 'sln_home_pages_seeded_1', gmdate( 'c' ), false );
}
add_action( 'admin_init', 'sln_home_seed_pages' );
