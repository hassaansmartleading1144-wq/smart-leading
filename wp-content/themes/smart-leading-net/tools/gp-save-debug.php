<?php
/**
 * Temporary Growth Page save diagnostics — delete after debugging.
 *
 * @package Smart_Leading_Net
 */

define( 'WP_USE_THEMES', false );
require dirname( __DIR__, 4 ) . '/wp-load.php';

if ( ! defined( 'SLN_GROWTH_PAGE_POST_TYPE' ) ) {
	echo "SLN_GROWTH_PAGE_POST_TYPE not defined\n";
	exit( 1 );
}

global $wp_filter;

echo "=== save_post_growth_page hooks ===\n";
$hook = 'save_post_' . SLN_GROWTH_PAGE_POST_TYPE;
if ( isset( $wp_filter[ $hook ] ) ) {
	foreach ( $wp_filter[ $hook ]->callbacks as $prio => $cbs ) {
		foreach ( $cbs as $cb ) {
			$fn = $cb['function'];
			if ( is_array( $fn ) ) {
				$name = ( is_object( $fn[0] ) ? get_class( $fn[0] ) : $fn[0] ) . '::' . $fn[1];
			} else {
				$name = $fn;
			}
			echo "  prio {$prio}: {$name}\n";
		}
	}
} else {
	echo "  NONE REGISTERED\n";
}

echo "\n=== growth_page posts ===\n";
$posts = get_posts(
	array(
		'post_type'      => SLN_GROWTH_PAGE_POST_TYPE,
		'posts_per_page' => 5,
		'post_status'    => 'any',
	)
);
foreach ( $posts as $p ) {
	echo "  ID {$p->ID} | {$p->post_status} | {$p->post_title}\n";
}

echo "\n=== PHP limits ===\n";
echo 'max_input_vars=' . ini_get( 'max_input_vars' ) . "\n";
echo 'post_max_size=' . ini_get( 'post_max_size' ) . "\n";

if ( $posts ) {
	$pid = $posts[0]->ID;
	echo "\n=== Test wp_update_post on ID {$pid} ===\n";
	$test_title = 'GP Debug ' . gmdate( 'Y-m-d H:i:s' );
	$result     = wp_update_post(
		array(
			'ID'         => $pid,
			'post_title' => $test_title,
		),
		true
	);
	if ( is_wp_error( $result ) ) {
		echo 'ERROR: ' . $result->get_error_message() . "\n";
	} else {
		$after = get_post( $pid );
		echo "Updated to: {$after->post_title}\n";
	}
}
