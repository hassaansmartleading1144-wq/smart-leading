<?php
/**
 * CLI helper: ensure Web Development Services page exists.
 *
 * Usage: php tools/ensure-wd-page.php
 *
 * @package Smart_Leading_Net
 */

$root = dirname( __DIR__, 4 ); // wp-content/themes/smart-leading-net → site root guess
$candidates = array(
	dirname( __DIR__, 4 ) . '/wp-load.php',
	dirname( __DIR__, 5 ) . '/wp-load.php',
	'C:/laragon/www/new-smart-leading/wp-load.php',
);

$loaded = false;
foreach ( $candidates as $path ) {
	if ( is_readable( $path ) ) {
		require $path;
		$loaded = true;
		break;
	}
}

if ( ! $loaded ) {
	fwrite( STDERR, "Could not find wp-load.php\n" );
	exit( 1 );
}

if ( ! function_exists( 'sln_ensure_web_development_services_page' ) ) {
	fwrite( STDERR, "sln_ensure_web_development_services_page() missing\n" );
	exit( 1 );
}

$page_id = sln_ensure_web_development_services_page();
update_option( 'sln_wd_page_ensured', (string) $page_id, false );

echo 'page_id=' . $page_id . PHP_EOL;
echo 'template=' . get_page_template_slug( $page_id ) . PHP_EOL;
echo 'url=' . get_permalink( $page_id ) . PHP_EOL;
