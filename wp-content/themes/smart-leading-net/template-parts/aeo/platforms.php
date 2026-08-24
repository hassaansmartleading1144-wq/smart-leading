<?php
/**
 * AEO page — platforms.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$platforms = sln_aeo_get_platforms();
?>

<section class="sln-aeo-section sln-aeo-section--tint" aria-labelledby="sln-aeo-platforms-heading">
	<div class="sls-container">
		<header class="sln-aeo-head sln-aeo-head--center sln-aeo-reveal">
			<p class="sln-aeo-eyebrow"><?php esc_html_e( 'Where you\'ll show up', 'smart-leading-net' ); ?></p>
			<h2 id="sln-aeo-platforms-heading" class="sln-aeo-title"><?php esc_html_e( 'Built for every major answer engine', 'smart-leading-net' ); ?></h2>
		</header>

		<div class="sln-aeo-plats sln-aeo-reveal">
			<?php foreach ( $platforms as $platform ) : ?>
				<span class="sln-aeo-plat"><?php echo esc_html( $platform ); ?></span>
			<?php endforeach; ?>
		</div>
	</div>
</section>
