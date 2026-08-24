<?php
/**
 * Template Name: AEO Services
 *
 * Answer Engine Optimization (AEO) Services landing page.
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

<main id="primary" class="site-main sln-aeo-page">
	<?php
	get_template_part( 'template-parts/aeo/hero' );
	get_template_part( 'template-parts/aeo/evolution' );
	get_template_part( 'template-parts/aeo/why-matters' );
	get_template_part( 'template-parts/aeo/strategy' );
	get_template_part( 'template-parts/aeo/services' );
	get_template_part( 'template-parts/aeo/how-it-works' );
	get_template_part( 'template-parts/aeo/comparison' );
	get_template_part( 'template-parts/aeo/platforms' );
	get_template_part( 'template-parts/aeo/industries' );
	get_template_part( 'template-parts/aeo/results' );
	get_template_part( 'template-parts/aeo/faq' );
	get_template_part( 'template-parts/aeo/final-cta' );
	?>
</main>

	<?php
endwhile;

get_footer();
