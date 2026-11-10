<?php
/**
 * Template Name: Case Studies
 * @package Smart_Leading_Net
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
wp_enqueue_style( 'sln-case-library', get_template_directory_uri() . '/assets/css/case-library.css', array( 'sln-style' ), filemtime( __DIR__ . '/assets/css/case-library.css' ) );
wp_enqueue_script( 'sln-case-library', get_template_directory_uri() . '/assets/js/case-library.js', array(), filemtime( __DIR__ . '/assets/js/case-library.js' ), true );
get_header();
?>
<main id="primary" class="sls-cs">
<?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?>
</main>
<?php get_footer(); ?>
