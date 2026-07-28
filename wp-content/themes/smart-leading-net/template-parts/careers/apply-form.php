<?php
/**
 * Careers — application form.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$position_options = sln_get_careers_form_positions();
?>

<section class="careers-page__section careers-page__apply" id="careers-apply" aria-labelledby="careers-apply-heading">
	<div class="sls-container careers-page__apply-inner">
		<header class="careers-page__section-head careers-page__section-head--center careers-page__reveal">
			<p class="careers-page__eyebrow"><?php esc_html_e( 'Application', 'smart-leading-net' ); ?></p>
			<h2 id="careers-apply-heading" class="careers-page__section-title">
				<?php esc_html_e( 'Start Your Application', 'smart-leading-net' ); ?>
			</h2>
			<p class="careers-page__section-desc">
				<?php esc_html_e( 'Share a few details and we will review your profile. Required fields are marked — LinkedIn and portfolio help us learn more about your work.', 'smart-leading-net' ); ?>
			</p>
		</header>

		<div class="careers-page__apply-card careers-page__reveal">
			<form class="careers-page__form" id="careers-page-form" method="post" enctype="multipart/form-data" novalidate>
				<div class="careers-page__form-grid">
					<div class="careers-page__field">
						<label class="careers-page__label" for="careers-name">
							<?php esc_html_e( 'Full Name', 'smart-leading-net' ); ?> <span aria-hidden="true">*</span>
						</label>
						<input class="careers-page__input" type="text" id="careers-name" name="careers_name" autocomplete="name" required>
					</div>

					<div class="careers-page__field">
						<label class="careers-page__label" for="careers-email">
							<?php esc_html_e( 'Email', 'smart-leading-net' ); ?> <span aria-hidden="true">*</span>
						</label>
						<input class="careers-page__input" type="email" id="careers-email" name="careers_email" autocomplete="email" required>
					</div>

					<div class="careers-page__field">
						<label class="careers-page__label" for="careers-phone">
							<?php esc_html_e( 'Phone', 'smart-leading-net' ); ?> <span aria-hidden="true">*</span>
						</label>
						<input class="careers-page__input" type="tel" id="careers-phone" name="careers_phone" autocomplete="tel" required>
					</div>

					<div class="careers-page__field">
						<label class="careers-page__label" for="careers-position">
							<?php esc_html_e( 'Position', 'smart-leading-net' ); ?> <span aria-hidden="true">*</span>
						</label>
						<select class="careers-page__input careers-page__select" id="careers-position" name="careers_position" required>
							<option value=""><?php esc_html_e( 'Select a position', 'smart-leading-net' ); ?></option>
							<?php foreach ( $position_options as $option ) : ?>
								<option value="<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $option ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="careers-page__field careers-page__field--full">
						<label class="careers-page__label" for="careers-resume">
							<?php esc_html_e( 'Resume Upload', 'smart-leading-net' ); ?> <span aria-hidden="true">*</span>
						</label>
						<input class="careers-page__input careers-page__file" type="file" id="careers-resume" name="careers_resume" accept=".pdf,.doc,.docx" required>
						<small class="careers-page__hint"><?php esc_html_e( 'PDF or DOC/DOCX, max 5MB.', 'smart-leading-net' ); ?></small>
					</div>

					<div class="careers-page__field">
						<label class="careers-page__label" for="careers-linkedin">
							<?php esc_html_e( 'LinkedIn', 'smart-leading-net' ); ?>
						</label>
						<input class="careers-page__input" type="url" id="careers-linkedin" name="careers_linkedin" placeholder="<?php esc_attr_e( 'https://linkedin.com/in/…', 'smart-leading-net' ); ?>" autocomplete="url">
					</div>

					<div class="careers-page__field">
						<label class="careers-page__label" for="careers-portfolio">
							<?php esc_html_e( 'Portfolio', 'smart-leading-net' ); ?>
						</label>
						<input class="careers-page__input" type="url" id="careers-portfolio" name="careers_portfolio" placeholder="<?php esc_attr_e( 'https://…', 'smart-leading-net' ); ?>" autocomplete="url">
					</div>

					<div class="careers-page__field careers-page__field--full">
						<label class="careers-page__label" for="careers-message">
							<?php esc_html_e( 'Message', 'smart-leading-net' ); ?>
						</label>
						<textarea class="careers-page__input careers-page__textarea" id="careers-message" name="careers_message" rows="4" placeholder="<?php esc_attr_e( 'Tell us briefly why you want to join Smart Leading…', 'smart-leading-net' ); ?>"></textarea>
					</div>
				</div>

				<?php
				sln_render_careers_page_button(
					array(
						'text'    => __( 'Submit Application', 'smart-leading-net' ),
						'type'    => 'submit',
						'variant' => 'secondary',
						'arrow'   => true,
						'class'   => 'careers-page__form-submit',
					)
				);
				?>

				<p class="careers-page__form-message" role="alert" aria-live="assertive" hidden></p>
			</form>

			<div class="careers-page__success-box" hidden>
				<h3 class="careers-page__success-title"><?php esc_html_e( 'Thank You!', 'smart-leading-net' ); ?></h3>
				<p class="careers-page__success-text"><?php esc_html_e( 'Your application has been received.', 'smart-leading-net' ); ?></p>
				<p class="careers-page__success-text"><?php esc_html_e( 'Our talent team will review your profile and contact you if there is a fit.', 'smart-leading-net' ); ?></p>
			</div>
		</div>
	</div>
</section>
