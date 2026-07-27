<?php
/**
 * Careers — hero section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<section class="careers-page__hero" id="careers-hero" aria-labelledby="careers-hero-heading">
	<div class="careers-page__hero-grid" aria-hidden="true"></div>
	<div class="sls-container careers-page__hero-inner">
		<div class="careers-page__hero-copy careers-page__reveal">
			<p class="careers-page__eyebrow careers-page__eyebrow--light"><?php esc_html_e( 'Careers', 'smart-leading-net' ); ?></p>
			<h1 id="careers-hero-heading" class="careers-page__hero-title">
				<?php
				echo wp_kses(
					__( 'Build Your Future With <span class="careers-page__hero-highlight">Smart Leading</span>', 'smart-leading-net' ),
					array( 'span' => array( 'class' => true ) )
				);
				?>
			</h1>
			<p class="careers-page__hero-lead">
				<?php esc_html_e( 'Join a marketing agency where craftsmanship, ownership, and measurable growth define every role. We hire curious people who want to ship work that moves revenue — and build careers they are proud of.', 'smart-leading-net' ); ?>
			</p>
			<div class="careers-page__hero-cta">
				<?php
				sln_render_careers_page_button(
					array(
						'text'    => __( 'View Open Positions', 'smart-leading-net' ),
						'url'     => '#careers-positions',
						'variant' => 'secondary',
						'arrow'   => true,
					)
				);
				sln_render_careers_page_button(
					array(
						'text'    => __( 'Life at Smart Leading', 'smart-leading-net' ),
						'url'     => '#careers-life',
						'variant' => 'white',
					)
				);
				?>
			</div>
		
		</div>

		<div class="careers-page__hero-visual careers-page__reveal" aria-hidden="true">
			<div class="careers-page__hero-card">
				<div class="careers-page__hero-card-top">
					<span class="careers-page__hero-dot"></span>
					<span class="careers-page__hero-dot"></span>
					<span class="careers-page__hero-dot"></span>
				</div>
				<div class="careers-page__hero-card-body">
					<p class="careers-page__hero-card-label"><?php esc_html_e( 'Open roles this month', 'smart-leading-net' ); ?></p>
					<p class="careers-page__hero-card-metric">12+</p>
					<p class="careers-page__hero-card-note"><?php esc_html_e( 'Across growth, engineering, design & people', 'smart-leading-net' ); ?></p>
					<div class="careers-page__hero-pills">
						<span><?php esc_html_e( 'SEO', 'smart-leading-net' ); ?></span>
						<span><?php esc_html_e( 'PPC', 'smart-leading-net' ); ?></span>
						<span><?php esc_html_e( 'Dev', 'smart-leading-net' ); ?></span>
						<span><?php esc_html_e( 'Design', 'smart-leading-net' ); ?></span>
					</div>
				</div>
			
			</div>
		</div>
	</div>
</section>
