<?php
/**
 * Careers — final CTA.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<section class="careers-page__section careers-page__final-cta" id="careers-cta" aria-labelledby="careers-cta-heading">
	<div class="sls-container careers-page__final-cta-inner careers-page__reveal">
		<p class="careers-page__eyebrow careers-page__eyebrow--light"><?php esc_html_e( 'Ready To Join', 'smart-leading-net' ); ?></p>
		<h2 id="careers-cta-heading" class="careers-page__section-title careers-page__section-title--light">
			<?php esc_html_e( 'Ready To Build Your Career?', 'smart-leading-net' ); ?>
		</h2>
		<p class="careers-page__final-cta-lead">
			<?php esc_html_e( 'If you care about craft, ownership, and measurable impact — we would love to meet you. Explore open roles or start your application today.', 'smart-leading-net' ); ?>
		</p>
		<div class="careers-page__final-cta-actions">
			<?php
			sln_render_careers_page_button(
				array(
					'text'    => __( 'Apply Now', 'smart-leading-net' ),
					'url'     => '#careers-apply',
					'variant' => 'secondary',
					'arrow'   => true,
				)
			);
			sln_render_careers_page_button(
				array(
					'text'    => __( 'Contact HR', 'smart-leading-net' ),
					'url'     => 'mailto:hr@smartleading.net',
					'variant' => 'white',
				)
			);
			?>
		</div>
	</div>
</section>
