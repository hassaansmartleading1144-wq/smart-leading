<?php
/**
 * One-off: ensure Digital Marketing Services page exists.
 *
 * Usage: php tools/ensure-dm-page.php
 *
 * @package Smart_Leading_Net
 */

define( 'WP_USE_THEMES', false );

// tools/ → theme → themes → wp-content → ABSPATH
$wp_load = dirname( __DIR__, 4 ) . DIRECTORY_SEPARATOR . 'wp-load.php';

if ( ! file_exists( $wp_load ) ) {
	fwrite( STDERR, "wp-load.php not found at {$wp_load}\n" );
	exit( 1 );
}

require $wp_load;

if ( ! function_exists( 'sln_ensure_digital_marketing_services_page' ) ) {
	fwrite( STDERR, "sln_ensure_digital_marketing_services_page() missing\n" );
	exit( 1 );
}

$page_id = sln_ensure_digital_marketing_services_page();

echo 'PAGE_ID=' . $page_id . PHP_EOL;
echo 'URL=' . get_permalink( $page_id ) . PHP_EOL;
echo 'TPL=' . get_page_template_slug( $page_id ) . PHP_EOL;
