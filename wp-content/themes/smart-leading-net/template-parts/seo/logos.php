<?php
/**
 * SEO page — client logos strip.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$client_names = sln_get_seo_page_client_names();
?>

<div class="seo-page__logos">
	<div class="sls-container seo-page__logos-inner">
		<span class="seo-page__logos-label"><?php esc_html_e( 'Trusted by 200+ growing brands', 'smart-leading-net' ); ?></span>
		<?php foreach ( $client_names as $name ) : ?>
			<span class="seo-page__logo-pill"><?php echo esc_html( $name ); ?></span>
		<?php endforeach; ?>
	</div>
</div>
