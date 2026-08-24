<?php
/**
 * AEO page — services grid.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$services = sln_aeo_get_services();
?>

<section class="sln-aeo-section" aria-labelledby="sln-aeo-services-heading">
	<div class="sls-container">
		<header class="sln-aeo-head sln-aeo-reveal">
			<p class="sln-aeo-eyebrow"><?php esc_html_e( 'What\'s included', 'smart-leading-net' ); ?></p>
			<h2 id="sln-aeo-services-heading" class="sln-aeo-title"><?php esc_html_e( 'AEO services', 'smart-leading-net' ); ?></h2>
			<p class="sln-aeo-lead"><?php esc_html_e( 'Everything needed to move your brand from ranked link to cited answer.', 'smart-leading-net' ); ?></p>
		</header>

		<div class="sln-aeo-svc-grid sln-aeo-reveal">
			<?php foreach ( $services as $service ) : ?>
				<article class="sln-aeo-svc">
					<div class="sln-aeo-svc__icon" aria-hidden="true">
						<?php echo sln_aeo_icon( $service['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<h3><?php echo esc_html( $service['title'] ); ?></h3>
					<p><?php echo esc_html( $service['description'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
