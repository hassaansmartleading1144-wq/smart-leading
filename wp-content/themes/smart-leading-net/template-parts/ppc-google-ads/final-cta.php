<?php
/**
 * PPC & Google Ads page — final CTA.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_helpers.php';

$section = sln_get_ppc_final_cta();

if ( ! sln_ppc_row_is_active( $section ) ) {
	return;
}
?>

<section id="contact" class="sln-ppc-section sln-ppc-section--dark sln-ppc-final" aria-labelledby="sln-ppc-final-heading">
	<span class="sln-ppc-final-glow sln-ppc-final-glow--azure" aria-hidden="true"></span>
	<span class="sln-ppc-final-glow sln-ppc-final-glow--orange" aria-hidden="true"></span>

	<div class="sls-container">
		<div class="sln-ppc-reveal">
			<?php if ( ! empty( $section['small_heading'] ) ) : ?>
				<span class="sln-ppc-kicker"><?php echo esc_html( $section['small_heading'] ); ?></span>
			<?php endif; ?>

			<h2 id="sln-ppc-final-heading" class="sln-ppc-title">
				<?php echo esc_html( $section['main_heading'] ?? '' ); ?>
				<?php if ( ! empty( $section['highlighted_text'] ) ) : ?>
					<span class="sln-ppc-highlight--azure"><?php echo esc_html( $section['highlighted_text'] ); ?></span>
				<?php endif; ?>
			</h2>

			<?php if ( sln_ppc_plain_text( $section['description'] ?? '' ) ) : ?>
				<div class="sln-ppc-dek"><?php echo sln_ppc_format_content( $section['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>

			<div class="sln-ppc-cta-row">
				<?php
				sln_ppc_part_render_button( $section['primary_button_text'] ?? '', $section['primary_button_url'] ?? '#contact' );
				sln_ppc_part_render_button( $section['secondary_button_text'] ?? '', $section['secondary_button_url'] ?? '#process', 'sln-ppc-btn--line', false );
				?>
			</div>

			<?php if ( ! empty( $section['website_text'] ) ) : ?>
				<div class="sln-ppc-final-web">
					<?php echo esc_html( $section['website_label'] ?? '' ); ?>
					<a href="<?php echo esc_url( $section['website_url'] ?? '#' ); ?>"><b><?php echo esc_html( $section['website_text'] ); ?></b></a>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $section['bottom_note'] ) ) : ?>
				<div class="sln-ppc-final-note"><?php echo esc_html( $section['bottom_note'] ); ?></div>
			<?php endif; ?>
		</div>
	</div>
</section>
