<?php
define( 'WP_USE_THEMES', false );
require dirname( __DIR__, 4 ) . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/post.php';

$post_id = 80;
$user    = get_user_by( 'id', 1 );
wp_set_current_user( $user->ID );

$_POST = array(
	'post_ID'                     => (string) $post_id,
	'post_type'                   => SLN_GROWTH_PAGE_POST_TYPE,
	'post_title'                  => 'Master Nonce Test ' . gmdate( 'H:i:s' ),
	'action'                      => 'editpost',
	'originalaction'              => 'editpost',
	'post_status'                 => 'publish',
	'comment_status'              => 'closed',
	'ping_status'                 => 'closed',
	'content'                     => '',
	'hidden_post_status'          => 'publish',
	'post_author'                 => '1',
	'_wpnonce'                    => wp_create_nonce( 'update-post_' . $post_id ),
	'sln_growth_page_master_nonce'=> wp_create_nonce( 'sln_growth_page_save_meta' ),
	'sln_gp_banner_small_heading' => 'MASTER_NONCE_BANNER',
);

$r = edit_post( $_POST );
echo is_wp_error( $r ) ? $r->get_error_message() : "OK\n";
echo 'Title: ' . get_post( $post_id )->post_title . "\n";
echo 'Banner: ' . get_post_meta( $post_id, '_sln_gp_banner_small_heading', true ) . "\n";
