<?php
/**
 * Web Development — Website Pricing (HTML design layout).
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = sln_get_wd_pricing_section();
$plans   = sln_get_wd_pricing_plans();

if ( ! sln_wd_row_is_active( $section ) || empty( $plans ) ) {
	return;
}

$icon_map = array(
	'starter'         => 'pen',
	'growth'          => 'chart',
	'online store'    => 'cart',
	'custom / larger' => 'puzzle',
);

$default_icons = array( 'pen', 'chart', 'cart', 'puzzle' );
?>

<section class="wd-pricing" id="wd-pricing" aria-labelledby="wd-pricing-heading">
	<div class="wd-wrap">
		<header class="wd-head wd-animate">
			<?php if ( ! empty( $section['small_heading'] ) ) : ?>
				<p class="wd-eyebrow"><?php echo esc_html( $section['small_heading'] ); ?></p>
			<?php endif; ?>
			<h2 id="wd-pricing-heading" class="wd-title">
				<?php
				$pricing_heading   = trim( (string) ( $section['main_heading'] ?? '' ) );
				$pricing_highlight = trim( (string) ( $section['highlighted_text'] ?? '' ) );

				if ( preg_match( '/^(.*?)(\s*No pushy sales calls\.?\s*)$/iu', $pricing_heading, $pricing_parts ) ) {
					echo esc_html( rtrim( $pricing_parts[1] ) ) . ' <span class="wd-hl">' . esc_html( trim( $pricing_parts[2] ) ) . '</span>';
				} elseif ( $pricing_highlight && false === stripos( $pricing_heading, $pricing_highlight ) ) {
					echo esc_html( $pricing_heading ) . ' <span class="wd-hl">' . esc_html( $pricing_highlight ) . '</span>';
				} else {
					echo esc_html( $pricing_heading );
				}
				?>
			</h2>
			<?php if ( sln_wd_plain_text( $section['description'] ?? '' ) ) : ?>
				<div class="wd-lead"><?php echo sln_wd_format_content( $section['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
		</header>

		<div class="wd-pricing__grid">
			<?php foreach ( $plans as $index => $plan ) : ?>
				<?php
				$is_popular = ! empty( $plan['is_popular'] );
				$plan_class = 'wd-pricing__card wd-animate';
				if ( $is_popular ) {
					$plan_class .= ' wd-pricing__card--popular';
				}
				$features  = is_array( $plan['features'] ?? null ) ? $plan['features'] : array();
				$name_key  = strtolower( trim( (string) ( $plan['name'] ?? '' ) ) );
				$icon_key  = $icon_map[ $name_key ] ?? ( $default_icons[ $index ] ?? 'generic' );
				?>
				<article class="<?php echo esc_attr( $plan_class ); ?>">
					<?php if ( $is_popular ) : ?>
						<span class="wd-pricing__badge">
							<span class="wd-pricing__badge-star" aria-hidden="true">★</span>
							<?php esc_html_e( 'Most popular', 'smart-leading-net' ); ?>
						</span>
					<?php endif; ?>

					<div class="wd-pricing__icon" aria-hidden="true">
						<?php echo sln_wd_fallback_icon( $icon_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>

					<h3 class="wd-pricing__name"><?php echo esc_html( $plan['name'] ?? '' ); ?></h3>

					<?php if ( ! empty( $plan['price'] ) || ! empty( $plan['timeline'] ) ) : ?>
						<div class="wd-pricing__meta">
							<?php if ( ! empty( $plan['price'] ) ) : ?>
								<span class="wd-pricing__price"><?php echo esc_html( $plan['price'] ); ?></span>
							<?php endif; ?>
							<?php if ( ! empty( $plan['price'] ) && ! empty( $plan['timeline'] ) ) : ?>
								<span class="wd-pricing__meta-sep" aria-hidden="true"></span>
							<?php endif; ?>
							<?php if ( ! empty( $plan['timeline'] ) ) : ?>
								<span class="wd-pricing__timeline"><?php echo esc_html( $plan['timeline'] ); ?></span>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<hr class="wd-pricing__divider" />

					<?php if ( ! empty( $features ) ) : ?>
						<ul class="wd-pricing__features">
							<?php foreach ( $features as $feature ) : ?>
								<li>
									<span class="wd-pricing__check" aria-hidden="true">
										<svg width="10" height="10" viewBox="0 0 24 24" fill="none"><path d="M5 13l4 4L19 7" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
									</span>
									<span class="wd-pricing__feature-text"><?php echo esc_html( $feature ); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php if ( ! empty( $plan['button_text'] ) ) : ?>
						<div class="wd-pricing__cta">
							<?php
							sln_render_wd_page_button(
								array(
									'text'    => $plan['button_text'],
									'url'     => $plan['button_url'] ?? '#wd-contact',
									'variant' => $is_popular ? 'primary' : 'outline',
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
