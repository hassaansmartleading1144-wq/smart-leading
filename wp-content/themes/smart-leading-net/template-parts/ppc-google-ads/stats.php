<?php
/**
 * PPC & Google Ads page — stat band.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_helpers.php';

$stats = sln_get_ppc_stats();

if ( empty( $stats ) ) {
	return;
}
?>

<div class="sln-ppc-statband">
	<div class="sln-ppc-statrow" data-sln-ppc-counts>
		<?php foreach ( $stats as $stat ) : ?>
			<?php
			$value    = (float) ( $stat['number'] ?? 0 );
			$decimals = (int) ( $stat['decimals'] ?? 0 );
			$display  = (string) ( $stat['prefix'] ?? '' ) . number_format( $value, $decimals ) . (string) ( $stat['suffix'] ?? '' );
			?>
			<div class="sln-ppc-stat">
				<div class="sln-ppc-stat-value">
					<span class="sln-ppc-count" <?php echo sln_ppc_part_numeric_attrs( $stat, 'number' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $display ); ?></span>
					<?php if ( ! empty( $stat['unit'] ) ) : ?>
						<span class="sln-ppc-stat-unit"><?php echo esc_html( $stat['unit'] ); ?></span>
					<?php endif; ?>
				</div>
				<?php if ( ! empty( $stat['label'] ) ) : ?>
					<div class="sln-ppc-stat-label"><?php echo esc_html( $stat['label'] ); ?></div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
