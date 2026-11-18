<?php
/**
 * Template Name: PPC & Google Ads Management
 *
 * PPC & Google Ads Management landing page.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	?>

<main id="primary" class="site-main sln-ppc-page<?php echo esc_attr( function_exists( 'sln_ppc_main_class' ) ? sln_ppc_main_class() : '' ); ?>">
	<?php
	get_template_part( 'template-parts/ppc-google-ads/hero' );
	get_template_part( 'template-parts/ppc-google-ads/keyword-marquee' );
	if ( function_exists( 'sln_ppc_render_home_section' ) ) {
		sln_ppc_render_home_section( 'accomplishments' );
	}
	get_template_part( 'template-parts/ppc-google-ads/stats' );
	get_template_part( 'template-parts/ppc-google-ads/trust-bar' );
	get_template_part( 'template-parts/ppc-google-ads/reality' );
	$sln_is_hub = function_exists( 'sln_ppc_is_hub' ) && sln_ppc_new_design() && sln_ppc_is_hub();
	if ( $sln_is_hub ) {
		get_template_part( 'template-parts/ppc-google-ads/services-hub' );
	}
	if ( function_exists( 'sln_ppc_new_design' ) && sln_ppc_new_design() && sln_ppc_guide_steps() ) {
		get_template_part( 'template-parts/ppc-google-ads/guide' );
	} else {
		get_template_part( 'template-parts/ppc-google-ads/approach' );
	}
	if ( $sln_is_hub ) {
		get_template_part( 'template-parts/ppc-google-ads/guide', null, sln_ppc_explainer() );
		get_template_part( 'template-parts/ppc-google-ads/seo-vs-ppc' );
	} else {
		get_template_part( 'template-parts/ppc-google-ads/quick-truth' );
		if ( function_exists( 'sln_ppc_page_extras' ) && sln_ppc_new_design() ) {
			foreach ( sln_ppc_page_extras() as $sln_extra ) {
				get_template_part( 'template-parts/ppc-google-ads/' . $sln_extra[0], null, $sln_extra[1] );
			}
		}
	}
	get_template_part( 'template-parts/ppc-google-ads/' . ( function_exists( 'sln_ppc_new_design' ) && sln_ppc_new_design() ? 'why-flip' : 'why-sls' ) );
	get_template_part( 'template-parts/ppc-google-ads/services' );
	if ( function_exists( 'sln_ppc_render_home_section' ) ) {
		sln_ppc_render_home_section( 'expertise' );
	}
	get_template_part( 'template-parts/ppc-google-ads/process' );
	get_template_part( 'template-parts/ppc-google-ads/mid-cta' );
	if ( function_exists( 'sln_ppc_render_home_section' ) ) {
		sln_ppc_render_home_section( 'case-studies' );
	}
	get_template_part( 'template-parts/ppc-google-ads/proof' );
	get_template_part( 'template-parts/ppc-google-ads/industries' );
	get_template_part( 'template-parts/ppc-google-ads/roi-estimator' );
	sln_render_lead_analytics_section();
	get_template_part( 'template-parts/ppc-google-ads/pricing' );
	get_template_part( 'template-parts/ppc-google-ads/faq' );
	if ( function_exists( 'sln_ppc_render_audit_form' ) ) {
		sln_ppc_render_audit_form();
	}
	get_template_part( 'template-parts/ppc-google-ads/final-cta' );
	?>

	<?php if ( get_the_content() ) : ?>
		<section class="sln-ppc-section">
			<div class="sls-container">
				<div class="sln-ppc-editor-content">
					<?php the_content(); ?>
				</div>
			</div>
		</section>
	<?php endif; ?>
</main>

	<?php
endwhile;

get_footer();
