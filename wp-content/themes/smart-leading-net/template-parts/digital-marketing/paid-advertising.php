<?php
/**
 * Digital Marketing page — paid advertising channels.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section  = sln_get_dm_ads_section();
$channels = sln_get_dm_ads_channels();

if ( empty( $channels ) ) {
	return;
}
?>

<section class="sln-dm-section sln-dm-section--dark" id="dm-paid-advertising" aria-labelledby="sln-dm-ads-heading">
	<div class="sls-container sln-dm-wrap">
		<header class="sln-dm-section__head sln-dm-animate">
			<div class="sln-dm-rule" aria-hidden="true"></div>
			<?php if ( ! empty( $section['small_heading'] ) ) : ?>
				<p class="sln-dm-eyebrow"><?php echo esc_html( $section['small_heading'] ); ?></p>
			<?php endif; ?>
			<h2 id="sln-dm-ads-heading" class="sln-dm-title sln-dm-title--light">
				<?php
				echo esc_html( $section['main_heading'] ?? '' );
				if ( ! empty( $section['highlighted_text'] ) ) {
					echo ' <span class="sln-dm-hl">' . esc_html( $section['highlighted_text'] ) . '</span>';
				}
				?>
			</h2>
			<?php if ( sln_dm_plain_text( $section['description'] ?? '' ) ) : ?>
				<div class="sln-dm-lead sln-dm-lead--light"><?php echo sln_dm_format_content( $section['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
		</header>

		<div class="sln-dm-chan-grid sln-dm-animate">
			<?php foreach ( $channels as $channel ) : ?>
				<?php
				$tag   = ! empty( $channel['url'] ) ? 'a' : 'div';
				$attrs = ' class="sln-dm-chan"';
				if ( ! empty( $channel['url'] ) ) {
					$attrs .= ' href="' . esc_url( $channel['url'] ) . '"';
				}
				?>
				<<?php echo esc_html( $tag ); ?><?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<div class="sln-dm-chan__chip" aria-hidden="true">
						<?php
						if ( ! empty( $channel['icon_id'] ) && function_exists( 'sln_get_attachment_inline_svg' ) ) {
							echo sln_get_attachment_inline_svg( absint( $channel['icon_id'] ), '', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						} else {
							echo esc_html( $channel['icon_text'] ?? '' );
						}
						?>
					</div>
					<div class="sln-dm-chan__name"><?php echo esc_html( $channel['name'] ?? '' ); ?></div>
					<?php if ( sln_dm_plain_text( $channel['description'] ?? '' ) ) : ?>
						<div class="sln-dm-chan__desc"><?php echo esc_html( sln_dm_plain_text( $channel['description'] ) ); ?></div>
					<?php endif; ?>
				</<?php echo esc_html( $tag ); ?>>
			<?php endforeach; ?>
		</div>
	</div>
</section>
