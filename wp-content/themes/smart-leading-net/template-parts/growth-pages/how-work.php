<?php
/**
 * How Work — Growth Page tabbed process section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$data = sln_get_growth_page_how_work();

if ( empty( $data['tabs'] ) ) {
	return;
}

$has_header = ! empty( $data['label'] )
	|| ! empty( $data['heading_lead'] )
	|| ! empty( $data['highlight_word_1'] )
	|| ! empty( $data['highlight_word_2'] )
	|| sln_growth_page_wysiwyg_has_content( $data['description'] );
?>

<section class="how-work" aria-labelledby="how-work-heading">
	<div class="how-work__container sls-container">
		<?php if ( $has_header ) : ?>
			<header class="how-work__header">
				<?php if ( ! empty( $data['label'] ) ) : ?>
					<p class="how-work__label"><?php echo esc_html( $data['label'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $data['heading_lead'] ) || ! empty( $data['highlight_word_1'] ) || ! empty( $data['highlight_word_2'] ) ) : ?>
					<h2 id="how-work-heading" class="how-work__heading how-work__title">
						<?php if ( ! empty( $data['heading_lead'] ) ) : ?>
							<?php echo esc_html( $data['heading_lead'] ); ?>
						<?php endif; ?>
						<?php if ( ! empty( $data['highlight_word_1'] ) ) : ?>
							<?php echo ! empty( $data['heading_lead'] ) ? ' ' : ''; ?><span class="how-work__accent how-work__accent--accent"><?php echo esc_html( $data['highlight_word_1'] ); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $data['highlight_word_2'] ) ) : ?>
							<?php echo ( ! empty( $data['heading_lead'] ) || ! empty( $data['highlight_word_1'] ) ) ? ' ' : ''; ?><span class="how-work__accent how-work__accent--primary"><?php echo esc_html( $data['highlight_word_2'] ); ?></span>
						<?php endif; ?>
					</h2>
				<?php endif; ?>

				<?php if ( sln_growth_page_wysiwyg_has_content( $data['description'] ) ) : ?>
					<div class="how-work__description"><?php echo sln_growth_page_format_wysiwyg_content( $data['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<?php endif; ?>
			</header>
		<?php endif; ?>

		<div class="our-services__tabs" role="tablist" aria-label="<?php esc_attr_e( 'Revenue growth process steps', 'smart-leading-net' ); ?>">
			<?php foreach ( $data['tabs'] as $index => $tab ) : ?>
				<button
					type="button"
					class="our-services__tab<?php echo 0 === $index ? ' is-active' : ''; ?>"
					id="how-work-tab-<?php echo esc_attr( $tab['slug'] ); ?>"
					role="tab"
					aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>"
					aria-controls="how-work-panel-<?php echo esc_attr( $tab['slug'] ); ?>"
					data-tab="<?php echo esc_attr( $tab['slug'] ); ?>"
				>
					<span class="our-services__tab-label"><?php echo esc_html( $tab['tab_label'] ); ?></span>
				</button>
			<?php endforeach; ?>
		</div>

		<div class="our-services__panels">
			<?php foreach ( $data['tabs'] as $index => $tab ) : ?>
				<?php $is_active = 0 === $index; ?>
				<div
					class="our-services__panel<?php echo $is_active ? ' is-active' : ''; ?>"
					id="how-work-panel-<?php echo esc_attr( $tab['slug'] ); ?>"
					role="tabpanel"
					data-panel="<?php echo esc_attr( $tab['slug'] ); ?>"
					aria-labelledby="how-work-tab-<?php echo esc_attr( $tab['slug'] ); ?>"
					<?php echo $is_active ? '' : ' hidden'; ?>
				>
					<div class="how-work__layout">
						<div class="how-work__content">
							<?php if ( ! empty( $tab['content_heading'] ) ) : ?>
								<h3 class="section-label"><?php echo esc_html( $tab['content_heading'] ); ?></h3>
							<?php endif; ?>

							<?php if ( sln_growth_page_wysiwyg_has_content( $tab['content_description'] ) ) : ?>
								<div class="how-work__content-text"><?php echo sln_growth_page_format_wysiwyg_content( $tab['content_description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
							<?php endif; ?>

							<?php if ( ! empty( $tab['activities'] ) ) : ?>
								<div class="how-work__activities">
									<span class="section-label"><?php esc_html_e( 'Key Activities', 'smart-leading-net' ); ?></span>
									<ul class="how-work__activities-list">
										<?php foreach ( $tab['activities'] as $activity ) : ?>
											<li class="how-work__activity">
												<span class="how-work__activity-check" aria-hidden="true">
													<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path d="M2.5 6L5 8.5L9.5 3.5" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
													</svg>
												</span>
												<span><?php echo esc_html( $activity ); ?></span>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>
						</div>

						<aside class="how-work__card" aria-label="<?php echo esc_attr( $tab['card_heading'] ); ?>">
							<span class="how-work__card-step" aria-hidden="true"><?php echo esc_html( $tab['step_number'] ); ?></span>
							<div class="how-work__card-body">
								<?php if ( ! empty( $tab['card_heading'] ) ) : ?>
									<span class="section-label"><?php echo esc_html( $tab['card_heading'] ); ?></span>
								<?php endif; ?>

								<?php if ( sln_growth_page_wysiwyg_has_content( $tab['card_description'] ) ) : ?>
									<div class="how-work__card-text"><?php echo sln_growth_page_format_wysiwyg_content( $tab['card_description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
								<?php endif; ?>

								<?php if ( ! empty( $tab['stats'] ) ) : ?>
									<div class="how-work__stats">
										<?php foreach ( $tab['stats'] as $stat ) : ?>
											<div class="how-work__stat">
												<?php if ( ! empty( $stat['number'] ) ) : ?>
													<p class="how-work__stat-number"><?php echo esc_html( $stat['number'] ); ?></p>
												<?php endif; ?>
												<?php if ( ! empty( $stat['label'] ) ) : ?>
													<p class="how-work__stat-label"><?php echo esc_html( $stat['label'] ); ?></p>
												<?php endif; ?>
											</div>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
							</div>
						</aside>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
