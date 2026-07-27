<?php
/**
 * PPC & Google Ads page — quick truth section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_helpers.php';

$section = sln_get_ppc_truth_section();

if ( ! sln_ppc_row_is_active( $section ) ) {
	return;
}
?>

<section class="sln-ppc-section sln-ppc-section--dark sln-ppc-truth" aria-label="<?php echo esc_attr( $section['statement'] ?? __( 'PPC truth', 'smart-leading-net' ) ); ?>">
	<span class="sln-ppc-truth-glow" aria-hidden="true"></span>

	<div class="sls-container">
		<div class="sln-ppc-reveal">
			<div class="sln-ppc-truth-rule" aria-hidden="true"></div>

			<div class="sln-ppc-truth-statement">
				<?php echo esc_html( $section['statement'] ?? '' ); ?>
				<?php if ( ! empty( $section['highlighted_text'] ) ) : ?>
					<span class="sln-ppc-highlight--azure"><?php echo esc_html( $section['highlighted_text'] ); ?></span>
				<?php endif; ?>
			</div>

			<?php if ( sln_ppc_plain_text( $section['body'] ?? '' ) ) : ?>
				<div class="sln-ppc-truth-body"><?php echo sln_ppc_format_content( $section['body'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>

			<?php if ( ! empty( $section['quote'] ) || ! empty( $section['quote_highlight'] ) ) : ?>
				<div class="sln-ppc-truth-pull">
					<q>
						<?php echo esc_html( $section['quote'] ?? '' ); ?>
						<?php if ( ! empty( $section['quote_highlight'] ) ) : ?>
							<span class="sln-ppc-highlight--orange"><?php echo esc_html( $section['quote_highlight'] ); ?></span>
						<?php endif; ?>
					</q>
					<?php if ( ! empty( $section['attribution'] ) ) : ?>
						<div class="sln-ppc-truth-by"><?php echo esc_html( $section['attribution'] ); ?></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $section['button_text'] ) ) : ?>
				<div class="sln-ppc-truth-cta">
					<?php sln_ppc_part_render_button( $section['button_text'], $section['button_url'] ?? '#contact' ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
