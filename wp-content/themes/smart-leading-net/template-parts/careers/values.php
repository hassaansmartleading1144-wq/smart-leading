<?php
/**
 * Careers — values.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$values = sln_get_careers_values();
?>

<section class="careers-page__section careers-page__values" id="careers-values" aria-labelledby="careers-values-heading">
	<div class="sls-container">
		<header class="careers-page__section-head careers-page__section-head--center careers-page__reveal">
			<p class="careers-page__eyebrow"><?php esc_html_e( 'Our Values', 'smart-leading-net' ); ?></p>
			<h2 id="careers-values-heading" class="careers-page__section-title">
				<?php esc_html_e( 'Principles That Guide How We Work', 'smart-leading-net' ); ?>
			</h2>
			<p class="careers-page__section-desc">
				<?php esc_html_e( 'These are not posters on a wall. They show up in how we hire, how we collaborate, and how we serve clients.', 'smart-leading-net' ); ?>
			</p>
		</header>

		<div class="careers-page__values-grid">
			<?php foreach ( $values as $value ) : ?>
				<article class="careers-page__value-card careers-page__reveal">
					<div class="careers-page__icon careers-page__icon--soft" aria-hidden="true">
						<?php echo sln_careers_page_icon( $value['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<h3><?php echo esc_html( $value['title'] ); ?></h3>
					<p><?php echo esc_html( $value['text'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
