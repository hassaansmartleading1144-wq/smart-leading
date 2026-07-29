<?php
/**
 * Web Development — Figma what we do.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section  = sln_get_wd_services_section();
$services = sln_get_wd_services();

if ( ! sln_wd_row_is_active( $section ) || empty( $services ) ) {
	return;
}
?>

<section class="wd-services" aria-labelledby="wd-services-heading">
	<div class="wd-wrap">
		<header class="wd-head wd-animate">
			<?php if ( ! empty( $section['small_heading'] ) ) : ?>
				<p class="wd-eyebrow"><?php echo esc_html( $section['small_heading'] ); ?></p>
			<?php endif; ?>
			<h2 id="wd-services-heading" class="wd-title"><?php echo esc_html( $section['main_heading'] ?? '' ); ?></h2>
			<?php if ( sln_wd_plain_text( $section['description'] ?? '' ) ) : ?>
				<div class="wd-lead"><?php echo sln_wd_format_content( $section['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
		</header>

		<div class="wd-services__grid">
			<?php foreach ( $services as $service ) : ?>
				<article class="wd-services__card wd-animate">
					<div class="wd-services__icon" aria-hidden="true">
						<?php sln_wd_render_icon( absint( $service['icon_id'] ?? 0 ), 'generic' ); ?>
					</div>
					<h3 class="wd-services__title"><?php echo esc_html( $service['title'] ?? '' ); ?></h3>
					<?php if ( sln_wd_plain_text( $service['description'] ?? '' ) ) : ?>
						<p class="wd-services__text"><?php echo esc_html( sln_wd_plain_text( $service['description'] ) ); ?></p>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
