<?php
/**
 * AEO page — why it matters.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cards = sln_aeo_get_why_cards();
?>

<section class="sln-aeo-section" aria-labelledby="sln-aeo-why-heading">
	<div class="sls-container">
		<header class="sln-aeo-head sln-aeo-reveal">
			<p class="sln-aeo-eyebrow"><?php esc_html_e( 'Why it matters', 'smart-leading-net' ); ?></p>
			<h2 id="sln-aeo-why-heading" class="sln-aeo-title"><?php esc_html_e( 'Your customers are already asking AI', 'smart-leading-net' ); ?></h2>
			<p class="sln-aeo-lead"><?php esc_html_e( 'The channels below aren\'t emerging anymore — they\'re where a growing share of research and buying decisions already happen.', 'smart-leading-net' ); ?></p>
		</header>

		<div class="sln-aeo-bento sln-aeo-reveal">
			<?php foreach ( $cards as $card ) : ?>
				<article class="sln-aeo-bento__card<?php echo ! empty( $card['wide'] ) ? ' sln-aeo-bento__card--wide' : ''; ?>">
					<div class="sln-aeo-bento__icon" aria-hidden="true">
						<?php echo sln_aeo_icon( $card['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<div>
						<h3><?php echo esc_html( $card['title'] ); ?></h3>
						<p><?php echo esc_html( $card['description'] ); ?></p>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
