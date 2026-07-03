<?php
/**
 * SEO page — final CTA with GHL lead form.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<section class="seo-page__section seo-page__final" id="seo-proposal" aria-labelledby="seo-proposal-heading">
	<div class="sls-container seo-page__final-inner">
		<div class="seo-page__reveal">
			<p class="seo-page__eyebrow seo-page__eyebrow--light"><?php esc_html_e( 'Get Started', 'smart-leading-net' ); ?></p>
			<h2 id="seo-proposal-heading" class="seo-page__section-title seo-page__section-title--light">
				<?php esc_html_e( 'Your Revenue Growth Starts With One Search', 'smart-leading-net' ); ?>
			</h2>
			<p class="seo-page__final-lead">
				<?php esc_html_e( 'Tell us about your business and we\'ll build a custom SEO proposal showing exactly how we\'ll grow your traffic, leads, and revenue.', 'smart-leading-net' ); ?>
			</p>
			<ul class="seo-page__final-pts">
				<li>
					<?php echo sln_seo_page_check_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php esc_html_e( 'Free SEO audit', 'smart-leading-net' ); ?>
				</li>
				<li>
					<?php echo sln_seo_page_check_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php esc_html_e( 'No credit card required', 'smart-leading-net' ); ?>
				</li>
				<li>
					<?php echo sln_seo_page_check_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php esc_html_e( '7-day free trial', 'smart-leading-net' ); ?>
				</li>
			</ul>
		</div>

		<div class="seo-page__lead-card seo-page__reveal">
			<h3 class="seo-page__lead-title"><?php esc_html_e( 'Get Your Free SEO Proposal', 'smart-leading-net' ); ?></h3>
			<p class="seo-page__lead-desc"><?php esc_html_e( 'We\'ll review your site and reply within one business day.', 'smart-leading-net' ); ?></p>

			<form
				class="seo-page__form"
				id="seo-page-form"
				method="post"
				novalidate
			>
				<div class="seo-page__field">
					<label class="seo-page__label" for="seo-name">
						<?php esc_html_e( 'Your name', 'smart-leading-net' ); ?>
					</label>
					<input
						class="seo-page__input"
						type="text"
						id="seo-name"
						name="seo_name"
						placeholder="<?php esc_attr_e( 'Jane Doe', 'smart-leading-net' ); ?>"
						autocomplete="name"
						required
					>
				</div>

				<div class="seo-page__field">
					<label class="seo-page__label" for="seo-email">
						<?php esc_html_e( 'Work email', 'smart-leading-net' ); ?>
					</label>
					<input
						class="seo-page__input"
						type="email"
						id="seo-email"
						name="seo_email"
						placeholder="<?php esc_attr_e( 'jane@company.com', 'smart-leading-net' ); ?>"
						autocomplete="email"
						required
					>
				</div>

				<div class="seo-page__field">
					<label class="seo-page__label" for="seo-website">
						<?php esc_html_e( 'Website URL', 'smart-leading-net' ); ?>
					</label>
					<input
						class="seo-page__input"
						type="url"
						id="seo-website"
						name="seo_website"
						placeholder="<?php esc_attr_e( 'https://yourbusiness.com', 'smart-leading-net' ); ?>"
						autocomplete="url"
					>
				</div>

				<?php
				sln_render_seo_page_button(
					array(
						'text'    => __( 'Get My Free Proposal', 'smart-leading-net' ),
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

			<p class="seo-page__lead-note">
				<?php esc_html_e( 'By submitting, you agree to be contacted about your proposal.', 'smart-leading-net' ); ?>
			</p>
		</div>
	</div>
</section>
