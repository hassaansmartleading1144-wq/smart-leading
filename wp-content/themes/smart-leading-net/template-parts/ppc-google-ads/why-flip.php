<?php
/**
 * PPC sub-pages — "Why SLS" as problem → fix flip cards (replaces the table).
 *
 * Each card has two faces in the HTML: the typical-agency problem and the
 * SLS answer. With JS, cards start on the problem side and flip to the
 * answer one by one when scrolled into view; a toggle flips them all.
 * Without JS (or with reduced motion) the SLS face shows, with the problem
 * as a "Instead of" line.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_helpers.php';

$section = sln_get_ppc_why_section();
$rows    = array_values( sln_get_ppc_why_comparison() );
$badges  = sln_get_ppc_why_badges();
$cards   = sln_ppc_why_cards();

if ( ! sln_ppc_row_is_active( $section ) || empty( $rows ) ) {
	return;
}

$them_label = ! empty( $section['left_heading'] ) ? $section['left_heading'] : __( 'Typical Agency', 'smart-leading-net' );
$us_label   = ! empty( $section['right_heading'] ) ? $section['right_heading'] : __( 'Smart Leading Solutions', 'smart-leading-net' );
$three_way  = function_exists( 'sln_ppc_is_hub' ) && sln_ppc_is_hub() && ! empty( $cards[0]['inhouse'] );
$inhouse_lb = __( 'In-House Team', 'smart-leading-net' );
?>

<section id="why" class="sln-ppc-section sln-why" aria-labelledby="sln-ppc-why-heading">
	<span class="sln-why-glow" aria-hidden="true"></span>
	<div class="sls-container">
		<?php sln_ppc_part_render_heading( $section, 'sln-ppc-why-heading', true, 'azure' ); ?>

		<div class="sln-why-wrap" data-sln-why>
			<div class="sln-why-toggle sln-ppc-reveal<?php echo $three_way ? ' sln-why-toggle--three' : ''; ?>" role="group" aria-label="<?php esc_attr_e( 'Compare', 'smart-leading-net' ); ?>" hidden>
				<?php if ( $three_way ) : ?>
					<button type="button" class="sln-why-toggle-btn" data-side="inhouse" aria-pressed="false"><?php echo esc_html( $inhouse_lb ); ?></button>
				<?php endif; ?>
				<button type="button" class="sln-why-toggle-btn" data-side="them" aria-pressed="false"><?php echo esc_html( $them_label ); ?></button>
				<button type="button" class="sln-why-toggle-btn is-on" data-side="us" aria-pressed="true"><?php echo esc_html( $us_label ); ?></button>
				<span class="sln-why-toggle-thumb" aria-hidden="true"></span>
			</div>

			<div class="sln-why-grid">
				<?php foreach ( $rows as $index => $row ) : ?>
					<?php
					$card  = $cards[ $index ] ?? array();
					$title = $card['title'] ?? '';
					$icon  = sln_ppc_icon( $card['icon'] ?? 'check' );
					?>
					<article class="sln-why-card sln-ppc-reveal<?php echo 0 === $index ? ' sln-why-card--feature' : ''; ?>" style="--i:<?php echo (int) $index; ?>">
						<div class="sln-why-inner">
							<div class="sln-why-face sln-why-face--us">
								<span class="sln-why-icon"><?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
								<?php if ( $title ) : ?>
									<h3 class="sln-why-title"><?php echo esc_html( $title ); ?></h3>
								<?php endif; ?>
								<div class="sln-why-text"><?php echo sln_ppc_format_content( $row['sls'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
								<div class="sln-why-instead">
									<span><?php esc_html_e( 'Instead of', 'smart-leading-net' ); ?></span>
									<s><?php echo esc_html( wp_strip_all_tags( $row['typical'] ?? '' ) ); ?></s>
								</div>
							</div>
							<div class="sln-why-face sln-why-face--them" aria-hidden="true">
								<span class="sln-why-them-label sln-why-v-agency"><?php sln_ppc_part_check_icon( 'x' ); ?><?php echo esc_html( $them_label ); ?></span>
								<p class="sln-why-them-text sln-why-v-agency"><?php echo esc_html( wp_strip_all_tags( $row['typical'] ?? '' ) ); ?></p>
								<?php if ( $three_way && ! empty( $card['inhouse'] ) ) : ?>
									<span class="sln-why-them-label sln-why-v-inhouse"><?php sln_ppc_part_check_icon( 'x' ); ?><?php echo esc_html( $inhouse_lb ); ?></span>
									<p class="sln-why-them-text sln-why-v-inhouse"><?php echo esc_html( $card['inhouse'] ); ?></p>
								<?php endif; ?>
								<span class="sln-why-them-hint"><?php esc_html_e( 'Watch how we fix it', 'smart-leading-net' ); ?> →</span>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>

			<?php if ( ! empty( $badges ) ) : ?>
				<div class="sln-why-badges sln-ppc-reveal">
					<?php foreach ( $badges as $badge ) : ?>
						<?php if ( empty( $badge['text'] ) ) : ?>
							<?php continue; ?>
						<?php endif; ?>
						<span class="sln-why-badge"><?php echo sln_ppc_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $badge['text'] ); ?></span>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
