<?php
/**
 * Template Name: Careers
 *
 * Careers landing page — standard WordPress page template.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Ensure careers helpers are loaded even if functions.php was deployed
 * without the careers-page-data.php require (root cause of the critical error).
 */
if ( ! function_exists( 'sln_render_careers_page_button' ) ) {
	require_once get_template_directory() . '/inc/careers-page-data.php';
}

/*
 * Fallback enqueue when sln_enqueue_careers_page_assets() is missing from functions.php.
 * Must run before get_header() so styles still print in wp_head.
 */
if ( ! function_exists( 'sln_enqueue_careers_page_assets' ) ) {
	$careers_version = defined( 'SLN_THEME_VERSION' ) ? SLN_THEME_VERSION : '1.7.3';

	wp_enqueue_style(
		'sln-careers-page',
		get_template_directory_uri() . '/assets/css/careers.css',
		array( 'sln-main', 'sln-buttons' ),
		$careers_version
	);

	wp_enqueue_script(
		'sln-careers-page',
		get_template_directory_uri() . '/assets/js/careers.js',
		array(),
		$careers_version,
		true
	);
}

get_header();

while ( have_posts() ) :
	the_post();
	?>

<main id="primary" class="site-main careers-page">
	<?php get_template_part( 'template-parts/careers/hero' ); ?>
	<?php get_template_part( 'template-parts/careers/why-join' ); ?>
	<?php get_template_part( 'template-parts/careers/culture' ); ?>
	<?php get_template_part( 'template-parts/careers/values' ); ?>
	<?php get_template_part( 'template-parts/careers/benefits' ); ?>
	<?php get_template_part( 'template-parts/careers/life' ); ?>
	<?php get_template_part( 'template-parts/careers/process' ); ?>
	<?php get_template_part( 'template-parts/careers/positions' ); ?>
	<?php get_template_part( 'template-parts/careers/faq' ); ?>
	<?php get_template_part( 'template-parts/careers/cta' ); ?>
	<?php get_template_part( 'template-parts/careers/apply-form' ); ?>
</main>

	<?php
endwhile;

get_footer();
