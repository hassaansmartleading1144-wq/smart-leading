<?php
/**
 * Web Development — Figma final contact section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$final = sln_get_wd_final();

if ( ! sln_wd_row_is_active( $final ) ) {
	return;
}

$benefits       = is_array( $final['benefits'] ?? null ) ? $final['benefits'] : array();
$countries      = is_array( $final['countries'] ?? null ) ? $final['countries'] : array();
$need_options   = is_array( $final['need_options'] ?? null ) ? $final['need_options'] : array();
$budget_options = is_array( $final['budget_options'] ?? null ) ? $final['budget_options'] : array();
$thank_you_url  = (string) ( $final['thank_you_url'] ?? '/thank-you/' );

if ( $thank_you_url && 0 !== strpos( $thank_you_url, 'http' ) ) {
	$thank_you_url = home_url( $thank_you_url );
}

$check_icon = function_exists( 'sln_seo_page_check_icon' )
	? sln_seo_page_check_icon()
	: '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>';
?>

<section class="wd-contact" id="wd-contact" aria-labelledby="wd-contact-heading">
	<div class="wd-wrap wd-contact__inner">
		<div class="wd-contact__copy wd-animate">
			<?php if ( ! empty( $final['small_heading'] ) ) : ?>
				<p class="wd-eyebrow"><?php echo esc_html( $final['small_heading'] ); ?></p>
			<?php endif; ?>
			<h2 id="wd-contact-heading" class="wd-title">
				<?php
				echo esc_html( $final['main_heading'] ?? '' );
				if ( ! empty( $final['highlighted_text'] ) ) {
					echo ' <span class="wd-hl">' . esc_html( $final['highlighted_text'] ) . '</span>';
				}
				?>
			</h2>
			<?php if ( sln_wd_plain_text( $final['description'] ?? '' ) ) : ?>
				<div class="wd-lead"><?php echo sln_wd_format_content( $final['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>

			<?php if ( ! empty( $benefits ) ) : ?>
				<ul class="wd-contact__checks">
					<?php foreach ( $benefits as $benefit ) : ?>
						<?php if ( empty( $benefit['text'] ) ) : ?>
							<?php continue; ?>
						<?php endif; ?>
						<li class="wd-contact__check">
							<span class="wd-contact__check-icon" aria-hidden="true"><?php echo $check_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<?php echo esc_html( $benefit['text'] ); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

		<div class="wd-contact__form wd-animate">
			<?php if ( ! empty( $final['form_heading'] ) ) : ?>
				<h3 class="wd-contact__form-title"><?php echo esc_html( $final['form_heading'] ); ?></h3>
			<?php endif; ?>

			<form class="wd-form" id="wd-page-form" method="post" novalidate data-thank-you-url="<?php echo esc_attr( $thank_you_url ); ?>">
				<div class="wd-form__row">
					<div class="wd-form__field">
						<label class="wd-form__label" for="wd-name"><?php echo esc_html( $final['name_label'] ?? __( 'Your name', 'smart-leading-net' ) ); ?></label>
						<input class="wd-form__input" type="text" id="wd-name" name="name" placeholder="<?php echo esc_attr( $final['name_placeholder'] ?? '' ); ?>" autocomplete="name" required>
					</div>
					<div class="wd-form__field">
						<label class="wd-form__label" for="wd-email"><?php echo esc_html( $final['email_label'] ?? __( 'Email', 'smart-leading-net' ) ); ?></label>
						<input class="wd-form__input" type="email" id="wd-email" name="email" placeholder="<?php echo esc_attr( $final['email_placeholder'] ?? '' ); ?>" autocomplete="email" required>
					</div>
				</div>

				<div class="wd-form__field">
					<label class="wd-form__label" for="wd-website"><?php echo esc_html( $final['website_label'] ?? __( 'Your website (if you have one)', 'smart-leading-net' ) ); ?></label>
					<input class="wd-form__input" type="url" id="wd-website" name="website" placeholder="<?php echo esc_attr( $final['website_placeholder'] ?? '' ); ?>" autocomplete="url">
				</div>

				<?php if ( ! empty( $countries ) ) : ?>
					<div class="wd-form__field">
						<label class="wd-form__label" for="wd-country"><?php echo esc_html( $final['country_label'] ?? __( 'Country', 'smart-leading-net' ) ); ?></label>
						<select class="wd-form__input" id="wd-country" name="country">
							<option value=""><?php echo esc_html( $final['country_placeholder'] ?? __( 'Select your country', 'smart-leading-net' ) ); ?></option>
							<?php foreach ( $countries as $country ) : ?>
								<option value="<?php echo esc_attr( $country ); ?>"><?php echo esc_html( $country ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $need_options ) ) : ?>
					<div class="wd-form__field">
						<label class="wd-form__label" for="wd-need"><?php echo esc_html( $final['need_label'] ?? __( 'What you need', 'smart-leading-net' ) ); ?></label>
						<select class="wd-form__input" id="wd-need" name="need">
							<option value=""><?php esc_html_e( 'Select an option', 'smart-leading-net' ); ?></option>
							<?php foreach ( $need_options as $option ) : ?>
								<option value="<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $option ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $budget_options ) ) : ?>
					<div class="wd-form__field">
						<label class="wd-form__label" for="wd-budget"><?php echo esc_html( $final['budget_label'] ?? __( 'Budget range', 'smart-leading-net' ) ); ?></label>
						<select class="wd-form__input" id="wd-budget" name="budget">
							<option value=""><?php esc_html_e( 'Select a range', 'smart-leading-net' ); ?></option>
							<?php foreach ( $budget_options as $option ) : ?>
								<option value="<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $option ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				<?php endif; ?>

				<div class="wd-form__field">
					<label class="wd-form__label" for="wd-message"><?php echo esc_html( $final['message_label'] ?? __( 'A few words about your project', 'smart-leading-net' ) ); ?></label>
					<textarea class="wd-form__input wd-form__textarea" id="wd-message" name="message" rows="4" placeholder="<?php echo esc_attr( $final['message_placeholder'] ?? '' ); ?>" required></textarea>
				</div>

				<?php
				sln_render_wd_page_button(
					array(
						'text'    => $final['submit_text'] ?? __( 'Send my free quote', 'smart-leading-net' ),
						'type'    => 'submit',
						'variant' => 'secondary',
						'arrow'   => true,
						'class'   => 'web-development-cta--submit',
					)
				);
				?>

				<p class="wd-form__message" role="alert" aria-live="assertive" hidden></p>
			</form>

			<div class="wd-form__success" hidden>
				<h4 class="wd-form__success-title"><?php esc_html_e( 'Thank You!', 'smart-leading-net' ); ?></h4>
				<p class="wd-form__success-text"><?php esc_html_e( 'Your request has been submitted successfully. A Smart Leading specialist will reply within one business day.', 'smart-leading-net' ); ?></p>
			</div>

			<?php if ( ! empty( $final['form_note'] ) ) : ?>
				<p class="wd-contact__form-note"><?php echo esc_html( $final['form_note'] ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>
