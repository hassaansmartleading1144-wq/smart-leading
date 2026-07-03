<?php
/**
 * Page template.
 *
 * @package Smart_Leading_Net
 */

get_header();
?>

<?php
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/global/content', 'page' );
endwhile;
?>

<?php
get_footer();
