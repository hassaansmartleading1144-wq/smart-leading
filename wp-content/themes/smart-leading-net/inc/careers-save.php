<?php
/**
 * Careers page — save post meta from admin metaboxes.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Save Careers page meta.
 *
 * @param int $post_id Post ID.
 */
function sln_careers_save_page_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return;
	}

	if ( ! isset( $_POST['sln_careers_admin_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sln_careers_admin_nonce'] ) ), 'sln_careers_save_meta' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_page', $post_id ) ) {
		return;
	}

	if ( 'page' !== get_post_type( $post_id ) ) {
		return;
	}

	if ( SLN_CAREERS_TEMPLATE !== get_page_template_slug( $post_id ) ) {
		return;
	}

	$culture_image = isset( $_POST['sln_careers_culture_image'] ) ? absint( $_POST['sln_careers_culture_image'] ) : 0;
	update_post_meta( $post_id, SLN_CAREERS_META_CULTURE_IMAGE, $culture_image );

	$open_roles = isset( $_POST['sln_careers_open_roles'] ) ? sanitize_text_field( wp_unslash( $_POST['sln_careers_open_roles'] ) ) : '';
	update_post_meta( $post_id, SLN_CAREERS_META_OPEN_ROLES, $open_roles );

	$hr_email = isset( $_POST['sln_careers_hr_email'] ) ? sanitize_email( wp_unslash( $_POST['sln_careers_hr_email'] ) ) : '';
	update_post_meta( $post_id, SLN_CAREERS_META_HR_EMAIL, $hr_email );

	$life_images = array();

	if ( isset( $_POST['sln_careers_life_images'] ) && is_array( $_POST['sln_careers_life_images'] ) ) {
		foreach ( wp_unslash( $_POST['sln_careers_life_images'] ) as $attachment_id ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$life_images[] = absint( $attachment_id );
		}
	}

	update_post_meta( $post_id, SLN_CAREERS_META_LIFE_IMAGES, $life_images );
}
add_action( 'save_post_page', 'sln_careers_save_page_meta' );
