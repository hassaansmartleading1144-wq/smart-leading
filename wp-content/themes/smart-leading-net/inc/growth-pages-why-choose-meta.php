<?php
/**
 * Growth Pages — Why Choose section meta box and helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_GP_WHY_CHOOSE_SECTION_META', '_sln_gp_why_choose_section' );
define( 'SLN_GP_WHY_CHOOSE_ROWS_META', '_sln_gp_why_choose_rows' );

/**
 * Default Why Choose section content.
 *
 * @return array<string, string>
 */
function sln_get_growth_page_default_why_choose_section() {
	return array(
		'label'          => __( 'Why Choose Us', 'smart-leading-net' ),
		'heading_lead'   => __( 'Unmatched', 'smart-leading-net' ),
		'highlight_word' => __( 'Revenue Growth', 'smart-leading-net' ),
		'heading_trail'  => __( 'Expertise', 'smart-leading-net' ),
		'description'    => __( 'See how Smart Leading stacks up against in-house teams and typical marketing agencies — and why our clients consistently choose us to drive their growth.', 'smart-leading-net' ),
		'button_text'    => '',
		'button_url'     => '',
	);
}

/**
 * Default comparison row.
 *
 * @param array<string, mixed> $args Row defaults.
 * @return array<string, mixed>
 */
function sln_get_growth_page_default_why_choose_row( $args ) {
	$defaults = array(
		'feature'       => '',
		'smart_leading' => 'check',
		'in_house'      => '',
		'agency'        => '',
		'active'        => true,
	);

	return wp_parse_args( $args, $defaults );
}

/**
 * Default Why Choose comparison rows.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_get_growth_page_default_why_choose_rows() {
	return array(
		sln_get_growth_page_default_why_choose_row(
			array(
				'feature'       => __( 'Dedicated Revenue Growth Specialist', 'smart-leading-net' ),
				'smart_leading' => 'check',
				'in_house'      => 'Sometimes',
				'agency'        => 'Varies',
			)
		),
		sln_get_growth_page_default_why_choose_row(
			array(
				'feature'       => __( 'Full-Funnel CRM & Ad Platform Integration', 'smart-leading-net' ),
				'smart_leading' => 'check',
				'in_house'      => 'cross',
				'agency'        => 'Limited',
			)
		),
		sln_get_growth_page_default_why_choose_row(
			array(
				'feature'       => __( 'Real-Time Revenue & ROI Reporting', 'smart-leading-net' ),
				'smart_leading' => 'check',
				'in_house'      => 'Limited',
				'agency'        => 'Sometimes',
			)
		),
		sln_get_growth_page_default_why_choose_row(
			array(
				'feature'       => __( 'Cross-Channel Strategy (Paid + Organic)', 'smart-leading-net' ),
				'smart_leading' => 'check',
				'in_house'      => 'cross',
				'agency'        => 'Limited',
			)
		),
		sln_get_growth_page_default_why_choose_row(
			array(
				'feature'       => __( 'Conversion Rate Optimization', 'smart-leading-net' ),
				'smart_leading' => 'check',
				'in_house'      => 'cross',
				'agency'        => 'Extra Cost',
			)
		),
		sln_get_growth_page_default_why_choose_row(
			array(
				'feature'       => __( 'Dedicated Account Management', 'smart-leading-net' ),
				'smart_leading' => 'check',
				'in_house'      => 'check',
				'agency'        => 'Sometimes',
			)
		),
		sln_get_growth_page_default_why_choose_row(
			array(
				'feature'       => __( 'No Long-Term Contracts', 'smart-leading-net' ),
				'smart_leading' => 'check',
				'in_house'      => 'check',
				'agency'        => 'cross',
			)
		),
		sln_get_growth_page_default_why_choose_row(
			array(
				'feature'       => __( 'Transparent Pricing', 'smart-leading-net' ),
				'smart_leading' => 'check',
				'in_house'      => 'check',
				'agency'        => 'Varies',
			)
		),
	);
}

/**
 * Normalize a comparison cell value.
 *
 * @param string $value Raw value.
 * @return string check|cross|warning
 */
function sln_growth_page_why_choose_normalize_cell_type( $value ) {
	$raw         = strtolower( trim( (string) $value ) );
	$check_marks = array( 'check', 'yes', 'true', '1', '✔', '✓' );
	$cross_marks = array( 'cross', 'no', 'false', '0', '✕', '✗', 'x' );

	if ( in_array( $raw, $check_marks, true ) ) {
		return 'check';
	}

	if ( in_array( $raw, $cross_marks, true ) ) {
		return 'cross';
	}

	return 'warning';
}

/**
 * Sanitize a Why Choose comparison row.
 *
 * @param array<string, mixed> $row Raw row data.
 * @return array<string, mixed>
 */
function sln_sanitize_growth_page_why_choose_row( $row ) {
	if ( ! is_array( $row ) ) {
		return array();
	}

	return array(
		'feature'       => isset( $row['feature'] ) ? sanitize_text_field( wp_unslash( $row['feature'] ) ) : '',
		'smart_leading' => isset( $row['smart_leading'] ) ? sanitize_text_field( wp_unslash( $row['smart_leading'] ) ) : '',
		'in_house'      => isset( $row['in_house'] ) ? sanitize_text_field( wp_unslash( $row['in_house'] ) ) : '',
		'agency'        => isset( $row['agency'] ) ? sanitize_text_field( wp_unslash( $row['agency'] ) ) : '',
		'active'        => ! empty( $row['active'] ),
	);
}

/**
 * Render a comparison table cell.
 *
 * @param string $value Cell value.
 */
function sln_growth_page_render_why_choose_cell( $value ) {
	$type = sln_growth_page_why_choose_normalize_cell_type( $value );

	if ( 'check' === $type ) {
		echo '<span class="why-choose__status why-choose__status--check" aria-hidden="true">✔</span>';
		echo '<span class="screen-reader-text">' . esc_html__( 'Yes', 'smart-leading-net' ) . '</span>';
		return;
	}

	if ( 'cross' === $type ) {
		echo '<span class="why-choose__status why-choose__status--cross" aria-hidden="true">✕</span>';
		echo '<span class="screen-reader-text">' . esc_html__( 'No', 'smart-leading-net' ) . '</span>';
		return;
	}

	echo '<span class="why-choose__status why-choose__status--warning">' . esc_html( $value ) . '</span>';
}

/**
 * Get Why Choose section data for frontend.
 *
 * @param int|null $post_id Post ID.
 * @return array<string, mixed>
 */
function sln_get_growth_page_why_choose( $post_id = null ) {
	$post_id  = $post_id ? absint( $post_id ) : get_the_ID();
	$defaults = sln_get_growth_page_default_why_choose_section();

	$section = sln_growth_page_get_section_settings( $post_id, SLN_GP_WHY_CHOOSE_SECTION_META, $defaults );
	$rows    = sln_growth_page_get_repeater_rows( $post_id, SLN_GP_WHY_CHOOSE_ROWS_META, sln_get_growth_page_default_why_choose_rows() );

	$active_rows = array();

	foreach ( $rows as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}

		$sanitized = sln_sanitize_growth_page_why_choose_row( $row );

		if ( empty( $sanitized['active'] ) ) {
			continue;
		}

		if ( '' === trim( $sanitized['feature'] ) ) {
			continue;
		}

		$active_rows[] = $sanitized;
	}

	return array(
		'label'          => $section['label'],
		'heading_lead'   => $section['heading_lead'],
		'highlight_word' => $section['highlight_word'],
		'heading_trail'  => $section['heading_trail'],
		'description'    => $section['description'],
		'button_text'    => $section['button_text'],
		'button_url'     => $section['button_url'],
		'rows'           => $active_rows,
	);
}

/**
 * Check whether Why Choose section should render.
 *
 * @param int|null $post_id Post ID.
 * @return bool
 */
function sln_growth_page_why_choose_has_content( $post_id = null ) {
	$data = sln_get_growth_page_why_choose( $post_id );

	return ! empty( $data['rows'] );
}

/**
 * Get raw Why Choose rows for admin.
 *
 * @param int $post_id Post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_growth_page_why_choose_rows_for_admin( $post_id ) {
	if ( ! metadata_exists( 'post', $post_id, SLN_GP_WHY_CHOOSE_ROWS_META ) ) {
		return sln_get_growth_page_default_why_choose_rows();
	}

	$rows = get_post_meta( $post_id, SLN_GP_WHY_CHOOSE_ROWS_META, true );

	if ( ! is_array( $rows ) ) {
		return array();
	}

	return array_map(
		static function ( $row ) {
			$row = is_array( $row ) ? $row : array();

			return wp_parse_args(
				$row,
				array(
					'feature'       => '',
					'smart_leading' => 'check',
					'in_house'      => '',
					'agency'        => '',
					'active'        => true,
				)
			);
		},
		$rows
	);
}

/**
 * Register Why Choose meta box.
 */
function sln_growth_page_register_why_choose_meta_box() {
	add_meta_box(
		'sln-growth-page-why-choose',
		__( 'Why Choose Section', 'smart-leading-net' ),
		'sln_growth_page_render_why_choose_meta_box',
		SLN_GROWTH_PAGE_POST_TYPE,
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'sln_growth_page_register_why_choose_meta_box' );

/**
 * Render a Why Choose comparison row in admin.
 *
 * @param int                  $index Row index.
 * @param array<string, mixed> $row   Row data.
 */
function sln_growth_page_render_why_choose_row( $index, $row ) {
	$row = wp_parse_args(
		$row,
		array(
			'feature'       => '',
			'smart_leading' => 'check',
			'in_house'      => '',
			'agency'        => '',
			'active'        => true,
		)
	);
	?>
	<div class="sln-os-admin__repeatable-row sln-gp-admin__wc-row">
		<div class="sln-gp-admin__card-controls">
			<button type="button" class="button button-small sln-gp-admin__wc-move-up"><?php esc_html_e( 'Move Up', 'smart-leading-net' ); ?></button>
			<button type="button" class="button button-small sln-gp-admin__wc-move-down"><?php esc_html_e( 'Move Down', 'smart-leading-net' ); ?></button>
		</div>
		<div class="sln-os-admin__repeatable-fields sln-gp-admin__card-fields">
			<label class="sln-gp-admin__field-full">
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Feature Name', 'smart-leading-net' ); ?></span>
				<input type="text" class="large-text" name="sln_gp_why_choose_rows[<?php echo esc_attr( $index ); ?>][feature]" value="<?php echo esc_attr( $row['feature'] ); ?>" />
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Smart Leading Value', 'smart-leading-net' ); ?></span>
				<input type="text" class="regular-text" name="sln_gp_why_choose_rows[<?php echo esc_attr( $index ); ?>][smart_leading]" value="<?php echo esc_attr( $row['smart_leading'] ); ?>" placeholder="<?php esc_attr_e( 'check', 'smart-leading-net' ); ?>" />
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'In-House Value', 'smart-leading-net' ); ?></span>
				<input type="text" class="regular-text" name="sln_gp_why_choose_rows[<?php echo esc_attr( $index ); ?>][in_house]" value="<?php echo esc_attr( $row['in_house'] ); ?>" placeholder="<?php esc_attr_e( 'Sometimes / cross / check', 'smart-leading-net' ); ?>" />
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Agency Value', 'smart-leading-net' ); ?></span>
				<input type="text" class="regular-text" name="sln_gp_why_choose_rows[<?php echo esc_attr( $index ); ?>][agency]" value="<?php echo esc_attr( $row['agency'] ); ?>" placeholder="<?php esc_attr_e( 'Limited / Varies / cross', 'smart-leading-net' ); ?>" />
			</label>
			<p class="description"><?php esc_html_e( 'Use check, cross, or warning text such as Sometimes, Limited, Varies, or Extra Cost.', 'smart-leading-net' ); ?></p>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Active Row', 'smart-leading-net' ); ?></span>
				<select name="sln_gp_why_choose_rows[<?php echo esc_attr( $index ); ?>][active]">
					<option value="1" <?php selected( ! empty( $row['active'] ) ); ?>><?php esc_html_e( 'Yes', 'smart-leading-net' ); ?></option>
					<option value="0" <?php selected( empty( $row['active'] ) ); ?>><?php esc_html_e( 'No', 'smart-leading-net' ); ?></option>
				</select>
			</label>
		</div>
		<button type="button" class="button-link-delete sln-gp-admin__wc-remove-row"><?php esc_html_e( 'Remove Row', 'smart-leading-net' ); ?></button>
	</div>
	<?php
}

/**
 * Render Why Choose meta box.
 *
 * @param WP_Post $post Current post.
 */
function sln_growth_page_render_why_choose_meta_box( $post ) {
	wp_nonce_field( 'sln_growth_page_save_why_choose', 'sln_growth_page_why_choose_nonce', false );

	$defaults = sln_get_growth_page_default_why_choose_section();
	$section  = get_post_meta( $post->ID, SLN_GP_WHY_CHOOSE_SECTION_META, true );
	$section  = is_array( $section ) ? array_intersect_key( wp_parse_args( $section, $defaults ), $defaults ) : $defaults;
	$rows     = sln_get_growth_page_why_choose_rows_for_admin( $post->ID );
	$orders   = sln_get_growth_page_section_orders( $post->ID );
	$order    = isset( $orders['why_choose'] ) ? absint( $orders['why_choose'] ) : 7;
	?>
	<div class="sln-gp-admin">
		<p class="description"><?php esc_html_e( 'Manage the Why Choose comparison table section content and ordering.', 'smart-leading-net' ); ?></p>

		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><label for="sln_gp_why_choose_label"><?php esc_html_e( 'Small Heading', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_why_choose_label" name="sln_gp_why_choose_section[label]" value="<?php echo esc_attr( $section['label'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Main Heading', 'smart-leading-net' ); ?></th>
					<td>
						<p><label for="sln_gp_why_choose_heading_lead"><?php esc_html_e( 'Text Before Highlight', 'smart-leading-net' ); ?></label><br />
						<input type="text" class="large-text" id="sln_gp_why_choose_heading_lead" name="sln_gp_why_choose_section[heading_lead]" value="<?php echo esc_attr( $section['heading_lead'] ); ?>" /></p>
						<p><label for="sln_gp_why_choose_highlight_word"><?php esc_html_e( 'Highlighted Text', 'smart-leading-net' ); ?></label><br />
						<input type="text" class="large-text" id="sln_gp_why_choose_highlight_word" name="sln_gp_why_choose_section[highlight_word]" value="<?php echo esc_attr( $section['highlight_word'] ); ?>" /></p>
						<p><label for="sln_gp_why_choose_heading_trail"><?php esc_html_e( 'Text After Highlight', 'smart-leading-net' ); ?></label><br />
						<input type="text" class="large-text" id="sln_gp_why_choose_heading_trail" name="sln_gp_why_choose_section[heading_trail]" value="<?php echo esc_attr( $section['heading_trail'] ); ?>" /></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_why_choose_description"><?php esc_html_e( 'Description', 'smart-leading-net' ); ?></label></th>
					<td>
						<?php
						sln_growth_page_render_wysiwyg_editor(
							'sln_gp_why_choose_description',
							'sln_gp_why_choose_section[description]',
							$section['description']
						);
						?>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_why_choose_button_text"><?php esc_html_e( 'Button Text (Optional)', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_why_choose_button_text" name="sln_gp_why_choose_section[button_text]" value="<?php echo esc_attr( $section['button_text'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_why_choose_button_url"><?php esc_html_e( 'Button URL (Optional)', 'smart-leading-net' ); ?></label></th>
					<td><input type="url" class="large-text" id="sln_gp_why_choose_button_url" name="sln_gp_why_choose_section[button_url]" value="<?php echo esc_attr( $section['button_url'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_section_order_why_choose"><?php esc_html_e( 'Section Order Number', 'smart-leading-net' ); ?></label></th>
					<td>
						<input type="number" min="1" max="99" step="1" class="small-text" id="sln_gp_section_order_why_choose" name="sln_gp_section_orders[why_choose]" value="<?php echo esc_attr( $order ); ?>" />
					</td>
				</tr>
			</tbody>
		</table>

		<div class="sln-os-admin__subsection">
			<h3><?php esc_html_e( 'Comparison Rows', 'smart-leading-net' ); ?></h3>
			<div class="sln-os-admin__repeatable sln-gp-admin__wc-rows-list">
				<?php foreach ( $rows as $index => $row ) : ?>
					<?php sln_growth_page_render_why_choose_row( $index, $row ); ?>
				<?php endforeach; ?>
			</div>
			<p><button type="button" class="button button-secondary sln-gp-admin__add-wc-row"><?php esc_html_e( 'Add New Row', 'smart-leading-net' ); ?></button></p>
		</div>
	</div>
	<?php
}

/**
 * Save Why Choose meta box values.
 *
 * @param int $post_id Post ID.
 */
function sln_growth_page_save_why_choose_meta( $post_id ) {
	if ( ! sln_growth_page_should_save_meta( $post_id, 'sln_growth_page_why_choose_nonce', 'sln_growth_page_save_why_choose' ) ) {
		return;
	}

	if ( isset( $_POST['sln_gp_why_choose_section'] ) && is_array( $_POST['sln_gp_why_choose_section'] ) ) {
		$raw     = wp_unslash( $_POST['sln_gp_why_choose_section'] );
		$section = array(
			'label'          => isset( $raw['label'] ) ? sanitize_text_field( $raw['label'] ) : '',
			'heading_lead'   => isset( $raw['heading_lead'] ) ? sanitize_text_field( $raw['heading_lead'] ) : '',
			'highlight_word' => isset( $raw['highlight_word'] ) ? sanitize_text_field( $raw['highlight_word'] ) : '',
			'heading_trail'  => isset( $raw['heading_trail'] ) ? sanitize_text_field( $raw['heading_trail'] ) : '',
			'description'    => isset( $raw['description'] ) ? sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ) : '',
			'button_text'    => isset( $raw['button_text'] ) ? sanitize_text_field( $raw['button_text'] ) : '',
			'button_url'     => isset( $raw['button_url'] ) ? esc_url_raw( $raw['button_url'] ) : '',
		);

		update_post_meta( $post_id, SLN_GP_WHY_CHOOSE_SECTION_META, $section );
	}

	sln_growth_page_update_repeater_meta(
		$post_id,
		SLN_GP_WHY_CHOOSE_ROWS_META,
		'sln_gp_why_choose_rows',
		'sln_sanitize_growth_page_why_choose_row',
		static function ( $row ) {
			return '' !== trim( $row['feature'] );
		}
	);
}
