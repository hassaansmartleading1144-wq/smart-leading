<?php
/**
 * Web Development — Figma why it matters.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = sln_get_wd_matters_section();
$cards   = sln_get_wd_matters_cards();

if ( ! sln_wd_row_is_active( $section ) ) {
	return;
}

$icon_keys = array(
	'easy to find'     => 'search',
	'fast loading'     => 'speed',
	'mobile friendly'  => 'mobile',
	'built to convert' => 'convert',
);
?>

<section class="wd-matters" aria-labelledby="wd-matters-heading">
	<div class="wd-wrap">
		<div class="wd-matters__layout">
			<div class="wd-matters__copy wd-animate">
				<?php if ( ! empty( $section['small_heading'] ) ) : ?>
					<p class="wd-eyebrow"><?php echo esc_html( $section['small_heading'] ); ?></p>
				<?php endif; ?>
				<h2 id="wd-matters-heading" class="wd-title">
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
			</div>

			<?php if ( ! empty( $cards ) ) : ?>
				<div class="wd-matters__cards">
					<?php foreach ( $cards as $index => $card ) : ?>
						<?php
						$title_key = strtolower( (string) ( $card['title'] ?? '' ) );
						$icon_key  = $icon_keys[ $title_key ] ?? 'generic';
						$mod       = ( 1 === $index || 3 === $index ) ? ' wd-matters__card--accent' : '';
						?>
						<article class="wd-matters__card wd-animate<?php echo esc_attr( $mod ); ?>">
							<div class="wd-matters__card-icon" aria-hidden="true">
								<?php sln_wd_render_icon( absint( $card['icon_id'] ?? 0 ), $icon_key ); ?>
							</div>
							<h3 class="wd-matters__card-title"><?php echo esc_html( $card['title'] ?? '' ); ?></h3>
							<?php if ( sln_wd_plain_text( $card['description'] ?? '' ) ) : ?>
								<p class="wd-matters__card-text"><?php echo esc_html( sln_wd_plain_text( $card['description'] ) ); ?></p>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
