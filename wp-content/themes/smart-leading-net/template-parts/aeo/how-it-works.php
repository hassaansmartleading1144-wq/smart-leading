<?php
/**
 * AEO page — how answer engines choose sources.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = sln_aeo_get_how_section();
$nodes   = sln_aeo_get_flow_nodes();
?>

<section class="sln-aeo-section sln-aeo-section--tint" id="how-it-works" aria-labelledby="sln-aeo-flow-heading">
	<div class="sls-container">
		<header class="sln-aeo-head sln-aeo-head--center sln-aeo-reveal">
			<p class="sln-aeo-eyebrow"><?php echo esc_html( $section['eyebrow'] ); ?></p>
			<h2 id="sln-aeo-flow-heading" class="sln-aeo-title"><?php echo esc_html( $section['heading'] ); ?></h2>
			<p class="sln-aeo-lead"><?php echo esc_html( sln_aeo_plain_text( $section['description'] ) ); ?></p>
		</header>

		<div class="sln-aeo-flow sln-aeo-reveal">
			<?php foreach ( $nodes as $index => $node ) : ?>
				<?php if ( $index > 0 ) : ?>
					<span class="sln-aeo-flow__line" aria-hidden="true"></span>
				<?php endif; ?>
				<article class="sln-aeo-flow__node<?php echo ! empty( $node['highlight'] ) ? ' sln-aeo-flow__node--hi' : ''; ?>">
					<div class="sln-aeo-flow__circ" aria-hidden="true">
						<?php echo sln_aeo_icon( $node['icon'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<h3><?php echo esc_html( $node['title'] ?? '' ); ?></h3>
					<p><?php echo esc_html( sln_aeo_plain_text( $node['description'] ?? '' ) ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
