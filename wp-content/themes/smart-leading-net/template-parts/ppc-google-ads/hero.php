<?php
/**
 * PPC & Google Ads page — hero section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_helpers.php';

$hero    = sln_get_ppc_hero();
$queries = sln_get_ppc_hero_search_queries();
$metrics = sln_get_ppc_hero_metrics();
$bars    = sln_get_ppc_hero_chart();

if ( ! sln_ppc_row_is_active( $hero ) ) {
	return;
}

$query_strings = array_values(
	array_filter(
		array_map(
			static function ( $row ) {
				return (string) ( $row['query'] ?? '' );
			},
			$queries
		)
	)
);

$first_query = $query_strings[0] ?? '';
?>

<section id="top" class="sln-ppc-hero sln-ppc-section sln-ppc-section--dark sln-ppc-grid-bg" aria-labelledby="sln-ppc-hero-heading">
	<span class="sln-ppc-glow sln-ppc-glow--azure" aria-hidden="true"></span>
	<span class="sln-ppc-glow sln-ppc-glow--orange" aria-hidden="true"></span>

	<div class="sls-container">
		<div class="sln-ppc-hero-grid">
			<div class="sln-ppc-reveal">
				<?php if ( ! empty( $hero['small_heading'] ) ) : ?>
					<span class="sln-ppc-kicker"><?php echo esc_html( $hero['small_heading'] ); ?></span>
				<?php endif; ?>

				<h1 id="sln-ppc-hero-heading" class="sln-ppc-hero-title">
					<?php echo esc_html( $hero['main_heading'] ?? '' ); ?>
					<?php if ( ! empty( $hero['highlighted_text'] ) ) : ?>
						<span class="sln-ppc-highlight--orange"><?php echo esc_html( $hero['highlighted_text'] ); ?></span>
					<?php endif; ?>
				</h1>

				<?php if ( sln_ppc_plain_text( $hero['description'] ?? '' ) ) : ?>
					<div class="sln-ppc-dek"><?php echo sln_ppc_format_content( $hero['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<?php endif; ?>

				<div class="sln-ppc-hero-cta">
					<?php
					sln_ppc_part_render_button( $hero['primary_button_text'] ?? '', $hero['primary_button_url'] ?? '#contact' );
					sln_ppc_part_render_button( $hero['secondary_button_text'] ?? '', $hero['secondary_button_url'] ?? '#process', 'sln-ppc-btn--line', false );
					?>
				</div>

				<?php if ( $first_query || ! empty( $hero['search_label'] ) ) : ?>
					<div
						class="sln-ppc-search"
						data-sln-ppc-search
						data-queries="<?php echo esc_attr( wp_json_encode( $query_strings ) ); ?>"
					>
						<?php if ( ! empty( $hero['search_label'] ) ) : ?>
							<div class="sln-ppc-search-label"><?php echo esc_html( $hero['search_label'] ); ?></div>
						<?php endif; ?>

						<div class="sln-ppc-searchbar">
							<svg class="sln-ppc-search-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
								<circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/>
								<path d="m21 21-4.3-4.3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
							</svg>
							<span class="sln-ppc-search-query"><?php echo esc_html( $first_query ); ?></span>
							<span class="sln-ppc-search-caret" aria-hidden="true"></span>
						</div>

						<?php foreach ( $queries as $query ) : ?>
							<?php if ( ! empty( $query['query'] ) ) : ?>
								<span class="screen-reader-text" data-query><?php echo esc_html( $query['query'] ); ?></span>
							<?php endif; ?>
						<?php endforeach; ?>

						<div class="sln-ppc-search-result sln-ppc-is-visible">
							<div>
								<?php if ( ! empty( $hero['search_result_ad_label'] ) ) : ?>
									<span class="sln-ppc-search-ad"><?php echo esc_html( $hero['search_result_ad_label'] ); ?></span>
								<?php endif; ?>
								<?php if ( ! empty( $hero['search_result_url'] ) ) : ?>
									<span class="sln-ppc-search-url"><?php echo esc_html( $hero['search_result_url'] ); ?></span>
								<?php endif; ?>
							</div>
							<?php if ( ! empty( $hero['search_result_title'] ) ) : ?>
								<div class="sln-ppc-search-title"><?php echo esc_html( $hero['search_result_title'] ); ?></div>
							<?php endif; ?>
							<?php if ( ! empty( $hero['search_result_description'] ) ) : ?>
								<div class="sln-ppc-search-desc"><?php echo esc_html( $hero['search_result_description'] ); ?></div>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<div class="sln-ppc-panel sln-ppc-reveal">
				<div class="sln-ppc-panel-head">
					<?php if ( ! empty( $hero['dashboard_title'] ) ) : ?>
						<span class="sln-ppc-panel-label"><?php echo esc_html( $hero['dashboard_title'] ); ?></span>
					<?php endif; ?>
					<?php if ( ! empty( $hero['live_label'] ) ) : ?>
						<span class="sln-ppc-live"><span class="sln-ppc-live-dot" aria-hidden="true"></span><?php echo esc_html( $hero['live_label'] ); ?></span>
					<?php endif; ?>
				</div>

				<div class="sln-ppc-tiles" data-sln-ppc-counts>
					<?php foreach ( $metrics as $metric ) : ?>
						<?php
						$display = sln_ppc_part_number_display( $metric );
						$class   = sln_ppc_part_visual_class( 'sln-ppc-tile-value', (string) ( $metric['visual_style'] ?? '' ) );
						?>
						<div class="sln-ppc-tile">
							<?php if ( ! empty( $metric['label'] ) ) : ?>
								<div class="sln-ppc-tile-label"><?php echo esc_html( $metric['label'] ); ?></div>
							<?php endif; ?>
							<div class="<?php echo esc_attr( $class ); ?>">
								<span class="sln-ppc-count" <?php echo sln_ppc_part_numeric_attrs( $metric ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $display ); ?></span>
							</div>
						</div>
					<?php endforeach; ?>

					<?php if ( ! empty( $bars ) ) : ?>
						<div class="sln-ppc-chart-card">
							<?php if ( ! empty( $hero['chart_label'] ) ) : ?>
								<div class="sln-ppc-chart-label"><?php echo esc_html( $hero['chart_label'] ); ?></div>
							<?php endif; ?>
							<div class="sln-ppc-bars" data-sln-ppc-bars>
								<?php foreach ( $bars as $bar ) : ?>
									<?php $height = max( 0, min( 100, (int) ( $bar['height'] ?? 62 ) ) ); ?>
									<span
										class="sln-ppc-bar"
										data-h="<?php echo esc_attr( (string) $height ); ?>"
										style="--h: <?php echo esc_attr( (string) $height ); ?>%; height: <?php echo esc_attr( (string) $height ); ?>%;"
										aria-label="<?php echo esc_attr( $bar['label'] ?? '' ); ?>"
									></span>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
