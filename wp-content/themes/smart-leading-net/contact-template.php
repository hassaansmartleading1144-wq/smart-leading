<?php
/**
 * Template Name: Contact Us
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$contact_phone_display = '+1 (512) 764-7877';
$contact_phone_href    = 'tel:+15127647877';
$contact_email         = 'admin@smartleading.net';
$contact_locations     = array(
	__( 'Australia', 'smart-leading-net' ),
	__( 'United Kingdom', 'smart-leading-net' ),
	__( 'Norway', 'smart-leading-net' ),
	__( 'Finland', 'smart-leading-net' ),
);

$contact_card_bg_image = SLN_THEME_URI . '/assets/images/call-shap1.webp';
$contact_card_logo     = SLN_THEME_URI . '/assets/images/Smart Leading white logo.webp';

get_header();

while ( have_posts() ) :
	the_post();
	?>

<div class="contact-page">
	<?php
	sln_render_page_banner(
		array(
			'title'            => __( 'Contact Us', 'smart-leading-net' ),
			'breadcrumb_label' => __( 'Contact Us', 'smart-leading-net' ),
			'heading_id'       => 'contact-page-hero-heading',
		)
	);
	?>

	<section class="contact-page__intro" aria-labelledby="contact-page-intro-heading">
		<div class="contact-page__intro-inner sls-container">
			<h2 id="contact-page-intro-heading" class="contact-page__intro-title">
				<?php esc_html_e( 'We Are Happy To Hear From You', 'smart-leading-net' ); ?>
			</h2>
			<p class="contact-page__intro-text">
				<?php esc_html_e( 'Ready to grow your business? Connect with our revenue growth specialists and let\'s discuss how Smart Leading can help generate more leads, more customers, and more revenue.', 'smart-leading-net' ); ?>
			</p>
		</div>
	</section>

	<section class="contact-page__main" aria-labelledby="contact-page-form-heading">
		<div class="contact-page__main-inner sls-container">
			<div class="contact-page__grid">
				<aside
					class="contact-page__info-card"
					aria-labelledby="contact-page-info-heading"
					style="--contact-card-bg-image: url('<?php echo esc_url( $contact_card_bg_image ); ?>');"
				>
					<img
						class="contact-page__info-watermark"
						src="<?php echo esc_url( $contact_card_logo ); ?>"
						alt=""
						aria-hidden="true"
						loading="lazy"
						decoding="async"
					/>

					<div class="contact-page__info-card-inner">
					<p class="contact-page__info-label"><?php esc_html_e( 'Contact Info', 'smart-leading-net' ); ?></p>
					<h2 id="contact-page-info-heading" class="contact-page__info-title">
						<?php esc_html_e( 'Let\'s Connect With Us', 'smart-leading-net' ); ?>
					</h2>
					<p class="contact-page__info-description">
						<?php esc_html_e( 'Get in touch and let us know how we can help. Our team will respond as soon as possible.', 'smart-leading-net' ); ?>
					</p>

					<ul class="contact-page__info-list">
						<li class="contact-page__info-item">
							<span class="contact-page__info-icon" aria-hidden="true">
								<?php sln_inline_svg( 'call-us-icon-footer.svg', 'contact-page__info-svg' ); ?>
							</span>
							<div class="contact-page__info-content">
								<span class="contact-page__info-item-label"><?php esc_html_e( 'Phone Number', 'smart-leading-net' ); ?></span>
								<a class="contact-page__info-link" href="<?php echo esc_attr( $contact_phone_href ); ?>">
									<?php echo esc_html( $contact_phone_display ); ?>
								</a>
							</div>
						</li>

						<li class="contact-page__info-item">
							<span class="contact-page__info-icon" aria-hidden="true">
								<?php sln_inline_svg( 'address-map.svg', 'contact-page__info-svg' ); ?>
							</span>
							<div class="contact-page__info-content">
								<span class="contact-page__info-item-label"><?php esc_html_e( 'Location', 'smart-leading-net' ); ?></span>
								<address class="contact-page__address">
									<?php esc_html_e( 'Smart Leading Solutions LLC', 'smart-leading-net' ); ?><br>
									<?php esc_html_e( '5900 Balcones Drive', 'smart-leading-net' ); ?><br>
									<?php esc_html_e( 'Suite 58601', 'smart-leading-net' ); ?><br>
									<?php esc_html_e( 'Austin, TX 78731', 'smart-leading-net' ); ?><br>
									<?php esc_html_e( 'USA', 'smart-leading-net' ); ?>
								</address>
								<div class="contact-page__other-locations">
									<strong class="contact-page__other-locations-title"><?php esc_html_e( 'Other Locations', 'smart-leading-net' ); ?></strong>
									<div class="contact-page__location-pills">
										<?php foreach ( $contact_locations as $location ) : ?>
											<span class="contact-page__location-pill"><?php echo esc_html( $location ); ?></span>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						</li>

						<li class="contact-page__info-item">
							<span class="contact-page__info-icon" aria-hidden="true">
								<?php sln_inline_svg( 'envelope-icon-footer.svg', 'contact-page__info-svg' ); ?>
							</span>
							<div class="contact-page__info-content">
								<span class="contact-page__info-item-label"><?php esc_html_e( 'Email', 'smart-leading-net' ); ?></span>
								<a class="contact-page__info-link" href="<?php echo esc_url( 'mailto:' . $contact_email ); ?>">
									<?php echo esc_html( $contact_email ); ?>
								</a>
							</div>
						</li>
					</ul>
					</div>
				</aside>

				<div class="contact-page__form-card">
					<h2 id="contact-page-form-heading" class="contact-page__form-title">
						<?php esc_html_e( 'Let\'s Connect With Us', 'smart-leading-net' ); ?>
					</h2>

					<form
						class="contact-page__form"
						id="contact-page-form"
						action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
						method="post"
						novalidate
					>
						<div class="contact-page__field">
							<label class="contact-page__label" for="contact-name">
								<?php esc_html_e( 'Full Name', 'smart-leading-net' ); ?>
								<span class="contact-page__required" aria-hidden="true">*</span>
							</label>
							<input
								class="contact-page__input"
								type="text"
								id="contact-name"
								name="contact_name"
								placeholder="<?php esc_attr_e( 'Your Full Name', 'smart-leading-net' ); ?>"
								autocomplete="name"
								required
							>
						</div>

						<div class="contact-page__field">
							<label class="contact-page__label" for="contact-email">
								<?php esc_html_e( 'Email Address', 'smart-leading-net' ); ?>
								<span class="contact-page__required" aria-hidden="true">*</span>
							</label>
							<input
								class="contact-page__input"
								type="email"
								id="contact-email"
								name="contact_email"
								placeholder="<?php esc_attr_e( 'Your Email Address', 'smart-leading-net' ); ?>"
								autocomplete="email"
								required
							>
						</div>

						<div class="contact-page__field">
							<label class="contact-page__label" for="contact-phone">
								<?php esc_html_e( 'Phone Number', 'smart-leading-net' ); ?>
								<span class="contact-page__required" aria-hidden="true">*</span>
							</label>
							<input
								class="contact-page__input contact-page__input--phone"
								type="tel"
								id="contact-phone"
								name="contact_phone"
								placeholder="<?php esc_attr_e( 'Enter phone number', 'smart-leading-net' ); ?>"
								autocomplete="tel"
								required
							>
						</div>

						<div class="contact-page__field">
							<label class="contact-page__label" for="contact-website">
								<?php esc_html_e( 'Website', 'smart-leading-net' ); ?>
								<span class="contact-page__optional"><?php esc_html_e( '(optional)', 'smart-leading-net' ); ?></span>
							</label>
							<input
								class="contact-page__input"
								type="url"
								id="contact-website"
								name="contact_website"
								placeholder="<?php esc_attr_e( 'https://yourwebsite.com', 'smart-leading-net' ); ?>"
								autocomplete="url"
							>
						</div>

						<div class="contact-page__field">
							<label class="contact-page__label" for="contact-message">
								<?php esc_html_e( 'Message', 'smart-leading-net' ); ?>
								<span class="contact-page__required" aria-hidden="true">*</span>
							</label>
							<textarea
								class="contact-page__input contact-page__textarea"
								id="contact-message"
								name="contact_message"
								rows="4"
								placeholder="<?php esc_attr_e( 'Tell us about your project...', 'smart-leading-net' ); ?>"
								required
							></textarea>
						</div>

						<button type="submit" class="contact-page__submit">
							<span class="contact-page__submit-text"><?php esc_html_e( 'Submit', 'smart-leading-net' ); ?></span>
						</button>

						<p class="contact-page__form-message" role="alert" aria-live="assertive" hidden></p>
					</form>
				</div>
			</div>
		</div>
	</section>

	<section class="contact-page__added-section section-padding" aria-labelledby="contact-page-added-section-heading">
		<div class="sls-container">
			<h2 id="contact-page-added-section-heading" class="contact-page__added-section-title">
				<?php esc_html_e( 'Section added1', 'smart-leading-net' ); ?>
			</h2>
		</div>
	</section>
</div>

	<?php
endwhile;

get_footer();
