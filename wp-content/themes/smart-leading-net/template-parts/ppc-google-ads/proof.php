<?php
/**
 * PPC & Google Ads page — proof/case studies section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_helpers.php';

$section = sln_get_ppc_proof_section();
$cases   = sln_get_ppc_case_studies();

if ( ! sln_ppc_row_is_active( $section ) ) {
	return;
}
?>

<section id="proof" class="sln-ppc-section" aria-labelledby="sln-ppc-proof-heading">
	<div class="sls-container">
		<?php sln_ppc_part_render_heading( $section, 'sln-ppc-proof-heading', false, 'orange' ); ?>

		<?php if ( ! empty( $cases ) ) : ?>
			<div class="sln-ppc-cases">
				<?php foreach ( $cases as $case ) : ?>
					<?php
					$progress = is_array( $case['progress'] ?? null ) ? $case['progress'] : array();
					$p_label  = $progress['label'] ?? ( $case['progress_label'] ?? '' );
					$p_value  = $progress['value'] ?? ( $case['progress_value'] ?? '' );
					$p_width  = $progress['width'] ?? ( $case['progress_percent'] ?? ( $case['progress_percentage'] ?? 72 ) );
					$p_width  = max( 0, min( 100, (int) $p_width ) );
					?>
					<article class="sln-ppc-case sln-ppc-reveal">
						<div class="sln-ppc-case-head">
							<?php if ( ! empty( $case['name'] ) ) : ?>
								<h3 class="sln-ppc-case-name"><?php echo esc_html( $case['name'] ); ?></h3>
							<?php endif; ?>
							<?php if ( ! empty( $case['tag'] ) ) : ?>
								<span class="sln-ppc-case-type"><?php echo esc_html( $case['tag'] ); ?></span>
							<?php endif; ?>
						</div>

						<?php if ( ! empty( $case['metrics'] ) && is_array( $case['metrics'] ) ) : ?>
							<div class="sln-ppc-figures" data-sln-ppc-counts>
								<?php foreach ( $case['metrics'] as $metric ) : ?>
									<?php
									if ( ! is_array( $metric ) ) {
										continue;
									}
									$display = sln_ppc_part_number_display( $metric );
									$class   = sln_ppc_part_visual_class( 'sln-ppc-figure-value', (string) ( $metric['visual_style'] ?? '' ) );
									?>
									<div class="sln-ppc-figure">
										<div class="<?php echo esc_attr( $class ); ?>">
											<span class="sln-ppc-count" <?php echo sln_ppc_part_numeric_attrs( $metric ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $display ); ?></span>
										</div>
										<?php if ( ! empty( $metric['label'] ) ) : ?>
											<div class="sln-ppc-figure-label"><?php echo esc_html( $metric['label'] ); ?></div>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

						<?php if ( $p_label || $p_value ) : ?>
							<div class="sln-ppc-progress">
								<div class="sln-ppc-progress-label">
									<span><?php echo esc_html( $p_label ); ?></span>
									<span><?php echo esc_html( $p_value ); ?></span>
								</div>
								<div class="sln-ppc-progress-track">
									<span
										class="sln-ppc-progress-fill"
										data-sln-ppc-progress
										data-w="<?php echo esc_attr( (string) $p_width ); ?>"
										style="--w:<?php echo esc_attr( (string) $p_width ); ?>%; width:<?php echo esc_attr( (string) $p_width ); ?>%;"
									></span>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $case['quote'] ) ) : ?>
							<blockquote><?php echo esc_html( $case['quote'] ); ?></blockquote>
						<?php endif; ?>
						<?php if ( ! empty( $case['attribution'] ) ) : ?>
							<div class="sln-ppc-case-by"><?php echo esc_html( $case['attribution'] ); ?></div>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $section['disclaimer'] ) ) : ?>
			<div class="sln-ppc-disclaimer"><?php echo esc_html( $section['disclaimer'] ); ?></div>
		<?php endif; ?>
	</div>
</section>
