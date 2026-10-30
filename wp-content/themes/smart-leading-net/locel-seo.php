<?php
/**
 * Template Name: Locale SEO
 * Template Post Type: page
 *
 * Gutenberg-driven Locale SEO landing page.
 * Page content is fully editable via the Block Editor (the_content).
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

<main id="primary" <?php post_class( 'site-main sls-locel-seo' ); ?>>
	<?php the_content(); ?>
</main>

	<?php
endwhile;

get_footer();
