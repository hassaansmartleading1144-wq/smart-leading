<?php
/**
 * Digital Marketing page — approach (problem → solution).
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = sln_get_dm_approach_section();
$items   = sln_get_dm_approach_items();

if ( empty( $items ) ) {
	return;
}
?>

<section class="sln-dm-section sln-dm-section--tint" id="dm-approach" aria-labelledby="sln-dm-approach-heading">
	<div class="sls-container sln-dm-wrap">
		<header class="sln-dm-section__head sln-dm-animate">
			<div class="sln-dm-rule" aria-hidden="true"></div>
			<?php if ( ! empty( $section['small_heading'] ) ) : ?>
				<p class="sln-dm-eyebrow"><?php echo esc_html( $section['small_heading'] ); ?></p>
			<?php endif; ?>
			<h2 id="sln-dm-approach-heading" class="sln-dm-title">
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

		<div class="sln-dm-list">
			<?php foreach ( $items as $item ) : ?>
				<?php
				$tag   = ! empty( $item['url'] ) ? 'a' : 'article';
				$attrs = ' class="sln-dm-appcard sln-dm-animate"';
				if ( ! empty( $item['url'] ) ) {
					$attrs .= ' href="' . esc_url( $item['url'] ) . '"';
				}
				?>
				<<?php echo esc_html( $tag ); ?><?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<div class="sln-dm-appcard__prob">
						<span class="sln-dm-appcard__x" aria-hidden="true">✕</span>
						<?php echo esc_html( $item['problem'] ?? '' ); ?>
					</div>
					<div class="sln-dm-appcard__sol">
						<span class="sln-dm-appcard__arrow" aria-hidden="true">→</span>
						<span><?php echo wp_kses_post( $item['solution'] ?? '' ); ?></span>
					</div>
				</<?php echo esc_html( $tag ); ?>>
			<?php endforeach; ?>
		</div>
	</div>
</section>
