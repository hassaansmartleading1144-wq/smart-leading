<?php
/**
 * Template Name: Digital Marketing
 *
 * Mobile-first digital marketing landing page from SLS blueprint.
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

<main id="primary" class="site-main digital-marketing-page">
	<div class="digital-marketing-page__column">
		<?php get_template_part( 'template-parts/digital-marketing/hero' ); ?>
		<?php get_template_part( 'template-parts/digital-marketing/pain', 'points' ); ?>
		<?php get_template_part( 'template-parts/digital-marketing/approach' ); ?>
		<?php get_template_part( 'template-parts/digital-marketing/truth' ); ?>
		<?php get_template_part( 'template-parts/digital-marketing/services' ); ?>
		<?php get_template_part( 'template-parts/digital-marketing/paid', 'channels' ); ?>
		<?php get_template_part( 'template-parts/digital-marketing/process' ); ?>
		<?php get_template_part( 'template-parts/digital-marketing/proof' ); ?>
		<?php get_template_part( 'template-parts/digital-marketing/pricing' ); ?>
		<?php get_template_part( 'template-parts/digital-marketing/faq' ); ?>
		<?php get_template_part( 'template-parts/digital-marketing/final', 'cta' ); ?>

		<?php if ( get_the_content() ) : ?>
		<div class="dm-page__editor-content dm-page__wrap">
			<?php the_content(); ?>
		</div>
		<?php endif; ?>
	</div>
</main>

	<?php
endwhile;

get_footer();
