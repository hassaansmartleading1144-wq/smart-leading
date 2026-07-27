<?php
/**
 * Careers — open positions.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$positions = sln_get_careers_positions();
?>

<section class="careers-page__section careers-page__positions" id="careers-positions" aria-labelledby="careers-positions-heading">
	<div class="sls-container">
		<header class="careers-page__section-head careers-page__section-head--center careers-page__reveal">
			<p class="careers-page__eyebrow"><?php esc_html_e( 'Open Positions', 'smart-leading-net' ); ?></p>
			<h2 id="careers-positions-heading" class="careers-page__section-title">
				<?php esc_html_e( 'Roles Where You Can Make An Impact', 'smart-leading-net' ); ?>
			</h2>
			<p class="careers-page__section-desc">
				<?php esc_html_e( 'Explore current openings across growth, engineering, design, and people. Do not see your role? Submit a general application below.', 'smart-leading-net' ); ?>
			</p>
		</header>

		<div class="careers-page__jobs-grid">
			<?php foreach ( $positions as $job ) : ?>
				<article class="careers-page__job-card careers-page__reveal">
					<div class="careers-page__job-meta">
						<span class="careers-page__job-dept"><?php echo esc_html( $job['department'] ); ?></span>
						<span class="careers-page__job-type"><?php echo esc_html( $job['type'] ); ?></span>
					</div>
					<h3 class="careers-page__job-title"><?php echo esc_html( $job['title'] ); ?></h3>
					<p class="careers-page__job-desc"><?php echo esc_html( $job['description'] ); ?></p>
					<ul class="careers-page__job-tags">
						<li><?php echo esc_html( $job['location'] ); ?></li>
						<li><?php echo esc_html( $job['experience'] ); ?></li>
						<?php if ( ! empty( $job['salary'] ) ) : ?>
							<li><?php echo esc_html( $job['salary'] ); ?></li>
						<?php endif; ?>
					</ul>
					<?php
					sln_render_careers_page_button(
						array(
							'text'    => __( 'Apply Now', 'smart-leading-net' ),
							'url'     => '#careers-apply',
							'variant' => 'outline',
							'class'   => 'careers-page__job-btn',
						)
					);
					?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
