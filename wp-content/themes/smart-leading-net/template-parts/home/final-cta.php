<?php
/**
 * Homepage final CTA — new copy on the existing Starts CTA card design.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$final_bg = '';
foreach ( array( '2026/05', '2026/06' ) as $dir ) {
	foreach ( array( 'webp', 'png', 'jpg', 'jpeg' ) as $ext ) {
		if ( file_exists( WP_CONTENT_DIR . '/uploads/' . $dir . '/download_bg.' . $ext ) ) {
			$final_bg = content_url( '/uploads/' . $dir . '/download_bg.' . $ext );
			break 2;
		}
	}
}

$final_underline = trailingslashit( content_url( '/uploads/2026/05' ) ) . rawurlencode( 'vector_43_stroke.svg' );
?>

<section class="starts-cta home-final" aria-labelledby="home-final-heading">
	<div class="sls-container starts-cta__wrap">
		<div
			class="starts-cta__card"
			<?php if ( $final_bg ) : ?>
				style="--starts-cta-bg: url('<?php echo esc_url( $final_bg ); ?>');"
			<?php endif; ?>
		>
			<div class="starts-cta__overlay" aria-hidden="true"></div>

			<div class="starts-cta__inner">
				<div class="starts-cta__content">
					<p class="home-eyebrow home-eyebrow--light"><?php esc_html_e( 'Let’s talk', 'smart-leading-net' ); ?></p>
					<h2 id="home-final-heading" class="starts-cta__heading home-final__heading">
						<?php esc_html_e( 'Tell us what is', 'smart-leading-net' ); ?>
						<span class="starts-cta__growth-wrap">
							<span class="starts-cta__growth-text"><?php esc_html_e( 'holding your business back.', 'smart-leading-net' ); ?></span>
							<img class="starts-cta__growth-underline" src="<?php echo esc_url( $final_underline ); ?>" alt="" width="223" height="13" loading="lazy" decoding="async">
						</span>
					</h2>

					<p class="starts-cta__description"><?php esc_html_e( 'More customers? Poor lead quality? A website that does not sell? We will look at your business and discuss what needs to change.', 'smart-leading-net' ); ?></p>
				</div>

				<form class="starts-cta__form" action="<?php echo esc_url( sln_home_contact_url() ); ?>" method="get">
					<label class="starts-cta__field">
						<span class="screen-reader-text"><?php esc_html_e( 'Your website', 'smart-leading-net' ); ?></span>
						<input class="starts-cta__input" type="url" name="website" placeholder="<?php esc_attr_e( 'Enter your website', 'smart-leading-net' ); ?>" autocomplete="url">
					</label>

					<?php
					sln_render_cta_button(
						array(
							'text'    => __( 'Get my free proposal', 'smart-leading-net' ),
							'type'    => 'submit',
							'variant' => 'secondary',
							'class'   => 'starts-cta__submit',
						)
					);
					?>
				</form>
			</div>
		</div>
	</div>
</section>
