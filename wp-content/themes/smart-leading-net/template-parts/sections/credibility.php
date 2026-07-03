<?php
/**
 * Credibility section — platforms trust and client logos.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$credibility_uploads_url  = trailingslashit( content_url( 'uploads/2026/05' ) );
$credibility_illustration = $credibility_uploads_url . 'design_center.webp';
$credibility_logos        = sln_get_credibility_logos();
$credibility_marquee_rows = sln_credibility_build_marquee_rows( $credibility_logos );
$credibility_row_one      = $credibility_marquee_rows['row_one'];
$credibility_row_two      = $credibility_marquee_rows['row_two'];
$credibility_has_marquee  = ! empty( $credibility_row_one ) || ! empty( $credibility_row_two );
?>

<section class="credibility" aria-labelledby="credibility-heading">
	<div class="sls-container credibility__container">
		<header class="credibility__header">
			<p class="credibility__label"><?php esc_html_e( 'CREDIBILITY', 'smart-leading-net' ); ?></p>

			<h2 id="credibility-heading" class="credibility__heading credibility__title">
				<span class="credibility__heading-line"><?php esc_html_e( 'Trusted By Business', 'smart-leading-net' ); ?></span>
				<span class="credibility__heading-line">
					<?php
					echo wp_kses(
						sprintf(
							/* translators: %s: highlighted phrase */
							__( 'Certified By %s', 'smart-leading-net' ),
							'<span class="credibility__heading-accent">' . esc_html__( 'Leading Platforms', 'smart-leading-net' ) . '</span>'
						),
						array(
							'span' => array(
								'class' => true,
							),
						)
					);
					?>
				</span>
			</h2>

			<p class="credibility__description">
				<?php esc_html_e( 'We\'re recognised by leading platforms and trusted by clients for delivering growth-driven solutions and measurable marketing success.', 'smart-leading-net' ); ?>
			</p>
		</header>

		<div class="credibility__illustration-wrap">
			<img
				class="credibility__illustration"
				src="<?php echo esc_url( $credibility_illustration ); ?>"
				alt="<?php esc_attr_e( 'Business growth journey from your business to revenue growth', 'smart-leading-net' ); ?>"
				width="1200"
				height="520"
				loading="lazy"
				decoding="async"
				decoding="async"
			/>
		</div>
	</div>

	<?php if ( $credibility_has_marquee ) : ?>
		<div class="credibility-marquee" aria-label="<?php esc_attr_e( 'Client logos', 'smart-leading-net' ); ?>">
			<?php if ( ! empty( $credibility_row_one ) ) : ?>
				<div class="credibility-marquee__row credibility-marquee__row--rtl">
					<div class="credibility-marquee__track">
						<div class="credibility-marquee__set">
							<?php sln_credibility_render_marquee_logos( $credibility_row_one, 'eager' ); ?>
						</div>
						<div class="credibility-marquee__set" aria-hidden="true">
							<?php sln_credibility_render_marquee_logos( $credibility_row_one, 'eager' ); ?>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $credibility_row_two ) ) : ?>
				<div class="credibility-marquee__row credibility-marquee__row--ltr">
					<div class="credibility-marquee__track">
						<div class="credibility-marquee__set">
							<?php sln_credibility_render_marquee_logos( $credibility_row_two, 'eager' ); ?>
						</div>
						<div class="credibility-marquee__set" aria-hidden="true">
							<?php sln_credibility_render_marquee_logos( $credibility_row_two, 'eager' ); ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</section>
