<?php
/**
 * Template Name: Previous Homepage
 *
 * The homepage as it was before the 25 Nov 2026 rebuild, kept as a draft
 * page for reference. Same sections as the old front-page.php
 * (template-parts/sections/). Its section assets load through
 * sln_is_home_sections_page() in functions.php.
 *
 * @package Smart_Leading_Net
 */

get_header();

get_template_part( 'template-parts/sections/hero', 'banner' );
get_template_part( 'template-parts/sections/accomplishments' );
get_template_part( 'template-parts/sections/why-choose', 'us' );
get_template_part( 'template-parts/sections/our', 'services' );
get_template_part( 'template-parts/sections/case', 'studies' );
get_template_part( 'template-parts/sections/expertise' );
get_template_part( 'template-parts/sections/growing' );
get_template_part( 'template-parts/sections/our', 'project' );
get_template_part( 'template-parts/sections/testimonials' );
get_template_part( 'template-parts/sections/credibility' );
get_template_part( 'template-parts/sections/workflow' );
get_template_part( 'template-parts/sections/team' );
get_template_part( 'template-parts/sections/starts', 'cta' );

get_footer();
