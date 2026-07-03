<?php
/**
 * Growth Pages — CTA Banner section meta box and helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_GP_CTA_BANNER_SECTION_META', '_sln_gp_cta_banner_section' );

/**
 * Default phrase wrapped with the orange underline in the heading.
 */
define( 'SLN_GP_CTA_BANNER_DEFAULT_HIGHLIGHT', 'Revenue Growth' );

/**
 * Default CTA Banner section content.
 *
 * @return array<string, mixed>
 */
function sln_get_growth_page_default_cta_banner_section() {
	return array(
		'main_heading'        => __( 'Your Revenue Growth Starts Here', 'smart-leading-net' ),
		'description'         => __( 'Tell us about your business, and we\'ll create a custom plan to grow your traffic, leads, and predictable revenue.', 'smart-leading-net' ),
		'input_placeholder'   => __( 'Enter your website', 'smart-leading-net' ),
		'button_text'         => __( 'Get My Free Proposal', 'smart-leading-net' ),
		'button_url'          => '#',
		'background_image_id' => 0,
		'active'              => true,
	);
}

/**
 * Get CTA Banner section data for frontend.
 *
 * @param int|null $post_id Post ID.
 * @return array<string, mixed>
 */
function sln_get_growth_page_cta_banner( $post_id = null ) {
	$post_id  = $post_id ? absint( $post_id ) : get_the_ID();
	$defaults = sln_get_growth_page_default_cta_banner_section();
	$section  = sln_growth_page_get_section_settings( $post_id, SLN_GP_CTA_BANNER_SECTION_META, $defaults );
	$bg_id    = isset( $section['background_image_id'] ) ? absint( $section['background_image_id'] ) : 0;
	$bg_url   = $bg_id ? (string) wp_get_attachment_image_url( $bg_id, 'full' ) : sln_get_starts_cta_background_url();

	return array(
		'main_heading'        => $section['main_heading'],
		'description'         => $section['description'],
		'input_placeholder'   => $section['input_placeholder'],
		'button_text'         => $section['button_text'],
		'button_url'          => $section['button_url'],
		'background_image_id' => $bg_id,
		'background_image_url'=> $bg_url,
		'active'              => ! empty( $section['active'] ),
	);
}

/**
 * Check whether CTA Banner section should render.
 *
 * @param int|null $post_id Post ID.
 * @return bool
 */
function sln_growth_page_cta_banner_has_content( $post_id = null ) {
	$data = sln_get_growth_page_cta_banner( $post_id );

	if ( empty( $data['active'] ) ) {
		return false;
	}

	return '' !== trim( $data['main_heading'] ) || '' !== trim( $data['description'] );
}

/**
 * Resolve Starts CTA background image URL (same as Home Page).
 *
 * @return string
 */
function sln_get_starts_cta_background_url() {
	$upload_dirs = array(
		WP_CONTENT_DIR . '/uploads/2026/05/',
		WP_CONTENT_DIR . '/uploads/2026/06/',
	);

	$upload_urls = array(
		trailingslashit( content_url( '/uploads/2026/05' ) ),
		trailingslashit( content_url( '/uploads/2026/06' ) ),
	);

	$extensions = array( 'webp', 'png', 'jpg', 'jpeg' );
	$basename   = 'download_bg';

	foreach ( $upload_dirs as $index => $upload_dir ) {
		foreach ( $extensions as $ext ) {
			$file_path = $upload_dir . $basename . '.' . $ext;

			if ( file_exists( $file_path ) ) {
				return $upload_urls[ $index ] . $basename . '.' . $ext;
			}
		}
	}

	return '';
}

/**
 * Resolve Starts CTA heading underline SVG URL (same as Home Page).
 *
 * @return string
 */
function sln_get_starts_cta_underline_url() {
	return trailingslashit( content_url( '/uploads/2026/05' ) ) . rawurlencode( 'vector_43_stroke.svg' );
}

/**
 * Render CTA Banner heading with optional orange underline highlight.
 *
 * @param string $heading          Full heading text.
 * @param string $highlight_phrase Phrase to wrap with underline styling.
 */
function sln_growth_page_render_cta_banner_heading( $heading, $highlight_phrase = SLN_GP_CTA_BANNER_DEFAULT_HIGHLIGHT ) {
	$heading          = trim( (string) $heading );
	$highlight_phrase = trim( (string) $highlight_phrase );

	if ( '' === $heading ) {
		return;
	}

	if ( '' === $highlight_phrase ) {
		echo esc_html( $heading );
		return;
	}

	$pos = stripos( $heading, $highlight_phrase );

	if ( false === $pos ) {
		echo esc_html( $heading );
		return;
	}

	$before    = substr( $heading, 0, $pos );
	$highlight = substr( $heading, $pos, strlen( $highlight_phrase ) );
	$after     = substr( $heading, $pos + strlen( $highlight_phrase ) );
	$underline = sln_get_starts_cta_underline_url();

	if ( '' !== trim( $before ) ) {
		echo esc_html( trim( $before ) ) . ' ';
	}
	?>
	<span class="starts-cta__growth-wrap">
		<span class="starts-cta__growth-text"><?php echo esc_html( $highlight ); ?></span>
		<img
			class="starts-cta__growth-underline"
			src="<?php echo esc_url( $underline ); ?>"
			alt=""
			width="223"
			height="13"
			loading="lazy"
			decoding="async"
		>
	</span>
	<?php
	if ( '' !== trim( $after ) ) {
		echo ' ' . esc_html( trim( $after ) );
	}
}

/**
 * Register CTA Banner meta box.
 */
function sln_growth_page_register_cta_banner_meta_box() {
	add_meta_box(
		'sln-growth-page-cta-banner',
		__( 'CTA Banner Section', 'smart-leading-net' ),
		'sln_growth_page_render_cta_banner_meta_box',
		SLN_GROWTH_PAGE_POST_TYPE,
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'sln_growth_page_register_cta_banner_meta_box' );

/**
 * Render CTA Banner meta box.
 *
 * @param WP_Post $post Current post.
 */
function sln_growth_page_render_cta_banner_meta_box( $post ) {
	wp_nonce_field( 'sln_growth_page_save_cta_banner', 'sln_growth_page_cta_banner_nonce', false );

	$defaults = sln_get_growth_page_default_cta_banner_section();
	$section  = get_post_meta( $post->ID, SLN_GP_CTA_BANNER_SECTION_META, true );
	$section  = is_array( $section ) ? array_intersect_key( wp_parse_args( $section, $defaults ), $defaults ) : $defaults;
	$bg_id    = isset( $section['background_image_id'] ) ? absint( $section['background_image_id'] ) : 0;
	$orders   = sln_get_growth_page_section_orders( $post->ID );
	$order    = isset( $orders['cta_banner'] ) ? absint( $orders['cta_banner'] ) : 10;
	?>
	<div class="sln-gp-admin">
		<p class="description"><?php esc_html_e( 'Manage the CTA Banner section. Uses the same frontend design as the Home Page Starts CTA section.', 'smart-leading-net' ); ?></p>

		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><label for="sln_gp_cta_banner_main_heading"><?php esc_html_e( 'Main Heading', 'smart-leading-net' ); ?></label></th>
					<td>
						<input type="text" class="large-text" id="sln_gp_cta_banner_main_heading" name="sln_gp_cta_banner_section[main_heading]" value="<?php echo esc_attr( $section['main_heading'] ); ?>" />
						<p class="description"><?php esc_html_e( 'Include "Revenue Growth" in the heading to apply the orange underline accent from the Home Page design.', 'smart-leading-net' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_cta_banner_description"><?php esc_html_e( 'Description', 'smart-leading-net' ); ?></label></th>
					<td>
						<textarea class="large-text" rows="4" id="sln_gp_cta_banner_description" name="sln_gp_cta_banner_section[description]"><?php echo esc_textarea( $section['description'] ); ?></textarea>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_cta_banner_input_placeholder"><?php esc_html_e( 'Placeholder Text', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_cta_banner_input_placeholder" name="sln_gp_cta_banner_section[input_placeholder]" value="<?php echo esc_attr( $section['input_placeholder'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_cta_banner_button_text"><?php esc_html_e( 'Button Text', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_cta_banner_button_text" name="sln_gp_cta_banner_section[button_text]" value="<?php echo esc_attr( $section['button_text'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_cta_banner_button_url"><?php esc_html_e( 'Button URL', 'smart-leading-net' ); ?></label></th>
					<td><input type="url" class="large-text" id="sln_gp_cta_banner_button_url" name="sln_gp_cta_banner_section[button_url]" value="<?php echo esc_attr( $section['button_url'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Background Image', 'smart-leading-net' ); ?></th>
					<td>
						<?php sln_our_services_render_media_field( 'sln_gp_cta_banner_section[background_image_id]', $bg_id, 'PNG, JPG, WEBP' ); ?>
						<p class="description"><?php esc_html_e( 'Optional. Falls back to the Home Page CTA background when empty.', 'smart-leading-net' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_section_order_cta_banner"><?php esc_html_e( 'Section Order Number', 'smart-leading-net' ); ?></label></th>
					<td>
						<input type="number" min="1" max="99" step="1" class="small-text" id="sln_gp_section_order_cta_banner" name="sln_gp_section_orders[cta_banner]" value="<?php echo esc_attr( $order ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_cta_banner_active"><?php esc_html_e( 'Active Section', 'smart-leading-net' ); ?></label></th>
					<td>
						<select id="sln_gp_cta_banner_active" name="sln_gp_cta_banner_section[active]">
							<option value="1" <?php selected( ! empty( $section['active'] ) ); ?>><?php esc_html_e( 'Yes', 'smart-leading-net' ); ?></option>
							<option value="0" <?php selected( empty( $section['active'] ) ); ?>><?php esc_html_e( 'No', 'smart-leading-net' ); ?></option>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php
}

/**
 * Save CTA Banner meta box values.
 *
 * @param int $post_id Post ID.
 */
function sln_growth_page_save_cta_banner_meta( $post_id ) {
	if ( ! sln_growth_page_should_save_meta( $post_id, 'sln_growth_page_cta_banner_nonce', 'sln_growth_page_save_cta_banner' ) ) {
		return;
	}

	if ( isset( $_POST['sln_gp_cta_banner_section'] ) && is_array( $_POST['sln_gp_cta_banner_section'] ) ) {
		$raw     = wp_unslash( $_POST['sln_gp_cta_banner_section'] );
		$section = array(
			'main_heading'        => isset( $raw['main_heading'] ) ? sanitize_text_field( $raw['main_heading'] ) : '',
			'description'         => isset( $raw['description'] ) ? wp_kses_post( $raw['description'] ) : '',
			'input_placeholder'   => isset( $raw['input_placeholder'] ) ? sanitize_text_field( $raw['input_placeholder'] ) : '',
			'button_text'         => isset( $raw['button_text'] ) ? sanitize_text_field( $raw['button_text'] ) : '',
			'button_url'          => isset( $raw['button_url'] ) ? esc_url_raw( $raw['button_url'] ) : '#',
			'background_image_id' => sln_sanitize_media_attachment_id(
				$raw['background_image_id'] ?? 0,
				array( 'image/webp', 'image/png', 'image/jpeg', 'image/jpg' )
			),
			'active'              => ! empty( $raw['active'] ),
		);

		update_post_meta( $post_id, SLN_GP_CTA_BANNER_SECTION_META, $section );
	}
}
