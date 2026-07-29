<?php
/**
 * Web Development — Our Promise bar.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section  = sln_get_wd_promises_section();
$promises = sln_get_wd_promises();

if ( ! sln_wd_row_is_active( $section ) || empty( $promises ) ) {
	return;
}

$heading = ! empty( $section['heading'] ) ? $section['heading'] : __( 'Our promise', 'smart-leading-net' );
?>

<section class="wd-promises" aria-labelledby="wd-promises-heading">
	<div class="wd-wrap">
		<div class="wd-promises__bar wd-animate">
			<div class="wd-promises__title">
				<span class="wd-promises__title-line" aria-hidden="true"></span>
				<span class="wd-promises__title-icon" aria-hidden="true">
					<?php echo sln_wd_fallback_icon( 'shield-check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>
				<h2 id="wd-promises-heading" class="wd-promises__heading"><?php echo esc_html( $heading ); ?></h2>
				<span class="wd-promises__title-line" aria-hidden="true"></span>
			</div>

			<div class="wd-promises__grid">
				<?php foreach ( $promises as $promise ) : ?>
					<?php
					$icon_key = ! empty( $promise['icon_key'] ) ? (string) $promise['icon_key'] : 'calendar';
					?>
					<div class="wd-promises__item">
						<span class="wd-promises__icon" aria-hidden="true">
							<?php echo sln_wd_fallback_icon( $icon_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</span>

						<div class="wd-promises__copy">
							<?php if ( ! empty( $promise['main_text'] ) ) : ?>
								<p class="wd-promises__main"><?php echo esc_html( $promise['main_text'] ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $promise['supporting_text'] ) ) : ?>
								<p class="wd-promises__support"><?php echo esc_html( $promise['supporting_text'] ); ?></p>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
