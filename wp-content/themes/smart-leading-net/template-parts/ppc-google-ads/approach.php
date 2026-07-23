<?php
/**
 * PPC & Google Ads page — approach section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_helpers.php';

$section = sln_get_ppc_approach_section();
$items   = sln_get_ppc_approach_items();

if ( ! sln_ppc_row_is_active( $section ) ) {
	return;
}
?>

<section class="sln-ppc-section" aria-labelledby="sln-ppc-approach-heading">
	<div class="sls-container">
		<?php sln_ppc_part_render_heading( $section, 'sln-ppc-approach-heading', false, 'azure' ); ?>

		<?php if ( ! empty( $items ) ) : ?>
			<div class="sln-ppc-approach-list">
				<?php foreach ( $items as $item ) : ?>
					<div class="sln-ppc-approach-card sln-ppc-reveal">
						<div class="sln-ppc-approach-problem">
							<span class="sln-ppc-approach-x" aria-hidden="true">✕</span>
							<span class="sln-ppc-approach-problem-text"><?php echo esc_html( $item['problem'] ?? '' ); ?></span>
						</div>
						<div class="sln-ppc-approach-node">
							<span class="sln-ppc-approach-node-num" aria-hidden="true">→</span>
						</div>
						<div class="sln-ppc-approach-solution">
							<span class="sln-ppc-approach-solution-icon" aria-hidden="true">✓</span>
							<span class="sln-ppc-approach-solution-text"><?php echo sln_ppc_format_content( $item['solution'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
