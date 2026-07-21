<?php
/**
 * Digital Marketing page — The Reality section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = sln_get_dm_reality_section();
$cards   = sln_get_dm_reality_cards();

if ( empty( $cards ) ) {
	return;
}
?>

<section class="sln-dm-section" id="dm-reality" aria-labelledby="sln-dm-reality-heading">
	<div class="sls-container sln-dm-wrap">
		<header class="sln-dm-section__head sln-dm-animate">
			<div class="sln-dm-rule" aria-hidden="true"></div>
			<?php if ( ! empty( $section['small_heading'] ) ) : ?>
				<p class="sln-dm-eyebrow"><?php echo esc_html( $section['small_heading'] ); ?></p>
			<?php endif; ?>
			<h2 id="sln-dm-reality-heading" class="sln-dm-title">
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
			<?php foreach ( $cards as $card ) : ?>
				<?php
				$tag   = ! empty( $card['url'] ) ? 'a' : 'article';
				$attrs = ' class="sln-dm-pcard sln-dm-animate"';
				if ( ! empty( $card['url'] ) ) {
					$attrs .= ' href="' . esc_url( $card['url'] ) . '"';
				}
				$icon_class = 'sln-dm-pcard__icon';
				if ( 'blue' === ( $card['icon_style'] ?? '' ) ) {
					$icon_class .= ' sln-dm-pcard__icon--blue';
				}
				?>
				<<?php echo esc_html( $tag ); ?><?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<div class="<?php echo esc_attr( $icon_class ); ?>" aria-hidden="true">
						<?php
						if ( ! empty( $card['icon_id'] ) && function_exists( 'sln_get_attachment_inline_svg' ) ) {
							echo sln_get_attachment_inline_svg( absint( $card['icon_id'] ), '', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						} else {
							echo esc_html( $card['icon_text'] ?? '!' );
						}
						?>
					</div>
					<div>
						<h3 class="sln-dm-pcard__title"><?php echo esc_html( $card['title'] ?? '' ); ?></h3>
						<?php if ( sln_dm_plain_text( $card['description'] ?? '' ) ) : ?>
							<p class="sln-dm-pcard__text"><?php echo esc_html( sln_dm_plain_text( $card['description'] ) ); ?></p>
						<?php endif; ?>
					</div>
				</<?php echo esc_html( $tag ); ?>>
			<?php endforeach; ?>
		</div>

		<?php if ( ! empty( $section['note_active'] ) && ( ! empty( $section['note_text'] ) || ! empty( $section['note_highlight'] ) ) ) : ?>
			<div class="sln-dm-rule-note sln-dm-animate">
				<p>
					<?php echo esc_html( $section['note_text'] ?? '' ); ?>
					<?php if ( ! empty( $section['note_highlight'] ) ) : ?>
						<strong><?php echo esc_html( $section['note_highlight'] ); ?></strong>
					<?php endif; ?>
				</p>
			</div>
		<?php endif; ?>
	</div>
</section>
