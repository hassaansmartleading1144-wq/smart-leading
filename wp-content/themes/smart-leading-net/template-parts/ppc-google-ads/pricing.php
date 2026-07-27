<?php
/**
 * PPC & Google Ads page — pricing section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_helpers.php';

$section = sln_get_ppc_pricing_section();
$plans   = sln_get_ppc_pricing_plans();

if ( ! sln_ppc_row_is_active( $section ) ) {
	return;
}
?>

<section id="pricing" class="sln-ppc-section sln-ppc-grid-bg" aria-labelledby="sln-ppc-pricing-heading">
	<div class="sls-container">
		<?php sln_ppc_part_render_heading( $section, 'sln-ppc-pricing-heading', true, 'azure' ); ?>

		<?php if ( ! empty( $plans ) ) : ?>
			<div class="sln-ppc-plans">
				<?php foreach ( $plans as $plan ) : ?>
					<?php
					$is_popular   = ! empty( $plan['is_popular'] );
					$plan_classes = $is_popular ? 'sln-ppc-plan sln-ppc-plan--popular sln-ppc-reveal' : 'sln-ppc-plan sln-ppc-reveal';
					$button_class = 'primary' === ( $plan['button_style'] ?? '' ) ? 'sln-ppc-btn--orange' : 'sln-ppc-btn--line sln-ppc-btn--dark';
					?>
					<article class="<?php echo esc_attr( $plan_classes ); ?>">
						<div class="sln-ppc-plan-top">
							<?php if ( ! empty( $plan['name'] ) ) : ?>
								<span class="sln-ppc-plan-tier"><?php echo esc_html( $plan['name'] ); ?></span>
							<?php endif; ?>
							<?php if ( $is_popular && ! empty( $plan['popular_badge'] ) ) : ?>
								<span class="sln-ppc-popular-badge"><?php echo esc_html( $plan['popular_badge'] ); ?></span>
							<?php endif; ?>
						</div>

						<?php if ( ! empty( $plan['tagline'] ) ) : ?>
							<div class="sln-ppc-plan-tagline"><?php echo esc_html( $plan['tagline'] ); ?></div>
						<?php endif; ?>
						<?php if ( ! empty( $plan['price'] ) ) : ?>
							<div class="sln-ppc-plan-price">
								<?php echo esc_html( $plan['price'] ); ?>
								<?php if ( ! empty( $plan['price_suffix'] ) ) : ?>
									<small><?php echo esc_html( $plan['price_suffix'] ); ?></small>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $plan['spend'] ) ) : ?>
							<div class="sln-ppc-plan-spend"><?php echo esc_html( $plan['spend'] ); ?></div>
						<?php endif; ?>

						<?php if ( ! empty( $plan['features'] ) && is_array( $plan['features'] ) ) : ?>
							<ul class="sln-ppc-plan-features">
								<?php foreach ( $plan['features'] as $feature ) : ?>
									<?php
									if ( ! is_array( $feature ) || ! sln_ppc_row_is_active( $feature ) || empty( $feature['text'] ) ) {
										continue;
									}

									$feature_class = ! empty( $feature['highlight'] ) ? ' class="sln-ppc-feature-highlight"' : '';
									?>
									<li<?php echo $feature_class; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $feature['text'] ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>

						<?php sln_ppc_part_render_button( $plan['button_text'] ?? '', $plan['button_url'] ?? '#contact', $button_class ); ?>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( sln_ppc_plain_text( $section['bottom_note'] ?? '' ) ) : ?>
			<div class="sln-ppc-plan-note sln-ppc-reveal"><?php echo sln_ppc_format_content( $section['bottom_note'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<?php endif; ?>
	</div>
</section>
