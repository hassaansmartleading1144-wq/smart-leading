<?php
/**
 * AEO page — strategy timeline.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$steps = sln_aeo_get_strategy_steps();
?>

<section class="sln-aeo-section sln-aeo-section--tint" aria-labelledby="sln-aeo-strategy-heading">
	<div class="sls-container">
		<header class="sln-aeo-head sln-aeo-head--center sln-aeo-reveal">
			<p class="sln-aeo-eyebrow"><?php esc_html_e( 'Our approach', 'smart-leading-net' ); ?></p>
			<h2 id="sln-aeo-strategy-heading" class="sln-aeo-title"><?php esc_html_e( 'The AEO strategy, step by step', 'smart-leading-net' ); ?></h2>
			<p class="sln-aeo-lead"><?php esc_html_e( 'A structured process for earning citations inside AI-generated answers — not a repackaged SEO checklist.', 'smart-leading-net' ); ?></p>
		</header>

		<ol class="sln-aeo-timeline sln-aeo-reveal">
			<?php foreach ( $steps as $index => $step ) : ?>
				<?php $side = 0 === $index % 2 ? 'left' : 'right'; ?>
				<li class="sln-aeo-timeline__row sln-aeo-timeline__row--<?php echo esc_attr( $side ); ?>">
					<div class="sln-aeo-timeline__card">
						<h3><?php echo esc_html( $step['title'] ); ?></h3>
						<p><?php echo esc_html( $step['description'] ); ?></p>
					</div>
					<span class="sln-aeo-timeline__num" aria-hidden="true"><?php echo esc_html( $step['number'] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
