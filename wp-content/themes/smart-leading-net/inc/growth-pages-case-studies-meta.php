<?php
/**
 * Growth Pages — Case Studies section meta box and helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_GP_CASE_STUDIES_SECTION_META', '_sln_gp_case_studies_section' );
define( 'SLN_GP_CASE_STUDIES_CARDS_META', '_sln_gp_case_studies_cards' );
define( 'SLN_GP_CASE_STUDIES_UPLOADS', '2026/05/' );

/**
 * Default chart SVG filenames (same as Home Page).
 *
 * @return array<int, string>
 */
function sln_get_growth_page_case_studies_chart_files() {
	return array(
		'paid-chart.svg',
		'ad-spend-chart.svg',
		'organic-chart.svg',
	);
}

/**
 * Default icon SVG filenames for unsaved Growth Pages.
 *
 * @return array<int, string>
 */
function sln_get_growth_page_case_studies_default_icon_files() {
	return array(
		'surface1.svg',
		'ecommerce.svg',
		'hospitality.svg',
	);
}

/**
 * Default Case Studies section content.
 *
 * @return array<string, string>
 */
function sln_get_growth_page_default_case_studies_section() {
	return array(
		'label'           => __( 'Case Studies', 'smart-leading-net' ),
		'main_heading'    => __( 'Proven Results For', 'smart-leading-net' ),
		'highlight_word'  => __( 'Growth-Focused Businesses', 'smart-leading-net' ),
		'description'     => __( 'See how we help brands turn strategy, paid media, websites, and optimisation into measurable growth through smart execution and data-backed decisions.', 'smart-leading-net' ),
		'more_link_text'  => __( 'More Case Studies', 'smart-leading-net' ),
		'more_link_url'   => '#',
	);
}

/**
 * Default Case Studies card.
 *
 * @param array<string, mixed> $args Card defaults.
 * @return array<string, mixed>
 */
function sln_get_growth_page_default_case_studies_card( $args ) {
	$defaults = array(
		'title'              => '',
		'metric_value'       => '',
		'metric_description' => '',
		'icon_id'            => 0,
		'icon_fallback'      => '',
		'theme_color'        => '#1f4e9e',
		'tags'               => array(),
		'active'             => true,
	);

	return wp_parse_args( $args, $defaults );
}

/**
 * Default Case Studies cards.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_get_growth_page_default_case_studies_cards() {
	$icon_files = sln_get_growth_page_case_studies_default_icon_files();

	return array(
		sln_get_growth_page_default_case_studies_card(
			array(
				'title'              => __( 'Case Study: Manufacturing', 'smart-leading-net' ),
				'metric_value'       => '30%',
				'metric_description' => __( 'ROI Increase From Paid Search', 'smart-leading-net' ),
				'icon_fallback'      => $icon_files[0],
				'theme_color'        => '#12b8b8',
				'tags'               => array(),
				'active'             => true,
			)
		),
		sln_get_growth_page_default_case_studies_card(
			array(
				'title'              => __( 'Case Study: ECommerce', 'smart-leading-net' ),
				'metric_value'       => '4.2X',
				'metric_description' => __( 'Higher Return On Ad Spend', 'smart-leading-net' ),
				'icon_fallback'      => $icon_files[1],
				'theme_color'        => '#f36b32',
				'tags'               => array(
					__( 'Data-Backed Strategies. Real Business Impact.', 'smart-leading-net' ),
				),
				'active'             => true,
			)
		),
		sln_get_growth_page_default_case_studies_card(
			array(
				'title'              => __( 'Case Study: Hospitality', 'smart-leading-net' ),
				'metric_value'       => '260%',
				'metric_description' => __( 'Increase In Organic Revenue', 'smart-leading-net' ),
				'icon_fallback'      => $icon_files[2],
				'theme_color'        => '#1f4e9e',
				'tags'               => array(
					__( 'More Traffic', 'smart-leading-net' ),
					__( 'Higher Rankings', 'smart-leading-net' ),
					__( 'More Revenue', 'smart-leading-net' ),
				),
				'active'             => true,
			)
		),
	);
}

/**
 * Sanitize a hex theme color.
 *
 * @param string $color Raw color value.
 * @return string
 */
function sln_growth_page_sanitize_case_studies_color( $color ) {
	$sanitized = sanitize_hex_color( wp_unslash( (string) $color ) );

	return $sanitized ? $sanitized : '#1f4e9e';
}

/**
 * Build inline CSS custom properties for a Case Studies card theme color.
 *
 * @param string $hex Hex color.
 * @return string
 */
function sln_growth_page_case_studies_card_style_attr( $hex ) {
	$hex   = sln_growth_page_sanitize_case_studies_color( $hex );
	$clean = ltrim( $hex, '#' );

	if ( 3 === strlen( $clean ) ) {
		$clean = $clean[0] . $clean[0] . $clean[1] . $clean[1] . $clean[2] . $clean[2];
	}

	$r = hexdec( substr( $clean, 0, 2 ) );
	$g = hexdec( substr( $clean, 2, 2 ) );
	$b = hexdec( substr( $clean, 4, 2 ) );

	$mix       = 0.92;
	$footer_r  = (int) round( $r * ( 1 - $mix ) + 255 * $mix );
	$footer_g  = (int) round( $g * ( 1 - $mix ) + 255 * $mix );
	$footer_b  = (int) round( $b * ( 1 - $mix ) + 255 * $mix );
	$footer_bg = sprintf( '#%02x%02x%02x', min( 255, $footer_r ), min( 255, $footer_g ), min( 255, $footer_b ) );

	$vars = array(
		'--case-studies-card-color:' . $hex,
		sprintf( '--case-studies-card-border:rgba(%d,%d,%d,0.35)', $r, $g, $b ),
		sprintf( '--case-studies-card-bg:linear-gradient(180deg, %s17 0%%, #ffffff 100%%)', $hex ),
		sprintf( '--case-studies-icon-bg:rgba(%d,%d,%d,0.12)', $r, $g, $b ),
		'--case-studies-footer-bg:' . $footer_bg,
		'--case-studies-hover-bg:' . $hex,
	);

	return implode( ';', $vars );
}

/**
 * Sanitize bottom tag rows.
 *
 * @param mixed $tags Raw tags.
 * @return array<int, string>
 */
function sln_sanitize_growth_page_case_studies_tags( $tags ) {
	$sanitized = array();

	if ( ! is_array( $tags ) ) {
		return $sanitized;
	}

	foreach ( $tags as $tag ) {
		$text = sanitize_text_field( wp_unslash( $tag ) );

		if ( '' !== trim( $text ) ) {
			$sanitized[] = $text;
		}
	}

	return $sanitized;
}

/**
 * Sanitize a Case Studies card row.
 *
 * @param array<string, mixed> $card Raw card data.
 * @return array<string, mixed>
 */
function sln_sanitize_growth_page_case_studies_card( $card ) {
	if ( ! is_array( $card ) ) {
		return array();
	}

	$icon_id = sln_sanitize_media_attachment_id( $card['icon_id'] ?? 0, array( 'image/svg+xml' ) );

	return array(
		'title'              => isset( $card['title'] ) ? sanitize_text_field( wp_unslash( $card['title'] ) ) : '',
		'metric_value'       => isset( $card['metric_value'] ) ? sanitize_text_field( wp_unslash( $card['metric_value'] ) ) : '',
		'metric_description' => isset( $card['metric_description'] ) ? sanitize_text_field( wp_unslash( $card['metric_description'] ) ) : '',
		'icon_id'            => $icon_id,
		'theme_color'        => isset( $card['theme_color'] ) ? sln_growth_page_sanitize_case_studies_color( $card['theme_color'] ) : '#1f4e9e',
		'tags'               => isset( $card['tags'] ) ? sln_sanitize_growth_page_case_studies_tags( $card['tags'] ) : array(),
		'active'             => ! empty( $card['active'] ),
	);
}

/**
 * Get Case Studies section data for frontend.
 *
 * @param int|null $post_id Post ID.
 * @return array<string, mixed>
 */
function sln_get_growth_page_case_studies( $post_id = null ) {
	if ( function_exists( 'sln_is_seo_services_page' ) && sln_is_seo_services_page() ) {
		return sln_get_seo_page_case_studies_data();
	}

	$post_id  = $post_id ? absint( $post_id ) : get_the_ID();
	$defaults = sln_get_growth_page_default_case_studies_section();

	$section     = sln_growth_page_get_section_settings( $post_id, SLN_GP_CASE_STUDIES_SECTION_META, $defaults );
	$cards_exist = metadata_exists( 'post', $post_id, SLN_GP_CASE_STUDIES_CARDS_META );
	$cards       = sln_growth_page_get_repeater_rows( $post_id, SLN_GP_CASE_STUDIES_CARDS_META, sln_get_growth_page_default_case_studies_cards() );

	$active_cards   = array();
	$chart_files    = sln_get_growth_page_case_studies_chart_files();
	$default_icons  = sln_get_growth_page_case_studies_default_icon_files();
	$chart_index    = 0;
	$fallback_index = 0;

	foreach ( $cards as $card ) {
		if ( ! is_array( $card ) ) {
			continue;
		}

		$sanitized = sln_sanitize_growth_page_case_studies_card( $card );

		if ( empty( $sanitized['active'] ) ) {
			continue;
		}

		if ( '' === trim( $sanitized['title'] ) && '' === trim( $sanitized['metric_value'] ) && '' === trim( $sanitized['metric_description'] ) ) {
			continue;
		}

		$icon_fallback = '';

		if ( empty( $sanitized['icon_id'] ) && ! $cards_exist && isset( $default_icons[ $fallback_index ] ) ) {
			$icon_fallback = $default_icons[ $fallback_index ];
		}

		$active_cards[] = array(
			'title'              => $sanitized['title'],
			'metric_value'       => $sanitized['metric_value'],
			'metric_description' => $sanitized['metric_description'],
			'icon_id'            => $sanitized['icon_id'],
			'icon_fallback'      => $icon_fallback,
			'theme_color'        => $sanitized['theme_color'],
			'tags'               => $sanitized['tags'],
			'chart_file'         => $chart_files[ $chart_index % count( $chart_files ) ],
		);

		++$chart_index;
		++$fallback_index;
	}

	return array(
		'label'          => $section['label'],
		'main_heading'   => $section['main_heading'],
		'highlight_word' => $section['highlight_word'],
		'description'    => $section['description'],
		'more_link_text' => $section['more_link_text'],
		'more_link_url'  => $section['more_link_url'],
		'cards'          => $active_cards,
	);
}

/**
 * Check whether Case Studies section should render.
 *
 * @param int|null $post_id Post ID.
 * @return bool
 */
function sln_growth_page_case_studies_has_content( $post_id = null ) {
	$data = sln_get_growth_page_case_studies( $post_id );

	return ! empty( $data['cards'] );
}

/**
 * Get raw Case Studies cards for admin.
 *
 * @param int $post_id Post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_growth_page_case_studies_cards_for_admin( $post_id ) {
	if ( ! metadata_exists( 'post', $post_id, SLN_GP_CASE_STUDIES_CARDS_META ) ) {
		return sln_get_growth_page_default_case_studies_cards();
	}

	$cards = get_post_meta( $post_id, SLN_GP_CASE_STUDIES_CARDS_META, true );

	if ( ! is_array( $cards ) ) {
		return array();
	}

	return array_map(
		static function ( $card ) {
			$card = is_array( $card ) ? $card : array();

			return wp_parse_args(
				$card,
				array(
					'title'              => '',
					'metric_value'       => '',
					'metric_description' => '',
					'icon_id'            => 0,
					'theme_color'        => '#1f4e9e',
					'tags'               => array(),
					'active'             => true,
				)
			);
		},
		$cards
	);
}

/**
 * Register Case Studies meta box.
 */
function sln_growth_page_register_case_studies_meta_box() {
	add_meta_box(
		'sln-growth-page-case-studies',
		__( 'Case Studies Section', 'smart-leading-net' ),
		'sln_growth_page_render_case_studies_meta_box',
		SLN_GROWTH_PAGE_POST_TYPE,
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'sln_growth_page_register_case_studies_meta_box' );

/**
 * Render a Case Studies tag row in admin.
 *
 * @param int    $card_index Card index.
 * @param int    $tag_index  Tag index.
 * @param string $tag        Tag text.
 */
function sln_growth_page_render_case_studies_tag_row( $card_index, $tag_index, $tag ) {
	?>
	<div class="sln-gp-admin__cs-tag-row">
		<input
			type="text"
			class="regular-text"
			name="sln_gp_case_studies_cards[<?php echo esc_attr( $card_index ); ?>][tags][<?php echo esc_attr( $tag_index ); ?>]"
			value="<?php echo esc_attr( $tag ); ?>"
		/>
		<button type="button" class="button-link-delete sln-gp-admin__cs-remove-tag"><?php esc_html_e( 'Remove Tag', 'smart-leading-net' ); ?></button>
	</div>
	<?php
}

/**
 * Render a Case Studies card row in admin.
 *
 * @param int                  $index Card index.
 * @param array<string, mixed> $card  Card data.
 */
function sln_growth_page_render_case_studies_card_row( $index, $card ) {
	$card = wp_parse_args(
		$card,
		array(
			'title'              => '',
			'metric_value'       => '',
			'metric_description' => '',
			'icon_id'            => 0,
			'theme_color'        => '#1f4e9e',
			'tags'               => array(),
			'active'             => true,
		)
	);

	$tags = is_array( $card['tags'] ) ? $card['tags'] : array();
	?>
	<div class="sln-os-admin__repeatable-row sln-gp-admin__cs-card-row">
		<div class="sln-gp-admin__card-controls">
			<button type="button" class="button button-small sln-gp-admin__cs-move-up"><?php esc_html_e( 'Move Up', 'smart-leading-net' ); ?></button>
			<button type="button" class="button button-small sln-gp-admin__cs-move-down"><?php esc_html_e( 'Move Down', 'smart-leading-net' ); ?></button>
		</div>
		<div class="sln-os-admin__repeatable-fields sln-gp-admin__card-fields">
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Card Title', 'smart-leading-net' ); ?></span>
				<input type="text" class="large-text" name="sln_gp_case_studies_cards[<?php echo esc_attr( $index ); ?>][title]" value="<?php echo esc_attr( $card['title'] ); ?>" />
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Metric Value', 'smart-leading-net' ); ?></span>
				<input type="text" class="regular-text" name="sln_gp_case_studies_cards[<?php echo esc_attr( $index ); ?>][metric_value]" value="<?php echo esc_attr( $card['metric_value'] ); ?>" />
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Metric Description', 'smart-leading-net' ); ?></span>
				<input type="text" class="large-text" name="sln_gp_case_studies_cards[<?php echo esc_attr( $index ); ?>][metric_description]" value="<?php echo esc_attr( $card['metric_description'] ); ?>" />
			</label>
			<label class="sln-gp-admin__field-full">
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'SVG Icon Upload', 'smart-leading-net' ); ?></span>
				<?php
				sln_our_services_render_media_field(
					'sln_gp_case_studies_cards[' . $index . '][icon_id]',
					absint( $card['icon_id'] ),
					'SVG'
				);
				?>
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Card Theme Color', 'smart-leading-net' ); ?></span>
				<input type="color" class="sln-gp-admin__color-picker" name="sln_gp_case_studies_cards[<?php echo esc_attr( $index ); ?>][theme_color]" value="<?php echo esc_attr( sln_growth_page_sanitize_case_studies_color( $card['theme_color'] ) ); ?>" />
			</label>
			<div class="sln-os-admin__subsection sln-gp-admin__cs-tags-wrap">
				<h4><?php esc_html_e( 'Bottom Tags', 'smart-leading-net' ); ?></h4>
				<div class="sln-os-admin__repeatable sln-gp-admin__cs-tags-list" data-card-index="<?php echo esc_attr( $index ); ?>">
					<?php foreach ( $tags as $tag_index => $tag ) : ?>
						<?php sln_growth_page_render_case_studies_tag_row( $index, $tag_index, $tag ); ?>
					<?php endforeach; ?>
				</div>
				<p>
					<button type="button" class="button button-secondary sln-gp-admin__cs-add-tag" data-card-index="<?php echo esc_attr( $index ); ?>">
						<?php esc_html_e( 'Add Tag', 'smart-leading-net' ); ?>
					</button>
				</p>
			</div>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Active Card', 'smart-leading-net' ); ?></span>
				<select name="sln_gp_case_studies_cards[<?php echo esc_attr( $index ); ?>][active]">
					<option value="1" <?php selected( ! empty( $card['active'] ) ); ?>><?php esc_html_e( 'Yes', 'smart-leading-net' ); ?></option>
					<option value="0" <?php selected( empty( $card['active'] ) ); ?>><?php esc_html_e( 'No', 'smart-leading-net' ); ?></option>
				</select>
			</label>
		</div>
		<button type="button" class="button-link-delete sln-gp-admin__cs-remove-card"><?php esc_html_e( 'Remove Card', 'smart-leading-net' ); ?></button>
	</div>
	<?php
}

/**
 * Render Case Studies meta box.
 *
 * @param WP_Post $post Current post.
 */
function sln_growth_page_render_case_studies_meta_box( $post ) {
	wp_nonce_field( 'sln_growth_page_save_case_studies', 'sln_growth_page_case_studies_nonce', false );

	$defaults = sln_get_growth_page_default_case_studies_section();
	$section  = get_post_meta( $post->ID, SLN_GP_CASE_STUDIES_SECTION_META, true );
	$section  = is_array( $section ) ? array_intersect_key( wp_parse_args( $section, $defaults ), $defaults ) : $defaults;
	$cards    = sln_get_growth_page_case_studies_cards_for_admin( $post->ID );
	$orders   = sln_get_growth_page_section_orders( $post->ID );
	$order    = isset( $orders['case_studies'] ) ? absint( $orders['case_studies'] ) : 6;
	?>
	<div class="sln-gp-admin">
		<p class="description"><?php esc_html_e( 'Manage the Case Studies section content and ordering. Uses the same frontend design as the Home Page Case Studies section.', 'smart-leading-net' ); ?></p>

		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><label for="sln_gp_case_studies_label"><?php esc_html_e( 'Small Heading', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_case_studies_label" name="sln_gp_case_studies_section[label]" value="<?php echo esc_attr( $section['label'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_case_studies_main_heading"><?php esc_html_e( 'Main Heading', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_case_studies_main_heading" name="sln_gp_case_studies_section[main_heading]" value="<?php echo esc_attr( $section['main_heading'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_case_studies_highlight_word"><?php esc_html_e( 'Highlight Word', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_case_studies_highlight_word" name="sln_gp_case_studies_section[highlight_word]" value="<?php echo esc_attr( $section['highlight_word'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_case_studies_description"><?php esc_html_e( 'Section Description', 'smart-leading-net' ); ?></label></th>
					<td>
						<?php
						sln_growth_page_render_wysiwyg_editor(
							'sln_gp_case_studies_description',
							'sln_gp_case_studies_section[description]',
							$section['description']
						);
						?>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_case_studies_more_link_text"><?php esc_html_e( 'More Case Studies Text', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_case_studies_more_link_text" name="sln_gp_case_studies_section[more_link_text]" value="<?php echo esc_attr( $section['more_link_text'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_case_studies_more_link_url"><?php esc_html_e( 'More Case Studies URL', 'smart-leading-net' ); ?></label></th>
					<td><input type="url" class="large-text" id="sln_gp_case_studies_more_link_url" name="sln_gp_case_studies_section[more_link_url]" value="<?php echo esc_attr( $section['more_link_url'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_section_order_case_studies"><?php esc_html_e( 'Section Order Number', 'smart-leading-net' ); ?></label></th>
					<td>
						<input type="number" min="1" max="99" step="1" class="small-text" id="sln_gp_section_order_case_studies" name="sln_gp_section_orders[case_studies]" value="<?php echo esc_attr( $order ); ?>" />
					</td>
				</tr>
			</tbody>
		</table>

		<div class="sln-os-admin__subsection">
			<h3><?php esc_html_e( 'Case Study Cards', 'smart-leading-net' ); ?></h3>
			<div class="sln-os-admin__repeatable sln-gp-admin__cs-cards-list">
				<?php foreach ( $cards as $index => $card ) : ?>
					<?php sln_growth_page_render_case_studies_card_row( $index, $card ); ?>
				<?php endforeach; ?>
			</div>
			<p><button type="button" class="button button-secondary sln-gp-admin__add-cs-card"><?php esc_html_e( 'Add Card', 'smart-leading-net' ); ?></button></p>
		</div>
	</div>
	<?php
}

/**
 * Save Case Studies meta box values.
 *
 * @param int $post_id Post ID.
 */
function sln_growth_page_save_case_studies_meta( $post_id ) {
	if ( ! sln_growth_page_should_save_meta( $post_id, 'sln_growth_page_case_studies_nonce', 'sln_growth_page_save_case_studies' ) ) {
		return;
	}

	if ( isset( $_POST['sln_gp_case_studies_section'] ) && is_array( $_POST['sln_gp_case_studies_section'] ) ) {
		$raw     = wp_unslash( $_POST['sln_gp_case_studies_section'] );
		$section = array(
			'label'          => isset( $raw['label'] ) ? sanitize_text_field( $raw['label'] ) : '',
			'main_heading'   => isset( $raw['main_heading'] ) ? sanitize_text_field( $raw['main_heading'] ) : '',
			'highlight_word' => isset( $raw['highlight_word'] ) ? sanitize_text_field( $raw['highlight_word'] ) : '',
			'description'    => isset( $raw['description'] ) ? sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ) : '',
			'more_link_text' => isset( $raw['more_link_text'] ) ? sanitize_text_field( $raw['more_link_text'] ) : '',
			'more_link_url'  => isset( $raw['more_link_url'] ) ? esc_url_raw( $raw['more_link_url'] ) : '',
		);

		update_post_meta( $post_id, SLN_GP_CASE_STUDIES_SECTION_META, $section );
	}

	sln_growth_page_update_repeater_meta(
		$post_id,
		SLN_GP_CASE_STUDIES_CARDS_META,
		'sln_gp_case_studies_cards',
		'sln_sanitize_growth_page_case_studies_card',
		static function ( $card ) {
			return '' !== trim( $card['title'] )
				|| '' !== trim( $card['metric_value'] )
				|| '' !== trim( $card['metric_description'] );
		}
	);
}
