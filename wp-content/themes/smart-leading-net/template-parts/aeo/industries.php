<?php
/**
 * AEO page — industries.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section    = sln_aeo_get_industries_section();
$industries = sln_aeo_get_industries();
?>

<section class="sln-aeo-section" aria-labelledby="sln-aeo-industries-heading">
	<div class="sls-container">
		<header class="sln-aeo-head sln-aeo-head--center sln-aeo-reveal">
			<p class="sln-aeo-eyebrow"><?php echo esc_html( $section['eyebrow'] ); ?></p>
			<h2 id="sln-aeo-industries-heading" class="sln-aeo-title"><?php echo esc_html( $section['heading'] ); ?></h2>
		</header>

		<div class="sln-aeo-ind-grid sln-aeo-reveal">
			<?php foreach ( $industries as $industry ) : ?>
				<article class="sln-aeo-ind">
					<div class="sln-aeo-ind__icon" aria-hidden="true">
						<?php echo sln_aeo_icon( $industry['icon'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<h3><?php echo esc_html( $industry['title'] ?? '' ); ?></h3>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
