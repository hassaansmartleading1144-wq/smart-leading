<?php
/**
 * AEO page — expected results.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = sln_aeo_get_results_section();
$results = sln_aeo_get_results();
?>

<section class="sln-aeo-section sln-aeo-section--dark sln-aeo-results" aria-labelledby="sln-aeo-results-heading">
	<div class="sls-container sln-aeo-results__grid">
		<div class="sln-aeo-reveal">
			<p class="sln-aeo-eyebrow sln-aeo-eyebrow--light"><?php echo esc_html( $section['eyebrow'] ); ?></p>
			<h2 id="sln-aeo-results-heading" class="sln-aeo-title sln-aeo-title--light"><?php echo esc_html( $section['heading'] ); ?></h2>
			<p class="sln-aeo-lead sln-aeo-lead--light"><?php echo esc_html( sln_aeo_plain_text( $section['description'] ) ); ?></p>
			<ul class="sln-aeo-results__list">
				<?php foreach ( $results as $result ) : ?>
					<li>
						<span class="sln-aeo-results__chk" aria-hidden="true">
							<?php echo sln_aeo_icon( 'tick' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</span>
						<div>
							<strong><?php echo esc_html( $result['title'] ?? '' ); ?></strong>
							<span><?php echo esc_html( sln_aeo_plain_text( $result['description'] ?? '' ) ); ?></span>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>

		<div class="sln-aeo-radar sln-aeo-reveal" aria-hidden="true">
			<div class="sln-aeo-radar__ring sln-aeo-radar__ring--outer">
				<span class="sln-aeo-radar__lbl sln-aeo-radar__lbl--top"><?php esc_html_e( 'SEARCH ENGINES', 'smart-leading-net' ); ?></span>
			</div>
			<div class="sln-aeo-radar__ring sln-aeo-radar__ring--mid">
				<span class="sln-aeo-radar__lbl sln-aeo-radar__lbl--mid"><?php esc_html_e( 'ANSWER ENGINES', 'smart-leading-net' ); ?></span>
			</div>
			<div class="sln-aeo-radar__ring sln-aeo-radar__ring--core">
				<span class="sln-aeo-radar__lbl sln-aeo-radar__lbl--core"><?php esc_html_e( 'YOUR BRAND', 'smart-leading-net' ); ?></span>
			</div>
		</div>
	</div>
</section>
