<?php
/**
 * Template Name: AI SEO Services
 * Template Post Type: page
 *
 * Gutenberg-driven AI SEO Services landing page.
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

<main id="primary" class="site-main ai-seo-page" <?php post_class(); ?>>
	<?php the_content(); ?>
</main>

	<?php
endwhile;

get_footer();
