<?php
/**
 * CTA Banner — Growth Page revenue growth proposal form.
 *
 * Reuses the Home Page Starts CTA markup and CSS classes.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$data = sln_get_growth_page_cta_banner();

if ( empty( $data['active'] ) ) {
	return;
}

$starts_cta_bg = ! empty( $data['background_image_url'] ) ? $data['background_image_url'] : sln_get_starts_cta_background_url();
$button_url    = trim( (string) $data['button_url'] );
$form_action   = ( '' !== $button_url && '#' !== $button_url ) ? $button_url : '#';
$form_method   = ( '' !== $button_url && '#' !== $button_url ) ? 'get' : 'post';
?>

<section class="starts-cta" aria-labelledby="growth-starts-cta-heading">
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
					<?php if ( ! empty( $data['main_heading'] ) ) : ?>
						<h2 id="growth-starts-cta-heading" class="starts-cta__heading">
							<?php sln_growth_page_render_cta_banner_heading( $data['main_heading'] ); ?>
						</h2>
					<?php endif; ?>

					<?php if ( ! empty( $data['description'] ) ) : ?>
						<p class="starts-cta__description"><?php echo esc_html( $data['description'] ); ?></p>
					<?php endif; ?>
				</div>

				<form class="starts-cta__form" action="<?php echo esc_url( $form_action ); ?>" method="<?php echo esc_attr( $form_method ); ?>" novalidate>
					<label class="starts-cta__field">
						<span class="screen-reader-text"><?php esc_html_e( 'Your website', 'smart-leading-net' ); ?></span>
						<input
							class="starts-cta__input"
							type="url"
							name="starts_cta_website"
							placeholder="<?php echo esc_attr( $data['input_placeholder'] ); ?>"
							autocomplete="url"
							required
						>
					</label>

					<?php
					sln_render_cta_button(
						array(
							'text'    => $data['button_text'],
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
