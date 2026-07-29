<?php
/**
 * Web Development — Figma process section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = sln_get_wd_process_section();
$stages  = sln_get_wd_process_stages();

if ( ! sln_wd_row_is_active( $section ) || empty( $stages ) ) {
	return;
}
?>

<section class="wd-process" id="wd-process" aria-labelledby="wd-process-heading">
	<div class="wd-wrap">
		<header class="wd-head wd-animate">
			<?php if ( ! empty( $section['small_heading'] ) ) : ?>
				<p class="wd-eyebrow wd-eyebrow--orange"><?php echo esc_html( $section['small_heading'] ); ?></p>
			<?php endif; ?>
			<h2 id="wd-process-heading" class="wd-title">
				<?php
				echo esc_html( $section['main_heading'] ?? '' );
				if ( ! empty( $section['highlighted_text'] ) ) {
					echo ' <span class="wd-hl wd-hl--orange">' . esc_html( $section['highlighted_text'] ) . '</span>';
				}
				?>
			</h2>
			<?php if ( sln_wd_plain_text( $section['description'] ?? '' ) ) : ?>
				<div class="wd-lead wd-lead--center"><?php echo sln_wd_format_content( $section['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
		</header>

		<div class="wd-process__grid" data-wd-process>
			<?php foreach ( $stages as $index => $stage ) : ?>
				<?php $steps = is_array( $stage['steps'] ?? null ) ? $stage['steps'] : array(); ?>
				<article class="wd-process__col wd-animate">
					<div class="wd-process__col-head">
						<div class="wd-process__stage"><?php echo esc_html( $stage['number'] ?? '' ); ?> <?php echo esc_html( $stage['title'] ?? '' ); ?></div>
					</div>
					<?php if ( ! empty( $steps ) ) : ?>
						<ol class="wd-process__steps">
							<?php foreach ( $steps as $step ) : ?>
								<li class="wd-process__step">
									<span class="wd-process__step-num"><?php echo esc_html( $step['number'] ?? '' ); ?></span>
									<div>
										<strong class="wd-process__step-title"><?php echo esc_html( $step['title'] ?? '' ); ?></strong>
										<?php if ( sln_wd_plain_text( $step['description'] ?? '' ) ) : ?>
											<p class="wd-process__step-text"><?php echo esc_html( sln_wd_plain_text( $step['description'] ) ); ?></p>
										<?php endif; ?>
									</div>
								</li>
							<?php endforeach; ?>
						</ol>
					<?php endif; ?>
				</article>
				<?php if ( $index < count( $stages ) - 1 ) : ?>
					<span class="wd-process__connector" aria-hidden="true">
						<svg width="28" height="28" viewBox="0 0 28 28" fill="none"><circle cx="14" cy="14" r="14" fill="currentColor"/><path d="M11 9l5 5-5 5" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</span>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
</section>
