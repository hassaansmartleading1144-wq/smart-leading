<?php
/**
 * PPC & Google Ads page — reality section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_helpers.php';

$section    = sln_get_ppc_reality_section();
$budget     = sln_get_ppc_reality_budget();
$challenges = sln_get_ppc_reality_challenges();

if ( ! sln_ppc_row_is_active( $section ) ) {
	return;
}

$waste_percent   = max( 0, min( 100, (int) ( $budget['waste_percent'] ?? 65 ) ) );
$working_percent = max( 0, min( 100, (int) ( $budget['working_percent'] ?? ( 100 - $waste_percent ) ) ) );
?>

<section id="reality" class="sln-ppc-section sln-ppc-grid-bg" aria-labelledby="sln-ppc-reality-heading">
	<div class="sls-container">
		<?php sln_ppc_part_render_heading( $section, 'sln-ppc-reality-heading', false, 'azure' ); ?>

		<?php if ( sln_ppc_row_is_active( $budget ) ) : ?>
			<div class="sln-ppc-leakpanel sln-ppc-reveal">
				<div class="sln-ppc-leak-top">
					<div>
						<?php if ( ! empty( $budget['lead_text'] ) ) : ?>
							<div class="sln-ppc-leak-lead"><?php echo esc_html( $budget['lead_text'] ); ?></div>
						<?php endif; ?>
						<div class="sln-ppc-leak-big">
							<span class="sln-ppc-leak-danger"><?php echo esc_html( $waste_percent ); ?>%</span>
							<?php echo esc_html( trim( str_replace( $waste_percent . '%', '', (string) ( $budget['waste_big_text'] ?? __( 'wasted', 'smart-leading-net' ) ) ) ) ); ?>
						</div>
					</div>
					<?php if ( ! empty( $budget['flip_text'] ) || ! empty( $budget['flip_highlight'] ) ) : ?>
						<div class="sln-ppc-leak-flip">
							<?php echo esc_html( $budget['flip_text'] ?? '' ); ?>
							<?php if ( ! empty( $budget['flip_highlight'] ) ) : ?>
								<b><?php echo esc_html( $budget['flip_highlight'] ); ?></b>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>

				<div class="sln-ppc-leakbar">
					<span
						class="sln-ppc-leak-seg sln-ppc-leak-seg--waste"
						data-sln-ppc-leak
						data-w="<?php echo esc_attr( (string) $waste_percent ); ?>"
						style="--w:<?php echo esc_attr( (string) $waste_percent ); ?>%; width:<?php echo esc_attr( (string) $waste_percent ); ?>%;"
					></span>
					<span
						class="sln-ppc-leak-seg sln-ppc-leak-seg--work"
						data-sln-ppc-leak
						data-w="<?php echo esc_attr( (string) $working_percent ); ?>"
						style="--w:<?php echo esc_attr( (string) $working_percent ); ?>%; width:<?php echo esc_attr( (string) $working_percent ); ?>%;"
					></span>
				</div>

				<div class="sln-ppc-leaklabels">
					<?php if ( ! empty( $budget['wasted_label'] ) ) : ?>
						<span class="sln-ppc-leaklabel--waste"><?php echo esc_html( $budget['wasted_label'] ); ?></span>
					<?php endif; ?>
					<?php if ( ! empty( $budget['working_label'] ) ) : ?>
						<span class="sln-ppc-leaklabel--work"><?php echo esc_html( $budget['working_label'] ); ?></span>
					<?php endif; ?>
				</div>

				<?php if ( ! empty( $budget['caption'] ) ) : ?>
					<div class="sln-ppc-leakcap"><?php echo esc_html( $budget['caption'] ); ?></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $challenges ) ) : ?>
			<div class="sln-ppc-reality-grid">
				<?php foreach ( $challenges as $challenge ) : ?>
					<div class="sln-ppc-reality-card sln-ppc-reveal">
						<div class="sln-ppc-reality-icon"><?php echo esc_html( $challenge['icon_text'] ?? '!' ); ?></div>
						<?php if ( ! empty( $challenge['title'] ) ) : ?>
							<h3><?php echo esc_html( $challenge['title'] ); ?></h3>
						<?php endif; ?>
						<?php if ( ! empty( $challenge['description'] ) ) : ?>
							<p><?php echo esc_html( $challenge['description'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $challenge['impact'] ) ) : ?>
							<div class="sln-ppc-reality-impact"><?php echo esc_html( $challenge['impact'] ); ?></div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( sln_ppc_plain_text( $section['bottom_note'] ?? '' ) ) : ?>
			<div class="sln-ppc-reality-note sln-ppc-reveal"><?php echo sln_ppc_format_content( $section['bottom_note'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<?php endif; ?>
	</div>
</section>
