<?php
/**
 * AEO page — final CTA.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$contact_url = sln_aeo_get_contact_url();
$seo_url     = sln_aeo_get_seo_services_url();
?>

<section class="sln-aeo-section" aria-labelledby="sln-aeo-final-heading">
	<div class="sls-container">
		<div class="sln-aeo-final sln-aeo-reveal">
			<p class="sln-aeo-eyebrow sln-aeo-eyebrow--light"><?php esc_html_e( 'Ready when you are', 'smart-leading-net' ); ?></p>
			<h2 id="sln-aeo-final-heading" class="sln-aeo-title sln-aeo-title--light"><?php esc_html_e( 'Get Your Brand Ready for AI Search', 'smart-leading-net' ); ?></h2>
			<p class="sln-aeo-lead sln-aeo-lead--light"><?php esc_html_e( 'Answer engines are already deciding which brands get quoted and which get skipped. The sooner your content is structured for that, the sooner you start showing up in it.', 'smart-leading-net' ); ?></p>
			<div class="sln-aeo-final__ctas">
				<?php
				sln_render_aeo_page_button(
					array(
						'text'    => __( 'Get Started', 'smart-leading-net' ),
						'url'     => $contact_url,
						'variant' => 'secondary',
						'arrow'   => true,
						'class'   => 'sln-aeo-cta--final',
					)
				);
				sln_render_aeo_page_button(
					array(
						'text'    => __( 'Explore All SEO Services', 'smart-leading-net' ),
						'url'     => $seo_url,
						'variant' => 'white',
						'arrow'   => false,
						'class'   => 'sln-aeo-cta--final',
					)
				);
				?>
			</div>
		</div>
	</div>
</section>
