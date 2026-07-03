<?php
/**
 * Growth Pages — Growth Metrics section meta box and helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_GP_GROWTH_METRICS_META', '_sln_gp_growth_metrics' );

/**
 * Default Growth Metrics section content.
 *
 * @return array<string, string>
 */
function sln_get_growth_page_default_growth_metrics() {
	return array(
		'metrics_heading'        => __( 'Growth Metrics That Matter', 'smart-leading-net' ),
		'revenue_growth_label'   => __( 'Revenue Growth', 'smart-leading-net' ),
		'revenue_growth_value'   => '$50M+',
		'roas_label'             => __( 'ROAS Increase', 'smart-leading-net' ),
		'roas_value'             => '3.5x',
		'leads_generated_label'  => __( 'Leads Generated', 'smart-leading-net' ),
		'leads_generated_value'  => '100k+',
		'conversion_boost_label' => __( 'ROI Boosted', 'smart-leading-net' ),
		'conversion_boost_value' => '3x',
	);
}

/**
 * Get Growth Metrics data for a Growth Page.
 *
 * @param int|null $post_id Post ID.
 * @return array<string, string>
 */
function sln_get_growth_page_growth_metrics( $post_id = null ) {
	$post_id  = $post_id ? absint( $post_id ) : get_the_ID();
	$defaults = sln_get_growth_page_default_growth_metrics();

	return sln_growth_page_get_section_settings( $post_id, SLN_GP_GROWTH_METRICS_META, $defaults );
}

/**
 * Register Growth Metrics meta box.
 */
function sln_growth_page_register_growth_metrics_meta_box() {
	add_meta_box(
		'sln-growth-page-growth-metrics',
		__( 'Growth Metrics Section', 'smart-leading-net' ),
		'sln_growth_page_render_growth_metrics_meta_box',
		SLN_GROWTH_PAGE_POST_TYPE,
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'sln_growth_page_register_growth_metrics_meta_box' );

/**
 * Render Growth Metrics meta box.
 *
 * @param WP_Post $post Current post.
 */
function sln_growth_page_render_growth_metrics_meta_box( $post ) {
	wp_nonce_field( 'sln_growth_page_save_growth_metrics', 'sln_growth_page_growth_metrics_nonce', false );

	$defaults = sln_get_growth_page_default_growth_metrics();
	$metrics  = sln_get_growth_page_growth_metrics( $post->ID );
	$metrics  = array_intersect_key( wp_parse_args( $metrics, $defaults ), $defaults );
	?>
	<div class="sln-gp-admin">
		<p class="description">
			<?php esc_html_e( 'Metric values shown in the hero "Growth Metrics That Matter" card. Each Growth Page can use different numbers.', 'smart-leading-net' ); ?>
		</p>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><label for="sln_gp_growth_metrics_heading"><?php esc_html_e( 'Metrics Heading', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_growth_metrics_heading" name="sln_gp_growth_metrics[metrics_heading]" value="<?php echo esc_attr( $metrics['metrics_heading'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_growth_metrics_revenue_label"><?php esc_html_e( 'Revenue Growth Label', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_growth_metrics_revenue_label" name="sln_gp_growth_metrics[revenue_growth_label]" value="<?php echo esc_attr( $metrics['revenue_growth_label'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_growth_metrics_revenue_value"><?php esc_html_e( 'Revenue Growth Value', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_growth_metrics_revenue_value" name="sln_gp_growth_metrics[revenue_growth_value]" value="<?php echo esc_attr( $metrics['revenue_growth_value'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_growth_metrics_roas_label"><?php esc_html_e( 'ROAS Label', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_growth_metrics_roas_label" name="sln_gp_growth_metrics[roas_label]" value="<?php echo esc_attr( $metrics['roas_label'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_growth_metrics_roas_value"><?php esc_html_e( 'ROAS Value', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_growth_metrics_roas_value" name="sln_gp_growth_metrics[roas_value]" value="<?php echo esc_attr( $metrics['roas_value'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_growth_metrics_leads_label"><?php esc_html_e( 'Leads Generated Label', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_growth_metrics_leads_label" name="sln_gp_growth_metrics[leads_generated_label]" value="<?php echo esc_attr( $metrics['leads_generated_label'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_growth_metrics_leads_value"><?php esc_html_e( 'Leads Generated Value', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_growth_metrics_leads_value" name="sln_gp_growth_metrics[leads_generated_value]" value="<?php echo esc_attr( $metrics['leads_generated_value'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_growth_metrics_conversion_label"><?php esc_html_e( 'Conversion Boost Label', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_growth_metrics_conversion_label" name="sln_gp_growth_metrics[conversion_boost_label]" value="<?php echo esc_attr( $metrics['conversion_boost_label'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_growth_metrics_conversion_value"><?php esc_html_e( 'Conversion Boost Value', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_growth_metrics_conversion_value" name="sln_gp_growth_metrics[conversion_boost_value]" value="<?php echo esc_attr( $metrics['conversion_boost_value'] ); ?>" /></td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php
}

/**
 * Save Growth Metrics meta box values.
 *
 * @param int $post_id Post ID.
 */
function sln_growth_page_save_growth_metrics_meta( $post_id ) {
	if ( ! sln_growth_page_should_save_meta( $post_id, 'sln_growth_page_growth_metrics_nonce', 'sln_growth_page_save_growth_metrics' ) ) {
		return;
	}

	if ( ! isset( $_POST['sln_gp_growth_metrics'] ) || ! is_array( $_POST['sln_gp_growth_metrics'] ) ) {
		return;
	}

	$raw      = wp_unslash( $_POST['sln_gp_growth_metrics'] );
	$defaults = sln_get_growth_page_default_growth_metrics();
	$metrics  = array();

	foreach ( array_keys( $defaults ) as $key ) {
		$metrics[ $key ] = isset( $raw[ $key ] ) ? sanitize_text_field( $raw[ $key ] ) : '';
	}

	update_post_meta( $post_id, SLN_GP_GROWTH_METRICS_META, $metrics );
}
