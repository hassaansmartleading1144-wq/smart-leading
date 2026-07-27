<?php
/**
 * PPC & Google Ads page — ROI estimator section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_helpers.php';

$section  = sln_get_ppc_roi_section();
$controls = sln_get_ppc_roi_controls();
$outputs  = sln_get_ppc_roi_outputs();

if ( ! sln_ppc_row_is_active( $section ) ) {
	return;
}

$cpc = (float) ( $section['cpc'] ?? 2.5 );
$cvr = (float) ( $section['cvr'] ?? 0.03 );
$assumption_text = ! empty( $section['assumption_text'] ) ? $section['assumption_text'] : ( $section['assumptions_text'] ?? '' );
?>

<section id="roi" class="sln-ppc-section" aria-labelledby="sln-ppc-roi-heading">
	<div class="sls-container">
		<?php sln_ppc_part_render_heading( $section, 'sln-ppc-roi-heading', true, 'orange' ); ?>

		<div
			class="sln-ppc-roi sln-ppc-reveal"
			data-sln-ppc-roi
			data-cpc="<?php echo esc_attr( (string) $cpc ); ?>"
			data-cvr="<?php echo esc_attr( (string) $cvr ); ?>"
		>
			<div class="sln-ppc-roi-controls">
				<?php foreach ( $controls as $control ) : ?>
					<?php
					$key      = sanitize_key( $control['key'] ?? '' );
					$default  = (float) ( $control['default'] ?? 0 );
					$min      = (float) ( $control['min'] ?? 0 );
					$max      = (float) ( $control['max'] ?? 100 );
					$step     = (float) ( $control['step'] ?? 1 );
					$display  = ! empty( $control['display_value'] ) ? (string) $control['display_value'] : ( (string) ( $control['prefix'] ?? '' ) . number_format( $default ) . (string) ( $control['suffix'] ?? '' ) );
					$input_id = 'sln-ppc-roi-' . $key;
					?>
					<div class="sln-ppc-roi-control" data-sln-ppc-roi-control="<?php echo esc_attr( $key ); ?>">
						<div class="sln-ppc-roi-control-head">
							<label for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_html( $control['label'] ?? '' ); ?></label>
							<span class="sln-ppc-roi-value" data-sln-ppc-roi-control-value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $display ); ?></span>
						</div>
						<input
							id="<?php echo esc_attr( $input_id ); ?>"
							type="range"
							min="<?php echo esc_attr( (string) $min ); ?>"
							max="<?php echo esc_attr( (string) $max ); ?>"
							step="<?php echo esc_attr( (string) $step ); ?>"
							value="<?php echo esc_attr( (string) $default ); ?>"
							data-prefix="<?php echo esc_attr( $control['prefix'] ?? '' ); ?>"
							data-suffix="<?php echo esc_attr( $control['suffix'] ?? '' ); ?>"
						>
					</div>
				<?php endforeach; ?>

				<?php if ( ! empty( $assumption_text ) ) : ?>
					<div class="sln-ppc-assumption"><?php echo esc_html( $assumption_text ); ?></div>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $outputs ) ) : ?>
				<div class="sln-ppc-roi-output">
					<?php foreach ( $outputs as $output ) : ?>
						<?php
						$key        = sanitize_key( $output['key'] ?? '' );
						$value_cls  = sln_ppc_part_visual_class( 'sln-ppc-roi-tile-value', (string) ( $output['visual_style'] ?? '' ) );
						$tile_class = ! empty( $output['highlight'] ) ? 'sln-ppc-roi-tile sln-ppc-roi-tile--highlight' : 'sln-ppc-roi-tile';
						?>
						<div class="<?php echo esc_attr( $tile_class ); ?>">
							<div
								class="<?php echo esc_attr( $value_cls ); ?>"
								data-sln-ppc-roi-output="<?php echo esc_attr( $key ); ?>"
								data-prefix="<?php echo esc_attr( $output['prefix'] ?? '' ); ?>"
								data-suffix="<?php echo esc_attr( $output['suffix'] ?? '' ); ?>"
							><?php echo esc_html( $output['display_value'] ?? '' ); ?></div>
							<?php if ( ! empty( $output['label'] ) ) : ?>
								<div class="sln-ppc-roi-tile-label"><?php echo esc_html( $output['label'] ); ?></div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $section['disclaimer'] ) ) : ?>
			<div class="sln-ppc-disclaimer"><?php echo esc_html( $section['disclaimer'] ); ?></div>
		<?php endif; ?>
	</div>
</section>
