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
$sln_is_sub  = function_exists( 'sln_ppc_new_design' ) && sln_ppc_new_design();
$sln_usp_ico = $sln_is_sub && function_exists( 'sln_ppc_hero_usp_icons' ) ? sln_ppc_hero_usp_icons() : array();
if ( $sln_is_sub && function_exists( 'sln_ppc_hero_stats' ) ) {
	$metrics     = sln_ppc_hero_stats();
	$sln_usp_ico = wp_list_pluck( $metrics, 'icon' );
}
?>

<section id="top" class="sln-ppc-hero sln-ppc-section sln-ppc-section--dark sln-ppc-grid-bg" aria-labelledby="sln-ppc-hero-heading">
	<span class="sln-ppc-glow sln-ppc-glow--azure" aria-hidden="true"></span>
	<span class="sln-ppc-glow sln-ppc-glow--orange" aria-hidden="true"></span>

	<div class="sls-container">
		<div class="sln-ppc-hero-grid">
			<div class="sln-ppc-reveal">
				<?php $sln_crumbs = function_exists( 'sln_ppc_subpage_breadcrumbs' ) ? sln_ppc_subpage_breadcrumbs() : array(); ?>
				<?php if ( $sln_crumbs ) : ?>
					<nav class="sln-ppc-crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'smart-leading-net' ); ?>">
						<ol>
							<?php foreach ( $sln_crumbs as $crumb ) : ?>
								<li>
									<?php if ( '' !== $crumb['url'] ) : ?>
										<a href="<?php echo esc_url( $crumb['url'] ); ?>"><?php echo esc_html( $crumb['label'] ); ?></a>
									<?php else : ?>
										<span aria-current="page"><?php echo esc_html( $crumb['label'] ); ?></span>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ol>
					</nav>
				<?php endif; ?>
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
					sln_ppc_part_render_button( $hero['primary_button_text'] ?? '', $hero['primary_button_url'] ?? '#contact', 'secondary', true );
					sln_ppc_part_render_button( $hero['secondary_button_text'] ?? '', $hero['secondary_button_url'] ?? '#process', 'white', false );
					?>
				</div>

				<?php ob_start(); ?>
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
				<?php $sln_search_html = ob_get_clean(); ?>
				<?php if ( ! $sln_is_sub ) { echo $sln_search_html; } // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
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

				<div class="sln-ppc-tiles<?php echo $sln_is_sub ? ' sln-ppc-tiles--usp' : ''; ?>" data-sln-ppc-counts>
					<?php foreach ( $metrics as $sln_mi => $metric ) : ?>
						<?php if ( $sln_is_sub ) : ?>
							<div class="sln-ppc-tile sln-ppc-usp">
								<span class="sln-ppc-usp-icon"><?php echo sln_ppc_icon( $sln_usp_ico[ $sln_mi ] ?? 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
								<span class="sln-ppc-usp-num"><span class="sln-ppc-count" <?php echo is_numeric( $metric['value'] ?? null ) ? sln_ppc_part_numeric_attrs( $metric ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( sln_ppc_part_number_display( $metric ) ); ?></span></span>
								<?php if ( ! empty( $metric['label'] ) ) : ?>
									<span class="sln-ppc-usp-text"><?php echo esc_html( $metric['label'] ); ?></span>
								<?php endif; ?>
							</div>
							<?php continue; ?>
						<?php endif; ?>
						<?php
						$display = sln_ppc_part_number_display( $metric );
						$class   = sln_ppc_part_visual_class( 'sln-ppc-tile-value', (string) ( $metric['visual_style'] ?? '' ) );
						?>
						<div class="sln-ppc-tile">
							<?php if ( ! empty( $metric['label'] ) ) : ?>
								<div class="sln-ppc-tile-label"><?php echo esc_html( $metric['label'] ); ?></div>
							<?php endif; ?>
							<div class="<?php echo esc_attr( $class ); ?>">
								<span class="sln-ppc-count" <?php echo is_numeric( $metric['value'] ?? null ) ? sln_ppc_part_numeric_attrs( $metric ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $display ); ?></span>
							</div>
						</div>
					<?php endforeach; ?>

					<?php if ( ! empty( $bars ) && ! $sln_is_sub ) : ?>
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
				<?php if ( $sln_is_sub && function_exists( 'sln_ppc_hero_logos' ) ) : ?>
					<div class="sln-ppc-logos">
						<span class="sln-ppc-logos-label"><?php esc_html_e( 'Platforms we run', 'smart-leading-net' ); ?></span>
						<div class="sln-ppc-logos-row">
							<?php foreach ( sln_ppc_hero_logos() as $sln_li => $sln_logo ) : ?>
								<span class="sln-ppc-logo" style="--i:<?php echo (int) $sln_li; ?>" title="<?php echo esc_attr( $sln_logo['name'] ); ?>">
									<img src="<?php echo esc_url( $sln_logo['src'] ); ?>" alt="<?php echo esc_attr( $sln_logo['name'] ); ?>" width="<?php echo (int) $sln_logo['w']; ?>" height="<?php echo (int) $sln_logo['h']; ?>" loading="lazy" decoding="async">
									<?php if ( ! empty( $sln_logo['caption'] ) ) : ?><em><?php echo esc_html( $sln_logo['caption'] ); ?></em><?php endif; ?>
								</span>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
				<?php if ( $sln_is_sub ) { echo $sln_search_html; } // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
	</div>
</section>
