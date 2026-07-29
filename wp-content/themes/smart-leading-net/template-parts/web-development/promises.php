<?php
/**
 * Web Development — Figma promise bar.
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
?>

<section class="wd-promises" aria-labelledby="wd-promises-heading">
	<div class="wd-wrap">
		<?php if ( ! empty( $section['heading'] ) ) : ?>
			<h2 id="wd-promises-heading" class="wd-promises__heading wd-animate"><?php echo esc_html( $section['heading'] ); ?></h2>
		<?php else : ?>
			<h2 id="wd-promises-heading" class="screen-reader-text"><?php esc_html_e( 'Our promise', 'smart-leading-net' ); ?></h2>
		<?php endif; ?>

		<div class="wd-promises__bar wd-animate">
			<div class="wd-promises__grid">
				<?php foreach ( $promises as $promise ) : ?>
					<div class="wd-promises__item">
						<p class="wd-promises__main"><?php echo esc_html( $promise['main_text'] ?? '' ); ?></p>
						<?php if ( ! empty( $promise['supporting_text'] ) ) : ?>
							<p class="wd-promises__support"><?php echo esc_html( $promise['supporting_text'] ); ?></p>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
