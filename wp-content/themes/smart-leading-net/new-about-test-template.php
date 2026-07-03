<?php
/**
 * Template Name: New About For Test
 *
 * Brand-aligned about-style page for testing — unique layout, not a duplicate of About Us.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	?>

<div class="new-about-test">
	<?php
	sln_render_page_banner(
		array(
			'title'            => __( 'New About For Test', 'smart-leading-net' ),
			'breadcrumb_label' => __( 'New About For Test', 'smart-leading-net' ),
			'heading_id'       => 'new-about-test-page-hero-heading',
		)
	);

	get_template_part( 'template-parts/about-test/intro' );
	get_template_part( 'template-parts/about-test/pillars' );
	get_template_part( 'template-parts/about-test/impact-band' );
	get_template_part( 'template-parts/sections/businesses', 'choose' );
	get_template_part( 'template-parts/sections/credibility' );
	get_template_part( 'template-parts/about-test/cta' );
	?>

	<?php if ( get_the_content() ) : ?>
	<div class="new-about-test__content sls-container section-padding">
		<?php the_content(); ?>
	</div>
	<?php endif; ?>
</div>

	<?php
endwhile;

get_footer();
