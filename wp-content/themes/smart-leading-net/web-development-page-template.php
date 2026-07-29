<?php
/**
 * Template Name: Web Development Services
 *
 * Figma-aligned Web Development services page.
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

<main id="primary" class="site-main web-development-page">
	<?php
	get_template_part( 'template-parts/web-development/hero' );
	get_template_part( 'template-parts/web-development/stats' );
	get_template_part( 'template-parts/web-development/platforms' );
	get_template_part( 'template-parts/web-development/helpful-extras' );
	get_template_part( 'template-parts/web-development/why-it-matters' );
	get_template_part( 'template-parts/web-development/process' );
	get_template_part( 'template-parts/web-development/promises' );
	get_template_part( 'template-parts/web-development/services' );
	get_template_part( 'template-parts/web-development/comparison' );
	get_template_part( 'template-parts/web-development/benefit-summary' );
	get_template_part( 'template-parts/web-development/case-studies' );
	get_template_part( 'template-parts/web-development/pricing' );
	get_template_part( 'template-parts/web-development/care-plans' );
	get_template_part( 'template-parts/web-development/faq' );
	get_template_part( 'template-parts/web-development/final-form' );
	?>
</main>

	<?php
endwhile;

get_footer();
