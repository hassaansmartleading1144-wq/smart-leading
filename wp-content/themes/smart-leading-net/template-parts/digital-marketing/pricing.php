<?php
/**
 * Digital Marketing page — pricing plans.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = sln_get_dm_pricing_section();
$plans   = sln_get_dm_pricing_plans();

if ( empty( $plans ) ) {
	return;
}
?>

<section class="sln-dm-section" id="dm-pricing" aria-labelledby="sln-dm-pricing-heading">
	<div class="sls-container sln-dm-wrap">
		<header class="sln-dm-section__head sln-dm-animate">
			<div class="sln-dm-rule" aria-hidden="true"></div>
			<?php if ( ! empty( $section['small_heading'] ) ) : ?>
				<p class="sln-dm-eyebrow"><?php echo esc_html( $section['small_heading'] ); ?></p>
			<?php endif; ?>
			<h2 id="sln-dm-pricing-heading" class="sln-dm-title">
				<?php
				echo esc_html( $section['main_heading'] ?? '' );
				if ( ! empty( $section['highlighted_text'] ) ) {
					echo ' <span class="sln-dm-hl">' . esc_html( $section['highlighted_text'] ) . '</span>';
				}
				?>
			</h2>
			<?php if ( sln_dm_plain_text( $section['description'] ?? '' ) ) : ?>
				<div class="sln-dm-lead"><?php echo sln_dm_format_content( $section['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
		</header>

		<div class="sln-dm-plan-grid">
			<?php foreach ( $plans as $plan ) : ?>
				<?php
				$plan_class = 'sln-dm-plan sln-dm-animate';
				if ( ! empty( $plan['is_popular'] ) ) {
					$plan_class .= ' sln-dm-plan--popular';
				}
				$features = is_array( $plan['features'] ?? null ) ? $plan['features'] : array();
				$btn_class  = 'sln-dm-pill';
				if ( 'ghost' === ( $plan['button_style'] ?? '' ) ) {
					$btn_class .= ' sln-dm-pill--ghost';
				}
				?>
				<article class="<?php echo esc_attr( $plan_class ); ?>">
					<?php if ( ! empty( $plan['is_popular'] ) && ! empty( $plan['popular_badge'] ) ) : ?>
						<span class="sln-dm-plan__badge"><?php echo esc_html( $plan['popular_badge'] ); ?></span>
					<?php endif; ?>

					<div class="sln-dm-plan__name"><?php echo esc_html( $plan['name'] ?? '' ); ?></div>
					<?php if ( ! empty( $plan['tagline'] ) ) : ?>
						<div class="sln-dm-plan__tagline"><?php echo esc_html( $plan['tagline'] ); ?></div>
					<?php endif; ?>

					<div class="sln-dm-plan__price">
						<?php echo esc_html( ( $plan['price_prefix'] ?? '' ) . ( $plan['price'] ?? '' ) ); ?>
						<?php if ( ! empty( $plan['price_suffix'] ) ) : ?>
							<small><?php echo esc_html( $plan['price_suffix'] ); ?></small>
						<?php endif; ?>
					</div>

					<?php if ( ! empty( $features ) ) : ?>
						<ul class="sln-dm-plan__features">
							<?php foreach ( $features as $feature ) : ?>
								<li><?php echo esc_html( $feature ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php if ( ! empty( $plan['button_text'] ) ) : ?>
						<a class="<?php echo esc_attr( $btn_class ); ?>" href="<?php echo esc_url( $plan['button_url'] ?? '#dm-contact' ); ?>">
							<span><?php echo esc_html( $plan['button_text'] ); ?></span>
							<span class="sln-dm-pill__arr" aria-hidden="true">→</span>
						</a>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>

		<?php if ( sln_dm_plain_text( $section['bottom_note'] ?? '' ) ) : ?>
			<p class="sln-dm-plan__note sln-dm-animate"><?php echo esc_html( sln_dm_plain_text( $section['bottom_note'] ) ); ?></p>
		<?php endif; ?>
	</div>
</section>
