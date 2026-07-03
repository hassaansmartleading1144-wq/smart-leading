<?php
/**
 * Price Plan — Growth Page pricing cards section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$data = sln_get_growth_page_price_plan();

if ( empty( $data['cards'] ) ) {
	return;
}

$has_header = ! empty( $data['label'] )
	|| ! empty( $data['heading_lead'] )
	|| ! empty( $data['highlight_word'] )
	|| ! empty( $data['heading_trail'] )
	|| sln_growth_page_wysiwyg_has_content( $data['description'] );
?>

<section class="price-plan price-plan-section"<?php echo ( function_exists( 'sln_is_seo_services_page' ) && sln_is_seo_services_page() ) ? ' id="seo-pricing"' : ''; ?> aria-labelledby="price-plan-heading">
	<div class="price-plan__container sls-container">
		<?php if ( $has_header ) : ?>
			<header class="price-plan__header">
				<?php if ( ! empty( $data['label'] ) ) : ?>
					<p class="price-plan__label"><?php echo esc_html( $data['label'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $data['heading_lead'] ) || ! empty( $data['highlight_word'] ) || ! empty( $data['heading_trail'] ) ) : ?>
					<h2 id="price-plan-heading" class="price-plan__heading price-plan__title">
						<?php if ( ! empty( $data['heading_lead'] ) ) : ?>
							<?php echo esc_html( $data['heading_lead'] ); ?>
						<?php endif; ?>
						<?php if ( ! empty( $data['highlight_word'] ) ) : ?>
							<?php echo ( ! empty( $data['heading_lead'] ) ? ' ' : '' ); ?><span class="price-plan__accent"><?php echo esc_html( $data['highlight_word'] ); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $data['heading_trail'] ) ) : ?>
							<?php echo ( ! empty( $data['heading_lead'] ) || ! empty( $data['highlight_word'] ) ) ? ' ' : ''; ?><?php echo esc_html( $data['heading_trail'] ); ?>
						<?php endif; ?>
					</h2>
				<?php endif; ?>

				<?php if ( sln_growth_page_wysiwyg_has_content( $data['description'] ) ) : ?>
					<div class="price-plan__description"><?php echo sln_growth_page_format_wysiwyg_content( $data['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<?php endif; ?>
			</header>
		<?php endif; ?>

		<div class="price-plan__slider" data-price-plan-slider>
			<div class="price-plan__viewport">
				<div class="price-plan__track">
					<?php foreach ( $data['cards'] as $card ) : ?>
						<article class="price-plan__card price-card<?php echo ! empty( $card['is_popular'] ) ? ' price-plan__card--popular' : ''; ?>">
							<?php if ( ! empty( $card['is_popular'] ) && ! empty( $card['badge_text'] ) ) : ?>
								<span class="price-plan__badge"><?php echo esc_html( $card['badge_text'] ); ?></span>
							<?php endif; ?>

							<?php if ( ! empty( $card['plan_name'] ) ) : ?>
								<h3 class="price-plan__name"><?php echo esc_html( $card['plan_name'] ); ?></h3>
							<?php endif; ?>

							<?php if ( ! empty( $card['price'] ) || ! empty( $card['price_suffix'] ) ) : ?>
								<p class="price-plan__price">
									<?php if ( ! empty( $card['price'] ) ) : ?>
										<span class="price-plan__price-value"><?php echo esc_html( $card['price'] ); ?></span>
									<?php endif; ?>
									<?php if ( ! empty( $card['price_suffix'] ) ) : ?>
										<span class="price-plan__price-suffix"><?php echo esc_html( $card['price_suffix'] ); ?></span>
									<?php endif; ?>
								</p>
							<?php endif; ?>

							<?php if ( sln_growth_page_wysiwyg_has_content( $card['description'] ) ) : ?>
								<div class="price-plan__card-description"><?php echo sln_growth_page_format_wysiwyg_content( $card['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
							<?php endif; ?>

							<?php if ( ! empty( $card['features'] ) ) : ?>
								<ul class="price-plan__features">
									<?php foreach ( $card['features'] as $feature ) : ?>
										<li class="price-plan__feature">
											<span class="price-plan__feature-check" aria-hidden="true">
												<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M2.5 6L5 8.5L9.5 3.5" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
												</svg>
											</span>
											<span><?php echo esc_html( $feature ); ?></span>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							<?php if ( ! empty( $card['button_text'] ) && ! empty( $card['button_url'] ) ) : ?>
								<div class="price-plan__cta">
									<?php
									sln_render_cta_button(
										array(
											'text'       => $card['button_text'],
											'url'        => $card['button_url'],
											'variant'    => ! empty( $card['is_popular'] ) ? 'primary' : 'outline',
											'show_arrow' => false,
											'class'      => 'pricing-btn price-plan__button',
										)
									);
									?>
								</div>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
				</div>
			</div>

			<?php if ( count( $data['cards'] ) > 1 ) : ?>
				<button type="button" class="price-plan__prev" aria-label="<?php esc_attr_e( 'Previous plan', 'smart-leading-net' ); ?>">
					<span aria-hidden="true">&lsaquo;</span>
				</button>
				<button type="button" class="price-plan__next" aria-label="<?php esc_attr_e( 'Next plan', 'smart-leading-net' ); ?>">
					<span aria-hidden="true">&rsaquo;</span>
				</button>
				<div class="price-plan__dots" role="tablist" aria-label="<?php esc_attr_e( 'Pricing plan slides', 'smart-leading-net' ); ?>">
					<?php foreach ( $data['cards'] as $index => $card ) : ?>
						<button
							type="button"
							class="price-plan__dot<?php echo 0 === $index ? ' is-active' : ''; ?>"
							role="tab"
							aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>"
							aria-label="<?php echo esc_attr( sprintf( /* translators: %d: slide number */ __( 'Go to plan %d', 'smart-leading-net' ), $index + 1 ) ); ?>"
							data-slide="<?php echo esc_attr( $index ); ?>"
						></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
