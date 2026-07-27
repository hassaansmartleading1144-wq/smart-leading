<?php
/**
 * Careers — benefits & perks.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$benefits = sln_get_careers_benefits();
?>

<section class="careers-page__section careers-page__benefits" id="careers-benefits" aria-labelledby="careers-benefits-heading">
	<div class="sls-container">
		<header class="careers-page__section-head careers-page__section-head--center careers-page__reveal">
			<p class="careers-page__eyebrow"><?php esc_html_e( 'Benefits & Perks', 'smart-leading-net' ); ?></p>
			<h2 id="careers-benefits-heading" class="careers-page__section-title">
				<?php esc_html_e( 'Support That Helps You Do Your Best Work', 'smart-leading-net' ); ?>
			</h2>
			<p class="careers-page__section-desc">
				<?php esc_html_e( 'We invest in tools, learning, wellbeing, and recognition — because great people deserve a workplace that backs them.', 'smart-leading-net' ); ?>
			</p>
		</header>

		<div class="careers-page__benefits-grid">
			<?php foreach ( $benefits as $benefit ) : ?>
				<article class="careers-page__benefit-card careers-page__reveal">
					<div class="careers-page__icon" aria-hidden="true">
						<?php echo sln_careers_page_icon( $benefit['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<h3><?php echo esc_html( $benefit['title'] ); ?></h3>
					<p><?php echo esc_html( $benefit['text'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
