<?php
/**
 * AEO page — strategy timeline.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = sln_aeo_get_strategy_section();
$steps   = sln_aeo_get_strategy_steps();
?>

<section class="sln-aeo-section sln-aeo-section--tint" aria-labelledby="sln-aeo-strategy-heading">
	<div class="sls-container">
		<header class="sln-aeo-head sln-aeo-head--center sln-aeo-reveal">
			<p class="sln-aeo-eyebrow"><?php echo esc_html( $section['eyebrow'] ); ?></p>
			<h2 id="sln-aeo-strategy-heading" class="sln-aeo-title"><?php echo esc_html( $section['heading'] ); ?></h2>
			<p class="sln-aeo-lead"><?php echo esc_html( sln_aeo_plain_text( $section['description'] ) ); ?></p>
		</header>

		<ol class="sln-aeo-timeline sln-aeo-reveal">
			<?php foreach ( $steps as $index => $step ) : ?>
				<?php $side = 0 === $index % 2 ? 'left' : 'right'; ?>
				<li class="sln-aeo-timeline__row sln-aeo-timeline__row--<?php echo esc_attr( $side ); ?>">
					<div class="sln-aeo-timeline__card">
						<h3><?php echo esc_html( $step['title'] ?? '' ); ?></h3>
						<p><?php echo esc_html( sln_aeo_plain_text( $step['description'] ?? '' ) ); ?></p>
					</div>
					<span class="sln-aeo-timeline__num" aria-hidden="true"><?php echo esc_html( $step['number'] ?? '' ); ?></span>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
