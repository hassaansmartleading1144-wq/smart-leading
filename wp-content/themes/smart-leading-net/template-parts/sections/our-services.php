<?php
/**
 * Our Services section — tabbed services showcase.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$our_services_settings = sln_get_our_services_settings();
$our_services_section  = $our_services_settings['section'];
$our_services_tabs     = array_values(
	array_filter(
		$our_services_settings['tabs'],
		function ( $tab ) {
			return ! empty( $tab['tab_title'] );
		}
	)
);

if ( empty( $our_services_tabs ) ) {
	return;
}
?>

<section
	class="our-services"
	aria-labelledby="our-services-heading"
	data-counter-enabled="<?php echo ! empty( $our_services_settings['counter']['enabled'] ) ? '1' : '0'; ?>"
	data-counter-duration="<?php echo esc_attr( absint( $our_services_settings['counter']['duration'] ) ); ?>"
>
	<div class="sls-container our-services__container">
		<header class="our-services__header">
			<?php if ( ! empty( $our_services_section['label'] ) ) : ?>
				<p class="our-services__label"><?php echo esc_html( $our_services_section['label'] ); ?></p>
			<?php endif; ?>

			<h2 id="our-services-heading" class="our-services__heading our-services__title">
				<?php
				echo wp_kses(
					sprintf(
						'%1$s %2$s And %3$s',
						esc_html( $our_services_section['heading_lead'] ),
						'<span class="our-services__heading-accent">' . esc_html( $our_services_section['heading_highlight_1'] ) . '</span>',
						'<span class="our-services__heading-accent">' . esc_html( $our_services_section['heading_highlight_2'] ) . '</span>'
					),
					array(
						'span' => array(
							'class' => true,
						),
					)
				);
				?>
			</h2>

			<?php if ( ! empty( $our_services_section['description'] ) ) : ?>
				<p class="our-services__description"><?php echo esc_html( $our_services_section['description'] ); ?></p>
			<?php endif; ?>
		</header>

		<div class="our-services__tabs" role="tablist" aria-label="<?php esc_attr_e( 'Service categories', 'smart-leading-net' ); ?>">
			<?php foreach ( $our_services_tabs as $index => $tab ) : ?>
				<button
					type="button"
					class="our-services__tab<?php echo 0 === $index ? ' is-active' : ''; ?>"
					id="our-services-tab-<?php echo esc_attr( $tab['slug'] ); ?>"
					role="tab"
					aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>"
					aria-controls="our-services-panel-<?php echo esc_attr( $tab['slug'] ); ?>"
					data-tab="<?php echo esc_attr( $tab['slug'] ); ?>"
				>
					<span class="our-services__tab-icon<?php echo ! empty( $tab['icon_flip'] ) ? ' our-services__tab-icon--lead-generation' : ''; ?>" aria-hidden="true">
						<?php
						echo sln_get_attachment_inline_svg( absint( $tab['tab_icon_id'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</span>
					<span class="our-services__tab-label"><?php echo esc_html( $tab['tab_title'] ); ?></span>
				</button>
			<?php endforeach; ?>
		</div>

		<div class="our-services__panels">
			<?php foreach ( $our_services_tabs as $index => $tab ) : ?>
				<?php
				$is_active          = 0 === $index;
				$tab_services       = sln_prepare_our_services_items( $tab['services'] ?? array() );
				$tab_results        = sln_prepare_our_services_results( $tab['results'] ?? array() );
				?>
				<div
					class="our-services__panel<?php echo $is_active ? ' is-active' : ''; ?>"
					id="our-services-panel-<?php echo esc_attr( $tab['slug'] ); ?>"
					role="tabpanel"
					data-panel="<?php echo esc_attr( $tab['slug'] ); ?>"
					aria-labelledby="our-services-tab-<?php echo esc_attr( $tab['slug'] ); ?>"
					<?php echo $is_active ? '' : ' hidden'; ?>
				>
					<div class="our-services__content">
						<div class="our-services__featured">
							<?php if ( ! empty( $tab['featured_label'] ) ) : ?>
								<p class="our-services__featured-label"><?php echo esc_html( $tab['featured_label'] ); ?></p>
							<?php endif; ?>

							<?php if ( ! empty( $tab['main_heading'] ) ) : ?>
								<h3 class="our-services__featured-heading"><?php echo esc_html( $tab['main_heading'] ); ?></h3>
							<?php endif; ?>

							<?php if ( ! empty( $tab['description'] ) ) : ?>
								<p class="our-services__featured-text"><?php echo esc_html( $tab['description'] ); ?></p>
							<?php endif; ?>

							<ul class="our-services__bullets">
								<?php foreach ( array( $tab['bullet_1'], $tab['bullet_2'], $tab['bullet_3'] ) as $bullet ) : ?>
									<?php if ( ! empty( $bullet ) ) : ?>
										<li class="our-services__bullet"><?php echo esc_html( $bullet ); ?></li>
									<?php endif; ?>
								<?php endforeach; ?>
							</ul>

							<?php if ( ! empty( $tab_services ) ) : ?>
								<div class="our-services__services">
									<?php foreach ( $tab_services as $service ) : ?>
										<a href="<?php echo esc_url( ! empty( $service['url'] ) ? $service['url'] : '#' ); ?>" class="our-services__service">
											<span class="our-services__service-icon" aria-hidden="true">
												<?php
												echo sln_get_attachment_inline_svg( absint( $service['icon_id'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
												?>
											</span>
											<span class="our-services__service-label"><?php echo esc_html( $service['title'] ); ?></span>
										</a>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>

						<?php if ( ! empty( $tab_results ) ) : ?>
							<div class="our-services__results" aria-label="<?php esc_attr_e( 'Client results', 'smart-leading-net' ); ?>">
								<?php foreach ( $tab_results as $result ) : ?>
									<div class="our-services__result-cell">
										<?php if ( 'stat' === $result['type'] ) : ?>
											<div
												class="our-services__result-value"
												data-counter-value="<?php echo esc_attr( $result['counter_value'] ); ?>"
												data-counter-prefix="<?php echo esc_attr( $result['counter_prefix'] ); ?>"
												data-counter-suffix="<?php echo esc_attr( $result['counter_suffix'] ); ?>"
											><?php echo esc_html( $result['counter_prefix'] . '0' . $result['counter_suffix'] ); ?></div>
											<?php if ( ! empty( $result['label'] ) ) : ?>
												<p class="our-services__result-label"><?php echo esc_html( $result['label'] ); ?></p>
											<?php endif; ?>
										<?php else : ?>
											<img
												class="our-services__result-logo"
												src="<?php echo esc_url( $result['src'] ); ?>"
												alt="<?php echo esc_attr( $result['alt'] ); ?>"
												loading="lazy"
												decoding="async"
											/>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
