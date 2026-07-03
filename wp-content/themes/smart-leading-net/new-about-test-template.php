<?php
/**
 * Template Name: New About For Test
 *
 * Test duplicate of the About Us page layout — does not replace /about-us/.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	get_template_part(
		'template-parts/about/page',
		'layout',
		array(
			'banner_title'        => __( 'New About For Test', 'smart-leading-net' ),
			'breadcrumb_label'    => __( 'New About For Test', 'smart-leading-net' ),
			'heading_id'          => 'new-about-test-page-hero-heading',
			'overview_heading_id' => 'new-about-test-overview-heading',
		)
	);
endwhile;

get_footer();
