<?php
/**
 * Single Growth Page template.
 *
 * @package Smart_Leading_Net
 */

get_header();

while ( have_posts() ) :
	the_post();
	sln_render_growth_page_sections();
	sln_render_lead_analytics_section();
endwhile;

get_footer();
