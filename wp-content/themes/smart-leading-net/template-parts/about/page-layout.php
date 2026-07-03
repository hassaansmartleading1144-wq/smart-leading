<?php
/**
 * Shared About page layout — banner, overview, leader section, workflow.
 *
 * @package Smart_Leading_Net
 *
 * @var array $args {
 *     @type string $banner_title       Page banner heading.
 *     @type string $breadcrumb_label   Breadcrumb label.
 *     @type string $heading_id         Banner heading element ID.
 *     @type string $overview_heading_id Overview section heading ID.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$banner_title         = $args['banner_title'] ?? __( 'About Us', 'smart-leading-net' );
$breadcrumb_label     = $args['breadcrumb_label'] ?? $banner_title;
$heading_id           = $args['heading_id'] ?? 'about-page-hero-heading';
$overview_heading_id  = $args['overview_heading_id'] ?? 'about-overview-heading';
?>

<div class="about-page">
	<?php
	sln_render_page_banner(
		array(
			'title'            => $banner_title,
			'breadcrumb_label' => $breadcrumb_label,
			'heading_id'       => $heading_id,
		)
	);
	?>

	<section class="about-overview section-padding" aria-labelledby="<?php echo esc_attr( $overview_heading_id ); ?>">
		<div class="about-overview__container sls-container">
			<div class="about-overview__grid">
				<div class="about-overview__content">
					<p class="about-overview__label"><?php esc_html_e( '15 Years of Experience', 'smart-leading-net' ); ?></p>
					<h2 id="<?php echo esc_attr( $overview_heading_id ); ?>" class="about-overview__title">
						<?php esc_html_e( 'Overview Of Our Company', 'smart-leading-net' ); ?>
					</h2>
					<p class="about-overview__description">
						<?php esc_html_e( 'Smart Leading Solution is a full-service Tech agency which has a team of talented, creative and visionary people. We are the doers, we implement what we plan and take it to the level of success. Technology runs in our blood, whether it is Web Development, Application Development, Digital Marketing, SEM, SEO or Graphic Designing we got it all running.', 'smart-leading-net' ); ?>
					</p>

					<ul class="about-overview__features">
						<li class="about-overview__feature">
							<span class="about-overview__feature-check" aria-hidden="true">
								<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M2.5 7.2L5.4 10.1L11.5 3.9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</span>
							<span><?php esc_html_e( 'Customer Satisfaction', 'smart-leading-net' ); ?></span>
						</li>
						<li class="about-overview__feature">
							<span class="about-overview__feature-check" aria-hidden="true">
								<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M2.5 7.2L5.4 10.1L11.5 3.9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</span>
							<span><?php esc_html_e( 'Creativity', 'smart-leading-net' ); ?></span>
						</li>
						<li class="about-overview__feature">
							<span class="about-overview__feature-check" aria-hidden="true">
								<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M2.5 7.2L5.4 10.1L11.5 3.9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</span>
							<span><?php esc_html_e( 'Strategies', 'smart-leading-net' ); ?></span>
						</li>
						<li class="about-overview__feature">
							<span class="about-overview__feature-check" aria-hidden="true">
								<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M2.5 7.2L5.4 10.1L11.5 3.9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</span>
							<span><?php esc_html_e( 'Quality', 'smart-leading-net' ); ?></span>
						</li>
					</ul>
				</div>

				<div class="about-overview__media">
					<img
						class="about-overview__image"
						src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/good-job.webp' ); ?>"
						alt="<?php esc_attr_e( 'Team collaboration illustration', 'smart-leading-net' ); ?>"
						width="560"
						height="420"
						loading="lazy"
						decoding="async"
					/>
				</div>
			</div>
		</div>
	</section>

	<?php get_template_part( 'template-parts/about/leader-about' ); ?>

	<?php get_template_part( 'template-parts/sections/workflow' ); ?>

	<?php if ( get_the_content() ) : ?>
	<div class="about-page__content sls-container">
		<?php the_content(); ?>
	</div>
	<?php endif; ?>
</div>
