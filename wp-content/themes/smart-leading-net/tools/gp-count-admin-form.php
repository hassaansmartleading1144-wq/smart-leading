<?php
/**
 * Count ALL form fields on Growth Page edit screen (admin bootstrap).
 */
define( 'WP_USE_THEMES', false );
define( 'WP_ADMIN', true );
require dirname( __DIR__, 4 ) . '/wp-load.php';

require_once ABSPATH . 'wp-admin/includes/admin.php';
require_once ABSPATH . 'wp-admin/includes/template.php';
require_once ABSPATH . 'wp-admin/includes/meta-boxes.php';
require_once ABSPATH . 'wp-admin/includes/post.php';

$post_id = 80;
$post    = get_post( $post_id );
$user    = get_user_by( 'id', 1 );
wp_set_current_user( $user->ID );

global $post_type, $post_type_object, $post, $title, $hook_suffix;
$post_type        = $post->post_type;
$post_type_object = get_post_type_object( $post_type );
$title            = $post->post_title;
$hook_suffix      = 'post.php';

set_current_screen( 'growth_page' );

// Register metaboxes like admin does.
do_action( 'add_meta_boxes', $post_type, $post );
do_action( "add_meta_boxes_{$post_type}", $post );

ob_start();
?>
<form name="post" action="post.php" method="post" id="post">
<input type="hidden" id="post_ID" name="post_ID" value="<?php echo esc_attr( $post_id ); ?>" />
<input type="hidden" id="post_type" name="post_type" value="<?php echo esc_attr( $post_type ); ?>" />
<div id="titlediv"><input type="text" name="post_title" id="title" value="<?php echo esc_attr( $post->post_title ); ?>" /></div>
<?php
wp_nonce_field( 'update-post_' . $post_id );
$postboxes = get_user_option( 'metaboxhidden_' . $post_type );
if ( ! is_array( $postboxes ) ) {
	$postboxes = array();
}
$boxes = $wp_meta_boxes[ $post_type ] ?? array();
foreach ( array( 'normal', 'advanced', 'side' ) as $context ) {
	if ( empty( $boxes[ $context ] ) ) {
		continue;
	}
	foreach ( array( 'high', 'sorted', 'core', 'default', 'low' ) as $priority ) {
		if ( empty( $boxes[ $context ][ $priority ] ) ) {
			continue;
		}
		foreach ( $boxes[ $context ][ $priority ] as $box ) {
			if ( in_array( $box['id'], $postboxes, true ) ) {
				echo "<!-- hidden metabox: {$box['id']} -->\n";
				continue;
			}
			echo '<div id="' . esc_attr( $box['id'] ) . '">';
			call_user_func( $box['callback'], $post, $box );
			echo '</div>';
		}
	}
}
submit_button( __( 'Update' ), 'primary', 'save' );
?>
</form>
<?php
$html = ob_get_clean();

preg_match_all( '/\bname=(["\'])([^"\']+)\1/', $html, $m );
$names = $m[2];

echo 'Total name= attributes: ' . count( $names ) . "\n";
echo 'Unique names: ' . count( array_unique( $names ) ) . "\n";
echo 'max_input_vars (web would be): 1000\n';
echo 'Over limit: ' . ( count( $names ) > 1000 ? 'YES' : 'no' ) . "\n";

// Position of critical fields.
$critical = array( 'post_ID', 'post_type', 'post_title', '_wpnonce', 'sln_growth_page_banner_nonce' );
foreach ( $critical as $c ) {
	$pos = array_search( $c, $names, true );
	echo "Index of {$c}: " . ( false === $pos ? 'MISSING' : $pos ) . "\n";
}
