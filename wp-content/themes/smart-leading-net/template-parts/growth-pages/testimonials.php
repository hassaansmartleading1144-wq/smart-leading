<?php
/**
 * Testimonials — Growth Page stats card and client reviews.
 *
 * Reuses the Home Page Testimonials markup and CSS classes.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$data = sln_get_growth_page_testimonials();

if ( empty( $data['stats'] ) && empty( $data['reviews'] ) ) {
	return;
}

$has_header = ! empty( $data['label'] )
	|| ! empty( $data['heading_lead'] )
	|| ! empty( $data['highlight_word'] );
?>

<section
	class="testimonials testimonials-section"
	aria-labelledby="growth-testimonials-heading"
>
	<div class="sls-container testimonials__container">
		<?php if ( $has_header ) : ?>
			<header class="testimonials__header">
				<?php if ( ! empty( $data['label'] ) ) : ?>
					<p class="testimonials__label"><?php echo esc_html( $data['label'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $data['heading_lead'] ) || ! empty( $data['highlight_word'] ) ) : ?>
					<h2 id="growth-testimonials-heading" class="testimonials__heading testimonials__title">
						<?php if ( ! empty( $data['heading_lead'] ) ) : ?>
							<?php echo esc_html( $data['heading_lead'] ); ?>
						<?php endif; ?>
						<?php if ( ! empty( $data['highlight_word'] ) ) : ?>
							<?php echo ! empty( $data['heading_lead'] ) ? ' ' : ''; ?>
							<span class="testimonials__heading-accent"><?php echo esc_html( $data['highlight_word'] ); ?></span>
						<?php endif; ?>
					</h2>
				<?php endif; ?>
			</header>
		<?php endif; ?>

		<div class="testimonials__content">
			<?php if ( ! empty( $data['stats'] ) ) : ?>
				<aside class="testimonials__stats-card" aria-label="<?php esc_attr_e( 'Client statistics', 'smart-leading-net' ); ?>">
					<div class="testimonials__stats-grid">
						<?php foreach ( $data['stats'] as $stat ) : ?>
							<div class="testimonials__stat">
								<?php if ( ! empty( $stat['icon_id'] ) || ! empty( $stat['icon_fallback'] ) ) : ?>
									<div class="testimonials__stat-icon" aria-hidden="true">
										<?php
										if ( ! empty( $stat['icon_id'] ) ) {
											echo sln_get_attachment_inline_svg( absint( $stat['icon_id'] ), 'testimonials__stat-svg', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										} else {
											echo sln_get_upload_inline_svg( SLN_GP_TESTIMONIALS_UPLOADS . $stat['icon_fallback'], 'testimonials__stat-svg', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										}
										?>
									</div>
								<?php endif; ?>

								<div class="testimonials__stat-content">
									<?php if ( ! empty( $stat['display_number'] ) ) : ?>
										<p
											class="testimonials__stat-number"
											<?php if ( is_numeric( $stat['counter_value'] ) ) : ?>
												data-counter-value="<?php echo esc_attr( $stat['counter_value'] ); ?>"
												data-counter-prefix="<?php echo esc_attr( $stat['counter_prefix'] ); ?>"
												data-counter-suffix="<?php echo esc_attr( $stat['counter_suffix'] ); ?>"
												data-counter-decimals="<?php echo esc_attr( (string) $stat['counter_decimals'] ); ?>"
											<?php endif; ?>
										>
											<?php echo esc_html( $stat['display_number'] ); ?>
										</p>
									<?php endif; ?>

									<?php if ( ! empty( $stat['label'] ) ) : ?>
										<p class="testimonials__stat-label"><?php echo esc_html( $stat['label'] ); ?></p>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>

					<?php if ( ! empty( $data['summary']['review_title'] ) || ! empty( $data['summary']['verified_text'] ) ) : ?>
						<div class="testimonials__stats-footer">
							<div class="testimonials__stats-footer-left">
								<?php if ( ! empty( $data['summary']['review_title'] ) ) : ?>
									<p class="testimonials__stats-footer-title"><?php echo esc_html( $data['summary']['review_title'] ); ?></p>
								<?php endif; ?>
								<?php sln_growth_page_render_testimonial_stars( $data['summary']['star_rating'], true ); ?>
							</div>

							<?php if ( ! empty( $data['summary']['verified_text'] ) ) : ?>
								<div class="testimonials__verified-badge">
									<span class="testimonials__verified-icon" aria-hidden="true">
										<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M11.6667 3.5L5.83333 9.91667L2.33333 6.41667" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
									</span>
									<?php echo esc_html( $data['summary']['verified_text'] ); ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</aside>
			<?php endif; ?>

			<?php if ( ! empty( $data['reviews'] ) ) : ?>
				<div class="testimonials__reviews">
					<?php foreach ( $data['reviews'] as $review ) : ?>
						<article class="testimonials__review-card">
							<div class="testimonial-card__quote" aria-hidden="true">
								<?php
								echo sln_get_upload_inline_svg( SLN_GP_TESTIMONIALS_UPLOADS . 'quote-line.svg', '', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>
							</div>

							<?php sln_growth_page_render_testimonial_stars( $review['rating'] ); ?>

							<?php if ( ! empty( $review['text'] ) ) : ?>
								<blockquote class="testimonials__review-text">
									<p><?php echo esc_html( $review['text'] ); ?></p>
								</blockquote>
							<?php endif; ?>

							<div class="testimonials__review-divider" aria-hidden="true"></div>

							<div class="testimonials__review-author">
								<?php if ( ! empty( $review['author_initials'] ) ) : ?>
									<div class="testimonials__review-avatar" aria-hidden="true">
										<span><?php echo esc_html( $review['author_initials'] ); ?></span>
									</div>
								<?php endif; ?>

								<div class="testimonials__review-meta">
									<?php if ( ! empty( $review['author_name'] ) ) : ?>
										<p class="testimonials__review-name"><?php echo esc_html( $review['author_name'] ); ?></p>
									<?php endif; ?>
									<?php if ( ! empty( $review['author_title'] ) ) : ?>
										<p class="testimonials__review-role"><?php echo esc_html( $review['author_title'] ); ?></p>
									<?php endif; ?>
								</div>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
