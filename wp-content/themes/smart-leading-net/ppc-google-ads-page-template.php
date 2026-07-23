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

<main id="primary" class="site-main sln-ppc-page">
	<?php
	get_template_part( 'template-parts/ppc-google-ads/hero' );
	get_template_part( 'template-parts/ppc-google-ads/keyword-marquee' );
	get_template_part( 'template-parts/ppc-google-ads/stats' );
	get_template_part( 'template-parts/ppc-google-ads/trust-bar' );
	get_template_part( 'template-parts/ppc-google-ads/reality' );
	get_template_part( 'template-parts/ppc-google-ads/approach' );
	get_template_part( 'template-parts/ppc-google-ads/quick-truth' );
	get_template_part( 'template-parts/ppc-google-ads/why-sls' );
	get_template_part( 'template-parts/ppc-google-ads/services' );
	get_template_part( 'template-parts/ppc-google-ads/process' );
	get_template_part( 'template-parts/ppc-google-ads/mid-cta' );
	get_template_part( 'template-parts/ppc-google-ads/proof' );
	get_template_part( 'template-parts/ppc-google-ads/industries' );
	get_template_part( 'template-parts/ppc-google-ads/roi-estimator' );
	get_template_part( 'template-parts/ppc-google-ads/pricing' );
	get_template_part( 'template-parts/ppc-google-ads/faq' );
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
