<?php
/**
 * Careers — company culture split.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$culture      = sln_get_careers_culture();
$culture_image = function_exists( 'sln_get_careers_culture_image' ) ? sln_get_careers_culture_image() : '';
?>

<section class="careers-page__section careers-page__culture" id="careers-culture" aria-labelledby="careers-culture-heading">
	<div class="sls-container careers-page__culture-inner">
		<div class="careers-page__culture-media careers-page__reveal">
			<div class="careers-page__culture-frame">
				<?php if ( $culture_image ) : ?>
					<img
						class="careers-page__culture-image"
						src="<?php echo esc_url( $culture_image ); ?>"
						alt="<?php esc_attr_e( 'Smart Leading team collaborating in the office', 'smart-leading-net' ); ?>"
						width="720"
						height="480"
						loading="lazy"
						decoding="async"
					/>
				<?php else : ?>
					<div class="careers-page__culture-placeholder" aria-hidden="true">
						<span><?php esc_html_e( 'Team photo', 'smart-leading-net' ); ?></span>
					</div>
				<?php endif; ?>
				
			</div>
		</div>

		<div class="careers-page__culture-copy careers-page__reveal">
			<p class="careers-page__eyebrow"><?php esc_html_e( 'Company Culture', 'smart-leading-net' ); ?></p>
			<h2 id="careers-culture-heading" class="careers-page__section-title">
				<?php esc_html_e( 'Where Performance Meets Belonging', 'smart-leading-net' ); ?>
			</h2>
			<p class="careers-page__section-desc">
				<?php esc_html_e( 'Smart Leading is built for people who care about craft and clients equally. We move fast, communicate clearly, and celebrate the wins that matter.', 'smart-leading-net' ); ?>
			</p>

			<div class="careers-page__culture-blocks">
				<?php foreach ( $culture as $block ) : ?>
					<div class="careers-page__culture-block">
						<h3><?php echo esc_html( $block['title'] ); ?></h3>
						<p><?php echo esc_html( $block['text'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
