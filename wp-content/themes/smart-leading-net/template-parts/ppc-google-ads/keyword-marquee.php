<?php
/**
 * PPC & Google Ads page — keyword marquee.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$keywords = sln_get_ppc_keyword_marquee();

if ( empty( $keywords ) ) {
	return;
}
?>

<div class="sln-ppc-kwstrip" aria-hidden="true">
	<div class="sln-ppc-kwtrack">
		<?php for ( $i = 0; $i < 2; $i++ ) : ?>
			<?php foreach ( $keywords as $keyword ) : ?>
				<?php if ( empty( $keyword['keyword'] ) ) : ?>
					<?php continue; ?>
				<?php endif; ?>
				<span class="sln-ppc-keyword">
					<span class="sln-ppc-keyword-icon"><?php echo esc_html( $keyword['icon_text'] ?? '⌕' ); ?></span>
					<?php echo esc_html( $keyword['keyword'] ); ?>
				</span>
			<?php endforeach; ?>
		<?php endfor; ?>
	</div>
</div>
