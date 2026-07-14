<?php
/**
 * Template Name: SEO Services
 *
 * Revenue-focused SEO landing page — standard WordPress page template.
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

<main id="primary" class="site-main seo-page">
	<?php get_template_part( 'template-parts/seo-services/hero' ); ?>
	<?php get_template_part( 'template-parts/seo/logos' ); ?>
	<?php get_template_part( 'template-parts/seo-services/reality' ); ?>
	<?php get_template_part( 'template-parts/seo-services/program' ); ?>
	<?php get_template_part( 'template-parts/seo-services/results' ); ?>
	<?php get_template_part( 'template-parts/seo-services/process' ); ?>
	<?php get_template_part( 'template-parts/seo-services/case', 'studies' ); ?>
	<?php get_template_part( 'template-parts/seo-services/pricing' ); ?>
	<?php get_template_part( 'template-parts/seo-services/testimonials' ); ?>
	<?php get_template_part( 'template-parts/seo-services/cta' ); ?>
	<?php get_template_part( 'template-parts/seo-services/faq' ); ?>
</main>

	<?php
endwhile;

get_footer();
