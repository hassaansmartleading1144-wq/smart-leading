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

$seo_contact = sln_get_seo_page_contact_details();
$seo_trust   = sln_get_seo_page_hero_trust_stats();
$seo_serp    = sln_get_seo_page_serp_data();

get_header();

while ( have_posts() ) :
	the_post();
	?>

<main id="primary" class="site-main seo-page">
	<?php get_template_part( 'template-parts/seo/hero' ); ?>
	<?php get_template_part( 'template-parts/seo/logos' ); ?>
	<?php get_template_part( 'template-parts/seo/pain-points' ); ?>
	<?php get_template_part( 'template-parts/seo/services' ); ?>
	<?php get_template_part( 'template-parts/seo/why-choose' ); ?>
	<?php get_template_part( 'template-parts/seo/process' ); ?>
	<?php get_template_part( 'template-parts/growth-pages/case', 'studies' ); ?>
	<?php get_template_part( 'template-parts/growth-pages/price', 'plan' ); ?>
	<?php get_template_part( 'template-parts/growth-pages/testimonials' ); ?>
	<?php get_template_part( 'template-parts/seo/cta-form' ); ?>
	<?php get_template_part( 'template-parts/seo/faq' ); ?>
</main>

	<?php
endwhile;

get_footer();
