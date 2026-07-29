<?php
/**
 * Web Development — Figma care plans.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = sln_get_wd_care_section();
$plans   = sln_get_wd_care_plans();

if ( ! sln_wd_row_is_active( $section ) || empty( $plans ) ) {
	return;
}
?>

<section class="wd-care" id="wd-care" aria-labelledby="wd-care-heading">
	<div class="wd-wrap">
		<header class="wd-head wd-animate">
			<?php if ( ! empty( $section['small_heading'] ) ) : ?>
				<p class="wd-eyebrow"><?php echo esc_html( $section['small_heading'] ); ?></p>
			<?php endif; ?>
			<h2 id="wd-care-heading" class="wd-title"><?php echo esc_html( $section['main_heading'] ?? '' ); ?></h2>
			<?php if ( sln_wd_plain_text( $section['description'] ?? '' ) ) : ?>
				<div class="wd-lead"><?php echo sln_wd_format_content( $section['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
		</header>

		<div class="wd-care__grid">
			<?php foreach ( $plans as $plan ) : ?>
				<?php
				$plan_class = 'wd-care__card wd-animate';
				if ( ! empty( $plan['is_popular'] ) ) {
					$plan_class .= ' wd-care__card--popular';
				}
				$features = is_array( $plan['features'] ?? null ) ? $plan['features'] : array();
				?>
				<article class="<?php echo esc_attr( $plan_class ); ?>">
					<?php if ( ! empty( $plan['is_popular'] ) ) : ?>
						<span class="wd-care__badge"><?php esc_html_e( 'Popular Choice', 'smart-leading-net' ); ?></span>
					<?php endif; ?>
					<h3 class="wd-care__name"><?php echo esc_html( $plan['name'] ?? '' ); ?></h3>
					<?php if ( ! empty( $plan['price'] ) ) : ?>
						<div class="wd-care__price"><?php echo esc_html( $plan['price'] ); ?></div>
					<?php endif; ?>
					<?php if ( sln_wd_plain_text( $plan['description'] ?? '' ) ) : ?>
						<p class="wd-care__desc"><?php echo esc_html( sln_wd_plain_text( $plan['description'] ) ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $features ) ) : ?>
						<ul class="wd-care__features">
							<?php foreach ( $features as $feature ) : ?>
								<li><?php echo esc_html( $feature ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<?php if ( ! empty( $plan['button_text'] ) ) : ?>
						<div class="wd-care__cta">
							<?php
							sln_render_wd_page_button(
								array(
									'text'    => $plan['button_text'],
									'url'     => $plan['button_url'] ?? '#wd-contact',
									'variant' => ! empty( $plan['is_popular'] ) ? 'primary' : 'outline',
									'arrow'   => true,
									'class'   => 'web-development-cta--plan',
								)
							);
							?>
						</div>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
