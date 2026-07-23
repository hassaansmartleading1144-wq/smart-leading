<?php
/**
 * PPC & Google Ads page — process section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_helpers.php';

$section = sln_get_ppc_process_section();
$steps   = sln_get_ppc_process_steps();

if ( ! sln_ppc_row_is_active( $section ) ) {
	return;
}
?>

<section id="process" class="sln-ppc-section sln-ppc-section--dark sln-ppc-grid-bg" aria-labelledby="sln-ppc-process-heading">
	<div class="sls-container">
		<?php sln_ppc_part_render_heading( $section, 'sln-ppc-process-heading', false, 'azure' ); ?>

		<?php if ( ! empty( $steps ) ) : ?>
			<div class="sln-ppc-process-rail sln-ppc-reveal" data-sln-ppc-process>
				<div class="sln-ppc-process-line" aria-hidden="true"><span class="sln-ppc-process-fill"></span></div>
				<?php foreach ( $steps as $step ) : ?>
					<div class="sln-ppc-process-phase">
						<div class="sln-ppc-process-number"><?php echo esc_html( $step['number'] ?? '' ); ?></div>
						<?php if ( ! empty( $step['title'] ) ) : ?>
							<h3><?php echo esc_html( $step['title'] ); ?></h3>
						<?php endif; ?>
						<?php if ( ! empty( $step['bullets'] ) && is_array( $step['bullets'] ) ) : ?>
							<ul>
								<?php foreach ( $step['bullets'] as $bullet ) : ?>
									<?php if ( '' === (string) $bullet ) : ?>
										<?php continue; ?>
									<?php endif; ?>
									<li><?php echo esc_html( $bullet ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $section['bottom_note'] ) ) : ?>
			<div class="sln-ppc-process-note"><?php echo esc_html( $section['bottom_note'] ); ?></div>
		<?php endif; ?>
	</div>
</section>
