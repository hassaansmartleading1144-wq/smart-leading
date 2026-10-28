<?php
/**
 * Single post template.
 *
 * @package Smart_Leading_Net
 */

get_header();

while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/blog/content', 'single' );
endwhile;

get_footer();
