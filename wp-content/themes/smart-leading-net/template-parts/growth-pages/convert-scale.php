<?php
/**
 * Convert And Scale — growth page services grid.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$services = sln_get_growth_page_services();

if ( empty( $services['cards'] ) ) {
	return;
}

$has_header = ! empty( $services['label'] )
	|| ! empty( $services['heading_lead'] )
	|| ! empty( $services['heading_accent_primary'] )
	|| ! empty( $services['heading_accent_secondary'] )
	|| sln_growth_page_wysiwyg_has_content( $services['description'] );
?>

<section class="convert-scale" aria-labelledby="convert-scale-heading">
	<div class="convert-scale__container sls-container">
		<?php if ( $has_header ) : ?>
			<header class="convert-scale__header">
				<?php if ( ! empty( $services['label'] ) ) : ?>
					<p class="convert-scale__label"><?php echo esc_html( $services['label'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $services['heading_lead'] ) || ! empty( $services['heading_accent_primary'] ) || ! empty( $services['heading_accent_secondary'] ) ) : ?>
					<h2 id="convert-scale-heading" class="convert-scale__heading convert-scale__title">
						<?php if ( ! empty( $services['heading_lead'] ) ) : ?>
							<?php echo esc_html( $services['heading_lead'] ); ?>
						<?php endif; ?>
						<?php if ( ! empty( $services['heading_accent_primary'] ) ) : ?>
							<span class="convert-scale__accent convert-scale__accent--primary"><?php echo esc_html( $services['heading_accent_primary'] ); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $services['heading_accent_primary'] ) && ( ! empty( $services['heading_accent_secondary'] ) || ! empty( $services['heading_lead'] ) ) ) : ?>
							,
						<?php endif; ?>
						<?php if ( ! empty( $services['heading_accent_secondary'] ) ) : ?>
							<span class="convert-scale__accent convert-scale__accent--accent"><?php echo esc_html( $services['heading_accent_secondary'] ); ?></span>
						<?php endif; ?>
					</h2>
				<?php endif; ?>

				<?php if ( sln_growth_page_wysiwyg_has_content( $services['description'] ) ) : ?>
					<div class="convert-scale__description">
						<?php echo sln_growth_page_format_wysiwyg_content( $services['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php endif; ?>
			</header>
		<?php endif; ?>

		<div class="convert-scale__grid">
			<?php foreach ( $services['cards'] as $service ) : ?>
				<article class="convert-scale__card">
					<span class="convert-scale__number" aria-hidden="true">
						<?php echo esc_html( $service['number'] ); ?>
					</span>

					<?php if ( ! empty( $service['title'] ) ) : ?>
						<h3 class="convert-scale__card-title"><?php echo esc_html( $service['title'] ); ?></h3>
					<?php endif; ?>

					<?php if ( sln_growth_page_wysiwyg_has_content( $service['description'] ) ) : ?>
						<div class="convert-scale__card-text">
							<?php echo sln_growth_page_format_wysiwyg_content( $service['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $service['features'] ) ) : ?>
						<ul class="convert-scale__features">
							<?php foreach ( $service['features'] as $feature ) : ?>
								<li class="convert-scale__feature">
									<span class="convert-scale__feature-check" aria-hidden="true">
										<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M2.5 6L5 8.5L9.5 3.5" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
									</span>
									<span><?php echo esc_html( $feature ); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
