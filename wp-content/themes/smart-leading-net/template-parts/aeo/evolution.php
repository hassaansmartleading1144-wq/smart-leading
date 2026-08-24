<?php
/**
 * AEO page — search evolution.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$stages = sln_aeo_get_evolution_stages();
?>

<section class="sln-aeo-section sln-aeo-section--tint" aria-labelledby="sln-aeo-evolution-heading">
	<div class="sls-container">
		<header class="sln-aeo-head sln-aeo-head--center sln-aeo-reveal">
			<p class="sln-aeo-eyebrow"><?php esc_html_e( 'Why this exists', 'smart-leading-net' ); ?></p>
			<h2 id="sln-aeo-evolution-heading" class="sln-aeo-title"><?php esc_html_e( 'Search changed shape', 'smart-leading-net' ); ?></h2>
			<p class="sln-aeo-lead"><?php esc_html_e( 'Three ways the same question gets answered today — and where your brand needs to show up in each one.', 'smart-leading-net' ); ?></p>
		</header>

		<div class="sln-aeo-evo sln-aeo-reveal">
			<?php foreach ( $stages as $index => $stage ) : ?>
				<?php if ( $index > 0 ) : ?>
					<div class="sln-aeo-evo__arrow" aria-hidden="true">
						<?php echo sln_aeo_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php endif; ?>
				<article class="sln-aeo-evo__card<?php echo ! empty( $stage['modifier'] ) ? ' sln-aeo-evo__card--' . esc_attr( $stage['modifier'] ) : ''; ?>">
					<div class="sln-aeo-evo__icon" aria-hidden="true">
						<?php echo sln_aeo_icon( $stage['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<span class="sln-aeo-evo__label"><?php echo esc_html( $stage['label'] ); ?></span>
					<h3><?php echo esc_html( $stage['title'] ); ?></h3>
					<p><?php echo esc_html( $stage['description'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
