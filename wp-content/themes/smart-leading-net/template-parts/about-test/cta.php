<?php
/**
 * New About For Test — closing CTA aligned with site branding.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uploads_url   = trailingslashit( content_url( '/uploads/2026/05' ) );
$underline_src = $uploads_url . rawurlencode( 'vector_43_stroke.svg' );
?>

<section class="nat-cta section-padding" aria-labelledby="nat-cta-heading">
	<div class="nat-cta__container sls-container">
		<div class="nat-cta__card card-custom">
			<div class="nat-cta__content">
				<h2 id="nat-cta-heading" class="nat-cta__title">
					<?php esc_html_e( 'Ready to', 'smart-leading-net' ); ?>
					<span class="nat-cta__accent-wrap">
						<span class="nat-cta__accent"><?php esc_html_e( 'Grow Smarter', 'smart-leading-net' ); ?></span>
						<img class="nat-cta__underline" src="<?php echo esc_url( $underline_src ); ?>" alt="" width="223" height="13" loading="lazy" decoding="async">
					</span>
					<?php esc_html_e( 'With Us?', 'smart-leading-net' ); ?>
				</h2>
				<p class="nat-cta__text">
					<?php esc_html_e( 'Tell us about your business goals and we will build a custom growth plan around leads, revenue, and measurable results.', 'smart-leading-net' ); ?>
				</p>
			</div>

			<div class="nat-cta__actions">
				<?php
				sln_render_cta_button(
					array(
						'text'    => __( 'Get My Free Proposal', 'smart-leading-net' ),
						'url'     => sln_get_page_url_by_slug( 'contact-us' ),
						'variant' => 'primary',
						'class'   => 'nat-cta__button',
					)
				);
				?>
				<p class="nat-cta__contact">
					<?php esc_html_e( 'Or call us at', 'smart-leading-net' ); ?>
					<a href="tel:+15127647877">+1 (512) 764-7877</a>
				</p>
			</div>
		</div>
	</div>
</section>
