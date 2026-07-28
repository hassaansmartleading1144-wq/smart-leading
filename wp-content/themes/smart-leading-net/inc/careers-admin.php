<?php
/**
 * Careers page — admin meta boxes for editable media and settings.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether the edit screen is a Careers template page.
 *
 * @param WP_Post|null $post Post object.
 * @return bool
 */
function sln_careers_admin_is_target_page( $post = null ) {
	if ( ! $post instanceof WP_Post ) {
		global $post;
	}

	if ( ! $post instanceof WP_Post || 'page' !== $post->post_type ) {
		return false;
	}

	return SLN_CAREERS_TEMPLATE === get_page_template_slug( $post->ID );
}

/**
 * Register Careers meta boxes.
 */
function sln_careers_register_meta_boxes() {
	$screen = get_current_screen();

	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}

	if ( ! sln_page_admin_should_register_template_boxes( 'sln_careers_admin_is_target_page' ) ) {
		return;
	}

	add_meta_box(
		'sln_careers_settings',
		__( 'Careers Page Settings', 'smart-leading-net' ),
		'sln_careers_render_settings_metabox',
		'page',
		'normal',
		'default'
	);

	add_meta_box(
		'sln_careers_media',
		__( 'Careers Media', 'smart-leading-net' ),
		'sln_careers_render_media_metabox',
		'page',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'sln_careers_register_meta_boxes' );

/**
 * Enqueue Careers admin assets.
 *
 * @param string $hook Current admin hook.
 */
function sln_careers_enqueue_admin_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	$screen = get_current_screen();

	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}

	$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$post    = $post_id ? get_post( $post_id ) : null;
	$is_target = ( $post instanceof WP_Post ) ? sln_careers_admin_is_target_page( $post ) : false;

	if ( ! $is_target && $post instanceof WP_Post ) {
		return;
	}

	wp_enqueue_media();

	wp_enqueue_style(
		'sln-our-services-admin',
		SLN_THEME_URI . '/assets/css/our-services-admin.css',
		array(),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-careers-admin',
		SLN_THEME_URI . '/assets/js/careers-admin.js',
		array( 'jquery' ),
		SLN_THEME_VERSION,
		true
	);

	wp_localize_script(
		'sln-careers-admin',
		'slnCareersAdmin',
		array(
			'template'        => SLN_CAREERS_TEMPLATE,
			'currentTemplate' => ( $post instanceof WP_Post ) ? get_page_template_slug( $post->ID ) : '',
			'isTargetPage'    => $is_target,
		)
	);
}
add_action( 'admin_enqueue_scripts', 'sln_careers_enqueue_admin_assets' );

/**
 * Render settings metabox.
 *
 * @param WP_Post $post Post object.
 */
function sln_careers_render_settings_metabox( $post ) {
	wp_nonce_field( 'sln_careers_save_meta', 'sln_careers_admin_nonce' );

	$open_roles = get_post_meta( $post->ID, SLN_CAREERS_META_OPEN_ROLES, true );
	$hr_email   = get_post_meta( $post->ID, SLN_CAREERS_META_HR_EMAIL, true );

	if ( ! is_string( $open_roles ) ) {
		$open_roles = '';
	}

	if ( ! is_email( (string) $hr_email ) ) {
		$hr_email = 'hr@smartleading.net';
	}
	?>
	<table class="form-table" role="presentation">
		<tr>
			<th scope="row"><label for="sln_careers_open_roles"><?php esc_html_e( 'Open Roles Metric', 'smart-leading-net' ); ?></label></th>
			<td>
				<input id="sln_careers_open_roles" type="text" class="regular-text" name="sln_careers_open_roles" value="<?php echo esc_attr( $open_roles ); ?>" placeholder="<?php echo esc_attr( (string) count( sln_get_careers_positions_defaults() ) ); ?>" />
				<p class="description"><?php esc_html_e( 'Shown in the hero card. Leave blank to use the number of listed positions.', 'smart-leading-net' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="sln_careers_hr_email"><?php esc_html_e( 'HR Contact Email', 'smart-leading-net' ); ?></label></th>
			<td>
				<input id="sln_careers_hr_email" type="email" class="regular-text" name="sln_careers_hr_email" value="<?php echo esc_attr( $hr_email ); ?>" />
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Render media metabox.
 *
 * @param WP_Post $post Post object.
 */
function sln_careers_render_media_metabox( $post ) {
	$culture_image = absint( get_post_meta( $post->ID, SLN_CAREERS_META_CULTURE_IMAGE, true ) );
	$life_images   = get_post_meta( $post->ID, SLN_CAREERS_META_LIFE_IMAGES, true );
	$gallery       = sln_get_careers_life_gallery_defaults();

	if ( ! is_array( $life_images ) ) {
		$life_images = array();
	}
	?>
	<table class="form-table" role="presentation">
		<tr>
			<th scope="row"><?php esc_html_e( 'Culture Image', 'smart-leading-net' ); ?></th>
			<td>
				<?php
				if ( function_exists( 'sln_our_services_render_media_field' ) ) {
					sln_our_services_render_media_field( 'sln_careers_culture_image', $culture_image, 'WEBP' );
				}
				?>
				<p class="description"><?php esc_html_e( 'Optional. Falls back to the theme default image when empty.', 'smart-leading-net' ); ?></p>
			</td>
		</tr>
	</table>

	<h4><?php esc_html_e( 'Life Gallery Images', 'smart-leading-net' ); ?></h4>
	<p class="description"><?php esc_html_e( 'Optional images for each gallery card. Theme defaults are used when empty.', 'smart-leading-net' ); ?></p>

	<div class="sln-careers-admin__life-grid">
		<?php foreach ( $gallery as $index => $item ) : ?>
			<div class="sln-careers-admin__life-item" style="margin-bottom:16px;padding:12px;border:1px solid #dcdcde;background:#fff;">
				<strong><?php echo esc_html( $item['title'] ); ?></strong>
				<?php
				$attachment_id = isset( $life_images[ $index ] ) ? absint( $life_images[ $index ] ) : 0;
				if ( function_exists( 'sln_our_services_render_media_field' ) ) {
					sln_our_services_render_media_field( 'sln_careers_life_images[' . $index . ']', $attachment_id, 'WEBP' );
				}
				?>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
}
