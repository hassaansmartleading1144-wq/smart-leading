<?php
/**
 * Growth Pages — Services Section meta box and helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_GP_SERVICES_SECTION_META', '_sln_gp_services_section' );
define( 'SLN_GP_SERVICES_CARDS_META', '_sln_gp_services_cards' );

/**
 * Default services section content.
 *
 * @return array<string, string>
 */
function sln_get_growth_page_default_services_section() {
	return array(
		'label'                   => __( 'Our Services', 'smart-leading-net' ),
		'heading_lead'            => __( 'Designed To', 'smart-leading-net' ),
		'heading_accent_primary'  => __( 'Attract', 'smart-leading-net' ),
		'heading_accent_secondary'=> __( 'Convert And Scale', 'smart-leading-net' ),
		'description'             => __( 'From visibility to conversions, our Revenue Growth services help businesses grow through smarter strategy, stronger campaigns, and measurable results.', 'smart-leading-net' ),
	);
}

/**
 * Default service cards seeded from the original static content.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_get_growth_page_default_service_cards() {
	return array(
		array(
			'number'      => '01',
			'title'       => __( 'SEO', 'smart-leading-net' ),
			'description' => __( 'We build long-term organic visibility that drives consistent, high-intent traffic to your site. Our SEO strategies are rooted in data, engineered around your buyers, and designed to generate compounding revenue over time.', 'smart-leading-net' ),
			'bullet_1'    => __( 'Technical SEO audits & on-page optimization', 'smart-leading-net' ),
			'bullet_2'    => __( 'High-intent keyword research & content strategy', 'smart-leading-net' ),
			'bullet_3'    => __( 'Authority building through backlinks & outreach', 'smart-leading-net' ),
			'bullet_4'    => '',
			'active'      => true,
		),
		array(
			'number'      => '02',
			'title'       => __( 'Google Ads', 'smart-leading-net' ),
			'description' => __( 'We transform search intent into high-value conversions. Through precision keyword targeting and aggressive bid management, we ensure your brand captures active buyers at the exact moment they\'re ready to purchase.', 'smart-leading-net' ),
			'bullet_1'    => __( 'High-intent keyword targeting & precision bidding', 'smart-leading-net' ),
			'bullet_2'    => __( 'Continuous A/B testing of ad copy & extensions', 'smart-leading-net' ),
			'bullet_3'    => __( 'Full campaign audits & competitor gap analysis', 'smart-leading-net' ),
			'bullet_4'    => '',
			'active'      => true,
		),
		array(
			'number'      => '03',
			'title'       => __( 'Meta Ads', 'smart-leading-net' ),
			'description' => __( 'We turn cold traffic into predictable growth using high-converting Meta advertising strategies. Our data-driven creatives stop the scroll, nurture prospects through seamless sequences, and lower your customer acquisition cost.', 'smart-leading-net' ),
			'bullet_1'    => __( 'Scroll-stopping Meta & Instagram ad creatives', 'smart-leading-net' ),
			'bullet_2'    => __( 'Audience segmentation & lookalike targeting', 'smart-leading-net' ),
			'bullet_3'    => __( 'Lower customer acquisition cost (CAC) guaranteed', 'smart-leading-net' ),
			'bullet_4'    => '',
			'active'      => true,
		),
		array(
			'number'      => '04',
			'title'       => __( 'CRO', 'smart-leading-net' ),
			'description' => __( 'We maximize the value of your existing traffic by removing friction and optimizing every stage of the buyer journey. More conversions from the same spend means a better ROI across all your marketing channels.', 'smart-leading-net' ),
			'bullet_1'    => __( 'Landing page audits & conversion flow optimization', 'smart-leading-net' ),
			'bullet_2'    => __( 'A/B testing of headlines, CTAs & page layouts', 'smart-leading-net' ),
			'bullet_3'    => __( 'UX improvements to reduce drop-off & bounce rates', 'smart-leading-net' ),
			'bullet_4'    => '',
			'active'      => true,
		),
		array(
			'number'      => '05',
			'title'       => __( 'Analytics & Attribution', 'smart-leading-net' ),
			'description' => __( 'Stop guessing and start scaling with absolute clarity. We synchronize your ad platforms with your CRM to give you full visibility into which campaigns drive real revenue — not just traffic or clicks.', 'smart-leading-net' ),
			'bullet_1'    => __( 'CRM & ad platform synchronization', 'smart-leading-net' ),
			'bullet_2'    => __( 'Track exactly which campaigns close revenue', 'smart-leading-net' ),
			'bullet_3'    => __( 'Real-time dashboards & performance reporting', 'smart-leading-net' ),
			'bullet_4'    => '',
			'active'      => true,
		),
		array(
			'number'      => '06',
			'title'       => __( 'E-commerce Marketing', 'smart-leading-net' ),
			'description' => __( 'We build complete e-commerce growth systems — from product visibility and paid acquisition to checkout optimization. Every touchpoint is engineered to turn browsers into loyal, high-value customers.', 'smart-leading-net' ),
			'bullet_1'    => __( 'Product feed optimization & shopping ads management', 'smart-leading-net' ),
			'bullet_2'    => __( 'Checkout funnel optimization & cart recovery', 'smart-leading-net' ),
			'bullet_3'    => __( 'Retargeting campaigns to recapture lost revenue', 'smart-leading-net' ),
			'bullet_4'    => '',
			'active'      => true,
		),
	);
}

/**
 * Sanitize a single service card row.
 *
 * @param array<string, mixed> $card Raw card data.
 * @return array<string, mixed>
 */
function sln_sanitize_growth_page_service_card( $card ) {
	if ( ! is_array( $card ) ) {
		return array();
	}

	$number = isset( $card['number'] ) ? sanitize_text_field( wp_unslash( $card['number'] ) ) : '';
	$number = preg_replace( '/[^0-9]/', '', $number );
	$number = '' !== $number ? str_pad( $number, 2, '0', STR_PAD_LEFT ) : '';

	return array(
		'number'      => $number,
		'title'       => isset( $card['title'] ) ? sanitize_text_field( wp_unslash( $card['title'] ) ) : '',
		'description' => isset( $card['description'] ) ? sln_growth_page_sanitize_wysiwyg_content( $card['description'] ) : '',
		'bullet_1'    => isset( $card['bullet_1'] ) ? sanitize_text_field( wp_unslash( $card['bullet_1'] ) ) : '',
		'bullet_2'    => isset( $card['bullet_2'] ) ? sanitize_text_field( wp_unslash( $card['bullet_2'] ) ) : '',
		'bullet_3'    => isset( $card['bullet_3'] ) ? sanitize_text_field( wp_unslash( $card['bullet_3'] ) ) : '',
		'bullet_4'    => isset( $card['bullet_4'] ) ? sanitize_text_field( wp_unslash( $card['bullet_4'] ) ) : '',
		'active'      => ! empty( $card['active'] ),
	);
}

/**
 * Get services section data for a Growth Page.
 *
 * @param int|null $post_id Post ID.
 * @return array<string, mixed>
 */
function sln_get_growth_page_services( $post_id = null ) {
	$post_id  = $post_id ? absint( $post_id ) : get_the_ID();
	$defaults = sln_get_growth_page_default_services_section();

	$section = sln_growth_page_get_section_settings( $post_id, SLN_GP_SERVICES_SECTION_META, $defaults );
	$cards   = sln_growth_page_get_repeater_rows( $post_id, SLN_GP_SERVICES_CARDS_META, sln_get_growth_page_default_service_cards() );

	$active_cards = array();

	foreach ( $cards as $index => $card ) {
		if ( ! is_array( $card ) ) {
			continue;
		}

		$card = wp_parse_args(
			$card,
			array(
				'number'      => str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ),
				'title'       => '',
				'description' => '',
				'bullet_1'    => '',
				'bullet_2'    => '',
				'bullet_3'    => '',
				'bullet_4'    => '',
				'active'      => true,
			)
		);

		if ( empty( $card['active'] ) ) {
			continue;
		}

		if ( '' === trim( $card['title'] ) && ! sln_growth_page_wysiwyg_has_content( $card['description'] ) ) {
			continue;
		}

		$bullets = array_values(
			array_filter(
				array(
					$card['bullet_1'],
					$card['bullet_2'],
					$card['bullet_3'],
					$card['bullet_4'],
				),
				static function ( $bullet ) {
					return '' !== trim( (string) $bullet );
				}
			)
		);

		$active_cards[] = array(
			'number'      => '' !== trim( $card['number'] ) ? $card['number'] : str_pad( (string) ( count( $active_cards ) + 1 ), 2, '0', STR_PAD_LEFT ),
			'title'       => $card['title'],
			'description' => $card['description'],
			'features'    => $bullets,
		);
	}

	return array(
		'label'                    => $section['label'],
		'heading_lead'             => $section['heading_lead'],
		'heading_accent_primary'   => $section['heading_accent_primary'],
		'heading_accent_secondary' => $section['heading_accent_secondary'],
		'description'              => $section['description'],
		'cards'                    => $active_cards,
	);
}

/**
 * Get raw service cards for admin editing.
 *
 * @param int $post_id Post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_growth_page_service_cards_for_admin( $post_id ) {
	if ( ! metadata_exists( 'post', $post_id, SLN_GP_SERVICES_CARDS_META ) ) {
		return sln_get_growth_page_default_service_cards();
	}

	$cards = get_post_meta( $post_id, SLN_GP_SERVICES_CARDS_META, true );

	return is_array( $cards ) ? $cards : array();
}

/**
 * Register Services Section meta box.
 */
function sln_growth_page_register_services_meta_box() {
	add_meta_box(
		'sln-growth-page-services',
		__( 'Services Section', 'smart-leading-net' ),
		'sln_growth_page_render_services_meta_box',
		SLN_GROWTH_PAGE_POST_TYPE,
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'sln_growth_page_register_services_meta_box' );

/**
 * Render a single service card row in admin.
 *
 * @param int                  $index Card index.
 * @param array<string, mixed> $card  Card data.
 */
function sln_growth_page_render_service_card_row( $index, $card ) {
	$card = wp_parse_args(
		$card,
		array(
			'number'      => str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ),
			'title'       => '',
			'description' => '',
			'bullet_1'    => '',
			'bullet_2'    => '',
			'bullet_3'    => '',
			'bullet_4'    => '',
			'active'      => true,
		)
	);
	?>
	<div class="sln-os-admin__repeatable-row sln-gp-admin__card-row">
		<div class="sln-gp-admin__card-controls">
			<button type="button" class="button button-small sln-gp-admin__move-up" aria-label="<?php esc_attr_e( 'Move card up', 'smart-leading-net' ); ?>">
				<?php esc_html_e( 'Move Up', 'smart-leading-net' ); ?>
			</button>
			<button type="button" class="button button-small sln-gp-admin__move-down" aria-label="<?php esc_attr_e( 'Move card down', 'smart-leading-net' ); ?>">
				<?php esc_html_e( 'Move Down', 'smart-leading-net' ); ?>
			</button>
		</div>

		<div class="sln-os-admin__repeatable-fields sln-gp-admin__card-fields">
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Card Number', 'smart-leading-net' ); ?></span>
				<input type="text" class="small-text" name="sln_gp_services_cards[<?php echo esc_attr( $index ); ?>][number]" value="<?php echo esc_attr( $card['number'] ); ?>" placeholder="01" />
			</label>

			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Service Title', 'smart-leading-net' ); ?></span>
				<input type="text" class="regular-text" name="sln_gp_services_cards[<?php echo esc_attr( $index ); ?>][title]" value="<?php echo esc_attr( $card['title'] ); ?>" />
			</label>

			<label class="sln-gp-admin__field-full sln-gp-admin__editor-field">
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Service Description', 'smart-leading-net' ); ?></span>
				<?php
				sln_growth_page_render_wysiwyg_editor(
					'sln_gp_service_description_' . $index,
					'sln_gp_services_cards[' . $index . '][description]',
					$card['description']
				);
				?>
			</label>

			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Bullet Point 1', 'smart-leading-net' ); ?></span>
				<input type="text" class="large-text" name="sln_gp_services_cards[<?php echo esc_attr( $index ); ?>][bullet_1]" value="<?php echo esc_attr( $card['bullet_1'] ); ?>" />
			</label>

			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Bullet Point 2', 'smart-leading-net' ); ?></span>
				<input type="text" class="large-text" name="sln_gp_services_cards[<?php echo esc_attr( $index ); ?>][bullet_2]" value="<?php echo esc_attr( $card['bullet_2'] ); ?>" />
			</label>

			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Bullet Point 3', 'smart-leading-net' ); ?></span>
				<input type="text" class="large-text" name="sln_gp_services_cards[<?php echo esc_attr( $index ); ?>][bullet_3]" value="<?php echo esc_attr( $card['bullet_3'] ); ?>" />
			</label>

			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Bullet Point 4', 'smart-leading-net' ); ?></span>
				<input type="text" class="large-text" name="sln_gp_services_cards[<?php echo esc_attr( $index ); ?>][bullet_4]" value="<?php echo esc_attr( $card['bullet_4'] ); ?>" />
			</label>

			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Active Card', 'smart-leading-net' ); ?></span>
				<select name="sln_gp_services_cards[<?php echo esc_attr( $index ); ?>][active]">
					<option value="1" <?php selected( ! empty( $card['active'] ) ); ?>><?php esc_html_e( 'Yes', 'smart-leading-net' ); ?></option>
					<option value="0" <?php selected( empty( $card['active'] ) ); ?>><?php esc_html_e( 'No', 'smart-leading-net' ); ?></option>
				</select>
			</label>
		</div>

		<button type="button" class="button-link-delete sln-gp-admin__remove-card"><?php esc_html_e( 'Remove Card', 'smart-leading-net' ); ?></button>
	</div>
	<?php
}

/**
 * Render Services Section meta box.
 *
 * @param WP_Post $post Current post.
 */
function sln_growth_page_render_services_meta_box( $post ) {
	wp_nonce_field( 'sln_growth_page_save_services', 'sln_growth_page_services_nonce', false );

	$section     = get_post_meta( $post->ID, SLN_GP_SERVICES_SECTION_META, true );
	$section     = is_array( $section ) ? wp_parse_args( $section, sln_get_growth_page_default_services_section() ) : sln_get_growth_page_default_services_section();
	$cards       = sln_get_growth_page_service_cards_for_admin( $post->ID );
	$orders      = sln_get_growth_page_section_orders( $post->ID );
	$services_order = isset( $orders['services'] ) ? absint( $orders['services'] ) : 2;
	?>
	<div class="sln-gp-admin">
		<p class="description">
			<?php esc_html_e( 'Manage the Convert And Scale services section. Cards render dynamically on the frontend based on the active cards below.', 'smart-leading-net' ); ?>
		</p>

		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><label for="sln_gp_services_label"><?php esc_html_e( 'Section Title Small', 'smart-leading-net' ); ?></label></th>
					<td>
						<input type="text" class="large-text" id="sln_gp_services_label" name="sln_gp_services_section[label]" value="<?php echo esc_attr( $section['label'] ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Section Main Heading', 'smart-leading-net' ); ?></th>
					<td>
						<p>
							<label for="sln_gp_services_heading_lead"><?php esc_html_e( 'Lead Text', 'smart-leading-net' ); ?></label><br />
							<input type="text" class="large-text" id="sln_gp_services_heading_lead" name="sln_gp_services_section[heading_lead]" value="<?php echo esc_attr( $section['heading_lead'] ); ?>" />
						</p>
						<p>
							<label for="sln_gp_services_heading_accent_primary"><?php esc_html_e( 'Primary Accent (Blue)', 'smart-leading-net' ); ?></label><br />
							<input type="text" class="regular-text" id="sln_gp_services_heading_accent_primary" name="sln_gp_services_section[heading_accent_primary]" value="<?php echo esc_attr( $section['heading_accent_primary'] ); ?>" />
						</p>
						<p>
							<label for="sln_gp_services_heading_accent_secondary"><?php esc_html_e( 'Secondary Accent (Orange)', 'smart-leading-net' ); ?></label><br />
							<input type="text" class="regular-text" id="sln_gp_services_heading_accent_secondary" name="sln_gp_services_section[heading_accent_secondary]" value="<?php echo esc_attr( $section['heading_accent_secondary'] ); ?>" />
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_services_description"><?php esc_html_e( 'Section Description', 'smart-leading-net' ); ?></label></th>
					<td>
						<?php
						sln_growth_page_render_wysiwyg_editor(
							'sln_gp_services_description',
							'sln_gp_services_section[description]',
							$section['description']
						);
						?>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_section_order_services"><?php esc_html_e( 'Section Order Number', 'smart-leading-net' ); ?></label></th>
					<td>
						<input type="number" min="1" max="99" step="1" class="small-text" id="sln_gp_section_order_services" name="sln_gp_section_orders[services]" value="<?php echo esc_attr( $services_order ); ?>" />
						<p class="description"><?php esc_html_e( 'Controls where the Services section appears relative to other Growth Page sections.', 'smart-leading-net' ); ?></p>
					</td>
				</tr>
			</tbody>
		</table>

		<div class="sln-os-admin__subsection">
			<h3><?php esc_html_e( 'Service Cards', 'smart-leading-net' ); ?></h3>
			<p class="description"><?php esc_html_e( 'Add, remove, and reorder cards. Only active cards with content appear on the frontend.', 'smart-leading-net' ); ?></p>

			<div class="sln-os-admin__repeatable sln-gp-admin__cards-list">
				<?php foreach ( $cards as $index => $card ) : ?>
					<?php sln_growth_page_render_service_card_row( $index, $card ); ?>
				<?php endforeach; ?>
			</div>

			<p>
				<button type="button" class="button button-secondary sln-gp-admin__add-card">
					<?php esc_html_e( 'Add New Card', 'smart-leading-net' ); ?>
				</button>
			</p>
		</div>
	</div>
	<?php
}

/**
 * Save Services Section meta box values.
 *
 * @param int $post_id Post ID.
 */
function sln_growth_page_save_services_meta( $post_id ) {
	if ( ! sln_growth_page_should_save_meta( $post_id, 'sln_growth_page_services_nonce', 'sln_growth_page_save_services' ) ) {
		return;
	}

	if ( isset( $_POST['sln_gp_services_section'] ) && is_array( $_POST['sln_gp_services_section'] ) ) {
		$section_raw = wp_unslash( $_POST['sln_gp_services_section'] );
		$section     = array(
			'label'                    => isset( $section_raw['label'] ) ? sanitize_text_field( $section_raw['label'] ) : '',
			'heading_lead'             => isset( $section_raw['heading_lead'] ) ? sanitize_text_field( $section_raw['heading_lead'] ) : '',
			'heading_accent_primary'   => isset( $section_raw['heading_accent_primary'] ) ? sanitize_text_field( $section_raw['heading_accent_primary'] ) : '',
			'heading_accent_secondary' => isset( $section_raw['heading_accent_secondary'] ) ? sanitize_text_field( $section_raw['heading_accent_secondary'] ) : '',
			'description'              => isset( $section_raw['description'] ) ? sln_growth_page_sanitize_wysiwyg_content( $section_raw['description'] ) : '',
		);

		update_post_meta( $post_id, SLN_GP_SERVICES_SECTION_META, $section );
	}

	sln_growth_page_update_repeater_meta(
		$post_id,
		SLN_GP_SERVICES_CARDS_META,
		'sln_gp_services_cards',
		'sln_sanitize_growth_page_service_card',
		static function ( $card ) {
			return '' !== trim( $card['title'] ) || sln_growth_page_wysiwyg_has_content( $card['description'] );
		}
	);
}
