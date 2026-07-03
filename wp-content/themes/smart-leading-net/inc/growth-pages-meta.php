<?php
/**
 * Growth Pages — banner meta boxes and helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_GROWTH_PAGE_BANNER_META', '_sln_gp_banner' );

/**
 * Banner meta field keys.
 *
 * @return array<string, string>
 */
function sln_growth_page_get_banner_field_map() {
	return array(
		'small_heading'        => '_sln_gp_banner_small_heading',
		'core_service_text'    => '_sln_gp_banner_core_service_text',
		'main_heading'         => '_sln_gp_banner_main_heading',
		'highlight_word'       => '_sln_gp_banner_highlight_word',
		'description'          => '_sln_gp_banner_description',
		'primary_btn_text'     => '_sln_gp_banner_primary_btn_text',
		'primary_btn_url'      => '_sln_gp_banner_primary_btn_url',
		'secondary_btn_text'   => '_sln_gp_banner_secondary_btn_text',
		'secondary_btn_url'    => '_sln_gp_banner_secondary_btn_url',
		'banner_image_id'      => '_sln_gp_banner_image_id',
	);
}

/**
 * Get banner field values for a Growth Page.
 *
 * @param int|null $post_id Post ID.
 * @return array<string, mixed>
 */
function sln_get_growth_page_banner( $post_id = null ) {
	$post_id = $post_id ? absint( $post_id ) : get_the_ID();

	if ( ! $post_id ) {
		return array();
	}

	$fields = sln_growth_page_get_banner_field_map();
	$banner = array();

	foreach ( $fields as $key => $meta_key ) {
		if ( 'banner_image_id' === $key ) {
			$banner[ $key ] = absint( get_post_meta( $post_id, $meta_key, true ) );
			continue;
		}

		$banner[ $key ] = (string) get_post_meta( $post_id, $meta_key, true );
	}

	$banner['banner_image_url'] = $banner['banner_image_id']
		? (string) wp_get_attachment_image_url( $banner['banner_image_id'], 'full' )
		: '';

	return $banner;
}

/**
 * Register banner and section order meta boxes.
 */
function sln_growth_page_register_meta_boxes() {
	add_meta_box(
		'sln-growth-page-banner',
		__( 'Banner Content', 'smart-leading-net' ),
		'sln_growth_page_render_banner_meta_box',
		SLN_GROWTH_PAGE_POST_TYPE,
		'normal',
		'high'
	);

	add_meta_box(
		'sln-growth-page-section-order',
		__( 'Section Order', 'smart-leading-net' ),
		'sln_growth_page_render_section_order_meta_box',
		SLN_GROWTH_PAGE_POST_TYPE,
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'sln_growth_page_register_meta_boxes' );

/**
 * Render section order meta box.
 *
 * @param WP_Post $post Current post.
 */
function sln_growth_page_render_section_order_meta_box( $post ) {
	wp_nonce_field( 'sln_growth_page_save_section_order', 'sln_growth_page_section_order_nonce', false );

	$orders   = sln_get_growth_page_section_orders( $post->ID );
	$registry = sln_get_growth_page_section_registry();
	?>
	<div class="sln-gp-admin">
		<p class="description">
			<?php esc_html_e( 'Set the display order for each section. Lower numbers appear first.', 'smart-leading-net' ); ?>
		</p>
		<table class="form-table" role="presentation">
			<tbody>
				<?php foreach ( $registry as $section_key => $section ) : ?>
					<tr>
						<th scope="row">
							<label for="sln_gp_section_order_<?php echo esc_attr( $section_key ); ?>">
								<?php echo esc_html( $section['label'] ); ?>
							</label>
						</th>
						<td>
							<input
								type="number"
								min="1"
								max="99"
								step="1"
								class="small-text"
								id="sln_gp_section_order_<?php echo esc_attr( $section_key ); ?>"
								name="sln_gp_section_orders[<?php echo esc_attr( $section_key ); ?>]"
								value="<?php echo esc_attr( $orders[ $section_key ] ); ?>"
							/>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
}

/**
 * Save section order meta box values.
 *
 * Runs at priority 99 so all POST fields are available and this is the only writer
 * for SLN_GP_SECTION_ORDERS_META (section metaboxes display order inputs but do not save them).
 *
 * @param int $post_id Post ID.
 */
function sln_growth_page_save_section_order_meta( $post_id ) {
	sln_growth_page_save_section_orders_from_post( $post_id );
}

/**
 * Render banner meta box fields.
 *
 * @param WP_Post $post Current post.
 */
function sln_growth_page_render_banner_meta_box( $post ) {
	wp_nonce_field( 'sln_growth_page_save_banner', 'sln_growth_page_banner_nonce', false );

	$banner = sln_get_growth_page_banner( $post->ID );
	?>
	<div class="sln-gp-admin">
		<p class="description">
			<?php esc_html_e( 'Hero banner content for this Growth Page. All fields are optional, but empty fields will not appear on the frontend.', 'smart-leading-net' ); ?>
		</p>

		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><label for="sln_gp_banner_small_heading"><?php esc_html_e( 'Small Heading', 'smart-leading-net' ); ?></label></th>
					<td>
						<input type="text" class="large-text" id="sln_gp_banner_small_heading" name="sln_gp_banner_small_heading" value="<?php echo esc_attr( $banner['small_heading'] ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_banner_core_service_text"><?php esc_html_e( 'Core Service Text', 'smart-leading-net' ); ?></label></th>
					<td>
						<input type="text" class="large-text" id="sln_gp_banner_core_service_text" name="sln_gp_banner_core_service_text" value="<?php echo esc_attr( $banner['core_service_text'] ); ?>" placeholder="<?php esc_attr_e( 'Core Service', 'smart-leading-net' ); ?>" />
						<p class="description"><?php esc_html_e( 'Text for the secondary badge pill beside the small heading.', 'smart-leading-net' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_banner_main_heading"><?php esc_html_e( 'Main Heading', 'smart-leading-net' ); ?></label></th>
					<td>
						<input type="text" class="large-text" id="sln_gp_banner_main_heading" name="sln_gp_banner_main_heading" value="<?php echo esc_attr( $banner['main_heading'] ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_banner_highlight_word"><?php esc_html_e( 'Highlighted Word', 'smart-leading-net' ); ?></label></th>
					<td>
						<input type="text" class="regular-text" id="sln_gp_banner_highlight_word" name="sln_gp_banner_highlight_word" value="<?php echo esc_attr( $banner['highlight_word'] ); ?>" />
						<p class="description"><?php esc_html_e( 'Displayed in accent orange on the second heading line.', 'smart-leading-net' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_banner_description"><?php esc_html_e( 'Description', 'smart-leading-net' ); ?></label></th>
					<td>
						<?php
						sln_growth_page_render_wysiwyg_editor(
							'sln_gp_banner_description',
							'sln_gp_banner_description',
							$banner['description']
						);
						?>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_banner_primary_btn_text"><?php esc_html_e( 'Primary Button Text', 'smart-leading-net' ); ?></label></th>
					<td>
						<input type="text" class="regular-text" id="sln_gp_banner_primary_btn_text" name="sln_gp_banner_primary_btn_text" value="<?php echo esc_attr( $banner['primary_btn_text'] ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_banner_primary_btn_url"><?php esc_html_e( 'Primary Button URL', 'smart-leading-net' ); ?></label></th>
					<td>
						<input type="url" class="large-text" id="sln_gp_banner_primary_btn_url" name="sln_gp_banner_primary_btn_url" value="<?php echo esc_attr( $banner['primary_btn_url'] ); ?>" placeholder="https://example.com/contact" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_banner_secondary_btn_text"><?php esc_html_e( 'Secondary Button Text', 'smart-leading-net' ); ?></label></th>
					<td>
						<input type="text" class="regular-text" id="sln_gp_banner_secondary_btn_text" name="sln_gp_banner_secondary_btn_text" value="<?php echo esc_attr( $banner['secondary_btn_text'] ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_banner_secondary_btn_url"><?php esc_html_e( 'Secondary Button URL', 'smart-leading-net' ); ?></label></th>
					<td>
						<input type="url" class="large-text" id="sln_gp_banner_secondary_btn_url" name="sln_gp_banner_secondary_btn_url" value="<?php echo esc_attr( $banner['secondary_btn_url'] ); ?>" placeholder="https://example.com/services" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Banner Image', 'smart-leading-net' ); ?></th>
					<td>
						<?php sln_our_services_render_media_field( 'sln_gp_banner_image_id', $banner['banner_image_id'], 'PNG, JPG, WEBP, or SVG' ); ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php
}

/**
 * Save banner meta box values.
 *
 * @param int $post_id Post ID.
 */
function sln_growth_page_save_banner_meta( $post_id ) {
	if ( ! sln_growth_page_should_save_meta( $post_id, 'sln_growth_page_banner_nonce', 'sln_growth_page_save_banner' ) ) {
		return;
	}

	$text_fields = array(
		'small_heading'      => 'sln_gp_banner_small_heading',
		'core_service_text'  => 'sln_gp_banner_core_service_text',
		'main_heading'       => 'sln_gp_banner_main_heading',
		'highlight_word'     => 'sln_gp_banner_highlight_word',
		'primary_btn_text'   => 'sln_gp_banner_primary_btn_text',
		'secondary_btn_text' => 'sln_gp_banner_secondary_btn_text',
	);

	foreach ( $text_fields as $meta_suffix => $input_name ) {
		$value = isset( $_POST[ $input_name ] )
			? sanitize_text_field( wp_unslash( $_POST[ $input_name ] ) )
			: '';

		update_post_meta( $post_id, '_sln_gp_banner_' . $meta_suffix, $value );
	}

	// WYSIWYG: always update when metabox is present — textarea is always in POST after tinymce.triggerSave().
	if ( isset( $_POST['sln_gp_banner_description'] ) ) {
		update_post_meta(
			$post_id,
			'_sln_gp_banner_description',
			sln_growth_page_sanitize_wysiwyg_content( $_POST['sln_gp_banner_description'] )
		);
	}

	$url_fields = array(
		'primary_btn_url'   => 'sln_gp_banner_primary_btn_url',
		'secondary_btn_url' => 'sln_gp_banner_secondary_btn_url',
	);

	foreach ( $url_fields as $meta_suffix => $input_name ) {
		$value = isset( $_POST[ $input_name ] )
			? esc_url_raw( wp_unslash( $_POST[ $input_name ] ) )
			: '';

		update_post_meta( $post_id, '_sln_gp_banner_' . $meta_suffix, $value );
	}

	$image_id = isset( $_POST['sln_gp_banner_image_id'] )
		? sln_sanitize_media_attachment_id( wp_unslash( $_POST['sln_gp_banner_image_id'] ) )
		: 0;

	update_post_meta( $post_id, '_sln_gp_banner_image_id', $image_id );
}

/**
 * Enqueue admin assets for Growth Page editor.
 *
 * @param string $hook Current admin hook.
 */
function sln_enqueue_growth_page_admin_assets( $hook ) {
	global $post_type;

	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) || SLN_GROWTH_PAGE_POST_TYPE !== $post_type ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_editor();

	wp_enqueue_style(
		'sln-our-services-admin',
		SLN_THEME_URI . '/assets/css/our-services-admin.css',
		array(),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-our-services-admin',
		SLN_THEME_URI . '/assets/js/our-services-admin.js',
		array( 'jquery' ),
		SLN_THEME_VERSION,
		true
	);

	wp_enqueue_script(
		'sln-growth-pages-admin',
		SLN_THEME_URI . '/assets/js/growth-pages-admin.js',
		array( 'jquery', 'editor', 'quicktags' ),
		SLN_THEME_VERSION,
		true
	);

	wp_localize_script(
		'sln-growth-pages-admin',
		'slnGrowthPagesAdmin',
		array(
			'editorSettings' => sln_growth_page_get_js_editor_settings(),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'sln_enqueue_growth_page_admin_assets' );

/**
 * Enqueue Growth Page frontend assets.
 */
function sln_enqueue_growth_page_assets() {
	if ( ! is_singular( SLN_GROWTH_PAGE_POST_TYPE ) ) {
		return;
	}

	wp_enqueue_style(
		'sln-growth-page-hero',
		SLN_THEME_URI . '/assets/css/growth-page-hero.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_style(
		'sln-convert-scale',
		SLN_THEME_URI . '/assets/css/convert-scale.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_style(
		'sln-client-story',
		SLN_THEME_URI . '/assets/css/client-story.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_style(
		'sln-our-services',
		SLN_THEME_URI . '/assets/css/our-services.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_style(
		'sln-how-work',
		SLN_THEME_URI . '/assets/css/how-work.css',
		array( 'sln-main', 'sln-our-services' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-how-work',
		SLN_THEME_URI . '/assets/js/how-work.js',
		array(),
		SLN_THEME_VERSION,
		true
	);

	wp_enqueue_style(
		'sln-growth-services',
		SLN_THEME_URI . '/assets/css/growth-services.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-growth-services',
		SLN_THEME_URI . '/assets/js/growth-services.js',
		array(),
		SLN_THEME_VERSION,
		true
	);

	wp_enqueue_style(
		'sln-case-studies',
		SLN_THEME_URI . '/assets/css/case-studies.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-case-studies',
		SLN_THEME_URI . '/assets/js/case-studies.js',
		array(),
		SLN_THEME_VERSION,
		true
	);

	wp_enqueue_style(
		'sln-why-choose',
		SLN_THEME_URI . '/assets/css/why-choose.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_style(
		'sln-price-plan',
		SLN_THEME_URI . '/assets/css/price-plan.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-price-plan',
		SLN_THEME_URI . '/assets/js/price-plan.js',
		array(),
		SLN_THEME_VERSION,
		true
	);

	wp_enqueue_style(
		'sln-testimonials',
		SLN_THEME_URI . '/assets/css/testimonials.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-testimonials',
		SLN_THEME_URI . '/assets/js/testimonials.js',
		array(),
		SLN_THEME_VERSION,
		true
	);

	wp_enqueue_style(
		'sln-starts-cta',
		SLN_THEME_URI . '/assets/css/starts-cta.css',
		array( 'sln-main' ),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-starts-cta',
		SLN_THEME_URI . '/assets/js/starts-cta.js',
		array(),
		SLN_THEME_VERSION,
		true
	);

	wp_enqueue_style(
		'sln-growth-page-mobile',
		SLN_THEME_URI . '/assets/css/growth-page-mobile.css',
		array(
			'sln-main',
			'sln-growth-page-hero',
			'sln-client-story',
			'sln-our-services',
			'sln-how-work',
			'sln-growth-services',
			'sln-case-studies',
			'sln-why-choose',
			'sln-price-plan',
			'sln-testimonials',
			'sln-starts-cta',
		),
		SLN_THEME_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'sln_enqueue_growth_page_assets' );
