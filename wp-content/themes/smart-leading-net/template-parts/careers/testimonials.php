<?php
/**
 * Careers — employee testimonials.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$testimonials = sln_get_careers_testimonials();
?>

<section class="careers-page__section careers-page__testimonials" id="careers-testimonials" aria-labelledby="careers-testimonials-heading">
	<div class="sls-container">
		<header class="careers-page__section-head careers-page__section-head--center careers-page__reveal">
			<p class="careers-page__eyebrow"><?php esc_html_e( 'Testimonials', 'smart-leading-net' ); ?></p>
			<h2 id="careers-testimonials-heading" class="careers-page__section-title">
				<?php esc_html_e( 'Hear From People Who Build Here', 'smart-leading-net' ); ?>
			</h2>
			<p class="careers-page__section-desc">
				<?php esc_html_e( 'Honest perspectives from teammates across strategy, paid media, engineering, and design.', 'smart-leading-net' ); ?>
			</p>
		</header>

		<div class="careers-page__testi-slider" data-careers-testi-slider>
			<div class="careers-page__testi-track">
				<?php foreach ( $testimonials as $index => $item ) : ?>
					<article class="careers-page__testi-card<?php echo 0 === $index ? ' is-active' : ''; ?>" data-careers-testi-slide>
						<div class="careers-page__stars" aria-label="<?php echo esc_attr( sprintf( /* translators: %d: star rating */ __( '%d out of 5 stars', 'smart-leading-net' ), (int) $item['rating'] ) ); ?>">
							<?php echo esc_html( str_repeat( '★', (int) $item['rating'] ) ); ?>
						</div>
						<blockquote class="careers-page__testi-quote">
							<p><?php echo esc_html( $item['quote'] ); ?></p>
						</blockquote>
						<div class="careers-page__testi-author">
							<div class="careers-page__testi-avatar" aria-hidden="true">
								<span><?php echo esc_html( $item['initials'] ); ?></span>
							</div>
							<div>
								<strong><?php echo esc_html( $item['name'] ); ?></strong>
								<small><?php echo esc_html( $item['role'] ); ?></small>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>

			<?php if ( count( $testimonials ) > 1 ) : ?>
				<div class="careers-page__testi-controls">
					<button type="button" class="careers-page__testi-prev" aria-label="<?php esc_attr_e( 'Previous testimonial', 'smart-leading-net' ); ?>">
						<span aria-hidden="true">&lsaquo;</span>
					</button>
					<div class="careers-page__testi-dots" role="tablist" aria-label="<?php esc_attr_e( 'Testimonials', 'smart-leading-net' ); ?>">
						<?php foreach ( $testimonials as $index => $item ) : ?>
							<button
								type="button"
								class="careers-page__testi-dot<?php echo 0 === $index ? ' is-active' : ''; ?>"
								role="tab"
								aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>"
								aria-label="<?php echo esc_attr( sprintf( /* translators: %d: slide number */ __( 'Go to testimonial %d', 'smart-leading-net' ), $index + 1 ) ); ?>"
								data-slide="<?php echo esc_attr( (string) $index ); ?>"
							></button>
						<?php endforeach; ?>
					</div>
					<button type="button" class="careers-page__testi-next" aria-label="<?php esc_attr_e( 'Next testimonial', 'smart-leading-net' ); ?>">
						<span aria-hidden="true">&rsaquo;</span>
					</button>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
