<?php
/**
 * Growth Pages — How Work section meta box and helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_GP_HOW_WORK_SECTION_META', '_sln_gp_how_work_section' );
define( 'SLN_GP_HOW_WORK_TABS_META', '_sln_gp_how_work_tabs' );

/**
 * Default How Work section content.
 *
 * @return array<string, string>
 */
function sln_get_growth_page_default_how_work_section() {
	return array(
		'label'            => __( 'How It Works', 'smart-leading-net' ),
		'heading_lead'     => __( 'Inside Our', 'smart-leading-net' ),
		'highlight_word_1' => __( 'Revenue Growth', 'smart-leading-net' ),
		'highlight_word_2' => __( 'Solutions', 'smart-leading-net' ),
		'description'      => __( 'Revenue growth is as easy as 1, 2, 3 with Smart Leading. As a full-service agency, we provide the talent and tech your business needs to make paid advertising a predictable revenue engine.', 'smart-leading-net' ),
	);
}

/**
 * Default tab placeholder content.
 *
 * @param string $label         Tab label.
 * @param string $content_title Content heading.
 * @param string $card_title    Card heading.
 * @return array<string, mixed>
 */
function sln_get_growth_page_default_how_work_tab( $label, $content_title, $card_title ) {
	return array(
		'tab_label'            => $label,
		'content_heading'      => $content_title,
		'content_description'  => sprintf(
			/* translators: %s: tab phase name */
			__( 'Our %s phase is designed to strengthen your revenue engine with proven strategies, measurable outcomes, and continuous optimization.', 'smart-leading-net' ),
			$content_title
		),
		'activities'           => array(
			__( 'Audit current performance and identify growth opportunities', 'smart-leading-net' ),
			__( 'Align campaigns with high-intent customer journeys', 'smart-leading-net' ),
			__( 'Implement data-backed optimizations across channels', 'smart-leading-net' ),
			__( 'Track KPIs and refine strategy for scalable revenue', 'smart-leading-net' ),
		),
		'card_heading'         => $card_title,
		'card_description'     => sprintf(
			/* translators: %s: tab phase name */
			__( 'We execute the %s stage with precision, clarity, and a focus on predictable revenue growth.', 'smart-leading-net' ),
			$content_title
		),
		'stat_1_number'        => '100%',
		'stat_1_label'         => __( 'Custom Strategy', 'smart-leading-net' ),
		'stat_2_number'        => '47+',
		'stat_2_label'         => __( 'Markets Analyzed', 'smart-leading-net' ),
		'stat_3_number'        => '3X',
		'stat_3_label'         => __( 'Avg ROI Boost', 'smart-leading-net' ),
		'active'               => true,
	);
}

/**
 * Default How Work tabs.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_get_growth_page_default_how_work_tabs() {
	return array(
		array(
			'tab_label'           => __( 'Discover', 'smart-leading-net' ),
			'content_heading'     => __( 'Discover', 'smart-leading-net' ),
			'content_description' => __( 'Every successful revenue system starts with understanding where growth opportunities already exist. Before launching campaigns, we analyze your market, competitors, customer behavior, and current performance to identify the fastest and most sustainable paths to revenue growth.', 'smart-leading-net' ),
			'activities'          => array(
				__( 'Identify high-value customer segments and buying intent', 'smart-leading-net' ),
				__( "Analyze competitors' visibility, messaging, and acquisition channels", 'smart-leading-net' ),
				__( 'Audit existing traffic, conversion, and revenue performance', 'smart-leading-net' ),
				__( 'Define growth goals, KPIs, and revenue opportunities', 'smart-leading-net' ),
			),
			'card_heading'        => __( 'Discovery & Analysis', 'smart-leading-net' ),
			'card_description'    => __( 'We dive deep into your business, market, and competition to uncover the clearest, fastest path to revenue growth.', 'smart-leading-net' ),
			'stat_1_number'       => '100%',
			'stat_1_label'        => __( 'Custom Strategy', 'smart-leading-net' ),
			'stat_2_number'       => '47+',
			'stat_2_label'        => __( 'Markets Analyzed', 'smart-leading-net' ),
			'stat_3_number'       => '3X',
			'stat_3_label'        => __( 'Avg ROI Boost', 'smart-leading-net' ),
			'active'              => true,
		),
		sln_get_growth_page_default_how_work_tab( __( 'Capture', 'smart-leading-net' ), __( 'Capture', 'smart-leading-net' ), __( 'Capture & Demand Generation', 'smart-leading-net' ) ),
		sln_get_growth_page_default_how_work_tab( __( 'Convert', 'smart-leading-net' ), __( 'Convert', 'smart-leading-net' ), __( 'Conversion Optimization', 'smart-leading-net' ) ),
		sln_get_growth_page_default_how_work_tab( __( 'Nurture', 'smart-leading-net' ), __( 'Nurture', 'smart-leading-net' ), __( 'Lead Nurturing Systems', 'smart-leading-net' ) ),
		sln_get_growth_page_default_how_work_tab( __( 'Scale', 'smart-leading-net' ), __( 'Scale', 'smart-leading-net' ), __( 'Scale & Expansion', 'smart-leading-net' ) ),
	);
}

/**
 * Sanitize How Work activity rows.
 *
 * @param mixed                $activities Raw activities data.
 * @param array<string, mixed> $legacy_tab Optional tab data for legacy field migration.
 * @return array<int, string>
 */
function sln_sanitize_growth_page_how_work_activities( $activities, $legacy_tab = array() ) {
	$sanitized = array();

	if ( is_array( $activities ) ) {
		foreach ( $activities as $activity ) {
			$text = is_array( $activity ) && isset( $activity['text'] )
				? sanitize_text_field( wp_unslash( $activity['text'] ) )
				: sanitize_text_field( wp_unslash( $activity ) );

			if ( '' !== trim( $text ) ) {
				$sanitized[] = $text;
			}
		}
	}

	if ( empty( $sanitized ) && is_array( $legacy_tab ) ) {
		for ( $i = 1; $i <= 4; $i++ ) {
			$key = 'activity_' . $i;

			if ( ! empty( $legacy_tab[ $key ] ) ) {
				$text = sanitize_text_field( wp_unslash( $legacy_tab[ $key ] ) );

				if ( '' !== trim( $text ) ) {
					$sanitized[] = $text;
				}
			}
		}
	}

	return $sanitized;
}

/**
 * Sanitize a How Work tab row.
 *
 * @param array<string, mixed> $tab Raw tab data.
 * @return array<string, mixed>
 */
function sln_sanitize_growth_page_how_work_tab( $tab ) {
	if ( ! is_array( $tab ) ) {
		return array();
	}

	return array(
		'tab_label'           => isset( $tab['tab_label'] ) ? sanitize_text_field( wp_unslash( $tab['tab_label'] ) ) : '',
		'content_heading'     => isset( $tab['content_heading'] ) ? sanitize_text_field( wp_unslash( $tab['content_heading'] ) ) : '',
		'content_description' => isset( $tab['content_description'] ) ? sln_growth_page_sanitize_wysiwyg_content( $tab['content_description'] ) : '',
		'activities'          => sln_sanitize_growth_page_how_work_activities(
			isset( $tab['activities'] ) ? $tab['activities'] : array(),
			$tab
		),
		'card_heading'        => isset( $tab['card_heading'] ) ? sanitize_text_field( wp_unslash( $tab['card_heading'] ) ) : '',
		'card_description'    => isset( $tab['card_description'] ) ? sln_growth_page_sanitize_wysiwyg_content( $tab['card_description'] ) : '',
		'stat_1_number'       => isset( $tab['stat_1_number'] ) ? sanitize_text_field( wp_unslash( $tab['stat_1_number'] ) ) : '',
		'stat_1_label'        => isset( $tab['stat_1_label'] ) ? sanitize_text_field( wp_unslash( $tab['stat_1_label'] ) ) : '',
		'stat_2_number'       => isset( $tab['stat_2_number'] ) ? sanitize_text_field( wp_unslash( $tab['stat_2_number'] ) ) : '',
		'stat_2_label'        => isset( $tab['stat_2_label'] ) ? sanitize_text_field( wp_unslash( $tab['stat_2_label'] ) ) : '',
		'stat_3_number'       => isset( $tab['stat_3_number'] ) ? sanitize_text_field( wp_unslash( $tab['stat_3_number'] ) ) : '',
		'stat_3_label'        => isset( $tab['stat_3_label'] ) ? sanitize_text_field( wp_unslash( $tab['stat_3_label'] ) ) : '',
		'active'              => ! empty( $tab['active'] ),
	);
}

/**
 * Get How Work section data for frontend.
 *
 * @param int|null $post_id Post ID.
 * @return array<string, mixed>
 */
function sln_get_growth_page_how_work( $post_id = null ) {
	$post_id  = $post_id ? absint( $post_id ) : get_the_ID();
	$defaults = sln_get_growth_page_default_how_work_section();

	$section = sln_growth_page_get_section_settings( $post_id, SLN_GP_HOW_WORK_SECTION_META, $defaults );
	$tabs    = sln_growth_page_get_repeater_rows( $post_id, SLN_GP_HOW_WORK_TABS_META, sln_get_growth_page_default_how_work_tabs() );

	$active_tabs = array();

	foreach ( $tabs as $index => $tab ) {
		if ( ! is_array( $tab ) ) {
			continue;
		}

		$tab = sln_sanitize_growth_page_how_work_tab( $tab );

		if ( empty( $tab['active'] ) || '' === trim( $tab['tab_label'] ) ) {
			continue;
		}

		$activities = $tab['activities'];

		$stats = array_values(
			array_filter(
				array(
					array(
						'number' => $tab['stat_1_number'],
						'label'  => $tab['stat_1_label'],
					),
					array(
						'number' => $tab['stat_2_number'],
						'label'  => $tab['stat_2_label'],
					),
					array(
						'number' => $tab['stat_3_number'],
						'label'  => $tab['stat_3_label'],
					),
				),
				static function ( $stat ) {
					return '' !== trim( $stat['number'] ) || '' !== trim( $stat['label'] );
				}
			)
		);

		$slug = sanitize_title( $tab['tab_label'] );
		if ( '' === $slug ) {
			$slug = 'tab-' . ( $index + 1 );
		}

		$active_tabs[] = array(
			'slug'                => $slug . '-' . ( count( $active_tabs ) + 1 ),
			'step_number'         => str_pad( (string) ( count( $active_tabs ) + 1 ), 2, '0', STR_PAD_LEFT ),
			'tab_label'           => $tab['tab_label'],
			'content_heading'     => $tab['content_heading'],
			'content_description' => $tab['content_description'],
			'activities'          => $activities,
			'card_heading'        => $tab['card_heading'],
			'card_description'    => $tab['card_description'],
			'stats'               => $stats,
		);
	}

	return array(
		'label'            => $section['label'],
		'heading_lead'     => $section['heading_lead'],
		'highlight_word_1' => $section['highlight_word_1'],
		'highlight_word_2' => $section['highlight_word_2'],
		'description'      => $section['description'],
		'tabs'             => $active_tabs,
	);
}

/**
 * Check whether How Work section should render.
 *
 * @param int|null $post_id Post ID.
 * @return bool
 */
function sln_growth_page_how_work_has_content( $post_id = null ) {
	$data = sln_get_growth_page_how_work( $post_id );

	return ! empty( $data['tabs'] );
}

/**
 * Get raw tabs for admin.
 *
 * @param int $post_id Post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_growth_page_how_work_tabs_for_admin( $post_id ) {
	if ( ! metadata_exists( 'post', $post_id, SLN_GP_HOW_WORK_TABS_META ) ) {
		return sln_get_growth_page_default_how_work_tabs();
	}

	$tabs = get_post_meta( $post_id, SLN_GP_HOW_WORK_TABS_META, true );

	return is_array( $tabs ) ? $tabs : array();
}

/**
 * Register How Work meta box.
 */
function sln_growth_page_register_how_work_meta_box() {
	add_meta_box(
		'sln-growth-page-how-work',
		__( 'How Work Section', 'smart-leading-net' ),
		'sln_growth_page_render_how_work_meta_box',
		SLN_GROWTH_PAGE_POST_TYPE,
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'sln_growth_page_register_how_work_meta_box' );

/**
 * Get activities for admin display (supports legacy activity_1–4 fields).
 *
 * @param array<string, mixed> $tab Tab data.
 * @return array<int, string>
 */
function sln_get_how_work_tab_activities_for_admin( $tab ) {
	if ( ! empty( $tab['activities'] ) && is_array( $tab['activities'] ) ) {
		return sln_sanitize_growth_page_how_work_activities( $tab['activities'] );
	}

	return sln_sanitize_growth_page_how_work_activities( array(), $tab );
}

/**
 * Render a Key Activity row in admin.
 *
 * @param int    $tab_index Tab index.
 * @param int    $act_index Activity index.
 * @param string $text      Activity text.
 */
function sln_growth_page_render_how_work_activity_row( $tab_index, $act_index, $text ) {
	?>
	<div class="sln-os-admin__repeatable-row sln-gp-admin__activity-row">
		<div class="sln-gp-admin__card-controls">
			<button type="button" class="button button-small sln-gp-admin__activity-move-up"><?php esc_html_e( 'Move Up', 'smart-leading-net' ); ?></button>
			<button type="button" class="button button-small sln-gp-admin__activity-move-down"><?php esc_html_e( 'Move Down', 'smart-leading-net' ); ?></button>
		</div>
		<label>
			<span class="sln-os-admin__field-label"><?php esc_html_e( 'Activity Text', 'smart-leading-net' ); ?></span>
			<input type="text" class="large-text" name="sln_gp_how_work_tabs[<?php echo esc_attr( $tab_index ); ?>][activities][<?php echo esc_attr( $act_index ); ?>]" value="<?php echo esc_attr( $text ); ?>" />
		</label>
		<button type="button" class="button-link-delete sln-gp-admin__remove-activity"><?php esc_html_e( 'Remove Activity', 'smart-leading-net' ); ?></button>
	</div>
	<?php
}

/**
 * Render a How Work tab row in admin.
 *
 * @param int                  $index Tab index.
 * @param array<string, mixed> $tab   Tab data.
 */
function sln_growth_page_render_how_work_tab_row( $index, $tab ) {
	$tab = wp_parse_args(
		$tab,
		array(
			'tab_label'           => '',
			'content_heading'     => '',
			'content_description' => '',
			'activities'          => array(),
			'card_heading'        => '',
			'card_description'    => '',
			'stat_1_number'       => '',
			'stat_1_label'        => '',
			'stat_2_number'       => '',
			'stat_2_label'        => '',
			'stat_3_number'       => '',
			'stat_3_label'        => '',
			'active'              => true,
		)
	);
	?>
	<div class="sln-os-admin__repeatable-row sln-gp-admin__how-work-tab-row">
		<div class="sln-gp-admin__card-controls">
			<button type="button" class="button button-small sln-gp-admin__how-work-tab-move-up"><?php esc_html_e( 'Move Up', 'smart-leading-net' ); ?></button>
			<button type="button" class="button button-small sln-gp-admin__how-work-tab-move-down"><?php esc_html_e( 'Move Down', 'smart-leading-net' ); ?></button>
		</div>
		<div class="sln-os-admin__repeatable-fields sln-gp-admin__card-fields">
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Tab Label', 'smart-leading-net' ); ?></span>
				<input type="text" class="regular-text" name="sln_gp_how_work_tabs[<?php echo esc_attr( $index ); ?>][tab_label]" value="<?php echo esc_attr( $tab['tab_label'] ); ?>" />
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Content Heading', 'smart-leading-net' ); ?></span>
				<input type="text" class="large-text" name="sln_gp_how_work_tabs[<?php echo esc_attr( $index ); ?>][content_heading]" value="<?php echo esc_attr( $tab['content_heading'] ); ?>" />
			</label>
			<label class="sln-gp-admin__field-full sln-gp-admin__editor-field">
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Content Description', 'smart-leading-net' ); ?></span>
				<?php
				sln_growth_page_render_wysiwyg_editor(
					'sln_gp_how_work_tab_description_' . $index,
					'sln_gp_how_work_tabs[' . $index . '][content_description]',
					$tab['content_description']
				);
				?>
			</label>
			<div class="sln-os-admin__subsection sln-gp-admin__activities-wrap">
				<h4><?php esc_html_e( 'Key Activities', 'smart-leading-net' ); ?></h4>
				<div class="sln-os-admin__repeatable sln-gp-admin__activities-list" data-tab-index="<?php echo esc_attr( $index ); ?>">
					<?php
					$activities = sln_get_how_work_tab_activities_for_admin( $tab );
					foreach ( $activities as $act_index => $text ) :
						sln_growth_page_render_how_work_activity_row( $index, $act_index, $text );
					endforeach;
					?>
				</div>
				<p><button type="button" class="button button-secondary sln-gp-admin__add-activity" data-tab-index="<?php echo esc_attr( $index ); ?>"><?php esc_html_e( 'Add Activity', 'smart-leading-net' ); ?></button></p>
			</div>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Card Heading', 'smart-leading-net' ); ?></span>
				<input type="text" class="large-text" name="sln_gp_how_work_tabs[<?php echo esc_attr( $index ); ?>][card_heading]" value="<?php echo esc_attr( $tab['card_heading'] ); ?>" />
			</label>
			<label class="sln-gp-admin__field-full sln-gp-admin__editor-field">
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Card Description', 'smart-leading-net' ); ?></span>
				<?php
				sln_growth_page_render_wysiwyg_editor(
					'sln_gp_how_work_card_description_' . $index,
					'sln_gp_how_work_tabs[' . $index . '][card_description]',
					$tab['card_description'],
					array(
						'textarea_rows' => 6,
					)
				);
				?>
			</label>
			<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
				<label>
					<span class="sln-os-admin__field-label">
						<?php
						printf(
							/* translators: %d: stat number */
							esc_html__( 'Stat %d Number', 'smart-leading-net' ),
							(int) $i
						);
						?>
					</span>
					<input type="text" class="regular-text" name="sln_gp_how_work_tabs[<?php echo esc_attr( $index ); ?>][stat_<?php echo esc_attr( (string) $i ); ?>_number]" value="<?php echo esc_attr( $tab[ 'stat_' . $i . '_number' ] ); ?>" />
				</label>
				<label>
					<span class="sln-os-admin__field-label">
						<?php
						printf(
							/* translators: %d: stat number */
							esc_html__( 'Stat %d Label', 'smart-leading-net' ),
							(int) $i
						);
						?>
					</span>
					<input type="text" class="large-text" name="sln_gp_how_work_tabs[<?php echo esc_attr( $index ); ?>][stat_<?php echo esc_attr( (string) $i ); ?>_label]" value="<?php echo esc_attr( $tab[ 'stat_' . $i . '_label' ] ); ?>" />
				</label>
			<?php endfor; ?>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Active Tab', 'smart-leading-net' ); ?></span>
				<select name="sln_gp_how_work_tabs[<?php echo esc_attr( $index ); ?>][active]">
					<option value="1" <?php selected( ! empty( $tab['active'] ) ); ?>><?php esc_html_e( 'Yes', 'smart-leading-net' ); ?></option>
					<option value="0" <?php selected( empty( $tab['active'] ) ); ?>><?php esc_html_e( 'No', 'smart-leading-net' ); ?></option>
				</select>
			</label>
		</div>
		<button type="button" class="button-link-delete sln-gp-admin__remove-how-work-tab"><?php esc_html_e( 'Remove Tab', 'smart-leading-net' ); ?></button>
	</div>
	<?php
}

/**
 * Render How Work meta box.
 *
 * @param WP_Post $post Current post.
 */
function sln_growth_page_render_how_work_meta_box( $post ) {
	wp_nonce_field( 'sln_growth_page_save_how_work', 'sln_growth_page_how_work_nonce', false );

	$defaults = sln_get_growth_page_default_how_work_section();
	$section  = get_post_meta( $post->ID, SLN_GP_HOW_WORK_SECTION_META, true );
	$section  = is_array( $section ) ? array_intersect_key( wp_parse_args( $section, $defaults ), $defaults ) : $defaults;
	$tabs     = sln_get_growth_page_how_work_tabs_for_admin( $post->ID );
	$orders   = sln_get_growth_page_section_orders( $post->ID );
	$order    = isset( $orders['how_work'] ) ? absint( $orders['how_work'] ) : 4;
	?>
	<div class="sln-gp-admin">
		<p class="description"><?php esc_html_e( 'Manage the How It Works tabbed section content and ordering.', 'smart-leading-net' ); ?></p>

		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><label for="sln_gp_how_work_label"><?php esc_html_e( 'Section Small Heading', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_how_work_label" name="sln_gp_how_work_section[label]" value="<?php echo esc_attr( $section['label'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Section Main Heading', 'smart-leading-net' ); ?></th>
					<td>
						<p><label for="sln_gp_how_work_heading_lead"><?php esc_html_e( 'Lead Text', 'smart-leading-net' ); ?></label><br />
						<input type="text" class="large-text" id="sln_gp_how_work_heading_lead" name="sln_gp_how_work_section[heading_lead]" value="<?php echo esc_attr( $section['heading_lead'] ); ?>" placeholder="<?php esc_attr_e( 'Inside Our', 'smart-leading-net' ); ?>" /></p>
						<p><label for="sln_gp_how_work_highlight_word_1"><?php esc_html_e( 'Highlight Word 1 (Orange)', 'smart-leading-net' ); ?></label><br />
						<input type="text" class="regular-text" id="sln_gp_how_work_highlight_word_1" name="sln_gp_how_work_section[highlight_word_1]" value="<?php echo esc_attr( $section['highlight_word_1'] ); ?>" /></p>
						<p><label for="sln_gp_how_work_highlight_word_2"><?php esc_html_e( 'Highlight Word 2 (Blue)', 'smart-leading-net' ); ?></label><br />
						<input type="text" class="regular-text" id="sln_gp_how_work_highlight_word_2" name="sln_gp_how_work_section[highlight_word_2]" value="<?php echo esc_attr( $section['highlight_word_2'] ); ?>" /></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_how_work_description"><?php esc_html_e( 'Section Description', 'smart-leading-net' ); ?></label></th>
					<td>
						<?php
						sln_growth_page_render_wysiwyg_editor(
							'sln_gp_how_work_description',
							'sln_gp_how_work_section[description]',
							$section['description']
						);
						?>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_section_order_how_work"><?php esc_html_e( 'Section Order Number', 'smart-leading-net' ); ?></label></th>
					<td>
						<input type="number" min="1" max="99" step="1" class="small-text" id="sln_gp_section_order_how_work" name="sln_gp_section_orders[how_work]" value="<?php echo esc_attr( $order ); ?>" />
					</td>
				</tr>
			</tbody>
		</table>

		<div class="sln-os-admin__subsection">
			<h3><?php esc_html_e( 'Tabs', 'smart-leading-net' ); ?></h3>
			<div class="sln-os-admin__repeatable sln-gp-admin__how-work-tabs-list">
				<?php foreach ( $tabs as $index => $tab ) : ?>
					<?php sln_growth_page_render_how_work_tab_row( $index, $tab ); ?>
				<?php endforeach; ?>
			</div>
			<p><button type="button" class="button button-secondary sln-gp-admin__add-how-work-tab"><?php esc_html_e( 'Add Tab', 'smart-leading-net' ); ?></button></p>
		</div>
	</div>
	<?php
}

/**
 * Save How Work meta box values.
 *
 * @param int $post_id Post ID.
 */
function sln_growth_page_save_how_work_meta( $post_id ) {
	if ( ! sln_growth_page_should_save_meta( $post_id, 'sln_growth_page_how_work_nonce', 'sln_growth_page_save_how_work' ) ) {
		return;
	}

	if ( isset( $_POST['sln_gp_how_work_section'] ) && is_array( $_POST['sln_gp_how_work_section'] ) ) {
		$raw     = wp_unslash( $_POST['sln_gp_how_work_section'] );
		$section = array(
			'label'            => isset( $raw['label'] ) ? sanitize_text_field( $raw['label'] ) : '',
			'heading_lead'     => isset( $raw['heading_lead'] ) ? sanitize_text_field( $raw['heading_lead'] ) : '',
			'highlight_word_1' => isset( $raw['highlight_word_1'] ) ? sanitize_text_field( $raw['highlight_word_1'] ) : '',
			'highlight_word_2' => isset( $raw['highlight_word_2'] ) ? sanitize_text_field( $raw['highlight_word_2'] ) : '',
			'description'      => isset( $raw['description'] ) ? sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ) : '',
		);

		update_post_meta( $post_id, SLN_GP_HOW_WORK_SECTION_META, $section );
	}

	sln_growth_page_update_repeater_meta(
		$post_id,
		SLN_GP_HOW_WORK_TABS_META,
		'sln_gp_how_work_tabs',
		'sln_sanitize_growth_page_how_work_tab',
		static function ( $tab ) {
			return '' !== trim( $tab['tab_label'] );
		}
	);
}
