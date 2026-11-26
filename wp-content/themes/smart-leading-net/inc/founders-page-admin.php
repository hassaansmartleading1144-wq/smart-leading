<?php
/**
 * Founders page — admin meta box (photos, bios, socials, courses).
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether a page uses the founders template.
 *
 * @param WP_Post $post Post.
 * @return bool
 */
function sln_founders_admin_is_target_page( $post ) {
	return $post instanceof WP_Post && SLN_FOUNDERS_TEMPLATE === get_page_template_slug( $post->ID );
}

/**
 * Register the meta box.
 */
function sln_founders_register_meta_box() {
	if ( ! sln_page_admin_should_register_template_boxes( 'sln_founders_admin_is_target_page' ) ) {
		return;
	}
	add_meta_box(
		'sln_founders_page',
		__( 'Founders Page — photos, bios, socials & courses', 'smart-leading-net' ),
		'sln_founders_render_meta_box',
		'page',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes_page', 'sln_founders_register_meta_box' );

/**
 * Admin assets.
 *
 * @param string $hook Admin hook.
 */
function sln_founders_admin_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}
	wp_enqueue_media();
	$js = SLN_THEME_DIR . '/assets/js/founders-page-admin.js';
	wp_enqueue_script(
		'sln-founders-page-admin',
		SLN_THEME_URI . '/assets/js/founders-page-admin.js',
		array( 'jquery' ),
		file_exists( $js ) ? (string) filemtime( $js ) : SLN_THEME_VERSION,
		true
	);
	wp_add_inline_style( 'wp-admin', sln_founders_admin_css() );
}
add_action( 'admin_enqueue_scripts', 'sln_founders_admin_assets' );

/**
 * Small admin stylesheet.
 *
 * @return string
 */
function sln_founders_admin_css() {
	return '
	.sfa{--sfa-line:#dcdcde}
	.sfa__note{background:#f0f6fc;border-left:4px solid #2271b1;padding:10px 12px;margin:4px 0 16px}
	.sfa__tabs{display:flex;gap:4px;border-bottom:1px solid var(--sfa-line);margin-bottom:16px;flex-wrap:wrap}
	.sfa__tab{background:#f6f7f7;border:1px solid var(--sfa-line);border-bottom:0;padding:8px 14px;cursor:pointer;font-weight:600;border-radius:4px 4px 0 0;margin-bottom:-1px}
	.sfa__tab[aria-selected=true]{background:#fff}
	.sfa__panel[hidden]{display:none}
	.sfa__grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:12px 16px}
	.sfa label{display:block;font-weight:600;margin-bottom:4px}
	.sfa input[type=text],.sfa input[type=url],.sfa input[type=email],.sfa textarea{width:100%}
	.sfa .description{margin-top:4px}
	.sfa__field{margin-bottom:12px}
	.sfa h4{margin:22px 0 10px;padding-top:14px;border-top:1px solid var(--sfa-line);font-size:14px}
	.sfa__media{display:flex;gap:14px;align-items:flex-start}
	.sfa__media-preview{width:96px;height:120px;border:1px dashed #a7aaad;border-radius:6px;background:#f6f7f7 center/cover no-repeat;flex:none;display:grid;place-items:center;color:#787c82;font-size:11px;text-align:center}
	.sfa__course{display:grid;grid-template-columns:2fr 1.4fr .6fr 1.6fr 1.1fr 1.1fr auto;gap:8px;align-items:end;padding:10px;border:1px solid var(--sfa-line);border-radius:6px;margin-bottom:8px;background:#fbfbfc}
	.sfa__course label{font-weight:500;font-size:12px}
	.sfa__media--compact{gap:8px;align-items:center}
	.sfa__media--compact .sfa__media-preview{width:40px;height:40px;border-radius:50%;font-size:0;background-size:contain;background-color:#fff}
	@media (max-width:900px){.sfa__course{grid-template-columns:1fr 1fr}}
	';
}

/**
 * Field name helper.
 *
 * @param string ...$parts Path parts.
 * @return string
 */
function sln_founders_field_name( ...$parts ) {
	return 'sln_founders[' . implode( '][', array_map( 'strval', $parts ) ) . ']';
}

/**
 * Render a media picker.
 *
 * @param string $name    Input name.
 * @param int    $id      Attachment ID.
 * @param string $label   Button label.
 */
function sln_founders_media_field( $name, $id, $label, $compact = false ) {
	$url = $id ? wp_get_attachment_image_url( $id, 'medium' ) : '';
	?>
	<div class="sfa__media<?php echo $compact ? ' sfa__media--compact' : ''; ?>" data-sfa-media>
		<div class="sfa__media-preview" data-sfa-preview style="<?php echo $url ? esc_attr( 'background-image:url(' . $url . ')' ) : ''; ?>"><?php echo $url ? '' : esc_html__( 'No photo', 'smart-leading-net' ); ?></div>
		<div>
			<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( (string) $id ); ?>" data-sfa-id>
			<button type="button" class="button" data-sfa-select><?php echo esc_html( $label ); ?></button>
			<button type="button" class="button-link button-link-delete" data-sfa-remove <?php echo $id ? '' : 'hidden'; ?>><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
			<?php if ( ! $compact ) : ?>
				<p class="description"><?php esc_html_e( 'Portrait 4:5, at least 1040 × 1300px. Set the alt text in the Media Library.', 'smart-leading-net' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
	<?php
}

/**
 * Render one course row.
 *
 * @param int|string $fi     Founder index.
 * @param int|string $ci     Course index.
 * @param array      $course Course data.
 */
function sln_founders_course_row( $fi, $ci, $course ) {
	$fields = array(
		'title'    => array( __( 'Course name', 'smart-leading-net' ), 'text', 'e.g. Google Ads Search Certification' ),
		'provider' => array( __( 'Provider', 'smart-leading-net' ), 'text', 'e.g. Google Skillshop' ),
		'year'     => array( __( 'Year', 'smart-leading-net' ), 'text', '2025' ),
		'url'      => array( __( 'Certificate link (optional)', 'smart-leading-net' ), 'url', 'https://' ),
	);
	?>
	<div class="sfa__course" data-sfa-course>
		<?php foreach ( $fields as $key => $def ) : ?>
			<div>
				<label><?php echo esc_html( $def[0] ); ?>
					<input type="<?php echo esc_attr( $def[1] ); ?>" name="<?php echo esc_attr( sln_founders_field_name( 'founders', $fi, 'courses', $ci, $key ) ); ?>" value="<?php echo esc_attr( $course[ $key ] ?? '' ); ?>" placeholder="<?php echo esc_attr( $def[2] ); ?>">
				</label>
			</div>
		<?php endforeach; ?>
		<div class="sfa__course-badge">
			<label><?php esc_html_e( 'Badge (optional)', 'smart-leading-net' ); ?></label>
			<?php sln_founders_media_field( sln_founders_field_name( 'founders', $fi, 'courses', $ci, 'badge_id' ), (int) ( $course['badge_id'] ?? 0 ), __( 'Choose', 'smart-leading-net' ), true ); ?>
		</div>
		<div class="sfa__course-badge">
			<label><?php esc_html_e( 'Certificate image', 'smart-leading-net' ); ?></label>
			<?php sln_founders_media_field( sln_founders_field_name( 'founders', $fi, 'courses', $ci, 'cert_id' ), (int) ( $course['cert_id'] ?? 0 ), __( 'Choose', 'smart-leading-net' ), true ); ?>
		</div>
		<button type="button" class="button-link button-link-delete" data-sfa-remove-course><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
	</div>
	<?php
}

/**
 * Render the meta box.
 *
 * @param WP_Post $post Post.
 */
function sln_founders_render_meta_box( $post ) {
	$data = sln_founders_get_data( $post->ID );
	$hero = $data['hero'];
	wp_nonce_field( 'sln_founders_save', 'sln_founders_nonce' );
	?>
	<div class="sfa">
		<p class="sfa__note"><?php esc_html_e( 'This page is built from the fields below. Content in the block editor above is not shown on this template. Empty bios, socials and courses are hidden from visitors; you will see dashed reminders while logged in.', 'smart-leading-net' ); ?></p>

		<div class="sfa__tabs" role="tablist">
			<button type="button" class="sfa__tab" role="tab" aria-selected="true" data-sfa-tab="hero"><?php esc_html_e( 'Hero', 'smart-leading-net' ); ?></button>
			<?php foreach ( $data['founders'] as $fi => $f ) : ?>
				<button type="button" class="sfa__tab" role="tab" aria-selected="false" data-sfa-tab="f<?php echo esc_attr( (string) $fi ); ?>"><?php echo esc_html( $f['name'] ); ?></button>
			<?php endforeach; ?>
		</div>

		<div class="sfa__panel" data-sfa-panel="hero">
			<div class="sfa__grid">
				<div class="sfa__field"><label><?php esc_html_e( 'Small label', 'smart-leading-net' ); ?><input type="text" name="<?php echo esc_attr( sln_founders_field_name( 'hero', 'label' ) ); ?>" value="<?php echo esc_attr( $hero['label'] ); ?>"></label></div>
				<div class="sfa__field"><label><?php esc_html_e( 'Main heading (H1)', 'smart-leading-net' ); ?><input type="text" name="<?php echo esc_attr( sln_founders_field_name( 'hero', 'title' ) ); ?>" value="<?php echo esc_attr( $hero['title'] ); ?>"></label><p class="description"><?php esc_html_e( 'Wrap words in {curly braces} to show them in orange.', 'smart-leading-net' ); ?></p></div>
				<div class="sfa__field"><label><?php esc_html_e( '“Since” line', 'smart-leading-net' ); ?><input type="text" name="<?php echo esc_attr( sln_founders_field_name( 'hero', 'since' ) ); ?>" value="<?php echo esc_attr( $hero['since'] ); ?>"></label></div>
			</div>
			<div class="sfa__field"><label><?php esc_html_e( 'Intro', 'smart-leading-net' ); ?><textarea rows="3" name="<?php echo esc_attr( sln_founders_field_name( 'hero', 'intro' ) ); ?>"><?php echo esc_textarea( $hero['intro'] ); ?></textarea></label></div>
			<h4><?php esc_html_e( 'Joint photo (optional)', 'smart-leading-net' ); ?></h4>
			<p class="description"><?php esc_html_e( 'If set, replaces the two portraits in the hero.', 'smart-leading-net' ); ?></p>
			<?php sln_founders_media_field( sln_founders_field_name( 'hero', 'joint_photo' ), (int) $hero['joint_photo'], __( 'Choose joint photo', 'smart-leading-net' ) ); ?>
		</div>

		<?php foreach ( $data['founders'] as $fi => $f ) : ?>
			<div class="sfa__panel" data-sfa-panel="f<?php echo esc_attr( (string) $fi ); ?>" hidden>
				<h4 style="border:0;padding:0;margin-top:0"><?php esc_html_e( 'Photo', 'smart-leading-net' ); ?></h4>
				<?php sln_founders_media_field( sln_founders_field_name( 'founders', $fi, 'photo_id' ), (int) $f['photo_id'], __( 'Choose photo', 'smart-leading-net' ) ); ?>

				<h4><?php esc_html_e( 'Profile', 'smart-leading-net' ); ?></h4>
				<div class="sfa__grid">
					<div class="sfa__field"><label><?php esc_html_e( 'Name', 'smart-leading-net' ); ?><input type="text" name="<?php echo esc_attr( sln_founders_field_name( 'founders', $fi, 'name' ) ); ?>" value="<?php echo esc_attr( $f['name'] ); ?>" required></label></div>
					<div class="sfa__field"><label><?php esc_html_e( 'Role', 'smart-leading-net' ); ?><input type="text" name="<?php echo esc_attr( sln_founders_field_name( 'founders', $fi, 'role' ) ); ?>" value="<?php echo esc_attr( $f['role'] ); ?>"></label></div>
					<div class="sfa__field"><label><?php esc_html_e( 'Professional focus', 'smart-leading-net' ); ?><input type="text" name="<?php echo esc_attr( sln_founders_field_name( 'founders', $fi, 'focus' ) ); ?>" value="<?php echo esc_attr( $f['focus'] ); ?>"></label></div>
				</div>
				<input type="hidden" name="<?php echo esc_attr( sln_founders_field_name( 'founders', $fi, 'anchor' ) ); ?>" value="<?php echo esc_attr( $f['anchor'] ); ?>">
				<div class="sfa__field"><label><?php esc_html_e( 'Short intro (one or two sentences)', 'smart-leading-net' ); ?><textarea rows="2" name="<?php echo esc_attr( sln_founders_field_name( 'founders', $fi, 'intro' ) ); ?>"><?php echo esc_textarea( $f['intro'] ); ?></textarea></label></div>
				<div class="sfa__field"><label><?php esc_html_e( 'Full biography', 'smart-leading-net' ); ?><textarea rows="7" name="<?php echo esc_attr( sln_founders_field_name( 'founders', $fi, 'bio' ) ); ?>"><?php echo esc_textarea( $f['bio'] ); ?></textarea></label><p class="description"><?php esc_html_e( 'Leave a blank line between paragraphs. Basic links are allowed.', 'smart-leading-net' ); ?></p></div>

				<h4><?php esc_html_e( 'Social profiles', 'smart-leading-net' ); ?></h4>
				<p class="description"><?php esc_html_e( 'Only filled-in links are shown.', 'smart-leading-net' ); ?></p>
				<div class="sfa__grid">
					<?php foreach ( sln_founders_social_networks() as $key => $label ) : ?>
						<div class="sfa__field"><label><?php echo esc_html( $label ); ?>
							<input type="<?php echo 'email' === $key ? 'email' : 'url'; ?>" name="<?php echo esc_attr( sln_founders_field_name( 'founders', $fi, 'socials', $key ) ); ?>" value="<?php echo esc_attr( $f['socials'][ $key ] ); ?>" placeholder="<?php echo 'email' === $key ? 'name@smartleading.net' : 'https://'; ?>">
						</label></div>
					<?php endforeach; ?>
				</div>

				<h4><?php esc_html_e( 'Courses & certifications completed', 'smart-leading-net' ); ?></h4>
				<div data-sfa-courses data-founder="<?php echo esc_attr( (string) $fi ); ?>" data-next="<?php echo esc_attr( (string) count( $f['courses'] ) ); ?>">
					<?php foreach ( $f['courses'] as $ci => $course ) : ?>
						<?php sln_founders_course_row( $fi, $ci, $course ); ?>
					<?php endforeach; ?>
				</div>
				<button type="button" class="button" data-sfa-add-course><?php esc_html_e( '+ Add course', 'smart-leading-net' ); ?></button>
				<template data-sfa-course-template>
					<?php sln_founders_course_row( $fi, '__CI__', array() ); ?>
				</template>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * Save the meta box.
 *
 * @param int $post_id Post ID.
 */
function sln_founders_save_meta_box( $post_id ) {
	if ( ! isset( $_POST['sln_founders_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sln_founders_nonce'] ) ), 'sln_founders_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( ! isset( $_POST['sln_founders'] ) ) {
		return;
	}
	$data = sln_founders_sanitize_data( $_POST['sln_founders'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitised in helper.
	if ( empty( $data['founders'] ) ) {
		return; // Never wipe founders by accident.
	}
	update_post_meta( $post_id, SLN_FOUNDERS_META_KEY, $data );
}
add_action( 'save_post_page', 'sln_founders_save_meta_box' );
