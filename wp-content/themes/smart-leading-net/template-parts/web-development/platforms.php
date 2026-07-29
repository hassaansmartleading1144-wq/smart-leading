<?php
/**
 * Web Development — Figma platforms section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section   = sln_get_wd_platforms_section();
$platforms = sln_get_wd_platforms();

if ( ! sln_wd_row_is_active( $section ) || empty( $platforms ) ) {
	return;
}
?>

<section class="wd-platforms" aria-labelledby="wd-platforms-heading">
	<div class="wd-wrap">
		<div class="wd-platforms__layout">
			<div class="wd-platforms__copy wd-animate">
				<?php if ( ! empty( $section['small_heading'] ) ) : ?>
					<p class="wd-eyebrow"><?php echo esc_html( $section['small_heading'] ); ?></p>
				<?php endif; ?>
				<h2 id="wd-platforms-heading" class="wd-title">
					<?php
					echo esc_html( $section['main_heading'] ?? '' );
					if ( ! empty( $section['highlighted_text'] ) ) {
						echo ' <span class="wd-hl">' . esc_html( $section['highlighted_text'] ) . '</span>';
					}
					?>
				</h2>
				<?php if ( sln_wd_plain_text( $section['description'] ?? '' ) ) : ?>
					<div class="wd-lead"><?php echo sln_wd_format_content( $section['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<?php endif; ?>

				<?php if ( sln_wd_plain_text( $section['bottom_note'] ?? '' ) ) : ?>
					<?php
					$note  = sln_wd_plain_text( $section['bottom_note'] );
					$parts = preg_split( '/(?<=\.)\s+/', $note, 2 );
					$title = $parts[0] ?? $note;
					$text  = $parts[1] ?? '';
					?>
					<div class="wd-platforms__cta-box">
						<span class="wd-platforms__cta-icon" aria-hidden="true">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</span>
						<div>
							<p class="wd-platforms__cta-title"><?php echo esc_html( $title ); ?></p>
							<?php if ( $text ) : ?>
								<p class="wd-platforms__cta-text"><?php echo esc_html( $text ); ?></p>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<div class="wd-platforms__grid">
				<?php foreach ( $platforms as $platform ) : ?>
					<article class="wd-platforms__card wd-animate">
						<span class="wd-platforms__card-icon" aria-hidden="true">+</span>
						<div class="wd-platforms__card-body">
							<h3 class="wd-platforms__card-title"><?php echo esc_html( $platform['title'] ?? '' ); ?></h3>
							<?php if ( sln_wd_plain_text( $platform['description'] ?? '' ) ) : ?>
								<p class="wd-platforms__card-text"><?php echo esc_html( sln_wd_plain_text( $platform['description'] ) ); ?></p>
							<?php endif; ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
