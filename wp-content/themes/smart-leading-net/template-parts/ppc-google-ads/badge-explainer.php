<?php
/**
 * PPC sub-pages — trust badge explainer (Google Guaranteed / Google Screened).
 *
 * Explains the badges and Google's verification steps. Never promises
 * approval: Google runs the screening and makes the decision.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_helpers.php';

$data = isset( $args ) && is_array( $args ) ? $args : array();

if ( empty( $data['badges'] ) ) {
	return;
}
?>

<section id="google-guaranteed" class="sln-ppc-section sln-badge" aria-labelledby="sln-badge-heading">
	<div class="sls-container">
		<?php sln_ppc_part_render_heading( $data['section'], 'sln-badge-heading', true, 'azure' ); ?>

		<div class="sln-badge-grid">
			<?php foreach ( $data['badges'] as $i => $badge ) : ?>
				<article class="sln-badge-card sln-ppc-reveal<?php echo 0 === $i ? ' sln-badge-card--main' : ''; ?>" style="--i:<?php echo (int) $i; ?>">
					<div class="sln-badge-seal" aria-hidden="true">
						<svg viewBox="0 0 64 64">
							<circle class="sln-badge-ring" cx="32" cy="32" r="28" pathLength="1"/>
							<path class="sln-badge-check" d="M20 33l8 8 16-17" pathLength="1"/>
						</svg>
					</div>
					<h3><?php echo esc_html( $badge['title'] ); ?></h3>
					<p class="sln-badge-for"><?php echo esc_html( $badge['for'] ); ?></p>
					<ul>
						<?php foreach ( $badge['points'] as $point ) : ?>
							<li><?php echo sln_ppc_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $point ); ?></span></li>
						<?php endforeach; ?>
					</ul>
				</article>
			<?php endforeach; ?>

			<div class="sln-badge-steps sln-ppc-reveal" style="--i:2">
				<h3><?php echo esc_html( $data['steps_title'] ); ?></h3>
				<ol>
					<?php foreach ( $data['steps'] as $s => $step ) : ?>
						<li style="--i:<?php echo (int) $s; ?>">
							<span class="sln-badge-num"><?php echo esc_html( (string) ( $s + 1 ) ); ?></span>
							<span class="sln-badge-step"><b><?php echo esc_html( $step[0] ); ?></b><?php echo esc_html( $step[1] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ol>
				<p class="sln-badge-note"><?php echo sln_ppc_icon( 'shield-check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $data['note'] ); ?></p>
			</div>
		</div>
	</div>
</section>
