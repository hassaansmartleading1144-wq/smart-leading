<?php
/**
 * Template Name: About Us
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
			'banner_title'        => __( 'About Us', 'smart-leading-net' ),
			'breadcrumb_label'    => __( 'About Us', 'smart-leading-net' ),
			'heading_id'          => 'about-page-hero-heading',
			'overview_heading_id' => 'about-overview-heading',
		)
	);
endwhile;

get_footer();
