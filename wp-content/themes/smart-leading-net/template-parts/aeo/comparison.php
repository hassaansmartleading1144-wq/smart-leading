<?php
/**
 * AEO page — traditional SEO vs AEO.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$rows = sln_aeo_get_comparison_rows();
?>

<section class="sln-aeo-section" aria-labelledby="sln-aeo-compare-heading">
	<div class="sls-container">
		<header class="sln-aeo-head sln-aeo-head--center sln-aeo-reveal">
			<p class="sln-aeo-eyebrow"><?php esc_html_e( 'The shift', 'smart-leading-net' ); ?></p>
			<h2 id="sln-aeo-compare-heading" class="sln-aeo-title"><?php esc_html_e( 'Traditional SEO vs. AEO', 'smart-leading-net' ); ?></h2>
			<p class="sln-aeo-lead"><?php esc_html_e( 'AEO doesn\'t replace SEO — it builds on it for a search landscape where the "result" is often a written answer.', 'smart-leading-net' ); ?></p>
		</header>

		<div class="sln-aeo-vs sln-aeo-reveal" role="table" aria-label="<?php esc_attr_e( 'Traditional SEO versus Answer Engine Optimization', 'smart-leading-net' ); ?>">
			<div class="sln-aeo-vs__head" role="row">
				<div class="sln-aeo-vs__cell sln-aeo-vs__cell--a" role="columnheader"><?php esc_html_e( 'Traditional SEO', 'smart-leading-net' ); ?></div>
				<div class="sln-aeo-vs__mid" aria-hidden="true"></div>
				<div class="sln-aeo-vs__cell sln-aeo-vs__cell--b" role="columnheader"><?php esc_html_e( 'Answer Engine Optimization', 'smart-leading-net' ); ?></div>
			</div>
			<?php foreach ( $rows as $row ) : ?>
				<div class="sln-aeo-vs__row" role="row">
					<div class="sln-aeo-vs__cell sln-aeo-vs__cell--a" role="cell"><?php echo esc_html( $row['seo'] ); ?></div>
					<div class="sln-aeo-vs__mid" aria-hidden="true"><?php esc_html_e( 'vs', 'smart-leading-net' ); ?></div>
					<div class="sln-aeo-vs__cell sln-aeo-vs__cell--b" role="cell"><?php echo esc_html( $row['aeo'] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
