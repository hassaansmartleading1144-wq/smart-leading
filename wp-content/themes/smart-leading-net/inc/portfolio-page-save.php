<?php
/**
 * Portfolio page — save handlers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register save hook.
 */
function sln_portfolio_register_save_hooks() {
	add_action( 'save_post_page', 'sln_portfolio_save_meta', 10, 2 );
}
add_action( 'init', 'sln_portfolio_register_save_hooks', 20 );

/**
 * Output save nonce after title.
 *
 * @param WP_Post $post Current post.
 */
function sln_portfolio_output_save_nonce( $post ) {
	if ( ! $post instanceof WP_Post || 'page' !== $post->post_type ) {
		return;
	}

	if ( ! sln_portfolio_admin_is_target_page( $post ) ) {
		return;
	}

	wp_nonce_field( 'sln_portfolio_save_meta', 'sln_portfolio_master_nonce', false );
}
add_action( 'edit_form_after_title', 'sln_portfolio_output_save_nonce' );

/**
 * Whether portfolio meta should save.
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function sln_portfolio_should_save_meta( $post_id ) {
	$post_id = absint( $post_id );

	if ( ! $post_id || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
		return false;
	}

	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return false;
	}

	if ( 'page' !== get_post_type( $post_id ) ) {
		return false;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return false;
	}

	if ( ! isset( $_POST['sln_portfolio_master_nonce'] ) ) {
		return false;
	}

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sln_portfolio_master_nonce'] ) ), 'sln_portfolio_save_meta' ) ) {
		return false;
	}

	$template = isset( $_POST['page_template'] )
		? sanitize_text_field( wp_unslash( $_POST['page_template'] ) )
		: get_page_template_slug( $post_id );

	return SLN_PORTFOLIO_TEMPLATE === $template;
}

/**
 * Sanitize section fields.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, string>
 */
function sln_portfolio_sanitize_section( $raw ) {
	$description = '';

	if ( function_exists( 'sln_growth_page_sanitize_wysiwyg_content' ) ) {
		$description = sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' );
	} else {
		$description = wp_kses_post( wp_unslash( $raw['description'] ?? '' ) );
	}

	return array(
		'small_heading' => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'  => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'description'   => $description,
	);
}

/**
 * Sanitize one project row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_portfolio_sanitize_project_row( $raw ) {
	$url = esc_url_raw( $raw['url'] ?? '' );
	$description = '';

	if ( function_exists( 'sln_growth_page_sanitize_wysiwyg_content' ) ) {
		$description = sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' );
	} else {
		$description = wp_kses_post( wp_unslash( $raw['description'] ?? '' ) );
	}

	return array(
		'image_id'    => sln_sanitize_media_attachment_id( $raw['image_id'] ?? 0 ),
		'image'       => sanitize_file_name( $raw['image'] ?? '' ),
		'title'       => sanitize_text_field( $raw['title'] ?? '' ),
		'url'         => '' !== $url ? $url : '#',
		'new_tab'     => ! empty( $raw['new_tab'] ),
		'active'      => ! empty( $raw['active'] ),
		'alt'         => sanitize_text_field( $raw['alt'] ?? '' ),
		'description' => $description,
	);
}

/**
 * Save portfolio page meta.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 */
function sln_portfolio_save_meta( $post_id, $post ) {
	unset( $post );

	if ( ! sln_portfolio_should_save_meta( $post_id ) ) {
		return;
	}

	if ( isset( $_POST['sln_portfolio_section'] ) && is_array( $_POST['sln_portfolio_section'] ) ) {
		update_post_meta(
			$post_id,
			SLN_PORTFOLIO_SECTION_META,
			sln_portfolio_sanitize_section( wp_unslash( $_POST['sln_portfolio_section'] ) )
		);
	}

	if ( isset( $_POST['sln_portfolio_projects'] ) && is_array( $_POST['sln_portfolio_projects'] ) ) {
		$projects = array();

		foreach ( wp_unslash( $_POST['sln_portfolio_projects'] ) as $raw_row ) {
			if ( ! is_array( $raw_row ) ) {
				continue;
			}

			$row = sln_portfolio_sanitize_project_row( $raw_row );

			if ( '' === $row['title'] && ! $row['image_id'] ) {
				continue;
			}

			$projects[] = $row;
		}

		update_post_meta( $post_id, SLN_PORTFOLIO_PROJECTS_META, $projects );
	}
}
