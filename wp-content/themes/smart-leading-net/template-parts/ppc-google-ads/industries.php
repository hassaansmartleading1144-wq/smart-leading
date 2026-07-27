<?php
/**
 * PPC & Google Ads page — industries section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_helpers.php';

$section = sln_get_ppc_industries_section();
$items   = sln_get_ppc_industries_items();

if ( ! sln_ppc_row_is_active( $section ) ) {
	return;
}
?>

<section id="industries" class="sln-ppc-section sln-ppc-grid-bg" aria-labelledby="sln-ppc-industries-heading">
	<div class="sls-container">
		<?php sln_ppc_part_render_heading( $section, 'sln-ppc-industries-heading', true, 'azure' ); ?>

		<?php if ( ! empty( $items ) ) : ?>
			<div class="sln-ppc-industry-grid">
				<?php foreach ( $items as $item ) : ?>
					<article class="sln-ppc-industry sln-ppc-reveal">
						<div class="sln-ppc-industry-icon" aria-hidden="true"><?php echo esc_html( $item['icon_text'] ?? '•' ); ?></div>
						<?php if ( ! empty( $item['title'] ) ) : ?>
							<h3><?php echo esc_html( $item['title'] ); ?></h3>
						<?php endif; ?>
						<?php if ( ! empty( $item['description'] ) ) : ?>
							<p><?php echo esc_html( $item['description'] ); ?></p>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
