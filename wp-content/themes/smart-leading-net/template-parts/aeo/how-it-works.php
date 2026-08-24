<?php
/**
 * AEO page — how answer engines choose sources.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$nodes = sln_aeo_get_flow_nodes();
?>

<section class="sln-aeo-section sln-aeo-section--tint" id="how-it-works" aria-labelledby="sln-aeo-flow-heading">
	<div class="sls-container">
		<header class="sln-aeo-head sln-aeo-head--center sln-aeo-reveal">
			<p class="sln-aeo-eyebrow"><?php esc_html_e( 'Under the hood', 'smart-leading-net' ); ?></p>
			<h2 id="sln-aeo-flow-heading" class="sln-aeo-title"><?php esc_html_e( 'How answer engines choose what to cite', 'smart-leading-net' ); ?></h2>
			<p class="sln-aeo-lead"><?php esc_html_e( 'A simplified look at the path from a user\'s question to your brand appearing in the answer.', 'smart-leading-net' ); ?></p>
		</header>

		<div class="sln-aeo-flow sln-aeo-reveal">
			<?php foreach ( $nodes as $index => $node ) : ?>
				<?php if ( $index > 0 ) : ?>
					<span class="sln-aeo-flow__line" aria-hidden="true"></span>
				<?php endif; ?>
				<article class="sln-aeo-flow__node<?php echo ! empty( $node['highlight'] ) ? ' sln-aeo-flow__node--hi' : ''; ?>">
					<div class="sln-aeo-flow__circ" aria-hidden="true">
						<?php echo sln_aeo_icon( $node['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<h3><?php echo esc_html( $node['title'] ); ?></h3>
					<p><?php echo esc_html( $node['description'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
