<?php
/**
 * Template Name: Founders Behind
 * Template Post Type: page
 *
 * Gutenberg-driven Founders Behind page.
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

<main id="primary" <?php post_class( 'site-main sln-founders' ); ?>>
	<?php the_content(); ?>
</main>

	<?php
endwhile;

get_footer();
