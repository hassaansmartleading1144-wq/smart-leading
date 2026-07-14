<?php
/**
 * SEO Services — CTA form section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cta = sln_get_seo_services_cta_form();
?>

<section class="seo-page__section seo-page__final" id="seo-proposal" aria-labelledby="seo-proposal-heading">
	<div class="sls-container seo-page__final-inner">
		<div class="seo-page__reveal">
			<?php if ( ! empty( $cta['small_heading'] ) ) : ?>
				<p class="seo-page__eyebrow seo-page__eyebrow--light"><?php echo esc_html( $cta['small_heading'] ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $cta['main_heading'] ) ) : ?>
				<h2 id="seo-proposal-heading" class="seo-page__section-title seo-page__section-title--light"><?php echo esc_html( $cta['main_heading'] ); ?></h2>
			<?php endif; ?>
			<?php if ( sln_seo_services_plain_text( $cta['description'] ) ) : ?>
				<div class="seo-page__final-lead"><?php echo sln_seo_services_format_content( $cta['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
			<ul class="seo-page__final-pts">
				<li><?php echo sln_seo_page_check_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php esc_html_e( 'Free SEO audit', 'smart-leading-net' ); ?></li>
				<li><?php echo sln_seo_page_check_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php esc_html_e( 'No credit card required', 'smart-leading-net' ); ?></li>
				<li><?php echo sln_seo_page_check_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php esc_html_e( '7-day free trial', 'smart-leading-net' ); ?></li>
			</ul>
		</div>

		<div class="seo-page__lead-card seo-page__reveal">
			<?php if ( ! empty( $cta['form_heading'] ) ) : ?>
				<h3 class="seo-page__lead-title"><?php echo esc_html( $cta['form_heading'] ); ?></h3>
			<?php endif; ?>
			<p class="seo-page__lead-desc"><?php esc_html_e( 'We\'ll review your site and reply within one business day.', 'smart-leading-net' ); ?></p>

			<form class="seo-page__form" id="seo-page-form" method="post" novalidate data-thank-you-url="<?php echo esc_attr( $cta['thank_you_page_url'] ); ?>">
				<div class="seo-page__field">
					<label class="seo-page__label" for="seo-name"><?php esc_html_e( 'Your name', 'smart-leading-net' ); ?></label>
					<input class="seo-page__input" type="text" id="seo-name" name="seo_name" placeholder="<?php echo esc_attr( $cta['name_placeholder'] ); ?>" autocomplete="name" required>
				</div>
				<div class="seo-page__field">
					<label class="seo-page__label" for="seo-email"><?php esc_html_e( 'Work email', 'smart-leading-net' ); ?></label>
					<input class="seo-page__input" type="email" id="seo-email" name="seo_email" placeholder="<?php echo esc_attr( $cta['email_placeholder'] ); ?>" autocomplete="email" required>
				</div>
				<div class="seo-page__field">
					<label class="seo-page__label" for="seo-website"><?php esc_html_e( 'Website URL', 'smart-leading-net' ); ?></label>
					<input class="seo-page__input" type="url" id="seo-website" name="seo_website" placeholder="<?php echo esc_attr( $cta['website_placeholder'] ); ?>" autocomplete="url">
				</div>
				<?php
				sln_render_seo_page_button(
					array(
						'text'    => $cta['button_text'],
						'type'    => 'submit',
						'variant' => 'secondary',
						'arrow'   => true,
						'class'   => 'seo-page__form-submit',
					)
				);
				?>
				<p class="seo-page__form-message" role="alert" aria-live="assertive" hidden></p>
			</form>

			<div class="seo-page__success-box" hidden>
				<h4 class="seo-page__success-title"><?php esc_html_e( 'Thank You!', 'smart-leading-net' ); ?></h4>
				<p class="seo-page__success-text"><?php esc_html_e( 'Your request has been submitted successfully.', 'smart-leading-net' ); ?></p>
				<p class="seo-page__success-text"><?php esc_html_e( 'A Smart Leading team member will review your information and contact you shortly.', 'smart-leading-net' ); ?></p>
			</div>

			<p class="seo-page__lead-note"><?php esc_html_e( 'By submitting, you agree to be contacted about your proposal.', 'smart-leading-net' ); ?></p>
		</div>
	</div>
</section>
