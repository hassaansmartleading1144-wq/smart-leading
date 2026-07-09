<?php
/**
 * Growth Pages — Client Story section meta box and helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_GP_CLIENT_STORY_SECTION_META', '_sln_gp_client_story_section' );
define( 'SLN_GP_CLIENT_STORY_STEPS_META', '_sln_gp_client_story_steps' );
define( 'SLN_GP_CLIENT_STORY_RESULTS_META', '_sln_gp_client_story_results' );

/**
 * Default Client Story section content.
 *
 * @return array<string, string>
 */
function sln_get_growth_page_default_client_story_section() {
	return array(
		'label'                 => __( 'Client Success Story', 'smart-leading-net' ),
		'heading_lead'          => __( 'From Wasted Ad Spend to', 'smart-leading-net' ),
		'heading_highlight'     => __( '$50M+', 'smart-leading-net' ),
		'heading_trail'         => __( 'in Sales', 'smart-leading-net' ),
		'description'           => __( 'A Flooring Success Story — How we transformed a struggling in-house ad account into a revenue-generating machine.', 'smart-leading-net' ),
		'challenge_label'       => __( 'The Challenge', 'smart-leading-net' ),
		'challenge_heading'     => __( 'The Costly Trap of Self-Managed Ads', 'smart-leading-net' ),
		'challenge_description' => __( "Sam's Flooring was managing their Google Ads entirely in-house. Without specialized optimization, they fell into a common trap — paying premium prices for generic keywords, driving irrelevant traffic, and bleeding budget on clicks that never converted.", 'smart-leading-net' ),
		'strategy_label'        => __( 'The Strategy', 'smart-leading-net' ),
		'strategy_heading'      => __( 'Precision Google Ads Optimization', 'smart-leading-net' ),
		'results_title'         => __( 'The Results: A Predictable Revenue Engine', 'smart-leading-net' ),
		'revenue_number'        => __( '$50M+', 'smart-leading-net' ),
		'revenue_label'         => __( 'Total Revenue Generated', 'smart-leading-net' ),
		'quote'                 => __( 'We stopped the bleeding, dialed in on high-converting search intent, and transformed their Google Ads into a scalable asset that generated over $50M in revenue.', 'smart-leading-net' ),
	);
}

/**
 * Default strategy steps.
 *
 * @return array<int, array<string, string>>
 */
function sln_get_growth_page_default_client_story_steps() {
	return array(
		array(
			'number'      => '01',
			'title'       => __( 'Eliminating Keyword Waste', 'smart-leading-net' ),
			'description' => __( 'Stripped broad-match keywords draining budget on non-buying searches, shifting entirely to high-intent phrases used by serious buyers.', 'smart-leading-net' ),
		),
		array(
			'number'      => '02',
			'title'       => __( 'Geotargeting & Audience Calibration', 'smart-leading-net' ),
			'description' => __( 'Optimized location targeting to focus on regions with high concentrations of homeowners, interior designers & contractors.', 'smart-leading-net' ),
		),
		array(
			'number'      => '03',
			'title'       => __( 'Conversion Funnel Restructuring', 'smart-leading-net' ),
			'description' => __( 'Reworked ad messaging and landing page flow, moving users seamlessly from search intent straight to booking a quote.', 'smart-leading-net' ),
		),
	);
}

/**
 * Default results table rows.
 *
 * @return array<int, array<string, string>>
 */
function sln_get_growth_page_default_client_story_results() {
	return array(
		array(
			'metric' => __( 'Ad Spend Efficiency', 'smart-leading-net' ),
			'before' => __( 'High Waste', 'smart-leading-net' ),
			'after'  => __( '$0 Wasted', 'smart-leading-net' ),
		),
		array(
			'metric' => __( 'Customer Acquisition Cost', 'smart-leading-net' ),
			'before' => __( 'Volatile & High', 'smart-leading-net' ),
			'after'  => __( 'Optimized', 'smart-leading-net' ),
		),
		array(
			'metric' => __( 'Conversion Rate', 'smart-leading-net' ),
			'before' => __( 'Low / Minimal', 'smart-leading-net' ),
			'after'  => __( '320% Up', 'smart-leading-net' ),
		),
		array(
			'metric' => __( 'Total Revenue', 'smart-leading-net' ),
			'before' => __( 'Stagnant', 'smart-leading-net' ),
			'after'  => __( '$50M+', 'smart-leading-net' ),
		),
	);
}

/**
 * Sanitize a strategy step row.
 *
 * @param array<string, mixed> $step Raw step data.
 * @return array<string, string>
 */
function sln_sanitize_growth_page_client_story_step( $step ) {
	if ( ! is_array( $step ) ) {
		return array();
	}

	$number = isset( $step['number'] ) ? sanitize_text_field( wp_unslash( $step['number'] ) ) : '';
	$number = preg_replace( '/[^0-9]/', '', $number );
	$number = '' !== $number ? str_pad( $number, 2, '0', STR_PAD_LEFT ) : '';

	return array(
		'number'      => $number,
		'title'       => isset( $step['title'] ) ? sanitize_text_field( wp_unslash( $step['title'] ) ) : '',
		'description' => isset( $step['description'] ) ? sln_growth_page_sanitize_wysiwyg_content( $step['description'] ) : '',
	);
}

/**
 * Sanitize a results table row.
 *
 * @param array<string, mixed> $row Raw row data.
 * @return array<string, string>
 */
function sln_sanitize_growth_page_client_story_result( $row ) {
	if ( ! is_array( $row ) ) {
		return array();
	}

	return array(
		'metric' => isset( $row['metric'] ) ? sanitize_text_field( wp_unslash( $row['metric'] ) ) : '',
		'before' => isset( $row['before'] ) ? sanitize_text_field( wp_unslash( $row['before'] ) ) : '',
		'after'  => isset( $row['after'] ) ? sanitize_text_field( wp_unslash( $row['after'] ) ) : '',
	);
}

/**
 * Get Client Story section data for frontend.
 *
 * @param int|null $post_id Post ID.
 * @return array<string, mixed>
 */
function sln_get_growth_page_client_story( $post_id = null ) {
	$post_id  = $post_id ? absint( $post_id ) : get_the_ID();
	$defaults = sln_get_growth_page_default_client_story_section();

	$section = sln_growth_page_get_section_settings( $post_id, SLN_GP_CLIENT_STORY_SECTION_META, $defaults );
	$steps   = sln_growth_page_get_repeater_rows( $post_id, SLN_GP_CLIENT_STORY_STEPS_META, sln_get_growth_page_default_client_story_steps() );
	$results = sln_growth_page_get_repeater_rows( $post_id, SLN_GP_CLIENT_STORY_RESULTS_META, sln_get_growth_page_default_client_story_results() );

	if ( metadata_exists( 'post', $post_id, SLN_GP_CLIENT_STORY_STEPS_META ) ) {
		$steps = array_values(
			array_filter(
				array_map( 'sln_sanitize_growth_page_client_story_step', $steps ),
				static function ( $step ) {
					return ! empty( $step['title'] ) || sln_growth_page_wysiwyg_has_content( $step['description'] );
				}
			)
		);
	}

	if ( metadata_exists( 'post', $post_id, SLN_GP_CLIENT_STORY_RESULTS_META ) ) {
		$results = array_values(
			array_filter(
				array_map( 'sln_sanitize_growth_page_client_story_result', $results ),
				static function ( $row ) {
					return ! empty( $row['metric'] );
				}
			)
		);
	}

	foreach ( $steps as $index => $step ) {
		if ( '' === trim( $step['number'] ) ) {
			$steps[ $index ]['number'] = str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT );
		}
	}

	return array(
		'section' => $section,
		'steps'   => $steps,
		'results' => $results,
	);
}

/**
 * Check whether Client Story section should render.
 *
 * @param int|null $post_id Post ID.
 * @return bool
 */
function sln_growth_page_client_story_has_content( $post_id = null ) {
	$data = sln_get_growth_page_client_story( $post_id );

	return ! empty( $data['section']['label'] )
		|| ! empty( $data['section']['heading_lead'] )
		|| ! empty( $data['section']['heading_highlight'] )
		|| ! empty( $data['steps'] )
		|| ! empty( $data['results'] );
}

/**
 * Get raw strategy steps for admin.
 *
 * @param int $post_id Post ID.
 * @return array<int, array<string, string>>
 */
function sln_get_growth_page_client_story_steps_for_admin( $post_id ) {
	if ( ! metadata_exists( 'post', $post_id, SLN_GP_CLIENT_STORY_STEPS_META ) ) {
		return sln_get_growth_page_default_client_story_steps();
	}

	$steps = get_post_meta( $post_id, SLN_GP_CLIENT_STORY_STEPS_META, true );

	return is_array( $steps ) ? $steps : array();
}

/**
 * Get raw results rows for admin.
 *
 * @param int $post_id Post ID.
 * @return array<int, array<string, string>>
 */
function sln_get_growth_page_client_story_results_for_admin( $post_id ) {
	if ( ! metadata_exists( 'post', $post_id, SLN_GP_CLIENT_STORY_RESULTS_META ) ) {
		return sln_get_growth_page_default_client_story_results();
	}

	$results = get_post_meta( $post_id, SLN_GP_CLIENT_STORY_RESULTS_META, true );

	return is_array( $results ) ? $results : array();
}

/**
 * Register Client Story meta box.
 */
function sln_growth_page_register_client_story_meta_box() {
	add_meta_box(
		'sln-growth-page-client-story',
		__( 'Client Story', 'smart-leading-net' ),
		'sln_growth_page_render_client_story_meta_box',
		SLN_GROWTH_PAGE_POST_TYPE,
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'sln_growth_page_register_client_story_meta_box' );

/**
 * Render a strategy step row in admin.
 *
 * @param int                  $index Step index.
 * @param array<string, string> $step Step data.
 */
function sln_growth_page_render_client_story_step_row( $index, $step ) {
	$step = wp_parse_args(
		$step,
		array(
			'number'      => str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ),
			'title'       => '',
			'description' => '',
		)
	);
	?>
	<div class="sln-os-admin__repeatable-row sln-gp-admin__step-row">
		<div class="sln-gp-admin__card-controls">
			<button type="button" class="button button-small sln-gp-admin__step-move-up"><?php esc_html_e( 'Move Up', 'smart-leading-net' ); ?></button>
			<button type="button" class="button button-small sln-gp-admin__step-move-down"><?php esc_html_e( 'Move Down', 'smart-leading-net' ); ?></button>
		</div>
		<div class="sln-os-admin__repeatable-fields sln-gp-admin__card-fields">
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Step Number', 'smart-leading-net' ); ?></span>
				<input type="text" class="small-text" name="sln_gp_client_story_steps[<?php echo esc_attr( $index ); ?>][number]" value="<?php echo esc_attr( $step['number'] ); ?>" placeholder="01" />
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Step Title', 'smart-leading-net' ); ?></span>
				<input type="text" class="large-text" name="sln_gp_client_story_steps[<?php echo esc_attr( $index ); ?>][title]" value="<?php echo esc_attr( $step['title'] ); ?>" />
			</label>
			<label class="sln-gp-admin__field-full sln-gp-admin__editor-field">
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Step Description', 'smart-leading-net' ); ?></span>
				<?php
				sln_growth_page_render_wysiwyg_editor(
					'sln_gp_client_story_step_description_' . $index,
					'sln_gp_client_story_steps[' . $index . '][description]',
					$step['description']
				);
				?>
			</label>
		</div>
		<button type="button" class="button-link-delete sln-gp-admin__remove-step"><?php esc_html_e( 'Remove Step', 'smart-leading-net' ); ?></button>
	</div>
	<?php
}

/**
 * Render a results table row in admin.
 *
 * @param int                  $index Row index.
 * @param array<string, string> $row  Row data.
 */
function sln_growth_page_render_client_story_result_row( $index, $row ) {
	$row = wp_parse_args(
		$row,
		array(
			'metric' => '',
			'before' => '',
			'after'  => '',
		)
	);
	?>
	<div class="sln-os-admin__repeatable-row sln-gp-admin__result-row">
		<div class="sln-gp-admin__card-controls">
			<button type="button" class="button button-small sln-gp-admin__result-move-up"><?php esc_html_e( 'Move Up', 'smart-leading-net' ); ?></button>
			<button type="button" class="button button-small sln-gp-admin__result-move-down"><?php esc_html_e( 'Move Down', 'smart-leading-net' ); ?></button>
		</div>
		<div class="sln-os-admin__repeatable-fields sln-gp-admin__card-fields">
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Metric', 'smart-leading-net' ); ?></span>
				<input type="text" class="large-text" name="sln_gp_client_story_results[<?php echo esc_attr( $index ); ?>][metric]" value="<?php echo esc_attr( $row['metric'] ); ?>" />
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Before SLS', 'smart-leading-net' ); ?></span>
				<input type="text" class="large-text" name="sln_gp_client_story_results[<?php echo esc_attr( $index ); ?>][before]" value="<?php echo esc_attr( $row['before'] ); ?>" />
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'After SLS', 'smart-leading-net' ); ?></span>
				<input type="text" class="large-text" name="sln_gp_client_story_results[<?php echo esc_attr( $index ); ?>][after]" value="<?php echo esc_attr( $row['after'] ); ?>" />
			</label>
		</div>
		<button type="button" class="button-link-delete sln-gp-admin__remove-result"><?php esc_html_e( 'Remove Row', 'smart-leading-net' ); ?></button>
	</div>
	<?php
}

/**
 * Render Client Story meta box.
 *
 * @param WP_Post $post Current post.
 */
function sln_growth_page_render_client_story_meta_box( $post ) {
	wp_nonce_field( 'sln_growth_page_save_client_story', 'sln_growth_page_client_story_nonce', false );

	$section = get_post_meta( $post->ID, SLN_GP_CLIENT_STORY_SECTION_META, true );
	$defaults = sln_get_growth_page_default_client_story_section();
	$section = is_array( $section ) ? array_intersect_key( wp_parse_args( $section, $defaults ), $defaults ) : $defaults;
	$steps   = sln_get_growth_page_client_story_steps_for_admin( $post->ID );
	$results = sln_get_growth_page_client_story_results_for_admin( $post->ID );
	$orders  = sln_get_growth_page_section_orders( $post->ID );
	$order   = isset( $orders['client_story'] ) ? absint( $orders['client_story'] ) : 3;
	?>
	<div class="sln-gp-admin">
		<p class="description"><?php esc_html_e( 'Manage the Client Success Story section content and ordering.', 'smart-leading-net' ); ?></p>

		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><label for="sln_gp_client_story_label"><?php esc_html_e( 'Section Small Heading', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_client_story_label" name="sln_gp_client_story_section[label]" value="<?php echo esc_attr( $section['label'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Main Heading', 'smart-leading-net' ); ?></th>
					<td>
						<p><label for="sln_gp_client_story_heading_lead"><?php esc_html_e( 'Lead Text', 'smart-leading-net' ); ?></label><br />
						<input type="text" class="large-text" id="sln_gp_client_story_heading_lead" name="sln_gp_client_story_section[heading_lead]" value="<?php echo esc_attr( $section['heading_lead'] ); ?>" /></p>
						<p><label for="sln_gp_client_story_heading_highlight"><?php esc_html_e( 'Highlight Text (Orange)', 'smart-leading-net' ); ?></label><br />
						<input type="text" class="regular-text" id="sln_gp_client_story_heading_highlight" name="sln_gp_client_story_section[heading_highlight]" value="<?php echo esc_attr( $section['heading_highlight'] ); ?>" /></p>
						<p><label for="sln_gp_client_story_heading_trail"><?php esc_html_e( 'Trailing Text', 'smart-leading-net' ); ?></label><br />
						<input type="text" class="regular-text" id="sln_gp_client_story_heading_trail" name="sln_gp_client_story_section[heading_trail]" value="<?php echo esc_attr( $section['heading_trail'] ); ?>" /></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_client_story_description"><?php esc_html_e( 'Description', 'smart-leading-net' ); ?></label></th>
					<td>
						<?php
						sln_growth_page_render_wysiwyg_editor(
							'sln_gp_client_story_description',
							'sln_gp_client_story_section[description]',
							$section['description']
						);
						?>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_client_story_challenge_label"><?php esc_html_e( 'Challenge Label', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="regular-text" id="sln_gp_client_story_challenge_label" name="sln_gp_client_story_section[challenge_label]" value="<?php echo esc_attr( $section['challenge_label'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_client_story_challenge_heading"><?php esc_html_e( 'Challenge Heading', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_client_story_challenge_heading" name="sln_gp_client_story_section[challenge_heading]" value="<?php echo esc_attr( $section['challenge_heading'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_client_story_challenge_description"><?php esc_html_e( 'Challenge Description', 'smart-leading-net' ); ?></label></th>
					<td>
						<?php
						sln_growth_page_render_wysiwyg_editor(
							'sln_gp_client_story_challenge_description',
							'sln_gp_client_story_section[challenge_description]',
							$section['challenge_description']
						);
						?>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_client_story_strategy_label"><?php esc_html_e( 'Strategy Label', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="regular-text" id="sln_gp_client_story_strategy_label" name="sln_gp_client_story_section[strategy_label]" value="<?php echo esc_attr( $section['strategy_label'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_client_story_strategy_heading"><?php esc_html_e( 'Strategy Heading', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_client_story_strategy_heading" name="sln_gp_client_story_section[strategy_heading]" value="<?php echo esc_attr( $section['strategy_heading'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_client_story_results_title"><?php esc_html_e( 'Results Title', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_client_story_results_title" name="sln_gp_client_story_section[results_title]" value="<?php echo esc_attr( $section['results_title'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_client_story_revenue_number"><?php esc_html_e( 'Revenue Result Number', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="regular-text" id="sln_gp_client_story_revenue_number" name="sln_gp_client_story_section[revenue_number]" value="<?php echo esc_attr( $section['revenue_number'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_client_story_revenue_label"><?php esc_html_e( 'Revenue Result Label', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_client_story_revenue_label" name="sln_gp_client_story_section[revenue_label]" value="<?php echo esc_attr( $section['revenue_label'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_client_story_quote"><?php esc_html_e( 'Quote', 'smart-leading-net' ); ?></label></th>
					<td>
						<?php
						sln_growth_page_render_wysiwyg_editor(
							'sln_gp_client_story_quote',
							'sln_gp_client_story_section[quote]',
							$section['quote']
						);
						?>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_section_order_client_story"><?php esc_html_e( 'Section Order Number', 'smart-leading-net' ); ?></label></th>
					<td>
						<input type="number" min="1" max="99" step="1" class="small-text" id="sln_gp_section_order_client_story" name="sln_gp_section_orders[client_story]" value="<?php echo esc_attr( $order ); ?>" />
					</td>
				</tr>
			</tbody>
		</table>

		<div class="sln-os-admin__subsection">
			<h3><?php esc_html_e( 'Strategy Steps', 'smart-leading-net' ); ?></h3>
			<div class="sln-os-admin__repeatable sln-gp-admin__steps-list">
				<?php foreach ( $steps as $index => $step ) : ?>
					<?php sln_growth_page_render_client_story_step_row( $index, $step ); ?>
				<?php endforeach; ?>
			</div>
			<p><button type="button" class="button button-secondary sln-gp-admin__add-step"><?php esc_html_e( 'Add Step', 'smart-leading-net' ); ?></button></p>
		</div>

		<div class="sln-os-admin__subsection">
			<h3><?php esc_html_e( 'Results Table', 'smart-leading-net' ); ?></h3>
			<div class="sln-os-admin__repeatable sln-gp-admin__results-list">
				<?php foreach ( $results as $index => $row ) : ?>
					<?php sln_growth_page_render_client_story_result_row( $index, $row ); ?>
				<?php endforeach; ?>
			</div>
			<p><button type="button" class="button button-secondary sln-gp-admin__add-result"><?php esc_html_e( 'Add Result Row', 'smart-leading-net' ); ?></button></p>
		</div>
	</div>
	<?php
}

/**
 * Save Client Story meta box values.
 *
 * @param int $post_id Post ID.
 */
function sln_growth_page_save_client_story_meta( $post_id ) {
	if ( ! sln_growth_page_should_save_meta( $post_id, 'sln_growth_page_client_story_nonce', 'sln_growth_page_save_client_story' ) ) {
		return;
	}

	if ( isset( $_POST['sln_gp_client_story_section'] ) && is_array( $_POST['sln_gp_client_story_section'] ) ) {
		$raw     = wp_unslash( $_POST['sln_gp_client_story_section'] );
		$section = array(
			'label'                 => isset( $raw['label'] ) ? sanitize_text_field( $raw['label'] ) : '',
			'heading_lead'          => isset( $raw['heading_lead'] ) ? sanitize_text_field( $raw['heading_lead'] ) : '',
			'heading_highlight'     => isset( $raw['heading_highlight'] ) ? sanitize_text_field( $raw['heading_highlight'] ) : '',
			'heading_trail'         => isset( $raw['heading_trail'] ) ? sanitize_text_field( $raw['heading_trail'] ) : '',
			'description'           => isset( $raw['description'] ) ? sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ) : '',
			'challenge_label'       => isset( $raw['challenge_label'] ) ? sanitize_text_field( $raw['challenge_label'] ) : '',
			'challenge_heading'     => isset( $raw['challenge_heading'] ) ? sanitize_text_field( $raw['challenge_heading'] ) : '',
			'challenge_description' => isset( $raw['challenge_description'] ) ? sln_growth_page_sanitize_wysiwyg_content( $raw['challenge_description'] ) : '',
			'strategy_label'        => isset( $raw['strategy_label'] ) ? sanitize_text_field( $raw['strategy_label'] ) : '',
			'strategy_heading'      => isset( $raw['strategy_heading'] ) ? sanitize_text_field( $raw['strategy_heading'] ) : '',
			'results_title'         => isset( $raw['results_title'] ) ? sanitize_text_field( $raw['results_title'] ) : '',
			'revenue_number'        => isset( $raw['revenue_number'] ) ? sanitize_text_field( $raw['revenue_number'] ) : '',
			'revenue_label'         => isset( $raw['revenue_label'] ) ? sanitize_text_field( $raw['revenue_label'] ) : '',
			'quote'                 => isset( $raw['quote'] ) ? sln_growth_page_sanitize_wysiwyg_content( $raw['quote'] ) : '',
		);

		update_post_meta( $post_id, SLN_GP_CLIENT_STORY_SECTION_META, $section );
	}

	sln_growth_page_update_repeater_meta(
		$post_id,
		SLN_GP_CLIENT_STORY_STEPS_META,
		'sln_gp_client_story_steps',
		'sln_sanitize_growth_page_client_story_step',
		static function ( $step ) {
			return '' !== trim( $step['title'] ) || sln_growth_page_wysiwyg_has_content( $step['description'] );
		}
	);

	sln_growth_page_update_repeater_meta(
		$post_id,
		SLN_GP_CLIENT_STORY_RESULTS_META,
		'sln_gp_client_story_results',
		'sln_sanitize_growth_page_client_story_result',
		static function ( $row ) {
			return '' !== trim( $row['metric'] );
		}
	);
}
