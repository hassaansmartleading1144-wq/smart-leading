<?php
/**
 * AEO page — traditional SEO vs AEO.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = sln_aeo_get_comparison_section();
$rows    = sln_aeo_get_comparison_rows();
?>

<section class="sln-aeo-section" aria-labelledby="sln-aeo-compare-heading">
	<div class="sls-container">
		<header class="sln-aeo-head sln-aeo-head--center sln-aeo-reveal">
			<p class="sln-aeo-eyebrow"><?php echo esc_html( $section['eyebrow'] ); ?></p>
			<h2 id="sln-aeo-compare-heading" class="sln-aeo-title"><?php echo esc_html( $section['heading'] ); ?></h2>
			<p class="sln-aeo-lead"><?php echo esc_html( sln_aeo_plain_text( $section['description'] ) ); ?></p>
		</header>

		<div class="sln-aeo-vs sln-aeo-reveal" role="table" aria-label="<?php esc_attr_e( 'Traditional SEO versus Answer Engine Optimization', 'smart-leading-net' ); ?>">
			<div class="sln-aeo-vs__head" role="row">
				<div class="sln-aeo-vs__cell sln-aeo-vs__cell--a" role="columnheader"><?php echo esc_html( $section['seo_heading'] ); ?></div>
				<div class="sln-aeo-vs__mid" aria-hidden="true"></div>
				<div class="sln-aeo-vs__cell sln-aeo-vs__cell--b" role="columnheader"><?php echo esc_html( $section['aeo_heading'] ); ?></div>
			</div>
			<?php foreach ( $rows as $row ) : ?>
				<div class="sln-aeo-vs__row" role="row">
					<div class="sln-aeo-vs__cell sln-aeo-vs__cell--a" role="cell"><?php echo esc_html( $row['seo'] ?? '' ); ?></div>
					<div class="sln-aeo-vs__mid" aria-hidden="true"><?php esc_html_e( 'vs', 'smart-leading-net' ); ?></div>
					<div class="sln-aeo-vs__cell sln-aeo-vs__cell--b" role="cell"><?php echo esc_html( $row['aeo'] ?? '' ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
