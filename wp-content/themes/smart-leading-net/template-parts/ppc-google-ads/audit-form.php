<?php
/**
 * Free PPC Audit — request form (id="contact" so every "#contact" button lands here).
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_helpers.php';

$section = array(
	'small_heading'    => __( 'Request Your Audit', 'smart-leading-net' ),
	'main_heading'     => __( 'Get Your', 'smart-leading-net' ),
	'highlighted_text' => __( 'Free PPC Audit.', 'smart-leading-net' ),
	'description'      => __( 'Tell us about your ads. We\'ll email simple steps for read-only access, then deliver your report, video and call within 2–3 business days.', 'smart-leading-net' ),
);
?>

<section id="contact" class="sln-ppc-section sln-ppc-section--dark sln-audform" aria-labelledby="sln-audform-heading">
	<span class="sln-ppc-final-glow sln-ppc-final-glow--azure" aria-hidden="true"></span>
	<div class="sls-container">
		<div class="sln-audform-grid">
			<div>
				<?php sln_ppc_part_render_heading( $section, 'sln-audform-heading', false, 'azure' ); ?>
				<ul class="sln-audform-points">
					<?php foreach ( array( __( 'Free, with no obligation', 'smart-leading-net' ), __( 'Google Ads, Local Service Ads and Meta', 'smart-leading-net' ), __( 'Read-only access, never your password', 'smart-leading-net' ), __( 'For accounts currently spending on ads', 'smart-leading-net' ) ) as $point ) : ?>
						<li><?php echo sln_ppc_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $point ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="sln-audform-card">
				<form class="sln-audform-form" data-sln-audit-form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" novalidate>
					<input type="hidden" name="action" value="<?php echo esc_attr( SLN_PPC_AUDIT_AJAX_ACTION ); ?>">
					<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( SLN_PPC_AUDIT_NONCE_ACTION ) ); ?>">
					<input type="text" name="company_fax" value="" tabindex="-1" autocomplete="off" class="sln-audform-hp" aria-hidden="true">

					<div class="sln-audform-row">
						<label><span><?php esc_html_e( 'Your name', 'smart-leading-net' ); ?> *</span><input type="text" name="name" autocomplete="name" required></label>
						<label><span><?php esc_html_e( 'Email', 'smart-leading-net' ); ?> *</span><input type="email" name="email" autocomplete="email" required></label>
					</div>
					<div class="sln-audform-row">
						<label><span><?php esc_html_e( 'Phone', 'smart-leading-net' ); ?></span><input type="tel" name="phone" autocomplete="tel"></label>
						<label><span><?php esc_html_e( 'Website', 'smart-leading-net' ); ?></span><input type="url" name="website" autocomplete="url" placeholder="yourbusiness.com"></label>
					</div>

					<fieldset class="sln-audform-plat">
						<legend><?php esc_html_e( 'Which accounts should we audit?', 'smart-leading-net' ); ?> *</legend>
						<?php foreach ( array( array( 'Google Ads', 'search' ), array( 'Local Service Ads', 'shield-check' ), array( 'Meta Ads', 'users' ) ) as $p ) : ?>
							<label class="sln-audform-chip"><input type="checkbox" name="platforms[]" value="<?php echo esc_attr( $p[0] ); ?>"><span><?php echo sln_ppc_icon( $p[1] ); // phpcs:ignore ?><?php echo esc_html( $p[0] ); ?></span></label>
						<?php endforeach; ?>
					</fieldset>

					<label class="sln-audform-full"><span><?php esc_html_e( 'Monthly ad spend', 'smart-leading-net' ); ?></span>
						<select name="spend">
							<option value=""><?php esc_html_e( 'Choose one (optional)', 'smart-leading-net' ); ?></option>
							<option>Under $1,000</option>
							<option>$1,000 – $5,000</option>
							<option>$5,000 – $20,000</option>
							<option>$20,000+</option>
						</select>
					</label>

					<label class="sln-audform-full"><span><?php esc_html_e( 'Anything we should know?', 'smart-leading-net' ); ?></span><textarea name="message" rows="3"></textarea></label>

					<button type="submit" class="sln-audform-submit"><?php esc_html_e( 'Request My Free Audit', 'smart-leading-net' ); ?> <span aria-hidden="true">→</span></button>
					<p class="sln-audform-msg" role="alert" aria-live="assertive" hidden></p>
				</form>

				<div class="sln-audform-success" hidden aria-live="polite">
					<span class="sln-audform-success-icon"><?php echo sln_ppc_icon( 'check' ); // phpcs:ignore ?></span>
					<h3 data-title></h3>
					<p data-message></p>
					<p data-message-2></p>
				</div>
			</div>
		</div>
	</div>
</section>
