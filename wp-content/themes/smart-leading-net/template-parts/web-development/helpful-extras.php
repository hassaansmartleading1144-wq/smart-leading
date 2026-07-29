<?php
/**
 * Web Development — Figma dark-blue helpful extras panel.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = sln_get_wd_extras_section();
$extras  = sln_get_wd_extras();

if ( ! sln_wd_row_is_active( $section ) || empty( $extras ) ) {
	return;
}
?>

<section class="wd-extras" aria-labelledby="wd-extras-heading">
	<div class="wd-wrap">
		<div class="wd-extras__panel wd-animate">
			<?php if ( ! empty( $section['heading'] ) ) : ?>
				<h2 id="wd-extras-heading" class="wd-title wd-title--light"><?php echo esc_html( $section['heading'] ); ?></h2>
			<?php endif; ?>
			<?php if ( sln_wd_plain_text( $section['description'] ?? '' ) ) : ?>
				<div class="wd-extras__intro"><?php echo sln_wd_format_content( $section['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>

			<ul class="wd-extras__grid">
				<?php foreach ( $extras as $extra ) : ?>
					<li class="wd-extras__item">
						<span class="wd-extras__bullet" aria-hidden="true">+</span>
						<div>
							<?php if ( ! empty( $extra['title'] ) ) : ?>
								<h3 class="wd-extras__item-title"><?php echo esc_html( $extra['title'] ); ?></h3>
							<?php endif; ?>
							<?php if ( sln_wd_plain_text( $extra['description'] ?? '' ) ) : ?>
								<p class="wd-extras__item-text"><?php echo esc_html( sln_wd_plain_text( $extra['description'] ) ); ?></p>
							<?php endif; ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
