<?php
/**
 * Web Development — Figma statistics strip.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$stats = sln_get_wd_stats();

if ( empty( $stats ) ) {
	return;
}
?>

<section class="wd-stats" aria-label="<?php esc_attr_e( 'Key statistics', 'smart-leading-net' ); ?>">
	<div class="wd-wrap">
		<div class="wd-stats__grid">
			<?php foreach ( $stats as $stat ) : ?>
				<?php
				$display = (string) ( $stat['prefix'] ?? '' ) . (string) ( $stat['value'] ?? '' ) . (string) ( $stat['suffix'] ?? '' );
				?>
				<div class="wd-stats__item wd-animate">
					<div class="wd-stats__num"><?php echo esc_html( $display ); ?></div>
					<div class="wd-stats__label"><?php echo esc_html( $stat['label'] ?? '' ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
