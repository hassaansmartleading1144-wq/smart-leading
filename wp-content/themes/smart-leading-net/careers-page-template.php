<?php
/**
 * Template Name: Careers
 *
 * Careers landing page — standard WordPress page template.
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

<main id="primary" class="site-main careers-page">
	<?php get_template_part( 'template-parts/careers/hero' ); ?>
	<?php get_template_part( 'template-parts/careers/why-join' ); ?>
	<?php get_template_part( 'template-parts/careers/culture' ); ?>
	<?php get_template_part( 'template-parts/careers/values' ); ?>
	<?php get_template_part( 'template-parts/careers/benefits' ); ?>
	<?php get_template_part( 'template-parts/careers/life' ); ?>
	<?php get_template_part( 'template-parts/careers/process' ); ?>
	<?php get_template_part( 'template-parts/careers/positions' ); ?>
	<?php get_template_part( 'template-parts/careers/testimonials' ); ?>
	<?php get_template_part( 'template-parts/careers/faq' ); ?>
	<?php get_template_part( 'template-parts/careers/cta' ); ?>
	<?php get_template_part( 'template-parts/careers/apply-form' ); ?>

	<?php if ( get_the_content() ) : ?>
		<section class="careers-page__section careers-page__editor-content">
			<div class="sls-container">
				<?php the_content(); ?>
			</div>
		</section>
	<?php endif; ?>
</main>

	<?php
endwhile;

get_footer();
