<?php
/**
 * AEO page — final CTA.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cta           = sln_aeo_get_final_cta();
$primary_url   = sln_aeo_resolve_cta_url( $cta['primary_cta_url'] ?? '', sln_aeo_get_contact_url() );
$secondary_url = sln_aeo_resolve_cta_url( $cta['secondary_cta_url'] ?? '', sln_aeo_get_seo_services_url() );
?>

<section class="sln-aeo-section" aria-labelledby="sln-aeo-final-heading">
	<div class="sls-container">
		<div class="sln-aeo-final sln-aeo-reveal">
			<p class="sln-aeo-eyebrow sln-aeo-eyebrow--light"><?php echo esc_html( $cta['eyebrow'] ); ?></p>
			<h2 id="sln-aeo-final-heading" class="sln-aeo-title sln-aeo-title--light"><?php echo esc_html( $cta['heading'] ); ?></h2>
			<p class="sln-aeo-lead sln-aeo-lead--light"><?php echo esc_html( sln_aeo_plain_text( $cta['description'] ) ); ?></p>
			<div class="sln-aeo-final__ctas">
				<?php
				sln_render_aeo_page_button(
					array(
						'text'    => $cta['primary_cta_text'],
						'url'     => $primary_url,
						'variant' => 'secondary',
						'arrow'   => true,
						'class'   => 'sln-aeo-cta--final',
					)
				);
				sln_render_aeo_page_button(
					array(
						'text'    => $cta['secondary_cta_text'],
						'url'     => $secondary_url,
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
