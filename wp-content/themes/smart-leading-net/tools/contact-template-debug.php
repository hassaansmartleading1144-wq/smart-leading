<?php
/**
 * Contact template loading diagnostics — run via WP-CLI or browser (admin only).
 *
 * @package Smart_Leading_Net
 */

require dirname( __DIR__, 4 ) . '/wp-load.php';

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

header( 'Content-Type: text/plain; charset=utf-8' );

echo "=== Contact Template Debug ===\n\n";

echo 'Active theme: ' . get_stylesheet() . ' / ' . get_template() . "\n";
echo 'Theme dir: ' . get_template_directory() . "\n";
echo 'contact-template.php exists: ' . ( file_exists( get_template_directory() . '/contact-template.php' ) ? 'yes' : 'no' ) . "\n\n";

$templates = wp_get_theme()->get_page_templates();
echo "Registered page templates:\n";
foreach ( $templates as $file => $name ) {
	echo "  - {$name} => {$file}\n";
}
echo "\n";

$pages = get_posts(
	array(
		'post_type'      => 'page',
		'posts_per_page' => -1,
		'post_status'    => array( 'publish', 'draft', 'private', 'pending' ),
		's'              => 'contact',
	)
);

echo "Pages matching 'contact':\n";
if ( empty( $pages ) ) {
	echo "  (none)\n";
} else {
	foreach ( $pages as $p ) {
		$tpl = get_page_template_slug( $p->ID );
		echo "  ID={$p->ID} slug={$p->post_name} status={$p->post_status} template=" . ( $tpl ? $tpl : 'default' ) . ' url=' . get_permalink( $p->ID ) . "\n";
	}
}
echo "\n";

$by_slug = get_page_by_path( 'contact-us' );
echo 'get_page_by_path(contact-us): ';
if ( $by_slug instanceof WP_Post ) {
	echo "ID={$by_slug->ID} status={$by_slug->post_status} template=" . get_page_template_slug( $by_slug->ID ) . ' url=' . get_permalink( $by_slug->ID ) . "\n";
} else {
	echo "NOT FOUND\n";
}

echo "\n=== Simulate main query for /contact-us/ ===\n";
global $wp, $wp_query;
$wp->init();
$wp->parse_request( array( 'contact-us' ) );
$wp->query_posts();
$wp->register_globals();

echo 'is_404: ' . ( is_404() ? 'yes' : 'no' ) . "\n";
echo 'is_page: ' . ( is_page() ? 'yes' : 'no' ) . "\n";
echo 'have_posts: ' . ( have_posts() ? 'yes' : 'no' ) . "\n";
echo 'queried_object_id: ' . ( get_queried_object_id() ?: 'none' ) . "\n";

$template = get_page_template();
echo 'get_page_template(): ' . ( $template ? $template : '(empty/default)' ) . "\n";

$located = locate_template( array( 'contact-template.php' ) );
echo 'locate_template(contact-template.php): ' . ( $located ? $located : 'not found' ) . "\n";

// Full template_include simulation.
$template_hierarchy = apply_filters( 'template_include', get_query_template( 'page' ) ?: get_index_template() );
echo 'template_include result: ' . $template_hierarchy . "\n";

echo "\npermalink_structure: " . get_option( 'permalink_structure' ) . "\n";
echo "siteurl: " . get_option( 'siteurl' ) . "\n";
echo "home: " . get_option( 'home' ) . "\n";
