<?php
/**
 * AEO page — why it matters.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = sln_aeo_get_why_section();
$cards   = sln_aeo_get_why_cards();
?>

<section class="sln-aeo-section" aria-labelledby="sln-aeo-why-heading">
	<div class="sls-container">
		<header class="sln-aeo-head sln-aeo-reveal">
			<p class="sln-aeo-eyebrow"><?php echo esc_html( $section['eyebrow'] ); ?></p>
			<h2 id="sln-aeo-why-heading" class="sln-aeo-title"><?php echo esc_html( $section['heading'] ); ?></h2>
			<p class="sln-aeo-lead"><?php echo esc_html( sln_aeo_plain_text( $section['description'] ) ); ?></p>
		</header>

		<div class="sln-aeo-bento sln-aeo-reveal">
			<?php foreach ( $cards as $card ) : ?>
				<article class="sln-aeo-bento__card<?php echo ! empty( $card['wide'] ) ? ' sln-aeo-bento__card--wide' : ''; ?>">
					<div class="sln-aeo-bento__icon" aria-hidden="true">
						<?php echo sln_aeo_icon( $card['icon'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<div>
						<h3><?php echo esc_html( $card['title'] ?? '' ); ?></h3>
						<p><?php echo esc_html( sln_aeo_plain_text( $card['description'] ?? '' ) ); ?></p>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
