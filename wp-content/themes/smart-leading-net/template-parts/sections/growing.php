<?php
/**
 * Growing section — revenue growth CTA with lead form.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$growing_uploads_url = trailingslashit( content_url( '/uploads/2026/05' ) );
$growing_bg          = $growing_uploads_url . 'growing_bg.webp';
?>

<section
	class="growing is-visible"
	aria-labelledby="growing-heading"
	style="--growing-bg: url('<?php echo esc_url( $growing_bg ); ?>');"
>
	<div class="sls-container growing__container">
		<div class="growing__badge" data-animate="fade-down">
			<span class="growing__badge-icon" aria-hidden="true">&#128640;</span>
			<span class="growing__badge-text"><?php esc_html_e( 'Start Growing Today', 'smart-leading-net' ); ?></span>
		</div>

		<h2 id="growing-heading" class="growing__heading contact-cta__title" data-animate="fade-up">
			<span class="growing__heading-line"><?php esc_html_e( 'Your Revenue', 'smart-leading-net' ); ?></span>
			<span class="growing__heading-line growing__heading-line--accent"><?php esc_html_e( 'Growth Starts Here', 'smart-leading-net' ); ?></span>
		</h2>

		<p class="growing__description" data-animate="fade-up">
			<?php esc_html_e( 'Tell us about your business and we\'ll create a custom growth plan to increase traffic, generate qualified leads, and drive measurable revenue.', 'smart-leading-net' ); ?>
		</p>

		<form class="growing__form" id="growing-lead-form" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" novalidate>
			<div class="growing__fields">
				<label class="growing__field" data-animate="fade-up">
					<span class="growing__field-icon" aria-hidden="true">
						<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M11 11C13.7614 11 16 8.76142 16 6C16 3.23858 13.7614 1 11 1C8.23858 1 6 3.23858 6 6C6 8.76142 8.23858 11 11 11Z" stroke="#2552A6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M3 21C3 17.134 6.13401 14 10 14H12C15.866 14 19 17.134 19 21" stroke="#2552A6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</span>
					<input
						class="growing__input"
						type="text"
						name="growing_name"
						placeholder="<?php esc_attr_e( 'Your Full Name', 'smart-leading-net' ); ?>"
						autocomplete="name"
						required
					>
				</label>

				<label class="growing__field" data-animate="fade-up">
					<span class="growing__field-icon" aria-hidden="true">
						<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M3 5.5L11 11.5L19 5.5" stroke="#2552A6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M3 5.5H19V16.5H3V5.5Z" stroke="#2552A6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</span>
					<input
						class="growing__input"
						type="email"
						name="growing_email"
						placeholder="<?php esc_attr_e( 'Your Email Address', 'smart-leading-net' ); ?>"
						autocomplete="email"
						required
					>
				</label>

				<label class="growing__field" data-animate="fade-up">
					<span class="growing__field-icon" aria-hidden="true">
						<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
							<circle cx="11" cy="11" r="9" stroke="#2552A6" stroke-width="1.5"/>
							<path d="M2 11H20" stroke="#2552A6" stroke-width="1.5" stroke-linecap="round"/>
							<path d="M11 2C13.5 5.5 14.5 8 14.5 11C14.5 14 13.5 16.5 11 20C8.5 16.5 7.5 14 7.5 11C7.5 8 8.5 5.5 11 2Z" stroke="#2552A6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</span>
					<input
						class="growing__input"
						type="url"
						name="growing_website"
						placeholder="<?php esc_attr_e( 'Your Website', 'smart-leading-net' ); ?>"
						autocomplete="url"
					>
				</label>
			</div>

			<?php
			sln_render_cta_button(
				array(
					'text'       => __( 'Get Your Free Proposal', 'smart-leading-net' ),
					'type'       => 'button',
					'variant'    => 'secondary',
					'class'      => 'growing__submit',
					'attributes' => array(
						'data-animate' => 'fade-up',
					),
				)
			);
			?>
		</form>

		<div class="growing__success-box" id="growing-success-box" hidden aria-live="polite">
			<h3 class="growing__success-title"><?php esc_html_e( 'Thank You!', 'smart-leading-net' ); ?></h3>
			<p class="growing__success-text">
				<?php esc_html_e( 'Your request has been submitted successfully.', 'smart-leading-net' ); ?>
			</p>
			<p class="growing__success-text">
				<?php esc_html_e( 'A Smart Leading team member will review your information and contact you shortly to discuss your business goals and growth opportunities.', 'smart-leading-net' ); ?>
			</p>
		</div>

		<p class="growing__form-message" role="alert" aria-live="assertive" hidden></p>

		<p class="growing__note growing__note--form" data-animate="fade-up">
			<span><?php esc_html_e( 'No credit card required', 'smart-leading-net' ); ?></span>
			<span><?php esc_html_e( 'Free 7-day trial', 'smart-leading-net' ); ?></span>
			<span><?php esc_html_e( 'Cancel anytime', 'smart-leading-net' ); ?></span>
		</p>
	</div>
</section>
