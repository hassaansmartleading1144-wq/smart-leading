<?php
/**
 * Template Name: Thank You
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

<div class="contact-page thank-you-page">
	<?php
	sln_render_page_banner(
		array(
			'title'            => __( 'Thank You', 'smart-leading-net' ),
			'breadcrumb_label' => __( 'Thank You', 'smart-leading-net' ),
			'heading_id'       => 'thank-you-page-hero-heading',
		)
	);
	?>

	<section class="contact-page__intro thank-you-page__content" aria-labelledby="thank-you-heading">
		<div class="contact-page__intro-inner sls-container">
			<h2 id="thank-you-heading" class="contact-page__intro-title">
				<?php esc_html_e( 'Thank You!', 'smart-leading-net' ); ?>
			</h2>
			<p class="contact-page__intro-text">
				<?php esc_html_e( 'Your message has been received. A Smart Leading team member will be in touch with you shortly.', 'smart-leading-net' ); ?>
			</p>
			<?php
			sln_render_cta_button(
				array(
					'text'       => __( 'Back to Home', 'smart-leading-net' ),
					'url'        => home_url( '/' ),
					'variant'    => 'primary',
					'class'      => 'thank-you-page__cta',
					'show_arrow' => false,
				)
			);
			?>
		</div>
	</section>
</div>

	<?php
endwhile;

get_footer();
