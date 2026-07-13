<?php
/**
 * Front page template — custom homepage sections only.
 *
 * @package Smart_Leading_Net
 */

get_header();

get_template_part( 'template-parts/sections/hero', 'banner' );
get_template_part( 'template-parts/sections/new', 'section' );
get_template_part( 'template-parts/sections/accomplishments' );
get_template_part( 'template-parts/sections/businesses', 'choose' );
get_template_part( 'template-parts/sections/our', 'services' );
get_template_part( 'template-parts/sections/case', 'studies' );
get_template_part( 'template-parts/sections/expertise' );
get_template_part( 'template-parts/sections/growing' );
get_template_part( 'template-parts/sections/our', 'project' );
get_template_part( 'template-parts/sections/testimonials' );
get_template_part( 'template-parts/sections/credibility' );
get_template_part( 'template-parts/sections/workflow' );
get_template_part( 'template-parts/sections/starts', 'cta' );

get_footer();
