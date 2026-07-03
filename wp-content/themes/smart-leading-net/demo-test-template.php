<?php
/**
 * Template Name: Demo Test
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$demo_test_contact_url = home_url( '/contact-us/' );

$demo_test_uploads_url = trailingslashit( content_url( '/uploads/2026/06' ) );

$demo_test_features = array(
	array(
		'icon'  => 'seo.svg',
		'title' => __( 'SEO That Drives Revenue', 'smart-leading-net' ),
		'text'  => __( 'Rank higher, attract qualified traffic, and turn organic visibility into measurable pipeline growth.', 'smart-leading-net' ),
	),
	array(
		'icon'  => 'google-Ads.svg',
		'title' => __( 'PPC & Paid Media ROI', 'smart-leading-net' ),
		'text'  => __( 'Data-driven ad campaigns optimized for leads, conversions, and return on ad spend — not vanity metrics.', 'smart-leading-net' ),
	),
	array(
		'icon'  => 'Revenue-Growth.svg',
		'title' => __( 'Full-Funnel Growth', 'smart-leading-net' ),
		'text'  => __( 'From lead generation to retention, our tech-enabled marketing stack scales revenue across every stage.', 'smart-leading-net' ),
	),
);

get_header();

while ( have_posts() ) :
	the_post();
	?>

<div class="demo-test-page">
	<?php
	sln_render_page_banner(
		array(
			'title'            => __( 'Demo Test', 'smart-leading-net' ),
			'breadcrumb_label' => __( 'Demo Test', 'smart-leading-net' ),
			'heading_id'       => 'demo-test-page-hero-heading',
		)
	);
	?>

	<section class="demo-test-intro section-padding" aria-labelledby="demo-test-intro-heading">
		<div class="demo-test-intro__container sls-container">
			<p class="demo-test-intro__label"><?php esc_html_e( 'Theme Demo Page', 'smart-leading-net' ); ?></p>
			<h2 id="demo-test-intro-heading" class="demo-test-intro__title section-title">
				<?php esc_html_e( 'Smart Leading Growth Marketing', 'smart-leading-net' ); ?>
			</h2>
			<p class="demo-test-intro__description section-description">
				<?php esc_html_e( 'This demo page showcases the Smart Leading design system — brand colors, typography, spacing, and reusable components — built to help businesses generate more leads, more customers, and more revenue.', 'smart-leading-net' ); ?>
			</p>
		</div>
	</section>

	<section class="demo-test-highlights section-padding" aria-labelledby="demo-test-highlights-heading">
		<div class="demo-test-highlights__container sls-container">
			<h2 id="demo-test-highlights-heading" class="demo-test-highlights__title section-title">
				<?php esc_html_e( 'What We Deliver', 'smart-leading-net' ); ?>
			</h2>
			<p class="demo-test-highlights__description section-description">
				<?php esc_html_e( 'Tech-enabled marketing services designed for measurable ROI.', 'smart-leading-net' ); ?>
			</p>

			<ul class="demo-test-highlights__grid">
				<?php foreach ( $demo_test_features as $feature ) : ?>
					<?php
					$icon_path = WP_CONTENT_DIR . '/uploads/2026/06/' . $feature['icon'];
					$icon_url  = $demo_test_uploads_url . $feature['icon'];
					?>
				<li class="demo-test-highlights__card card-custom">
					<div class="demo-test-highlights__card-icon" aria-hidden="true">
						<?php if ( file_exists( $icon_path ) ) : ?>
							<img
								src="<?php echo esc_url( $icon_url ); ?>"
								alt=""
								width="48"
								height="48"
								loading="lazy"
								decoding="async"
							/>
						<?php else : ?>
							<?php echo sln_inline_svg( 'focused.svg', 'demo-test-highlights__card-icon-fallback' ); ?>
						<?php endif; ?>
					</div>
					<h3 class="demo-test-highlights__card-title"><?php echo esc_html( $feature['title'] ); ?></h3>
					<p class="demo-test-highlights__card-text"><?php echo esc_html( $feature['text'] ); ?></p>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>

	<section class="demo-test-cta section-padding" aria-labelledby="demo-test-cta-heading">
		<div class="demo-test-cta__container sls-container">
			<div class="demo-test-cta__inner card-custom">
				<h2 id="demo-test-cta-heading" class="demo-test-cta__title">
					<?php esc_html_e( 'Ready to Accelerate Your Revenue?', 'smart-leading-net' ); ?>
				</h2>
				<p class="demo-test-cta__text">
					<?php esc_html_e( 'Connect with our growth specialists and discover how Smart Leading can turn your marketing into a revenue engine.', 'smart-leading-net' ); ?>
				</p>
				<?php
				sln_render_cta_button(
					array(
						'text'    => __( 'Get Started Today', 'smart-leading-net' ),
						'url'     => $demo_test_contact_url,
						'variant' => 'primary',
						'class'   => 'demo-test-cta__button',
					)
				);
				?>
			</div>
		</div>
	</section>

	<?php if ( get_the_content() ) : ?>
	<div class="demo-test-page__content sls-container">
		<?php the_content(); ?>
	</div>
	<?php endif; ?>
</div>

	<?php
endwhile;

get_footer();
