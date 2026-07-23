<?php
/**
 * Digital Marketing page — process (homepage workflow styling).
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = sln_get_dm_process_section();
$steps   = sln_get_dm_process_steps();

if ( empty( $steps ) ) {
	return;
}
?>

<section class="sln-dm-section sln-dm-process" id="dm-process" aria-labelledby="sln-dm-process-heading" style="background-color:#ecf2fc;">
	<div class="sls-container sln-dm-wrap">
		<header class="sln-dm-section__head sln-dm-animate">
			<div class="sln-dm-rule" aria-hidden="true"></div>
			<?php if ( ! empty( $section['small_heading'] ) ) : ?>
				<p class="sln-dm-eyebrow"><?php echo esc_html( $section['small_heading'] ); ?></p>
			<?php endif; ?>
			<h2 id="sln-dm-process-heading" class="sln-dm-title">
				<?php
				echo esc_html( $section['main_heading'] ?? '' );
				if ( ! empty( $section['highlighted_text'] ) ) {
					echo ' <span class="sln-dm-hl">' . esc_html( $section['highlighted_text'] ) . '</span>';
				}
				?>
			</h2>
			<?php if ( sln_dm_plain_text( $section['description'] ?? '' ) ) : ?>
				<div class="sln-dm-lead"><?php echo sln_dm_format_content( $section['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
		</header>

		<div class="workflow__grid sln-dm-process__grid" data-sln-dm-timeline>
			<?php foreach ( $steps as $step ) : ?>
				<?php
				$tag     = ! empty( $step['url'] ) ? 'a' : 'article';
				$bullets = is_array( $step['bullets'] ?? null ) ? $step['bullets'] : array();
				$attrs   = ' class="workflow__card sln-dm-animate"';
				if ( ! empty( $step['url'] ) ) {
					$attrs .= ' href="' . esc_url( $step['url'] ) . '"';
				}
				?>
				<<?php echo esc_html( $tag ); ?><?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<div class="workflow__card-shell">
						<div class="workflow__badge-group" aria-hidden="true">
							<div class="workflow__badge">
								<span class="workflow__badge-number"><?php echo esc_html( $step['number'] ?? '' ); ?></span>
							</div>
							<span class="workflow__badge-pointer"></span>
						</div>
						<div class="workflow__card-body">
							<div class="workflow__card-content">
								<h3 class="workflow__card-title"><?php echo esc_html( $step['title'] ?? '' ); ?></h3>
								<?php if ( ! empty( $bullets ) ) : ?>
									<ul class="sln-dm-process__bullets">
										<?php foreach ( $bullets as $bullet ) : ?>
											<li><?php echo esc_html( $bullet ); ?></li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</<?php echo esc_html( $tag ); ?>>
			<?php endforeach; ?>
		</div>

		<?php if ( sln_dm_plain_text( $section['bottom_note'] ?? '' ) ) : ?>
			<p class="sln-dm-process__note sln-dm-animate"><?php echo esc_html( sln_dm_plain_text( $section['bottom_note'] ) ); ?></p>
		<?php endif; ?>
	</div>
</section>
