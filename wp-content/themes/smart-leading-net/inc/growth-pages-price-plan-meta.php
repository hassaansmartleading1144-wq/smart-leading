<?php
/**
 * Growth Pages — Price Plan section meta box and helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_GP_PRICE_PLAN_SECTION_META', '_sln_gp_price_plan_section' );
define( 'SLN_GP_PRICE_PLAN_CARDS_META', '_sln_gp_price_plan_cards' );

/**
 * Default Price Plan section content.
 *
 * @return array<string, string>
 */
function sln_get_growth_page_default_price_plan_section() {
	return array(
		'label'          => __( 'Pricing Plans', 'smart-leading-net' ),
		'heading_lead'   => __( 'Transparent', 'smart-leading-net' ),
		'highlight_word' => __( 'Revenue Growth Pricing', 'smart-leading-net' ),
		'heading_trail'  => '',
		'description'    => __( 'Tailored to your business goals. Whether you\'re just starting or ready to scale aggressively, we have a plan designed to turn your marketing spend into predictable revenue.', 'smart-leading-net' ),
	);
}

/**
 * Default pricing card.
 *
 * @param array<string, mixed> $args Card defaults.
 * @return array<string, mixed>
 */
function sln_get_growth_page_default_price_plan_card( $args ) {
	$defaults = array(
		'plan_name'    => '',
		'price'        => '',
		'price_suffix' => '',
		'description'  => '',
		'features'     => array(),
		'button_text'  => '',
		'button_url'   => '',
		'is_popular'   => false,
		'badge_text'   => __( 'MOST POPULAR', 'smart-leading-net' ),
		'active'       => true,
	);

	return wp_parse_args( $args, $defaults );
}

/**
 * Default Price Plan cards.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_get_growth_page_default_price_plan_cards() {
	return array(
		sln_get_growth_page_default_price_plan_card(
			array(
				'plan_name'    => __( 'BASIC', 'smart-leading-net' ),
				'price'        => '$999',
				'price_suffix' => __( '/ month', 'smart-leading-net' ),
				'description'  => __( 'Essential paid media management for businesses ready to start generating consistent revenue from search and social.', 'smart-leading-net' ),
				'features'     => array(
					__( 'Google Ads campaign management', 'smart-leading-net' ),
					__( 'Monthly performance reporting', 'smart-leading-net' ),
					__( 'Landing page recommendations', 'smart-leading-net' ),
					__( 'Email support', 'smart-leading-net' ),
				),
				'button_text'  => __( 'Get Started', 'smart-leading-net' ),
				'button_url'   => '#',
				'is_popular'   => false,
				'active'       => true,
			)
		),
		sln_get_growth_page_default_price_plan_card(
			array(
				'plan_name'    => __( 'GROWTH', 'smart-leading-net' ),
				'price'        => '$2,499',
				'price_suffix' => __( '/ month', 'smart-leading-net' ),
				'description'  => __( 'Full-funnel growth for brands scaling paid acquisition with optimization across channels.', 'smart-leading-net' ),
				'features'     => array(
					__( 'Everything in Basic', 'smart-leading-net' ),
					__( 'Meta Ads management', 'smart-leading-net' ),
					__( 'Conversion rate optimization', 'smart-leading-net' ),
					__( 'Bi-weekly strategy calls', 'smart-leading-net' ),
					__( 'Dedicated account manager', 'smart-leading-net' ),
				),
				'button_text'  => __( 'Get Started', 'smart-leading-net' ),
				'button_url'   => '#',
				'is_popular'   => true,
				'badge_text'   => __( 'MOST POPULAR', 'smart-leading-net' ),
				'active'       => true,
			)
		),
		sln_get_growth_page_default_price_plan_card(
			array(
				'plan_name'    => __( 'PRO', 'smart-leading-net' ),
				'price'        => __( 'Custom pricing', 'smart-leading-net' ),
				'price_suffix' => '',
				'description'  => __( 'Enterprise-level revenue growth partnerships with custom strategy, integrations, and dedicated support.', 'smart-leading-net' ),
				'features'     => array(
					__( 'Full-funnel revenue strategy', 'smart-leading-net' ),
					__( 'Multi-channel campaign management', 'smart-leading-net' ),
					__( 'Custom analytics & attribution', 'smart-leading-net' ),
					__( 'Priority support & consulting', 'smart-leading-net' ),
					__( 'Custom CRM & platform integrations', 'smart-leading-net' ),
				),
				'button_text'  => __( 'Get a Custom Quote', 'smart-leading-net' ),
				'button_url'   => '#',
				'is_popular'   => false,
				'active'       => true,
			)
		),
	);
}

/**
 * Sanitize pricing card feature rows.
 *
 * @param mixed $features Raw features.
 * @return array<int, string>
 */
function sln_sanitize_growth_page_price_plan_features( $features ) {
	$sanitized = array();

	if ( ! is_array( $features ) ) {
		return $sanitized;
	}

	foreach ( $features as $feature ) {
		$text = is_array( $feature ) && isset( $feature['text'] )
			? sanitize_text_field( wp_unslash( $feature['text'] ) )
			: sanitize_text_field( wp_unslash( $feature ) );

		if ( '' !== trim( $text ) ) {
			$sanitized[] = $text;
		}
	}

	return $sanitized;
}

/**
 * Sanitize a Price Plan card row.
 *
 * @param array<string, mixed> $card Raw card data.
 * @return array<string, mixed>
 */
function sln_sanitize_growth_page_price_plan_card( $card ) {
	if ( ! is_array( $card ) ) {
		return array();
	}

	return array(
		'plan_name'    => isset( $card['plan_name'] ) ? sanitize_text_field( wp_unslash( $card['plan_name'] ) ) : '',
		'price'        => isset( $card['price'] ) ? sanitize_text_field( wp_unslash( $card['price'] ) ) : '',
		'price_suffix' => isset( $card['price_suffix'] ) ? sanitize_text_field( wp_unslash( $card['price_suffix'] ) ) : '',
		'description'  => isset( $card['description'] ) ? sln_growth_page_sanitize_wysiwyg_content( $card['description'] ) : '',
		'features'     => isset( $card['features'] ) ? sln_sanitize_growth_page_price_plan_features( $card['features'] ) : array(),
		'button_text'  => isset( $card['button_text'] ) ? sanitize_text_field( wp_unslash( $card['button_text'] ) ) : '',
		'button_url'   => isset( $card['button_url'] ) ? esc_url_raw( wp_unslash( $card['button_url'] ) ) : '',
		'is_popular'   => ! empty( $card['is_popular'] ),
		'badge_text'   => isset( $card['badge_text'] ) && '' !== trim( (string) $card['badge_text'] )
			? sanitize_text_field( wp_unslash( $card['badge_text'] ) )
			: __( 'MOST POPULAR', 'smart-leading-net' ),
		'active'       => ! empty( $card['active'] ),
	);
}

/**
 * Get Price Plan section data for frontend.
 *
 * @param int|null $post_id Post ID.
 * @return array<string, mixed>
 */
function sln_get_growth_page_price_plan( $post_id = null ) {
	if ( function_exists( 'sln_is_seo_services_page' ) && sln_is_seo_services_page() ) {
		return sln_get_seo_page_price_plan_data();
	}

	$post_id  = $post_id ? absint( $post_id ) : get_the_ID();
	$defaults = sln_get_growth_page_default_price_plan_section();

	$section = sln_growth_page_get_section_settings( $post_id, SLN_GP_PRICE_PLAN_SECTION_META, $defaults );
	$cards   = sln_growth_page_get_repeater_rows( $post_id, SLN_GP_PRICE_PLAN_CARDS_META, sln_get_growth_page_default_price_plan_cards() );

	$active_cards = array();

	foreach ( $cards as $card ) {
		if ( ! is_array( $card ) ) {
			continue;
		}

		$sanitized = sln_sanitize_growth_page_price_plan_card( $card );

		if ( empty( $sanitized['active'] ) ) {
			continue;
		}

		if ( '' === trim( $sanitized['plan_name'] ) && '' === trim( $sanitized['price'] ) ) {
			continue;
		}

		$active_cards[] = $sanitized;
	}

	return array(
		'label'          => $section['label'],
		'heading_lead'   => $section['heading_lead'],
		'highlight_word' => $section['highlight_word'],
		'heading_trail'  => $section['heading_trail'],
		'description'    => $section['description'],
		'cards'          => $active_cards,
	);
}

/**
 * Check whether Price Plan section should render.
 *
 * @param int|null $post_id Post ID.
 * @return bool
 */
function sln_growth_page_price_plan_has_content( $post_id = null ) {
	$data = sln_get_growth_page_price_plan( $post_id );

	return ! empty( $data['cards'] );
}

/**
 * Get raw Price Plan cards for admin.
 *
 * @param int $post_id Post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_growth_page_price_plan_cards_for_admin( $post_id ) {
	if ( ! metadata_exists( 'post', $post_id, SLN_GP_PRICE_PLAN_CARDS_META ) ) {
		return sln_get_growth_page_default_price_plan_cards();
	}

	$cards = get_post_meta( $post_id, SLN_GP_PRICE_PLAN_CARDS_META, true );

	if ( ! is_array( $cards ) ) {
		return array();
	}

	return array_map(
		static function ( $card ) {
			$card = is_array( $card ) ? $card : array();

			return wp_parse_args(
				$card,
				array(
					'plan_name'    => '',
					'price'        => '',
					'price_suffix' => '',
					'description'  => '',
					'features'     => array(),
					'button_text'  => '',
					'button_url'   => '',
					'is_popular'   => false,
					'badge_text'   => __( 'MOST POPULAR', 'smart-leading-net' ),
					'active'       => true,
				)
			);
		},
		$cards
	);
}

/**
 * Register Price Plan meta box.
 */
function sln_growth_page_register_price_plan_meta_box() {
	add_meta_box(
		'sln-growth-page-price-plan',
		__( 'Price Plan Section', 'smart-leading-net' ),
		'sln_growth_page_render_price_plan_meta_box',
		SLN_GROWTH_PAGE_POST_TYPE,
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'sln_growth_page_register_price_plan_meta_box' );

/**
 * Render a Price Plan feature row in admin.
 *
 * @param int    $card_index    Card index.
 * @param int    $feature_index Feature index.
 * @param string $feature       Feature text.
 */
function sln_growth_page_render_price_plan_feature_row( $card_index, $feature_index, $feature ) {
	?>
	<div class="sln-gp-admin__pp-feature-row">
		<input
			type="text"
			class="large-text"
			name="sln_gp_price_plan_cards[<?php echo esc_attr( $card_index ); ?>][features][<?php echo esc_attr( $feature_index ); ?>]"
			value="<?php echo esc_attr( $feature ); ?>"
		/>
		<button type="button" class="button-link-delete sln-gp-admin__pp-remove-feature"><?php esc_html_e( 'Remove Feature', 'smart-leading-net' ); ?></button>
	</div>
	<?php
}

/**
 * Render a Price Plan card row in admin.
 *
 * @param int                  $index Card index.
 * @param array<string, mixed> $card  Card data.
 */
function sln_growth_page_render_price_plan_card_row( $index, $card ) {
	$card = wp_parse_args(
		$card,
		array(
			'plan_name'    => '',
			'price'        => '',
			'price_suffix' => '',
			'description'  => '',
			'features'     => array(),
			'button_text'  => '',
			'button_url'   => '',
			'is_popular'   => false,
			'badge_text'   => __( 'MOST POPULAR', 'smart-leading-net' ),
			'active'       => true,
		)
	);

	$features = is_array( $card['features'] ) ? $card['features'] : array();
	?>
	<div class="sln-os-admin__repeatable-row sln-gp-admin__pp-card-row">
		<div class="sln-gp-admin__card-controls">
			<button type="button" class="button button-small sln-gp-admin__pp-move-up"><?php esc_html_e( 'Move Up', 'smart-leading-net' ); ?></button>
			<button type="button" class="button button-small sln-gp-admin__pp-move-down"><?php esc_html_e( 'Move Down', 'smart-leading-net' ); ?></button>
		</div>
		<div class="sln-os-admin__repeatable-card-fields sln-gp-admin__card-body">
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Plan Name', 'smart-leading-net' ); ?></span>
				<input type="text" class="regular-text" name="sln_gp_price_plan_cards[<?php echo esc_attr( $index ); ?>][plan_name]" value="<?php echo esc_attr( $card['plan_name'] ); ?>" />
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Price', 'smart-leading-net' ); ?></span>
				<input type="text" class="regular-text" name="sln_gp_price_plan_cards[<?php echo esc_attr( $index ); ?>][price]" value="<?php echo esc_attr( $card['price'] ); ?>" placeholder="<?php esc_attr_e( '$999', 'smart-leading-net' ); ?>" />
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Price Suffix', 'smart-leading-net' ); ?></span>
				<input type="text" class="regular-text" name="sln_gp_price_plan_cards[<?php echo esc_attr( $index ); ?>][price_suffix]" value="<?php echo esc_attr( $card['price_suffix'] ); ?>" placeholder="<?php esc_attr_e( '/ month', 'smart-leading-net' ); ?>" />
			</label>
			<label class="sln-gp-admin__field-full sln-gp-admin__editor-field">
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Description', 'smart-leading-net' ); ?></span>
				<?php
				sln_growth_page_render_wysiwyg_editor(
					'sln_gp_price_plan_card_desc_' . $index,
					'sln_gp_price_plan_cards[' . $index . '][description]',
					$card['description'],
					array(
						'textarea_rows' => 4,
					)
				);
				?>
			</label>
			<div class="sln-os-admin__subsection sln-gp-admin__pp-features-wrap">
				<h4><?php esc_html_e( 'Features', 'smart-leading-net' ); ?></h4>
				<div class="sln-os-admin__repeatable sln-gp-admin__pp-features-list" data-card-index="<?php echo esc_attr( $index ); ?>">
					<?php foreach ( $features as $feature_index => $feature ) : ?>
						<?php sln_growth_page_render_price_plan_feature_row( $index, $feature_index, $feature ); ?>
					<?php endforeach; ?>
				</div>
				<p>
					<button type="button" class="button button-secondary sln-gp-admin__pp-add-feature" data-card-index="<?php echo esc_attr( $index ); ?>">
						<?php esc_html_e( 'Add Feature', 'smart-leading-net' ); ?>
					</button>
				</p>
			</div>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Button Text', 'smart-leading-net' ); ?></span>
				<input type="text" class="regular-text" name="sln_gp_price_plan_cards[<?php echo esc_attr( $index ); ?>][button_text]" value="<?php echo esc_attr( $card['button_text'] ); ?>" />
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Button URL', 'smart-leading-net' ); ?></span>
				<input type="url" class="large-text" name="sln_gp_price_plan_cards[<?php echo esc_attr( $index ); ?>][button_url]" value="<?php echo esc_attr( $card['button_url'] ); ?>" />
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Popular Badge Text', 'smart-leading-net' ); ?></span>
				<input type="text" class="regular-text" name="sln_gp_price_plan_cards[<?php echo esc_attr( $index ); ?>][badge_text]" value="<?php echo esc_attr( $card['badge_text'] ); ?>" />
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Popular Badge', 'smart-leading-net' ); ?></span>
				<select name="sln_gp_price_plan_cards[<?php echo esc_attr( $index ); ?>][is_popular]">
					<option value="1" <?php selected( ! empty( $card['is_popular'] ) ); ?>><?php esc_html_e( 'Yes', 'smart-leading-net' ); ?></option>
					<option value="0" <?php selected( empty( $card['is_popular'] ) ); ?>><?php esc_html_e( 'No', 'smart-leading-net' ); ?></option>
				</select>
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Active Card', 'smart-leading-net' ); ?></span>
				<select name="sln_gp_price_plan_cards[<?php echo esc_attr( $index ); ?>][active]">
					<option value="1" <?php selected( ! empty( $card['active'] ) ); ?>><?php esc_html_e( 'Yes', 'smart-leading-net' ); ?></option>
					<option value="0" <?php selected( empty( $card['active'] ) ); ?>><?php esc_html_e( 'No', 'smart-leading-net' ); ?></option>
				</select>
			</label>
		</div>
		<button type="button" class="button-link-delete sln-gp-admin__pp-remove-card"><?php esc_html_e( 'Remove Card', 'smart-leading-net' ); ?></button>
	</div>
	<?php
}

/**
 * Render Price Plan meta box.
 *
 * @param WP_Post $post Current post.
 */
function sln_growth_page_render_price_plan_meta_box( $post ) {
	wp_nonce_field( 'sln_growth_page_save_price_plan', 'sln_growth_page_price_plan_nonce', false );

	$defaults = sln_get_growth_page_default_price_plan_section();
	$section  = get_post_meta( $post->ID, SLN_GP_PRICE_PLAN_SECTION_META, true );
	$section  = is_array( $section ) ? array_intersect_key( wp_parse_args( $section, $defaults ), $defaults ) : $defaults;
	$cards    = sln_get_growth_page_price_plan_cards_for_admin( $post->ID );
	$orders   = sln_get_growth_page_section_orders( $post->ID );
	$order    = isset( $orders['price_plan'] ) ? absint( $orders['price_plan'] ) : 8;
	?>
	<div class="sln-gp-admin">
		<p class="description"><?php esc_html_e( 'Manage the Pricing Plans section content, cards, and ordering.', 'smart-leading-net' ); ?></p>

		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><label for="sln_gp_price_plan_label"><?php esc_html_e( 'Small Heading', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_price_plan_label" name="sln_gp_price_plan_section[label]" value="<?php echo esc_attr( $section['label'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Main Heading', 'smart-leading-net' ); ?></th>
					<td>
						<p><label for="sln_gp_price_plan_heading_lead"><?php esc_html_e( 'Lead Text', 'smart-leading-net' ); ?></label><br />
						<input type="text" class="large-text" id="sln_gp_price_plan_heading_lead" name="sln_gp_price_plan_section[heading_lead]" value="<?php echo esc_attr( $section['heading_lead'] ); ?>" /></p>
						<p><label for="sln_gp_price_plan_highlight_word"><?php esc_html_e( 'Highlight Text (Blue)', 'smart-leading-net' ); ?></label><br />
						<input type="text" class="large-text" id="sln_gp_price_plan_highlight_word" name="sln_gp_price_plan_section[highlight_word]" value="<?php echo esc_attr( $section['highlight_word'] ); ?>" /></p>
						<p><label for="sln_gp_price_plan_heading_trail"><?php esc_html_e( 'Trailing Text', 'smart-leading-net' ); ?></label><br />
						<input type="text" class="regular-text" id="sln_gp_price_plan_heading_trail" name="sln_gp_price_plan_section[heading_trail]" value="<?php echo esc_attr( $section['heading_trail'] ); ?>" /></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_price_plan_description"><?php esc_html_e( 'Description', 'smart-leading-net' ); ?></label></th>
					<td>
						<?php
						sln_growth_page_render_wysiwyg_editor(
							'sln_gp_price_plan_description',
							'sln_gp_price_plan_section[description]',
							$section['description']
						);
						?>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_section_order_price_plan"><?php esc_html_e( 'Section Order Number', 'smart-leading-net' ); ?></label></th>
					<td>
						<input type="number" min="1" max="99" step="1" class="small-text" id="sln_gp_section_order_price_plan" name="sln_gp_section_orders[price_plan]" value="<?php echo esc_attr( $order ); ?>" />
					</td>
				</tr>
			</tbody>
		</table>

		<div class="sln-os-admin__subsection">
			<h3><?php esc_html_e( 'Pricing Cards', 'smart-leading-net' ); ?></h3>
			<div class="sln-os-admin__repeatable sln-gp-admin__pp-cards-list">
				<?php foreach ( $cards as $index => $card ) : ?>
					<?php sln_growth_page_render_price_plan_card_row( $index, $card ); ?>
				<?php endforeach; ?>
			</div>
			<p><button type="button" class="button button-secondary sln-gp-admin__add-pp-card"><?php esc_html_e( 'Add Card', 'smart-leading-net' ); ?></button></p>
		</div>
	</div>
	<?php
}

/**
 * Save Price Plan meta box values.
 *
 * @param int $post_id Post ID.
 */
function sln_growth_page_save_price_plan_meta( $post_id ) {
	if ( ! sln_growth_page_should_save_meta( $post_id, 'sln_growth_page_price_plan_nonce', 'sln_growth_page_save_price_plan' ) ) {
		return;
	}

	if ( isset( $_POST['sln_gp_price_plan_section'] ) && is_array( $_POST['sln_gp_price_plan_section'] ) ) {
		$raw     = wp_unslash( $_POST['sln_gp_price_plan_section'] );
		$section = array(
			'label'          => isset( $raw['label'] ) ? sanitize_text_field( $raw['label'] ) : '',
			'heading_lead'   => isset( $raw['heading_lead'] ) ? sanitize_text_field( $raw['heading_lead'] ) : '',
			'highlight_word' => isset( $raw['highlight_word'] ) ? sanitize_text_field( $raw['highlight_word'] ) : '',
			'heading_trail'  => isset( $raw['heading_trail'] ) ? sanitize_text_field( $raw['heading_trail'] ) : '',
			'description'    => isset( $raw['description'] ) ? sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ) : '',
		);

		update_post_meta( $post_id, SLN_GP_PRICE_PLAN_SECTION_META, $section );
	}

	sln_growth_page_update_repeater_meta(
		$post_id,
		SLN_GP_PRICE_PLAN_CARDS_META,
		'sln_gp_price_plan_cards',
		'sln_sanitize_growth_page_price_plan_card',
		static function ( $card ) {
			return '' !== trim( $card['plan_name'] ) || '' !== trim( $card['price'] );
		}
	);
}
