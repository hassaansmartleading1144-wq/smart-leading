<?php
/**
 * Careers — life at Smart Leading gallery.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$items = sln_get_careers_life_gallery();
?>

<section class="careers-page__section careers-page__life" id="careers-life" aria-labelledby="careers-life-heading">
	<div class="sls-container">
		<header class="careers-page__section-head careers-page__section-head--center careers-page__reveal">
			<p class="careers-page__eyebrow"><?php esc_html_e( 'Life At Smart Leading', 'smart-leading-net' ); ?></p>
			<h2 id="careers-life-heading" class="careers-page__section-title">
				<?php esc_html_e( 'Moments That Make The Work Meaningful', 'smart-leading-net' ); ?>
			</h2>
			<p class="careers-page__section-desc">
				<?php esc_html_e( 'From focused collaboration to celebrations and learning sessions — this is what day-to-day looks like on our teams.', 'smart-leading-net' ); ?>
			</p>
		</header>

		<div class="careers-page__life-grid">
			<?php foreach ( $items as $item ) : ?>
				<article class="careers-page__life-card careers-page__life-card--<?php echo esc_attr( $item['size'] ); ?> careers-page__reveal">
					<div class="careers-page__life-media">
						<?php if ( ! empty( $item['image'] ) ) : ?>
							<img
								src="<?php echo esc_url( $item['image'] ); ?>"
								alt="<?php echo esc_attr( $item['title'] ); ?>"
								width="640"
								height="420"
								loading="lazy"
								decoding="async"
							/>
						<?php else : ?>
							<span class="careers-page__life-placeholder" aria-hidden="true"><?php echo esc_html( $item['title'] ); ?></span>
						<?php endif; ?>
					</div>
					<div class="careers-page__life-body">
						<h3><?php echo esc_html( $item['title'] ); ?></h3>
						<p><?php echo esc_html( $item['text'] ); ?></p>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
