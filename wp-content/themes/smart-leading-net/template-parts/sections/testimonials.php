<?php
/**
 * Testimonials section — stats card and client reviews.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$testimonials_uploads     = '2026/05/';
$testimonials_uploads_url = trailingslashit( content_url( '/uploads/2026/05' ) );
$testimonials_bg_file     = WP_CONTENT_DIR . '/uploads/2026/05/testimonials-bg.webp';

if ( file_exists( $testimonials_bg_file ) ) {
	$testimonials_bg = $testimonials_uploads_url . rawurlencode( 'testimonials-bg.webp' );
} else {
	$testimonials_bg = $testimonials_uploads_url . 'case-studies-bg.webp';
}

$testimonials_stats = array(
	array(
		'icon'     => 'client-reviews.svg',
		'counter'  => 28,
		'prefix'   => '',
		'suffix'   => 'K+',
		'decimals' => 0,
		'label'    => __( 'Client Reviews', 'smart-leading-net' ),
	),
	array(
		'icon'     => 'rating-star.svg',
		'counter'  => 4.9,
		'prefix'   => '',
		'suffix'   => '★',
		'decimals' => 1,
		'label'    => __( 'Average Rating', 'smart-leading-net' ),
	),
	array(
		'icon'     => 'website-review.svg',
		'counter'  => 200,
		'prefix'   => '',
		'suffix'   => '+',
		'decimals' => 0,
		'label'    => __( 'Website Build', 'smart-leading-net' ),
	),
	array(
		'icon'     => 'revenue review.svg',
		'counter'  => 50,
		'prefix'   => '$',
		'suffix'   => 'M+',
		'decimals' => 0,
		'label'    => __( 'Revenue Generated', 'smart-leading-net' ),
	),
);

$testimonials_reviews = array(
	array(
		'text'     => __( 'Highly cooperative and honest with their work. They developed my business website and I am super happy with them. On time delivery and 24hrs support. They also manage our social media — they know what they\'re doing.', 'smart-leading-net' ),
		'name'     => __( 'Sarah Mitchell', 'smart-leading-net' ),
		'role'     => __( 'Growth Labs', 'smart-leading-net' ),
		'initials' => 'SM',
	),
	array(
		'text'     => __( 'Before working with Smart Leading, our campaigns lacked direction. They helped us build a clear strategy, improve conversions, and understand exactly where our growth was coming from.', 'smart-leading-net' ),
		'name'     => __( 'James Carter', 'smart-leading-net' ),
		'role'     => __( 'Managing Director', 'smart-leading-net' ),
		'initials' => 'JC',
	),
);
?>

<section
	class="testimonials"
	aria-labelledby="testimonials-heading"
	style="--testimonials-bg: url('<?php echo esc_url( $testimonials_bg ); ?>');"
>
	<div class="sls-container testimonials__container">
		<header class="testimonials__header">
			<p class="testimonials__label"><?php esc_html_e( 'Testimonials', 'smart-leading-net' ); ?></p>

			<h2 id="testimonials-heading" class="testimonials__heading testimonials__title">
				<?php
				echo wp_kses(
					sprintf(
						/* translators: %s: highlighted word */
						__( 'Trusted Partnerships Built On %s', 'smart-leading-net' ),
						'<span class="testimonials__heading-accent">' . esc_html__( 'Results', 'smart-leading-net' ) . '</span>'
					),
					array(
						'span' => array(
							'class' => true,
						),
					)
				);
				?>
			</h2>
		</header>

		<div class="testimonials__content">
			<aside class="testimonials__stats-card" aria-label="<?php esc_attr_e( 'Client statistics', 'smart-leading-net' ); ?>">
				<div class="testimonials__stats-grid">
					<?php foreach ( $testimonials_stats as $stat ) : ?>
						<div class="testimonials__stat">
							<div class="testimonials__stat-icon" aria-hidden="true">
								<?php
								echo sln_get_upload_inline_svg( $testimonials_uploads . $stat['icon'], 'testimonials__stat-svg', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>
							</div>

							<div class="testimonials__stat-content">
								<p
									class="testimonials__stat-number"
									data-counter-value="<?php echo esc_attr( (string) $stat['counter'] ); ?>"
									data-counter-prefix="<?php echo esc_attr( $stat['prefix'] ); ?>"
									data-counter-suffix="<?php echo esc_attr( $stat['suffix'] ); ?>"
									data-counter-decimals="<?php echo esc_attr( (string) $stat['decimals'] ); ?>"
								>
									<?php
									$display_value = $stat['decimals'] > 0
										? number_format( $stat['counter'], $stat['decimals'], '.', '' )
										: (string) (int) $stat['counter'];
									echo esc_html( $stat['prefix'] . $display_value . $stat['suffix'] );
									?>
								</p>

								<p class="testimonials__stat-label"><?php echo esc_html( $stat['label'] ); ?></p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="testimonials__stats-footer">
					<div class="testimonials__stats-footer-left">
						<p class="testimonials__stats-footer-title"><?php esc_html_e( '28k+ Client Reviews', 'smart-leading-net' ); ?></p>
						<div class="testimonials__stars testimonials__stars--footer" aria-hidden="true">
							<span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
						</div>
					</div>

					<div class="testimonials__verified-badge">
						<span class="testimonials__verified-icon" aria-hidden="true">
							<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M11.6667 3.5L5.83333 9.91667L2.33333 6.41667" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</span>
						<?php esc_html_e( 'Verified', 'smart-leading-net' ); ?>
					</div>
				</div>
			</aside>

			<div class="testimonials__reviews">
				<?php foreach ( $testimonials_reviews as $review ) : ?>
					<article class="testimonials__review-card">
						<div class="testimonial-card__quote" aria-hidden="true">
							<?php
							echo sln_get_upload_inline_svg( $testimonials_uploads . 'quote-line.svg', '', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
						</div>

						<div class="testimonials__stars" aria-label="<?php esc_attr_e( '5 out of 5 stars', 'smart-leading-net' ); ?>">
							<span aria-hidden="true">★</span><span aria-hidden="true">★</span><span aria-hidden="true">★</span><span aria-hidden="true">★</span><span aria-hidden="true">★</span>
						</div>

						<blockquote class="testimonials__review-text">
							<p><?php echo esc_html( $review['text'] ); ?></p>
						</blockquote>

						<div class="testimonials__review-divider" aria-hidden="true"></div>

						<div class="testimonials__review-author">
							<div class="testimonials__review-avatar" aria-hidden="true">
								<span><?php echo esc_html( $review['initials'] ); ?></span>
							</div>

							<div class="testimonials__review-meta">
								<p class="testimonials__review-name"><?php echo esc_html( $review['name'] ); ?></p>
								<p class="testimonials__review-role"><?php echo esc_html( $review['role'] ); ?></p>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
