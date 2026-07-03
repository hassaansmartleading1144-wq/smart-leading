<?php
/**
 * Digital Marketing page — pricing plans.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$plans = sln_get_dm_page_pricing_plans();
?>

<section class="dm-page__section" aria-labelledby="dm-pricing-heading">
	<div class="dm-page__wrap">
		<p class="dm-page__eyebrow dm-page__reveal"><?php esc_html_e( 'Investment', 'smart-leading-net' ); ?></p>
		<h2 id="dm-pricing-heading" class="dm-page__section-title dm-page__reveal">
			<?php
			echo wp_kses(
				__( 'Simple, <span class="dm-page__hl">Transparent Pricing.</span>', 'smart-leading-net' ),
				array( 'span' => array( 'class' => true ) )
			);
			?>
		</h2>
		<p class="dm-page__lead dm-page__reveal">
			<?php esc_html_e( 'No hidden fees. No long contracts. Just results you can measure.', 'smart-leading-net' ); ?>
		</p>
		<div class="dm-page__plans">
			<?php foreach ( $plans as $plan ) : ?>
				<article class="dm-page__plan<?php echo ! empty( $plan['popular'] ) ? ' dm-page__plan--popular' : ''; ?> dm-page__reveal">
					<?php if ( ! empty( $plan['popular_label'] ) ) : ?>
						<span class="dm-page__plan-popular"><?php echo esc_html( $plan['popular_label'] ); ?></span>
					<?php endif; ?>
					<div class="dm-page__plan-tier"><?php echo esc_html( $plan['tier'] ); ?></div>
					<div class="dm-page__plan-tagline"><?php echo esc_html( $plan['tagline'] ); ?></div>
					<div class="dm-page__plan-price">
						<?php echo esc_html( $plan['price'] ); ?>
						<small><?php echo esc_html( $plan['price_note'] ); ?></small>
					</div>
					<ul class="dm-page__plan-features">
						<?php foreach ( $plan['features'] as $feature ) : ?>
							<li><?php echo esc_html( $feature ); ?></li>
						<?php endforeach; ?>
					</ul>
					<a
						class="dm-page__pill<?php echo 'ghost' === $plan['cta_variant'] ? ' dm-page__pill--ghost' : ''; ?>"
						href="<?php echo esc_url( $plan['cta_url'] ); ?>"
					>
						<?php esc_html_e( 'Get Started', 'smart-leading-net' ); ?>
						<span class="dm-page__pill-arrow" aria-hidden="true">→</span>
					</a>
				</article>
			<?php endforeach; ?>
		</div>
		<p class="dm-page__pricenote dm-page__reveal">
			<?php esc_html_e( 'All plans include onboarding, a strategy session and a dedicated account manager. Custom plans available on request.', 'smart-leading-net' ); ?>
		</p>
	</div>
</section>
