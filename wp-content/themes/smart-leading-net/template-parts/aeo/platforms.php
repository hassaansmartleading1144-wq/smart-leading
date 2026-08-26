<?php
/**
 * AEO page — platforms.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section   = sln_aeo_get_platforms_section();
$platforms = sln_aeo_get_platforms();
?>

<section class="sln-aeo-section sln-aeo-section--tint" aria-labelledby="sln-aeo-platforms-heading">
	<div class="sls-container">
		<header class="sln-aeo-head sln-aeo-head--center sln-aeo-reveal">
			<p class="sln-aeo-eyebrow"><?php echo esc_html( $section['eyebrow'] ); ?></p>
			<h2 id="sln-aeo-platforms-heading" class="sln-aeo-title"><?php echo esc_html( $section['heading'] ); ?></h2>
		</header>

		<div class="sln-aeo-plats sln-aeo-reveal">
			<?php foreach ( $platforms as $platform ) : ?>
				<span class="sln-aeo-plat"><?php echo esc_html( $platform ); ?></span>
			<?php endforeach; ?>
		</div>
	</div>
</section>
