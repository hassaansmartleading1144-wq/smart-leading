<?php
/**
 * Count actual rendered metabox field names for a Growth Page post.
 */
define( 'WP_USE_THEMES', false );
require dirname( __DIR__, 4 ) . '/wp-load.php';

require_once ABSPATH . 'wp-admin/includes/template.php';
require_once ABSPATH . 'wp-admin/includes/post.php';

$post = get_post( 80 );
if ( ! $post ) {
	echo "Post 80 missing\n";
	exit( 1 );
}

$renderers = array(
	'sln_growth_page_render_banner_meta_box',
	'sln_growth_page_render_section_order_meta_box',
	'sln_growth_page_render_services_meta_box',
	'sln_growth_page_render_client_story_meta_box',
	'sln_growth_page_render_how_work_meta_box',
	'sln_growth_page_render_growth_services_meta_box',
	'sln_growth_page_render_case_studies_meta_box',
	'sln_growth_page_render_why_choose_meta_box',
	'sln_growth_page_render_price_plan_meta_box',
	'sln_growth_page_render_testimonials_meta_box',
	'sln_growth_page_render_cta_banner_meta_box',
);

ob_start();
foreach ( $renderers as $cb ) {
	if ( is_callable( $cb ) ) {
		call_user_func( $cb, $post );
	}
}
$html = ob_get_clean();

preg_match_all( '/\bname=(["\'])([^"\']+)\1/', $html, $m );
$names = $m[2];

echo 'Post ID: ' . $post->ID . ' | ' . $post->post_title . "\n";
echo 'Metabox name= count: ' . count( $names ) . "\n";
echo 'Unique names: ' . count( array_unique( $names ) ) . "\n";
echo 'With WP core estimate (~90): ' . ( count( $names ) + 90 ) . "\n";
echo 'max_input_vars: ' . ini_get( 'max_input_vars' ) . "\n";

$dupes = array_count_values( $names );
$dupes = array_filter( $dupes, static fn( $c ) => $c > 1 );
if ( $dupes ) {
	echo "\nDuplicate name attributes (same POST key collision):\n";
	foreach ( $dupes as $name => $count ) {
		echo "  {$name} x{$count}\n";
	}
}
