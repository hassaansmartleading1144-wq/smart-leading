<?php
/**
 * Growth Pages — Growth Services section meta box and helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_GP_GROWTH_SERVICES_SECTION_META', '_sln_gp_growth_services_section' );
define( 'SLN_GP_GROWTH_SERVICES_CARDS_META', '_sln_gp_growth_services_cards' );

/**
 * Default Growth Services section content.
 *
 * @return array<string, string>
 */
function sln_get_growth_page_default_growth_services_section() {
	return array(
		'label'            => __( 'Why Smart Leading', 'smart-leading-net' ),
		'heading_lead'     => __( 'How Our', 'smart-leading-net' ),
		'highlight_word_1' => __( 'Revenue Growth', 'smart-leading-net' ),
		'heading_trail'    => __( 'Services', 'smart-leading-net' ),
		'highlight_word_2' => __( 'Drive More Revenue', 'smart-leading-net' ),
		'description'      => __( 'With Smart Leading as your revenue growth partner, you get access to a team obsessed with one thing: turning your marketing spend into predictable, scalable business growth.', 'smart-leading-net' ),
	);
}

/**
 * Default Growth Services card.
 *
 * @param string $title       Card title.
 * @param string $description Card description.
 * @return array<string, mixed>
 */
function sln_get_growth_page_default_growth_services_card( $title, $description = '' ) {
	return array(
		'icon_id'     => 0,
		'title'       => $title,
		'description' => $description,
		'active'      => true,
	);
}

/**
 * Default Growth Services cards.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_get_growth_page_default_growth_services_cards() {
	return array(
		sln_get_growth_page_default_growth_services_card(
			__( 'Custom Growth Strategies', 'smart-leading-net' ),
			__( 'Tailored revenue plans built around your market, margins, and growth goals — not generic templates.', 'smart-leading-net' )
		),
		sln_get_growth_page_default_growth_services_card(
			__( 'Dedicated Growth Team', 'smart-leading-net' ),
			__( 'Specialists across paid media, CRO, and analytics working as an extension of your business.', 'smart-leading-net' )
		),
		sln_get_growth_page_default_growth_services_card(
			__( 'Full-Funnel ROI Tracking', 'smart-leading-net' ),
			__( 'Clear attribution from first click to closed revenue so you know exactly what is working.', 'smart-leading-net' )
		),
		sln_get_growth_page_default_growth_services_card(
			__( 'Transparent Partnership', 'smart-leading-net' ),
			__( 'Open reporting, honest recommendations, and proactive communication at every stage.', 'smart-leading-net' )
		),
		sln_get_growth_page_default_growth_services_card(
			__( 'Advanced Ad Technology', 'smart-leading-net' ),
			__( 'Modern campaign architecture, testing frameworks, and optimization systems that scale efficiently.', 'smart-leading-net' )
		),
		sln_get_growth_page_default_growth_services_card(
			__( 'Google Partner Certified', 'smart-leading-net' ),
			__( 'Certified expertise backed by platform standards, best practices, and proven performance.', 'smart-leading-net' )
		),
	);
}

/**
 * Sanitize a Growth Services card row.
 *
 * @param array<string, mixed> $card Raw card data.
 * @return array<string, mixed>
 */
function sln_sanitize_growth_page_growth_services_card( $card ) {
	if ( ! is_array( $card ) ) {
		return array();
	}

	$icon_id = sln_sanitize_media_attachment_id( $card['icon_id'] ?? 0, array( 'image/svg+xml' ) );

	return array(
		'icon_id'     => $icon_id,
		'title'       => isset( $card['title'] ) ? sanitize_text_field( wp_unslash( $card['title'] ) ) : '',
		'description' => isset( $card['description'] ) ? sln_growth_page_sanitize_wysiwyg_content( $card['description'] ) : '',
		'active'      => ! empty( $card['active'] ),
	);
}

/**
 * Get Growth Services section data for frontend.
 *
 * @param int|null $post_id Post ID.
 * @return array<string, mixed>
 */
function sln_get_growth_page_growth_services( $post_id = null ) {
	$post_id  = $post_id ? absint( $post_id ) : get_the_ID();
	$defaults = sln_get_growth_page_default_growth_services_section();

	$section = sln_growth_page_get_section_settings( $post_id, SLN_GP_GROWTH_SERVICES_SECTION_META, $defaults );
	$cards   = sln_growth_page_get_repeater_rows( $post_id, SLN_GP_GROWTH_SERVICES_CARDS_META, sln_get_growth_page_default_growth_services_cards() );

	$active_cards = array();

	foreach ( $cards as $card ) {
		if ( ! is_array( $card ) ) {
			continue;
		}

		$card = sln_sanitize_growth_page_growth_services_card( $card );

		if ( empty( $card['active'] ) ) {
			continue;
		}

		if ( '' === trim( $card['title'] ) && ! sln_growth_page_wysiwyg_has_content( $card['description'] ) ) {
			continue;
		}

		$active_cards[] = array(
			'icon_id'     => $card['icon_id'],
			'title'       => $card['title'],
			'description' => $card['description'],
		);
	}

	return array(
		'label'            => $section['label'],
		'heading_lead'     => $section['heading_lead'],
		'highlight_word_1' => $section['highlight_word_1'],
		'heading_trail'    => $section['heading_trail'],
		'highlight_word_2' => $section['highlight_word_2'],
		'description'      => $section['description'],
		'cards'            => $active_cards,
	);
}

/**
 * Check whether Growth Services section should render.
 *
 * @param int|null $post_id Post ID.
 * @return bool
 */
function sln_growth_page_growth_services_has_content( $post_id = null ) {
	$data = sln_get_growth_page_growth_services( $post_id );

	return ! empty( $data['cards'] );
}

/**
 * Get raw Growth Services cards for admin.
 *
 * @param int $post_id Post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_growth_page_growth_services_cards_for_admin( $post_id ) {
	if ( ! metadata_exists( 'post', $post_id, SLN_GP_GROWTH_SERVICES_CARDS_META ) ) {
		return sln_get_growth_page_default_growth_services_cards();
	}

	$cards = get_post_meta( $post_id, SLN_GP_GROWTH_SERVICES_CARDS_META, true );

	return is_array( $cards ) ? $cards : array();
}

/**
 * Register Growth Services meta box.
 */
function sln_growth_page_register_growth_services_meta_box() {
	add_meta_box(
		'sln-growth-page-growth-services',
		__( 'Growth Services Section', 'smart-leading-net' ),
		'sln_growth_page_render_growth_services_meta_box',
		SLN_GROWTH_PAGE_POST_TYPE,
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'sln_growth_page_register_growth_services_meta_box' );

/**
 * Render a Growth Services card row in admin.
 *
 * @param int                  $index Card index.
 * @param array<string, mixed> $card  Card data.
 */
function sln_growth_page_render_growth_services_card_row( $index, $card ) {
	$card = wp_parse_args(
		$card,
		array(
			'icon_id'     => 0,
			'title'       => '',
			'description' => '',
			'active'      => true,
		)
	);
	?>
	<div class="sln-os-admin__repeatable-row sln-gp-admin__gs-card-row">
		<div class="sln-gp-admin__card-controls">
			<button type="button" class="button button-small sln-gp-admin__gs-move-up"><?php esc_html_e( 'Move Up', 'smart-leading-net' ); ?></button>
			<button type="button" class="button button-small sln-gp-admin__gs-move-down"><?php esc_html_e( 'Move Down', 'smart-leading-net' ); ?></button>
		</div>
		<div class="sln-os-admin__repeatable-fields sln-gp-admin__card-fields">
			<label class="sln-gp-admin__field-full">
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'SVG Icon Upload', 'smart-leading-net' ); ?></span>
				<?php
				sln_our_services_render_media_field(
					'sln_gp_growth_services_cards[' . $index . '][icon_id]',
					absint( $card['icon_id'] ),
					'SVG'
				);
				?>
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Card Title', 'smart-leading-net' ); ?></span>
				<input type="text" class="large-text" name="sln_gp_growth_services_cards[<?php echo esc_attr( $index ); ?>][title]" value="<?php echo esc_attr( $card['title'] ); ?>" />
			</label>
			<label class="sln-gp-admin__field-full sln-gp-admin__editor-field">
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Card Description', 'smart-leading-net' ); ?></span>
				<?php
				sln_growth_page_render_wysiwyg_editor(
					'growth_service_card_desc_' . $index,
					'sln_gp_growth_services_cards[' . $index . '][description]',
					$card['description'],
					array(
						'textarea_rows' => 6,
					)
				);
				?>
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Active Card', 'smart-leading-net' ); ?></span>
				<select name="sln_gp_growth_services_cards[<?php echo esc_attr( $index ); ?>][active]">
					<option value="1" <?php selected( ! empty( $card['active'] ) ); ?>><?php esc_html_e( 'Yes', 'smart-leading-net' ); ?></option>
					<option value="0" <?php selected( empty( $card['active'] ) ); ?>><?php esc_html_e( 'No', 'smart-leading-net' ); ?></option>
				</select>
			</label>
		</div>
		<button type="button" class="button-link-delete sln-gp-admin__gs-remove-card"><?php esc_html_e( 'Remove Card', 'smart-leading-net' ); ?></button>
	</div>
	<?php
}

/**
 * Render Growth Services meta box.
 *
 * @param WP_Post $post Current post.
 */
function sln_growth_page_render_growth_services_meta_box( $post ) {
	wp_nonce_field( 'sln_growth_page_save_growth_services', 'sln_growth_page_growth_services_nonce', false );

	$defaults = sln_get_growth_page_default_growth_services_section();
	$section  = get_post_meta( $post->ID, SLN_GP_GROWTH_SERVICES_SECTION_META, true );
	$section  = is_array( $section ) ? array_intersect_key( wp_parse_args( $section, $defaults ), $defaults ) : $defaults;
	$cards    = sln_get_growth_page_growth_services_cards_for_admin( $post->ID );
	$orders   = sln_get_growth_page_section_orders( $post->ID );
	$order    = isset( $orders['growth_services'] ) ? absint( $orders['growth_services'] ) : 5;
	?>
	<div class="sln-gp-admin">
		<p class="description"><?php esc_html_e( 'Manage the Growth Services card grid section content and ordering.', 'smart-leading-net' ); ?></p>

		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><label for="sln_gp_growth_services_label"><?php esc_html_e( 'Section Small Heading', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_growth_services_label" name="sln_gp_growth_services_section[label]" value="<?php echo esc_attr( $section['label'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Section Main Heading', 'smart-leading-net' ); ?></th>
					<td>
						<p><label for="sln_gp_growth_services_heading_lead"><?php esc_html_e( 'Lead Text', 'smart-leading-net' ); ?></label><br />
						<input type="text" class="large-text" id="sln_gp_growth_services_heading_lead" name="sln_gp_growth_services_section[heading_lead]" value="<?php echo esc_attr( $section['heading_lead'] ); ?>" /></p>
						<p><label for="sln_gp_growth_services_highlight_word_1"><?php esc_html_e( 'Highlight Word 1 (Blue)', 'smart-leading-net' ); ?></label><br />
						<input type="text" class="regular-text" id="sln_gp_growth_services_highlight_word_1" name="sln_gp_growth_services_section[highlight_word_1]" value="<?php echo esc_attr( $section['highlight_word_1'] ); ?>" /></p>
						<p><label for="sln_gp_growth_services_heading_trail"><?php esc_html_e( 'Middle Text', 'smart-leading-net' ); ?></label><br />
						<input type="text" class="regular-text" id="sln_gp_growth_services_heading_trail" name="sln_gp_growth_services_section[heading_trail]" value="<?php echo esc_attr( $section['heading_trail'] ); ?>" /></p>
						<p><label for="sln_gp_growth_services_highlight_word_2"><?php esc_html_e( 'Highlight Word 2 (Orange)', 'smart-leading-net' ); ?></label><br />
						<input type="text" class="regular-text" id="sln_gp_growth_services_highlight_word_2" name="sln_gp_growth_services_section[highlight_word_2]" value="<?php echo esc_attr( $section['highlight_word_2'] ); ?>" /></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_growth_services_description"><?php esc_html_e( 'Section Description', 'smart-leading-net' ); ?></label></th>
					<td>
						<?php
						sln_growth_page_render_wysiwyg_editor(
							'sln_gp_growth_services_description',
							'sln_gp_growth_services_section[description]',
							$section['description']
						);
						?>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_section_order_growth_services"><?php esc_html_e( 'Section Order Number', 'smart-leading-net' ); ?></label></th>
					<td>
						<input type="number" min="1" max="99" step="1" class="small-text" id="sln_gp_section_order_growth_services" name="sln_gp_section_orders[growth_services]" value="<?php echo esc_attr( $order ); ?>" />
					</td>
				</tr>
			</tbody>
		</table>

		<div class="sln-os-admin__subsection">
			<h3><?php esc_html_e( 'Service Cards', 'smart-leading-net' ); ?></h3>
			<div class="sln-os-admin__repeatable sln-gp-admin__gs-cards-list">
				<?php foreach ( $cards as $index => $card ) : ?>
					<?php sln_growth_page_render_growth_services_card_row( $index, $card ); ?>
				<?php endforeach; ?>
			</div>
			<p><button type="button" class="button button-secondary sln-gp-admin__add-gs-card"><?php esc_html_e( 'Add Card', 'smart-leading-net' ); ?></button></p>
		</div>
	</div>
	<?php
}

/**
 * Save Growth Services meta box values.
 *
 * @param int $post_id Post ID.
 */
function sln_growth_page_save_growth_services_meta( $post_id ) {
	if ( ! sln_growth_page_should_save_meta( $post_id, 'sln_growth_page_growth_services_nonce', 'sln_growth_page_save_growth_services' ) ) {
		return;
	}

	if ( isset( $_POST['sln_gp_growth_services_section'] ) && is_array( $_POST['sln_gp_growth_services_section'] ) ) {
		$raw     = wp_unslash( $_POST['sln_gp_growth_services_section'] );
		$section = array(
			'label'            => isset( $raw['label'] ) ? sanitize_text_field( $raw['label'] ) : '',
			'heading_lead'     => isset( $raw['heading_lead'] ) ? sanitize_text_field( $raw['heading_lead'] ) : '',
			'highlight_word_1' => isset( $raw['highlight_word_1'] ) ? sanitize_text_field( $raw['highlight_word_1'] ) : '',
			'heading_trail'    => isset( $raw['heading_trail'] ) ? sanitize_text_field( $raw['heading_trail'] ) : '',
			'highlight_word_2' => isset( $raw['highlight_word_2'] ) ? sanitize_text_field( $raw['highlight_word_2'] ) : '',
			'description'      => isset( $raw['description'] ) ? sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ) : '',
		);

		update_post_meta( $post_id, SLN_GP_GROWTH_SERVICES_SECTION_META, $section );
	}

	sln_growth_page_update_repeater_meta(
		$post_id,
		SLN_GP_GROWTH_SERVICES_CARDS_META,
		'sln_gp_growth_services_cards',
		'sln_sanitize_growth_page_growth_services_card',
		static function ( $card ) {
			return '' !== trim( $card['title'] ) || sln_growth_page_wysiwyg_has_content( $card['description'] );
		}
	);
}
