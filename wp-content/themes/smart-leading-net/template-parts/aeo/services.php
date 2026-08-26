<?php
/**
 * AEO page — services grid.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section  = sln_aeo_get_services_section();
$services = sln_aeo_get_services();
?>

<section class="sln-aeo-section" aria-labelledby="sln-aeo-services-heading">
	<div class="sls-container">
		<header class="sln-aeo-head sln-aeo-reveal">
			<p class="sln-aeo-eyebrow"><?php echo esc_html( $section['eyebrow'] ); ?></p>
			<h2 id="sln-aeo-services-heading" class="sln-aeo-title"><?php echo esc_html( $section['heading'] ); ?></h2>
			<p class="sln-aeo-lead"><?php echo esc_html( sln_aeo_plain_text( $section['description'] ) ); ?></p>
		</header>

		<div class="sln-aeo-svc-grid sln-aeo-reveal">
			<?php foreach ( $services as $service ) : ?>
				<article class="sln-aeo-svc">
					<div class="sln-aeo-svc__icon" aria-hidden="true">
						<?php echo sln_aeo_icon( $service['icon'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<h3><?php echo esc_html( $service['title'] ?? '' ); ?></h3>
					<p><?php echo esc_html( sln_aeo_plain_text( $service['description'] ?? '' ) ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
