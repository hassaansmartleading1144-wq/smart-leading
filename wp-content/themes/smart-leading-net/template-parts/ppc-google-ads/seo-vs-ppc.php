<?php
/**
 * PPC hub — SEO vs PPC toggle comparison.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_helpers.php';

$sln_args = isset( $args ) && is_array( $args ) ? $args : array();
$data     = $sln_args['data'] ?? sln_ppc_seo_vs_ppc();
$section  = $sln_args['section'] ?? array(
	'small_heading'    => __( 'SEO vs PPC', 'smart-leading-net' ),
	'main_heading'     => __( 'SEO vs PPC:', 'smart-leading-net' ),
	'highlighted_text' => __( 'Which Does Your Business Need?', 'smart-leading-net' ),
	'description'      => __( 'Both bring customers from search, on different timelines and budgets. Switch between them to compare, or see how SEO and PPC work together.', 'smart-leading-net' ),
);
$order    = $sln_args['order'] ?? array( 'ppc', 'seo', 'both' );
$sid      = sanitize_key( $sln_args['id'] ?? 'seo-vs-ppc' );
$link_lbl = $data['link_label'] ?? __( 'Explore our SEO services', 'smart-leading-net' );
$link_url = $data['link_url'] ?? ( $data['seo_url'] ?? '' );
?>

<section id="<?php echo esc_attr( $sid ); ?>" class="sln-ppc-section sln-ppc-section--dark sln-ppc-grid-bg sln-svp" aria-labelledby="<?php echo esc_attr( $sid ); ?>-heading">
	<div class="sls-container">
		<?php sln_ppc_part_render_heading( $section, $sid . '-heading', true, 'azure' ); ?>

		<div class="sln-svp-wrap sln-ppc-reveal" data-sln-svp>
			<div class="sln-svp-toggle" role="tablist" aria-label="<?php esc_attr_e( 'Compare SEO and PPC', 'smart-leading-net' ); ?>">
				<?php foreach ( $order as $i => $key ) : ?>
					<button type="button" role="tab" class="sln-svp-btn<?php echo 0 === $i ? ' is-on' : ''; ?>" data-side="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $sid ); ?>-tab-<?php echo esc_attr( $key ); ?>" aria-controls="<?php echo esc_attr( $sid ); ?>-panel-<?php echo esc_attr( $key ); ?>" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>">
						<?php echo esc_html( $data['sides'][ $key ]['label'] ); ?>
					</button>
				<?php endforeach; ?>
				<span class="sln-svp-thumb" aria-hidden="true"></span>
			</div>

			<div class="sln-svp-panels">
				<?php foreach ( $order as $i => $key ) : ?>
					<?php $side = $data['sides'][ $key ]; ?>
					<div class="sln-svp-panel sln-svp-panel--<?php echo esc_attr( $key ); ?><?php echo 0 === $i ? ' is-active' : ''; ?>" role="tabpanel" id="<?php echo esc_attr( $sid ); ?>-panel-<?php echo esc_attr( $key ); ?>" aria-labelledby="<?php echo esc_attr( $sid ); ?>-tab-<?php echo esc_attr( $key ); ?>">
						<div class="sln-svp-head">
							<span class="sln-svp-icon"><?php echo sln_ppc_icon( $side['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<h3><?php echo esc_html( $side['title'] ); ?></h3>
						</div>
						<dl class="sln-svp-rows">
							<?php foreach ( $data['rows'] as $r => $row ) : ?>
								<div class="sln-svp-row" style="--i:<?php echo (int) $r; ?>">
									<dt><?php echo esc_html( $row ); ?></dt>
									<dd><?php echo esc_html( $side['values'][ $r ] ?? '' ); ?></dd>
								</div>
							<?php endforeach; ?>
						</dl>
						<?php if ( 'both' === $key && $link_url ) : ?>
							<a class="sln-svp-link" href="<?php echo esc_url( $link_url ); ?>"><?php echo esc_html( $link_lbl ); ?> <span aria-hidden="true">→</span></a>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
