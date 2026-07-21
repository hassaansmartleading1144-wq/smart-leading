<?php
/**
 * Digital Marketing page — final CTA.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cta = sln_get_dm_final_cta();

if ( ! sln_dm_row_is_active( $cta ) ) {
	return;
}

$benefits = is_array( $cta['benefits'] ?? null ) ? $cta['benefits'] : array();
?>

<section class="sln-dm-final sln-dm-section sln-dm-section--dark" id="dm-contact" aria-labelledby="sln-dm-final-heading">
	<span class="sln-dm-final__blob sln-dm-final__blob--orange" aria-hidden="true"></span>

	<div class="sls-container sln-dm-wrap">
		<div class="sln-dm-rule sln-dm-animate" aria-hidden="true"></div>

		<?php if ( ! empty( $cta['small_heading'] ) ) : ?>
			<p class="sln-dm-eyebrow sln-dm-animate"><?php echo esc_html( $cta['small_heading'] ); ?></p>
		<?php endif; ?>

		<h2 id="sln-dm-final-heading" class="sln-dm-title sln-dm-title--light sln-dm-animate">
			<?php
			echo esc_html( $cta['main_heading'] ?? '' );
			if ( ! empty( $cta['highlighted_text'] ) ) {
				echo ' <span class="sln-dm-hl">' . esc_html( $cta['highlighted_text'] ) . '</span>';
			}
			?>
		</h2>

		<?php if ( sln_dm_plain_text( $cta['description'] ?? '' ) ) : ?>
			<div class="sln-dm-lead sln-dm-lead--light sln-dm-animate"><?php echo sln_dm_format_content( $cta['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<?php endif; ?>

		<?php if ( ! empty( $benefits ) ) : ?>
			<ul class="sln-dm-final__checks">
				<?php foreach ( $benefits as $benefit ) : ?>
					<li class="sln-dm-final__check sln-dm-animate">
						<span class="sln-dm-final__check-tick" aria-hidden="true">✓</span>
						<?php echo esc_html( $benefit['text'] ?? '' ); ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( ! empty( $cta['button_text'] ) ) : ?>
			<div class="sln-dm-final__cta sln-dm-animate">
				<a class="sln-dm-pill" href="<?php echo esc_url( $cta['button_url'] ?? '#dm-contact' ); ?>">
					<span><?php echo esc_html( $cta['button_text'] ); ?></span>
					<span class="sln-dm-pill__arr" aria-hidden="true">→</span>
				</a>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $cta['website_text'] ) ) : ?>
			<?php if ( ! empty( $cta['website_url'] ) ) : ?>
				<a class="sln-dm-final__web sln-dm-animate" href="<?php echo esc_url( $cta['website_url'] ); ?>" target="_blank" rel="noopener noreferrer">
					<span class="sln-dm-final__web-icon" aria-hidden="true">⊕</span>
					<?php echo esc_html( $cta['website_text'] ); ?>
				</a>
			<?php else : ?>
				<p class="sln-dm-final__web sln-dm-animate">
					<span class="sln-dm-final__web-icon" aria-hidden="true">⊕</span>
					<?php echo esc_html( $cta['website_text'] ); ?>
				</p>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( sln_dm_plain_text( $cta['bottom_note'] ?? '' ) ) : ?>
			<p class="sln-dm-final__note sln-dm-animate"><?php echo esc_html( sln_dm_plain_text( $cta['bottom_note'] ) ); ?></p>
		<?php endif; ?>
	</div>
</section>
