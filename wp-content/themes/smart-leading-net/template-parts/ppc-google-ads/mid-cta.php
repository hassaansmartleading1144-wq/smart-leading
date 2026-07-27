<?php
/**
 * PPC & Google Ads page — mid-page CTA.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_helpers.php';

$cta = sln_get_ppc_mid_cta();

if ( ! sln_ppc_row_is_active( $cta ) ) {
	return;
}
?>

<section class="sln-ppc-section sln-ppc-midcta" aria-label="<?php echo esc_attr( $cta['heading'] ?? __( 'PPC audit call to action', 'smart-leading-net' ) ); ?>">
	<div class="sls-container">
		<div class="sln-ppc-midcta-card sln-ppc-reveal">
			<div>
				<?php if ( ! empty( $cta['heading'] ) ) : ?>
					<h2><?php echo esc_html( $cta['heading'] ); ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $cta['description'] ) ) : ?>
					<p><?php echo esc_html( $cta['description'] ); ?></p>
				<?php endif; ?>
			</div>
			<?php sln_ppc_part_render_button( $cta['button_text'] ?? '', $cta['button_url'] ?? '#contact' ); ?>
		</div>
	</div>
</section>
