<?php
/**
 * Reusable inner page hero banner.
 *
 * @package Smart_Leading_Net
 *
 * @var array $args {
 *     @type string $title            Page heading.
 *     @type string $breadcrumb_label Breadcrumb current item label.
 *     @type string $heading_id       Unique heading ID for aria-labelledby.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = wp_parse_args(
	$args ?? array(),
	array(
		'title'            => '',
		'breadcrumb_label' => '',
		'heading_id'       => 'page-banner-heading',
	)
);

if ( '' === trim( $args['title'] ) ) {
	return;
}

if ( '' === trim( $args['breadcrumb_label'] ) ) {
	$args['breadcrumb_label'] = $args['title'];
}

$heading_id = sanitize_html_class( $args['heading_id'] );
if ( '' === $heading_id ) {
	$heading_id = 'page-banner-heading';
}
?>

<section class="page-banner" aria-labelledby="<?php echo esc_attr( $heading_id ); ?>">
	<div class="page-banner__inner sls-container">
		<nav class="page-banner__breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'smart-leading-net' ); ?>">
			<ol class="page-banner__breadcrumb-list">
				<li class="page-banner__breadcrumb-item">
					<a class="page-banner__breadcrumb-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php esc_html_e( 'Home', 'smart-leading-net' ); ?>
					</a>
				</li>
				<li class="page-banner__breadcrumb-item" aria-current="page">
					<span class="page-banner__breadcrumb-sep" aria-hidden="true">&raquo;</span>
					<?php echo esc_html( $args['breadcrumb_label'] ); ?>
				</li>
			</ol>
		</nav>

		<h1 id="<?php echo esc_attr( $heading_id ); ?>" class="page-banner__title">
			<?php echo esc_html( $args['title'] ); ?>
		</h1>
	</div>
</section>
