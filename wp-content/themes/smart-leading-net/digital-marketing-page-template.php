<?php
/**
 * Template Name: Digital Marketing Services
 *
 * Digital marketing services landing page — standard WordPress page template.
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

<main id="primary" class="site-main dm-page">
	<?php get_template_part( 'template-parts/digital-marketing/hero' ); ?>
	<?php get_template_part( 'template-parts/digital-marketing/reality' ); ?>
</main>

	<?php
endwhile;

get_footer();
