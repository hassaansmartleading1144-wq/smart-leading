<?php
/**
 * Test edit_post under various POST failure conditions.
 */
define( 'WP_USE_THEMES', false );
require dirname( __DIR__, 4 ) . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/post.php';

$post_id = 80;
$user    = get_user_by( 'id', 1 );
wp_set_current_user( $user->ID );

function gp_base_post( $post_id ) {
	$post = get_post( $post_id );
	return array(
		'post_ID'            => (string) $post_id,
		'post_type'          => SLN_GROWTH_PAGE_POST_TYPE,
		'post_title'         => 'Full POST Test ' . gmdate( 'H:i:s' ),
		'action'             => 'editpost',
		'originalaction'     => 'editpost',
		'post_status'        => $post->post_status,
		'comment_status'     => $post->comment_status,
		'ping_status'        => $post->ping_status,
		'content'            => '',
		'post_name'          => $post->post_name,
		'hidden_post_status' => $post->post_status,
		'post_author'        => (string) $post->post_author,
		'_wpnonce'           => wp_create_nonce( 'update-post_' . $post_id ),
	);
}

function gp_all_nonces( $post_id ) {
	return array(
		'sln_growth_page_banner_nonce'         => wp_create_nonce( 'sln_growth_page_save_banner' ),
		'sln_growth_page_section_order_nonce'  => wp_create_nonce( 'sln_growth_page_save_section_order' ),
		'sln_growth_page_services_nonce'       => wp_create_nonce( 'sln_growth_page_save_services' ),
		'sln_growth_page_client_story_nonce'   => wp_create_nonce( 'sln_growth_page_save_client_story' ),
		'sln_growth_page_how_work_nonce'       => wp_create_nonce( 'sln_growth_page_save_how_work' ),
		'sln_growth_page_growth_services_nonce'=> wp_create_nonce( 'sln_growth_page_save_growth_services' ),
		'sln_growth_page_case_studies_nonce'   => wp_create_nonce( 'sln_growth_page_save_case_studies' ),
		'sln_growth_page_why_choose_nonce'     => wp_create_nonce( 'sln_growth_page_save_why_choose' ),
		'sln_growth_page_price_plan_nonce'     => wp_create_nonce( 'sln_growth_page_save_price_plan' ),
		'sln_growth_page_testimonials_nonce'   => wp_create_nonce( 'sln_growth_page_save_testimonials' ),
		'sln_growth_page_cta_banner_nonce'     => wp_create_nonce( 'sln_growth_page_save_cta_banner' ),
	);
}

// Test 1: Missing main _wpnonce
$_POST = gp_base_post( $post_id );
unset( $_POST['_wpnonce'] );
$r = edit_post( $_POST );
echo "Test 1 missing _wpnonce: " . ( is_wp_error( $r ) ? $r->get_error_message() : "OK id={$r}" ) . "\n";
echo "  Title now: " . get_post( $post_id )->post_title . "\n\n";

// Test 2: All nonces but missing metabox field data
$_POST = array_merge( gp_base_post( $post_id ), gp_all_nonces( $post_id ) );
$_POST['post_title'] = 'All Nonces Test ' . gmdate( 'H:i:s' );
$_POST['sln_gp_banner_small_heading'] = 'ALL_NONCES_BANNER';
$r = edit_post( $_POST );
echo "Test 2 all nonces minimal fields: " . ( is_wp_error( $r ) ? $r->get_error_message() : "OK id={$r}" ) . "\n";
echo "  Title: " . get_post( $post_id )->post_title . "\n";
echo "  Banner: " . get_post_meta( $post_id, '_sln_gp_banner_small_heading', true ) . "\n\n";

// Test 3: Missing ALL metabox nonces (simulates truncation)
$_POST = gp_base_post( $post_id );
$_POST['post_title'] = 'No Meta Nonces ' . gmdate( 'H:i:s' );
$_POST['sln_gp_banner_small_heading'] = 'NO_META_NONCES_BANNER';
$r = edit_post( $_POST );
echo "Test 3 no metabox nonces: " . ( is_wp_error( $r ) ? $r->get_error_message() : "OK id={$r}" ) . "\n";
echo "  Title: " . get_post( $post_id )->post_title . "\n";
echo "  Banner: " . get_post_meta( $post_id, '_sln_gp_banner_small_heading', true ) . "\n";
