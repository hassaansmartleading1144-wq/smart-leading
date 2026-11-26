<?php
/**
 * Front page template — homepage sections (copy approved 25 Nov 2026).
 *
 * Section templates live in template-parts/home/. The previous homepage
 * sections in template-parts/sections/ are kept for other pages
 * (About Us uses workflow, Portfolio uses credibility).
 *
 * @package Smart_Leading_Net
 */

get_header();

get_template_part( 'template-parts/home/hero' );
get_template_part( 'template-parts/home/partners' );
get_template_part( 'template-parts/home/why' );
get_template_part( 'template-parts/home/services' );
get_template_part( 'template-parts/home/offer' );
get_template_part( 'template-parts/home/lead-system' );
get_template_part( 'template-parts/home/clients' );
get_template_part( 'template-parts/home/final-cta' );

get_footer();
