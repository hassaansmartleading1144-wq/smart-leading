<?php
/**
 * Template Name: SEO Services
 *
 * Revenue-focused SEO landing page — standard WordPress page template.
 * Used by the main SEO page and its sub-pages (SEO Audit, Technical SEO, …).
 * Optional sections can be hidden per page from the "Section Visibility" box.
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
	<?php if ( sln_seo_services_section_visible( 'logos' ) ) : ?>
		<?php get_template_part( 'template-parts/seo/logos' ); ?>
	<?php endif; ?>
	<?php get_template_part( 'template-parts/seo-services/reality' ); ?>
	<?php get_template_part( 'template-parts/seo-services/program' ); ?>
	<?php get_template_part( 'template-parts/seo-services/results' ); ?>
	<?php get_template_part( 'template-parts/seo-services/process' ); ?>
	<?php if ( sln_seo_services_section_visible( 'case_studies' ) ) : ?>
		<?php get_template_part( 'template-parts/seo-services/case', 'studies' ); ?>
	<?php endif; ?>
	<?php if ( sln_seo_services_section_visible( 'tracking' ) ) : ?>
		<?php get_template_part( 'template-parts/seo-services/tracking' ); ?>
	<?php endif; ?>
	<?php if ( sln_seo_services_section_visible( 'pricing' ) ) : ?>
		<?php get_template_part( 'template-parts/seo-services/pricing' ); ?>
	<?php endif; ?>
	<?php if ( sln_seo_services_section_visible( 'testimonials' ) ) : ?>
		<?php get_template_part( 'template-parts/seo-services/testimonials' ); ?>
	<?php endif; ?>
	<?php if ( sln_seo_services_section_visible( 'related' ) ) : ?>
		<?php get_template_part( 'template-parts/seo-services/related' ); ?>
	<?php endif; ?>
	<?php get_template_part( 'template-parts/seo-services/cta' ); ?>
	<?php get_template_part( 'template-parts/seo-services/faq' ); ?>
</main>

	<?php
endwhile;

get_footer();
