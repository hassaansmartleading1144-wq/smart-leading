<?php
/**
 * Careers page — Open Positions admin (add / remove jobs only).
 *
 * Page content stays hard-coded; only job posts are editable.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_CAREERS_JOBS_META', '_sln_careers_jobs' );
define( 'SLN_CAREERS_JOBS_NONCE', 'sln_careers_jobs_nonce' );
define( 'SLN_CAREERS_TEMPLATE', 'careers-page-template.php' );

/**
 * Whether the given page uses the Careers template.
 *
 * @param WP_Post|null $post Post object.
 * @return bool
 */
function sln_careers_admin_is_target_page( $post = null ) {
	if ( ! $post instanceof WP_Post ) {
		return false;
	}

	return SLN_CAREERS_TEMPLATE === get_page_template_slug( $post->ID );
}

/**
 * Register Careers jobs meta box.
 */
function sln_careers_register_jobs_meta_box() {
	$screen = get_current_screen();

	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}

	if ( ! sln_page_admin_should_register_template_boxes( 'sln_careers_admin_is_target_page' ) ) {
		return;
	}

	add_meta_box(
		'sln_careers_jobs',
		__( 'Careers — Open Positions (Jobs)', 'smart-leading-net' ),
		'sln_careers_render_jobs_meta_box',
		'page',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'sln_careers_register_jobs_meta_box' );

/**
 * Enqueue Careers jobs admin assets.
 *
 * @param string $hook Current admin hook.
 */
function sln_careers_enqueue_jobs_admin_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	$screen = get_current_screen();

	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}

	$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$post    = $post_id ? get_post( $post_id ) : null;

	wp_enqueue_script( 'jquery-ui-sortable' );

	wp_enqueue_style(
		'sln-careers-jobs-admin',
		SLN_THEME_URI . '/assets/css/careers-jobs-admin.css',
		array(),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-careers-jobs-admin',
		SLN_THEME_URI . '/assets/js/careers-jobs-admin.js',
		array( 'jquery', 'jquery-ui-sortable' ),
		SLN_THEME_VERSION,
		true
	);

	wp_localize_script(
		'sln-careers-jobs-admin',
		'slnCareersJobsAdmin',
		array(
			'template'        => SLN_CAREERS_TEMPLATE,
			'currentTemplate' => ( $post instanceof WP_Post ) ? get_page_template_slug( $post->ID ) : '',
			'isTargetPage'    => ( $post instanceof WP_Post ) ? sln_careers_admin_is_target_page( $post ) : false,
		)
	);
}
add_action( 'admin_enqueue_scripts', 'sln_careers_enqueue_jobs_admin_assets' );

/**
 * Jobs for the admin form (stored meta or defaults).
 *
 * @param int $post_id Page ID.
 * @return array<int, array<string, string>>
 */
function sln_careers_admin_get_jobs( $post_id ) {
	$stored = get_post_meta( $post_id, SLN_CAREERS_JOBS_META, true );

	if ( is_array( $stored ) && ! empty( $stored ) ) {
		return array_values( $stored );
	}

	return sln_get_careers_default_positions();
}

/**
 * Render jobs repeater meta box.
 *
 * @param WP_Post $post Current post.
 */
function sln_careers_render_jobs_meta_box( $post ) {
	$jobs = sln_careers_admin_get_jobs( $post->ID );

	wp_nonce_field( SLN_CAREERS_JOBS_NONCE, SLN_CAREERS_JOBS_NONCE );
	?>
	<p class="description">
		<?php esc_html_e( 'Page content stays fixed. Add, edit, reorder, or remove job posts only. Empty titles are skipped on save.', 'smart-leading-net' ); ?>
	</p>

	<div class="sln-careers-jobs-admin" data-name-prefix="sln_careers_jobs">
		<div class="sln-careers-jobs-admin__list">
			<?php foreach ( $jobs as $index => $job ) : ?>
				<?php sln_careers_admin_render_job_row( (int) $index, $job ); ?>
			<?php endforeach; ?>
		</div>

		<p>
			<button type="button" class="button button-secondary sln-careers-jobs-admin__add">
				<?php esc_html_e( 'Add Job', 'smart-leading-net' ); ?>
			</button>
		</p>

		<template class="sln-careers-jobs-admin__template">
			<?php
			sln_careers_admin_render_job_row(
				'__INDEX__',
				array(
					'title'       => '',
					'department'  => '',
					'location'    => '',
					'type'        => '',
					'experience'  => '',
					'salary'      => '',
					'description' => '',
				)
			);
			?>
		</template>
	</div>
	<?php
}

/**
 * Render one job row.
 *
 * @param int|string           $index Row index.
 * @param array<string, string> $job   Job fields.
 */
function sln_careers_admin_render_job_row( $index, $job ) {
	$job = wp_parse_args(
		$job,
		array(
			'title'       => '',
			'department'  => '',
			'location'    => '',
			'type'        => '',
			'experience'  => '',
			'salary'      => '',
			'description' => '',
		)
	);

	$name = 'sln_careers_jobs[' . $index . ']';
	?>
	<div class="sln-careers-jobs-admin__row">
		<div class="sln-careers-jobs-admin__row-head">
			<span class="sln-careers-jobs-admin__handle dashicons dashicons-menu" aria-hidden="true"></span>
			<strong class="sln-careers-jobs-admin__label">
				<?php echo esc_html( $job['title'] ? $job['title'] : __( 'New Job', 'smart-leading-net' ) ); ?>
			</strong>
			<button type="button" class="button-link-delete sln-careers-jobs-admin__remove">
				<?php esc_html_e( 'Remove', 'smart-leading-net' ); ?>
			</button>
		</div>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><label><?php esc_html_e( 'Job Title', 'smart-leading-net' ); ?></label></th>
				<td><input type="text" class="large-text sln-careers-jobs-admin__title" name="<?php echo esc_attr( $name . '[title]' ); ?>" value="<?php echo esc_attr( $job['title'] ); ?>" /></td>
			</tr>
			<tr>
				<th scope="row"><label><?php esc_html_e( 'Department', 'smart-leading-net' ); ?></label></th>
				<td><input type="text" class="large-text" name="<?php echo esc_attr( $name . '[department]' ); ?>" value="<?php echo esc_attr( $job['department'] ); ?>" /></td>
			</tr>
			<tr>
				<th scope="row"><label><?php esc_html_e( 'Location', 'smart-leading-net' ); ?></label></th>
				<td><input type="text" class="large-text" name="<?php echo esc_attr( $name . '[location]' ); ?>" value="<?php echo esc_attr( $job['location'] ); ?>" placeholder="<?php esc_attr_e( 'On-site', 'smart-leading-net' ); ?>" /></td>
			</tr>
			<tr>
				<th scope="row"><label><?php esc_html_e( 'Type', 'smart-leading-net' ); ?></label></th>
				<td><input type="text" class="large-text" name="<?php echo esc_attr( $name . '[type]' ); ?>" value="<?php echo esc_attr( $job['type'] ); ?>" placeholder="<?php esc_attr_e( 'Full-time', 'smart-leading-net' ); ?>" /></td>
			</tr>
			<tr>
				<th scope="row"><label><?php esc_html_e( 'Experience', 'smart-leading-net' ); ?></label></th>
				<td><input type="text" class="large-text" name="<?php echo esc_attr( $name . '[experience]' ); ?>" value="<?php echo esc_attr( $job['experience'] ); ?>" placeholder="<?php esc_attr_e( '2–4 years', 'smart-leading-net' ); ?>" /></td>
			</tr>
			<tr>
				<th scope="row"><label><?php esc_html_e( 'Salary (optional)', 'smart-leading-net' ); ?></label></th>
				<td><input type="text" class="large-text" name="<?php echo esc_attr( $name . '[salary]' ); ?>" value="<?php echo esc_attr( $job['salary'] ); ?>" /></td>
			</tr>
			<tr>
				<th scope="row"><label><?php esc_html_e( 'Description', 'smart-leading-net' ); ?></label></th>
				<td><textarea class="large-text" rows="3" name="<?php echo esc_attr( $name . '[description]' ); ?>"><?php echo esc_textarea( $job['description'] ); ?></textarea></td>
			</tr>
		</table>
	</div>
	<?php
}

/**
 * Save Careers jobs meta.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 */
function sln_careers_save_jobs_meta( $post_id, $post ) {
	if ( ! $post instanceof WP_Post || 'page' !== $post->post_type ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! isset( $_POST[ SLN_CAREERS_JOBS_NONCE ] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ SLN_CAREERS_JOBS_NONCE ] ) ), SLN_CAREERS_JOBS_NONCE ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_page', $post_id ) ) {
		return;
	}

	$template = isset( $_POST['page_template'] )
		? sanitize_text_field( wp_unslash( $_POST['page_template'] ) )
		: get_page_template_slug( $post_id );

	if ( SLN_CAREERS_TEMPLATE !== $template ) {
		return;
	}

	if ( ! isset( $_POST['sln_careers_jobs'] ) || ! is_array( $_POST['sln_careers_jobs'] ) ) {
		delete_post_meta( $post_id, SLN_CAREERS_JOBS_META );
		return;
	}

	$raw  = wp_unslash( $_POST['sln_careers_jobs'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$jobs = array();

	foreach ( $raw as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}

		$title = isset( $row['title'] ) ? sanitize_text_field( $row['title'] ) : '';

		if ( '' === $title ) {
			continue;
		}

		$jobs[] = array(
			'title'       => $title,
			'department'  => isset( $row['department'] ) ? sanitize_text_field( $row['department'] ) : '',
			'location'    => isset( $row['location'] ) ? sanitize_text_field( $row['location'] ) : '',
			'type'        => isset( $row['type'] ) ? sanitize_text_field( $row['type'] ) : '',
			'experience'  => isset( $row['experience'] ) ? sanitize_text_field( $row['experience'] ) : '',
			'salary'      => isset( $row['salary'] ) ? sanitize_text_field( $row['salary'] ) : '',
			'description' => isset( $row['description'] ) ? sanitize_textarea_field( $row['description'] ) : '',
		);
	}

	update_post_meta( $post_id, SLN_CAREERS_JOBS_META, $jobs );
}
add_action( 'save_post_page', 'sln_careers_save_jobs_meta', 10, 2 );
