<?php
/**
 * PPC & Google Ads page — services/modules section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_helpers.php';

$section = sln_get_ppc_services_section();
$items   = sln_get_ppc_services_items();

if ( ! sln_ppc_row_is_active( $section ) ) {
	return;
}
?>

<section id="services" class="sln-ppc-section sln-ppc-grid-bg" aria-labelledby="sln-ppc-services-heading">
	<div class="sls-container">
		<?php sln_ppc_part_render_heading( $section, 'sln-ppc-services-heading', false, 'azure' ); ?>

		<?php if ( ! empty( $items ) ) : ?>
			<div class="sln-ppc-modules">
				<?php foreach ( $items as $item ) : ?>
					<div class="sln-ppc-module sln-ppc-reveal">
						<div class="sln-ppc-module-head">
							<div class="sln-ppc-module-icon">
								<?php
								$image = '';

								if ( ! empty( $item['icon_id'] ) ) {
									$image = wp_get_attachment_image(
										absint( $item['icon_id'] ),
										'thumbnail',
										false,
										array(
											'class' => 'sln-ppc-module-icon-img',
											'alt'   => '',
										)
									);
								}

								if ( $image ) {
									echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								} elseif ( ! empty( $item['icon_key'] ) ) {
									sln_ppc_part_svg_icon( (string) $item['icon_key'] );
								} else {
									echo esc_html( $item['icon_text'] ?? '•' );
								}
								?>
							</div>

							<?php if ( ! empty( $item['tag'] ) ) : ?>
								<span class="sln-ppc-module-tag"><?php echo esc_html( $item['tag'] ); ?></span>
							<?php endif; ?>
						</div>

						<?php if ( ! empty( $item['title'] ) ) : ?>
							<h3><?php echo esc_html( $item['title'] ); ?></h3>
						<?php endif; ?>
						<?php if ( ! empty( $item['description'] ) ) : ?>
							<p><?php echo esc_html( $item['description'] ); ?></p>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
