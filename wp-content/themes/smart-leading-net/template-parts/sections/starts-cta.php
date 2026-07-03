<?php
/**
 * Starts CTA section — revenue growth proposal form.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$starts_cta_upload_dirs = array(
	WP_CONTENT_DIR . '/uploads/2026/05/',
	WP_CONTENT_DIR . '/uploads/2026/06/',
);

$starts_cta_upload_urls = array(
	trailingslashit( content_url( '/uploads/2026/05' ) ),
	trailingslashit( content_url( '/uploads/2026/06' ) ),
);

$starts_cta_bg       = '';
$starts_cta_bg_exts  = array( 'webp', 'png', 'jpg', 'jpeg' );
$starts_cta_basename = 'download_bg';

foreach ( $starts_cta_upload_dirs as $index => $upload_dir ) {
	foreach ( $starts_cta_bg_exts as $ext ) {
		$file_path = $upload_dir . $starts_cta_basename . '.' . $ext;

		if ( file_exists( $file_path ) ) {
			$starts_cta_bg = $starts_cta_upload_urls[ $index ] . $starts_cta_basename . '.' . $ext;
			break 2;
		}
	}
}

$starts_cta_assets_url = trailingslashit( content_url( '/uploads/2026/05' ) );
$starts_cta_underline  = $starts_cta_assets_url . rawurlencode( 'vector_43_stroke.svg' );
?>

<section class="starts-cta" aria-labelledby="starts-cta-heading">
	<div class="sls-container starts-cta__wrap">
		<div
			class="starts-cta__card"
			<?php if ( $starts_cta_bg ) : ?>
				style="--starts-cta-bg: url('<?php echo esc_url( $starts_cta_bg ); ?>');"
			<?php endif; ?>
		>
			<div class="starts-cta__overlay" aria-hidden="true"></div>

			<div class="starts-cta__inner">
				<div class="starts-cta__content">
					<h2 id="starts-cta-heading" class="starts-cta__heading">
						<?php esc_html_e( 'Your', 'smart-leading-net' ); ?>
						<span class="starts-cta__growth-wrap">
							<span class="starts-cta__growth-text"><?php esc_html_e( 'Revenue Growth', 'smart-leading-net' ); ?></span>
							<img
								class="starts-cta__growth-underline"
								src="<?php echo esc_url( $starts_cta_underline ); ?>"
								alt=""
								width="223"
								height="13"
								loading="lazy"
								decoding="async"
							>
						</span>
						<?php esc_html_e( 'Starts Here', 'smart-leading-net' ); ?>
					</h2>

					<p class="starts-cta__description">
						<?php esc_html_e( 'Tell us about your business, and we\'ll create a custom plan to grow your traffic, leads, and predictable revenue.', 'smart-leading-net' ); ?>
					</p>
				</div>

				<form class="starts-cta__form" action="#" method="post" novalidate>
					<label class="starts-cta__field">
						<span class="screen-reader-text"><?php esc_html_e( 'Your email', 'smart-leading-net' ); ?></span>
						<input
							class="starts-cta__input"
							type="email"
							name="starts_cta_email"
							placeholder="<?php esc_attr_e( 'Enter your email', 'smart-leading-net' ); ?>"
							autocomplete="email"
							required
						>
					</label>

					<?php
					sln_render_cta_button(
						array(
							'text'    => __( 'Get My Free Proposal', 'smart-leading-net' ),
							'type'    => 'button',
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
