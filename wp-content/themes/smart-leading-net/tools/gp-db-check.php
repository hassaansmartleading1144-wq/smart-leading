<?php
define( 'WP_USE_THEMES', false );
require dirname( __DIR__, 4 ) . '/wp-load.php';

global $wpdb;

echo "=== growth_page posts ===\n";
$rows = $wpdb->get_results(
	"SELECT ID, post_title, post_name, post_status, post_type FROM {$wpdb->posts} WHERE post_type = 'growth_page' ORDER BY ID"
);
foreach ( $rows as $r ) {
	echo "{$r->ID} | {$r->post_status} | {$r->post_name} | {$r->post_title}\n";
}

echo "\n=== pages with 'growth' or 'revenue' in title ===\n";
$pages = $wpdb->get_results(
	"SELECT ID, post_title, post_name, post_status FROM {$wpdb->posts} WHERE post_type = 'page' AND (post_title LIKE '%growth%' OR post_title LIKE '%Growth%' OR post_title LIKE '%revenue%' OR post_title LIKE '%Revenue%') ORDER BY ID"
);
foreach ( $pages as $p ) {
	echo "{$p->ID} | {$p->post_status} | {$p->post_name} | {$p->post_title}\n";
}

echo "\n=== postmeta count for growth_page ID 80 ===\n";
$meta = $wpdb->get_results( $wpdb->prepare(
	"SELECT meta_key, LENGTH(meta_value) AS len FROM {$wpdb->postmeta} WHERE post_id = %d ORDER BY meta_key",
	80
) );
foreach ( $meta as $m ) {
	echo "{$m->meta_key} ({$m->len} bytes)\n";
}
