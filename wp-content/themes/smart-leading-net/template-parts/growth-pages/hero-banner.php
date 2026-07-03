<?php
/**
 * Growth Page hero banner.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$banner  = sln_get_growth_page_banner();
$metrics = sln_get_growth_page_growth_metrics();

$core_service_text = '' !== trim( $banner['core_service_text'] )
	? $banner['core_service_text']
	: __( 'Core Service', 'smart-leading-net' );

$has_heading = ! empty( $banner['small_heading'] ) || ! empty( $banner['main_heading'] ) || ! empty( $banner['highlight_word'] );
$has_badges  = ! empty( $banner['small_heading'] ) || '' !== trim( $core_service_text );
$has_copy    = sln_growth_page_wysiwyg_has_content( $banner['description'] );
$has_primary = ! empty( $banner['primary_btn_text'] ) && ! empty( $banner['primary_btn_url'] );
$has_card_primary = ! empty( $banner['secondary_btn_text'] ) && ! empty( $banner['secondary_btn_url'] );
$has_buttons = $has_primary || $has_card_primary;
$has_image   = ! empty( $banner['banner_image_url'] );

$metric_items = array(
	array(
		'label'       => $metrics['revenue_growth_label'],
		'value'       => $metrics['revenue_growth_value'],
		'value_class' => 'growth-page-hero__metric-value--accent',
		'badge'       => __( 'Generated', 'smart-leading-net' ),
		'badge_class' => 'growth-page-hero__metric-badge--accent',
	),
	array(
		'label'       => $metrics['roas_label'],
		'value'       => $metrics['roas_value'],
		'value_class' => 'growth-page-hero__metric-value--primary',
		'badge'       => __( 'Increased', 'smart-leading-net' ),
		'badge_class' => 'growth-page-hero__metric-badge--primary',
	),
	array(
		'label'       => $metrics['leads_generated_label'],
		'value'       => $metrics['leads_generated_value'],
		'value_class' => 'growth-page-hero__metric-value--primary',
		'badge'       => __( '& Growing', 'smart-leading-net' ),
		'badge_class' => 'growth-page-hero__metric-badge--primary',
	),
	array(
		'label'       => $metrics['conversion_boost_label'],
		'value'       => $metrics['conversion_boost_value'],
		'value_class' => 'growth-page-hero__metric-value--accent',
		'badge'       => __( 'Average', 'smart-leading-net' ),
		'badge_class' => 'growth-page-hero__metric-badge--accent',
	),
);

if ( ! $has_heading && ! $has_copy && ! $has_buttons ) {
	return;
}
?>

<section class="growth-page-hero gp-hero gp-section" aria-labelledby="growth-page-hero-heading">
	<div class="sls-container growth-page-hero__container gp-container">
		<div class="growth-page-hero__stage gp-hero-inner">
			<div class="growth-page-hero__content gp-hero-content">
				<?php if ( $has_badges ) : ?>
					<div class="growth-page-hero__badges">
						<?php if ( ! empty( $banner['small_heading'] ) ) : ?>
							<span class="growth-page-hero__badge-pill growth-page-hero__badge-pill--primary">
								<?php echo esc_html( $banner['small_heading'] ); ?>
							</span>
						<?php endif; ?>
						<span class="growth-page-hero__badge-pill growth-page-hero__badge-pill--secondary">
							<span class="growth-page-hero__badge-check" aria-hidden="true">
								<svg width="10" height="8" viewBox="0 0 10 8" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M1 4.2L3.6 6.8L9 1.2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</span>
							<?php echo esc_html( $core_service_text ); ?>
						</span>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $banner['main_heading'] ) || ! empty( $banner['highlight_word'] ) ) : ?>
					<h1 id="growth-page-hero-heading" class="growth-page-hero__heading gp-hero-title">
						<?php if ( ! empty( $banner['main_heading'] ) ) : ?>
							<span class="growth-page-hero__heading-line"><?php echo esc_html( $banner['main_heading'] ); ?></span>
						<?php endif; ?>

						<?php if ( ! empty( $banner['highlight_word'] ) ) : ?>
							<span class="growth-page-hero__heading-line growth-page-hero__heading-line--accent">
								<span class="growth-page-hero__accent-wrap">
									<span class="growth-page-hero__heading-accent"><?php echo esc_html( $banner['highlight_word'] ); ?></span>
									<span class="growth-page-hero__accent-underline" aria-hidden="true"></span>
								</span>
							</span>
						<?php endif; ?>
					</h1>
				<?php endif; ?>

				<?php if ( sln_growth_page_wysiwyg_has_content( $banner['description'] ) ) : ?>
					<div class="growth-page-hero__description"><?php echo sln_growth_page_format_wysiwyg_content( $banner['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<?php endif; ?>

				<?php if ( $has_primary ) : ?>
					<div class="gp-hero-buttons">
					<?php
					sln_render_cta_button(
						array(
							'text'    => $banner['primary_btn_text'],
							'url'     => $banner['primary_btn_url'],
							'variant' => 'primary',
							'class'   => 'growth-page-hero__cta',
						)
					);
					?>
					</div>
				<?php endif; ?>
			</div>

			<div class="growth-page-hero__visual gp-hero-metrics">
				<?php if ( $has_image ) : ?>
					<img
						class="growth-page-hero__deco-image"
						src="<?php echo esc_url( $banner['banner_image_url'] ); ?>"
						alt=""
						aria-hidden="true"
						loading="lazy"
						decoding="async"
					/>
				<?php endif; ?>

				<div class="growth-page-hero__metrics card-custom gp-metrics-card" aria-label="<?php esc_attr_e( 'Growth metrics', 'smart-leading-net' ); ?>">
					<h2 class="growth-page-hero__metrics-title"><?php echo esc_html( $metrics['metrics_heading'] ); ?></h2>

					<div class="growth-page-hero__metrics-grid gp-metrics-grid">
						<?php foreach ( $metric_items as $metric_item ) : ?>
							<div class="growth-page-hero__metric">
								<?php if ( ! empty( $metric_item['label'] ) ) : ?>
									<span class="growth-page-hero__metric-label"><?php echo esc_html( $metric_item['label'] ); ?></span>
								<?php endif; ?>
								<?php if ( ! empty( $metric_item['value'] ) ) : ?>
									<strong class="growth-page-hero__metric-value <?php echo esc_attr( $metric_item['value_class'] ); ?>"><?php echo esc_html( $metric_item['value'] ); ?></strong>
								<?php endif; ?>
								<?php if ( ! empty( $metric_item['badge'] ) ) : ?>
									<span class="growth-page-hero__metric-badge <?php echo esc_attr( $metric_item['badge_class'] ); ?>"><?php echo esc_html( $metric_item['badge'] ); ?></span>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>

					<div class="growth-page-hero__metrics-actions gp-metrics-buttons gp-hero-buttons">
						<?php if ( $has_card_primary ) : ?>
							<?php
							sln_render_cta_button(
								array(
									'text'       => $banner['secondary_btn_text'],
									'url'        => $banner['secondary_btn_url'],
									'variant'    => 'primary',
									'show_arrow' => false,
									'class'      => 'growth-page-hero__metrics-btn',
								)
							);
							?>
						<?php endif; ?>
						<?php
						sln_render_cta_button(
							array(
								'text'       => __( 'Case Studies', 'smart-leading-net' ),
								'url'        => home_url( '/#case-studies-heading' ),
								'variant'    => 'secondary',
								'show_arrow' => false,
								'class'      => 'growth-page-hero__metrics-btn',
							)
						);
						?>
					</div>
				</div>

				<span class="growth-page-hero__deco growth-page-hero__deco--chart" aria-hidden="true">
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
						<rect x="3" y="10" width="3" height="7" rx="1" fill="currentColor"/>
						<rect x="8.5" y="6" width="3" height="11" rx="1" fill="currentColor"/>
						<rect x="14" y="3" width="3" height="14" rx="1" fill="currentColor"/>
					</svg>
				</span>
				<span class="growth-page-hero__deco growth-page-hero__deco--trend" aria-hidden="true">
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M3 14L8 9L12 12L17 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M12 5H17V10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</span>
			</div>
		</div>
	</div>
</section>
