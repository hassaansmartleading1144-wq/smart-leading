<?php
/**
 * Careers — why join us.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cards = sln_get_careers_why_join();
?>

<section class="careers-page__section careers-page__why-join" id="careers-why" aria-labelledby="careers-why-heading">
	<div class="sls-container">
		<header class="careers-page__section-head careers-page__section-head--center careers-page__reveal">
			<p class="careers-page__eyebrow"><?php esc_html_e( 'Why Join Us', 'smart-leading-net' ); ?></p>
			<h2 id="careers-why-heading" class="careers-page__section-title">
				<?php esc_html_e( 'A Place Built For Ambitious Marketers & Builders', 'smart-leading-net' ); ?>
			</h2>
			<p class="careers-page__section-desc">
				<?php esc_html_e( 'We combine agency energy with product-quality standards — so you grow faster, ship better work, and stay proud of what you put into the world.', 'smart-leading-net' ); ?>
			</p>
		</header>

		<div class="careers-page__why-grid">
			<?php foreach ( $cards as $card ) : ?>
				<article class="careers-page__why-card careers-page__reveal">
					<div class="careers-page__icon" aria-hidden="true">
						<?php echo sln_careers_page_icon( $card['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<h3 class="careers-page__why-title"><?php echo esc_html( $card['title'] ); ?></h3>
					<p class="careers-page__why-text"><?php echo esc_html( $card['text'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
