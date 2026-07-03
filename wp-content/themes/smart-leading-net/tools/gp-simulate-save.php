<?php
/**
 * Simulate Growth Page admin save and inspect results.
 */
define( 'WP_USE_THEMES', false );
require dirname( __DIR__, 4 ) . '/wp-load.php';

require_once ABSPATH . 'wp-admin/includes/post.php';
require_once ABSPATH . 'wp-admin/includes/admin.php';

$post_id = 80;
$post    = get_post( $post_id );

if ( ! $post || SLN_GROWTH_PAGE_POST_TYPE !== $post->post_type ) {
	echo "Post {$post_id} not a growth_page\n";
	exit( 1 );
}

$user = get_user_by( 'id', 1 );
if ( ! $user ) {
	echo "No admin user\n";
	exit( 1 );
}
wp_set_current_user( $user->ID );

$before_title = $post->post_title;
$before_banner = get_post_meta( $post_id, '_sln_gp_banner_small_heading', true );

echo "Before title: {$before_title}\n";
echo "Before banner small_heading: {$before_banner}\n";

// Minimal POST like admin Update with one metabox change.
$_POST = array(
	'post_ID'                    => (string) $post_id,
	'post_type'                  => SLN_GROWTH_PAGE_POST_TYPE,
	'post_title'                 => 'Simulated Admin Save ' . gmdate( 'H:i:s' ),
	'action'                     => 'editpost',
	'originalaction'             => 'editpost',
	'post_status'                => $post->post_status,
	'comment_status'             => $post->comment_status,
	'ping_status'                => $post->ping_status,
	'content'                    => '',
	'post_name'                  => $post->post_name,
	'wp-preview'                 => '',
	'hidden_post_status'         => $post->post_status,
	'post_author'                => (string) $post->post_author,
	'post_password'              => '',
	'mm'                         => gmdate( 'm', strtotime( $post->post_date ) ),
	'jj'                         => gmdate( 'd', strtotime( $post->post_date ) ),
	'aa'                         => gmdate( 'Y', strtotime( $post->post_date ) ),
	'hh'                         => gmdate( 'H', strtotime( $post->post_date ) ),
	'mn'                         => gmdate( 'i', strtotime( $post->post_date ) ),
	'ss'                         => gmdate( 's', strtotime( $post->post_date ) ),
	'_wpnonce'                   => wp_create_nonce( 'update-post_' . $post_id ),
	'_wp_http_referer'           => admin_url( 'post.php?post=' . $post_id . '&action=edit' ),
	'sln_gp_banner_small_heading'=> 'DEBUG_BANNER_' . gmdate( 'H:i:s' ),
);

// Add banner nonce.
$_POST['sln_growth_page_banner_nonce'] = wp_create_nonce( 'sln_growth_page_save_banner' );

// Trigger the same path as post.php editpost.
$result = edit_post( $_POST );

if ( is_wp_error( $result ) ) {
	echo 'edit_post ERROR: ' . $result->get_error_message() . "\n";
	exit( 1 );
}

$after = get_post( $post_id );
echo "After title: {$after->post_title}\n";
echo "After banner small_heading: " . get_post_meta( $post_id, '_sln_gp_banner_small_heading', true ) . "\n";
echo "edit_post returned post ID: {$result}\n";
