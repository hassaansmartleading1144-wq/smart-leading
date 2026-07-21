<?php
/**
 * Digital Marketing page — services ("What We Do").
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section  = sln_get_dm_services_section();
$services = sln_get_dm_services_items();

if ( empty( $services ) ) {
	return;
}
?>

<section class="sln-dm-section sln-dm-section--tint" id="dm-services" aria-labelledby="sln-dm-services-heading">
	<div class="sls-container sln-dm-wrap">
		<header class="sln-dm-section__head sln-dm-animate">
			<div class="sln-dm-rule" aria-hidden="true"></div>
			<?php if ( ! empty( $section['small_heading'] ) ) : ?>
				<p class="sln-dm-eyebrow"><?php echo esc_html( $section['small_heading'] ); ?></p>
			<?php endif; ?>
			<h2 id="sln-dm-services-heading" class="sln-dm-title">
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
			<?php foreach ( $services as $service ) : ?>
				<?php
				$icon_class = 'sln-dm-svc__icon';
				if ( 'blue' === ( $service['icon_style'] ?? '' ) ) {
					$icon_class .= ' sln-dm-svc__icon--blue';
				}

				$tag   = ! empty( $service['url'] ) ? 'a' : 'article';
				$attrs = ' class="sln-dm-svc sln-dm-animate"';
				if ( ! empty( $service['url'] ) ) {
					$attrs .= ' href="' . esc_url( $service['url'] ) . '"';
					if ( ! empty( $service['new_tab'] ) ) {
						$attrs .= ' target="_blank" rel="noopener noreferrer"';
					}
				}
				?>
				<<?php echo esc_html( $tag ); ?><?php echo $attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
					<div class="<?php echo esc_attr( $icon_class ); ?>" aria-hidden="true">
						<?php
						if ( ! empty( $service['icon_id'] ) && function_exists( 'sln_get_attachment_inline_svg' ) ) {
							echo sln_get_attachment_inline_svg( absint( $service['icon_id'] ), '', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						} else {
							echo esc_html( $service['icon_text'] ?? '' );
						}
						?>
					</div>
					<h3 class="sln-dm-svc__title"><?php echo esc_html( $service['title'] ?? '' ); ?></h3>
					<?php if ( sln_dm_plain_text( $service['description'] ?? '' ) ) : ?>
						<p class="sln-dm-svc__text"><?php echo esc_html( sln_dm_plain_text( $service['description'] ) ); ?></p>
					<?php endif; ?>
				</<?php echo esc_html( $tag ); ?>>
			<?php endforeach; ?>
		</div>
	</div>
</section>
